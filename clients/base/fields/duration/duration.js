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
 * DurationFieldView is a fieldset for Meetings/Calls for managing duration of an event
 *
 * FIXME: This component will be moved out of clients/base folder as part of MAR-2274 and SC-3593
 *
 * @class View.Fields.Base.DurationField
 * @alias SUGAR.App.view.fields.BaseDurationField
 * @extends View.Fields.Base.FieldsetField
 */
({
    extendsFrom: 'FieldsetField',

    detailViewNames: ['record', 'create', 'create-nodupecheck', 'preview', 'pmse-case'],

    /**
     * Set default start date time if date_start has not been set. Add custom validation
     * to make sure that the date range is valid before saving.
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        if (this.model.isNew() && (!this.model.get('date_start'))) {
            this.setDefaultStartDateTime();
            this.modifyEndDateToRetainDuration();
            this.updateDurationHoursAndMinutes();

            // Values for date_start, date_end, duration_hours, and duration_minutes
            // should be set as the default on the model.
            this.model.setDefault({
                'date_start': this.model.get('date_start'),
                'date_end': this.model.get('date_end'),
                'duration_hours': this.model.get('duration_hours'),
                'duration_minutes': this.model.get('duration_minutes')
            });
        }

        // Date range should be valid before saving the record.
        this.model.addValidationTask('duration_date_range_' + this.cid, _.bind(function(fields, errors, callback) {
            _.extend(errors, this.validate());
            callback(null, fields, errors);
        }, this));
    },

    /**
     * @inheritdoc
     */
    bindDataChange: function() {
        // Change the end date when start date changes.
        this.model.on('change:date_start', this.modifyEndDateToRetainDuration, this);

        // Check for valid date range on edit. If not valid, show a validation error.
        // In detail mode, re-render the field if either start or end date changes.
        this.model.on('change:date_start change:date_end', function(model) {
            var dateStartField;
            var dateEndField;
            var errors;

            this.updateDurationHoursAndMinutes();

            if (this.action === 'edit') {
                dateStartField = this.view.getField('date_start');
                dateEndField = this.view.getField('date_end');

                if (dateStartField && !dateStartField.disposed && dateEndField && !dateEndField.disposed) {
                    dateStartField.clearErrorDecoration();
                    dateEndField.clearErrorDecoration();
                    errors = this.validate();

                    if (errors) {
                        dateStartField.decorateError(errors.date_start);
                        dateEndField.decorateError(errors.date_end);
                    }
                }
            } else {
                this.render();
            }
        }, this);

        this._super('bindDataChange');
    },

    /**
     * Check to see if there are any errors on the field. Returns undefined if it is valid.
     * @return {Object} Errors
     */
    validate: function() {
        var errors,
            dateStartField = this.view.getField('date_start'),
            dateEndField = this.view.getField('date_end');

        if (!this.isDateRangeValid()) {
            errors = {};
            errors.date_start = {
                isBefore: dateEndField.label
            };
            errors.date_end = {
                isAfter: dateStartField.label
            };
        }

        return errors;
    },

    /**
     * @override
     *
     * Return the display string for the start and date, along with the duration.
     *
     * @param {Array/Object/string/number/boolean} value The value to format.
     * @return {string} The duration string
     */
    format: function(value) {
        var displayString = '',
            startDateString = this.model.get('date_start'),
            endDateString = this.model.get('date_end'),
            startDate,
            endDate,
            duration,
            durationString;

        if (startDateString && endDateString) {
            startDate = app.date(startDateString);
            endDate = app.date(endDateString);
            duration = app.date.duration(endDate - startDate);
            durationString = duration.format() || ('0 ' + app.lang.get('LBL_DURATION_MINUTES'));

            if ((startDate.date() === endDate.date()) &&
                (startDate.month() === endDate.month()) &&
                (startDate.year() === endDate.year())
            ) {
                // Should not display the date twice when the start and the end dates are the same.
                displayString = app.lang.get('LBL_START_AND_END_DATE_SAME_DAY', this.module, {
                    date: startDate.formatUser(true),
                    start: startDate.format(app.date.getUserTimeFormat()),
                    end: endDate.format(app.date.getUserTimeFormat()),
                    duration: durationString
                });
            } else {
                displayString = app.lang.get('LBL_START_AND_END_DATE', this.module, {
                    start: startDate.formatUser(false),
                    end: endDate.formatUser(false),
                    duration: durationString
                });
            }
        }

        return displayString;
    },

    /**
     * Set the default start date time to the upcoming hour or half hour,
     * whichever is closest.
     * @param {Utils.Date} currentDateTime (optional) - current date time
     */
    setDefaultStartDateTime: function(currentDateTime) {
        var defaultDateTime = currentDateTime || app.date().seconds(0);

        if (defaultDateTime.minutes() > 30) {
            defaultDateTime
                .add(1, 'h')
                .minutes(0);
        } else if (defaultDateTime.minutes() > 0) {
            defaultDateTime.minutes(30);
        }

        this.model.set('date_start', defaultDateTime.formatServer());
    },

    /**
     * Set duration_hours and duration_minutes based upon date_start and date_end.
     */
    updateDurationHoursAndMinutes: function() {
        var diff = app.date(this.model.get('date_end')).diff(this.model.get('date_start'));
        this.model.set('duration_hours', Math.floor(app.date.duration(diff).asHours()));
        this.model.set('duration_minutes', app.date.duration(diff).minutes());
    },

    /**
     * If the start and end date has been set and the start date changes,
     * automatically change the end date to maintain duration.
     */
    modifyEndDateToRetainDuration: function() {
        var startDateString = this.model.get('date_start'),
            originalStartDateString = this.model.previous('date_start'),
            originalStartDate,
            endDateString = this.model.get('date_end'),
            endDate,
            duration,
            changedAttributes = this.model.changedAttributes();

        // Do not change the end date if the start date has not been set or if the start date
        // and the end date have been changed at the same time.
        if (!startDateString ||
            (changedAttributes.date_start && changedAttributes.date_end) ||
            !app.acl.hasAccessToModel('edit', this.model, 'date_end')
        ) {
            return;
        }

        if (endDateString && originalStartDateString) {
            // If end date has been set, maintain duration when the start
            // date changes.
            originalStartDate = app.date(originalStartDateString);
            duration = app.date(endDateString).diff(originalStartDate);

            // Only set the end date if start date is before the end date.
            if (duration >= 0) {
                endDate = app.date(startDateString).add(duration).formatServer();
                this.model.set('date_end', endDate);
            }
        } else {
            // Set the end date to be an hour from the start date if the end
            // date has not been set yet.
            endDate = app.date(startDateString).add(30, 'm').formatServer();
            this.model.set('date_end', endDate);
        }
    },

    /**
     * Is this date range valid?
     * @return {boolean} `true` when start date is before end date, `false` otherwise
     */
    isDateRangeValid: function() {
        var start = this.model.get('date_start'),
            end = this.model.get('date_end'),
            isValid = false;

        if (start && end) {
            if (app.date.compare(start, end) < 1) {
                isValid = true;
            }
        }

        return isValid;
    },

    /**
     * Inherit fieldset templates for edit.
     * FIXME: Will be refactored by SC-3471.
     * @inheritdoc
     * @private
     */
    _loadTemplate: function() {
        this._super('_loadTemplate');
        // FIXME: SC-3836 will replace special-casing view names/actions via
        // action based templates.
        // Use detail view if the view.name is in list of views defined in detailViewNames
        if ((_.indexOf(this.detailViewNames, this.view.name) > -1) && (this.action === 'edit')) {
            this.template = app.template.getField('fieldset', 'record-detail', this.model.module);
        }
    },

    /**
     * Remove validation on the model.
     * @inheritdoc
     */
    _dispose: function() {
        this.model.removeValidationTask('duration_date_range_' + this.cid);
        this._super('_dispose');
    },

    /**
     * Forces the date and time pickers to close in the event that they remain
     * opened when the field is re-rendered.
     *
     * @inheritdoc
     */
    _render: function() {
        var start = this.view.getField('date_start');
        var end = this.view.getField('date_end');

        if (start) {
            start.$(start.fieldTag).datepicker('hide');
            start.$(start.secondaryFieldTag).timepicker('hide');
        }

        if (end) {
            end.$(end.fieldTag).datepicker('hide');
            end.$(end.secondaryFieldTag).timepicker('hide');
        }

        return this._super('_render');
    },

    /**
     * Special case for duration fields on preview view
     *
     * @inheritdoc
     */
    setMode: function(name) {
        //on preview view, we use the preview action instead of detail
        if (this.view.name === 'preview' && name === 'detail') {
            name = 'preview';
        }

        this._super('setMode', [name]);
    }
})
