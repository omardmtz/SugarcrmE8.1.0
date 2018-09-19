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

const User = require('core/user');

/**
 * Math module provides utility methods for working with basic calculations
 * that JS normally fails to do well.
 *
 * @module Utils/Math
 */
module.exports = {
    /**
     * Do mathematical calculations in JavaScript,
     * sans floating point errors.
     *
     * ex. $10.52 is really 1052 cents. Think of currency as
     * cents and apply math that way (as integers)  and this should
     * help keep floating point issues out of the picture.
     *
     * @param {string} operator The operator.
     * @param {number} n1 The first member of the calculation.
     * @param {number} [n2] The second number of the calculation.
     * @param {number} [decimals=6] The number of decimal digits to keep.
     * @param {boolean} [fixed] `true` to return the value as a fixed string
     *   using the number of decimals specified in `decimals`.
     * @return {string} The calculated number.
     * @private
     */
    _math: function(operator, n1, n2, decimals, fixed) {
        decimals = (_.isFinite(decimals) && decimals >= 0) ? parseInt(decimals) : 6;
        Big.E_NEG = -1 * (decimals + 1);
        fixed = fixed || false;
        var result;

        // if n1 is not a number, just return it, no need to do math on it.
        if (!_.isFinite(n1)) {
            return n1;
        }

        try {
            switch (operator) {
                case 'round':
                    result = Big(n1).round(decimals);
                    break;
                case 'add':
                    result = Big(n1).plus(n2);
                    break;
                case 'sub':
                    result = Big(n1).minus(n2);
                    break;
                case 'mul':
                    result = Big(n1).times(n2).round(decimals);
                    break;
                case 'div':
                    result = Big(n1).div(n2).round(decimals);
                    break;
                default:

                    // no valid operator, just return number
                    return n1;
            }
        } catch (error) {
            if (error.name == 'BigError') {
                return n1;
            }
        }

        if (fixed && !_.isString(result)) {
            return result.toFixed(decimals);
        } else if (!_.isString(result)) {
            return result.toString();
        } else {
            return result;
        }
    },

    /**
     * Rounds a number to specified decimals as integer value.
     *
     * @param {number} number The number to round.
     * @param {number} [decimals] The number of decimal digits to keep.
     * @param {boolean} [fixed] Returns value as fixed string.
     * @return {string} The rounded number.
     */
    round: function(number, decimals, fixed) {
        return this._math('round', number, null, decimals, fixed);
    },

    /**
     * Adds two numbers as integer values.
     *
     * @param {number} n1 The first number.
     * @param {number} n2 The second number.
     * @param {number} [decimals] The number of decimal digits to keep.
     * @param {boolean} [fixed] Returns value as fixed string using the
     *   specified number of decimals.
     * @return {string} The sum of the two numbers.
     */
    add: function(n1, n2, decimals, fixed) {
        return this._math('add', n1, n2, decimals, fixed);
    },

    /**
     * Subtracts two numbers as integer values.
     *
     * @param {number} n1 The number to subtract from.
     * @param {number} n2 The number to subtract.
     * @param {number} [decimals] The number of decimal digits to keep.
     * @param {boolean} [fixed] Returns value as fixed string using the
     *   specified number of decimals.
     * @return {string} The difference between the two numbers.
     */
    sub: function(n1, n2, decimals, fixed) {
        return this._math('sub', n1, n2, decimals, fixed);
    },

    /**
     * Multiplies two numbers as integer values.
     *
     * @param {number} n1 The first number.
     * @param {number} n2 The second number.
     * @param {number} [decimals] The number of decimal digits to keep.
     * @param {boolean} [fixed] Returns value as fixed string using the
     *   specified number of decimals.
     * @return {string} The product of the two numbers.
     */
    mul: function(n1, n2, decimals, fixed) {
        return this._math('mul', n1, n2, decimals, fixed);
    },

    /**
     * Divides two numbers as integer values.
     *
     * @param {number} n1 The dividend.
     * @param {number} n2 The divisor.
     * @param {number} [decimals] The number of decimal digits to keep.
     * @param {boolean} [fixed] Returns value as fixed string using the
     *   specified number of decimals.
     * @return {string} The quotient.
     */
    div: function(n1, n2, decimals, fixed) {
        return this._math('div', n1, n2, decimals, fixed);
    },

    /**
     * Checks to see if two values are different according to the given
     * precision.
     *
     * @param {string|number} newValue The new value.
     * @param {string|number} oldValue The old value.
     * @param {number} [precision] What precision should we use. If not
     *   specified, falls back to the value in the user preferences.
     * @return {boolean} `true` if the values are different according to the
     *   given precision.
     */
    isDifferentWithPrecision: function(newValue, oldValue, precision) {
        var config = SUGAR.App.metadata.getConfig();
        var user_precision = precision || User.getPreference('decimal_precision');
        precision = (_.isFinite(user_precision)) ? user_precision : config.defaultCurrencySignificantDigits || 2;
        var diff = this._math('round', this.getDifference(newValue, oldValue, true), null, precision);
        var diffPrecision = (precision === 0) ? '0' : this._math('div', 0.1, Math.pow(10, (precision-1)));

        // if the diff is 0 (zero) always return false, this should only happen when precision is 0
        return (diff === '0') ? false : (parseFloat(diff) >= parseFloat(diffPrecision));
    },

    /**
     * Gets the difference between two numbers.
     *
     * @param {number} newValue The number to subtract from.
     * @param {number} oldValue The number to subtract.
     * @param {boolean} [absolute=false] `true` to return the absolute value
     *   of the difference.
     * @return {string|number} The difference between `newValue` and
     *   `oldValue`, or the absolute value of it if `absolute` is `true`.
     */
    getDifference: function(newValue, oldValue, absolute) {
        var diff = this._math('sub', newValue, oldValue);
        absolute = _.isUndefined(absolute) ? false : absolute;

        return (absolute) ? Math.abs(diff) : diff;
    }
};
