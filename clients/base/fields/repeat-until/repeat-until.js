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
 * Repeat Until is a custom field for Meetings/Calls modules used to add
 * validation that ensures the date is after the end date, but only if this
 * field is editable.
 *
 * FIXME: This component will be moved out of clients/base folder as part of
 * MAR-2274 and SC-3593
 *
 * @class View.Fields.Base.RepeatUntilField
 * @alias SUGAR.App.view.fields.BaseRepeatUntilField
 * @extends View.Fields.Base.DateField
 */
({
    extendsFrom: 'DateField',

    /**
     * @inheritdoc
     *
     * Add validation that ensures the date is after the end date, but only if
     * this field is editable.
     */
    initialize: function(options) {
        this._super('initialize', [options]);
        this.type = 'date';

        this.model.addValidationTask(
            'repeat_until_validator_' + this.cid,
            _.bind(this._doValidateRepeatUntil, this)
        );
    },

    /**
     * Custom validator for the `repeat_until` field.
     *
     * This validates `repeat_until` to ensure the date is on or after the start date,
     * but only if this field is editable.
     *
     * @param {Object} fields The list of field names and their definitions.
     * @param {Object} errors The list of field names and their errors.
     * @param {Function} callback Async.js waterfall callback.
     * @private
     */
    _doValidateRepeatUntil: function(fields, errors, callback) {
        var isOnOrAfterStartDate,
            startDate = this.model.get('date_start'),
            repeatUntil = this.model.get(this.name),
            startDateField = this.view.getField('date_start');

        if (!_.isEmpty(repeatUntil) && (this.action === 'edit') && startDateField) {
            startDate = app.date(startDate).minutes(0).hours(0);
            isOnOrAfterStartDate = !app.date(repeatUntil).isBefore(startDate);
            if (!isOnOrAfterStartDate || !startDate.isValid()) {
                errors[this.name] = {'isAfter': startDateField.label};
            }
        }

        callback(null, fields, errors);
    },

    /**
     * @inheritdoc
     */
    _dispose: function() {
        this.model.removeValidationTask('repeat_until_validator_' + this.cid);
        this._super('_dispose');
    }
})
