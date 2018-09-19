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
        this.context.off("businessrules:import:finish", null, this);
        this.context.on("businessrules:import:finish", this.warnImportBusinessRules, this);
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
        if (field.name === 'businessrules_import') {
            field.setMode('edit');
        }
    },

    warnImportBusinessRules: function () {
        var that = this;
        if (app.cache.get('show_br_import_warning')) {
            app.alert.show('br-import-confirmation', {
                level: 'confirmation',
                messages: app.lang.get('LBL_PMSE_IMPORT_EXPORT_WARNING') + "<br/><br/>"
                    + app.lang.get('LBL_PMSE_IMPORT_CONFIRMATION'),
                onConfirm: function () {
                    app.cache.set('show_br_import_warning', false);
                    that.importBusinessRules();
                },
                onCancel: function () {
                    app.router.goBack();
                }
            });
        } else {
            this.importBusinessRules();
        }
    },

    /**
     * Import the Business Rules file (.pbr)
     */
    importBusinessRules: function() {
        var self = this,
            projectFile = $('[name=businessrules_import]');

        // Check if a file was chosen
        if (_.isEmpty(projectFile.val())) {
            app.alert.show('error_validation_businessrules', {
                level:'error',
                messages: app.lang.get('LBL_PMSE_BUSINESS_RULES_EMPTY_WARNING', self.module),
                autoClose: false
            });
        } else {
            app.alert.show('upload', {level: 'process', title: 'LBL_UPLOADING', autoclose: false});

            var callbacks = {
                success: function (data) {
                    app.alert.dismiss('upload');
                    app.router.goBack();
                    app.alert.show('process-import-saved', {
                        level: 'success',
                        messages: app.lang.get('LBL_PMSE_BUSINESS_RULES_IMPORT_SUCCESS', self.module),
                        autoClose: true
                    });
                },
                error: function (error) {
                    app.alert.dismiss('upload');
                    app.alert.show('process-import-saved', {
                        level: 'error',
                        messages: error.error_message,
                        autoClose: false
                    });
                }
            };

            this.model.uploadFile('businessrules_import', projectFile, callbacks, {deleteIfFails: true, htmlJsonFormat: true});
        }
    }
})
