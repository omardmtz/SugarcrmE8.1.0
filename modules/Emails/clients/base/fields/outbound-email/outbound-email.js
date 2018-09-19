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
 * @class View.Fields.Base.Emails.OutboundEmailField
 * @alias SUGAR.App.view.fields.BaseEmailsOutboundEmailField
 * @extends View.Fields.Base.EnumField
 */
({
    extendsFrom: 'BaseEnumField',

    /**
     * Sets the field type to `enum` so that the `BaseEnumField` templates are
     * loaded. This is necessary when extending a field and using a
     * different name without any custom templates.
     *
     * Adds help text (LBL_OUTBOUND_EMAIL_ID_HELP) for admins.
     *
     * @inheritdoc
     */
    initialize: function(options) {
        if (app.user.get('type') === 'admin') {
            options.def.help = 'LBL_OUTBOUND_EMAIL_ID_HELP';
        }

        this._super('initialize', [options]);
        this.type = 'enum';
    },

    /**
     * @inheritdoc
     *
     * Only add the help tooltip if the help text is being hidden.
     */
    decorateHelper: function() {
        if (this.def.hideHelp) {
            this._super('decorateHelper');
        }
    },

    /**
     * @inheritdoc
     *
     * Dismisses any alerts with the key `email-client-status`.
     */
    _dispose: function() {
        app.alert.dismiss('email-client-status');
        this._super('_dispose');
    },

    /**
     * Shows a warning to the user when a not_authorized error is returned.
     *
     * @inheritdoc
     * @fires email_not_configured Triggered on the view to allow the view to
     * decide what should be done beyond warning the user. The error is passed
     * to listeners.
     */
    loadEnumOptions: function(fetch, callback, error) {
        var oError = error;

        error = _.bind(function(e) {
            if (e.code === 'not_authorized') {
                // Mark the error as having been handled so that it doesn't get
                // handled again.
                e.handled = true;
                app.alert.show('email-client-status', {
                    level: 'warning',
                    messages: app.lang.get(e.message, this.module),
                    autoClose: false,
                    onLinkClick: function() {
                        app.alert.dismiss('email-client-status');
                    }
                });
                this.view.trigger('email_not_configured', e);
            }

            if (oError) {
                oError(e);
            }
        }, this);

        this._super('loadEnumOptions', [fetch, callback, error]);
    }
})
