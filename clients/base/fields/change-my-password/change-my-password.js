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
 * @class View.Fields.Base.ChangeMyPasswordField
 * @alias SUGAR.App.view.fields.BaseChangeMyPasswordField
 * @extends View.Fields.Base.ChangePasswordField
 */
({
    extendsFrom: 'ChangePasswordField',

    /**
     * @inheritdoc
     */
    fieldTag: 'input',

    /**
     * @override
     * @param options
     */
    initialize: function(options) {
        this._super("initialize", [options]);
        /**
         * Manually adds the validation error label to errorName2Keys
         * @type {string}
         */
        app.error.errorName2Keys['current_password'] = 'ERR_PASSWORD_MISMATCH';
        app.error.errorName2Keys['new_password'] = 'ERR_ENTER_NEW_PASSWORD';

        this.__extendModel();
    },


    /**
     * Extends the model (note that the model is already extended by ChangePasswordField)
     * - adds a validation task _doValidateCurrentPassword : handle the current password validation
     * - revertAttributes : to unset temporary attributes _current_password
     */
    __extendModel: function() {

        // _hasChangePasswordModifs is a flag to make sure model methods are overriden only once
        if (this.model && !this.model._hasChangeMyPasswordModifs) {
            // Make a copy of the model
            var _proto = _.clone(this.model);

            // This is the flag to make sure we do override methods only once
            this.model._hasChangeMyPasswordModifs = true;

            /**
             * Validates current password against server
             *
             * @param {Object} fields Hash of field definitions to validate.
             * @param {Object} errors Error validation errors
             * @param {Function} callback Async.js waterfall callback
             * @private
             */
            this.model._doValidateCurrentPassword = function(fields, errors, callback) {
                // Find the change my password field
                var field = _.find(fields, function(field) {
                    return field.type === 'change-my-password';
                });

                // change-my-password field was not changed, so
                // don't attempt to validate password
                if (!field) {
                    callback(null, fields, errors);
                    return;
                }

                //Get the current password
                var current = this.get(field.name + '_current_password');
                var password = this.get(field.name + '_new_password'),
                    confirmation = this.get(field.name + '_confirm_password');

                if (_.isEmpty(current) && _.isEmpty(password) && _.isEmpty(confirmation)) {
                    callback(null, fields, errors);
                    return;
                }
                //current is non-empty but we haven't put new/confirm passwords
                if (!_.isEmpty(current) && _.isEmpty(password) && _.isEmpty(confirmation)) {
                    errors[field.name] = errors[field.name] || {};
                    errors[field.name]['new_password'] = true;
                    callback(null, fields, errors);
                    return;
                }
                //Validate current password
                var alertOptions = {
                    title: app.lang.get("LBL_VALIDATING"),
                    level: "process"
                };
                app.alert.show('validation', alertOptions);

                app.api.verifyPassword(current, {
                    success: function(data) {
                        if(!data || !data.valid) {
                            errors[field.name] = errors[field.name] || {};
                            errors[field.name]['current_password'] = true;
                        }
                    },
                    error: function(error) {
                        errors[field.name] = errors[field.name] || {};
                        errors[field.name]['current_password'] = true;
                    },
                    /**
                     * After check is done, close alert and trigger the completion of the validation to the editor
                     */
                    complete: function() {
                        app.alert.dismiss('validation');
                        callback(null, fields, errors);
                    }
                });
            };
            this.model.addValidationTask('current_password_' + this.cid, _.bind(this.model._doValidateCurrentPassword, this.model));

            this.model.revertAttributes = function(options) {
                // Find any change password field
                var attrs = _.clone(this.attributes);
                _.each(attrs, function(value, attr) {
                    if (attr.match('_current_password')) {
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
     * @param {Boolean} value
     * @return {string} value
     */
    format: function(value) {
        if (this.action === 'edit') {
            this.currentPassword = this.model.get(this.name + '_current_password');
            value = '';
        } else if (value === true) {
            value = 'value_setvalue_set';
        }
        return value;
    },

    /**
     * @override
     */
    decorateError: function (errors) {
        var ftag = this.fieldTag;
        if (errors['current_password']) {
            this.fieldTag = 'input[name=current_password]';
            app.view.Field.prototype.decorateError.call(this, {current_password: true});
        }
        errors = _.omit(errors, 'current_password');
        if (!_.isEmpty(errors)) {
            this.fieldTag = 'input[name!=current_password]';
            app.view.Field.prototype.decorateError.call(this, errors);
        }
        this.fieldTag = ftag;
    },

    /**
     * @override
     */
    clearErrorDecoration: function () {
        var self = this,
            ftag = this.fieldTag || '',
            $ftag = this.$(ftag);
        // Remove previous exclamation then add back.
        this.$('.add-on').remove();

        //Not all inputs are necessarily wrapped so check each individually
        $ftag.each(function(index, el) {
            var isWrapped = self.$(el).parent().hasClass('input-append');
            if (isWrapped) {
                self.$(el).unwrap();
            }
        });
        this.$el.removeClass(ftag);
        this.$el.removeClass("error");
        this.$el.closest('.record-cell').removeClass("error");
    },

    /**
     * @override
     */
    bindDomChange: function() {
        this.$('input[name=current_password], input[name=new_password], input[name=confirm_password]').on('change.' + this.cid, _.bind(this._setPasswordAttribute, this));
    },

    /**
     * @inheritdoc
     */
    unbindDom: function() {
        this.$('input[name=current_password]').off('change.' + this.cid);
        this._super('unbindDom');
    },

    /**
     * Remove validation on the model.
     * @inheritdoc
     */
    _dispose: function() {
        this.model.removeValidationTask('current_password_' + this.cid);
        this._super('_dispose');
    }

})
