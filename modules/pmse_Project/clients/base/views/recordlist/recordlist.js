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
    extendsFrom: 'RecordlistView',

    /**
     * @override
     * @param {Object} options
     */
    initialize: function(options) {
        this.contextEvents = _.extend({}, this.contextEvents, {
            "list:opendesigner:fire": "openDesigner",
            "list:exportprocess:fire": "showExportingWarning",
            "list:enabledDisabledRow:fire": "enableDisableProcess"
        });

        this._super('initialize', [options]);
    },

    openDesigner: function(model) {
        var verifyURL = app.api.buildURL(
                this.module,
                'verify',
                {
                    id : model.get('id')
                }
            ),
            self = this;
        app.api.call('read', verifyURL, null, {
            success: function(data) {
                if (!data) {
                    app.navigate(this.context, model, 'layout/designer');
                } else {
                    app.alert.show('project-design-confirmation',  {
                        level: 'confirmation',
                        messages: App.lang.get('LBL_PMSE_PROCESS_DEFINITIONS_EDIT', model.module),
                        onConfirm: function () {
                            app.navigate(this.context, model, 'layout/designer');
                        },
                        onCancel: $.noop
                    });
                }
            }
        });
    },

    showExportingWarning: function (model) {
        var that = this;
        if (app.cache.get("show_project_export_warning")) {
            app.alert.show('project-export-confirmation',  {
                level: 'confirmation',
                messages: App.lang.get('LBL_PMSE_IMPORT_EXPORT_WARNING') + "<br/><br/>"
                    + app.lang.get('LBL_PMSE_EXPORT_CONFIRMATION'),
                onConfirm: function () {
                    app.cache.set("show_project_export_warning", false);
                    that.exportProcess(model);
                },
                onCancel: $.noop
            });
        } else {
            that.exportProcess(model);
        }
    },

    exportProcess: function(model) {
        var url = app.api.buildURL(model.module, 'dproject', {id: model.id}, {platform: app.config.platform});

        if (_.isEmpty(url)) {
            app.logger.error('Unable to get the Project download uri.');
            return;
        }

        app.api.fileDownload(url, {
            error: function(data) {
                // refresh token if it has expired
                app.error.handleHttpError(data, {});
            }
        }, {iframe: this.$el});
    },
    enabledProcess: function(model) {
        var self = this;
        var name = model.get('name') || '';
        app.alert.show(model.get('id') + ':deleted', {
            level: 'confirmation',
            messages: app.utils.formatString(app.lang.get('LBL_PRO_ENABLE_CONFIRMATION', model.module),[name.trim()]),
            onConfirm: function() {
                self._updateProStatusEnabled(model);
            }
        });
    },
    _showSuccessAlert: function () {
        app.alert.show("data:sync:success", {
            level: "success",
            messages: App.lang.get('LBL_RECORD_SAVED'),
            autoClose: true
        });
    },
    _updateProStatusEnabled: function(model) {
        model.set('prj_status', 'ACTIVE');
        model.save();
        this._showSuccessAlert();
    },
    disabledProcess: function(model) {
        var self = this;
        var name = model.get('name') || '';

        var verifyURL = app.api.buildURL(
                this.module,
                'verify',
                {
                    id : model.get('id')
                }
            );
        app.api.call('read', verifyURL, null, {
            success: function(data) {
                if (!data) {
                    app.alert.show('project_disable', {
                        level: 'confirmation',
                        messages: app.utils.formatString(app.lang.get('LBL_PRO_DISABLE_CONFIRMATION', model.module),[name.trim()]),
                        onConfirm: function() {
                            self._updateProStatusDisabled(model);
                        }
                    });
                } else {
                    app.alert.show('project-disable-confirmation',  {
                        level: 'confirmation',
                        messages: App.lang.get('LBL_PMSE_DISABLE_CONFIRMATION_PD', model.module),
                        onConfirm: function () {
                            self._updateProStatusDisabled(model);
                        },
                        onCancel: $.noop
                    });
                }
            }
        });
    },
    _updateProStatusDisabled: function(model) {
        model.set('prj_status', 'INACTIVE');
        model.save();
        this._showSuccessAlert();
    },
    enableDisableProcess: function (model) {
        var status = model.get("prj_status");
        if (status === 'ACTIVE') {
            this.disabledProcess(model);
        } else {
            this.enabledProcess(model);
        }
    },
    getDeleteMessages: function(model) {
        var messages = {};
        var name = Handlebars.Utils.escapeExpression(app.utils.getRecordName(model)).trim();
        var context = app.lang.getModuleName(model.module).toLowerCase() + ' ' + name;

        messages.confirmation = app.utils.formatString(app.lang.get('NTC_DELETE_CONFIRMATION_FORMATTED'), [context]);
        messages.success = app.utils.formatString(app.lang.get('NTC_DELETE_SUCCESS'), [context]);
        return messages;
    },
    deleteModel: function() {
        var self = this,
            model = this._modelToDelete;

        model.destroy({

            //Show alerts for this request
            showAlerts: {
                'process': true,
                'success': {
                    messages: self.getDeleteMessages(model).success
                }
            },
            success: function() {
                var redirect = self._targetUrl !== self._currentUrl;
                self._modelToDelete = null;
                self.collection.remove(model, { silent: redirect });
                if (redirect) {
                    self.unbindBeforeRouteDelete();
                    //Replace the url hash back to the current staying page
                    app.router.navigate(self._targetUrl, {trigger: true});
                    return;
                }
                app.events.trigger("preview:close");
                if (!self.disposed) {
                    self.render();
                }

                self.layout.trigger("list:record:deleted", model);
            }
        });
    },
    warnDelete: function(model) {
        var verifyURL = app.api.buildURL(
                this.module,
                'verify',
                {
                    id : model.get('id')
                }
            ),
            self = this;
        app.api.call('read', verifyURL, null, {
            success: function(data) {
                if (!data) {
                    namePd = Handlebars.Utils.escapeExpression(app.utils.getRecordName(model)).trim();
                    if ( (namePd !== '') && (app.lastNamePdDel !== namePd) ) {
                        self._targetUrl = Backbone.history.getFragment();
                        //Replace the url hash back to the current staying page
                        if (self._targetUrl !== self._currentUrl) {
                            app.router.navigate(self._currentUrl, {trigger: false, replace: true});
                        }
                        app.alert.show('delete_confirmation', {
                            level: 'confirmation',
                            messages: self.getDeleteMessages(model).confirmation,
                            onConfirm: function() {
                                self._modelToDelete = model;
                                self.deleteModel();
                                app.lastNamePdDel = namePd;
                            }
                        });
                    }
                } else {
                    app.alert.show('message-id', {
                        level: 'warning',
                        title: app.lang.get('LBL_WARNING'),
                        messages: app.lang.get('LBL_PA_PRODEF_HAS_PENDING_PROCESSES'),
                        autoClose: false
                    });
                }
            }
        });
    }
})
