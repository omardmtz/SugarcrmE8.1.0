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
 * Widget for changing a password.
 *
 * It does not require old password confirmation.
 *
 * @class View.Fields.Base.ChangePasswordField
 * @alias SUGAR.App.view.fields.BaseChangePasswordField
 * @extends View.Fields.Base.BaseField
 */
({
    fieldTag: 'input:not(:disabled)',

    events: {
        'click .togglePasswordFields': 'togglePasswordFields'
    },

    /**
     * @override
     * @param options
     */
    initialize: function(options) {
        app.view.Field.prototype.initialize.call(this, options);
        /**
         * Manually adds the validation error label to errorName2Keys
         * @type {string}
         */
        app.error.errorName2Keys['confirm_password'] = 'ERR_REENTER_PASSWORDS';
        this._extendModel();
    },

    /**
     * Extends the model
     * - adds a validation task _doValidatePasswordConfirmation : handle the password confirmation validation
     * - revertAttributes : to unset temporary attributes _new_password and _confirm_password
     */
    _extendModel: function() {
        // _hasChangePasswordModifs is a flag to make sure model methods are overriden only once
        if (this.model && !this.model._hasChangePasswordModifs) {
            // Make a copy of the model
            var _proto = _.clone(this.model);

            // This is the flag to make sure we do extend model only once
            this.model._hasChangePasswordModifs = true;

            /**
             * Validates new password and confirmation match
             *
             * @param {Object} fields Hash of field definitions to validate.
             * @param {Object} errors Error validation errors
             * @param {Function} callback Async.js waterfall callback
             */
            this.model._doValidatePasswordConfirmation = function(fields, errors, callback) {
                // Find any change password field
                var changePasswordFields = _.filter(fields, function(field) {
                    return field.type === 'change-password' || field.type === 'change-my-password';
                });
                _.each(changePasswordFields, function(field) {
                    // Get the new password and the confirmation
                    var password = this.get(field.name + '_new_password'),
                        confirmation = this.get(field.name + '_confirm_password');

                    /**
                     * Passwords don't match
                     */
                    if (password !== confirmation) {
                        // Adds the validation error
                        // confirm_password is added to errorName2Keys on initialize
                        errors[field.name] = errors[field.name] || {};
                        errors[field.name]['confirm_password'] = true;
                    } else if (!errors[field.name]) {
                        /**
                         * Passwords match
                         */
                        this.unset(field.name + '_current_password'); //Needs to be cleared for change-my-password
                        if (password !== '') {
                            this.unset(field.name + '_new_password');
                            this.unset(field.name + '_confirm_password');
                            this.set(field.name, password);
                        }
                    }
                }, this);

                callback(null, fields, errors);
            };

            /**
             * Adds the validation task to the model
             * @override
             * @param options
             */
            this.model.addValidationTask('password_confirmation_' + this.cid, _.bind(this.model._doValidatePasswordConfirmation, this.model));

            /**
             * Unsets new password and confirmation values on revertAttributes
             * @override
             * @param options
             */
            this.model.revertAttributes = function(options) {
                // Find any change password field
                var attrs = _.clone(this.attributes);
                _.each(attrs, function(value, attr) {
                    if (attr.match('_new_password') || attr.match('_confirm_password')) {
                        this.unset(attr);
                    }
                }, this);
                // Call the old method
                _proto.revertAttributes.call(this, options);
            };
        }
    },

    /**
     * @override
     * @private
     */
    _render: function() {
        if (this.model) {
            this.newPassword = this.model.get(this.name + '_new_password');
            this.confirmPassword = this.model.get(this.name + '_confirm_password');
            // Decides to display inputs or the link
            this.showPasswordFields = this.showPasswordFields ||
                //Show password fields if the formatted value is empty
                !this.format(this.value) ||
                //Show password fields if they aren't empty
                !!(this.newPassword || this.confirmPassword);
        }
        app.view.Field.prototype._render.call(this);
        this.showPasswordFields = false;
        this.$inputs = this.$(this.fieldTag);
        this.focusIndex = 0;
        return this;
    },

    /**
     * Sets an arbitrary value just to display stars on detail view
     * @override
     * @param {Boolean} value
     * @return {string} value
     */
    format: function(value) {
        if (value === true) return 'value_setvalue_set';
        return value;
    },

    /**
     * Reset the arbitrary value
     * @override
     * @param {String} value
     * @return {Mixed} value boolean is the value is not set
     */
    unformat: function(value) {
        if (value === 'value_setvalue_set') return true;
        return value;
    },

    /**
     * Sets a password attribute on the model of this field.
     *
     * @private
     * @param {Event} evt The event object.
     */
    _setPasswordAttribute: function(evt) {
        var $el = this.$(evt.currentTarget);
        var attr = $el.attr('name');
        var val = $el.val();

        this.model.set(this.name + '_' + attr, this.unformat(val));
    },

    /**
     * @override
     */
    bindDomChange: function() {
        if (!(this.model instanceof Backbone.Model)) return;

        this.$('input[name=new_password]').on('change.' + this.cid, _.bind(this._setPasswordAttribute, this));

        var self = this;
        this.$('input[name=confirm_password]').on('change.' + this.cid, function() {
            var val = self.unformat($(this).val());
            self.model.set(self.name + '_confirm_password', val);
            self.model.set(self.name, val);
        });

        this.$('input[name=new_password], input[name=confirm_password]').on('focus.' + this.cid, _.bind(this.handleFocus, this));
    },

    /**
     * @inheritdoc
     */
    unbindDom: function() {
        this.$('input[name=new_password], input[name=confirm_password]')
            .off('change.' + this.cid)
            .off('focus.' + this.cid);
        this._super('unbindDom');
    },

    /**
     * @return {Boolean} `true` if there is another input to focus, `false` if
     *   it is the last input already.
     */
    focus: function() {
        if (!this.$inputs.length) {
            this.togglePasswordFields();
        }
        // this should be zero but lets make sure
        if (this.focusIndex < 0) {
            this.focusIndex = 0;
        }

        if (this.focusIndex >= this.$inputs.length) {
            // done focusing our inputs return false
            this.focusIndex = -1;
            return false;
        } else {
            // focus the next item in our list of inputs
            this.$inputs[this.focusIndex].focus();
            this.focusIndex++;
            return true;
        }
    },

    /**
     * Displays inputs for the new password and the confirmation
     * @param event
     */
    togglePasswordFields: function(event) {
        this.showPasswordFields = true;
        this.render();
    },

    /**
     * Remove validation on the model.
     * @inheritdoc
     */
    _dispose: function() {
        this.model.removeValidationTask('password_confirmation_' + this.cid);
        this._super('_dispose');
    }
})
