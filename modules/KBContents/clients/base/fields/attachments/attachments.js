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

({
    /**
     * @inheritdoc
     */
    events: {
        'click [data-action=download-all]': 'startDownloadArchive'
    },

    plugins: ['DragdropAttachments'],

    /**
     * @property {Object} `Select2` object.
     */
    $node: null,

    /**
     * @property {string} Selector for `Select2` dropdown.
     */
    fieldSelector: '',

    /**
     * @property {string} Unique ID for file input.
     */
    cid: null,

    /**
     * @property {string} Selector for file input.
     */
    fileInputSelector: '',

    /**
     * @property {Object} Handlebar object.
     */
    _select2formatSelectionTemplate: null,

    /**
     * Label for `Download all`.
     */
    download_label: '',

    /**
     * @inheritdoc
     */
    initialize: function (opts) {
        var evt = {},
            relate,
            self = this;
        evt['change ' +  this.getFileNode().selector] = 'uploadFile';
        this.events = _.extend({}, this.events, opts.def.events, evt);

        this.fileInputSelector = opts.def.fileinput || '';
        this.fieldSelector = opts.def.field || '';
        this.cid = _.uniqueId('attachment');

        this._super('initialize', [opts]);
        this._select2formatSelectionTemplate = app.template.get('f.attachments.KBContents.selection-partial');

        /**
         * Selects attachments related module.
         */
        if (this.model.id) {
            relate = this.model.getRelatedCollection(this.def.link);
            relate.fetch({
                relate: true,
                success: function() {
                    if (self.disposed === true) {
                        return;
                    }
                    self.render();
                }
            });
        }

        /**
         * Override handling on drop attachment.
         */
        this.before('attachments:drop', this._onAttachmentDrop, this);
    },

    /**
     * @inheritdoc
     */
    format: function (value) {
        return _.map(value, function (item) {
            var forceDownload = !item.isImage,
                mimeType = item.isImage ? 'image' : 'application/octet-stream',
                fileName = item.name.substring(0, item.name.lastIndexOf(".")),
                fileExt = item.name.substring(item.name.lastIndexOf(".") + 1).toLowerCase(),
                urlOpts = {
                    module: this.def.module,
                    id: item.id,
                    field: this.def.modulefield
                };

            fileExt = !_.isEmpty(fileExt) ? '.' + fileExt : fileExt;

            return _.extend(
                {},
                {
                    mimeType: mimeType,
                    fileName: fileName,
                    fileExt: fileExt,
                    url: app.api.buildFileURL(
                        urlOpts,
                        {
                            htmlJsonFormat: false,
                            passOAuthToken: false,
                            cleanCache: true,
                            forceDownload: forceDownload
                        }
                    )
                },
                item
            );
        }, this);
    },

    /**
     * @inheritdoc
     */
    _render: function () {
        if (this.action == 'noaccess') {
            return;
        }
        this.download_label = (this.value && this.value.length > 1) ? 'LBL_DOWNLOAD_ALL' : 'LBL_DOWNLOAD_ONE';
        // Please, do not put this._super call before acl check,
        // due to _loadTemplate function logic from sidecar/src/view.js file
        this._super('_render',[]);

        this.$node = this.$(this.fieldSelector + '[data-type=attachments]');
        this.setSelect2Node();
        if (this.$node.length > 0) {
            this.$node.select2({
                allowClear: true,
                multiple: true,
                containerCssClass: 'select2-choices-pills-close span12 with-padding kb-attachmentlist-details-view',
                tags: [],
                formatSelection: _.bind(this.formatSelection, this),
                width: 'off',
                escapeMarkup: function(m) {
                    return m;
                }
            });
            $(this.$node.data('select2').container).attr('data-attachable', true);
            this.refreshFromModel();
        }
        this._IEDownloadAttributeWorkaroud();
    },

    /**
     * 'Download' attribute workaround for IE browser (which does not support it)
     */
    _IEDownloadAttributeWorkaroud: function () {
        var isIE = /*@cc_on!@*/false || !!document.documentMode;
        var field = "";
        var href = "";
        if (isIE) {
            var downloadFile = function (event) {
                field = this.getAttribute("download");
                href = this.getAttribute("href");
                event.preventDefault();
                var request = new XMLHttpRequest();
                request.addEventListener("load",requestListener, false);
                request.open("get", this, true);
                request.responseType = 'blob';
                request.send();
            }
            var requestListener = function () {
                if (field == "") {
                    field = href;
                }
                var blobObject = this.response;
                window.navigator.msSaveBlob(blobObject, field);
            }
            var items = document.querySelectorAll('a[download], area[download]');
            for (var i = 0; i < items.length; i++) {
                items[i].addEventListener('click', downloadFile, false);
            }
        }
    },

    /**
     *  Update `Select2` data from model.
     */
    refreshFromModel: function () {
        var attachments = [];
        if (this.model.has(this.name)) {
            attachments = this.model.get(this.name);
        }
        this.$node.select2('data', this.format(attachments));
    },

    /**
     * Set `$node` as `Select2` object.
     * Unlink and delete attached notes on remove from select2.
     */
    setSelect2Node: function () {
        var self = this;
        if (!this.$node || this.$node.length == 0) {
            return;
        }
        this.$node.off('select2-removed');
        this.$node.off('select2-opening');

        this.$node.on('select2-removed', function(evt) {
            var note = app.data.createBean('Notes', {id: evt.val});
            note.fetch({
                success: function(model) {
                    // Do nothing with a note of original record.
                    if (!self.model.id && model.get('parent_id')) {
                        return;
                    }
                    model.destroy();
                }
            });
            self.model.set(self.name, _.filter(self.model.get(self.name),
                function(file) {
                    return (file.id !== evt.val);
                }
            ));
            self.render();
        });
        /**
         * Disables dropdown for `Select2`
         */
        this.$node.on('select2-opening', function (evt) {
            evt.preventDefault();
        });

    },

    /**
     * Return file input.
     * @return {Object}
     */
    getFileNode: function () {
        return this.$(this.fileInputSelector + '[data-type=fileinput]');
    },

    /**
     * @inheritdoc
     */
    bindDomChange: function () {
        this.setSelect2Node();
    },

    /**
     * Upload file to server.
     * Create a real note for an attachment to use drag and drop and the file in body.
     * Do not create a related note because the attachment field is enabled on create view.
     */
    uploadFile: function() {
        var self = this,
            $input = this.getFileNode(),
            note = app.data.createBean('Notes'),
            fieldName = 'filename';

        note.save({name: $input[0].files[0].name, portal_flag: true}, {
            success: function(model) {
                // FileApi uses one name for file key and defs.
                var $cloneInput = _.clone($input);
                $cloneInput.attr('name', fieldName);
                model.uploadFile(
                    fieldName,
                    $input,
                    {
                        success: function(rsp) {
                            var att = {};
                            att.id = rsp.record.id;
                            att.isImage = (rsp[fieldName]['content-type'].indexOf('image') !== -1);
                            att.name = rsp[fieldName].name;
                            self.model.set(self.name, _.union([], self.model.get(self.name) || [], [att]));
                            $input.val('');
                            self.render();
                        },
                        error: function(error) {
                            app.alert.show('delete_confirmation', {
                                level: 'error',
                                title: 'LBL_EMAIL_ATTACHMENT_UPLOAD_FAILED',
                                messages: [error.error_message]
                            });
                        }
                    }
                );
            }
        });
    },

    /**
     * Handler for 'attachments:drop' event.
     * This event is triggered when user drops file on the file field.
     *
     * @param {Event} event Drop event.
     * @return {boolean} Returns 'false' to prevent running default behavior.
     */
    _onAttachmentDrop: function(event) {
        event.preventDefault();
        var self = this,
            data = new FormData(),
            fieldName = 'filename';

        _.each(event.dataTransfer.files, function(file) {
            data.append(this.name, file);

            var note = app.data.createBean('Notes');
            note.save({name: file.name}, {
                success: function(model) {
                    var url = app.api.buildFileURL({
                        module: model.module,
                        id: model.id,
                        field: 'filename'
                    }, {htmlJsonFormat: false});
                    data.append('filename', file);
                    data.append('OAuth-Token', app.api.getOAuthToken());

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: data,
                        processData: false,
                        contentType: false,
                        success: function(rsp) {
                            var att = {};
                            att.id = rsp.record.id;
                            att.isImage = (rsp[fieldName]['content-type'].indexOf('image') !== -1);
                            att.name = rsp[fieldName].name;
                            self.model.set(self.name, _.union([], self.model.get(self.name) || [], [att]));
                            self.render();
                        }
                    });
                }
            });
        }, this);

        return false;
    },

    /**
     * Format selection for `Select2` to display.
     * @param {Object} attachment
     * @return {string}
     */
    formatSelection: function (attachment) {
        return this._select2formatSelectionTemplate(attachment);
    },

    /**
     * Download archived files from server.
     */
    startDownloadArchive: function () {
        var params = {
            format:'sugar-html-json',
            link_name: this.def.link,
            platform: app.config.platform
        };
        params[(new Date()).getTime()] = 1;

        // todo: change buildURL to buildFileURL when will be allowed "link" attribute
        var uri = app.api.buildURL(this.model.module, 'file', {
            module: this.model.module,
            id: this.model.id,
            field: this.def.modulefield
        }, params);

        app.api.fileDownload(
            uri,
            {
                error: function (data) {
                    // refresh token if it has expired
                    app.error.handleHttpError(data, {});
                }
            },
            {iframe: this.$el}
        );
    },

    /**
     * @inheritdoc
     *
     * Disposes event listeners on `Select2` object.
     */
    dispose: function () {
        this.$node.off('select2-removed');
        this.$node.off('select2-opening');
        this._super('dispose');
    },

    /**
     * We do not support this field for preview edit
     * @inheritdoc
     */
    _loadTemplate: function() {
        this._super('_loadTemplate');

        if (this.view.name === 'preview') {
            this.template = app.template.getField('attachments', 'detail', this.model.module);
        }
    }
})
