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
 * @class View.Views.Base.ForecastsConfigRangesView
 * @alias SUGAR.App.view.layouts.BaseForecastsConfigRangesView
 * @extends View.Views.Base.ConfigPanelView
 */
({
    extendsFrom: 'ConfigPanelView',

    events: {
        'click #btnAddCustomRange a': 'addCustomRange',
        'click #btnAddCustomRangeWithoutProbability a': 'addCustomRange',
        'click .addCustomRange': 'addCustomRange',
        'click .removeCustomRange': 'removeCustomRange',
        'keyup input[type=text]': 'updateCustomRangeLabel',
        'change :radio': 'selectionHandler'
    },

    /**
     * Holds the fields metadata
     */
    fieldsMeta: {},

    /**
     * used to hold the metadata for the forecasts_ranges field, used to manipulate and render out as the radio buttons
     * that correspond to the fieldset for each bucket type.
     */
    forecastRangesField: {},

    /**
     * Used to hold the buckets_dom field metadata, used to retrieve and set the proper bucket dropdowns based on the
     * selection for the forecast_ranges
     */
    bucketsDomField: {},

    /**
     * Used to hold the category_ranges field metadata, used for rendering the sliders that correspond to the range
     * settings for each of the values contained in the selected buckets_dom dropdown definition.
     */
    categoryRangesField: {},

    /**
     * Holds the values found in Forecasts Config commit_stages_included value
     */
    includedCommitStages: [],

    //TODO-sfa remove this once the ability to map buckets when they get changed is implemented (SFA-215).
    /**
     * This is used to determine whether we need to lock the module or not, based on whether forecasts has been set up already
     */
    disableRanges: false,

    /**
     * Used to keep track of the selection as it changes so that it can be used to determine how to hide and show the
     * sub-elements that contain the fields for setting the category ranges
     */
    selectedRange: '',

    /**
     * a placeholder for the individual range sliders that will be used to build the range setting
     */
    fieldRanges: {},

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        // parse get the fields metadata
        _.each(_.first(this.meta.panels).fields, function(field) {
            this.fieldsMeta[field.name] = field;
            if (field.name === 'category_ranges') {
                // get rid of the name key so it doesn't mess up the other fields
                delete this.fieldsMeta.category_ranges.name;
            }
        }, this);

        // init the fields from metadata
        this.forecastRangesField = this.fieldsMeta.forecast_ranges;
        this.bucketsDomField = this.fieldsMeta.buckets_dom;
        this.categoryRangesField = this.fieldsMeta.category_ranges;

        // get the included commit stages
        this.includedCommitStages = this.model.get('commit_stages_included');

        // set the values for forecastRangesField and bucketsDomField from the model, so it can be set to selected properly when rendered
        this.forecastRangesField.value = this.model.get('forecast_ranges');
        this.bucketsDomField.value = this.model.get('buckets_dom');

        // This will be set to true if the forecasts ranges setup should be disabled
        this.disableRanges = this.model.get('has_commits');
        this.selectedRange = this.model.get('forecast_ranges');
    },

    /**
     * @inheritdoc
     */
    _updateTitleValues: function() {
        var forecastRanges = this.model.get('forecast_ranges'),
            rangeObjs = this.model.get(forecastRanges + '_ranges'),
            tmpArr = [],
            str = '',
            aSort = function(a, b) {
                if (a.min < b.min) {
                    return -1;
                } else if (a.min > b.min) {
                    return 1;
                }
            }

        // Get the keys into an object
        _.each(rangeObjs, function(value, key) {
            if(key.indexOf('without_probability') === -1) {
                tmpArr.push({
                    min: value.min,
                    max: value.max
                });
            }
        });

        tmpArr.sort(aSort);

        _.each(tmpArr, function(val) {
            str += val.min + '% - ' + val.max + '%, ';
        });

        this.titleSelectedValues = str.slice(0, str.length - 2);
        this.titleSelectedRange = app.lang.getAppListStrings('forecasts_config_ranges_options_dom')[forecastRanges];
    },

    /**
     * @inheritdoc
     */
    _updateTitleTemplateVars: function() {
        this.titleTemplateVars = {
            title: this.titleViewNameTitle,
            message: this.titleSelectedRange,
            selectedValues: this.titleSelectedValues,
            viewName: this.name
        };
    },

    /**
     * @inheritdoc
     */
    bindDataChange: function() {
        this.model.on('change:show_binary_ranges change:show_buckets_ranges change:show_custom_buckets_ranges',
            function() {
                this.updateTitle();
            }, this
        );
        this.model.on('change:forecast_ranges', function(model) {
            this.updateTitle();
            if(model.get('forecast_ranges') === 'show_custom_buckets') {
                this.updateCustomRangesCheckboxes();
            }
        }, this);
    },

    /**
     * @inheritdoc
     */
    _render: function() {
        this._super('_render');

        // after the view renders, check for the range that has been selected and
        // trigger the change event on its element so that it shows
        this.$(':radio:checked').trigger('change');

        if(this.model.get('forecast_ranges') === 'show_custom_buckets') {
            this.updateCustomRangesCheckboxes();
        }
    },

    /**
     * Handles when the radio buttons change
     *
     * @param {jQuery.Event} event
     */
    selectionHandler: function(event) {
        var newValue = $(event.target).val(),
            oldValue = this.selectedRange,
            bucket_dom = this.bucketsDomField.options[newValue],
            elToHide = this.$('#' + oldValue + '_ranges'),
            elToShow = this.$('#' + newValue + '_ranges');

        // now set the new selection, so that if they change it,
        // we can later hide the things we are about to show.
        this.selectedRange = newValue;

        if(elToShow.children().length === 0) {
            if(newValue === 'show_custom_buckets') {
                this._customSelectionHandler(newValue, elToShow);
            } else {
                this._selectionHandler(newValue, elToShow);
            }

            // use call to set context back to the view for connecting the sliders
            this.connectSliders.call(this, newValue, this.fieldRanges);
        }

        if(elToHide) {
            elToHide.toggleClass('hide', true);
        }

        if(elToShow) {
            elToShow.toggleClass('hide', false);
        }

        // set the forecast ranges and associated dropdown dom on the model
        this.model.set({
            forecast_ranges: newValue,
            buckets_dom: bucket_dom
        });
    },

    /**
     * Selection handler for standard ranges (two and three ranges)
     *
     * @param {Object} elementVal value of the radio button that was clicked
     * @param {Object} showElement the jQuery-wrapped html element from selectionHandler
     * @private
     */
    _selectionHandler: function(elementVal, showElement) {
        var bucketDomStrings = app.lang.getAppListStrings(this.bucketsDomField.options[elementVal]);

        // add the things here...
        this.fieldRanges[elementVal] = {};
        showElement.append('<p>' + app.lang.get('LBL_FORECASTS_CONFIG_' + elementVal.toUpperCase() + '_RANGES_DESCRIPTION', 'Forecasts', this) + '</p>');

        _.each(bucketDomStrings, function(label, key) {
            if(key != 'exclude') {
                var rangeField,
                    model = new Backbone.Model(),
                    fieldSettings;

                // get the value in the current model and use it to display the slider
                model.set(key, this.model.get(elementVal + '_ranges')[key]);

                // build a range field
                fieldSettings = {
                    view: this,
                    def: this.fieldsMeta.category_ranges[key],
                    viewName: 'edit',
                    context: this.context,
                    module: this.module,
                    model: model,
                    meta: app.metadata.getField({name: 'range', module: this.module})
                };

                rangeField = app.view.createField(fieldSettings);
                showElement.append('<b>' + label + ':</b>').append(rangeField.el);
                rangeField.render();

                // now give the view a way to get at this field's model, so it can be used to set the value on the
                // real model.
                this.fieldRanges[elementVal][key] = rangeField;

                // this gives the field a way to save to the view's real model. It's wrapped in a closure to allow us to
                // ensure we have everything when switching contexts from this handler back to the view.
                rangeField.sliderDoneDelegate = function(category, key, view) {

                    return function(value) {
                        this.view.updateRangeSettings(category, key, value);
                    };
                }(elementVal, key, this);
            }
        }, this);
        showElement.append($('<p>' + app.lang.get('LBL_FORECASTS_CONFIG_RANGES_EXCLUDE_INFO', 'Forecasts') + '</p>'));
    },

    /**
     * Selection handler for custom ranges
     *
     * @param {Object} elementVal value of the radio button that was clicked
     * @param {Object} showElement the jQuery-wrapped html element from selectionHandler
     * @private
     */
    _customSelectionHandler: function(elementVal, showElement) {
        var bucketDomOptions = {},
            elValRanges = elementVal + '_ranges',
            bucketDomStrings = app.lang.getAppListStrings(this.bucketsDomField.options[elementVal]),
            rangeField,
            _ranges = _.clone(this.model.get(elValRanges));

        this.fieldRanges[elementVal] = {};
        showElement.append('<p>' + app.lang.get('LBL_FORECASTS_CONFIG_' + elementVal.toUpperCase() + '_RANGES_DESCRIPTION', 'Forecasts', this) + '</p>');

        // if custom bucket isn't defined save default values
        if(!this.model.has(elValRanges)) {
            this.model.set(elValRanges, {});
        }

        _.each(bucketDomStrings, function(label, key) {
            if (_.isUndefined(_ranges[key])) {
                // the range doesn't exist, so we add it to the ranges
                _ranges[key] = {min: 0, max: 100, in_included_total: false};
            } else {
                // the range already exists, update the in_included_total value
                _ranges[key].in_included_total = (_.contains(this.includedCommitStages, key));
            }
            bucketDomOptions[key] = label;
        }, this);
        this.model.set(elValRanges, _ranges);

        // save key and label of custom range from the language file to model
        // then we can add or remove ranges and save it on backend side
        // bind handler on change to validate data
        this.model.set(elementVal + '_options', bucketDomOptions);
        this.model.on('change:' + elementVal + '_options', function(event) {
            this.validateCustomRangeLabels(elementVal);
        }, this);

        // create layout, create placeholders for different types of custom ranges
        this._renderCustomRangesLayout(showElement, elementVal);

        // render custom ranges
        _.each(bucketDomStrings, function(label, key) {
            rangeField = this._renderCustomRange(key, label, showElement, elementVal);
            // now give the view a way to get at this field's model, so it can be used to set the value on the
            // real model.
            this.fieldRanges[elementVal][key] = rangeField;
        }, this);

        // if there are custom ranges not based on probability hide add button on the top of block
        if(this._getLastCustomRangeIndex(elementVal, 'custom')) {
            this.$('#btnAddCustomRange').hide();
        }

        // if there are custom ranges not based on probability hide add button on the top of block
        if(this._getLastCustomRangeIndex(elementVal, 'custom_without_probability')) {
            this.$('#btnAddCustomRangeWithoutProbability').hide();
        }
    },

    /**
     * Render layout for custom ranges, add placeholders for different types of ranges
     *
     * @param {Object} showElement the jQuery-wrapped html element from selectionHandler
     * @param {string} category type for the ranges 'show_binary' etc.
     * @private
     */
    _renderCustomRangesLayout: function(showElement, category) {
        var template = app.template.getView('config-ranges.customRangesDefault', 'Forecasts'),
            mdl = {
                category: category
            };

        showElement.append(template(mdl));
    },

    /**
     * Creates a new custom range field and renders it in showElement
     *
     * @param {string} key
     * @param {string} label
     * @param {Object} showElement the jQuery-wrapped html element from selectionHandler
     * @param {string} category type for the ranges 'show_binary' etc.
     * @private
     * @return {View.field} new created field
     */
    _renderCustomRange: function(key, label, showElement, category) {
        var customType = key,
            customIndex = 0,
            isExclude = false,
            // placeholder to insert custom range
            currentPlaceholder = showElement,
            rangeField,
            model = new Backbone.Model(),
            fieldSettings,
            lastCustomRange;

        // define type of new custom range based on name of range and choose placeholder to insert
        // custom_default: include, upside or exclude
        // custom - based on probability
        // custom_without_probability - not based on probability
        if(key.substring(0, 26) == 'custom_without_probability') {
            customType = 'custom_without_probability';
            customIndex = key.substring(27);
            currentPlaceholder = this.$('#plhCustomWithoutProbability');
        } else if(key.substring(0, 6) == 'custom') {
            customType = 'custom';
            customIndex = key.substring(7);
            currentPlaceholder = this.$('#plhCustom');
        } else if(key.substring(0, 7) == 'exclude') {
            customType = 'custom_default';
            currentPlaceholder = this.$('#plhExclude');
            isExclude = true;
        } else {
            customType = 'custom_default';
            currentPlaceholder = this.$('#plhCustomDefault');
        }

        // get the value in the current model and use it to display the slider
        model.set(key, this.model.get(category + '_ranges')[key]);

        // get the field definition from
        var fieldDef = this.fieldsMeta.category_ranges[key] || this.fieldsMeta.category_ranges[customType];

        // build a range field
        fieldSettings = {
            view: this,
            def: _.clone(fieldDef),
            viewName: 'forecastsCustomRange',
            context: this.context,
            module: this.module,
            model: model,
            meta: app.metadata.getField({name: 'range', module: this.module})
        };
        // set up real range name
        fieldSettings.def.name = key;
        // set up view
        fieldSettings.def.view = 'forecastsCustomRange';
        // enable slider
        fieldSettings.def.enabled = true;

        rangeField = app.view.createField(fieldSettings);
        currentPlaceholder.append(rangeField.el);
        rangeField.label = label;
        rangeField.customType = customType;

        // added + to make sure customIndex is numeric
        rangeField.customIndex = +customIndex;

        rangeField.isExclude = isExclude;
        rangeField.in_included_total = (_.contains(this.includedCommitStages, key));
        rangeField.category = category;

        if(key == 'include') {
            rangeField.isReadonly = true;
        }

        rangeField.render();

        // enable slider after render
        rangeField.$(rangeField.fieldTag).noUiSlider('enable');

        // hide add button for previous custom range not based on probability
        lastCustomRange = this._getLastCustomRange(category, rangeField.customType);
        if(lastCustomRange) {
            lastCustomRange.$('.addCustomRange').parent().hide();
        }

        // add error class if the range has an empty label
        if(_.isEmpty(rangeField.label)) {
            rangeField.$('.control-group').addClass('error');
        } else {
            rangeField.$('.control-group').removeClass('error');
        }

        // this gives the field a way to save to the view's real model. It's wrapped in a closure to allow us to
        // ensure we have everything when switching contexts from this handler back to the view.
        rangeField.sliderDoneDelegate = function(category, key, view) {
            return function(value) {
                this.view.updateRangeSettings(category, key, value);
            };
        }(category, key, this);

        return rangeField;
    },

    /**
     * Returns the index of the last custom range or 0
     *
     * @param {string} category type for the ranges 'show_binary' etc.
     * @param {string} customType
     * @return {number}
     * @private
     */
    _getLastCustomRangeIndex: function(category, customType) {
        var lastCustomRangeIndex = 0;
        // loop through all ranges, if there are multiple ranges with the same customType, they'll just overwrite
        // each other's index and after the loop we'll have the final index left
        if(this.fieldRanges[category]) {
            _.each(this.fieldRanges[category], function(range) {
                if(range.customType == customType && range.customIndex > lastCustomRangeIndex) {
                    lastCustomRangeIndex = range.customIndex;
                }
            }, this);
        }
        return lastCustomRangeIndex;
    },

    /**
     * Returns the last created custom range object, if no range object, return upside/include
     * for custom type and exclude for custom_without_probability type
     *
     * @param {string} category type for the ranges 'show_binary' etc.
     * @param {string} customType
     * @return {*}
     * @private
     */
    _getLastCustomRange: function(category, customType) {
        if(!_.isEmpty(this.fieldRanges[category])) {
            var lastCustomRange = undefined;
            // loop through all ranges, if there are multiple ranges with the same customType, they'll just overwrite
            // each other on lastCustomRange and after the loop we'll have the final one left
            _.each(this.fieldRanges[category], function(range) {
                if(range.customType == customType
                    && (_.isUndefined(lastCustomRange) || range.customIndex > lastCustomRange.customIndex)) {
                    lastCustomRange = range;
                }
            }, this);

            if(_.isUndefined(lastCustomRange)) {
                // there is not custom range - use default ranges
                if(customType == 'custom') {
                    // use upside or include
                    lastCustomRange = this.fieldRanges[category].upside || this.fieldRanges[category].include;
                } else {
                    // use exclude
                    lastCustomRange = this.fieldRanges[category].exclude;
                }
            }
        }

        return lastCustomRange;
    },

    /**
     * Adds a new custom range field and renders it in specific placeholder
     *
     * @param {jQuery.Event} event click
     */
    addCustomRange: function(event) {
        var self = this,
            category = $(event.currentTarget).data('category'),
            customType = $(event.currentTarget).data('type'),
            categoryRange = category + '_ranges',
            categoryOptions = category + '_options',
            ranges = _.clone(this.model.get(categoryRange)),
            bucketDomOptions = _.clone(this.model.get(categoryOptions));

        if (_.isUndefined(category) || _.isUndefined(customType)
            || _.isUndefined(ranges) || _.isUndefined(bucketDomOptions)) {
            return false;
        }

        var showElement = (customType == 'custom') ? this.$('#plhCustom') : this.$('#plhCustomWithoutProbability'),
            label = app.lang.get('LBL_FORECASTS_CUSTOM_RANGES_DEFAULT_NAME', 'Forecasts'),
            rangeField,
            lastCustomRange = this._getLastCustomRange(category, customType),
            lastCustomRangeIndex = this._getLastCustomRangeIndex(category, customType);

        lastCustomRangeIndex++;

        // setup key for the new range
        var key = customType + '_' + lastCustomRangeIndex;

        // set up min/max values for new custom range
        if (customType != 'custom') {
            // if range is without probability setup min and max values to 0
            ranges[key] = {
                min: 0,
                max: 0,
                in_included_total: false
            };
        } else if (ranges.exclude.max - ranges.exclude.min > 3) {
            // decrement exclude range to insert new range
            ranges[key] = {
                min: parseInt(ranges.exclude.max, 10) - 1,
                max: parseInt(ranges.exclude.max, 10),
                in_included_total: false
            };
            ranges.exclude.max = parseInt(ranges.exclude.max, 10) - 2;
            if (this.fieldRanges[category].exclude.$el) {
                this.fieldRanges[category].exclude.$(this.fieldRanges[category].exclude.fieldTag)
                    .noUiSlider('move', {handle: 'upper', to: ranges.exclude.max});
            }
        } else if (ranges[lastCustomRange.name].max - ranges[lastCustomRange.name].min > 3) {
            // decrement previous range to insert new range
            ranges[key] = {
                min: parseInt(ranges[lastCustomRange.name].min, 10),
                max: parseInt(ranges[lastCustomRange.name].min, 10) + 1,
                in_included_total: false
            };
            ranges[lastCustomRange.name].min = parseInt(ranges[lastCustomRange.name].min, 10) + 2;
            if (lastCustomRange.$el) {

                lastCustomRange.$(lastCustomRange.fieldTag)
                    .noUiSlider('move', {handle: 'lower', to: ranges[lastCustomRange.name].min});
            }
        } else {
            ranges[key] = {
                min: parseInt(ranges[lastCustomRange.name].min, 10) - 2,
                max: parseInt(ranges[lastCustomRange.name].min, 10) - 1,
                in_included_total: false
            };
        }

        this.model.set(categoryRange, ranges);

        rangeField = this._renderCustomRange(key, label, showElement, category);
        if(rangeField) {
            this.fieldRanges[category][key] = rangeField;
        }

        bucketDomOptions[key] = label;
        this.model.set(categoryOptions, bucketDomOptions);

        // adding event listener to new custom range
        rangeField.$(':checkbox').each(function() {
            var $el = $(this);
            $el.on('click', _.bind(self.updateCustomRangeIncludeInTotal, self));
            app.accessibility.run($el, 'click');
        });

        if(customType == 'custom') {
            // use call to set context back to the view for connecting the sliders
            this.$('#btnAddCustomRange').hide();
            this.connectSliders.call(this, category, this.fieldRanges);
        } else {
            // hide add button form top of block and for previous ranges not based on probability
            this.$('#btnAddCustomRangeWithoutProbability').hide();
            _.each(this.fieldRanges[category], function(item) {
                if(item.customType == customType && item.customIndex < lastCustomRangeIndex && item.$el) {
                    item.$('.addCustomRange').parent().hide();
                }
            }, this);
        }

        // update checkboxes
        this.updateCustomRangesCheckboxes();
    },

    /**
     * Removes a custom range from the model and view
     *
     * @param {jQuery.Event} event click
     * @return void
     */
    removeCustomRange: function(event) {
        var category = $(event.currentTarget).data('category'),
            fieldKey = $(event.currentTarget).data('key'),
            categoryRanges = category + '_ranges',
            categoryOptions = category + '_options',
            ranges = _.clone(this.model.get(categoryRanges)),
            bucketDomOptions = _.clone(this.model.get(categoryOptions));

        if (_.isUndefined(category) || _.isUndefined(fieldKey) || _.isUndefined(this.fieldRanges[category])
            || _.isUndefined(this.fieldRanges[category][fieldKey]) || _.isUndefined(ranges)
            || _.isUndefined(bucketDomOptions))
        {
            return false;
        }

        var range,
            previousCustomRange,
            lastCustomRangeIndex,
            lastCustomRange;

        range = this.fieldRanges[category][fieldKey];

        if (_.indexOf(['include', 'upside', 'exclude'], range.name) != -1) {
            return false;
        }

        if(range.customType == 'custom') {
            // find previous renge and reassign range values form removed to it
            _.each(this.fieldRanges[category], function(item) {
                if(item.customType == 'custom' && item.customIndex < range.customIndex) {
                    previousCustomRange = item;
                }
            }, this);

            if(_.isUndefined(previousCustomRange)) {
                previousCustomRange = (this.fieldRanges[category].upside) ? this.fieldRanges[category].upside : this.fieldRanges[category].include;
            }

            ranges[previousCustomRange.name].min = +ranges[range.name].min;

            if(previousCustomRange.$el) {
                previousCustomRange.$(previousCustomRange.fieldTag).noUiSlider('move', {handle: 'lower', to: ranges[previousCustomRange.name].min});
            }
        }

        // update included ranges
        this.includedCommitStages = _.without(this.includedCommitStages, range.name)

        // removing event listener for custom range
        range.$(':checkbox').off('click');

        // remove view for the range
        this.fieldRanges[category][range.name].remove();

        delete ranges[range.name];
        delete this.fieldRanges[category][range.name];
        delete bucketDomOptions[range.name];

        this.model.set(categoryOptions, bucketDomOptions);
        this.model.set(categoryRanges, ranges);

        lastCustomRangeIndex = this._getLastCustomRangeIndex(category, range.customType);
        if(range.customType == 'custom') {
            // use call to set context back to the view for connecting the sliders
            if (lastCustomRangeIndex == 0) {
                this.$('#btnAddCustomRange').show();
            }
            this.connectSliders.call(this, category, this.fieldRanges);
        } else {
            // show add button for custom range not based on probability
            if(lastCustomRangeIndex == 0) {
                this.$('#btnAddCustomRangeWithoutProbability').show();
            }
        }
        lastCustomRange = this._getLastCustomRange(category, range.customType);
        if(lastCustomRange.$el) {
            lastCustomRange.$('.addCustomRange').parent().show();
        }

        // update checkboxes
        this.updateCustomRangesCheckboxes();
    },

    /**
     * Change a label for a custom range in the model
     *
     * @param {jQuery.Event} event keyup
     */
    updateCustomRangeLabel: function(event) {
        var category = $(event.target).data('category'),
            fieldKey = $(event.target).data('key'),
            categoryOptions = category + '_options',
            bucketDomOptions = _.clone(this.model.get(categoryOptions));

        if (category && fieldKey && bucketDomOptions) {
            bucketDomOptions[fieldKey] = $(event.target).val();
            this.model.set(categoryOptions, bucketDomOptions);
        }
    },

    /**
     * Validate labels for custom ranges, if it is invalid add error style for input
     *
     * @param {string} category type for the ranges 'show_binary' etc.
     */
    validateCustomRangeLabels: function(category) {
        var opts = this.model.get(category + '_options'),
            hasErrors = false,
            range;

        _.each(opts, function(label, key) {
            range = this.fieldRanges[category][key];
            if(_.isEmpty(label.trim())) {
                range.$('.control-group').addClass('error');
                hasErrors = true;
            } else {
                range.$('.control-group').removeClass('error');
            }
        }, this);

        var saveBtn = this.layout.layout.$('[name=save_button]');
        if(saveBtn) {
            if(hasErrors) {
                // if there are errors, disable the save button
                saveBtn.addClass('disabled');
            } else if(!hasErrors && saveBtn.hasClass('disabled')) {
                // if there are no errors and the save btn is disabled, enable it
                saveBtn.removeClass('disabled');
            }
        }
    },

    /**
     * Change in_included_total value for custom range in model
     *
     * @param {Backbone.Event} event change
     */
    updateCustomRangeIncludeInTotal: function(event) {
        var category = $(event.target).data('category'),
            fieldKey = $(event.target).data('key'),
            categoryRanges = category + '_ranges',
            ranges;

        if (category && fieldKey) {
            ranges = _.clone(this.model.get(categoryRanges));
            if (ranges && ranges[fieldKey]) {
                if (fieldKey !== 'exclude' && fieldKey.indexOf('custom_without_probability') == -1) {
                    var isChecked = $(event.target).is(':checked');
                    ranges[fieldKey].in_included_total = isChecked;
                    if(isChecked) {
                        // silently add this range to the includedCommitStages
                        this.includedCommitStages.push(fieldKey);
                    } else {
                        // silently remove this range from includedCommitStages
                        this.includedCommitStages = _.without(this.includedCommitStages, fieldKey)
                    }

                    this.model.set('commit_stages_included', this.includedCommitStages);

                } else {
                    ranges[fieldKey].in_included_total = false;
                }
                this.model.set(categoryRanges, ranges);
                this.updateCustomRangesCheckboxes();
            }
        }
    },

    /**
     * Iterates through custom ranges checkboxes and enables/disables
     * checkboxes so users can only select certain checkboxes to include ranges
     */
    updateCustomRangesCheckboxes: function() {
        var els = this.$('#plhCustomDefault :checkbox, #plhCustom :checkbox'),
            len = els.length,
            $el,
            fieldKey,
            i;

        for(i = 0; i < len; i++) {
            $el = $(els[i]);
            fieldKey = $el.data('key');

            //disable the checkbox
            $el.attr('disabled', true);
            // remove any click event listeners
            $el.off('click');

            // looking specifically for checkboxes that are not the 'include' checkbox but that are
            // the last included commit stage range or the first non-included commit stage range
            if(fieldKey !== 'include'
                && (i == this.includedCommitStages.length - 1 || i == this.includedCommitStages.length)) {
                // enable the checkbox
                $el.attr('disabled', false);
                // add new click event listener
                $el.on('click', _.bind(this.updateCustomRangeIncludeInTotal, this));
                app.accessibility.run($el, 'click');
            }
        }
    },

    /**
     * Updates the setting in the model for the specific range types.
     * This gets triggered when the range slider after the user changes a range
     *
     * @param {string} category type for the ranges 'show_binary' etc.
     * @param {string} range - the range being set, i. e. `include`, `exclude` or `upside` for `show_buckets` category
     * @param {number} value - the value being set
     */
    updateRangeSettings: function(category, range, value) {
        var catRange = category + '_ranges',
            setting = _.clone(this.model.get(catRange));

        if (category == 'show_custom_buckets') {
            value.in_included_total = setting[range].in_included_total || false;
        }

        setting[range] = value;
        this.model.set(catRange, setting);
    },

    /**
     * Graphically connects the sliders to the one below, so that they move in unison when changed, based on category.
     *
     * @param {string} ranges - the forecasts category that was selected, i. e. 'show_binary' or 'show_buckets'
     * @param {Object} sliders - an object containing the sliders that have been set up in the page.  This is created in the
     * selection handler when the user selects a category type.
     */
    connectSliders: function(ranges, sliders) {
        var rangeSliders = sliders[ranges];

        if(ranges == 'show_binary') {
            rangeSliders.include.sliderChangeDelegate = function(value) {
                // lock the upper handle to 100, as per UI/UX requirements to show a dual slider
                rangeSliders.include.$(rangeSliders.include.fieldTag).noUiSlider('move', {handle: 'upper', to: rangeSliders.include.def.maxRange});
                // set the excluded range based on the lower value of the include range
                this.view.setExcludeValueForLastSlider(value, ranges, rangeSliders.include);
            };
        } else if(ranges == 'show_buckets') {
            rangeSliders.include.sliderChangeDelegate = function(value) {
                // lock the upper handle to 100, as per UI/UX requirements to show a dual slider
                rangeSliders.include.$(rangeSliders.include.fieldTag).noUiSlider('move', {handle: 'upper', to: rangeSliders.include.def.maxRange});

                rangeSliders.upside.$(rangeSliders.upside.fieldTag).noUiSlider('move', {handle: 'upper', to: value.min - 1});
                if(value.min <= rangeSliders.upside.$(rangeSliders.upside.fieldTag).noUiSlider('value')[0] + 1) {
                    rangeSliders.upside.$(rangeSliders.upside.fieldTag).noUiSlider('move', {handle: 'lower', to: value.min - 2});
                }
            };
            rangeSliders.upside.sliderChangeDelegate = function(value) {
                rangeSliders.include.$(rangeSliders.include.fieldTag).noUiSlider('move', {handle: 'lower', to: value.max + 1});
                // set the excluded range based on the lower value of the upside range
                this.view.setExcludeValueForLastSlider(value, ranges, rangeSliders.upside);
            };
        } else if(ranges == 'show_custom_buckets') {
            var i, max,
                customSliders = _.sortBy(_.filter(
                    rangeSliders,
                    function(item) {
                        return item.customType == 'custom';
                    }
                ), function(item) {
                        return parseInt(item.customIndex, 10);
                    }
                ),
                probabilitySliders = _.union(rangeSliders.include, rangeSliders.upside, customSliders, rangeSliders.exclude);

            if(probabilitySliders.length) {
                for(i = 0, max = probabilitySliders.length; i < max; i++) {
                    probabilitySliders[i].connectedSlider = (probabilitySliders[i + 1]) ? probabilitySliders[i + 1] : null;
                    probabilitySliders[i].connectedToSlider = (probabilitySliders[i - 1]) ? probabilitySliders[i - 1] : null;
                    probabilitySliders[i].sliderChangeDelegate = function(value, populateEvent) {
                        // lock the upper handle to 100, as per UI/UX requirements to show a dual slider
                        if(this.name == 'include') {
                            this.$(this.fieldTag).noUiSlider('move', {handle: 'upper', to: this.def.maxRange});
                        } else if(this.name == 'exclude') {
                            this.$(this.fieldTag).noUiSlider('move', {handle: 'lower', to: this.def.minRange});
                        }

                        if(this.connectedSlider) {
                            this.connectedSlider.$(this.connectedSlider.fieldTag).noUiSlider('move', {handle: 'upper', to: value.min - 1});
                            if(value.min <= this.connectedSlider.$(this.connectedSlider.fieldTag).noUiSlider('value')[0] + 1) {
                                this.connectedSlider.$(this.connectedSlider.fieldTag).noUiSlider('move', {handle: 'lower', to: value.min - 2});
                            }
                            if(_.isUndefined(populateEvent) || populateEvent == 'down') {
                                this.connectedSlider.sliderChangeDelegate.call(this.connectedSlider, {
                                    min: this.connectedSlider.$(this.connectedSlider.fieldTag).noUiSlider('value')[0],
                                    max: this.connectedSlider.$(this.connectedSlider.fieldTag).noUiSlider('value')[1]
                                }, 'down');
                            }
                        }
                        if(this.connectedToSlider) {
                            this.connectedToSlider.$(this.connectedToSlider.fieldTag).noUiSlider('move', {handle: 'lower', to: value.max + 1});
                            if(value.max >= this.connectedToSlider.$(this.connectedToSlider.fieldTag).noUiSlider('value')[1] - 1) {
                                this.connectedToSlider.$(this.connectedToSlider.fieldTag).noUiSlider('move', {handle: 'upper', to: value.max + 2});
                            }
                            if(_.isUndefined(populateEvent) || populateEvent == 'up') {
                                this.connectedToSlider.sliderChangeDelegate.call(this.connectedToSlider, {
                                    min: this.connectedToSlider.$(this.connectedToSlider.fieldTag).noUiSlider('value')[0],
                                    max: this.connectedToSlider.$(this.connectedToSlider.fieldTag).noUiSlider('value')[1]
                                }, 'up');
                            }
                        }
                    };
                }
            }
        }
    },

    /**
     * Provides a way for the last of the slider fields in the view, to set the value for the exclude range.
     *
     * @param {Object} value the range value of the slider
     * @param {string} ranges the selected config range
     * @param {Object} slider the slider
     */
    setExcludeValueForLastSlider: function(value, ranges, slider) {
        var excludeRange = {
                min: 0,
                max: 100
            },
            settingName = ranges + '_ranges',
            setting = _.clone(this.model.get(settingName));

        excludeRange.max = value.min - 1;
        excludeRange.min = slider.def.minRange;
        setting.exclude = excludeRange;
        this.model.set(settingName, setting);
    }
})
