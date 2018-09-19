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
 * @class View.Fields.Base.AttachmentsField
 * @alias SUGAR.App.view.fields.BaseAttachmentsField
 * @extends View.Fields.Base.BaseField
 * @deprecated Use {@link View.Fields.Base.EmailAttachmentsField} instead.
 */
({
    fieldSelector: '.attachments',
    fileInputSelector: '.fileinput',
    $node: null,
    fileCounter: 0,

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        app.logger.warn('View.Fields.Base.AttachmentsField is deprecated. Use ' +
            'View.Fields.Base.EmailAttachmentsField instead.');

        this.events = _.extend({}, this.events, options.def.events, {
            'change .fileinput': 'uploadFile'
        });
        app.view.Field.prototype.initialize.call(this, options);

        this.context.on('attachment:add', this.addAttachment, this);
        this.context.on('attachment:filepicker:launch', this.launchFilePicker, this);
        this.context.on('attachment:upload:remove', this.removeUploadedAttachment, this);
        this.context.on('attachments:remove-by-tag', this.removeAttachmentsByTag, this);
        this.context.on('attachments:remove-by-id', this.removeAttachmentsById, this);

        // Put id on the context so <label>s can be created elsewhere to trigger this file input
        // This is required to work around an IE issue (only files picked directly or
        // from click on label can be uploaded - not programatically)
        this.fileInputName = 'email_attachment';
        this.context.set('attachment_field_' + this.fileInputName, this.cid);

        this.clearUserAttachmentCache();

        // keep track of active file upload requests so that they can be
        // aborted when the user cancels an in-progress upload
        this.requests = {};
    },

    /**
     * Allow Backspace and Delete Keys for attachments (Select2) and disable all other keys
     * @param e
     * @return {Boolean}
     * @private
     */
    _keyHandler: function(e) {
        // if key is backspace or delete ...
        if ((event.keyCode == 8 || event.keyCode == 46)) {
            return true; // Allow
        }
        return false; // Ignore Any other Keyboard Input
    },

    /**
     * @inheritdoc
     */
    _render: function() {
        var result = app.view.Field.prototype._render.call(this);

        this.$node = this.$(this.fieldSelector);

        this.$node.select2({
            allowClear:          true,
            multiple:            true,
            containerCssClass:   'select2-choices-pills-close',
            containerCss:        {'width':'100%'},
            tags: [],
            formatSelection: this.formatSelection,
            width: 'off',
            escapeMarkup: function(m) { return m; }
        });

        var inp = this.$el.find('.attachments.select2-container .select2-choices .select2-search-field .select2-input');
        if (inp && inp[0]) {
            $(inp[0]).keypress(this._keyHandler);
            $(inp[0]).keyup(this._keyHandler);
            $(inp[0]).keydown(this._keyHandler);
        }

        //handle case where attachments are pre-populated on the model
        this.refreshFromModel();

        return result;
    },

    /**
     * Launch the file input picker.
     */
    launchFilePicker: function() {
        var $fileInput = this.$(this.fileInputSelector);
        $fileInput.click();
    },

    /**
     * Add attachment to the select2 field and update the model explicitly (because select2 does not fire change on add)
     *
     * @param attachment object containing at least guid and nameForDisplay attributes
     */
    addAttachment: function(attachment) {
        this.addAttachmentToContainer(attachment);
        this.updateModel();
    },

    /**
     * Just add the attachment to the container - useful for upload progress items
     * @param attachment
     */
    addAttachmentToContainer: function(attachment) {
        var attachments = this.getDisplayedAttachments();

        if (attachment.replaceId) {
            attachments = _.map(attachments, function(existing) {
                return (existing.id == attachment.replaceId) ? attachment : existing;
            });
            delete attachment.replaceId;
        } else {
            attachments.push(attachment);
        }

        this.setDisplayedAttachments(attachments);
    },

    /**
     * @inheritdoc
     * Update model if attachments are removed (select2-removing event fires when attachment removed)
     * Prevent dropdown from opening on this field (its a container only)
     */
    bindDomChange: function() {
        this.$node = this.$(this.fieldSelector);
        this.$node.on("select2-removing", _.bind(this.handleChange, this));
        this.$node.on("select2-opening", function(event) {
            event.preventDefault();
        });
    },

    /**
     * Before handling any attachment uploads, need to clear the user's attachment cache.
     */
    clearUserAttachmentCache: function() {
        var clearCacheUrl = app.api.buildURL('Mail/attachment', "cache");
        app.api.call('delete', clearCacheUrl);
    },

    /**
     * Format how the attachment should be displayed in the pill
     *
     * @param attachment
     * @return {String}
     */
    formatSelection: function(attachment) {
        var item = '<span data-id="'+attachment.id+'">'+attachment.nameForDisplay+'</span>';
        if (attachment.showProgress) {
            item += ' <i class="fa fa-refresh fa-spin"></i>';
        }
        return item;
    },

    /**
     * Get the attachments displayed in select2
     *
     * @return {array} of attachments
     */
    getDisplayedAttachments: function() {
        return this.$node.select2('data');
    },

    /**
     * Handle change event fired by select2 - this is really just remove attachment events
     * @param event
     */
    handleChange: function(event) {
        if (event && event.choice && event.choice.id) {
            this.removeAttachmentsById(event.choice.id);
        }

        this.updateModel();
        this.notifyAttachmentsChanged();
    },

    /**
     * Fire event when attachment is removed
     * (useful for attachment types that require cleanup)
     *
     * Aborts the associated request if it is still active.
     *
     * @param attachment
     */
    notifyAttachmentRemoved: function(attachment) {
        if (this.requests[attachment.id]) {
            app.api.abortRequest(this.requests[attachment.id]);
        }

        this.context.trigger('attachment:' + attachment.type + ':remove', attachment);
    },

    /**
     * Fire event when attachments displayed has changed
     *
     * @param attachments
     */
    notifyAttachmentsChanged: function(attachments) {
        attachments = attachments || this.getDisplayedAttachments();
        this.context.trigger('attachments:updated', attachments);
    },

    /**
     * Refresh select2 from model
     */
    refreshFromModel: function() {
        var attachments = [];
        if (this.model.has(this.name)) {
            attachments = this.model.get(this.name);
        }
        this.setDisplayedAttachments(attachments);
    },

    /**
     * Remove attachments in list based on a given truth test iterator
     * Removes from select2 and then updates the model
     *
     * @param iterator
     */
    removeAttachmentsByIterator: function(iterator) {
        var attachments = this.getDisplayedAttachments();
        attachments = _.reject(attachments, iterator);
        this.setDisplayedAttachments(attachments);
        this.updateModel();
    },

    /**
     * Remove attachments in list based on a given guid
     *
     * @param id
     */
    removeAttachmentsById: function(id) {
        this.removeAttachmentsByIterator(_.bind(function(attachment) {
            if (attachment.id && attachment.id === id) {
                this.notifyAttachmentRemoved(attachment);
                return true;
            }
        }, this));
    },

    /**
     * Remove attachments in list based on a given tag
     *
     * @param tag
     */
    removeAttachmentsByTag: function(tag) {
        this.removeAttachmentsByIterator(_.bind(function(attachment) {
            if (attachment.tag && attachment.tag === tag) {
                this.notifyAttachmentRemoved(attachment);
                return true;
            }
        }, this));
    },

    /**
     * Remove the given attachment from the server, if there is a problem doing this, no big deal (hence no error alert)
     * @param attachment
     */
    removeUploadedAttachment: function(attachment) {
        var deleteUrl = app.api.buildURL('Mail/attachment', "delete", {id:attachment.id});
        app.api.call('delete', deleteUrl);
    },

    /**
     * Sets the attachments on select2
     */
    setDisplayedAttachments: function(attachments) {
        this.$node.select2('data', attachments);
        this.notifyAttachmentsChanged(attachments);
    },

    /**
     * Update the model from the data stored in select2
     */
    updateModel: function() {
        this.model.set(this.name, this.getDisplayedAttachments());
    },

    /**
     * Returns a File object from the HTML element passed in.
     *
     * @private
     * @param {HTMLElement} el The <input> element containing the file.
     * @return {File} The File object, containing file information.
     */
    _getFileFromInput: function(el) {
        return el.files[0];
    },

    /**
     * Upload the file and define callbacks for success & failure
     */
    uploadFile: function() {
        var $fileInput = this.$(this.fileInputSelector),
            ajaxParams = {
                files: $fileInput,
                iframe: true
            },
            fileId,
            myURL,
            options;

        //don't do anything if user cancels out of picking a file
        if (_.isEmpty(this.getFileInputVal())) {
            return;
        }

        var inputEl = $fileInput.get(0);
        var file = this._getFileFromInput(inputEl);

        if (file.size > app.config.uploadMaxsize) {
            app.alert.show('large_attachment_error', {
                level: 'error',
                messages: app.lang.get('ERROR_MAX_FILESIZE_EXCEEDED')
            });
            return;
        }

        //Notify user of progress uploading by adding a placeholder pill
        this.fileCounter++;
        fileId = 'upload'+this.fileCounter;
        this.addAttachmentToContainer({
            id: fileId,
            nameForDisplay: this.getFileInputVal().split('\\').pop(),
            showProgress: true
        });

        // pass OAuth token as GET-parameter during file upload.
        // otherwise, in case if file is too large, the whole request body may
        // be ignored by interpreter together with the token
        options = {
            format: 'sugar-html-json',
            oauth_token: app.api.getOAuthToken()
        };
        myURL = app.api.buildURL('Mail/attachment', null, null, options);
        var request = app.api.call('create', myURL, null, {
                success: _.bind(function (result) {
                    if (this.disposed === true) return; //if field is already disposed, bail out
                    if (!result.guid) {
                        this.handleUploadError(fileId, result);
                        app.logger.error('Attachment Upload Failed - no guid returned from API');
                        return;
                    }

                    //add attachment to container, replacing placeholder pill from above
                    result.id = result.guid;
                    delete result.guid;
                    result.type = 'upload';
                    result.replaceId = fileId;
                    this.context.trigger('attachment:add', result);
                }, this),

                error: _.bind(function(e) {
                    //if field is already disposed, bail out
                    if (this.disposed === true) {
                        return;
                    }

                    // When a user cancels a file upload, the associated request
                    // is aborted. The error handler is called when a request is
                    // aborted. No error message needs to be shown in this case.
                    if (e && e.errorThrown === 'abort') {
                        return;
                    }

                    this.handleUploadError(fileId, e);
                    app.logger.error('Attachment Upload Failed: ' + e);
                }, this),

                complete: _.bind(function() {
                    // the request is done so there is nothing to cancel
                    // no need to keep track of finished requests
                    delete this.requests[fileId];

                    //clear out the file input so we can detect the next change, even if it is the same file
                    this.clearFileInputVal($fileInput);
                }, this)
            },
            ajaxParams
        );

        // keep track of the request so that it can be aborted when the user cancels the file upload
        if (request) {
            this.requests[fileId] = request.uid;
        }
    },

    /**
     * Retrieve the val from the file input element (return null if not there)
     */
    getFileInputVal: function($fileInput) {
        $fileInput = $fileInput || this.$(this.fileInputSelector);
        if (_.isUndefined($fileInput)) {
            return null;
        }
        return $fileInput.val();
    },

    /**
     * Clear the value of the file input element
     * This is a bit of a hack, but is required for cross-browser (read IE isn't playing nice)
     * FIXME: When we drop IE10 support, change to: $fileInput.val(null);
     *
     * @param $fileInput
     */
    clearFileInputVal: function($fileInput) {
        $fileInput = $fileInput || this.$(this.fileInputSelector);
        if (!_.isUndefined($fileInput)) {
            $fileInput.wrap('<form>').closest('form').get(0).reset();
            $fileInput.unwrap();
        }
    },

    /**
     * When upload fails, display an error alert and remove the placeholder pill
     * @param fileId
     * @param {Object} [error] The error object containing the message to display.
     * @param {string} [error.error_message] The error message to display.
     */
    handleUploadError: function(fileId, error) {
        var message = (error && error.error_message) ? error.error_message : 'LBL_EMAIL_ATTACHMENT_UPLOAD_FAILED';

        this.context.trigger('attachments:remove-by-id', fileId);
        app.alert.show('upload_error', {
            level: 'error',
            messages: message
        });
    },

    /**
     * Turn off re-rendering of field when model changes - let select2 handle how the field looks
     */
    bindDataChange:$.noop,

    _dispose: function() {
        this.$node.select2('destroy');
        app.view.Field.prototype._dispose.call(this);
    }
})
