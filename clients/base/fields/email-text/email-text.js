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
 * @class View.Fields.Base.EmailTextField
 * @alias SUGAR.App.view.fields.BaseEmailTextField
 * @extends View.Fields.Base.BaseField
 */
({
    /**
     * @inheritdoc
     *
     * The direction for this field should always be `ltr`.
     */
    direction: 'ltr',

    // The purpose of email-text is to provide a simpler textfield email
    // when our main email widget is overkill. For example, the first time
    // login wizard uses email-text. Note that the email mutated is the
    // primary_address email.
    initialize: function(options) {
        options     = options || {};
        options.def = options.def || {};
        if (_.isUndefined(options.def.link)) {
            options.def.link = true;
        }

        this._super('initialize', [options]);
    },
   /**
     * Formats for display
     * If we have a proper email value from model we parse out just
     * the primary address part since we're using a simple text field.
     * @param  {Object} value The value retrieved from model for email
     * @return {Object}       Normalized email value for simple field
     */
    format: function(value) {
        if(_.isArray(value)) {
            var primaryEmail = _.find(value, function(email) {
                return email.primary_address && email.primary_address !== "0";
            });
            return primaryEmail ? primaryEmail.email_address : '';
        }
        return value;
    },
    /**
     * Prepares email for going back to API
     * @param  {Object} value The value
     * @return {Object}       API ready value for email
     */
    unformat: function(value) {
        var self = this,
            emails = this.model.get('email'),
            changed = false;
        if(!_.isArray(emails)){emails = [];}
        _.each(emails, function(email, index) {
            // If we find a primary address and its email_address is different
            if(email.primary_address &&
                email.primary_address !== "0" &&
                email.email_address !== value)
            {
                changed = true;
                emails[index].email_address = value;
            }
        }, this);
        // If brand new email we push a primary address
        if (emails.length == 0) {
            emails.push({
                email_address:   value,
                primary_address: "1",
                hasAnchor:       false,
                _wasNotArray:    true
            });
            changed = true;
        }
        if (changed) {
            this.model.set(this.name, emails);
            this.model.trigger('change:' + this.name, this, emails);
        }
        return emails;
    }
})
