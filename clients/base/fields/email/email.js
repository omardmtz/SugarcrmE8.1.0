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
 * @class View.Fields.Base.EmailField
 * @alias SUGAR.App.view.fields.BaseEmailField
 * @extends View.Fields.Base.BaseField
 */
({
    events: {
        'change .existingAddress': 'updateExistingAddress',
        'click  .btn-edit':        'toggleExistingAddressProperty',
        'click  .removeEmail':     'removeExistingAddress',
        'click  .addEmail':        'addNewAddress',
        'change .newEmail': 'addNewAddress',
        'click [data-action=audit-email-address]': 'auditEmailAddress',
    },

    _flag2Deco: {
        primary_address: {lbl: "LBL_EMAIL_PRIMARY", cl: "primary"},
        opt_out: {lbl: "LBL_EMAIL_OPT_OUT", cl: "opted-out"},
        invalid_email: {lbl: "LBL_EMAIL_INVALID", cl: "invalid"}
    },

    plugins: ['ListEditable', 'EmailClientLaunch'],

    /**
     * @inheritdoc
     *
     * The direction for this field should always be `ltr`.
     */
    direction: 'ltr',

    /**
     * @inheritdoc
     * @param options
     */
    initialize: function(options) {
        options     = options || {};
        options.def = options.def || {};

        // By default, compose email link should be allowed
        if (_.isUndefined(options.def.emailLink)) {
            options.def.emailLink = true;
        }

        // Check if the email1 field was made required, if-so copy that property to the dynamic field.
        if (options.model &&
            options.model.fields &&
            options.model.fields.email1 &&
            options.model.fields.email1.required) {
            options.def.required = options.model.fields.email1.required;
        }

        if (options.view.action === 'filter-rows') {
            options.viewName = 'filter-rows-edit';
        }

        this._super('initialize', [options]);

        //set model as the related record when composing an email (copy is made by plugin)
        this.addEmailOptions({related: this.model});
    },

    /**
     * When data changes, re-render the field only if it is not on edit (see MAR-1617).
     * @inheritdoc
     */
    bindDataChange: function() {
        this.model.on('change:' + this.name, function() {
            if (this.action !== 'edit') {
                this.render();
            }
        }, this);
    },

    /**
     * In edit mode, render email input fields using the edit-email-field template.
     * @inheritdoc
     * @private
     */
    _render: function() {
        var emailsHtml = '';

        this._super("_render");

        if (this.tplName === 'edit') {
            // Add email input fields for edit
            _.each(this.value, function(email) {
                emailsHtml += this._buildEmailFieldHtml(email);
            }, this);
            this.$el.prepend(emailsHtml);
        }
    },

    /**
     * Get HTML for email input field.
     * @param {Object} email
     * @return {Object}
     * @private
     */
    _buildEmailFieldHtml: function(email) {
        var editEmailFieldTemplate = app.template.getField('email', 'edit-email-field'),
            emails = this.model.get(this.name),
            index = _.indexOf(emails, email);

        return editEmailFieldTemplate({
            max_length: this.def.len,
            index: index === -1 ? emails.length-1 : index,
            email_address: email.email_address,
            primary_address: email.primary_address,
            opt_out: email.opt_out,
            invalid_email: email.invalid_email
        });
    },

    /**
     * Event handler to add a new address field.
     * @param {Event} evt
     */
    addNewAddress: function(evt){
        if (!evt) return;

        var email = this.$(evt.currentTarget).val() || this.$('.newEmail').val(),
            currentValue,
            emailFieldHtml,
            $newEmailField;

        email = $.trim(email);

        if ((email !== '') && (this._addNewAddressToModel(email))) {
            // build the new email field
            currentValue = this.model.get(this.name);
            emailFieldHtml = this._buildEmailFieldHtml({
                email_address: email,
                primary_address: currentValue && (currentValue.length === 1),
                opt_out: app.config.newEmailAddressesOptedOut || false,
                invalid_email: false
            });

            // append the new field before the new email input
            $newEmailField = this._getNewEmailField()
                .closest('.email')
                .before(emailFieldHtml);

            if (this.def.required && this._shouldRenderRequiredPlaceholder()) {
                // we need to remove the required place holder now
                var label = app.lang.get('LBL_REQUIRED_FIELD', this.module),
                    el = this.$(this.fieldTag).last(),
                    placeholder = el.prop('placeholder').replace('(' + label + ') ', '');

                el.prop('placeholder', placeholder.trim()).removeClass('required');
            }
        }

        this._clearNewAddressField();
    },

    /**
     * Event handler to update an email address.
     * @param {Event} evt
     */
    updateExistingAddress: function(evt) {
        if (!evt) return;
        var $inputs = this.$('.existingAddress'),
            $input = this.$(evt.currentTarget),
            index = $inputs.index($input),
            newEmail = $input.val(),
            emails = this.model.get(this.name) || [],
            primaryRemoved;

        newEmail = $.trim(newEmail);

        if (newEmail === '') {
            // remove email if email is empty
            primaryRemoved = this._removeExistingAddressInModel(index);

            $input
                .closest('.email')
                .remove();

            if (primaryRemoved) {
                // on list views we need to set the current value on the input
                if (this.view && this.view.action === 'list') {
                    var primaryAddress = _.filter(emails, function(address) {
                        if (address.primary_address) {
                            return true;
                        }
                    });
                    if(primaryAddress[0] && primaryAddress[0].email_address) {
                        app.alert.show('list_delete_email_info', {
                            level: 'info',
                            autoClose: true,
                            messages: app.lang.get('LBL_LIST_REMOVE_EMAIL_INFO')
                        });
                        $input.val(primaryAddress[0].email_address);
                    }
                }
                this.$('[data-emailproperty=primary_address]')
                    .first()
                    .addClass('active');
            }
            return;
        }
        if (this.tplName === 'list-edit') {
            // In list-edit mode the index is not always at the index of the current target.
            _.find(emails, function(email, i) {
                if (email.primary_address) {
                    index = i;
                    return true;
                }
            });
        }
        this._updateExistingAddressInModel(index, newEmail);
    },

    /**
     * Event handler to remove an email address.
     * @param {Event} evt
     */
    removeExistingAddress: function(evt) {
        if (!evt) return;

        var $deleteButtons = this.$('.removeEmail'),
            $deleteButton = this.$(evt.currentTarget),
            index = $deleteButtons.index($deleteButton),
            primaryRemoved,
            $removeThisField;

        primaryRemoved = this._removeExistingAddressInModel(index);

        $removeThisField = $deleteButton.closest('.email');
        $removeThisField.remove();

        if (primaryRemoved) {
            // If primary has been removed, the first email address is the primary address.
            this.$('[data-emailproperty=primary_address]')
                .first()
                .addClass('active');
        }

        // if this field is required, and there is nothing in the model, then we should decorate it as required
        if (this.def.required && _.isEmpty(this.model.get(this.name))) {
            this.decorateRequired();
        }
    },

    /**
     * Event handler to toggle email address properties.
     * @param {Event} evt
     */
    toggleExistingAddressProperty: function(evt) {
        if (!evt) return;

        var $property = this.$(evt.currentTarget),
            property = $property.data('emailproperty'),
            $properties = this.$('[data-emailproperty='+property+']'),
            index = $properties.index($property);

        if (property === 'primary_address') {
            $properties.removeClass('active').attr('aria-pressed', false);
        }
        $property.attr('aria-pressed', !$property.hasClass('active'));

        this._toggleExistingAddressPropertyInModel(index, property);
    },

    /**
     * Add the new email address to the model.
     * @param {String} email
     * @return {boolean} Returns true when a new email is added.  Returns false if duplicate is found,
     *          and was not added to the model.
     * @private
     */
    _addNewAddressToModel: function(email) {
        var existingAddresses = this.model.get(this.name) ? app.utils.deepCopy(this.model.get(this.name)) : [],
            dupeAddress = _.find(existingAddresses, function(address){
                return (address.email_address === email);
            }),
            success = false;

        if (_.isUndefined(dupeAddress)) {
            existingAddresses.push({
                email_address: email,
                primary_address: (existingAddresses.length === 0),
                opt_out: app.config.newEmailAddressesOptedOut || false
            });
            this.model.set(this.name, existingAddresses);
            success = true;
        }

        return success;
    },

    /**
     * Update email address in the model.
     * @param {Number} index
     * @param {String} newEmail
     * @private
     */
    _updateExistingAddressInModel: function(index, newEmail) {
        var existingAddresses = app.utils.deepCopy(this.model.get(this.name));
        //Simply update the email address
        existingAddresses[index].email_address = newEmail;
        if (this.tplName === 'edit') {
            this.model.set(this.name + (index + 1), newEmail);
        }
        this.model.set(this.name, existingAddresses);
    },

    /**
     * Toggle email address properties: primary, opt-out, and invalid.
     * @param {Number} index
     * @param {String} property
     * @private
     */
    _toggleExistingAddressPropertyInModel: function(index, property) {
        var existingAddresses = app.utils.deepCopy(this.model.get(this.name));

        //If property is primary_address, we want to make sure one and only one primary email is set
        //As a consequence we reset all the primary_address properties to 0 then we toggle property for this index.
        if (property === 'primary_address') {
            existingAddresses[index][property] = false;
            _.each(existingAddresses, function(email, i) {
                if (email[property]) {
                    existingAddresses[i][property] = false;
                }
            });
        }

        // Toggle property for this email
        if (existingAddresses[index][property]) {
            existingAddresses[index][property] = false;
        } else {
            existingAddresses[index][property] = true;
        }

        this.model.set(this.name, existingAddresses);
    },

    /**
     * Remove email address from the model.
     * @param {Number} index
     * @return {boolean} Returns true if the removed address was the primary address.
     * @private
     */
    _removeExistingAddressInModel: function(index) {
        var existingAddresses = app.utils.deepCopy(this.model.get(this.name)),
            primaryAddressRemoved = !!existingAddresses[index]['primary_address'];

        //Reject this index from existing addresses
        existingAddresses = _.reject(existingAddresses, function (emailInfo, i) { return i == index; });

        // If a removed address was the primary email, we still need at least one address to be set as the primary email
        if (primaryAddressRemoved) {
            //Let's pick the first one
            var address = _.first(existingAddresses);
            if (address) {
                address.primary_address = true;
            }
        }

        this.model.set(this.name, existingAddresses);
        return primaryAddressRemoved;
    },

    /**
     * Clear out the new email address field.
     * @private
     */
    _clearNewAddressField: function() {
        this._getNewEmailField()
            .val('');
    },

    /**
     * Get the new email address input field.
     * @return {jQuery}
     * @private
     */
    _getNewEmailField: function() {
        return this.$('.newEmail');
    },

    /**
     * Need to call `decorateError` after all email fields are rendered.
     * @inheritdoc
     *
     * FIXME This is a temporary fix due to time constraints, a proper solution will be implemented in SC-4358
     */
    handleValidationError: function(errors) {
        this._super('handleValidationError', [errors]);
        _.defer(function (field) {
            field.decorateError(errors);
        }, this);
    },

    /**
     * Custom error styling for the e-mail field
     * @param {Object} errors
     * @override BaseField
     */
    decorateError: function(errors){
        var emails;

        this.$el.closest('.record-cell').addClass("error");

        //Select all existing emails
        emails = this.$('input:not(.newEmail)');

        _.each(errors, function(errorContext, errorName) {
            //For `email` validator the error is specific to an email
            if (errorName === 'email' || errorName === 'duplicateEmail') {

                // For each of our `sub-email` fields
                _.each(emails, function(e) {
                    var $email = this.$(e),
                        email = $email.val();

                    var isError = _.find(errorContext, function(emailError) { return emailError === email; });
                    // if we're on an email sub field where error occurred, add error styling
                    if(!_.isUndefined(isError)) {
                        this._addErrorDecoration($email, errorName, [isError]);
                    }
                }, this);
            //For required or primaryEmail we want to decorate only the first email
            } else {
                var $email = this.$('input:first');
                this._addErrorDecoration($email, errorName, errorContext);
            }
        }, this);
    },

    _addErrorDecoration: function($input, errorName, errorContext) {
        var isWrapped = $input.parent().hasClass('input-append');
        if (!isWrapped)
            $input.wrap('<div class="input-append error '+this.fieldTag+'">');
        $input.next('.error-tooltip').remove();
        $input.after(this.exclamationMarkTemplate([app.error.getErrorString(errorName, errorContext)]));
    },

    /**
     * Binds DOM changes to set field value on model.
     * @param {Backbone.Model} model model this field is bound to.
     * @param {String} fieldName field name.
     */
    bindDomChange: function() {
        if(this.tplName === 'list-edit') {
            this._super("bindDomChange");
        }
    },

    /**
     * To display representation
     * @param {string|Array|Object} value single email address or set of email addresses.
     */
    format: function(value) {
        value = app.utils.deepCopy(value);
        if (_.isArray(value) && value.length > 0) {
            // got an array of email addresses
            _.each(value, function(email) {
                // On render, determine which e-mail addresses need anchor tag included
                // Needed for handlebars template, can't accomplish this boolean expression with handlebars
                email.hasAnchor = this.def.emailLink && !email.invalid_email;
            }, this);
        } else if (_.isObject(value) && !_.isEmpty(value)) {
            // Expecting an object containing attributes for an email address
            value = [{
                email_address: value.email_address,
                email_address_id: value.id,
                primary_address: value.primary_address,
            }];
        } else if ((_.isString(value) && value !== "") || this.view.action === 'list') {
            // expected an array with a single address but got a string or an empty array
            value = [{
                email_address:value,
                primary_address:true,
                hasAnchor:true
            }];
        }

        if (value && value.length === 1) {
            value[0].soleEmail = true;
        }

        value = this.addFlagLabels(value);
        return value;
    },

    /**
     * Build label that gets displayed in tooltips.
     * @param {Object} value
     * @return {Object}
     */
    addFlagLabels: function(value) {
        var flagStr = "", flagArray;
        _.each(value, function(emailObj) {
            flagStr = "";
            flagArray = _.map(emailObj, function (flagValue, key) {
                if (!_.isUndefined(this._flag2Deco[key]) && this._flag2Deco[key].lbl && flagValue) {
                    return app.lang.get(this._flag2Deco[key].lbl);
                }
            }, this);
            flagArray = _.without(flagArray, undefined);
            if (flagArray.length > 0) {
                flagStr = flagArray.join(", ");
            }
            emailObj.flagLabel = flagStr;
        }, this);
        return value;
    },

    /**
     * To API representation
     * @param {String|Array} value single email address or set of email addresses
     */
    unformat: function(value) {
        if (this.view.action === 'list') {
            var emails = app.utils.deepCopy(this.model.get(this.name));

            if (!_.isArray(emails)) { // emails is empty, initialize array
                emails = [];
            }

            emails = _.map(emails, function(email) {
                if (email.primary_address && email.email_address !== value) {
                    email.email_address = value;
                }
                return email;
            }, this);

            // Adding a new email
            if (emails.length == 0) {
                emails.push({
                    email_address: value,
                    primary_address: true
                });
            }

            return emails;
        }

        if (this.view.action === 'filter-rows') {
            return value;
        }
    },

    /**
     * Opens a drawer to audit the email address.
     */
    auditEmailAddress: function() {
        var email = _.first(this.value);
        var emailModel = app.data.createBean('EmailAddresses', {
            name: email.email_address,
            id: email.email_address_id
        });

        var parentContext = this.context.getChildContext({
            forceNew: true,
            model: emailModel,
            module: 'EmailAddresses',
            modelId: email.email_address_id
        });

        app.drawer.open({
            layout: 'audit',
            context: {
                module: 'Audit',
                model: emailModel,
                parent: parentContext,
            }
        });
    },

    /**
     * Apply focus on the new email input field.
     */
    focus: function () {
        if(this.action !== 'disabled') {
            this._getNewEmailField().focus();
        }
    },

    /**
     * Retrieve link specific email options for launching the email client
     * Builds upon emailOptions on this
     *
     * @param {jQuery} $link
     * @private
     */
    _retrieveEmailOptionsFromLink: function($link) {
        return {
            to: [
                {
                    email: app.data.createBean('EmailAddresses', {
                        id: $link.data('email-address-id'),
                        email_address: $link.data('email-to'),
                        opt_out: $link.data('email-opt-out')
                    }),
                    bean: this.model
                }
            ]
        };
    },

    /**
     * @override
     *
     * Check if the value is a string representing the UUID.
     */
    _isErasedField: function() {
        if (!this.model) {
            return false;
        }

        var value = this.model.get(this.name);
        var erasedFields = this.model.get('_erased_fields');
        return _.isString(value) && _.contains(erasedFields, this.name);
    }
})
