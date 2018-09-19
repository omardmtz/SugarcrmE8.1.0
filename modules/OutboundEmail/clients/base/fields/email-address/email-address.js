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
 * @class View.Fields.Base.OutboundEmail.EmailAddressField
 * @alias SUGAR.App.view.fields.BaseOutboundEmailEmailAddressField
 * @extends View.Fields.Base.RelateField
 */
({
    extendsFrom: 'RelateField',

    /**
     * @inheritdoc
     *
     * This field should only ever be a single-select.
     */
    initialize: function(options) {
        options = options || {};
        options.def.isMultiSelect = false;

        this._super('initialize', [options]);

        // Use the RelateField templates.
        this.type = 'relate';

        // Semi-colon can only appear inside quotation marks in an email
        // address. Such a case is unlikely, so it is safer than a pipe,
        // which can appear in an email address without quotes.
        this._separator = ':';
    },

    /**
     * @inheritdoc
     *
     * Adds a `createSearchChoice` option.
     *
     * @see EmailAddressField#_createSearchChoice
     */
    _getSelect2Options: function() {
        var options = this._super('_getSelect2Options');

        options.createSearchChoice = _.bind(this._createSearchChoice, this);

        return options;
    },

    /**
     * Adds a new choice to the dropdown when the search term is a valid email
     * address that doesn't match any search results. This allows the user to
     * enter a new email address that doesn't yet exist in the database.
     *
     * @param {string} term The partial or full email address the user has
     * entered.
     * @return {Object|null} Returns `null` when the email address isn't valid
     * and should not be added to the dropdown.
     * @private
     */
    _createSearchChoice: function(term) {
        var $select2 = this._getSelect2();
        var hasContext = !!($select2 && $select2.context);
        var hasChoice = !!(hasContext && $select2.context.findWhere({email_address: term}));

        // Note: When `hasContext` is false, something went wrong with
        // associating the search collection with Select2. This leaves open the
        // possibility that the entered email address already exists. We allow
        // the user to select the choice anyway, and an attempt will be made to
        // create the email address. `EmailAddressesApi` will recognize the
        // duplicate email address and return the ID of the existing email
        // address. This will yield the same behavior as if searching had
        // worked as expected.

        if (!hasChoice && app.utils.isValidEmailAddress(term)) {
            // Add this choice to the search context so that the Select2 change
            // event handler can find the option among the results.
            if (hasContext) {
                $select2.context.add({
                    id: term,
                    email_address: term
                });
            }

            return {
                id: term,
                text: term
            };
        }

        return null;
    },

    /**
     * @inheritdoc
     *
     * When the selection is a new email address, that email address is created
     * on the server and the result is asynchronously applied to the model such
     * that the Select2 instance obtains the new ID for the email address.
     */
    _onFormatSelection: function(obj) {
        var email;
        var success;
        var error;
        var complete;

        if (obj.id === obj.text) {
            /**
             * Update the ID field with the ID of the newly created model.
             *
             * @param {Data.Bean} model The created EmailAddresses model.
             */
            success = _.bind(function(model) {
                this.setValue({
                    id: model.get('id'),
                    value: model.get('email_address')
                });
            }, this);

            /**
             * Clear the selection on an error when creating the model.
             */
            error = _.bind(function() {
                this.setValue({
                    id: '',
                    value: ''
                });
            }, this);

            /**
             * Remove the choice from the search context so that the Select2
             * change event doesn't ever find the option among its results. The
             * temporary option is replaced by the option that was created on
             * the server.
             *
             * Enables the action buttons once the request is done.
             */
            complete = _.bind(function() {
                var $select2 = this._getSelect2();

                if ($select2 && $select2.context) {
                    $select2.context.remove(obj.id);
                }

                if (_.isFunction(this.view.toggleButtons)) {
                    this.view.toggleButtons(true);
                }
            }, this);

            // Disable the action buttons while creating the new email address.
            if (_.isFunction(this.view.toggleButtons)) {
                this.view.toggleButtons(false);
            }

            email = app.data.createBean(this.getSearchModule(), {email_address: obj.text});
            email.save(null, {
                success: success,
                error: error,
                complete: complete
            });
        }

        return this._super('_onFormatSelection', [obj]);
    },

    /**
     * Convenience method for getting this field's Select2 instance.
     *
     * @return {Select2|undefined}
     * @private
     */
    _getSelect2: function() {
        var $el = this.$(this.fieldTag);

        return $el.data('select2');
    }
})
