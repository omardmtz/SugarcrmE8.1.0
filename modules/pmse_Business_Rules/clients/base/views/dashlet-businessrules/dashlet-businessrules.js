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
    extendsFrom: 'TabbedDashletView',

    /**
     * @inheritdoc
     *
     * @property {Number} _defaultSettings.limit Maximum number of records to
     *   load per request, defaults to '10'.
     * @property {String} _defaultSettings.visibility Records visibility
     *   regarding current user, supported values are 'user' and 'group',
     *   defaults to 'user'.
     */
    _defaultSettings: {
        limit: 10,
        visibility: 'user'
    },

    thresholdRelativeTime: 2, //Show relative time for 2 days and then date time after

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        options.meta = options.meta || {};
        options.meta.template = 'tabbed-dashlet';

        this.plugins = _.union(this.plugins, [
            'LinkedModel'
        ]);

        this._super('initialize', [options]);
    },

    /**
     * @inheritdoc
     */
    _initEvents: function() {
        this._super('_initEvents');
        this.on('dashlet-businessrules:businessRulesLayout:fire', this.businessRulesLayout, this);
        this.on('dashlet-businessrules:delete-record:fire', this.deleteRecord, this);
        this.on('dashlet-businessrules:download:fire', this.warnExportBusinessRules, this);
        this.on('dashlet-businessrules:description-record:fire', this.descriptionRecord, this);
        this.on('linked-model:create', this.loadData, this);
        return this;
    },

    /**
     * Re-fetches the data for the context's collection.
     *
     * FIXME: This will be removed when SC-4775 is implemented.
     *
     * @private
     */
    _reloadData: function() {
        this.context.set('skipFetch', false);
        this.context.reloadData();
    },

    /**
     * Fire dessigner
     */
    businessRulesLayout: function (model) {
        var redirect = model.module+"/"+model.id+"/layout/businessrules";
        var verifyURL = app.api.buildURL(
                'pmse_Project',
                'verify',
                {id: model.get('id')},
                {baseModule: this.module}),
            self = this;
        app.api.call('read', verifyURL, null, {
            success: function(data) {
                if (!data) {
                    app.router.navigate(redirect , {trigger: true, replace: true });
                } else {
                    app.alert.show('business-rule-design-confirmation',  {
                        level: 'confirmation',
                        messages: App.lang.get('LBL_PMSE_PROCESS_BUSINESS_RULES_EDIT', model.module),
                        onConfirm: function () {
                            app.router.navigate(redirect , {trigger: true, replace: true });
                        },
                        onCancel: $.noop
                    });
                }
            }
        });
    },

    /**
     * @inheritdoc
     *
     * FIXME: This should be removed when metadata supports date operators to
     * allow one to define relative dates for date filters.
     */
    _initTabs: function() {
        this._super('_initTabs');

        // FIXME: since there's no way to do this metadata driven (at the
        // moment) and for the sake of simplicity only filters with 'date_due'
        // value 'today' are replaced by today's date
        var today = new Date();
        today.setHours(23, 59, 59);
        today.toISOString();

        _.each(_.pluck(_.pluck(this.tabs, 'filters'), 'date_due'), function(filter) {
            _.each(filter, function(value, operator) {
                if (value === 'today') {
                    filter[operator] = today;
                }
            });
        });
    },

    /**
     * Create new record.
     *
     * @param {Event} event Click event.
     * @param {String} params.layout Layout name.
     * @param {String} params.module Module name.
     */
    createRecord: function(event, params) {
        if (this.module !== 'pmse_Business_Rules') {
            this.createRelatedRecord(params.module, params.link);
        } else {
            var self = this;
            app.drawer.open({
                layout: 'create',
                context: {
                    create: true,
                    module: params.module
                }
            }, function(context, model) {
                if (!model) {
                    return;
                }
                self.context.resetLoadFlag();
                self.context.set('skipFetch', false);
                if (_.isFunction(self.loadData)) {
                    self.loadData();
                } else {
                    self.context.loadData();
                }
            });
        }

    },
    importRecord: function(event, params) {
        App.router.navigate(params.link , {trigger: true, replace: true });
    },

    /**
     * Delete record.
     *
     * @param {Event} event Click event.
     * @param {String} params.layout Layout name.
     * @param {String} params.module Module name.
     */
    deleteRecord: function(model) {
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
                    app.alert.show('delete_confirmation', {
                        level: 'confirmation',
                        messages: app.utils.formatString(app.lang.get('LBL_PRO_DELETE_CONFIRMATION', model.module)),
                        onConfirm: function () {
                            model.destroy({
                                showAlerts: true,
                                success: self._getRemoveRecord()
                            });
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

    /**
     * Updating in fields delete removed
     * @return {Function} complete callback
     * @private
     */
    _getRemoveRecord: function() {
        return _.bind(function(model){
            if (this.disposed) {
                return;
            }
            this.collection.remove(model);
            this.render();
            this.context.trigger("tabbed-dashlet:refresh", model.module);
        }, this);
    },

    /**
     * Method view alert in process with text modify
     * show and hide alert
     */
    _refresh: function(model, status) {
        app.alert.show(model.id + ':refresh', {
            level:"process",
            title: status,
            autoclose: false
        });
        return _.bind(function(model){
            var options = {};
            this.layout.reloadDashlet(options);
            app.alert.dismiss(model.id + ':refresh');
        }, this);
    },

    /**
     * Disable record.
     *
     * @param {Event} event Click event.
     * @param {String} params.layout Layout name.
     * @param {String} params.module Module name.
     */
    disableRecord: function(model) {
        var self = this;
        this._modelToDelete = true;
        var name = model.get('name') || '';
        app.alert.show(model.get('id') + ':deleted', {
            level: 'confirmation',
            messages: app.utils.formatString(app.lang.get('LBL_PRO_DISABLE_CONFIRMATION', model.module),[name.trim()]),
            onConfirm: function() {
                self._updateProStatusDisabled(model);
            }
        });
    },

    /**
     * Update record in table pmse_Project in fields prj_status by INACTIVE
     */
    _updateProStatusDisabled: function(model) {
        var self = this;
        url = App.api.buildURL(model.module, null, {id: model.id});
        attributes = {prj_status: 'INACTIVE'};
        App.api.call('update', url, attributes, {
            success: self._refresh(model, app.lang.get('LBL_PRO_DISABLE', model.module)),
            error: function (err) {
//                app.error.handleHttpError(err); if (callback) callback(err);
//                self.isWaitingResponse = false;
//                self.mergeDirtyElements();
//                self.isDirty = false;
            }
        });
    },

    /**
     * Enable record.
     *
     * @param {Event} event Click event.
     * @param {String} params.layout Layout name.
     * @param {String} params.module Module name.
     */
    enableRecord: function(model) {
        var self = this;
        this._modelToDelete = true;
        var name = model.get('name') || '';
        app.alert.show(model.get('id') + ':deleted', {
            level: 'confirmation',
            messages: app.utils.formatString(app.lang.get('LBL_PRO_ENABLE_CONFIRMATION', model.module),[name.trim()]),
            onConfirm: function() {
                self._updateProStatusEnabled(model);
            }
        });
    },

    /**
     * Update record in table pmse_Project in fields prj_status by ACTIVE
     */
    _updateProStatusEnabled: function(model) {
        var self = this;
        url = App.api.buildURL(model.module, null, {id: model.id});
        attributes = {prj_status: 'ACTIVE'};
        App.api.call('update', url, attributes, {
            success: self._refresh(model,app.lang.get('LBL_PRO_ENABLE', model.module)),
            error: function (err) {
//                app.error.handleHttpError(err); if (callback) callback(err);
//                self.isWaitingResponse = false;
//                self.mergeDirtyElements();
//                self.isDirty = false;
            }
        });
    },

    /**
     * Show warning of pmse_bussiness_rules
     */
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

    /**
     * Download record of table pmse_business_rules
     */
    exportBusinessRules: function (model) {
        var url = app.api.buildURL(model.module, 'brules', {id: model.id}, {platform: app.config.platform});

        if (_.isEmpty(url)) {
            app.logger.error('Unable to get the Business Rule download uri.');
            return;
        }

        app.api.fileDownload(url, {
            error: function(data) {
                // refresh token if it has expired
                app.error.handleHttpError(data, {});
            }
        }, {iframe: this.$el});
    },
    /**
     * descriptionRecord: View description in table pmse_Business Rules in fields
     */
    descriptionRecord: function(model) {
        app.alert.dismiss('message-id');
        app.alert.show('message-id', {
            level: 'info',
            title: app.lang.get('LBL_DESCRIPTION'),
            messages: '<br/>' + Handlebars.Utils.escapeExpression(model.get('description')),
            autoClose: false
        });
    },

    /**
     * Sets property useRelativeTime to show date created as a relative time or as date time.
     *
     * @private
     */
    _setRelativeTimeAvailable: function(date) {
        var diffInDays = app.date().diff(date, 'days', true);
        var useRelativeTime = (diffInDays <= this.thresholdRelativeTime);
        return useRelativeTime;
    },

    /**
     * @inheritdoc
     *
     * New model related properties are injected into each model:
     *
     * - {Boolean} overdue True if record is prior to now.
     * - {String} picture_url Picture url for model's assigned user.
     * - {String} rst_module_name Name of the triggering module.
     */
    _renderHtml: function() {
        if (this.meta.config) {
            this._super('_renderHtml');
            return;
        }

        var tab = this.tabs[this.settings.get('activeTab')];

        if (tab.overdue_badge) {
            this.overdueBadge = tab.overdue_badge;
        }

        _.each(this.collection.models, function(model) {
            var pictureUrl = app.api.buildFileURL({
                module: 'Users',
                id: model.get('assigned_user_id'),
                field: 'picture'
            });
            model.set('picture_url', pictureUrl);
            model.useRelativeTime = this._setRelativeTimeAvailable(model.attributes.date_entered);
            // Update the triggering module names.
            var module = model.get('rst_module');
            var label = app.lang.getModString('LBL_MODULE_NAME', module);
            if (_.isUndefined(label)) {
                label = module;
            }
            model.set('rst_module_name', label);
        }, this);

        this._super('_renderHtml');
    }
});
