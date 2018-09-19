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
        this.on('dashlet-email:edit:fire', this.editRecord, this);
        this.on('dashlet-email:delete-record:fire', this.deleteRecord, this);
        this.on('dashlet-email:enable-record:fire', this.enableRecord, this);
        this.on('dashlet-email:download:fire', this.warnExportEmailsTemplates, this);
        this.on('dashlet-email:description-record:fire', this.descriptionRecord, this);
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
    editRecord: function(model) {
        var verifyURL = app.api.buildURL(
                'pmse_Project',
                'verify',
                {id: model.get('id')},
                {baseModule: this.module});
        this._modelToEdit = model;
        app.api.call('read', verifyURL, null, {
            success: _.bind(this._onEditRecordVerify, this)
        });
    },

    /**
     * Callback after checking if the template to be edited is already in use.
     *
     * @param {boolean} data: True if the template is being used (e.g. in a process), false otherwise.
     *
     * @private
     */
    _onEditRecordVerify: function(data) {
        var model = this._modelToEdit;
        var redirect = model.module + '/' + model.id + '/layout/emailtemplates';
        if (!data) {
            app.router.navigate(redirect, {trigger: true, replace: true});
        } else {
            app.alert.show('email-templates-edit-confirmation',  {
                level: 'confirmation',
                messages: app.lang.get('LBL_PMSE_PROCESS_EMAIL_TEMPLATES_EDIT', model.module),
                onConfirm: _.bind(this._onWarnEditActiveRecordConfirm, this, redirect),
                onCancel: _.bind(this._onWarnEditActiveRecordCancel, this)
            });
        }
    },

    /**
     * onConfirm callback for edit record warning.
     * @param {string} redirect: The redirect location made in the _onEditRecordVerify call that lead to this.
     *
     * @private
     */
    _onWarnEditActiveRecordConfirm: function(redirect) {
        app.router.navigate(redirect, {trigger: true, replace: true});
        this._modelToEdit = null;
    },

    /**
     * onCancel callback for edit record warning.
     *
     * @private
     */
    _onWarnEditActiveRecordCancel: function() {
        this._modelToEdit = null;
    },

    /**
     * Show warning of pmse_email_templates
     */
    warnExportEmailsTemplates: function(model) {
        var that = this;
        if (app.cache.get('show_emailtpl_export_warning')) {
            app.alert.show('emailtpl-export-confirmation', {
                level: 'confirmation',
                messages: app.lang.get('LBL_PMSE_IMPORT_EXPORT_WARNING') +
                '<br/><br/>' + app.lang.get('LBL_PMSE_EXPORT_CONFIRMATION'),
                //model is passed to _.bind to pass it as a parameter to _onWarnExportEmailsTemplatesConfirm
                onConfirm: _.bind(this._onWarnExportEmailsTemplatesConfirm, this, model),
                onCancel: $.noop
            });
        } else {
            that.exportEmailsTemplates(model);
        }
    },
    /**
     * onConfirm callback for warnExportEmailsTemplates call.
     * Set the cache so the warning isn't sent again and start the download.
     *
     * @param {Object} model: The model passed to the warnExportsEmailsTemplates call
     *
     * @private
     */
    _onWarnExportEmailsTemplatesConfirm: function(model) {
        app.cache.set('show_emailtpl_export_warning', false);
        this.exportEmailsTemplates(model);
    },
    /**
     * Download record of table pmse_emails_templates
     */
    exportEmailsTemplates: function(model) {
        var url = app.api.buildURL(model.module, 'etemplate', {id: model.id}, {platform: app.config.platform});

        if (_.isEmpty(url)) {
            app.logger.error('Unable to get the Email Template download uri.');
            return;
        }

        app.api.fileDownload(url, {
            error: this._onExportEmailsTemplatesDownloadError
        }, {iframe: this.$el});
    },
    /**
     * error callback for exportEmailsTemplates fileDownload call.
     * @param {Object} data: The data from the api call
     *
     * @private
     */
    _onExportEmailsTemplatesDownloadError: function(data) {
        // refresh token if it has expired
        app.error.handleHttpError(data, {});
    },

    /**
     * @inheritdoc
     *
     * FIXME: This should be removed when metadata supports date operators to
     * allow one to define relative dates for date filters.
     */
    _initTabs: function() {
        this._super('_initTabs');
    },

    /**
     * Create new record.
     *
     * @param {Event} event Click event.
     * @param {String} params.layout Layout name.
     * @param {String} params.module Module name.
     */
    createRecord: function(event, params) {
        if (this.module !== 'pmse_Emails_Templates') {
            this.createRelatedRecord(params.module, params.link);
        } else {
            var self = this;
            app.drawer.open({
                layout: 'create',
                context: {
                    create: true,
                    module: params.module
                }
            }, _.bind(self._onCreateRecordDrawerClose, self));
        }

    },
    /**
     * Callback used by the createRecord call to app.drawer.open
     *
     * @param {Object} context: Something that app.drawer.open calls this with
     * @param {Object} model: Model of the created record. Will be falsy if user cancels.
     *
     * @private
     */
    _onCreateRecordDrawerClose: function(context, model) {
        if (!model) {
            return;
        }
        this.context.resetLoadFlag();
        this.context.set('skipFetch', false);
        if (_.isFunction(this.loadData)) {
            this.loadData();
        } else {
            this.context.loadData();
        }
    },

    importRecord: function(event, params) {
        app.router.navigate(params.link, {trigger: true, replace: true});
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
                {baseModule: this.module});
        this._modelToDelete = model;
        app.api.call('read', verifyURL, null, {
            success: _.bind(this._onDeleteRecordVerify, this)
        });
    },

    /**
     * Callback after api call to verify whether the email template is active in a process.
     * @param {boolean} data: true if the email template is being used (e.g. in a process), false otherwise.
     * @private
     */
    _onDeleteRecordVerify: function(data) {
        var model = this._modelToDelete;
        if (!data) { // Is NOT actively in use.
            app.alert.show('delete_confirmation', {
                level: 'confirmation',
                messages: app.utils.formatString(app.lang.get('LBL_PRO_DELETE_CONFIRMATION', model.module)),
                onConfirm: _.bind(this._onWarnDeleteInactiveRecordConfirm, this),
                onCancel: _.bind(this._onWarnDeleteInactiveRecordCancel, this)
            });
        } else { // Is actively in use, do not allow deletion.
            app.alert.show('message-id', {
                level: 'warning',
                title: app.lang.get('LBL_WARNING'),
                messages: app.lang.get('LBL_PMSE_PROCESS_EMAIL_TEMPLATES_DELETE', model.module),
                autoClose: false
            });
            this._modelToDelete = null;
        }
    },

    /**
     * onConfirm callback for delete record warning.
     * Called by _onDeleteRecordVerify if the template is not active.
     *
     * @private
     */
    _onWarnDeleteInactiveRecordConfirm: function() {
        var model = this._modelToDelete;
        model.destroy({
            showAlerts: true,
            success: _.bind(this._getRemoveRecord, this)
        });
    },

    /**
     * onCancel callback for delete record warning.
     * Called by _onDeleteRecordVerify if the template is not active.
     *
     * @private
     */
    _onWarnDeleteInactiveRecordCancel: function() {
        this._modelToDelete = null;
    },

    /**
     * Updating in fields delete removed
     * @private
     */
    _getRemoveRecord: function(model) {
        if (this.disposed) {
            return;
        }
        this.collection.remove(model);
        this.render();
        this.context.trigger('tabbed-dashlet:refresh', model.module);
    },

    /**
     * Method view alert in process with text modify
     * show and hide alert
     */
    _refresh: function(model, status) {
        app.alert.show(model.id + ':refresh', {
            level: 'process',
            title: status,
            autoclose: false
        });
        return _.bind(this._refreshReturn, this);
    },

    /**
     * Function that _refresh returns
     *
     * @param {Object} model: The model passed to _refresh
     * @private
     */
    _refreshReturn: function(model) {
        var options = {};
        this.layout.reloadDashlet(options);
        app.alert.dismiss(model.id + ':refresh');
    },

    /**
     * descriptionRecord: View description in table pmse_Emails_Templates in fields
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
     * - {String} base_module_name Name of the triggering module.
     */
    _renderHtml: function() {
        if (this.meta.config) {
            this._super('_renderHtml');
            return;
        }

        // Render each of the templates.
        _.each(this.collection.models, this._renderItemHtml, this);

        this._super('_renderHtml');
    },

    /**
     * Render an individual process emails template in the dashlet. Used by _renderHtml.
     *
     * @param {Object} model: The model object of the process emails template.
     * @private
     */
    _renderItemHtml: function(model) {
        model.useRelativeTime = this._setRelativeTimeAvailable(model.attributes.date_entered);
        // Update the triggering module names.
        var module = model.get('base_module');
        var label = app.lang.getModString('LBL_MODULE_NAME', module);
        if (_.isUndefined(label)) {
            label = module;
        }
        model.set('base_module_name', label);
    }
});
