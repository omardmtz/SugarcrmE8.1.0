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
 * @class View.Fields.Base.Quotes.QuoteFooterCurrency
 * @alias SUGAR.App.view.fields.BaseQuotesQuoteFooterCurrency
 * @extends View.Fields.Base.CurrencyField
 */
({
    extendsFrom: 'CurrencyField',

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        var isCreate = options.context.isCreate();
        options.viewName = isCreate ? 'edit' : 'detail';

        this._super('initialize', [options]);

        if (!isCreate) {
            // only add this event on record view
            this.events = _.extend({
                'click .currency-field': '_toggleFieldToEdit'
            }, this.events);
        }

        this.model.addValidationTask(
            'isNumeric_validator_' + this.cid,
            _.bind(this._doValidateIsNumeric, this)
        );

        this.action = isCreate ? 'edit' : 'detail';

        this.context.trigger('quotes:editableFields:add', this);
    },

    /**
     * Toggles the field to edit if it not in edit
     *
     * @param {jQuery.Event} evt jQuery click event
     * @private
     */
    _toggleFieldToEdit: function(evt) {
        var record;

        if (!this.$el.hasClass('edit')) {
            this.action = 'edit';
            this.tplName = 'detail';

            // if this isn't already in edit, toggle to edit
            record = this.closestComponent('record');
            if (record) {
                record.context.trigger('editable:handleEdit', evt);
            }
        }
    },

    /**
     * Validation function to check to see if a value is numeric.
     *
     * @param {Array} fields
     * @param {Array} errors
     * @param {Function} callback
     * @private
     */
    _doValidateIsNumeric: function(fields, errors, callback) {
        var value = this.model.get(this.name);
        if (!$.isNumeric(value)) {
            errors[this.name] = app.lang.get('ERROR_NUMBER');
        }
        callback(null, fields, errors);
    },

    /**
      * Extending to remove the custom validation task for this field
      *
      * @inheritdoc
      * @private
      */
    _dispose: function() {
        this.model.removeValidationTask('isNumeric_validator_' + this.cid);
        this._super('_dispose');
    }
});
