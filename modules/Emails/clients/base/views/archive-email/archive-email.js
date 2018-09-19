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
/**
 * @class View.Views.Base.Emails.ArchiveEmailView
 * @alias SUGAR.App.view.views.BaseEmailsArchiveEmailView
 * @extends View.Views.Base.Emails.ComposeView
 * @deprecated Use {@link View.Views.Base.Emails.CreateView} instead.
 */
({
    extendsFrom: 'EmailsComposeView',

    /**
     * @inheritdoc
     *
     * Add click event handler to archive an email.
     */
    initialize: function(options) {
        app.logger.warn(
            'View.Views.Base.Emails.ArchiveEmailView is deprecated. Use View.Views.Base.Emails.CreateView instead.'
        );

        this.events = _.extend({}, this.events, {
            'click [name=archive_button]': 'archive'
        });
        this._super('initialize', [options]);

        if (!this.model.has('assigned_user_id')) {
            this.model.set('assigned_user_id', app.user.id);
            this.model.set('assigned_user_name', app.user.get('full_name'));
        }
    },

    /**
     * Set headerpane title.
     * @private
     */
    _render: function() {
        var $controls;

        this._super('_render');

        $controls = this.$('.control-group:not(.hide) .control-label');
        if ($controls.length) {
            $controls.last().addClass('end-fieldgroup');
        }

        this.setTitle(app.lang.get('LBL_ARCHIVE_EMAIL', this.module));
    },

    /**
     * Archive email if validation passes.
     */
    archive: function(event) {
        this.setMainButtonsDisabled(true);
        this.model.doValidate(this.getFieldsToValidate(), _.bind(function(isValid) {
            if (isValid) {
                this.archiveEmail();
            } else {
                this.setMainButtonsDisabled(false);
            }
        }, this));
    },

    /**
     * Get fields that needs to be validated.
     * @return {Object}
     */
    getFieldsToValidate: function() {
        var fields = {};
        _.each(this.fields, function(field) {
            fields[field.name] = field.def;
        });
        return fields;
    },

    /**
     * Call archive api.
     */
    archiveEmail: function() {
        var archiveUrl = app.api.buildURL('Mail/archive');
        var alertKey = 'mail_archive';
        var archiveEmailModel = this.initializeSendEmailModel();

        app.alert.show(alertKey, {level: 'process', title: app.lang.get('LBL_EMAIL_ARCHIVING', this.module)});

        app.api.call('create', archiveUrl, archiveEmailModel, {
            success: _.bind(function() {
                app.alert.dismiss(alertKey);
                app.alert.show(alertKey, {
                    autoClose: true,
                    level: 'success',
                    messages: app.lang.get('LBL_EMAIL_ARCHIVED', this.module)
                });
                app.drawer.close(this.model);
            }, this),
            error: function(error) {
                var msg = {level: 'error'};
                if (error && _.isString(error.message)) {
                    msg.messages = error.message;
                }
                app.alert.dismiss(alertKey);
                app.alert.show(alertKey, msg);
            },
            complete: _.bind(function() {
                if (!this.disposed) {
                    this.setMainButtonsDisabled(false);
                }
            }, this)
        });
    },

    /**
     * @inheritdoc
     */
    initializeSendEmailModel: function() {
        var model = this._super('initializeSendEmailModel');
        model.set({
            'date_sent': this.model.get('date_sent'),
            'from_address': this.model.get('from_address'),
            'status': 'archive',
            'state': 'Archived'
        });
        return model;
    },

    /**
     * Disable/enable archive button.
     * @param {boolean} disabled
     */
    setMainButtonsDisabled: function(disabled) {
        this.getField('archive_button').setDisabled(disabled);
    },

    /**
     * No need to warn of configuration status for archive email because no
     * email is being sent.
     */
    notifyConfigurationStatus: $.noop
})
