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
 * @class View.Fields.Base.CurrencyField
 * @alias SUGAR.App.view.fields.BaseCurrencyField
 * @extends View.Fields.Base.BaseField
 */
({
    /**
     * list of events to listen for
     * @type {Object}
     */
    'events': {
        'click': 'updateCss'
    },
    /**
     * @type {String}
     * field value non-formatted or converted
     */
    transactionValue: '',
    /**
     * @type {Object}
     * reference to the currency dropdown field object
     */
    _currencyField: null,
    /**
     * @type {Boolean}
     * whether or not the currency dropdown is hidden from view
     */
    hideCurrencyDropdown: false,
    /**
     * @type {String}
     * last known record currency id
     */
    _lastCurrencyId: null,

    /**
     * @inheritdoc
     *
     * The direction for this field should always be `ltr`.
     */
    direction: 'ltr',

    /**
     * Do we have edit access to this field?
     */
    hasEditAccess: true,

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        var currencyField = this.def.currency_field || 'currency_id',
            currencyFieldValue, baseRateField, baseRateFieldValue;

        // should we ignore the user preference currency
        // currently this is only used in quotes
        var ignoreUserPrefCurrency = this.model.ignoreUserPrefCurrency || false;

        if (this.model.isNew() && (!this.model.isCopy()) && (!ignoreUserPrefCurrency)) {
            // new records are set the user's preferred currency
            currencyFieldValue = app.user.getPreference('currency_id');
            this.model.set(currencyField, currencyFieldValue);

            // set the base rate for the user's preferred currency
            baseRateField = this.def.base_rate_field || 'base_rate';
            baseRateFieldValue = app.metadata.getCurrency(currencyFieldValue).conversion_rate;
            this.model.set(baseRateField, baseRateFieldValue);

            // Modules such as `Forecasts` uses models that aren't `Data.Bean`
            if (_.isFunction(this.model.setDefault)) {
                var defaults = {};
                defaults[currencyField] = currencyFieldValue;
                defaults[baseRateField] = baseRateFieldValue;
                this.model.setDefault(defaults);
            }
        }
        this.hasEditAccess = app.acl.hasAccess('edit', this.model.module, undefined, this.name);
        // hide currency dropdown on list views
        this.hideCurrencyDropdown = this.view.action === 'list';
        // track the last currency id to convert the value on change
        this._lastCurrencyId = this.model.get(currencyField);
    },

    /**
     * @inheritdoc
     *
     * Setup transactional amount if flag is present and transaction currency
     * is not base.
     * On edit view render the currency enum field associated with this field on
     * the correct placeholder
     *
     * @return {Object} this
     * @private
     */
    _render: function() {
        if (this._currencyField) {
            this._currencyField.dispose();
            this._currencyField = null;
        }
        this._super('_render');
        if (this.hideCurrencyDropdown === false && this.tplName === 'edit') {
            this.getCurrencyField().setElement(this.$('span[sfuuid="' + this.currencySfId + '"]'));
            this.$el.find('div.select2-container').css('min-width', '8px');
            this.getCurrencyField().render();
        }
        return this;
    },

    handleValidationError: function(errors) {
        this._super('handleValidationError', [errors]);
        _.defer(function (field) {
            field.clearErrorDecoration();
            field.decorateError(errors);

        }, this);
    },

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
     *
     * If the incoming value is the same as the value on the model
     * then just set the currency value so it's formatted correctly
     * otherwise, set the new value on the model
     */
    bindDomChange: function() {
        if (!(this.model instanceof Backbone.Model)) {
            return;
        }

        var self = this;
        var el = this.$el.find(this.fieldTag);
        el.on('change', function() {
            var val = self.unformat(el.val());
            if (_.isEqual(val, self.model.get(self.name))) {
                self.setCurrencyValue(val);
            } else {
                self.model.set(self.name, val);
            }
        });
    },

    /**
     * When currency changes, we need to make appropriate silent changes to the base rate.
     */
    bindDataChange: function() {
        // we do not call the parent which re-renders,
        // but instead update the value on the field directly
        this.model.on('change:' + this.name, this._valueChangeHandler, this);

        if (this.def.is_base_currency) {
            // do not add change handler to _usdollar fields
            return;
        }

        var currencyField = this.def.currency_field || 'currency_id';
        var baseRateField = this.def.base_rate_field || 'base_rate';
        // if the current_user doesn't have edit access to the field
        // don't add these listeners
        if (this.hasEditAccess && this.view.action !== 'list') {
            this.model.on('change:' + baseRateField, this.handleBaseRateFieldChange, this);
            this.model.on('change:' + currencyField, function(model, currencyId, options) {
                //When model is reset, it should not be called
                if (!currencyId || !this._lastCurrencyId || options.revert === true) {
                    this._lastCurrencyId = currencyId;
                    return;
                }

                if (_.has(model.changed, this.name)) {
                    // if this field is on the view more than once, this will trigger x number of times. so if the
                    // currency_id has changed and this field has already changed on the model, we should ignore it.
                    return;
                }

                // update the base rate in the model, set it silently since we are already going to do a re-render
                this.model.set(baseRateField, app.metadata.getCurrency(currencyId).conversion_rate, {silent: true});

                if (!_.isUndefined(this.view.getField('base_rate'))) {
                    this.view.getField('base_rate').render();
                }

                // convert the value to new currency on the model
                if (model.has(this.name)) {
                    var val = model.get(this.name);
                    if (val) {
                        this.model.set(
                            this.name,
                            app.currency.convertAmount(
                                val,
                                this._lastCurrencyId,
                                currencyId
                            ),
                            // we don't want to affect other bindings like sugar logic
                            // when updating a value upon a currency_id change,
                            // so set the model silently, then update the field value
                            // directly (see next func call)
                            {silent: true}
                        );
                    }
                    // now defer changes to the end of the thread to avoid conflicts
                    // with other events (from SugarLogic, etc.)
                    this._deferModelChange();
                }
                this._lastCurrencyId = currencyId;
            }, this);
        }
    },

    /**
     * handles when the base rate changes.  Defers model changes and re-renders the field
     * so that the currency symbol changes when a 0 amount is switched between currencies.
     * @private
     */
    handleBaseRateFieldChange: function(model, currencyId, options) {
        var baseRateField = this.def.base_rate_field || 'base_rate';
        var prevBaseRate = model.previous(baseRateField);
        var baseRate = model.get(baseRateField);
        var precision;
        var newValue;
        var previousValue;

        if (!_.isUndefined(prevBaseRate)) { // it is undefined, of course, at first load
            precision = this.def && this.def.precision || 6;
            // lets actually make sure this really changed before triggering the deferModelChange method.
            // that way if base_rate is a integer we can actually make sure it didn't change
            // eg: 1 to "1.000000"
            newValue = app.math.round(baseRate, precision, true);
            previousValue = app.math.round(prevBaseRate, precision, true);
            if (!_.isEqual(newValue, previousValue)) {
                if (options && _.isUndefined(options.revert)) {
                    this._deferModelChange();
                }
            }
        }
        //rerender the currency field. This is needed if a currency field is 0, but changes currencies.  Since
        //$0 and 0 EUR are the same, it doesn't detect the switch and 0 fields can show the wrong currency symbol.
        this._render();
    },

    /**
     * Trigger the model change, but only if the current user has edit access to it.
     *
     * @private
     */
    _deferModelChange: function() {
        if (this.hasEditAccess) {
            _.defer(_.bind(function() {
                if (!this.disposed) {
                    this.model.trigger('change:' + this.name, this.model, this.model.get(this.name));
                }
            }, this));
        }
    },

    /**
     * Handler for when the value changes on the model.
     *
     * If action does not match edit, field is re-rendered, otherwise the field
     * value is updated, plus, if the currency of the given model is different
     * from the one we have, the supplied amount is also converted to the new
     * currency.
     *
     * @param {Data.Bean} model Model.
     * @param {String} value Amount.
     * @private
     */
    _valueChangeHandler: function(model, value) {
        if (this.action != 'edit') {
            this.render();
            return;
        }

        if (model.get('currency_id') !== this.model.get('currency_id')) {
            value = app.currency.convertAmount(
                value,
                model.get('currency_id'),
                this.model.get('currency_id')
            );
        }

        this.setCurrencyValue(value);
    },

    /**
     * set the currency value on the field directly
     *
     * @param {String} value
     */
    setCurrencyValue: function(value) {
        this.$('[name=' + this.name + ']').val(app.utils.formatNumberLocale(value));
    },

    /**
     * @inheritdoc
     *
     * Convert to base currency if flag is present.
     *
     * @param {Array/Object/String/Number/Boolean} value The value to format.
     * @return {String} the formatted value based on view name.
     */
    format: function(value) {
        if (_.isNull(value) || _.isUndefined(value) || _.isNaN(value)) {
            value = '';
        }

        if (this.tplName === 'edit') {
            this.currencySfId = this.getCurrencyField().sfId;
            return app.utils.formatNumberLocale(value);
        }

        var baseRate = this.model.get(this.def.base_rate_field || 'base_rate');
        var transactionalCurrencyId = this.model.get(this.def.currency_field || 'currency_id'),
            convertedCurrencyId = transactionalCurrencyId,
            origTransactionValue = value;

        // TODO review this forecasts requirement and make it work with css defined on metadata
        // force this to recalculate the transaction value if needed
        // and more importantly, clear out previous transaction value
        this.transactionValue = '';
        if (value === '') {
            return value;
        }
        if (this.def.is_base_currency) {
            // usdollar field, treat the field as base currency
            transactionalCurrencyId = convertedCurrencyId = app.currency.getBaseCurrencyId();
        } else {
            if (this.def.convertToBase && transactionalCurrencyId !== app.currency.getBaseCurrencyId()) {
                if (this.def.showTransactionalAmount) {
                    this.transactionValue = app.currency.formatAmountLocale(
                        this.model.get(this.name) || 0,
                        transactionalCurrencyId
                    );
                }
                value = app.currency.convertWithRate(value, baseRate) || 0;
                convertedCurrencyId = app.currency.getBaseCurrencyId();
            }
        }
        // convert value to user preferred currency
        if ((this.def.is_base_currency || this.def.convertToBase) &&
            !this.def.skip_preferred_conversion &&
            app.user.get('preferences').currency_show_preferred) {
                var userPreferredCurrencyId = app.user.getPreference('currency_id');
                if (userPreferredCurrencyId !== transactionalCurrencyId) {
                    convertedCurrencyId = userPreferredCurrencyId;

                    // when we are displaying in the user preferred currency, the transactional
                    // amount should equal the row amount
                    this.transactionValue = app.currency.formatAmountLocale(
                        this.model.get(this.name) || 0,
                        transactionalCurrencyId
                    );
                    value = app.currency.convertWithRate(
                        value,
                        '1.0',
                        app.metadata.getCurrency(userPreferredCurrencyId).conversion_rate
                    );
                } else {
                    // user preferred same as transactional, no conversion required
                    this.transactionValue = '';
                    convertedCurrencyId = transactionalCurrencyId;
                    value = origTransactionValue;
                }
        }
        return app.currency.formatAmountLocale(value, convertedCurrencyId);
    },

    /**
     * @inheritdoc
     *
     * @param {String} value The value to unformat.
     * @return {Number} Unformatted value.
     */
    unformat: function(value) {
        var unformattedValue;
        if (this.tplName === 'edit') {
            unformattedValue = app.utils.unformatNumberStringLocale(value);
        } else {
            unformattedValue = app.currency.unformatAmountLocale(value);
        }

        // if we got a number back and we have a precision we should round to that precision as that is what will
        // be stored in the db, this is needed just in case SugarLogic is used on this field's value
        if (_.isFinite(unformattedValue)) {
            // if no precision is defined, default to 6 which is the system default
            var precision = this.def && this.def.precision || 6;
            return app.math.round(unformattedValue, precision, true);
        }

        return value;
    },

    /**
     * update dropdown css to active state
     */
    updateCss: function() {
        $('div.select2-drop.select2-drop-active').width('auto');
    },

    /**
     * Get the currency field related to this currency amount.
     *
     * @return {View.Field} the currency field associated.
     */
    getCurrencyField: function() {

        if (!_.isNull(this._currencyField)) {
            return this._currencyField;
        }

        var currencyDef = this.model.fields[this.def.currency_field || 'currency_id'];
        currencyDef.type = 'enum';
        currencyDef.options = app.currency.getCurrenciesSelector(Handlebars.compile('{{symbol}} ({{iso4217}})'));
        currencyDef.enum_width = '100%';
        currencyDef.searchBarThreshold = this.def.searchBarThreshold || 7;

        this._currencyField = app.view.createField({
            def: currencyDef,
            view: this.view,
            viewName: this.tplName,
            model: this.model
        });
        this._currencyField.defaultOnUndefined = false;

        return this._currencyField;
    },

    /**
     * set the mode of the dropdown field
     * @param {String} the mode name.
     */
    setMode: function(name) {
        this._super('setMode', [name]);
        if (this.action === 'edit') {
            this.getCurrencyField().setMode(name);
        }
    },

    /**
     * @inheritdoc
     */
    dispose: function() {
        if (this._currencyField) {
            this._currencyField.dispose();
            this._currencyField = null;
        }
        this._super('dispose');
    }
})
