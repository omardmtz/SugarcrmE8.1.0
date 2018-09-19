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
 * @class View.Fields.Base.Emails.EmailactionPaneltopField
 * @alias SUGAR.App.view.fields.BaseEmailsEmailactionPaneltopField
 * @extends View.Fields.Base.EmailactionField
 */
({
    extendsFrom: 'EmailactionField',

    /**
     * @inheritdoc
     * Set type to emailaction to get the template
     */
    initialize: function(options) {
        this._super("initialize", [options]);
        this.type = 'emailaction';
        this.on('emailclient:close', this.handleEmailClientClose, this);
    },

    /**
     * When email compose is done, refresh the data in the Emails subpanel
     */
    handleEmailClientClose: function() {
        var context = this.context.parent || this.context;
        var links = app.utils.getLinksBetweenModules(context.get('module'), this.module);

        _.each(links, function(link) {
            context.trigger('panel-top:refresh', link.name);
        });
    },

    /**
     * No additional options are needed from the element in order to launch the
     * email client.
     *
     * @param {jQuery} [$link] The element from which to get options.
     * @return {Object}
     * @private
     * @deprecated Use
     * View.Fields.Base.Emails.EmailactionPaneltopField#emailOptionTo and
     * View.Fields.Base.Emails.EmailactionPaneltopField#emailOptionRelated
     * instead.
     */
    _retrieveEmailOptionsFromLink: function($link) {
        app.logger.warn('View.Fields.Base.Emails.EmailactionPaneltopField#_retrieveEmailOptionsFromLink is ' +
            'deprecated. Use View.Fields.Base.Emails.EmailactionPaneltopField#emailOptionTo and ' +
            'View.Fields.Base.Emails.EmailactionPaneltopField#emailOptionRelated instead.');
        return {};
    },

    /**
     * Returns the recipients to use in the To field of the email. If
     * `this.def.set_recipient_to_parent` is true, then the model is added to
     * the email's To field.
     *
     * @see EmailClientLaunch plugin.
     * @param {Data.Bean} model Use this model when identifying the recipients.
     * @return {undefined|Array}
     */
    emailOptionTo: function(model) {
        if (this.def.set_recipient_to_parent) {
            return [{
                bean: model
            }];
        }
    },

    /**
     * Returns the bean to use as the email's related record. If
     * `this.def.set_related_to_parent` is true, then the model is used.
     *
     * @see EmailClientLaunch plugin.
     * @param {Data.Bean} model This model's parent is used as the email's
     * related record.
     * @return {undefined|Data.Bean}
     */
    emailOptionRelated: function(model) {
        if (this.def.set_related_to_parent) {
            return model;
        }
    }
})
