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
 * @class View.Views.Base.ForecastsConfigScenariosView
 * @alias SUGAR.App.view.layouts.BaseForecastsConfigScenariosView
 * @extends View.Views.Base.ConfigPanelView
 */
({
    extendsFrom: 'ConfigPanelView',

    /**
     * Holds ALL possible different scenarios
     */
    scenarioOptions: [],

    /**
     * Holds the scenario objects that should start selected by default
     */
    selectedOptions: [],

    /**
     * Holds the select2 instance of the default scenario that users cannot change
     */
    defaultSelect2: undefined,

    /**
     * Holds the select2 instance of the options that users can add/remove
     */
    optionsSelect2: undefined,

    /**
     * The default key used for the "Amount" value in forecasts, right now it is "likely" but users will be able to
     * change that in admin to be best or worst
     *
     * todo: eventually this will be moved to config settings where users can select their default forecasted value likely/best/worst
     */
    defaultForecastedAmountKey: 'show_worksheet_likely',

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        this.selectedOptions = [];
        this.scenarioOptions = [];

        // set up scenarioOptions
        _.each(this.meta.panels[0].fields, function(field) {
            var obj = {
                id: field.name,
                text: app.lang.get(field.label, 'Forecasts')
            }

            // Check if this field is the one we don't want users to delete
            if(field.name == this.defaultForecastedAmountKey) {
                obj['locked'] = true;
            }

            this.scenarioOptions.push(obj);

            // if this should be selected by default and it is not the undeletable scenario, push it to selectedOptions
            if(this.model.get(field.name) == 1) {
                // push fields that should be selected to selectedOptions
                this.selectedOptions.push(obj);
            }
        }, this);
    },

    /**
     * Empty function as the title values have already been set properly
     * with the change:scenarios event handler
     *
     * @inheritdoc
     */
    _updateTitleValues: function() {
    },

    /**
     * @inheritdoc
     */
    bindDataChange: function() {
        this.model.on('change:scenarios', function(model) {
            var arr = [];

            if(model.get('show_worksheet_likely')) {
                arr.push(app.lang.get('LBL_FORECASTS_CONFIG_WORKSHEET_SCENARIOS_LIKELY', 'Forecasts'));
            }
            if(model.get('show_worksheet_best')) {
                arr.push(app.lang.get('LBL_FORECASTS_CONFIG_WORKSHEET_SCENARIOS_BEST', 'Forecasts'));
            }
            if(model.get('show_worksheet_worst')) {
                arr.push(app.lang.get('LBL_FORECASTS_CONFIG_WORKSHEET_SCENARIOS_WORST', 'Forecasts'));
            }

            this.titleSelectedValues = arr.join(', ');

            this.updateTitle();
        }, this);

        // trigger the change event to set the title when this gets added
        this.model.trigger('change:scenarios', this.model);
    },

    /**
     * @inheritdoc
     */
    _render: function() {
        this._super('_render');

        this.$('.select2-container-disabled').width('auto');
        this.$('.select2-search-field').css('display','none');

        // handle setting up select2 options
        var isRTL = app.lang.direction === 'rtl';
        this.optionsSelect2 = this.$('#scenariosSelect').select2({
            data: this.scenarioOptions,
            multiple: true,
            width: "100%",
            containerCssClass: "select2-choices-pills-close",
            escapeMarkup: function(m) {
                return m;
            },
            initSelection : _.bind(function (element, callback) {
                callback(this.selectedOptions);
            }, this)
        });
        this.optionsSelect2.select2('val', this.selectedOptions);

        this.optionsSelect2.on('change', _.bind(this.handleScenarioModelChange, this));
    },

    /**
     * Event handler for the select2 dropdown changing selected items
     *
     * @param {jQuery.Event} evt select2 change event
     */
    handleScenarioModelChange: function(evt) {
        var changedEnabled = [],
            changedDisabled = [],
            allOptions = [];

        // Get the options that changed and set the model
        _.each($(evt.target).val().split(','), function(option) {
            changedEnabled.push(option);
            this.model.set(option, true, {silent: true});
        }, this);

        // Convert all scenario options into a flat array of ids
        _.each(this.scenarioOptions, function(option) {
            allOptions.push(option.id);
        }, this);

        // Take all options and return an array without the ones that changed to true
        changedDisabled = _.difference(allOptions, changedEnabled);

        // Set any options that weren't changed to true to false
        _.each(changedDisabled, function(option) {
            this.model.set(option, false, {silent: true});
        }, this);

        this.model.trigger('change:scenarios', this.model);
    },

    /**
     * Formats pill selections
     *
     * @param {Object} item selected item
     */
    formatCustomSelection: function(item) {
        return '<a class="select2-choice-filter" rel="'+ item.id + '" href="javascript:void(0)">'+ item.text +'</a>';
    },

    /**
     * @inheritdoc
     *
     * Remove custom listeners off select2 instances
     */
    _dispose: function() {
        // remove event listener from select2
        if (this.defaultSelect2) {
            this.defaultSelect2.off();
            this.defaultSelect2.select2('destroy');
            this.defaultSelect2 = null;
        }
        if (this.optionsSelect2) {
            this.optionsSelect2.off();
            this.optionsSelect2.select2('destroy');
            this.optionsSelect2 = null;
        }

        this._super('_dispose');
    }
})
