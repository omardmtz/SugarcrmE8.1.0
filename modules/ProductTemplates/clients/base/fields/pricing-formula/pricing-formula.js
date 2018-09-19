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
 * Field that computes the logic for the pricing factor field
 *
 * @class View.Fields.Base.ProductTemplates.PricingFormulaField
 * @alias SUGAR.App.view.fields.BaseProductTemplatesPricingFormulaField
 * @extends View.Fields.Base.EnumField
 */
({
    /**
     * Where the core logic is at
     */
    extendsFrom: 'EnumField',

    /**
     * Should we show the factor field on the front end
     */
    showFactorField: false,

    /**
     * Valid formulas that we should show the factor field for.
     */
    validFactorFieldFormulas: [
        'ProfitMargin',
        'PercentageMarkup',
        'PercentageDiscount'
    ],

    /**
     * Label for the factor field
     */
    factorFieldLabel: '',

    /**
     * Value of the factor field
     */
    factorValue: 0,

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        this.before('render', function() {
            this.showFactorField = this.checkShouldShowFactorField();
            this.factorFieldLabel = this.getFactorFieldLabel();
            this.disableDiscountField();
            this.factorValue = this.model.get('pricing_factor');
        }, this);

        this.listenTo(this, 'render', function() {
            // only setup the formulas when the action is edit
            if (this.action == 'edit') {
                    if (this.showFactorField) {
                    // put the cursor int he factor field once this is rendered
                    this.$el.find('.pricing-factor').focus();
                }
                this.setupPricingFormula();
            }
        });
    },

    /**
     * Listen for this field to change it's value, and when it does, we should re-render the field as it could have
     * the pricing_factor field visible
     */
    bindDataChange: function() {
        this.listenTo(this.model, 'change:' + this.name, function() {
            // when it's changed, we need to re-render just in case we need to show the factor field
            if (!this.disposed) {
                this.render();
            }
        });
    },

    /**
     * Override to remove default DOM change listener so we can listen for the pricing factor change if it's visible
     *
     * @inheritdoc
     */
    bindDomChange: function() {
        if (this.showFactorField) {
            var $el = this.$('.pricing-factor');
            $el.on('change', _.bind(function() {
                this.model.set('pricing_factor', $el.val());
            }, this));
        }

        // call the super just in case something ever gets put there
        this._super('bindDomChange');
    },

    /**
     * Override so we can stop listening to the pricing factor field if it's visible
     *
     * @inheritdoc
     */
    unbindDom: function() {
        if (this.showFactorField) {
            this.$('.pricing-factor').off();
        }

        // call the super
        this._super('unbindDom');
    },

    /**
     * Utility Method to check if we should show the factor field or not
     * @return {*|boolean}
     */
    checkShouldShowFactorField: function() {
        return (this.model.has(this.name) && _.contains(this.validFactorFieldFormulas, this.model.get(this.name)));
    },

    /**
     * Get the correct label for the field type
     */
    getFactorFieldLabel: function() {
        if (this.model.has(this.name)) {
            switch (this.model.get(this.name)) {
                case 'ProfitMargin':
                    return (this.action === 'edit' && this.view.action === 'list') ? 'LBL_POINTS_ABBR' : 'LBL_POINTS';
                case 'PercentageMarkup':
                case 'PercentageDiscount':
                    return (this.action === 'edit' && this.view.action === 'list') ? '%' : 'LBL_PERCENTAGE';
            }
        }

        return '';
    },

    /**
     * Figure out which formula to setup based off the value from the model.
     */
    setupPricingFormula: function() {
        if (this.model.has(this.name)) {
            switch (this.model.get(this.name)) {
                case 'ProfitMargin':
                    this._setupProfitMarginFormula();
                    break;
                case 'PercentageMarkup':
                    this._setupPercentageMarkupFormula();
                    break;
                case 'PercentageDiscount':
                    this._setupPercentageDiscountFormula();
                    break;
                case 'IsList':
                    this._setupIsListFormula();
                    break;
                default:
                    var oldPrice = this.model.get('discount_price');
                    if (_.isUndefined(oldPrice) || _.isNaN(oldPrice)) {
                        this.model.set('discount_price', '');
                    }
                    break;
            }
        }
    },

    /**
     * Profit Margin Formula
     *
     * ($cost_price * 100)/(100 - $points)
     *
     * @private
     */
    _setupProfitMarginFormula: function() {
        var formula = function(cost_price, points) {
            return app.math.div(app.math.mul(cost_price, 100), app.math.sub(100, points));
        };

        this._costPriceFormula(formula);
    },

    /**
     * Percent Markup
     *
     * $cost_price * (1 + ($percentage/100))
     *
     * @private
     */
    _setupPercentageMarkupFormula: function() {
        var formula = function(cost_price, percentage) {
            return app.math.mul(cost_price, app.math.add(1, app.math.div(percentage, 100)));
        };

        this._costPriceFormula(formula);
    },

    /**
     * Percent Discount from List Price
     *
     * $list_price - ($list_price * ($percentage/100))
     *
     * @private
     */
    _setupPercentageDiscountFormula: function() {
        var formula = function(list_price, percentage) {
            return app.math.sub(list_price, app.math.mul(list_price, app.math.div(percentage, 100)));
        };

        this._costPriceFormula(formula, 'list_price');
    },

    /**
     * Utility Method to handle multiple formulas using the same listener for cost_price, just pass in a function
     * that handles the formula and accepts two params, cost_price and the pricing factor.
     * @param {Function} formula
     * @param {String} [field]      What field to use in the listenTo, if undefined, it will default to cost_price
     * @private
     */
    _costPriceFormula: function(formula, field) {
        field = field || 'cost_price'
        this.listenTo(this.model, 'change:' + field, function(model, price) {
            model.set('discount_price', formula(price, model.get('pricing_factor')));
        });

        this.listenTo(this.model, 'change:pricing_factor', function(model, pricing_factor) {
            model.set('discount_price', formula(model.get(field), pricing_factor));
        });

        // run this now just to make sure if default values are already set
        this.model.set('discount_price', formula(this.model.get(field), this.model.get('pricing_factor')));
    },

    /**
     * Code to handle when the pricing formula is IsList where discount_price is the same as list_price
     *
     * @private
     */
    _setupIsListFormula: function() {
        this.listenTo(this.model, 'change:list_price', function(model, value) {
            model.set('discount_price', value);
        });

        this.model.set('discount_price', this.model.get('list_price'));
    },

    /**
     * Method to handle when the discount_price field should be disable or not.
     */
    disableDiscountField: function() {
        if (this.model.has(this.name)) {
            var field = this.view.getField('discount_price');
            if (field) {
                switch (this.model.get(this.name)) {
                    case 'ProfitMargin':
                    case 'PercentageMarkup':
                    case 'PercentageDiscount':
                    case 'IsList':
                        field.setDisabled(true);
                        break;
                    default:
                        field.setDisabled(false);
                        break;
                }
            }
        }
    }
})
