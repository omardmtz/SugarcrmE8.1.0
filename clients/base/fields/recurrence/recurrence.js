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
 * Recurrence is a field for Meetings/Calls module used to set attributes
 * about a recurring record.
 *
 * FIXME: This component will be moved out of clients/base folder as part of MAR-2274 and SC-3593
 *
 * @class View.Fields.Base.RecurrenceField
 * @alias SUGAR.App.view.fields.BaseRecurrenceField
 * @extends View.Fields.Base.FieldsetField
 */
({
    extendsFrom: 'FieldsetField',

    /**
     * @property {boolean} showNoData
     *
     * This field doesn't support `showNoData`.
     */
    showNoData: false,

    /**
     * @property {int} repeatCountMin
     *
     * The minimum number of occurrences that is allowed when the repeat_count
     * field is used.
     */
    repeatCountMin: 1,

    /**
     * @property {Object} repeatEndLastValues
     *
     * Place to save off the last values of the repeat end fields so we can put
     * the values back if the user toggles back.
     */
    repeatEndLastValues: {},

    repeatTypeSpecificFields: {
        repeat_dow: ['Weekly'],
        repeat_selector: ['Monthly', 'Yearly']
    },

    /**
     * @inheritdoc
     *
     * Default the `repeat_end_type` to "Until" and update the visibility of the
     * `repeat_count` and `repeat_until` fields.
     *
     * Add validator to ensure that `repeat_count` or `repeat_until`
     * have appropriate values based on `repeat_end_type`
     */
    initialize: function(options) {
        this._super('initialize', [options]);
        this.model.addValidationTask(
            'repeat_count_or_until_required_validator_' + this.cid,
            _.bind(this._doValidateRepeatCountOrUntilRequired, this)
        );
    },

    /**
     * @inheritdoc
     */
    bindDataChange: function() {
        this._super('bindDataChange');
        this.model.on('sync', this.setEndTypeFromEndFieldValues, this);
        this.model.on('change:repeat_type', this.repeatTypeChanged, this);
        this.model.on('change:repeat_selector', this.updateRepeatSelectorDependentFieldVisibility, this);
        this.model.on('change:repeat_end_type', this.updateRepeatEndFieldVisibility, this);
    },

    /**
     * Inherit fieldset templates
     * FIXME: Will be refactored by SC-3471.
     * @inheritdoc
     * @private
     */
    _loadTemplate: function() {
        var originalType = this.type;
        this.type = 'fieldset';
        this._super('_loadTemplate');
        this.type = originalType;
    },

    /**
     * @inheritdoc
     *
     * Prepare the recurrence fields based on the value of `repeat_type`
     */
    _render: function() {
        var repeatType = this.model.get('repeat_type');

        this._super('_render');

        switch (repeatType) {
            case 'Daily':
            case 'Weekly':
            case 'Monthly':
            case 'Yearly':
                this.show();
                break;
            default:
                this.hide();
                break;
        }

        this.prepareView();
    },

    /**
     * Set up the recurrence fields based on `repeat_type` and the action
     *
     * * `repeat_dow` - show when repeat_type is weekly, hide otherwise
     * * `repeat_end_type` - hide on detail view
     */
    prepareView: function() {
        if (this.action === 'detail') {
            this._hideField('repeat_end_type');
        }

        // If copying or view/edit - we are defaulting from end field values
        if (!this.model.isNew() || this.model.isCopy()) {
            this.setEndTypeFromEndFieldValues();
        }

        this.updateRepeatTypeDependentFieldVisibility();
        this.updateRepeatEndFieldVisibility();
        this.updateRepeatSelectorDependentFieldVisibility();
    },

    /**
     * Set field defaults when `repeat_type` changes & then re-render so the
     * hide/show logic is applied.
     *
     * When `repeat_type` is cleared (set to None), force fields to their
     * default values. Exclude repeat_end_type from defaulting since its value
     * is based on the values of repeat_count and repeat_until.
     */
    repeatTypeChanged: function() {
        var isRecurring = this._isPopulated(this.model.get('repeat_type'));
        _.each(this.fields, function(field) {
            var fieldValue = this.model.get(field.name),
                isEmpty = !this._isPopulated(fieldValue) || (fieldValue === 0);
            if ((!isRecurring || isEmpty) && field.name !== 'repeat_end_type') {
                this.model.set(field.name, field.def['default']);
            }
        }, this);

        this.render();
    },

    /**
     * Update the visibility of fields dependent on the `repeat_type` field
     */
    updateRepeatTypeDependentFieldVisibility: function() {
        var repeatType = this.model.get('repeat_type');
        _.each(this.repeatTypeSpecificFields, function(showValues, fieldName) {
            if (_.contains(showValues, repeatType)) {
                this._showField(fieldName);
            } else {
                this._hideField(fieldName);
            }
        }, this);
    },

    /**
     * Update the visibility of fields dependent on the `repeat_selector` field
     */
    updateRepeatSelectorDependentFieldVisibility: function() {
        var repeatSelector = this.model.get('repeat_selector'),
            repeatSelectorVisible = this._isFieldVisible('repeat_selector');

        if (repeatSelectorVisible && repeatSelector === 'Each') {
            this._showField('repeat_days');
        } else {
            this._hideField('repeat_days');
        }

        if (repeatSelectorVisible && repeatSelector === 'On') {
            this._showField('repeat_ordinal');
            this._showField('repeat_unit');
        } else {
            this._hideField('repeat_ordinal');
            this._hideField('repeat_unit');
        }
    },

    /**
     * Set the value of repeat_end_type based on whether values are set for
     * repeat_count and repeat_until.
     */
    setEndTypeFromEndFieldValues: function() {
        var repeatUntil = this.model.get('repeat_until') || '',
            repeatCount = this.model.get('repeat_count') || '',
            repeatEndType;

        if (this._isPopulated(repeatUntil)) {
            repeatEndType = 'Until';
        } else if (this._isPopulated(repeatCount)) {
            repeatEndType = 'Occurrences';
        }

        if (repeatEndType) {
            this.model.set('repeat_end_type', repeatEndType);
        }
    },

    /**
     * Swap out Repeat Until & Repeat Occurrences fields based on the
     * value of repeat_end_type.
     */
    updateRepeatEndFieldVisibility: function() {
        var endType = this.model.get('repeat_end_type');

        //bail out if end type is not set yet
        if (!endType) {
            return;
        }

        this._toggleRepeatEndField('repeat_count', (endType === 'Occurrences'));
        this._toggleRepeatEndField('repeat_until', (endType === 'Until'));
    },

    /**
     * Hide/show the given field and either save off its value & clear (on hide)
     * or restore its previous value (on show).
     *
     * @param {string} fieldName The name of the field to hide/show
     * @param {boolean} show Whether to show (true) or hide (false)
     * @private
     */
    _toggleRepeatEndField: function(fieldName, show) {
        var value = this.model.get(fieldName),
            lastValue = this.repeatEndLastValues[fieldName];

        if (show) {
            this._showField(fieldName);
            if (!this._isPopulated(value) && this._isPopulated(lastValue)) {
                this.model.set(fieldName, lastValue);
            }
        } else {
            this._hideField(fieldName);
            this.repeatEndLastValues[fieldName] = value;
            if (this._isPopulated(value)) {
                this.model.unset(fieldName);
            }
        }
    },

    /**
     * Show the given field
     *
     * @param {string} fieldName Name of the field to show
     * @private
     */
    _showField: function(fieldName) {
        this._getFieldRecordCellByName(fieldName).show();
    },

    /**
     * Hide the given field
     *
     * @param {string} fieldName Name of the field to hide
     * @private
     */
    _hideField: function(fieldName) {
        this._getFieldRecordCellByName(fieldName).hide();
    },

    /**
     * Checks if a given field is visible
     *
     * @param {string} fieldName
     * @return {boolean} Returns true if the field is visible, false otherwise
     * @private
     */
    _isFieldVisible: function(fieldName) {
        return this._getFieldRecordCellByName(fieldName).is(':visible');
    },

    /**
     * Returns the field cell for a given field name
     *
     * @param {string} fieldName Name of the field to select
     * @return {jQuery} jQuery selected record cell
     * @private
     */
    _getFieldRecordCellByName: function(fieldName) {
        var selector = '.fieldset-field[data-name="' + fieldName + '"]';
        return this.$(selector);
    },

    /**
     * Check if a particular field is populated
     *
     * @param {string|number} value The value to check if it is populated
     * @return {boolean} Returns true if the field is populated
     * @private
     */
    _isPopulated: function(value) {
        return !_.isUndefined(value) && !_.isNull(value) && value !== '';
    },

    /**
     * Custom validator for the `repeat_count`/`repeat_until` field.
     *
     * This validates `repeat_count` is populated when `repeat_end_type` is
     * "Occurrences" and `repeat_until` is populated when `repeat_end_type` is
     * "Until".
     *
     * @param {Object} fields The list of field names and their definitions.
     * @param {Object} errors The list of field names and their errors.
     * @param {Function} callback Async.js waterfall callback.
     * @private
     */
    _doValidateRepeatCountOrUntilRequired: function(fields, errors, callback) {
        var repeatEndType = this.model.get('repeat_end_type'),
            repeatCount = this.model.get('repeat_count'),
            repeatCountIsPopulated = this._isPopulated(repeatCount),
            repeatUntilIsPopulated = this._isPopulated(this.model.get('repeat_until'));

        if (this._isPopulated(this.model.get('repeat_type'))) {
            if (repeatEndType === 'Until' && !repeatUntilIsPopulated) {
                errors.repeat_until = {required: true};
            } else if (repeatEndType === 'Occurrences') {
                if (!repeatCountIsPopulated) {
                    errors.repeat_count = {required: true};
                } else if (repeatCount < this.repeatCountMin) {
                    errors.repeat_count = {minValue: this.repeatCountMin};
                }
            }
        }

        callback(null, fields, errors);
    },

    /**
     * @inheritdoc
     */
    _dispose: function() {
        this.model.removeValidationTask('repeat_count_or_until_required_validator_' + this.cid);
        this._super('_dispose');
    }
})
