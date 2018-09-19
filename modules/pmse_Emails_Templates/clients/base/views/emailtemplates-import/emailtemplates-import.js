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
    initialize: function(options) {
        app.view.View.prototype.initialize.call(this, options);
        this.context.off('emailtemplates:import:finish', null, this);
        this.context.on('emailtemplates:import:finish', this.warnImportEmailTemplates, this);
    },

    /**
     * @inheritdoc
     *
     * Sets up the file field to edit mode
     *
     * @param {View.Field} field
     * @private
     */
    _renderField: function(field) {
        app.view.View.prototype._renderField.call(this, field);
        if (field.name === 'emailtemplates_import') {
            field.setMode('edit');
        }
    },

    warnImportEmailTemplates: function() {
        var that = this;
        if (app.cache.get('show_emailtpl_import_warning')) {
            app.alert.show('emailtpl-import-confirmation', {
                level: 'confirmation',
                messages: app.lang.get('LBL_PMSE_IMPORT_EXPORT_WARNING') +
                '<br/><br/>' + app.lang.get('LBL_PMSE_IMPORT_CONFIRMATION'),
                onConfirm: _.bind(that._onWarnImportEmailTemplatesConfirm, that),
                onCancel: _.bind(that._onWarnImportEmailTemplatesCancel, that)
            });
        } else {
            that.importEmailTemplates();
        }
    },

    /**
     * onConfirm callback for warnImportEmailTemplates alert.
     * Set the cache so the warning isn't sent again and start the import.
     *
     * @private
     */
    _onWarnImportEmailTemplatesConfirm: function() {
        app.cache.set('show_emailtpl_import_warning', false);
        this.importEmailTemplates();
    },

    /**
     * onCancel callback for warnImportEmailTemplates alert.
     * Navigate the user back to where they were before.
     *
     * @private
     */
    _onWarnImportEmailTemplatesCancel: function() {
        app.router.goBack();
    },

    /**
     * Import the Email Templates file (.pet)
     */
    importEmailTemplates: function() {
        var self = this,
            projectFile = $('[name=emailtemplates_import]');

        // Check if a file was chosen
        if (_.isEmpty(projectFile.val())) {
            app.alert.show('error_validation_emailtemplates', {
                level: 'error',
                messages: app.lang.get('LBL_PMSE_EMAIL_TEMPLATES_EMPTY_WARNING', self.module),
                autoClose: false
            });
        } else {
            app.alert.show('upload', {level: 'process', title: 'LBL_UPLOADING', autoclose: false});

            var callbacks =
                {
                    success: _.bind(self._onImportEmailTemplatesSuccess, self),
                    error:  self._onImportEmailTemplatesError
                };

            this.model.uploadFile('emailtemplates_import',
                projectFile,
                callbacks,
                {deleteIfFails: true, htmlJsonFormat: true});
        }
    },

    /**
     * success callback for template import.
     * @param {Object} data: response data.
     *
     * @private
     */
    _onImportEmailTemplatesSuccess: function(data) {
        app.alert.dismiss('upload');
        app.router.goBack();
        app.alert.show('process-import-saved', {
            level: 'success',
            messages: app.lang.get('LBL_PMSE_EMAIL_TEMPLATES_IMPORT_SUCCESS', this.module),
            autoClose: true
        });
    },

    /**
     * error callback for template import.
     * @param {Object} error: response data.
     *
     * @private
     */
    _onImportEmailTemplatesError: function(error) {
        app.alert.dismiss('upload');
        app.alert.show('process-import-saved', {
            level: 'error',
            messages: error.error_message,
            autoClose: false
        });
    }
});
