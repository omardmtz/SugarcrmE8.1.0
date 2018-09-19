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
({
    results: {},
    chart: {},
    plugins: ['Dashlet', 'Chart'],

    /**
     * Is the forecast Module setup??
     */
    forecastSetup: 0,

    /**
     * Track if current user is manager.
     */
    isManager: false,

    /**
     * @inheritDoc
     */
    initialize: function(options) {
        this.isManager = app.user.get('is_manager');
        this._initPlugins();
        this._super('initialize', [options]);

        // check to make sure that forecast is configured
        var forecastConfig = app.metadata.getModule('Forecasts', 'config') || {};
        this.forecastSetup = forecastConfig['is_setup'] || 0;
        this.userCurrencyPreference = app.user.getPreference('currency_id');
        this.locale = SUGAR.charts.getUserLocale();
        // create deep copy for tooltip temp use, etc.
        // it will be set to chart.locality() after instantiation
        this.locality = {};
        this.tooltipTemplate = app.template.getView(this.name + '.tooltiptemplate');
    },

    /**
     * {@inheritDoc}
     */
    initDashlet: function(view) {
        var salesStageLabels = app.lang.getAppListStrings('sales_stage_dom');

        if (!this.isManager && this.meta.config) {
            // FIXME: Dashlet's config page is rendered from meta.panels directly.
            // See the "dashletconfiguration-edit.hbs" file.
            this.meta.panels = _.chain(this.meta.panels).filter(function(panel) {
                panel.fields = _.without(panel.fields, _.findWhere(panel.fields, {name: 'visibility'}));
                return panel;
            }).value();
        }

        // get the current timeperiod
        if (this.forecastSetup) {
            app.api.call('GET', app.api.buildURL('TimePeriods/current'), null, {
                success: _.bind(function(currentTP) {
                    this.settings.set({'selectedTimePeriod': currentTP.id}, {silent: true});
                    this.layout.loadData();
                }, this),
                error: _.bind(function() {
                    // Needed to catch the 404 in case there isnt a current timeperiod
                }, this),
                complete: view.options ? view.options.complete : null
            });
        } else {
            this.settings.set({'selectedTimePeriod': 'current'}, {silent: true});
        }

        this.chart = sucrose.charts.funnelChart()
            .showTitle(false)
            .tooltips(true)
            .margin({top: 0})
            .direction(app.lang.direction)
            .tooltipContent(_.bind(function(eo, properties) {
                var point = {};
                var key = this.chart.getKey()(eo);
                var value = this.chart.getValue()(eo);
                var count = this.chart.getCount()(eo);
                point.label = salesStageLabels ? salesStageLabels[key] || key : key;
                point.value = this._formatValue(value);
                point.percent = sucrose.utility.numberFormatPercent(value, properties.total, this.locality);
                point.count = sucrose.utility.numberFormat(count, 0, false, this.locality);
                return this.tooltipTemplate(point).replace(/(\r\n|\n|\r)/gm, '');
            }, this))
            .colorData('class', {step: 2})
            .fmtValue(_.bind(function(d) {
                var y = d.value || (isNaN(d) ? 0 : d);
                return this._formatValue(y, 0);
            }, this))
            .strings({
                legend: {
                    close: app.lang.get('LBL_CHART_LEGEND_CLOSE'),
                    open: app.lang.get('LBL_CHART_LEGEND_OPEN')
                },
                noData: app.lang.get('LBL_CHART_NO_DATA'),
                noLabel: app.lang.get('LBL_CHART_NO_LABEL')
            })
            .locality(this.locale);

        this.locality = this.chart.locality();
    },

    /**
     * This method is called by the chart model in initDashlet
     *
     * @param {number} d  The numeric value to be formatted
     * @param {number} precision  The level of precision to apply
     * @return {string}  A number formatted with current user settings
     * @private
     */
    _formatValue: function(d, precision) {
        return app.currency.formatAmountLocale(d, this.userCurrencyPreference, precision);
    },

    /**
     * Initialize plugins.
     * Only manager can toggle visibility.
     *
     * @return {View.Views.BaseForecastPipeline} Instance of this view.
     * @protected
     */
    _initPlugins: function() {
        if (this.isManager) {
            this.plugins = _.union(this.plugins, [
                'ToggleVisibility'
            ]);
        }
        return this;
    },

    /**
     * {@inheritDoc}
     */
    bindDataChange: function() {
        this.settings.on('change', function(model) {
            // reload the chart
            if (this.$el && this.$el.is(':visible')) {
                this.loadData({});
            }
        }, this);
    },

    /**
     * Generic method to render chart with check for visibility and data.
     * Called by _renderHtml and loadData.
     */
    renderChart: function() {
        if (!this.isChartReady()) {
            return;
        }
        // Clear out the current chart before a re-render
        this.$('svg#' + this.cid).children().remove();

        d3sugar.select('svg#' + this.cid)
            .datum(this.results)
            .transition().duration(500)
            .call(this.chart);

        this.chart_loaded = _.isFunction(this.chart.update);
        this.displayNoData(!this.chart_loaded);
    },

    /**
     * @inheritdoc
     *
     * Additional logic on switch visibility event.
     */
    visibilitySwitcher: function() {
        var activeVisibility;
        if (!this.isManager) {
            return;
        }
        activeVisibility = this.getVisibility();
        this.$el.find('[data-action=visibility-switcher]')
            .attr('aria-pressed', function() {
                return $(this).val() === activeVisibility;
            });
    },

    hasChartData: function() {
        return !_.isEmpty(this.results) && this.results.data && this.results.data.length > 0;
    },

    /**
     * @inheritDoc
     */
    loadData: function(options) {
        var timeperiod = this.settings.get('selectedTimePeriod');
        if (timeperiod) {
            var oppsConfig = app.metadata.getModule('Opportunities', 'config');

            if (oppsConfig) {
                var oppsViewBy = oppsConfig['opps_view_by'];
            } else {
                this.results = {};
                this.renderChart();

                return false;
            }

            var url_base = oppsViewBy + '/chart/pipeline/' + timeperiod + '/';

            if (this.isManager) {
                url_base += this.getVisibility() + '/';
            }
            var url = app.api.buildURL(url_base);
            app.api.call('GET', url, null, {
                success: _.bind(function(o) {
                    if (o && o.data) {
                        var salesStageLabels = app.lang.getAppListStrings('sales_stage_dom');

                        // update sales stage labels to translated strings
                        _.each(o.data, function(dataBlock) {
                            if (dataBlock && dataBlock.key && salesStageLabels && salesStageLabels[dataBlock.key]) {
                                dataBlock.key = salesStageLabels[dataBlock.key];
                            }

                        });
                    }
                    this.results = {};
                    this.results = o;
                    this.renderChart();
                }, this),
                error: _.bind(function(o) {
                    this.results = {};
                    this.renderChart();
                }, this),
                complete: options ? options.complete : null
            });
        }
    },

    /**
     * @inheritDoc
     */
    unbind: function() {
        this.settings.off('change');
        this._super('unbind');
    }
})
