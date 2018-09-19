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
 * @class View.Fields.Base.SelectionField
 * @alias SUGAR.App.view.fields.BaseTimeperiodField
 * @extends View.Fields.Base.EnumField
 */
({
    /**
     * Who we should extend
     */
    extendsFrom: 'EnumField',

    /**
     * The template for the tooltip
     */
    tooltipTemplate: '',

    /**
     * Mapping of ID's with the start ane end dates formatted for use when the tooltip is displayed
     */
    tpTooltipMap: {},

    /**
     * The selector we use to find the dropdown since it's appended to the body and not the current element
     */
    cssClassSelector: '',

    /**
     * Flag to use if Select2 tries to format the tooltips before timeperiod data returns from the server
     */
    updateDefaultTooltip: false,

    /**
     * Tooltip placement direction for the template
     */
    tooltipDir: 'right',

    /**
     * Tooltip key for determining which language string to use
     */
    tooltipKey: 'LBL_DROPDOWN_TOOLTIP',

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        var tooltipCssClasses = '',
            ttTemplate;

        if (app.lang.direction === 'rtl') {
            this.tooltipDir = 'left';
            tooltipCssClasses = 'force-ltr';
            this.tooltipKey += '_RTL';
        }

        ttTemplate = app.template.getField('timeperiod', 'tooltip-default');
        Handlebars.registerPartial('tooltipHtmlTemplate', ttTemplate({
            cssClasses: tooltipCssClasses
        }));

        var collectionParams = {
            limit: 100,
            params: {}
        };

        this._super('initialize', [options]);

        if (this.def.use_generic_timeperiods) {
            collectionParams.params.use_generic_timeperiods = true;
        }

        /**
         * Collection for fetching all the Timeperiods.
         *
         * @property {Data.BeanCollection}
         */
        this.tpCollection = app.data.createBeanCollection('TimePeriods');
        this.tpCollection.once('reset', this.formatTooltips, this);
        this.tpCollection.on('sync', this.render, this);
        this.tpCollection.fetch(collectionParams);

        // load the tooltip template
        this.tooltipTemplate = app.template.getField('timeperiod', 'tooltip', this.module);

        // if forecast is not setup, then we need to use the generic options
        var config = app.metadata.getModule('Forecasts', 'config');
        if (!config || config.is_setup === 0) {
            this.def.options = 'generic_timeperiod_options';
        }
    },

    /**
     * Utility method to take the TimePeriod collection and parse our the start and end dates to be in the user
     * date preference and store them for when the enum is actually opened
     * @param {Backbone.Collection} data
     */
    formatTooltips: function(data) {
        var usersDatePrefs = app.user.getPreference('datepref');
        data.each(function(model) {
          this.tpTooltipMap[model.id] = {
              start: app.date.format(app.date.parse(model.get('start_date')), usersDatePrefs),
              end: app.date.format(app.date.parse(model.get('end_date')), usersDatePrefs)
          };
        }, this);
        // since we don't need it any more, destroy it
        this._destroyTpCollection();

        if (this.updateDefaultTooltip) {
            this.updateDefaultTooltip = false;
            // manually update the default selected item's tooltip
            var tooltipText = app.lang.get('LBL_DROPDOWN_TOOLTIP', 'TimePeriods', this.tpTooltipMap[this.value[0]]);
            this.$('[rel="tooltip"]').attr('data-original-title', tooltipText);
        }
    },

    /**
     * Since this is specific to fetching the timeperiods and it's a dynamic endpoint
     * override the module for when it has to load the enum options
     *
     * @override
     * @return {string}
     */
    getLoadEnumOptionsModule: function() {
        return 'Forecasts';
    },

    /**
     * @inheritdoc
     */
    getSelect2Options: function(optionsKeys) {
        var options = this._super('getSelect2Options', [optionsKeys]);

        // this is to format the results
        options.formatResult = _.bind(this.formatOption, this);

        // this is to format the currently selected option
        options.formatSelection = _.bind(this.formatOption, this);

        if (_.isEmpty(options.dropdownCssClass)) {
            options.dropdownCssClass = 'select2-timeperiod-dropdown-' + this.cid;
        }

        this.cssClassSelector = options.dropdownCssClass;

        return options;
    },

    /**
     * Format Option for the results and the selected option to bind the tool tip data into the html
     * that gets output
     *
     * @param {Object} object
     * @return {string}
     */
    formatOption: function(object) {
        // check once if the tpTooltipMap has been built yet
        this.updateDefaultTooltip = _.isUndefined(this.tpTooltipMap[object.id]);
        return this.tooltipTemplate({
            tooltip: this.tpTooltipMap[object.id],
            value: object.text,
            tooltipDir: this.tooltipDir,
            tooltipKey: this.tooltipKey
        });
    },

    /**
     * Disposes the {@link #tpCollection} properly.
     *
     * @private
     */
    _destroyTpCollection: function() {
        if (this.tpCollection) {
            this.tpCollection.off(null, null, this);
            this.tpCollection = null;
        }
    },

    /**
     * @inheritdoc
     */
    _dispose: function() {
        this._destroyTpCollection();
        this._super('_dispose');
    }
})
