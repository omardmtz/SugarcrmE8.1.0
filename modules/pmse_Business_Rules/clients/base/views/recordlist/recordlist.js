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
            "list:editbusinessrules:fire": "openBusinessRules",
            "list:exportbusinessrules:fire": "warnExportBusinessRules",
            "list:edit_businessrules:fire": "warnEditBusinessRules",
            "list:deletebusinessrules:fire": "warnDeleteBusinessRules"
        });
        this._super('initialize', [options]);
    },

    openBusinessRules: function(model) {
        var verifyURL = app.api.buildURL(
                'pmse_Project',
                'verify',
                {id: model.get('id')},
                {baseModule: this.module}),
            self = this;
        app.api.call('read', verifyURL, null, {
            success: function(data) {
                if (!data) {
                    app.navigate(this.context, model, 'layout/businessrules');
                } else {
                    app.alert.show('business-rule-design-confirmation',  {
                        level: 'confirmation',
                        messages: App.lang.get('LBL_PMSE_PROCESS_BUSINESS_RULES_EDIT', model.module),
                        onConfirm: function () {
                            app.navigate(this.context, model, 'layout/businessrules');
                        },
                        onCancel: $.noop
                    });
                }
            }
        });
    },

    warnEditBusinessRules: function(model){
        var verifyURL = app.api.buildURL(
                'pmse_Project',
                'verify',
                {id: model.get('id')},
                {baseModule: this.module}),
            self = this;
        app.api.call('read', verifyURL, null, {
            success: function(data) {
                if (!data) {
                    self.toggleRow(model.id, true);
                    self.resize();
                } else {
                    app.alert.show('business-rule-design-confirmation',  {
                        level: 'confirmation',
                        messages: App.lang.get('LBL_PMSE_PROCESS_BUSINESS_RULES_EDIT', model.module),
                        onConfirm: function () {
                            self.toggleRow(model.id, true);
                            self.resize();
                        },
                        onCancel: $.noop
                    });
                }
            }
        });
    },

    warnDeleteBusinessRules: function (model) {
        var verifyURL = app.api.buildURL(
                'pmse_Project',
                'verify',
                {id: model.get('id')},
                {baseModule: this.module}),
            self = this;
        this._modelToDelete = model;
        app.api.call('read', verifyURL, null, {
            success: function(data) {
                if (!data) {
                    self._targetUrl = Backbone.history.getFragment();
                    //Replace the url hash back to the current staying page
                    if (self._targetUrl !== self._currentUrl) {
                        app.router.navigate(self._currentUrl, {trigger: false, replace: true});
                    }

                    app.alert.show('delete_confirmation', {
                        level: 'confirmation',
                        messages: self.getDeleteMessages(model).confirmation,
                        onConfirm: function () {
                            self.deleteModel();
                        },
                        onCancel: function () {
                            self._modelToDelete = null;
                        }
                    });
                } else {
                    app.alert.show('message-id', {
                        level: 'warning',
                        title: app.lang.get('LBL_WARNING'),
                        messages: app.lang.get('LBL_PMSE_PROCESS_BUSINESS_RULES_DELETE', model.module),
                        autoClose: false
                    });
                    self._modelToDelete = null;
                }
            }
        });
    },

    warnExportBusinessRules: function (model) {
        var that = this;
        if (app.cache.get("show_br_export_warning")) {
            app.alert.show('show-br-export-confirmation', {
                level: 'confirmation',
                messages: app.lang.get('LBL_PMSE_IMPORT_EXPORT_WARNING') + "<br/><br/>"
                    + app.lang.get('LBL_PMSE_EXPORT_CONFIRMATION'),
                onConfirm: function() {
                    app.cache.set("show_br_export_warning", false);
                    that.exportBusinessRules(model);
                },
                onCancel: $.noop
            });
        } else {
            that.exportBusinessRules(model);
        }
    },

    exportBusinessRules: function(model) {
        var url = app.api.buildURL(model.module, 'brules', {id: model.id}, {platform: app.config.platform});

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
    }
})
