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
    extendsFrom: 'RecordView',

    initialize: function(options) {
        this._super('initialize', [options]);
        this.context.on('button:design_emailtemplates:click', this.designEmailTemplates, this);
        this.context.on('button:export_emailtemplates:click', this.warnExportEmailTemplates, this);
        this.context.on('button:delete_emailstemplates:click', this.warnDeleteEmailsTemplates, this);
        this.context.on('button:edit_emailstemplates:click', this.warnEditEmailTemplates, this);
    },

    _render: function() {
        this._super('_render');
        this.$('.record-cell[data-name=subject]').remove();
        this.$('.record-cell[data-name=body_html]').remove();
    },

    designEmailTemplates: function(model) {
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
            app.alert.show('email-templates-edit-confirmation',  {
                level: 'confirmation',
                messages: app.lang.get('LBL_PMSE_PROCESS_EMAIL_TEMPLATES_EDIT', model.module),
                onConfirm: _.bind(this._onWarnDesignActiveRecordConfirm, this, model),
                onCancel: $.noop
            });
        }
    },

    /**
     * onConfirm callback for design record warning.
     *
     * @private
     */
    _onWarnDesignActiveRecordConfirm: function(model) {
        app.navigate(this.context, model, 'layout/emailtemplates');
        this._modelToDesign = null;
    },

    warnEditEmailTemplates: function(model) {
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
        if (!data) { // Not in use, continue with edit.
            this.editClicked();
        } else { // Template in use, warn user.
            app.alert.show('email-templates-edit-confirmation',  {
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
        this.editClicked();
        this._modelToEdit = null;
    },

    handleEdit: function(e, cell) {
        this.warnEditEmailTemplates(this.model);
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
        var model = this._modelToDelete;
        if (!data) { // Template not in use, warn user.
            app.alert.show('delete_confirmation', {
                level: 'confirmation',
                messages: this.getDeleteMessages(model).confirmation,
                onConfirm: _.bind(this._onWarnDeleteInactiveRecordConfirm, this),
                onCancel: _.bind(this._clearModelToDelete, this)
            });
        } else { // Template in use, block deletion
            app.alert.show('message-id', {
                level: 'warning',
                title: app.lang.get('LBL_WARNING'),
                messages: app.lang.get('LBL_PMSE_PROCESS_EMAIL_TEMPLATES_DELETE', model.module),
                autoClose: false
            });
            this._clearModelToDelete();
        }
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
     * Unset _modelToDelete as it is used by the parent record.js file.
     *
     * @private
     */
    _clearModelToDelete: function() {
        this._modelToDelete = null;
    },

    warnExportEmailTemplates: function(model) {
        var that = this;
        if (app.cache.get('show_emailtpl_export_warning')) {
            app.alert.show('emailtpl-export-confirmation', {
                level: 'confirmation',
                messages: app.lang.get('LBL_PMSE_IMPORT_EXPORT_WARNING') +
                '<br/><br/>' + app.lang.get('LBL_PMSE_EXPORT_CONFIRMATION'),
                onConfirm: _.bind(that._onWarnExportEmailTemplatesConfirm, that, model),
                onCancel: $.noop
            });
        } else {
            that.exportEmailTemplates(model);
        }
    },

    /**
     * onConfirm callback for warnExportEmailTemplates call.
     * Set the cache so the warning isn't sent again and start the download.
     *
     * @param {Object} model: The model passed to the warnExportsEmailTemplates call
     *
     * @private
     */
    _onWarnExportEmailTemplatesConfirm: function(model) {
        app.cache.set('show_emailtpl_export_warning', false);
        this.exportEmailTemplates(model);
    },

    exportEmailTemplates: function(model) {
        var url = app.api.buildURL(model.module, 'etemplate', {id: model.id}, {platform: app.config.platform});

        if (_.isEmpty(url)) {
            app.logger.error('Unable to get the Project download uri.');
            return;
        }

        app.api.fileDownload(url, {
            error: this._onExportEmailTemplatesDownloadError
        }, {iframe: this.$el});
    },

    /**
     * error callback for exportEmailTemplates fileDownload call.
     * @param {Object} data: The data from the api call
     *
     * @private
     */
    _onExportEmailTemplatesDownloadError: function(data) {
        // refresh token if it has expired
        app.error.handleHttpError(data, {});
    }
})
