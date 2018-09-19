/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */
/**
 * @class View.Fields.Base.FileField
 * @alias SUGAR.App.view.fields.BaseFileField
 * @extends View.Fields.Base.BaseField
 */
({
    fieldTag: 'input[type=file]',
    supportedImageExtensions: {
        'image/jpeg': 'jpg',
        'image/png': 'png',
        'image/gif': 'gif'
    },
    events: {
        'click [data-action=download]': 'startDownload',
        'click [data-action=delete]': 'deleteFile'
    },
    fileUrl: '',
    plugins: ['File', 'FieldDuplicate'],

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        // FIXME: This needs an API instead. SC-3369 should address this.
        app.error.errorName2Keys['tooBig'] = 'ERROR_MAX_FILESIZE_EXCEEDED';
        app.error.errorName2Keys['uploadFailed'] = 'ERROR_UPLOAD_FAILED';

        if (this.model) {
            this.model.addValidationTask('file_upload_' + this.cid, _.bind(this._doValidateFile, this));
        }
    },

    /**
     * Validator for the file field. If the field is required and has no value,
     * it will fail validation prior to performing a file upload. If there is a
     * value, it will perform a file upload to the temporary folder (required in
     * order to test uploads for files that are potentially larger than
     * `upload_max_filesize` in php.ini).
     *
     * @param {Object} fields The list of fields to validate.
     * @param {Object} errors The errors object during this validation task.
     * @param {Function} callback The callback function to continue validation.
     */
    _doValidateFile: function(fields, errors, callback) {
        var fieldName = this.name,
            $field = this.$(this.fieldTag);

        if ($field.length === 0) {
            callback(null, fields, errors);
            return;
        }

        var val = $field.val();
        if (_.isEmpty(val)) {
            if (this.def.required) {
                errors[fieldName] = errors[fieldName] || {};
                errors[fieldName].required = true;
            }
            callback(null, fields, errors);
            return;
        }

        var ajaxParams = {
            temp: true,
            iframe: true,
            deleteIfFails: false,
            htmlJsonFormat: true
        };

        app.alert.show('upload', {
            level: 'process',
            title: app.lang.get('LBL_UPLOADING'),
            autoclose: false
        });

        this.model.uploadFile(fieldName, $field, {
            success:_.bind(this._doValidateFileSuccess, this, fields, errors, callback),
            error:_.bind(this._doValidateFileError, this, fields, errors, callback)
        }, ajaxParams);
    },

    /**
     * Success callback for the {@link #_doValidateFile} function.
     *
     * @param {Object} fields The list of fields to validate.
     * @param {Object} errors The errors object during this validation task.
     * @param {Function} callback The callback function to continue validation.
     * @param {Object} data File data returned from the successful file upload.
     */
    _doValidateFileSuccess: function(fields, errors, callback, data) {
        app.alert.dismiss('upload');

        var guid = data.record && data.record.id;
        if (!guid) {
            app.logger.error('Temporary file uploaded has no GUID.');
            this._doValidateFileError(fields, errors, callback, data);
            return;
        }

        var fieldName = this.name;
        // Add the guid to the list of fields to set on the model.
        if (!this.model.fields[fieldName + '_guid']) {
            this.model.fields[fieldName + '_guid'] = {
                type: 'file_temp',
                group: fieldName
            };
        }
        this.model.set(fieldName + '_guid', guid);

        // Update filename of the model with the value from response,
        // since it may have been modified on the server side
        this.model.set(fieldName, data.record[fieldName]);

        callback(null, fields, errors);
    },

    /**
     * Error callback for the {@link #_doValidateFile} function.
     *
     * @param {Object} fields The list of fields to validate.
     * @param {Object} errors The errors object during this validation task.
     * @param {Function} callback The callback function to continue validation.
     * @param {Object} resp Error object returned from the API.
     */
    _doValidateFileError: function(fields, errors, callback, resp) {
        app.alert.dismiss('upload');

        var errors = errors || {},
            fieldName = this.name;
        errors[fieldName] = {};

        switch (resp.error) {
            case 'request_too_large':
                errors[fieldName].tooBig = true;
                break;
            default:
                errors[fieldName].uploadFailed = true;
        }
        this.model.unset(fieldName + '_guid');
        callback(null, fields, errors);
    },

    /**
     * @inheritdoc
     */
    _dispose: function() {
        // Remove specific validation task from the model.
        this.model.removeValidationTask('file_upload_' + this.cid);
        this._super('_dispose');
    },

    /**
     * Handler for delete file control
     *
     * Calls api to remove attached file from the model and
     * clear value and shows input[type=file] to upload new file
     * @param {Event} e
     */
    deleteFile: function(e) {
        var self = this;

        if (this.model.isNew()) {
            this.model.unset(this.name);
            if (this.disposed) {
                return;
            }
            this.render();
            return;
        }

        app.alert.show('delete_file_confirmation', {
            level: 'confirmation',
            messages: app.lang.get('LBL_FILE_DELETE_CONFIRM', self.module),
            onConfirm: function() {
                var data = {
                        module: self.module,
                        id: self.model.id,
                        field: self.name
                    },
                    callbacks = {
                        success: function() {
                            self.model.set(self.name, '');
                            self.model.save({}, {
                                //Show alerts for this request
                                showAlerts: {
                                    'process': true,
                                    'success': {
                                        messages: app.lang.get('LBL_FILE_DELETED', self.module)
                                    }
                                },
                                fields: [self.name]
                            });
                            if (self.disposed) {
                                return;
                            }
                            // Because delete button is enabled in edit mode only and
                            // bindDataChange is overrided to prevent rendering field
                            // in edit mode call render method manually
                            self.render();
                        },
                        error: function(data) {
                            // refresh token if it has expired
                            app.error.handleHttpError(data, {});
                        }
                    };
                app.api.file('delete', data, null, callbacks, {htmlJsonFormat: false});
            }
        });
    },

    /**
     * @inheritdoc
     */
    setMode: function(name) {
        if (!_.isEmpty(this._errors)) {
            if (this.action === 'edit') {
                this.clearErrorDecoration();
                this.decorateError(this._errors);
                return;
            }
        }

        this._super('setMode', [name]);
    },

    /**
     * @inheritdoc
     *
     * Override field templates for merge-duplicate view.
     */
    _loadTemplate: function() {
        this._super('_loadTemplate');
        if (this.view.name === 'merge-duplicates') {
            this.template = app.template.getField(this.type,
                'merge-duplicates-' + this.tplName,
                this.module, this.tplName
            ) || app.template.empty;
            this.tplName = 'list';
        }
    },

    /**
     * Handler to refresh field state.
     *
     * Called from {@link app.plugins._onFieldDuplicate}.
     */
    onFieldDuplicate: function() {
        if (this.disposed ||
            this.view.name !== 'merge-duplicates' ||
            this.options.viewName !== 'edit'
        ) {
            return;
        }
        this.render();
    },

    _render: function() {
        // This array will contain objects accessible in the view
        this.model = this.model || this.view.model;
        app.view.Field.prototype._render.call(this);
        return this;
    },
    format: function(value) {
        var attachments = [];
        // Not the same behavior either the value is a string or an array of files
        if (_.isArray(value)) {
            // If it's an array, we get the uri for each files in the response
            _.each(value, function(file) {
                var fileObj = {
                    name: file.name,
                    url: this.formatUri(file.uri)
                };
                attachments.push(fileObj);
            }, this);
        } else if (value) {
            // If it's a string, build the uri with the api library
            var urlOpts = {
                    module: this.module,
                    id: this.model.id,
                    field: this.name
                },
                fileObj = this._createFileObj(value, urlOpts);
            attachments.push(fileObj);
        }
        // Cannot be a hard check against "list" since subpanel-list needs this too
        return attachments;
    },

    /**
     * Creates a file object
     * @param {string} value The file name
     * @param {Object} urlOpts URL options
     * @return {Object} The created file object
     * @return {string} return.name The file name
     * @return {string} return.docType The document type
     * @return {string} return.mimeType The file's MIME type
     * @return {string} return.url The file resource url
     * @private
     */
    _createFileObj: function (value, urlOpts) {
        var isImage = this._isImage(this.model.get('file_mime_type')),
            forceDownload = !isImage,
            mimeType = isImage ? 'image' : '',
            docType = this.model.get('doc_type');
        return {
            name: value,
            mimeType: mimeType,
            docType: docType,
            url: app.api.buildFileURL(urlOpts,
                {
                    htmlJsonFormat: false,
                    passOAuthToken: false,
                    cleanCache: true,
                    forceDownload: forceDownload
                })
        };
    },

    /**
     * This is overridden by portal in order to prepend site url
     * @param {String} uri
     * @return {string} formatted uri
     */
    formatUri: function(uri) {
        return uri;
    },

    startDownload: function(e) {
        var uri = this.$(e.currentTarget).data('url');

        app.api.fileDownload(uri, {
            error: function(data) {
                // refresh token if it has expired
                app.error.handleHttpError(data, {});
            }
        }, {iframe: this.$el});
    },

    /**
     * @inheritdoc
     *
     * Overrides `change` event for file field.
     * We should call `render` method when change event is triggered if:
     * 1. it is not duplicate-merge view and field isn't in edit mode. If it is
     * in edit mode we cannot set a value of a type `file` input.
     * 2. it is duplicate-merge view and field is in edit mode. Because
     * for this view we display file field as label (not input[type=file])
     * in edit mode we should update view on change.
     */
    bindDataChange: function() {
        if (!this.model) {
            return;
        }
        this.model.on('change:' + this.name, function() {
            // Clear any errors on the field if we are changing the value.
            this._errors = {};
            this.clearErrorDecoration();
            if (_.isUndefined(this.options.viewName) || this.options.viewName !== 'edit') {
                this.render();
            }
            // check if other fields want use the name of the file
            if (!_.isUndefined(this.def.populate_list)) {
                _.each(this.def.populate_list, function(field) {
                    if (!this.model.get(field) && app.acl.hasAccessToModel('edit', this.model, field)) {
                        this.model.set(field, this.model.get(this.name));
                    }
                }, this);
            }
        }, this);
    },

    /**
     * @inheritdoc
     *
     * Because input file uses full local path to file as value,
     * value can contains directory names.
     * Unformat value to have file name only in it.
     */
    unformat: function (value) {
        return value.split('/').pop().split('\\').pop();
    },

    /**
     * Check if input mime type is an image or not.
     *
     * @param {String} mime type.
     * @return {Boolean} true if mime type is an image.
     * @private
     */
    _isImage: function(mimeType) {
        return !!this.supportedImageExtensions[mimeType];
    }
})
