
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

const math = require('utils/math');
const User = require('core/user');
const Utils = require('utils/utils');

/**
 * Currency module provides utility methods for working with currencies.
 *
 * @module Utils/Currency
 */
 module.exports = {
    /**
     * Gets the base currency id.
     *
     * @return {string} The base currency id.
     */
    getBaseCurrencyId: function() {
        return SUGAR.App.metadata.getBaseCurrencyId();
    },

    /**
     * Gets the system's base currency.
     *
     * @return {Object} The base currency data.
     */
    getBaseCurrency: function() {
        var currId = SUGAR.App.metadata.getBaseCurrencyId(),
            currencyObj = SUGAR.App.metadata.getCurrency(currId);

        // add currency_id to returned data
        currencyObj.currency_id = currId;

        return currencyObj;
    },

    /**
     * Returns a map of currency IDs to the result of applying the
     * given Handlebars template to them.
     *
     * Example for the `template` param:
     * ```
     *   getCurrenciesSelector(Handlebars.compile('{{symbol}} ({{iso}})'));
     * ```
     *
     * @param {Function} template How to format the values returned.
     * @return {Object} Map from currency IDs to formatted currency data.
     */
    getCurrenciesSelector: function(template) {
        var currencies = {};

        _.each(SUGAR.App.metadata.getCurrencies(), function(currency, id) {
            currencies[id] = template(currency);
        });
        return currencies;
    },

    /**
     * Gets the symbol for the given currency ID.
     *
     * @param {string}  currencyId Currency identifier.
     * @return {string} The currency display symbol (e.g. "$").
     */
    getCurrencySymbol: function (currencyId) {
        var currency = SUGAR.App.metadata.getCurrency(currencyId);
        return currency ? currency.symbol : '';
    },

    /**
     * Formats a currency amount.
     *
     * @param {number} amount The amount to be formatted.
     * @param {string} [currencyId] The currency id to be used. If not
     *     specified, the system default will be used.
     * @param {number} [decimalPrecision=2] The number of digits for
     *     decimal precision.
     * @param {string} [numberGroupingSeparator=','] The thousands separator.
     * @param {string} [decimalSeparator='.'] The decimal separator (string
     *     between number and decimal digits).
     * @param {string} [symbolSeparator=''] The string between the symbol and
     *     the amount.
     * @return {string} The formatted currency amount.
     */
    formatAmount: function(
        amount,
        currencyId,
        decimalPrecision,
        numberGroupingSeparator,
        decimalSeparator,
        symbolSeparator
    ) {
        // we don't want to format non-numbers
        if (!_.isFinite(amount)) {
            return amount;
        }
        var currencySymbol;
        var config = SUGAR.App.metadata.getConfig();
        // default to base currency
        currencyId = currencyId || this.getBaseCurrencyId();
        symbolSeparator = symbolSeparator || '';
        // use reasonable defaults if none exist
        if (!_.isFinite(decimalPrecision)) {
            if (_.isFinite(config.defaultCurrencySignificantDigits)) {
                decimalPrecision = config.defaultCurrencySignificantDigits;
            }
            else {
                decimalPrecision = 2;
            }
        }

        decimalSeparator = decimalSeparator || config.defaultDecimalSeparator || '.';
        // if the numberGroupingSeparator is truly undefined we need to get the defaults, if it's an empty string
        // we need to use the empty string as it's a valid value.
        numberGroupingSeparator = (!_.isUndefined(numberGroupingSeparator)) ?
            numberGroupingSeparator : config.defaultNumberGroupingSeparator || ',';

        currencySymbol = this.getCurrencySymbol(currencyId) || this.getCurrencySymbol(this.getBaseCurrencyId());
        amount = Utils.formatNumber(
            amount,
            decimalPrecision,
            decimalPrecision,
            numberGroupingSeparator,
            decimalSeparator
        );
        return currencySymbol + symbolSeparator + amount;
    },

    /**
     * Formats a currency amount according to the current user's locale.
     *
     * @param {number} amount The amount to format.
     * @param {string} [currencyId] The currency id to use. If not
     *   specified, the system default will be used.
     * @param {string} [decimalPrecision] The number of decimal digits to use.
     *   If not specified, the user preference will be used.
     * @return {string} formatted currency amount.
     */
    formatAmountLocale: function (amount, currencyId, decimalPrecision) {
        // get user preferred formatting
        var decimalSeparator = User.getPreference('decimal_separator');
        var numberGroupingSeparator = User.getPreference('number_grouping_separator');
        var userDecimalPrecision = User.getPreference('decimal_precision');
        decimalPrecision = (_.isFinite(decimalPrecision)) ? decimalPrecision : userDecimalPrecision;
        return this.formatAmount(
            amount,
            currencyId,
            decimalPrecision,
            numberGroupingSeparator,
            decimalSeparator
        );
    },

    /**
     * Unformats a currency amount.
     *
     * @param {string} amount The amount to unformat.
     * @param {string} numberGroupingSeparator Thousands separator.
     * @param {string} decimalSeparator The string between number and decimals.
     * @param {boolean} [toFloat=false] If `true`, convert string to float
     *   value.
     * @return {string} The unformatted currency amount.
     */
    unformatAmount:function (amount, numberGroupingSeparator, decimalSeparator, toFloat) {
        toFloat = toFloat || false;
        // strip off currency symbol, or anything prefixed that is not a digit or separator
        amount = amount.toString().replace(/^[^\d\.\,-]+/, '');
        return Utils.unformatNumberString(
            amount,
            numberGroupingSeparator,
            decimalSeparator,
            toFloat
        );
    },

    /**
     * Unformats the currency amount according to the current user's locale.
     *
     * @param {string} amount Amount to unformat.
     * @param {boolean} toFloat If `true`, convert string to float value.
     * @return {string} The unformatted currency amount.
     */
    unformatAmountLocale:function (amount, toFloat) {
        var decimalSeparator,
            numberGroupingSeparator,
            config = SUGAR.App.metadata.getConfig();
        // use user locale or reasonable defaults
        decimalSeparator = User.getPreference('decimal_separator') || config.defaultDecimalSeparator || '.';
        var userNumGroupingSeparator = User.getPreference('number_grouping_separator');
        numberGroupingSeparator = userNumGroupingSeparator || config.defaultNumberGroupingSeparator || ',';
        return this.unformatAmount(
            amount,
            numberGroupingSeparator,
            decimalSeparator,
            toFloat
        );
    },

    /**
     * Converts from one currency to another.
     *
     * @param {number|string} amount Base currency amount.
     * @param {string} fromId Source currency id.
     * @param {string} toId Target currency id.
     * @return {string} The converted amount.
     */
    convertAmount: function(amount, fromId, toId) {
        var currency1;
        var currency2;
        if (fromId == toId) {
            return math.round(amount, undefined, true);
        }
        currency1 = SUGAR.App.metadata.getCurrency(fromId);
        currency2 = SUGAR.App.metadata.getCurrency(toId);
        return this.convertWithRate(amount, currency1.conversion_rate, currency2.conversion_rate);
    },

    /**
     * Converts a currency to the base currency.
     *
     * @param {number|string} amount The amount in the source currency.
     * @param {string} fromId Source currency id.
     * @return {string} The converted amount.
     */
    convertToBase: function (amount, fromId) {
        return this.convertAmount(amount, fromId, this.getBaseCurrencyId());
    },

    /**
     * Converts from the base currency to another currency.
     *
     * @param {number|string} amount The amount in the base currency.
     * @param {string} toId Target currency id.
     * @return {string} The converted amount.
     */
    convertFromBase:function (amount, toId) {
        return this.convertAmount(amount, this.getBaseCurrencyId(), toId);
    },

    /**
     * Converts a currency with given conversion rates.
     *
     * @param {number|string} amount The amount in the origin currency.
     * @param {number} fromRate The origin conversion rate relative to the base
     *   currency.
     * @param {number} toRate The target conversion rate relative to the base
     *   currency.
     * @return {string} The converted amount.
     */
    convertWithRate: function(amount, fromRate, toRate) {
        fromRate = fromRate || 1.0;
        toRate = toRate || 1.0;
        return math.mul(math.div(amount, fromRate, undefined, true), toRate, undefined, true);
    }
};
