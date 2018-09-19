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
        this.context.off("project:import:finish", null, this);
        this.context.on("project:import:finish", this.importProject, this);
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
        if (field.name === 'project_import') {
            field.setMode('edit');
        }
    },

    /**
     * Import the Process Definition File (.bpm)
     */
    importProject: function() {
        var self = this,
            projectFile = $('[name=project_import]');

        // Check if a file was chosen
        if (_.isEmpty(projectFile.val())) {
            app.alert.show('error_validation_process', {
                level:'error',
                messages: app.lang.get('LBL_PMSE_PROCESS_DEFINITION_EMPTY_WARNING', self.module),
                autoClose: false
            });
        } else {
            app.alert.show('upload', {level: 'process', title: 'LBL_UPLOADING', autoclose: false});
            var callbacks = {
                    success: function (data) {
                        app.alert.dismiss('upload');
                        var route = app.router.buildRoute(self.module, data.project_import.id);
                        route = route + '/layout/designer';
                        app.router.navigate(route, {trigger: true});
                        app.alert.show('process-import-saved', {
                            level: 'success',
                            messages: app.lang.get('LBL_PMSE_PROCESS_DEFINITION_IMPORT_SUCCESS', self.module),
                            autoClose: true
                        });
                        // Shows warning message if PD contains BR
                        if (data.project_import.br_warning) {
                            app.alert.show('process-import-save-with-br', {
                                level: 'warning',
                                messages: app.lang.get('LBL_PMSE_PROCESS_DEFINITION_IMPORT_BR', self.module),
                                autoClose: false
                            });
                        }
                    },
                    error: function (error) {
                        app.alert.dismiss('upload');
                        app.alert.show('process-import-saved', {
                            level: 'error',
                            messages: error.error_message,
                            autoClose: false
                        });
                    }
                }

            this.model.uploadFile('project_import', projectFile, callbacks, {deleteIfFails: true, htmlJsonFormat: true});
        }
    }
})
