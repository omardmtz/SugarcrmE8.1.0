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
 * @class View.Fields.Base.EmailAttachmentsField
 * @alias SUGAR.App.view.fields.BaseEmailAttachmentsField
 * @extends View.Fields.Base.BaseField
 */
({
    /**
     * The selector for accessing the Select2 field when in edit mode. The
     * Select2 field is where the attachments are displayed.
     *
     * @property {string}
     */
    fieldTag: 'input.select2',

    /**
     * The selector for accessing the file input field when in edit mode.
     *
     * @property {string}
     */
    _fileTag: 'input[type=file]',

    /**
     * A collection of models that represent promises for an attachment that is
     * being retrieved asynchronously. Each placeholder model's cid can be used
     * to determine which request is associated with that placeholder.
     *
     * @property {Data.BeanCollection}
     */
    _placeholders: null,

    /**
     * Keeps track of active requests so that they can be aborted if the user
     * cancels an action. The key of a request is the unique number of the
     * placeholder associated with that request, so that requests can be
     * singled out by their placeholder and removed from this object without
     * affecting other active requests.
     *
     * @property {Object}
     */
    _requests: null,

    /**
     * @inheritdoc
     *
     * Adds events for uploading a file when the file input changes and
     * downloading a file when a file link is clicked in detail mode.
     *
     * Adds listeners for the `email_attachments:file` and
     * `email_attachments:document` events that are triggered on the view to
     * add attachments. `email_attachments:file` will launch the file picker
     * dialog. `email_attachments:document` accepts a document to attach.
     */
    initialize: function(options) {
        var events = {};

        events['change ' + this._fileTag] = '_uploadFile';
        events['click [data-action=download]'] = '_downloadFile';
        this.events = _.extend({}, this.events, options.def.events, events);
        this.plugins = _.union(this.plugins || [], ['CollectionFieldLoadAll', 'ListEditable']);
        this._super('initialize', [options]);

        this.listenTo(this.view, 'email_attachments:file', this._openFilePicker);
        this.listenTo(this.view, 'email_attachments:document', this._attachDocument);

        this._placeholders = app.data.createBeanCollection('Notes');
        this._requests = {};
    },

    /**
     * @inheritdoc
     *
     * @fires change:<field_name> on the model when placeholder attachments are
     * added or removed. Internally, this causes the field to be rendered with
     * the updated placeholders. And it avoids an expensive full render when
     * in edit mode.
     * @fires <field_name>:under_max_total_bytes on the model when the total
     * bytes of all attachments is under the maximum from the
     * max_aggregate_email_attachments_bytes configuration. The total bytes,
     * maximum allowed bytes, and bytes remaining under the limit (positive)
     * are passed as parameters.
     * @fires <field_name>:over_max_total_bytes on the model when the total
     * bytes of all attachments exceeds the maximum from the
     * max_aggregate_email_attachments_bytes configuration. The total bytes,
     * maximum allowed bytes, and bytes remaining under the limit (negative)
     * are passed as parameters.
     */
    bindDataChange: function() {
        /**
         * Sums the bytes for each attachment in the collection.
         *
         * @param {Data.BeanCollection} attachments
         * @return {int}
         */
        function getTotalBytes(attachments) {
            var bytes;

            if (!(attachments instanceof app.BeanCollection)) {
                return 0;
            }

            bytes = attachments.reduce(function(total, attachment) {
                var fileSize = attachment.get('file_size');

                if (_.isNaN(fileSize) || !_.isNumber(fileSize)) {
                    try {
                        fileSize = parseInt(fileSize, 10) || 0;
                    } catch (err) {
                        // If failed conversion, treat attachment as 0 bytes.
                        fileSize = 0;
                    }
                }

                return total + fileSize;
            }, 0);

            return bytes;
        }

        if (this.model) {
            this.listenTo(this.model, 'change:' + this.name, function() {
                var $el = this.$(this.fieldTag);
                var maxBytes = app.config.maxAggregateEmailAttachmentsBytes;
                var totalBytes;
                var bytesRemaining;

                if (_.isEmpty($el.data('select2'))) {
                    this.render();
                } else {
                    $el.select2('data', this.getFormattedValue());
                }

                totalBytes = getTotalBytes(this.model.get(this.name));
                bytesRemaining = maxBytes - totalBytes;

                if (bytesRemaining > 0) {
                    this.model.trigger(this.name + ':under_max_total_bytes', totalBytes, maxBytes, bytesRemaining);
                } else {
                    this.model.trigger(this.name + ':over_max_total_bytes', totalBytes, maxBytes, bytesRemaining);
                }
            });
        }

        if (this._placeholders) {
            this.listenTo(this._placeholders, 'update', function() {
                this.model.trigger('change:' + this.name);
            });
        }
    },

    /**
     * @inheritdoc
     *
     * Prevents the Select2 dropdown from opening, as the Select2 field is used
     * as a container only.
     *
     * Removes an attachment when an item is removed from the Select2 field.
     */
    bindDomChange: function() {
        var $el = this.$(this.fieldTag);

        $el.on('select2-opening', function(event) {
            event.preventDefault();
        });

        $el.on('select2-removed', _.bind(function(event) {
            this.model.get(this.name).remove(event.val) || this._placeholders.remove(event.val);
        }, this));
    },

    /**
     * Returns `true` if there are any attachments or placeholders.
     * {@link BaseEmailsCreateView} uses this method to determine whether to
     * hide or show the field as it changes.
     *
     * @return {boolean}
     */
    isEmpty: function() {
        return this.model.get(this.name).isEmpty() && this._placeholders.isEmpty();
    },

    /**
     * @inheritdoc
     *
     * Initializes Select2 when in edit mode and disables all but the delete
     * and backspace keys in the Select2 input field.
     */
    _render: function() {
        var $el;
        var select2Input;

        /**
         * Returns `true` when the event occurs for the delete and backspace
         * keys and `false` for all other keys.
         *
         * @param {Object} event DOM event.
         * @return {boolean}
         */
        var isDeleteKey = function(event) {
            return event.keyCode == 8 || event.keyCode == 46;
        };

        this._super('_render');

        $el = this.$(this.fieldTag);

        if ($el.length > 0) {
            $el.select2({
                multiple: true,
                data: this.getFormattedValue(),
                initSelection: _.bind(function(element, callback) {
                    callback(this.getFormattedValue());
                }, this),
                containerCssClass: 'select2-choices-pills-close',
                containerCss: {
                    width: '100%'
                },
                width: 'off',
                /**
                 * Use `cid` as a choice's ID. Some models are not yet synchronized
                 * and can only be identified by their `cid`. All models have a
                 * `cid`.
                 *
                 * See [Select2 Documentation](https://select2.github.io/select2/#documentation).
                 *
                 * @param {Object} choice
                 * @return {null|string|number}
                 */
                id: function(choice) {
                    return _.isEmpty(choice) ? null : choice.cid;
                },
                /**
                 * Formats an attachment object for rendering.
                 *
                 * See [Select2 Documentation](https://select2.github.io/select2/#documentation).
                 *
                 * @param {Object} choice
                 * @return {string}
                 */
                formatSelection: _.bind(function(choice) {
                    var $selection = '<span class="ellipsis-value ellipsis_inline" title="' + choice.name + '">' +
                        choice.name + '</span>';

                    if (this._placeholders.get(choice.cid)) {
                        $selection += '<span class="ellipsis-extra"><i class="fa fa-refresh fa-spin"></i></span>';
                    } else {
                        $selection += '<span class="ellipsis-extra">(' + choice.file_size + ')</span>';
                    }

                    $selection = '<span data-id="' + choice.cid + '">' + $selection + '</span>';

                    return $selection;
                }, this),
                /**
                 * Don't escape a choice's markup since we built the HTML.
                 *
                 * See [Select2 Documentation](https://select2.github.io/select2/#documentation).
                 *
                 * @param {string} markup
                 * @return {string}
                 */
                escapeMarkup: function(markup) {
                    return markup;
                }
            }).select2('val', []);

            select2Input = this.$('.select2-input');

            if (select2Input) {
                select2Input.keypress(isDeleteKey);
                select2Input.keyup(isDeleteKey);
                select2Input.keydown(isDeleteKey);
            }

            if (this.isDisabled()) {
                $el.select2('disable');
            }
        }

        return this;
    },

    /**
     * Returns the file input field.
     *
     * Used for mocking the file input field so that its value can be set
     * programmatically. Stubbing `this.$` for only the parameter
     * `this._fileTag` is not possible; it would cause `this.$` to be stubbed
     * for all calls.
     *
     * @return {jQuery}
     * @private
     */
    _getFileInput: function() {
        return this.$(this._fileTag);
    },

    /**
     * @inheritdoc
     *
     * Select2 expects an array of objects to display. The attachments marked
     * for removal are excluded. The attributes of the remaining attachments
     * and placeholders are returned. Each model's `cid` is returned as that is
     * the `id` that Select2 is using. Each model's `file_url` is returned if
     * the attachment has an `id`. The model's `file_size` is returned as a
     * human-readable string, by way of {@link Utils#getReadableFileSize}.
     */
    format: function(value) {
        var urlAttributes = {
            module: 'Notes',
            field: 'filename'
        };
        var urlOptions = {
            htmlJsonFormat: false,
            passOAuthToken: false,
            cleanCache: true,
            forceDownload: true
        };

        value = value instanceof app.BeanCollection ? value.models : value;
        value = _.map(_.union(value || [], this._placeholders.models), function(model) {
            var attachment = _.extend({cid: model.cid}, model.toJSON());

            attachment.file_url = attachment.id ?
                app.api.buildFileURL(_.extend({}, urlAttributes, {id: attachment.id}), urlOptions) :
                null;
            attachment.file_size = app.utils.getReadableFileSize(attachment.file_size);

            return attachment;
        });

        this.tooltip = _.pluck(value, 'name').join(', ');

        return value;
    },

    /**
     * @inheritdoc
     *
     * Destroys the Select2 element.
     */
    unbindDom: function() {
        this.$(this.fieldTag).select2('destroy');
        this._super('unbindDom');
    },

    /**
     * @inheritdoc
     *
     * Aborts any active requests. Stops listening to events on the view.
     */
    _dispose: function() {
        _.each(this._requests, function(request) {
            if (request && request.uid) {
                app.api.abortRequest(request.uid);
            }
        });

        this._requests = null;
        this.stopListening(this.view);
        this._super('_dispose');
    },

    /**
     * Makes a request to download the file based on the URL identified in the
     * attributes of the current target of the event.
     *
     * @param {Object} event DOM event.
     * @param {Object} event.currentTarget The current target of the event.
     * @private
     */
    _downloadFile: function(event) {
        var url = this.$(event.currentTarget).data('url');

        if (this.disposed === true) {
            return;
        }

        if (!_.isEmpty(url)) {
            app.api.fileDownload(url, {}, {iframe: this.getFieldElement()});
        }
    },

    /**
     * Launches the file picker dialog.
     *
     * @private
     */
    _openFilePicker: function() {
        if (this.disposed === true) {
            return;
        }

        this._getFileInput().click();
    },

    /**
     * Uploads the file selected from the file picker as a temporary file.
     *
     * A placeholder attachment is added to the Select2 field while the file is
     * being uploaded.
     *
     * @private
     */
    _uploadFile: function() {
        var $file = this._getFileInput();
        var ajaxParams = {
            temp: true,
            iframe: true,
            deleteIfFails: true,
            htmlJsonFormat: true
        };
        var note;
        var placeholder;
        var val = $file.val();

        if (_.isEmpty(val)) {
            return;
        }

        placeholder = this._addPlaceholderAttachment(val.split('\\').pop());
        note = app.data.createBean('Notes');
        this._requests[placeholder.cid] = note.uploadFile('filename', $file, {
            success: _.bind(this._handleFileUploadSuccess, this),
            error: _.bind(this._handleFileUploadError, this),
            complete: _.bind(function() {
                // Clear the file input field.
                $file.val(null);
                this._removePlaceholderAttachment(placeholder);
            }, this)
        }, ajaxParams);
    },

    /**
     * Handles a successful response from the API for uploading the file.
     *
     * The relevant data is taken from the record and added as an attachment.
     * An error is shown to the user if the record does not have a GUID.
     *
     * @param {Object} data The data from a successful API response.
     * @param {Object} data.record The record representing the temporary Notes
     * object.
     * @param {string} data.record.id The GUID of the uploaded file.
     * @private
     */
    _handleFileUploadSuccess: function(data) {
        var file;
        var error;

        if (this.disposed === true) {
            return;
        }

        if (!data.record || !data.record.id) {
            error = new Error('Temporary file has no GUID');
            app.logger.error(error.message);
            app.alert.show('upload_error', {
                level: 'error',
                autoClose: true,
                messages: app.lang.get('ERROR_UPLOAD_FAILED')
            });

            // Track errors attaching a file.
            app.analytics.trackEvent('email_attachment', 'upload_error', error);
        } else {
            file = app.data.createBean('Notes', {
                _link: 'attachments',
                filename_guid: data.record.id,
                name: data.record.filename || data.record.name,
                filename: data.record.filename || data.record.name,
                file_mime_type: data.record.file_mime_type,
                file_size: data.record.file_size,
                file_ext: data.record.file_ext
            });
            this.model.get(this.name).add(file, {merge: true});

            // Track attaching a file.
            app.analytics.trackEvent('email_attachment', 'attached_file', file);
        }
    },

    /**
     * Handles an error response from the API for uploading the file.
     *
     * If the error status is 'request_too_large' or 413, then an error is
     * shown to the user indicating that the error was due to exceeding the
     * maximum filesize. Otherwise, the error is handled by the framework.
     *
     * @param {HttpError} error AJAX error.
     * @private
     */
    _handleFileUploadError: function(error) {
        if (this.disposed === true) {
            return;
        }

        // Track errors attaching a file.
        app.analytics.trackEvent('email_attachment', 'upload_error', error);

        if (error && (error.error === 'request_too_large' || error.status == 413)) {
            // Mark the error as having been handled so that it doesn't get
            // handled again.
            error.handled = true;
            app.alert.show(error.error, {
                level: 'error',
                autoClose: true,
                messages: app.lang.get('ERROR_MAX_FILESIZE_EXCEEDED')
            });
        }

        if (_.isFunction(app.api.defaultErrorHandler)) {
            app.api.defaultErrorHandler(error);
        }
    },

    /**
     * Called when the Document selection drawer is closed. If a Document was
     * selected, then the Document is fetched.
     *
     * The Document must be fetched because it is unlikely that the model
     * retrieved for {@link BaseSelectionListView} contains all of the data that
     * is needed. A placeholder attachment is added to the Select2 field while
     * the Document is being retrieved.
     *
     * @param {Data.Bean} doc The selected Document.
     * @private
     */
    _attachDocument: function(doc) {
        var placeholder;
        var placeholderName;

        if (this.disposed === true) {
            return;
        }

        placeholderName = app.utils.getRecordName(doc) || app.lang.getModuleName(doc.module);
        placeholder = this._addPlaceholderAttachment(placeholderName);
        this._requests[placeholder.cid] = doc.fetch({
            success: _.bind(this._handleDocumentFetchSuccess, this),
            error: function(error) {
                // Track errors attaching a document.
                app.analytics.trackEvent('email_attachment', 'doc_error', error);
            },
            complete: _.bind(function() {
                this._removePlaceholderAttachment(placeholder);
            }, this)
        });
    },

    /**
     * Handles a successful response from the API for fetching the Document.
     *
     * The relevant data is taken from the record and added as an attachment.
     *
     * @param {Object} doc The fetched record.
     * @private
     */
    _handleDocumentFetchSuccess: function(doc) {
        var file;

        if (this.disposed === true) {
            return;
        }

        file = app.data.createBean('Notes', {
            _link: 'attachments',
            upload_id: doc.get('document_revision_id'),
            name: doc.get('filename') || doc.get('name'),
            filename: doc.get('filename') || doc.get('name'),
            file_mime_type: doc.get('latest_revision_file_mime_type'),
            file_size: doc.get('latest_revision_file_size'),
            file_ext: doc.get('latest_revision_file_ext'),
            file_source: 'DocumentRevisions'
        });
        this.model.get(this.name).add(file, {merge: true});

        // Track attaching a document.
        app.analytics.trackEvent('email_attachment', 'attached_doc', file);
    },

    /**
     * Adds a placeholder attachment to the Select2 field.
     *
     * @param {string} name The display name for the placeholder attachment.
     * @return {Data.Bean} The placeholder model.
     * @private
     */
    _addPlaceholderAttachment: function(name) {
        return this._placeholders.add({name: name});
    },

    /**
     * Removes a placeholder attachment from the Select2 field and aborts the
     * associated request, if it is active.
     *
     * @param {Data.Bean} placeholder The placeholder model.
     * @private
     */
    _removePlaceholderAttachment: function(placeholder) {
        var request = this._requests[placeholder.cid];

        this._placeholders.remove(placeholder);

        if (request && request.uid) {
            // Abort the request if it is still active.
            app.api.abortRequest(request.uid);
            delete this._requests[placeholder.cid];
        }
    }
})
