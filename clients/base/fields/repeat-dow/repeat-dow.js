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
 * Repeat Day of Week is a custom field for Meetings/Calls used to set
 * days of the week for a Weekly recurring record.
 *
 * FIXME: This component will be moved out of clients/base folder as part of MAR-2274 and SC-3593
 *
 * @class View.Fields.Base.RepeatDowField
 * @alias SUGAR.App.view.fields.BaseRepeatDowField
 * @extends View.Fields.Base.EnumField
 */
({
    extendsFrom: 'EnumField',

    defaultOnUndefined: false, //custom default behavior defined below

    /**
     * @inheritdoc
     *
     * Set default value for this field and
     * add validation (required if `repeat_type` is weekly)
     */
    initialize: function(options) {
        this._super('initialize', [options]);
        this.type = 'enum';

        this.def['default'] = this.getDefaultDayOfWeek();

        this.model.addValidationTask(
            'repeat_dow_validator_' + this.cid,
            _.bind(this._doValidateRepeatDow, this)
        );
    },

    /**
     * Get the default day of week (current day of the week)
     *
     * @return {String} Day of the week
     */
    getDefaultDayOfWeek: function() {
        var isoDayOfWeek = app.date().isoWeekday(),
            sugarDayOfWeek = (isoDayOfWeek === 7) ? 0 : isoDayOfWeek;
        return sugarDayOfWeek.toString();
    },

    /**
     * @inheritdoc
     *
     * Model day of week format is a string of numeric characters ('1'-'7')
     * Select2 needs an array of these numeric strings
     */
    format: function(value) {
        return (_.isString(value)) ? value.split('').sort() : value;
    },

    /**
     * @inheritdoc
     *
     * Select2 array of numeric strings to Model numeric string format
     */
    unformat: function(value) {
        return (_.isArray(value)) ? value.sort().join('') : value;
    },

    /**
     * Custom required validator for the `repeat_dow` field.
     *
     * This validates `repeat_dow` based on the value of `repeat_type` -
     * if Weekly repeat type, repeat day of week must be specified
     *
     * @param {Object} fields The list of field names and their definitions.
     * @param {Object} errors The list of field names and their errors.
     * @param {Function} callback Async.js waterfall callback.
     * @private
     */
    _doValidateRepeatDow: function(fields, errors, callback) {
        var repeatType = this.model.get('repeat_type'),
            repeatDow = this.model.get(this.name);

        if (repeatType === 'Weekly' && (!_.isString(repeatDow) || repeatDow.length < 1)) {
            errors[this.name] = {'required': true};
        }
        callback(null, fields, errors);
    },

    /**
     * @inheritdoc
     */
    _dispose: function() {
        this.model.removeValidationTask('repeat_dow_validator_' + this.cid);
        this._super('_dispose');
    }
})
