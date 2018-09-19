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
(function(app) {
    app.events.on('app:init', function() {
        /**
         * Adds ability to override TinyMCE default file upload functionality via the EmbeddedFiles module and fileAPI.
         * Just override the TinyMCE file_browser_callback function with the tinyMCEFileBrowseCallback method.
         * Once attached the plugin creates a hidden input to upload files.
         */
        app.plugins.register('Tinymce', ['field'], {

            /**
             * File input element.
             */
            $embeddedInput: null,

            /**
             * Name of file input.
             */
            fileFieldName: null,

            /**
             * @inheritdoc
             */
            onAttach: function(component) {
                var self = this;
                component.on('init', function() {
                    this.fileFieldName = component.options.def.name + '_file';
                    this.$embeddedInput = $('<input />', {name: this.fileFieldName, type: 'file'}).hide();
                }, this);
                component.on('render', function() {
                    component.$el.append(self.$embeddedInput);
                }, this);
            },

            /**
             * @inheritdoc
             */
            onDetach: function(component) {
                this.$embeddedInput.remove();
            },

            /**
             * Handle embedded file upload process.
             *
             * This callaback creates new EmbeddedFile object, so this module should be present in SugarCRM.
             * If there is no EmbeddedFile module, this method does nothing.
             *
             * To enable the usage of embeded files in tinymce you need to specify 'file_browser_callback'.
             * See [TinyMCE documentation](http://www.tinymce.com/wiki.php/Configuration:file_browser_callback)
             *
             * Example:
             *
             * config.file_browser_callback = _.bind(this.tinyMCEFileBrowseCallback, this);
             *
             * @param {string} fieldName The name (and ID) of the dialogue window's input field.
             * @param {string} url Carries the existing link URL if you modify a link.
             * @param {string} type Either 'image', 'media' or 'file'.
             * (called respectively from image plugin, media plugin and link plugin insert/edit dialogs).
             * @param {Object} win A reference to the dialogue window itself.
             */
            tinyMCEFileBrowseCallback: function(fieldName, url, type, win) {

                if (_.isUndefined(app.metadata.getModule('EmbeddedFiles'))) {
                    return;
                }

                var attributes = {
                    fieldName: fieldName,
                    type: type,
                    win: win
                };

                this.$embeddedInput.unbind().change(_.bind(this._onEmbededFile, this, attributes));
                this.$embeddedInput.trigger('click');
            },

            /**
             * Handler called when user chooses file to upload.
             *
             * @param {Object} attributes
             * @param {string} attributes.fieldName The name (and ID) of the dialogue window's input field.
             * @param {string} attributes.type Either 'image', 'media' or 'file'
             * @param {string} attributes.win A reference to the dialogue window itself.
             * @param {Event} event Dom event.
             * @private
             */
            _onEmbededFile: function(attributes, event) {
                var $target = $(event.target),
                    fileObj = $target[0].files[0];

                if (attributes.type === 'image' && fileObj.type.indexOf('image') === -1) {
                    this.clearFileInput($target);
                    tinymce.activeEditor.windowManager.alert(app.lang.get('LBL_UPLOAD_ONLY_IMAGE', 'EmbeddedFiles'));
                    return;
                }

                var embeddedFile = app.data.createBean('EmbeddedFiles');
                embeddedFile.save({name: fileObj.name}, {
                    success: _.bind(this._saveEmbededFile, this, attributes)
                });
            },

            /**
             * Handler to save new embeded file.
             *
             * @param {Object} attributes
             * @param {string} attributes.fieldName The name (and ID) of the dialogue window's input field.
             * @param {string} attributes.win A reference to the dialogue window itself.
             * @param {EmbeddedFile} model Model to save.
             * @private
             */
            _saveEmbededFile: function(attributes, model) {
                model.uploadFile(
                    this.fileFieldName,
                    this.$embeddedInput,
                    {
                        success: _.bind(function(rsp) {
                            var forceDownload = !(rsp[this.fileFieldName]['content-type'].indexOf('image') !== -1);
                            var url = app.api.buildFileURL(
                                {
                                    module: 'EmbeddedFiles',
                                    id: rsp.record.id,
                                    field: this.fileFieldName
                                },
                                {
                                    htmlJsonFormat: false,
                                    passOAuthToken: false,
                                    cleanCache: true,
                                    forceDownload: forceDownload
                                }
                            );

                            $(attributes.win.document).find('#' + attributes.fieldName).val(url);

                            if (attributes.type === 'image') {
                                // We are, so update image dimensions.
                                this.updateImageData(url);
                            }

                            this.clearFileInput(this.$embeddedInput);
                        }, this),
                        error: _.bind(function() {
                            app.alert.show('upload-error', {
                                level: 'error',
                                messages: 'ERROR_UPLOAD_FAILED'
                            });
                            this.clearFileInput(this.$embeddedInput);
                        }, this)
                    }
                );
            },

            /**
             * Clears input file value.
             *
             * @param {Object} $field Jquery input selector.
             */
            clearFileInput: function($field) {
                $field.val('');
                // For IE.
                $field.replaceWith($field.clone(true));
            },

            /**
             * Updates image data such as dimensions for example.
             *
             * @param {string} url Uploaded image url.
             */
            updateImageData: function(url) {
                var win = tinymce.activeEditor.windowManager.windows[0];
                win.find('#src').value(url).fire('change');
            }
        });
    });
})(SUGAR.App);
