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
 * @class View.Views.Base.OpportunityMetricsView
 * @alias SUGAR.App.view.views.BaseOpportunityMetricsView
 * @extends View.View
 */
({
    plugins: ['Dashlet', 'Chart'],
    className: 'opportunity-metrics-wrapper',

    metricsCollection: null,

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        this.tooltipTemplate = app.template.getField('chart', 'singletooltiptemplate', this.module);
        this.locale = SUGAR.charts.getSystemLocale();

        this.chart = sucrose.charts.pieChart()
                .margin({top: 0, right: 0, bottom: 5, left: 0})
                .donut(true)
                .donutLabelsOutside(true)
                .donutRatio(0.447)
                .rotateDegrees(0)
                .arcDegrees(360)
                .maxRadius(110)
                .hole(this.total)
                .showTitle(false)
                .tooltips(true)
                .showLegend(false)
                .colorData('data')
                .direction(app.lang.direction)
                .tooltipContent(_.bind(function(eo, properties) {
                    var point = {};
                    point.key = this.chart.getKey()(eo);
                    point.label = app.lang.get('LBL_CHART_COUNT');
                    point.value = this.chart.getValue()(eo);
                    point.percent = sucrose.utility.numberFormatPercent(point.value, properties.total, this.locality);
                    return this.tooltipTemplate(point).replace(/(\r\n|\n|\r)/gm, '');
                }, this))
                .fmtValue(_.bind(function(d) {
                    return this._valueFormat(d);
                }, this))
                .fmtKey(_.bind(function(d) {
                    return this._labelFormat(d);
                }, this))
                .strings({
                    noData: app.lang.get('LBL_CHART_NO_DATA'),
                    noLabel: app.lang.get('LBL_CHART_NO_LABEL')
                })
                .locality(this.locale);

        this.locality = this.chart.locality();
    },

    /**
     * Generic method to render chart with check for visibility and data.
     * Called by _renderHtml and loadData.
     */
    renderChart: function() {
        if (!this.isChartReady()) {
            return;
        }

        // Set value of label inside donut chart
        this.chart.hole(this.total);

        d3sugar.select(this.el).select('svg#' + this.cid)
            .datum(this.chartCollection)
            .transition().duration(500)
            .call(this.chart);

        this.chart_loaded = _.isFunction(this.chart.update);
        this.displayNoData(!this.chart_loaded);
    },

    /* Process data loaded from REST endpoint so that d3 chart can consume
     * and set general chart properties
     */
    evaluateResult: function(data) {
        var total = 0,
            userConversionRate = 1 / app.metadata.getCurrency(app.user.getPreference('currency_id')).conversion_rate,
            userCurrencyPreference = app.user.getPreference('currency_id'),
            stageLabels = app.lang.getAppListStrings('opportunity_metrics_dom'),
            convertedAmount;

        _.each(data, function(value, key) {
            convertedAmount = app.currency.convertWithRate(value.amount_usdollar, userConversionRate);
            // parse currencies, format to user preference and attach the correct delimiters/symbols etc
            data[key].formattedAmount = app.currency.formatAmountLocale(convertedAmount, userCurrencyPreference, 0);
            data[key].icon = key === 'won' ? 'caret-up' : (key === 'lost' ? 'caret-down' : 'minus');
            data[key].cssClass = key === 'won' ? 'won' : (key === 'lost' ? 'lost' : 'active');
            data[key].dealLabel = key;
            data[key].stageLabel = stageLabels[key] || key;
            total += value.count;
        });

        this.total = total;
        this.metricsCollection = data;

        this.chartCollection = {
            data: _.map(this.metricsCollection, function(value, key) {
                return {
                    key: value.stageLabel,
                    value: value.count,
                    classes: key
                };
            }),
            properties: {
                title: app.lang.get('LBL_DASHLET_OPPORTUNITY_NAME'),
                value: 3,
                label: total,
                yDataType: 'numeric',
                xDataType: 'string'
            }
        };
    },

    /**
     * @inheritdoc
     */
    loadData: function(options) {
        var self = this,
            url;
        if (this.meta.config) {
            return;
        }
        url = app.api.buildURL(this.model.module, 'opportunity_stats', {
            id: this.model.get('id')
        });
        app.api.call('read', url, null, {
            success: function(data) {
                self.evaluateResult(data);
                if (!self.disposed) {
                    // we have to rerender the entire dashlet, not just the chart,
                    // because the HBS file is dependant on metricsCollection
                    self.render();
                }
            },
            error: _.bind(function() {
                this.displayNoData(true);
            }, this),
            complete: options ? options.complete : null
        });
    },

    /**
     * This method is called by the chart model in initialize
     *
     * @param {number} d  The numeric value to be formatted
     * @return {string}  A number formatted with SI units if needed
     * @private
     */
    _valueFormat: function(d) {
        var val = d.series ? this.chart.getValue()(d.series) : d;
        return sucrose.utility.numberFormatSI(val, 2, false);
    },

    /**
     * This method is called by the chart model in initialize
     *
     * @param {Object|string} d  The data to extract the label from
     * @return {string}  A label formatted as needed
     * @private
     */
    _labelFormat: function(d) {
        var val = d.series ? this.chart.getKey()(d.series) : d;
        return val;
    }
})
