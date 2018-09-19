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
 * @class View.Fields.Base.Products.DiscountField
 * @alias SUGAR.App.view.fields.BaseProductsDiscountField
 * @extends View.Fields.Base.CurrencyField
 */
({
    extendsFrom: 'CurrencyField',

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        var validationTaskName = 'isNumeric_validator_' + this.cid;

        // removing the validation task if it exists already for this field
        this.model.removeValidationTask(validationTaskName);
        this.model.addValidationTask(validationTaskName, _.bind(this._validateAsNumber, this));
    },

    /**
     * Overriding to add the custom validation handler to the dom change event
     *
     * @inheritdoc
     */
    bindDomChange: function() {
        if (!(this.model instanceof Backbone.Model)) {
            return;
        }

        var $el = this.$(this.fieldTag);
        if ($el.length) {
            $el.on('change', _.bind(function(evt) {
                var val = evt.currentTarget.value;

                this.clearErrorDecoration();
                this.model.set(this.name, this.unformat(val));
                this.model.doValidate(this.name, _.bind(this._validationComplete, this));
            }, this));
        }
    },

    /**
     * Callback for after validation runs.
     * @param {bool} isValid flag determining if the validation is correct
     * @private
     */
    _validationComplete: function(isValid) {
        if (isValid) {
            app.alert.dismiss('invalid-data');
        }
    },

    /**
     * @inheritdoc
     *
     * Listen for the discount_select field to change, when it does, re-render the field
     */
    bindDataChange: function() {
        this._super('bindDataChange');

        // if discount select changes, we need to re-render this field
        this.model.on('change:discount_select', this.render, this);
    },

    /**
     * @inheritdoc
     *
     * Special handling of the templates, if we are displaying it as a percent, then use the _super call,
     * otherwise get the templates from the currency field.
     */
    _loadTemplate: function() {
        if (this.model.get('discount_select') == true) {
            this._super('_loadTemplate');
        } else {
            this.template = app.template.getField('currency', this.action || this.view.action, this.module) ||
                app.template.empty;
            this.tplName = this.action || this.view.action;
        }
    },

    /**
     * @inheritdoc
     *
     * Special handling for the format, if we are in a percent, use the decimal field to handle the percent, otherwise
     * use the format according to the currency field
     */
    format: function(value) {
        if (this.model.get('discount_select') == true) {
            return app.utils.formatNumberLocale(value);
        } else {
            return this._super('format', [value]);
        }
    },

    /**
     * @inheritdoc
     *
     * Special handling for the unformat, if we are in a percent, use the decimal field to handle the percent,
     * otherwise use the format according to the currency field
     */
    unformat: function(value) {
        if (this.model.get('discount_select') == true) {
            var unformattedValue = app.utils.unformatNumberStringLocale(value, true);
            // if unformat failed, return original value
            return _.isFinite(unformattedValue) ? unformattedValue : value;
        } else {
            return this._super('unformat', [value]);
        }
    },

    /**
     * Validate the discount field as a number - do not allow letters
     *
     * @param {Object} fields The list of field names and their definitions.
     * @param {Object} errors The list of field names and their errors.
     * @param {Function} callback Async.js waterfall callback.
     * @private
     */
     _validateAsNumber: function(fields, errors, callback) {
        var value = this.model.get(this.name);

        if (!_.isFinite(value)) {
            errors[this.name] = {'number': value};
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
        var validationTaskName = 'isNumeric_validator_' + this.cid;
        this.model.removeValidationTask(validationTaskName);

        this._super('_dispose');
    }
})
