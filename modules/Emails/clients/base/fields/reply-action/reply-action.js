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
 * Reply action.
 *
 * This allows a user to "reply" to an existing email.
 *
 * @class View.Fields.Base.Emails.ReplyActionField
 * @alias SUGAR.App.view.fields.EmailsBaseReplyActionField
 * @extends View.Fields.Base.Emails.ForwardActionField
 */
({
    extendsFrom: 'EmailsForwardActionField',

    /**
     * The name of the template for the reply header.
     *
     * @inheritdoc
     */
    _tplHeaderHtmlName: 'reply-header-html',

    /**
     * @inheritdoc
     */
    _subjectPrefix: 'LBL_RE',

    /**
     * The element ID to use to identify the reply content.
     *
     * @inheritdoc
     */
    _contentId: 'replycontent',

    /**
     * @inheritdoc
     *
     * Updates the reply_to_id email option anytime the model's id attribute
     * changes.
     */
    bindDataChange: function() {
        var context = this.context.parent || this.context;
        var model = context.get('model');

        this._super('bindDataChange');

        if (model) {
            // Set the reply_to_id email option if the ID already exists.
            this.addEmailOptions({reply_to_id: model.get('id')});

            // Update the reply_to_id email option anytime the ID changes. This
            // might occur if the ID was discovered later. It is an edge-case.
            this.listenTo(model, 'change:id', function() {
                this.addEmailOptions({reply_to_id: model.get('id')});
            });
        }
    },

    /**
     * Returns the recipients to use in the To field of the email. The sender
     * from the original email is included.
     *
     * @see EmailClientLaunch plugin.
     * @param {Data.Bean} model Use this model when identifying the recipients.
     * @return {undefined|Array}
     */
    emailOptionTo: function(model) {
        var originalTo;
        var originalSender = model.get('from_collection');
        var to = this._createRecipients(originalSender);

        if (this.def.reply_all) {
            app.logger.warn('The reply_all option is deprecated. Use View.Fields.Base.Emails.ReplyAllActionField ' +
                'instead.');
            originalTo = model.get('to_collection');
            to = _.union(to, this._createRecipients(originalTo));
        }

        return to;
    },

    /**
     * Returns the recipients to use in the CC field of the email. The
     * `reply_all` option is deprecated. Use
     * View.Fields.Base.Emails.ReplyAllActionField instead.
     *
     * @see EmailClientLaunch plugin.
     * @param {Data.Bean} model Use this model when identifying the recipients.
     * @return {undefined|Array}
     */
    emailOptionCc: function(model) {
        var originalCc;
        var cc;

        if (this.def.reply_all) {
            app.logger.warn('The reply_all option is deprecated. Use View.Fields.Base.Emails.ReplyAllActionField ' +
                'instead.');
            originalCc = model.get('cc_collection');
            cc = this._createRecipients(originalCc);
        }

        return cc;
    },

    /**
     * Attachments are not carried over to replies.
     *
     * @inheritdoc
     */
    emailOptionAttachments: function(model) {
    },

    /**
     * Sets up the email options for the EmailClientLaunch plugin to use -
     * passing to the email compose drawer or building up the mailto link.
     *
     * @protected
     * @deprecated The EmailClientLaunch plugin handles email options.
     */
    _updateEmailOptions: function() {
        app.logger.warn('View.Fields.Base.Emails.ReplyActionField#_updateEmailOptions is deprecated. ' +
            'The EmailClientLaunch plugin handles email options.');
    },

    /**
     * Build the reply recipients based on the original email's from, to, and cc
     *
     * @param {boolean} all Whether this is reply to all (true) or just a standard
     *   reply (false).
     * @return {Object} To and Cc values for the reply email.
     * @return {Array} return.to The to values for the reply email.
     * @return {Array} return.cc The cc values for the reply email.
     * @protected
     * @deprecated Use
     * View.Fields.Base.Emails.ReplyActionField#emailOptionTo and
     * View.Fields.Base.Emails.ReplyActionField#emailOptionCc instead.
     */
    _getReplyRecipients: function(all) {
        app.logger.warn('View.Fields.Base.Emails.ReplyActionField#_getReplyRecipients is deprecated. Use ' +
            'View.Fields.Base.Emails.ReplyActionField#emailOptionTo and ' +
            'View.Fields.Base.Emails.ReplyActionField#emailOptionCc instead.');

        if (all) {
            app.logger.warn('The reply_all option is deprecated. Use View.Fields.Base.Emails.ReplyAllActionField ' +
                'instead.');
        }

        return {
            to: this.emailOptionTo(this.model) || [],
            cc: this.emailOptionCc(this.model) || []
        };
    },

    /**
     * Given the original subject, generate a reply subject.
     *
     * @param {string} subject
     * @protected
     * @deprecated Use
     * View.Fields.Base.Emails.ReplyActionField#emailOptionSubject instead.
     */
    _getReplySubject: function(subject) {
        app.logger.warn('View.Fields.Base.Emails.ReplyActionField#_getReplySubject is deprecated. Use ' +
            'View.Fields.Base.Emails.ReplyActionField#emailOptionSubject instead.');

        return this.emailOptionSubject(this.model);
    },

    /**
     * Get the data required by the header template.
     *
     * @return {Object}
     * @protected
     * @deprecated Use
     * View.Fields.Base.Emails.ReplyActionField#_getHeaderParams instead.
     */
    _getReplyHeaderParams: function() {
        app.logger.warn('View.Fields.Base.Emails.ReplyActionField#_getReplyHeaderParams is deprecated. Use ' +
            'View.Fields.Base.Emails.ReplyActionField#_getHeaderParams instead.');

        return this._getHeaderParams(this.model);
    },

    /**
     * Build the reply header for text only emails.
     *
     * @param {Object} params
     * @param {string} params.from
     * @param {string} [params.date] Date original email was sent
     * @param {string} params.to
     * @param {string} [params.cc]
     * @param {string} params.name The subject of the original email.
     * @return {string}
     * @private
     * @deprecated Use
     * View.Fields.Base.Emails.ReplyActionField#_getHeader instead.
     */
    _getReplyHeader: function(params) {
        app.logger.warn('View.Fields.Base.Emails.ReplyActionField#_getReplyHeader is deprecated. Use ' +
            'View.Fields.Base.Emails.ReplyActionField#_getHeader instead.');

        return this._getHeader(params);
    },

    /**
     * Create an array of email recipients from the collection, which can be
     * used as recipients to pass to the new email.
     *
     * @param {Data.BeanCollection} collection
     * @return {Array}
     * @private
     */
    _createRecipients: function(collection) {
        return collection.map(function(recipient) {
            var data = {
                email: app.data.createBean('EmailAddresses', recipient.get('email_addresses'))
            };
            var parent;

            if (recipient.hasParent()) {
                parent = recipient.getParent();

                if (parent) {
                    data.bean = parent;
                }
            }

            return data;
        });
    },

    /**
     * Retrieve the plain text version of the reply body.
     *
     * @param {Data.Bean} model The body should come from this model's
     * attributes. EmailClientLaunch plugin should dictate the model based on
     * the context.
     * @return {string} The reply body
     * @private
     */
    _getReplyBody: function(model) {
        // Falls back to the `this.model` for backward compatibility.
        model = model || this.model;

        return model.get('description') || '';
    },

    /**
     * Retrieve the HTML version of the email body.
     *
     * Ensure the result is a defined string and strip any signature wrapper
     * tags to ensure it doesn't get stripped if we insert a signature above
     * the forward content. Also strip any reply content class if this is a
     * forward to a previous reply. And strip any forward content class if this
     * is a forward to a previous forward.
     *
     * @return {string}
     * @protected
     * @deprecated Use
     * View.Fields.Base.Emails.ReplyActionField#emailOptionDescriptionHtml
     * instead.
     */
    _getReplyBodyHtml: function() {
        app.logger.warn('View.Fields.Base.Emails.ReplyActionField#_getReplyBodyHtml is deprecated. Use ' +
            'View.Fields.Base.Emails.ReplyActionField#emailOptionDescriptionHtml instead.');

        return this.emailOptionDescriptionHtml(this.model);
    }
})
