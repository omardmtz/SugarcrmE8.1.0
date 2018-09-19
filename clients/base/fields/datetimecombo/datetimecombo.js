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
 * @class View.Fields.Base.DatetimecomboField
 * @alias SUGAR.App.view.fields.BaseDatetimecomboField
 * @extends View.Fields.Base.DateField
 */
({
    extendsFrom: 'DateField',

    /**
     * HTML tag of the secondary field.
     *
     * @property {String}
     */
    secondaryFieldTag: 'input[data-type=time]',

    initialize: function(options) {
        this._super('initialize', [options]);

        /**
         * If a time picker has been initialized on the field or not.
         *
         * @type {boolean}
         * @private
         */
        this._hasTimePicker = false;
    },

    /**
     * @inheritdoc
     *
     * Add `show-timepicker` on click listener.
     */
    _initEvents: function() {
        this._super('_initEvents');

        _.extend(this.events, {
            'click [data-action="show-timepicker"]': 'showTimePicker'
        });

        return this;
    },

    /**
     * @override
     */
    _initDefaultValue: function() {
        if (!this.model.isNew() || this.model.get(this.name) || !this.def.display_default) {
            return this;
        }

        var value = app.date.parseDisplayDefault(this.def.display_default);
        if (!value) {
            return this;
        }

        value = this.unformat(
            app.date(value).format(
                app.date.convertFormat(this.getUserDateTimeFormat())
            )
        );

        this.model.setDefault(this.name, value);

        return this;
    },

    /**
     * @inheritdoc
     */
    _initPlaceholderAttribute: function() {
        this._super('_initPlaceholderAttribute');

        var placeholder = this.getTimePlaceHolder(this.getUserTimeFormat());

        this.secondaryFieldPlaceholder = this.def.placeholder && app.lang.get(
            this.def.placeholder,
            this.module,
            {format: placeholder}
        ) || placeholder;

        return this;
    },

    /**
     * Handler to show time picker on icon click.
     *
     * We trigger the focus on element instead of the jqueryfied element, to
     * trigger the focus on the input and avoid the `preventDefault()` imposed
     * in the library.
     */
    showTimePicker: function() {
        this.$(this.secondaryFieldTag)[0].focus();
    },

    /**
     * Return user time format.
     *
     * @return {string} User time format.
     */
    getUserTimeFormat: function() {
        return app.user.getPreference('timepref');
    },

    /**
     * Return user datetime format.
     *
     * @return {string} User datetime format.
     */
    getUserDateTimeFormat: function() {
        return this.getUserDateFormat() + ' ' + this.getUserTimeFormat();
    },

    /**
     * Return time place holder based on supplied format.
     *
     * @param {String} format Format.
     * @return {String} Time place holder.
     */
    getTimePlaceHolder: function(format) {
        var map = {
            'H': 'hh',
            'h': 'hh',
            'i': 'mm',
            'a': '',
            'A': ''
        };

        return format.replace(/[HhiaA]/g, function(s) {
            return map[s];
        });
    },

    /**
     * Set up the time picker.
     *
     * @protected
     */
    _setupTimePicker: function() {
        var options;
        var localeData = app.date.localeData();
        var lang = {
            am: localeData.meridiem(1, 00, true),
            pm: localeData.meridiem(13, 00, true),
            AM: localeData.meridiem(1, 00, false),
            PM: localeData.meridiem(13, 00, false)
        };

        this.def.time || (this.def.time = {});

        options = {
            timeFormat: this.getUserTimeFormat(),
            scrollDefaultNow: _.isUndefined(this.def.time.scroll_default_now) ?
                true :
                !!this.def.time.scroll_default_now,
            step: this.def.time.step || 15,
            disableTextInput: _.isUndefined(this.def.time.disable_text_input) ?
                false :
                !!this.def.time.disable_text_input,
            className: this.def.time.css_class || 'prevent-mousedown',
            appendTo: this.$el,
            lang: lang
        };

        this._enableDuration(options);

        this.$(this.secondaryFieldTag).timepicker(options);
        this._hasTimePicker = true;
    },

    /**
     * Show duration on the timepicker dropdown if enabled in view definition.
     * @param {Object} options The timepicker options (see
     *   https://github.com/jonthornton/jquery-timepicker#options).
     * @private
     */
    _enableDuration: function(options) {
        var self = this;

        if (this.def.time.duration) {
            options.maxTime = 85500; //23.75 hours, which is 11:45pm

            options.durationTime = function() {
                var dateStartString = self.model.get(self.def.time.duration.relative_to),
                    dateEndString = self.model.get(self.name),
                    startDate,
                    endDate;

                this.minTime = null;
                this.showDuration = false;

                if (!dateStartString || !dateEndString) {
                    return;
                }

                startDate = app.date(dateStartString);
                endDate = app.date(dateEndString);

                if ((startDate.year() === endDate.year()) &&
                    (startDate.month() === endDate.month()) &&
                    (startDate.day() === endDate.day())
                ) {
                    this.minTime = app.date.duration({
                        hours: startDate.hours(),
                        minutes: startDate.minutes()
                    }).asSeconds();
                    this.showDuration = true;
                }

                return this.minTime;
            };
        }
    },

    /**
     * Handle date and time picker changes.
     *
     * If model value is defined and supplied date or time is empty, an empty
     * string is returned, otherwise, empty values will fallback to current
     * date/time.
     *
     * All parameters and returned value are formatted according to user
     * preferences.
     *
     * @param {String} d Date value.
     * @param {String} t Time value.
     * @return {String} Datetime value.
     */
    handleDateTimeChanges: function(d, t) {
        if (this.model.get(this.name) && (!d || !t)) {
            return '';
        }

        var now = app.date();

        d = d || (t && now.format(app.date.convertFormat(this.getUserDateFormat())));
        t = t || (d && now.format(app.date.convertFormat(this.getUserTimeFormat())));

        return (d + ' ' + t).trim();
    },

    /**
     * Date picker doesn't trigger a `change` event whenever the date value
     * changes we need to override this method and listen to the `hide` event.
     *
     * Handles `hide` date picker event expecting to set the default time if
     * not filled yet, see {@link #handleDateTimeChanges}.
     *
     * All invalid values are cleared from fields without triggering an event
     * because `this.model.set()` could have been already empty thus not
     * triggering a new event and not calling the default code of
     * `bindDomChange()`.
     *
     * Undefined model values will not be replaced with empty string to prevent
     * unnecessary unsaved changes warnings.
     *
     * @override
     */
    handleHideDatePicker: function() {
        var $dateField = this.$(this.fieldTag),
            $timeField = this.$(this.secondaryFieldTag),
            d = $dateField.val(),
            t = $timeField.val(),
            datetime = this.unformat(this.handleDateTimeChanges(d, t));

        if (!datetime) {
            $dateField.val('');
            $timeField.val('');
        }

        if (_.isEmptyValue(datetime) && _.isUndefined(this.model.get(this.name))) {
            return;
        }

        this.model.set(this.name, datetime);
    },

    /**
     * @inheritdoc
     *
     * Bind time picker `changeTime` event expecting to set the default date if
     * not filled yet, see {@link #handleDateTimeChanges}.
     */
    bindDomChange: function() {
        this._super('bindDomChange');

        if (this._inDetailMode()) {
            return;
        }

        var $dateField = this.$(this.fieldTag),
            $timeField = this.$(this.secondaryFieldTag),
            selfView = this.view;

        $timeField.timepicker().on({
            showTimepicker: function() {
                selfView.trigger('list:scrollLock', true);
            },
            hideTimepicker: function() {
                selfView.trigger('list:scrollLock', false);
            },
            change: _.bind(function() {
                var t = $timeField.val().trim(),
                    datetime = '';

                if (t) {
                    var d = $dateField.val();
                    datetime = this.unformat(this.handleDateTimeChanges(d, t));
                    if (!datetime) {
                        $dateField.val('');
                        $timeField.val('');
                    }
                }
                this.model.set(this.name, datetime);
            }, this),
            focus: _.bind(function() {
                this.handleFocus();
            }, this)
        });
    },

    /**
     * @inheritdoc
     *
     * Add extra logic to unbind secondary field tag.
     */
    unbindDom: function() {
        this._super('unbindDom');

        if (this._inDetailMode()) {
            return;
        }

        this.$(this.secondaryFieldTag).off();
    },

    /**
     * Binds model changes on this field, taking into account both field tags.
     *
     * @override
     */
    bindDataChange: function() {
        if (!this.model) {
            return;
        }

        this.model.on('change:' + this.name, function(model, value) {
            if (this.disposed) {
                return;
            }

            if (this._inDetailMode()) {
                this.render();
                return;
            }

            value = this.format(value) || {'date': '', 'time': ''};

            this.$(this.fieldTag).val(value['date']);
            if (value['date']) {
                this.$(this.fieldTag).data('datepicker').setValue(value['date']);
            }
            this.$(this.secondaryFieldTag).val(value['time']);
        }, this);
    },

    /**
     * Formats date value according to user preferences.
     *
     * @param {String} value Datetime value to format.
     * @return {Object/String/undefined} On edit mode the returned value is an
     *   object with two keys, `date` and `time`. On detail mode the returned
     *   value is a date, formatted according to user preferences if supplied
     *   value is a valid date, otherwise returned value is `undefined`.
     *
     * @override
     */
    format: function(value) {
        if (!value) {
            return value;
        }

        value = app.date(value);

        if (!value.isValid()) {
            return;
        }

        if (this.action === 'edit' || this.action === 'massupdate') {
            value = {
                'date': value.format(app.date.convertFormat(this.getUserDateFormat())),
                'time': value.format(app.date.convertFormat(this.getUserTimeFormat()))
            };

        } else {
            value = value.formatUser(false);
        }

        return value;
    },

    /**
     * Unformats datetime value for storing in model.
     *
     * @return {String} Unformatted value or `undefined` if value is
     *   an invalid date.
     *
     * @override
     */
    unformat: function(value) {
        if (!value) {
            return value;
        }

        value = app.date(value, app.date.convertFormat(this.getUserDateTimeFormat()), true);

        if (!value.isValid()) {
            return;
        }

        return value.formatServer();
    },

    /**
     * Override decorateError to take into account the two fields.
     *
     * @override
     */
    decorateError: function(errors) {
        var ftag = this.fieldTag || '',
            $ftag = this.$(ftag),
            errorMessages = [],
            $tooltip;

        // Add error styling
        this.$el.closest('.record-cell').addClass('error');
        this.$el.addClass('error');

        if (_.isString(errors)) {
            // A custom validation error was triggered for this field
            errorMessages.push(errors);
        } else {
            // For each error add to error help block
            _.each(errors, function(errorContext, errorName) {
                errorMessages.push(app.error.getErrorString(errorName, errorContext));
            });
        }

        $ftag.parent().addClass('error');

        $tooltip = [$(this.exclamationMarkTemplate(errorMessages)), $(this.exclamationMarkTemplate(errorMessages))];

        var self = this;

        $ftag.parent().children('input').each(function(index) {
            $(this).after($tooltip[index]);
        });
    },

    /**
     * @inheritdoc
     */
    _render: function() {
        if (this._hasTimePicker) {
            this.$(this.secondaryFieldTag).timepicker('hide');
        }

        this._super('_render');

        if (this._inDetailMode()) {
            return;
        }

        this._setupTimePicker();
    },

    /**
     * @inheritdoc
     */
    _dispose: function() {
        if (this._hasTimePicker) {
            this.$(this.secondaryFieldTag).timepicker('remove');
        }

        this._super('_dispose');
    }
})
