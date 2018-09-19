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
    currentValue: null,
    chartCollection: null,
    hasData: false,
    total: 0,
    showProcesses: null,

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        this.locale = SUGAR.charts.getUserLocale();
        this.tooltipTemplate = app.template.getField('chart', 'singletooltiptemplate', 'Reports');

        this.chart = sucrose.charts.pieChart()
            .margin({top: 5, right: 20, bottom: 20, left: 20})
            .donut(true)
            .donutLabelsOutside(true)
            .donutRatio(0.447)
            .hole(this.total)
            .showTitle(false)
            .tooltips(true)
            .showLegend(true)
            .colorData('class')
            .tooltipContent(_.bind(function(eo, properties) {
                var point = {};
                point.key = eo.key;
                point.label = app.lang.get('LBL_CHART_COUNT');
                point.value = sucrose.utility.numberFormat(eo.value, this.locality.precision, false, this.locality);
                return this.tooltipTemplate(point).replace(/(\r\n|\n|\r)/gm, '');
            }, this))
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

    initDashlet: function (view) {
        var self = this;
        // loading all Processes list
        this.showProcesses = !(this.settings.get('isRecord') === '1');

        if (this.showProcesses) {
            app.api.call('GET', app.api.buildURL('pmse_Project/filter?fields=id,name'), null, {
                success: _.bind(function (data) {
                    var options = {};

                    this.processCollection = data.records;
                    this.processCollection.unshift({
                        id: 'all',
                        name: app.lang.get('LBL_PMSE_ALL_PROCESSES_LABEL', 'pmse_Inbox')
                    });

                    //Filling options
                    _.each(this.processCollection, function (row) {
                        options[row.id] = row.name;
                    });
                    this.dashletConfig.processes_selector[0].options = options;
                    this.currentValue = 'all';

                    this.layout.render();
                    this.layout.loadData();
                }, this),
                complete: view.options ? view.options.complete : null
            });

            this.settings.on('change:processes_selector', function (context, value) {
                self.currentValue = value;
                self.loadData();
            });
        } else {
            this.currentValue = this.model.get('id');
            //this.loadData();
        }
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

        // Set value of label inside donut chart if is greater than zero
        if (this.total && this.total > 0) {
            this.chart.hole(this.total);
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
        url = app.api.buildURL('pmse_Inbox/processUsersChart/' + this.currentValue);
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
        this.total = response.properties.total;
        this.hasData = !!this.total;
        response.data.map(function(d) {
            d.value = parseInt(d.value, 10);
        });
        this.chartCollection = response;
    }
})
