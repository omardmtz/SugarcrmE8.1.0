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
            'list:editemailstemplates:fire': 'openEmailsTemplates',
            'list:exportemailstemplates:fire': 'warnExportEmailsTemplates',
            'list:deleteemailstemplates:fire': 'warnDeleteEmailsTemplates',
            'list:edit_emailstemplates:fire': 'warnEditEmailsTemplates'
        });
        this._super('initialize', [options]);
    },

    openEmailsTemplates: function(model) {
        var verifyURL = app.api.buildURL(
                'pmse_Project',
                'verify',
                {id: model.get('id')},
                {baseModule: this.module});
        this._modelToDesign = model;

        app.api.call('read', verifyURL, null, {
            success: _.bind(this._onDesignRecordVerify, this)
        });
    },

    /**
     * Callback after checking if the template to be designed is already in use.
     *
     * @param {boolean} data: True if the template is being used (e.g. in a process), false otherwise.
     *
     * @private
     */
    _onDesignRecordVerify: function(data) {
        var model = this._modelToDesign;
        if (!data) {
            app.navigate(this.context, model, 'layout/emailtemplates');
        } else {
            app.alert.show('business-rule-design-confirmation',  {
                level: 'confirmation',
                messages: app.lang.get('LBL_PMSE_PROCESS_EMAIL_TEMPLATES_EDIT', model.module),
                onConfirm: _.bind(this._onWarnDesignActiveRecordConfirm, this, model),
                onCancel: $.noop
            });
        }
    },

    /**
     * onConfirm callback for design record warning.
     * @param {Object} model: The model of the template to be designed.
     *
     * @private
     */
    _onWarnDesignActiveRecordConfirm: function(model) {
        app.navigate(this.context, model, 'layout/emailtemplates');
    },

    warnEditEmailsTemplates: function(model) {
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
        if (!data) { //
            this.toggleRow(model.id, true);
            this.resize();
        } else {
            app.alert.show('business-rule-design-confirmation',  {
                level: 'confirmation',
                messages: app.lang.get('LBL_PMSE_PROCESS_EMAIL_TEMPLATES_EDIT', model.module),
                onConfirm: _.bind(this._onWarnEditActiveRecordConfirm, this),
                onCancel: $.noop
            });
        }
    },

    /**
     * onConfirm callback for edit record warning.
     *
     * @private
     */
    _onWarnEditActiveRecordConfirm: function() {
        var model = this._modelToEdit;
        this.toggleRow(model.id, true);
        this.resize();
        this._modelToEdit = null;
    },

    warnDeleteEmailsTemplates: function(model) {
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
     * Callback for api call to verify whether the email template is active in a process.
     * @param {boolean} data: true if the email template is being used (e.g. in a process), false otherwise.
     * @private
     */
    _onDeleteRecordVerify: function(data) {
        if (!data) {
            this._warnDeleteInactiveRecord();
        } else {
            this._blockDeleteActiveRecord();
        }
    },

    /**
     * Get the user's confirmation before deleting the record.
     * Separated to reduce complexity of function for testing.
     *
     * @private
     */
    _warnDeleteInactiveRecord: function() {
        var model = this._modelToDelete;
        this._targetUrl = Backbone.history.getFragment();
        //Replace the url hash back to the current staying page
        if (this._targetUrl !== this._currentUrl) {
            app.router.navigate(this._currentUrl, {trigger: false, replace: true});
        }

        app.alert.show('delete_confirmation', {
            level: 'confirmation',
            messages: this.getDeleteMessages(model).confirmation,
            onConfirm: _.bind(this._onWarnDeleteInactiveRecordConfirm, this),
            onCancel: _.bind(this._clearModelToDelete, this)
        });
    },

    /**
     * Prevent the user from deleting an email template that is in use.
     * Separated to reduce complexity of function for testing.
     *
     * @private
     */
    _blockDeleteActiveRecord: function() {
        var model = this._modelToDelete;
        app.alert.show('message-id', {
            level: 'warning',
            title: app.lang.get('LBL_WARNING'),
            messages: app.lang.get('LBL_PMSE_PROCESS_EMAIL_TEMPLATES_DELETE', model.module),
            autoClose: false
        });
        this._clearModelToDelete();
    },

    /**
     * onConfirm callback for delete record warning.
     *
     * @private
     */
    _onWarnDeleteInactiveRecordConfirm: function() {
        this.deleteModel();
    },

    /**
     * onCancel callback for delete record warning.
     *
     * @private
     */
    _clearModelToDelete: function() {
        this._modelToDelete = null;
    },

    warnExportEmailsTemplates: function(model) {
        var that = this;
        if (app.cache.get('show_emailtpl_export_warning')) {
            app.alert.show('emailtpl-export-confirmation', {
                level: 'confirmation',
                messages: app.lang.get('LBL_PMSE_IMPORT_EXPORT_WARNING') +
                '<br/><br/>' + app.lang.get('LBL_PMSE_EXPORT_CONFIRMATION'),
                onConfirm: _.bind(that._onWarnExportEmailsTemplatesConfirm, that, model),
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
     * @param {Object} model: The model passed to the warnExportsEmailTemplates call
     *
     * @private
     */
    _onWarnExportEmailsTemplatesConfirm: function(model) {
        app.cache.set('show_emailtpl_export_warning', false);
        this.exportEmailsTemplates(model);
    },

    exportEmailsTemplates: function(model) {
        var url = app.api.buildURL(model.module, 'etemplate', {id: model.id}, {platform: app.config.platform});

        if (_.isEmpty(url)) {
            app.logger.error('Unable to get the Project download uri.');
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
    }
})
