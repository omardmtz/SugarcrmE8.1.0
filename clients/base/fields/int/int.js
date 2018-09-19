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
 * @class View.Fields.Base.IntField
 * @alias SUGAR.App.view.fields.BaseIntField
 * @extends View.Fields.Base.BaseField
 */
({
    /**
     * @inheritdoc
     *
     * The direction for this field should always be `ltr`.
     */
    direction: 'ltr',

    /**
     * Older IE doesn't support Number.MIN_SAFE_INTEGER
     * @private
     */
    _minInt: Number.MIN_SAFE_INTEGER || -9007199254740991,

    /**
     * Older IE doesn't support Number.MAX_SAFE_INTEGER
     * @private
     */
    _maxInt: Number.MAX_SAFE_INTEGER || 9007199254740991,

    /**
     * @inheritdoc
     *
     * Add custom min/max value validation.
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        this.model.addValidationTask(
            'min_max_int_validator_' + this.cid,
            _.bind(this._doValidateMinMaxInt, this)
        );
    },

    /**
     * @inheritdoc
     *
     * Unformats the integer based on userPreferences (grouping separator).
     * If we weren't able to parse the value, `undefined` is returned.
     *
     * @param {String} value the formatted value based on user preferences.
     * @return {Number|undefined} the unformatted value.
     */
    unformat: function(value) {
        value = app.utils.unformatNumberStringLocale(value, false);
        if (!this._isSafeInt(value)) {
            return value;
        }
        return parseFloat(value);
    },

    /**
     * @inheritdoc
     *
     * Formats the integer based on user preferences (grouping separator).
     * If the field definition has `disabled_num_format` as `true` the value
     * won't be formatted. Also, if the value isn't a finite integer it will
     * return `undefined`.
     *
     * @param {Number} value the integer value to format as per user
     *   preferences.
     * @return {String|undefined} the formatted value based as per user
     *   preferences.
     */
    format: function(value) {
        var numberGroupSeparator = '', decimalSeparator = '';
        if (!this._isSafeInt(value)) {
            return value;
        }
        if (!this.def.disable_num_format) {
            numberGroupSeparator = app.user.getPreference('number_grouping_separator') || ',';
            decimalSeparator = app.user.getPreference('decimal_separator') || '.';
        }

        return app.utils.formatNumber(
            value, 0, 0,
            numberGroupSeparator,
            decimalSeparator
        );
    },

    /**
     * This validates int doesn't exceed min/max value defined in sugar config.
     *
     * @param {Object} fields The list of field names and their definitions.
     * @param {Object} errors The list of field names and their errors.
     * @param {Function} callback Async.js waterfall callback.
     * @private
     */
    _doValidateMinMaxInt: function(fields, errors, callback) {
        var value = this.model.get(this.name);
        var minValue = this._minInt;
        var maxValue = this._maxInt;
        if (!_.isUndefined(app.config.sugarMinInt)) {
            minValue = Math.max(minValue, app.config.sugarMinInt);
        }
        if (!_.isUndefined(app.config.sugarMaxInt)) {
            maxValue = Math.min(maxValue, app.config.sugarMaxInt);
        }
        if (value < minValue) {
            errors[this.name] = {'minValue': minValue};
        } else if (value > maxValue) {
            errors[this.name] = {'maxValue': maxValue};
        }
        callback(null, fields, errors);
    },

    /**
     * Checks if value is too big to format/unformat.
     * @param {string|number} value
     * @return {boolean}
     * @private
     */
    _isSafeInt: function(value) {
        return (_.isFinite(value) && this._minInt <= value && value <= this._maxInt);
    },

    /**
     * @inheritdoc
     */
    _dispose: function() {
        this.model.removeValidationTask('min_max_int_validator_' + this.cid);
        this._super('_dispose');
    }
})
