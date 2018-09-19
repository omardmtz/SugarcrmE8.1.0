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
    plugins: ['Dashlet', 'Chart'],
    processCollection: null,
    currentValue: 'all',
    chartCollection: null,
    hasData: false,
    total: 0,

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        this.locale = SUGAR.charts.getUserLocale();
        this.tooltipTemplate = app.template.getField('chart', 'multipletooltiptemplate', 'Reports');

        this.chart = sucrose.charts.multibarChart()
            .showTitle(false)
            .showControls(true)
            .showValues(false)
            .stacked(true)
            .tooltipContent(_.bind(function(eo, properties) {
                var point = {};
                var precision = this.locality.precision;
                point.groupName = app.lang.get('LBL_PMSE_LABEL_PROCESS', this.module);
                point.groupLabel = eo.group.label;
                point.seriesName = app.lang.get('LBL_PMSE_LABEL_STATUS', this.module);
                point.seriesLabel = eo.series.key;
                point.valueName = app.lang.get('LBL_CHART_COUNT');
                point.valueLabel = sucrose.utility.numberFormat(eo.point.y, precision, false, this.locality);
                return this.tooltipTemplate(point).replace(/(\r\n|\n|\r)/gm, '');
            }, this))
            .tooltips(true)
            .strings({
                legend: {
                    close: app.lang.get('LBL_CHART_LEGEND_CLOSE'),
                    open: app.lang.get('LBL_CHART_LEGEND_OPEN'),
                    noLabel: app.lang.get('LBL_CHART_UNDEFINED')
                },
                noData: app.lang.get('LBL_CHART_NO_DATA'),
                noLabel: app.lang.get('LBL_CHART_UNDEFINED')
            })
            .locality(this.locale);

        this.locality = this.chart.locality();
    },

    hasChartData: function () {
        return this.hasData;
    },

    /**
     * Generic method to render chart with check for visibility and data.
     * Called by _renderHtml and loadData.
     */
    renderChart: function() {
        if (!this.isChartReady()) {
            return;
        }

        d3.select(this.el).select('svg#' + this.cid)
            .datum(this.chartCollection)
            .transition().duration(500)
            .call(this.chart);

        this.chart_loaded = _.isFunction(this.chart.update);
        this.displayNoData(!this.chart_loaded);
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
        if (!this.currentValue) {
            return;
        }
        url = app.api.buildURL('pmse_Inbox/processStatusChart/' + this.currentValue);
        this.hasData = false;
        app.api.call('GET', url, null, {
            success: function(data) {
                self.evaluateResponse(data);
                self.renderChart();
            },
            complete: options ? options.complete : null
        });
    },

    evaluateResponse: function(response) {
        var total = d3.sum(response.data, function(d) {
                return d3.sum(d.values, function(h) {
                  return h.y;
                });
              });
        this.hasData = !!total;
        this.chartCollection = response;
    }
})
