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
 * @class Model.Datas.Base.EmailParticipantsModel
 * @alias SUGAR.App.model.datas.BaseEmailParticipantsModel
 * @extends Data.Bean
 */
({
    /**
     * @inheritdoc
     *
     * Patches the model with the parent's name if the name is empty, but it
     * can be computed from other attributes.
     */
    set: function(key, val, options) {
        var result = app.Bean.prototype.set.call(this, key, val, options);
        var parent = this.getParent();
        var name;

        // If the name field is empty but other fields are present, then it may
        // be possible to construct the name. We try to do that before assuming
        // the name can't be found.
        if (parent && !parent.get('name') && !app.utils.isNameErased(parent)) {
            name = app.utils.getRecordName(parent);

            // Replicate the name to all of the fields on the model that
            // contain the name, so we don't have to construct it again.
            if (name) {
                this.get('parent').name = name;
                this.set('parent_name', name);
            }
        }

        return result;
    },

    /**
     * Returns a string representing the email participant in the format that
     * would be used for an address in an email address header. Note that the
     * name is not surrounded by quotes unless the `surroundNameWithQuotes`
     * parameter is `true`.
     *
     * @example
     * // With name and email address.
     * Will Westin <will@example.com>
     * @example
     * // Without name.
     * will@example.com
     * @example
     * // Surround name with quotes.
     * "Will Westin" <will@example.com>
     * @example
     * // Name has been erased via a data privacy request.
     * Value erased <will@example.com>
     * @example
     * // Email address has been erased via a data privacy request.
     * Will Westin <Value erased>
     * @param {Object} [options]
     * @param {boolean} [options.quote_name=false]
     * @return {string}
     */
    toHeaderString: function(options) {
        var name = this.get('parent_name') || '';
        var email = this.get('email_address') || '';

        options = options || {};

        // The name was erased, so let's use the label.
        if (_.isEmpty(name) && this.isNameErased()) {
            name = app.lang.get('LBL_VALUE_ERASED', this.module);
        }

        // The email was erased, so let's use the label.
        if (_.isEmpty(email) && this.isEmailErased()) {
            email = app.lang.get('LBL_VALUE_ERASED', this.module);
        }

        if (_.isEmpty(name)) {
            return email;
        }

        if (_.isEmpty(email)) {
            return name;
        }

        if (options.quote_name) {
            name = '"' + name + '"';
        }

        return name + ' <' + email + '>';
    },

    /**
     * Determines if there is really a parent record.
     *
     * The type and id fields are not unset after a parent record is deleted.
     * So we test for name because the parent record is truly only there if
     * type and id are non-empty and the parent record can be resolved and has
     * not been deleted.
     *
     * @return {boolean}
     */
    hasParent: function() {
        var parent = this.getParent();

        return !!(parent && (parent.get('name') || app.utils.isNameErased(parent)));
    },

    /**
     * Returns a bean from the parent data or undefined if no parent exists.
     *
     * @return {undefined|Data.Bean}
     */
    getParent: function() {
        if (this.get('parent') && this.get('parent').type && this.get('parent').id) {
            // We omit type because it is actually the module name and should
            // not be treated as an attribute.
            return app.data.createBean(this.get('parent').type, _.omit(this.get('parent'), 'type'));
        }
    },

    /**
     * Returns true of the parent record's name has been erased.
     *
     * @return {boolean}
     */
    isNameErased: function() {
        var parent;

        if (this.hasParent()) {
            parent = this.getParent();

            if (parent) {
                return app.utils.isNameErased(parent);
            }
        }

        return false;
    },

    /**
     * Returns true if the email address has been erased.
     *
     * @return {boolean}
     */
    isEmailErased: function() {
        var link = this.get('email_addresses');
        var erasedFields = link && link._erased_fields ? link._erased_fields : [];

        return _.isEmpty(erasedFields) ? false : _.contains(erasedFields, 'email_address');
    }
})
