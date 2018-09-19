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
 * @class View.Fields.Base.FloatField
 * @alias SUGAR.App.view.fields.BaseFloatField
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
     * @inheritdoc
     *
     * Unformats the float based on userPreferences (grouping/decimal separator).
     * If we weren't able to parse the value, the original value is returned.
     *
     * @param {String} value the formatted value based on user preferences.
     * @return {Number|String} the unformatted value, or original string if invalid.
     */
    unformat: function(value) {
        var unformattedValue = app.utils.unformatNumberStringLocale(value);

        // if we got a number back and we have a precision we should round to that precision as that is what will
        // be stored in the db, this is needed just in case SugarLogic is used on this field's value
        if (_.isFinite(unformattedValue) && this.def && this.def.precision) {
            unformattedValue = app.math.round(unformattedValue, this.def.precision, true);
        }
        // if unformat failed, return original value
        return _.isFinite(unformattedValue) ? unformattedValue : value;

    },

    /**
     * @inheritdoc
     *
     * Formats the float based on user preferences (grouping separator).
     * If the field definition has `disabled_num_format` as `true` the value
     * won't be formatted. Also, if the value isn't a finite float it will
     * return `undefined`.
     *
     * @param {Number} value the float value to format as per user preferences.
     * @return {String|undefined} the formatted value based as per user
     *   preferences.
     */
    format: function(value) {
        if (this.def.disable_num_format || _.isNull(value)|| _.isUndefined(value) || _.isNaN(value)) {
            return value;
        }

        var number_grouping_separator = app.user.getPreference('number_grouping_separator') || ',';
        var decimal_separator = app.user.getPreference('decimal_separator') || '.';

        if (_.isUndefined(this.def.precision) || !this.def.precision) {
            return app.utils.addNumberSeparators(
                value.toString(),
                number_grouping_separator,
                decimal_separator
            );
        }

        return app.utils.formatNumber(
            value,
            this.def.precision,
            this.def.precision,
            number_grouping_separator,
            decimal_separator
        );
    }
})
