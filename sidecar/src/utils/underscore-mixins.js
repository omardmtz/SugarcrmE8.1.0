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
 * Mixin intended to be used with `_.mixin`.
 *
 * ```
 * const Mixins = require('utils/underscore-mixins');
 * _.mixin(Mixins);
 * ```
 * @alias Utils/UnderscoreMixins
 * @mixin
 */
const MIXIN = {
    /**
     * Checks to see if a value is empty.
     *
     * Returns true if:
     *
     * - value is `undefined` or `null`
     * - value is an empty string
     * - value is an empty object
     * - value is an empty array
     *
     * All other values return false.
     *
     * @param {boolean|number|string|Object|Array} value The value to check.
     * @return {boolean} `true` if given an empty value; `false` otherwise.
     */
    isEmptyValue: function(value) {
        return !_.isNumber(value) && !_.isBoolean(value) && !_.isDate(value) && _.isEmpty(value);
    },

    /**
     * Performs a deep-diff comparison between two objects
     * and returns a hash mapping all keys from  `obj1`
     * and `obj2` to whether or not they were changed.
     * (A key is mapped to `true` if there are differences, `false` otherwise).
     *
     * It does not matter which order you pass the parameters in; the
     * returned object will remain the same.
     *
     * Example:
     * ```
     * var newModelAttributes = {
     *     a: 1,
     *     b: {create: true, edit: false},
     *     c: 'test',
     *     d: {a: 'b', c: {d: true}}
     * };
     *
     * var oldModelAttributes = {
     *     a: 1,
     *     b: {create: true},
     *     c: undefined,
     *     e: [1, 2]
     * };
     *
     * _.changed(newModelAttributes, oldModelAttributes);
     * // {a: false, b: true, c: true, d: true, e: true}
     * ```
     *
     * @param {Object} obj1 The first of two objects to diff between.
     * @param {Object} obj2 The second of two objects to diff between.
     * @return {Object|undefined} The hash of differences between `obj1`
     *   and `obj2`. Returns `undefined` if `obj1` and `obj2` are empty.
     */
    changed: function(obj1, obj2) {
        if (_.isEmpty(obj1) && _.isEmpty(obj2)) {
            return;
        }

        var result = {};
        var allKeys = _.keys(_.extend(result, obj2, obj1));

        _.each(allKeys, function(key) {
            var changed = false;
            if (!_.has(obj1, key) ||
                !_.has(obj2, key) ||
                !_.isEqual(obj1[key], obj2[key])
            ) {
                changed = true;
            }
            result[key] = changed;
        });

        return result;
    }
};

module.exports = MIXIN;
