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
 * Reply all action.
 *
 * This allows a user to "reply all" to an existing email.
 *
 * @class View.Fields.Base.Emails.ReplyAllActionField
 * @alias SUGAR.App.view.fields.EmailsBaseReplyAllActionField
 * @extends View.Fields.Base.Emails.ReplyActionField
 */
({
    extendsFrom: 'EmailsReplyActionField',

    /**
     * Returns the recipients to use in the To field of the email. The sender
     * and the recipients in the To field from the original email are included.
     *
     * @see EmailClientLaunch plugin.
     * @param {Data.Bean} model Use this model when identifying the recipients.
     * @return {undefined|Array}
     */
    emailOptionTo: function(model) {
        var originalTo = model.get('to_collection');
        var to = this._super('emailOptionTo', [model]) || [];

        to = _.union(to, this._createRecipients(originalTo));

        return to;
    },

    /**
     * Returns the recipients to use in the CC field of the email. These
     * recipients are the same ones who appeared in the original email's CC
     * field.
     *
     * @see EmailClientLaunch plugin.
     * @param {Data.Bean} model Use this model when identifying the recipients.
     * @return {undefined|Array}
     */
    emailOptionCc: function(model) {
        var originalCc = model.get('cc_collection');
        var cc = this._createRecipients(originalCc);

        return cc;
    },

    /**
     * Returns the template from View.Fields.Base.Emails.ReplyActionField.
     *
     * @inheritdoc
     */
    _getHeaderHtmlTemplate: function() {
        this._tplHeaderHtml = this._tplHeaderHtml ||
            app.template.getField('reply-action', this._tplHeaderHtmlName, 'Emails');

        return this._tplHeaderHtml;
    }
})
