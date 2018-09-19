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
 * Repeat Days of Month is a custom field for Meetings/Calls used to set
 * day(s) of the month for a Monthly recurring record.
 *
 * FIXME: This component will be moved out of clients/base folder as part of MAR-2274 and SC-3593
 *
 * @class View.Fields.Base.RepeatDaysField
 * @alias SUGAR.App.view.fields.BaseRepeatDaysField
 * @extends View.Fields.Base.Field
 */
({
    /**
     * The Enum field with selected dates
     * @type {View.Fields.Base.EnumField}
     */
    select2Field: undefined,

    /**
     * Array of currently selected dates
     * @type {Array}
     */
    selectedDates: undefined,

    /**
     * The select2/enum field sfid
     * @type {String}
     */
    select2SfId: '',

    /**
     * AppListStrings repeat_days_dom values for the template
     * @type {Array}
     */
    datesDom: undefined,

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        this.datesDom = app.lang.getAppListStrings('repeat_days_dom');

        this.model.addValidationTask(
            'repeat_days_validator_' + this.cid,
            _.bind(this._doValidateRepeatDays, this)
        );

        this.selectedDates = this.model.get(this.name) || [];
        if (_.isString(this.selectedDates)) {
            this.selectedDates = this.format(this.selectedDates);
        }
    },

    /**
     * @inheritdoc
     */
    setMode: function(name) {
        this._super('setMode', [name]);
        if (!this.model.isNew() && this.action === 'edit') {
            this.getSelect2Field().setMode(name);
            // if this is a create view we don't need to set the options on the model here
            this._updateSelect2SelectedDates(true, false);
            // update calendar dates with a selected class
            this.decorateCalendarDates();
        }
    },

    /**
     * @inheritdoc
     */
    bindDataChange: function() {
        if (this.model) {
            this.model.on('change:' + this.name, function(model, value) {
                if (this.action === 'edit' || this.action === 'detail') {
                    var dates = this.model.get(this.name);
                    if (dates) {
                        this.selectedDates = dates;
                        if (_.isString(this.selectedDates)) {
                            this.selectedDates = this.format(this.selectedDates);
                            this.render();
                        }
                    }
                }
            }, this);
        }
    },

    /**
     * @inheritdoc
     */
    _render: function() {
        this._super('_render');
        this.getSelect2Field().setElement(this.$('div[sfuuid="' + this.select2SfId + '"]'));
        if (_.isEmpty(this.select2Field.items) && this.selectedDates.length) {
            this._updateSelect2SelectedDates(true, false);
        } else {
            this.select2Field.render();
        }

        if (this.action === 'edit' && this.selectedDates.length) {
            this.decorateCalendarDates();
        }

        this._addDateFieldEvents();
    },

    /**
     * Model days format is a string of comma separated numbers (1-31)
     * Select2 needs an array of these values
     *
     * @inheritdoc
     */
    format: function(value) {
        if (!_.isString(value)) {
            return value;
        } else if (value === '') {
            return [];
        } else {
            return _.sortBy(value.split(','), function(num) {
                return parseInt(num);
            });
        }
    },

    /**
     * Select2 array of numeric strings to model comma separated number format
     *
     * @inheritdoc
     */
    unformat: function(value) {
        return _.isArray(value) ? value.join(',') : value;
    },

    /**
     * Adds the click event listeners to the days in the dropdown
     * and adds a change listener to the select2 field
     *
     * @protected
     */
    _addDateFieldEvents: function() {
        this.$('.multi-column-dropdown a[id^="repeat-on-day-"]').on('click', _.bind(this._onDatePicked, this));
        this.select2Field.$el.on('change', _.bind(this._onSelect2Change, this));
        /**
         * Disables dropdown for `Select2`
         */
        this.select2Field.$el.on('select2-opening', _.bind(function(evt) {
            evt.preventDefault();
            this.$('[data-toggle=dropdown]').dropdown('toggle');
        }, this));
    },

    /**
     * Adds the `selected` class to each calendar item in `this.selectedDates`
     */
    decorateCalendarDates: function() {
        if (this.selectedDates.length) {
            _.each(this.selectedDates, function(item) {
                this.$('#repeat-on-day-' + item).addClass('selected');
            }, this);
        }
    },

    /**
     * Handles when a day is picked from the dropdown
     *
     * @param {jQuery.Event} evt The `click` event from the day
     * @protected
     */
    _onDatePicked: function(evt) {
        var $target = $(evt.target),
            isSelected = $target.hasClass('selected'),
            val = $target.text();

        $(evt.target).toggleClass('selected');

        if (isSelected) {
            this.selectedDates = _.without(this.selectedDates, val);
        } else {
            this.selectedDates.push(val);
        }

        this._updateSelect2SelectedDates();
    },

    /**
     * Handles when the select2 field changes from removing an item
     *
     * @param {Select2.Event} evt The Select2 `change` Event
     * @protected
     */
    _onSelect2Change: function(evt) {
        if (evt.removed) {
            this.$('#repeat-on-day-' + evt.removed.id).removeClass('selected');
            this.selectedDates = _.without(this.selectedDates, evt.removed.id);

            if (this.selectedDates.length) {
                this._updateSelect2SelectedDates(false);
            } else {
                this._setSelectedDatesOnModel(null);
                this.select2Field.items = null;
            }
        }
    },

    /**
     * Parses `this.selectedDates` Array into an Object for the select2
     *
     * @param {boolean} [renderField] Optional - True if the select2's _render should be called
     * @param {boolean} [setModelOptions] Optional - True if we should update this.model with the options
     * @protected
     */
    _updateSelect2SelectedDates: function(renderField, setModelOptions) {
        renderField = _.isUndefined(renderField) ? true : renderField;
        setModelOptions = _.isUndefined(setModelOptions) ? true : setModelOptions;

        // sort numerically
        this.selectedDates = _.sortBy(this.selectedDates, function(val) {
            return parseInt(val);
        });

        var items = {};
        _.each(this.selectedDates, function(item) {
            items[item] = item;
        }, this);

        if (setModelOptions) {
            this._setSelectedDatesOnModel(this.selectedDates);
        }
        this.select2Field.items = items;

        if (renderField) {
            this.select2Field.render();
        }
    },

    /**
     * Sets the model with the selected dates in `items`
     *
     * @param {String|Array} items The selected dates to set on the model, converts to String if sent as Array
     * @protected
     */
    _setSelectedDatesOnModel: function(items) {
        if (_.isArray(items)) {
            items = items.toString();
        }

        this.model.set(this.name, items);
    },

    /**
     * If `this.select2Field` has not been created yet, it creates the enum field and returns it,
     * otherwise it just returns the already created field
     *
     * @returns {View.Fields.Base.EnumField}
     */
    getSelect2Field: function() {
        if (this.select2Field) {
            this.select2Field.setMode(this.action);
            return this.select2Field;
        }

        var select2Defs = _.clone(this.def);
        select2Defs.type = 'enum';
        this.select2Field = app.view.createField({
            def: select2Defs,
            model: this.model,
            viewName: 'detail',
            view: this.view
        });
        this.select2SfId = this.select2Field.sfId;
        this.select2Field.setMode(this.action);

        return this.select2Field;
    },

    /**
     * Custom required validator for the `repeat_days` field.
     *
     * This validates `repeat_days` based on the value of `repeat_selector` -
     * if "Each", repeat days must be specified
     *
     * @param {Object} fields The list of field names and their definitions.
     * @param {Object} errors The list of field names and their errors.
     * @param {Function} callback Async.js waterfall callback.
     * @private
     */
    _doValidateRepeatDays: function(fields, errors, callback) {
        var repeatSelector = this.model.get('repeat_selector'),
            repeatDays = this.model.get(this.name);

        if (repeatSelector === 'Each' && (!_.isString(repeatDays) || repeatDays.length < 1)) {
            errors[this.name] = {'required': true};
        }
        callback(null, fields, errors);
    },

    /**
     * @inheritdoc
     */
    _dispose: function() {
        this.model.removeValidationTask('repeat_days_validator_' + this.cid);
        this.$('.multi-column-dropdown a[id^="repeat-on-day-"]').off('click');
        // removes 'change' and 'select2-opening' events
        this.select2Field.$el.off();
        this.select2Field.dispose();

        this._super('_dispose');
    }
})
