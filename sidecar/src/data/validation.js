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

const DateUtils = require('utils/date');
const User = require('core/user');
const Utils = require('utils/utils');
const Language = require('core/language');

/**
 * Validation module.
 *
 * The validation module is used by {@link Data/Bean#doValidate}.
 * Each bean field is validated by each of the validators specified in the
 * {@link Data.Validation.validators} hash.
 *
 * The bean is also checked for required fields by
 * {@link Data.Validation#requiredValidator}.
 *
 * @module Data/Validation
 */

function makeValidators() {
    /**
     * Validates whether the given value meets a max/min value requirement.
     *
     * @param {Object} field Bean field metadata.
     * @param {string} value Bean field value (a number).
     * @param {string} type The type of requirement that must be met. Possible
     *   choices are 'max', 'min', 'greaterthan', and 'lessthan'.
     * @return {number|undefined} The numerical value of the limit if the
     *   requirement is not met and `undefined` if it is.
     * @private
     */
    var _minMaxValue = function(field, value, type) {
        var limit = _.isUndefined(field[type]) ?
            (field.validation ? field.validation[type] : null) : field[type];
        if (_.contains(['int', 'float', 'decimal', 'currency'], field.type) && _.isFinite(limit)) {
            if (field.type == 'int') {
                value = parseInt(value);
            } else {
                value = parseFloat(value);
            }
            if (type == 'max') {
                if (value > limit) return limit;
            } else if (type == 'min') {
                if (value < limit) return limit;
            } else if (type == 'greaterthan') {
                if (value <= limit) return limit;
            } else if (type == 'lessthan') {
                if (value >= limit) return limit;
            }
        }
    };

    // Helper that validates the given date is before/after the date of another field
    var _isBeforeAfter = function(field, value, type, model) {
        if(_.indexOf(['date', 'datetimecombo'], field.type) !== -1 && field.validation && field.validation.type === type) {
            var compareTo = model.fields[field.validation.compareto];
            if(!_.isUndefined(compareTo) && _.indexOf(['date', 'datetimecombo'], compareTo.type) != -1) {
                var compareToValue = Date.parse(model.get(compareTo.name));
                value = Date.parse(value.toString());
                if(!_.isNaN(compareToValue) && !_.isNaN(value)) {
                    var compareToLabel = Language.get(compareTo.label || compareTo.vname || compareTo.name, model.module);
                    if(type == "isbefore") {
                        return compareToValue < value ? compareToLabel : undefined;
                    }
                    if(type == "isafter") {
                        return compareToValue > value ? compareToLabel : undefined;
                    }
                }
            }
        }
    };

    /**
     * A hash of validators. Each validator function must return an error
     * definition if validation fails and `undefined` if it succeeds.
     *
     * Error definitions can be primitives value such as max length or an
     * array, such as a range's lower and upper limits.
     * Validator functions accept field metadata and the value to be validated.
     *
     * @class
     * @name Data/Validation.Validators
     */
    return {
        /**
         * Validates the maximum length of a given value.
         *
         * @param {string} field Bean field metadata.
         * @param {string|number} value Bean field value.
         * @return {number|undefined} Maximum length or `undefined` if the
         *   field is valid.
         * @memberOf Data/Validation.Validators
         */
        maxLength: function(field, value) {
            if(_.isNumber(value)){
                value = value.toString();
            }
            if (_.isNumber(field.len)  && _.isString(value)) {
                var maxLength = field.len;
                value = value || "";
                value = value.toString();
                if (value.length > maxLength) {
                    return maxLength;
                }
            }
        },

        /**
         * Validates the minimum length of a given value.
         *
         * @param {Object} field Bean field metadata.
         * @param {string} value Bean field value.
         * @return {number|undefined} Minimum length or `undefined` if the
         *   field is valid.
         * @memberOf Data/Validation.Validators
         */
        minLength: function(field, value) {
            if (_.isNumber(field.minlen)) { // TODO: Not sure what the proper property is if there is one
                var minLength = field.minlen;
                value = value || "";
                value = value.toString();

                if (value.length < minLength) {
                    return minLength;
                }
            }
        },

        /**
         * Validates that a given value is a valid URL.
         * Note that is impossible to do full validation of URLs in JavaScript.
         *
         * **This function has been a no-op since 6.7. Do NOT use it.**
         *
         * @param {Object} field Bean field metadata.
         * @param {string} value Bean field value.
         * @deprecated Since 7.10
         * @memberOf Data/Validation.Validators
         */
        url: function(field, value) {
            SUGAR.App.logger.warn('The function `app.validation.validators.url()` is deprecated since 7.10 ' +
                'because it has no effect.');
        },

        /**
         * Validates that a given value contains only valid email address.
         * Note that it is impossible to do full validation of email addresses
         * in JavaScript.
         *
         * @param {Object} field Bean field metadata.
         * @param {Object[]} emails Bean field value which is an array of email
         *   objects.
         * @return {string[]|undefined} Array of invalid email addresses or
         *   `undefined` if the addresses are all valid.
         * @memberOf Data/Validation.Validators
         */
        email: function(field, emails) {
            var results;
            if (field.type == 'email' || field.type === 'email-text') {
                if (emails.length > 0) {
                    _.each(emails, function(email) {
                        // if email is blank but not required, let it go
                        if (email.email_address === '' && (_.isUndefined(field.required) || !field.required)) {
                            return;
                        }
                        if (!Utils.isValidEmailAddress(email.email_address)) {
                            if (!results) results = [];
                            results.push(email.email_address);
                        }
                    });
                }
                if (results && results.length > 0) {
                    return results;
                }
            }
        },

        /**
         * Validates that a given email array has at least one email set as the
         * primary email.
         *
         * @param {Object} field Bean field metadata.
         * @param {Object[]} emails Bean field value which is an array of email
         *   objects.
         * @return {boolean|undefined} `true` if there is no primary email set
         *   or `undefined` if at least one of the emails is the primary
         *   email.
         * @memberOf Data/Validation.Validators
         */
        primaryEmail: function(field, emails) {
            if (field.type == "email") {
                if (emails.length > 0 &&
                    !_.find(emails, function(email) { return email.primary_address == "1"; })) {
                    return true;
                }
            }
        },

        /**
         * Validates that a given email array has no duplicate email addresses.
         *
         * @param {Object} field Bean field metadata.
         * @param {object[]} emails Bean field value which is an array of email
         *   objects.
         * @return {string[]|undefined} Array of duplicated email addresses or
         *   `undefined` if there are no duplicates.
         * @memberOf Data/Validation.Validators
         */
        duplicateEmail: function(field, emails) {
            if (field.type == "email") {
                var values = _.pluck(emails, "email_address"),
                    duplicates = [],
                    n = values.length,
                    i, j;
                // to ensure the fewest possible comparisons
                for (i = 0; i < n; i++) {                      // outer loop uses each item i at 0 through n
                    for (j = i + 1; j < n; j++) {              // inner loop only compares items j at i+1 to n
                        if (values[i] == values[j]) duplicates.push(values[i]);
                    }
                }
                if (duplicates && duplicates.length > 0) {
                    return duplicates;
                }
            }
        },

        /**
         * Validates that a given value is a real date or datetime.
         *
         * @param {Object} field Bean field metadata.
         * @param {string} value Date or datetime value as string.
         * @return {string|undefined} The invalid date/datetime or `undefined`
         *   if it is a valid date.
         * @memberOf Data/Validation.Validators
         */
        datetime: function(field, value){
            var val, invalidNumberOfDigits, format, sep, formatParts, parts, i, len;

            function inRange(val, min, max) {
                var value = parseInt(val, 10);
                return (!isNaN(value) && value >= min && value <= max);
            }

            if(field.type === "date" || field.type === "datetimecombo") {
                // First check will short circuit (falsy) if the value is a valid server ISO date string.
                // For datepicker values, however, we need the second check since Safari chokes on '.', '-'
                if(_.isNaN(Date.parse(value)) && _.isNaN(Date.parse(value.replace(/[\.\-]/g, '/')))) {
                    return value;
                } else {

                    // Check for valid date parts for non ISO dates as IE and FF both successfully parse
                    // 2014/13/22 simply wrapping extra months around to following year (so previous example
                    // becomes 2015/01/22).
                    if (!DateUtils.isIso(value)) {
                        // The first set of Date.parse conditionals will negate three digit days or months
                        // but 3 digit years are valid for JavaScript dates so they'll slip through. The reason
                        // we explicitly invalidate 3 digit years is datepicker auto corrects 1 and 2 digit years
                        // in yyyy but cannot do anything sensible with 3 digit years. Moreover, it was decided
                        // that it's much more likely a 3 digit years is a user entry error; they don't really
                        // intend to enter a date year (e.g. 100-999 A.D.). Also any part > 4 digits is considered
                        // invalid as well since we only support:
                        // 2010-12-23, Y-m-d
                        // 12-23-2010, m-d-Y
                        // 23-12-2010, d-m-Y
                        // 2010/12/23, Y/m/d
                        // 12/23/2010, m/d/Y
                        // 23/12/2010, d/m/Y
                        // 2010.12.23, Y.m.d
                        // 23.12.2010, d.m.Y
                        // 12.23.2010, m.d.Y
                        parts = value.replace(/[\.\-]/g, '/').split('/');
                        invalidNumberOfDigits = _.filter(parts,
                            (part) => part.length === 3 || part.length > 4
                        );

                        if (invalidNumberOfDigits.length) {
                            return value;
                        }

                        // Invalidate consecutive separators e.g. 12--23--2013
                        if (/([\.\/\-])\1/.test(value) === true) {
                            return value;
                        }

                        // Lastly, validate month and day ranges
                        format = User.getPreference('datepref');
                        sep = format.match(/[.\/\-\s].*?/);
                        formatParts = format.split(sep);
                        for(i=0, len=formatParts.length; i<len; i++) {
                            val = parts[i];
                            switch(formatParts[i].toLowerCase().charAt(0)) {
                                case 'm':
                                    if (!inRange(val, 1, 12)) {
                                        return value;
                                    }
                                    break;
                                case 'd':
                                    if (!inRange(val, 1, 31)) {
                                        return value;
                                    }
                                    break;
                            }
                        }
                    } else {
                        // The datepicker plugin will leave 3 digit years and this validation is supposed to
                        // invalidate; but to iso date will turn that to something like: 0201-01-31T08:00:00.000Z
                        // We have to reject 100-999 to be consistent with the rest of our date year validation.
                        if (value.charAt(0) === '0') return value;
                    }
                }
            }
        },

        /**
         * Validates minimum integer values.
         *
         * @param {Object} field Bean field metadata.
         * @param {string} value Field value which is a number.
         * @return {number|undefined} Value of the actual min if the limit is
         *   not met and `undefined` if it is.
         * @memberOf Data/Validation.Validators
         */
        minValue: function(field, value) {
            return _minMaxValue(field, value, 'min');
        },

        /**
         * Validates maximum integer values.
         *
         * @param {Object} field Bean field metadata.
         * @param {string} value Field value which is a number.
         * @return {number|undefined} Value of the actual max if the limit is
         *   not met and `undefined` if it is.
         * @memberOf Data/Validation.Validators
         */
        maxValue: function(field, value) {
            return _minMaxValue(field, value, 'max');
        },

        /**
         * Validates a value to make sure it's larger than a given value.
         *
         * @param {Object} field Bean field metadata.
         * @param {string} value Field value which is a number.
         * @return {number|undefined} Value that must be exceeded if the limit
         *   is not met and `undefined` if it is.
         * @memberOf Data/Validation.Validators
         */
        greaterThan: function(field, value) {
            return _minMaxValue(field, value, 'greaterthan');
        },

        /**
         * Validates a value to make sure it's less than a given value.
         *
         * @param {Object} field Bean field metadata.
         * @param {string} value Field value which is a number.
         * @return {number|undefined} Value that `value` must be less than if
         *   the limit is not met and `undefined` if it is.
         * @memberOf Data/Validation.Validators
         */
        lessThan: function(field, value) {
            return _minMaxValue(field, value, 'lessthan');
        },

        /**
         * Validates numeric values.
         *
         * @param {Object} field Bean field metadata.
         * @param {string} value field value which is an integer
         * @return {boolean|undefined} `true` if `value` is invalid,
         *   `undefined` otherwise.
         * @memberOf Data/Validation.Validators
         */
        number: function(field, value) {
            if (_.indexOf(['int', 'float', 'decimal', 'currency'], field.type) != -1) {
                return (_.isBoolean(value) || (_.isString(value) && !value.trim().length) ||
                isNaN(parseFloat(value)) || !_.isFinite(value)) ?
                    true : undefined;
            }
        },

        /**
         * Validates that the given date is before the date of another field.
         *
         * @param {Object} field Bean field metadata.
         * @param {string} value Field value which is an integer.
         * @param {Object} model Model.
         * @return {string|undefined} Compare field label if it is invalid
         *   and `undefined` otherwise.
         * @memberOf Data/Validation.Validators
         */
        isBefore: function(field, value, model) {
            return _isBeforeAfter(field, value, 'isbefore', model);
        },

        /**
         * Validates that the given date is after the date of another field.
         *
         * @param {Object} field Bean field metadata.
         * @param {string} value Field value which is an integer.
         * @param {Object} model Model.
         * @return {string} Compare field label if is invalid, `undefined`
         *   otherwise.
         * @memberOf Data/Validation.Validators
         */
        isAfter: function(field, value, model) {
            return _isBeforeAfter(field, value, 'isafter', model);
        }
    };
}

/**
 * Checks if the given array contains only empty values.
 *
 * @param {Array} value Array to check.
 * @return {boolean} `true` if all of the array's elements are empty
 *   and `false` otherwise.
 * @private
 */
function isArrayEmpty(value) {
    return _.every(value, _.isEmpty);
}

/**
 * @alias module:Data/Validation
 */
const Validation = {
    /**
     * @type {Data/Validation.Validators}
     */
    validators: makeValidators(),

    /**
     * Validates if the required field is set on a bean or about to be set.
     *
     * @param {Object} field Bean field metadata.
     * @param {string} fieldName Bean field name.
     * @param {Data/Bean} model Bean instance.
     * @param {string} value Value to be set.
     * @return {boolean} `true` if the validation fails, `undefined` otherwise.
     */
    requiredValidator: function(field, fieldName, model, value) {
        // Image type fields have their own requiredValidator
        if ((field.required === true) && (fieldName !== 'id') &&
            (field.type !== 'image') &&
            _.isUndefined(field.auto_increment)
        ) {
            var currentValue = model.get(fieldName);
            var currentUndefined = _.isUndefined(currentValue);
            var valueEmpty = _.isNull(value) ||
                value === '' ||
                value === false ||
                (_.isArray(value) && isArrayEmpty(value)) ||
                (value instanceof Backbone.Collection && !value.length);

            // Remove validation for relate/flex relate if name is erased
            if (field.id_name && model.get(field.id_name)) {
                return;
            }

            if ((currentUndefined && _.isUndefined(value)) || valueEmpty) {
                return true;
            }
        }
    },

    _isArrayEmpty: function(value) {
        if (!SUGAR.App.config.sidecarCompatMode) {
            SUGAR.App.logger.error('Data.Validation#_isArrayEmpty is a private method that you are not allowed ' +
                'to access. Please use only the public API.');
            return;
        }

        SUGAR.App.logger.warn('Data.Validation#_isArrayEmpty is a private method that you should not access. ' +
            'You will NOT be allowed to access it in the next release. Please update your code to rely on the public ' +
            'API only.');

        return isArrayEmpty(value);
    }
};

module.exports = Validation;
