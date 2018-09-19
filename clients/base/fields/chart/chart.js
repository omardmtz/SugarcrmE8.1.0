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
 * @class View.Fields.Base.ChartField
 * @alias SUGAR.App.view.fields.BaseChartField
 * @extends View.Fields.Base.BaseField
 */
({
    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        this.chart = null;
        this.chart_loaded = false;
        this.chartType = '';
        this.locale = SUGAR.charts.getSystemLocale();
    },

    /**
     * @inheritdoc
     */
    bindDataChange: function() {
        this.model.on('change:rawChartData', function(model, newChartData) {
            // make sure this.model.get('rawChartData') is not null by checking that
            // the newChartData (data set for the model's rawChartData) is not null
            if (newChartData && this.model.get('rawChartData').values.length > 0) {
                this.displayNoData(false);
                // if the chart already exists, remove it before we generate the new one
                if (this.chart_loaded) {
                    this.$('#d3_' + this.cid + ' svg').remove();
                }
                this.generateD3Chart();
            } else {
                this.displayNoData(true);
            }
        }, this);
    },

    overflowHandler: function(distance) {
        var b = this.view.$el.parents().filter(function() {
            return $(this).css('overflow-y') === 'auto' || $(this).css('overflow-y') === 'scroll';
        }).first();

        b.scrollTop(b.scrollTop() - distance);
    },

    /**
     * Callback function on chart render complete.
     *
     * @param {Function} chart sucrose chart instance
     * @param {Object} params chart display parameters
     * @param {Object} data report data with properties and data array
     */
    chartComplete: function(chart, params, reportData, chartData) {
        this.chart = chart;
        this.chart_loaded = _.isFunction(chart.update);

        if (!this.chart_loaded) {
            return;
        }

        this.view.trigger('chart:complete', chart, params, reportData, chartData);
    },

    /**
     * Generate the D3 Chart Object
     */
    generateD3Chart: function() {
        var id = this.cid;
        var reportData = this.model.get('rawReportData');
        var chartData = this.model.get('rawChartData');
        var params = this.getChartParams(chartData); //NOTE: This is where groupType comes from
        var config = this.getChartConfig(chartData, params);

        var sugarChart = new loadSugarChart(id, chartData, [], config, params, _.bind(function(chart) {
            this.chartComplete(chart, params, reportData, chartData);
        }, this));

        // This event fires when a preview is closed.
        // We listen to this event to call the chart resize method
        // in case the window was resized while the preview was open.
        app.events.on('preview:close', function() {
            if (_.isUndefined(app.drawer) || app.drawer.isActive(this.$el)) {
                this.resize();
            }
        }, this);
        // This event fires when the dashlet is collapsed or opened.
        // We listen to this event to call the chart resize method
        // in case the window was resized while the dashlet was closed.
        this.view.layout.on('dashlet:collapse', function(collapse) {
            if (!collapse) {
                this.resize();
            }
        }, this);
        // This event fires when the dashlet is dragged and dropped.
        // We listen to this event to call the chart resize method
        // because the size of the dashlet can change in the dashboard.
        this.view.layout.context.on('dashlet:draggable:stop', function() {
            this.resize();
        }, this);
        // Resize chart on window resize.
        // This event also fires when the sidebar is collapsed or opened.
        // We listen to this event to call the chart resize method
        // in case the window was resized while the sidebar was closed.
        $(window).on('resize.' + this.sfId, _.debounce(_.bind(this.resize, this), 100));
        // Resize chart on print.
        this.handlePrinting('on');
        // This on click event is required to dismiss the dropdown legend
        this.$('.sc-chart').on('click', _.bind(function() {
            this.chart.dispatch.call('chartClick', this);
        }, this));
    },

    getChartParams: function(chartData) {
        var chartId = this.cid;
        var chartParams = this.model.get('rawChartParams') || {};
        // Get properties from rawChartData
        var properties = !_.isUndefined(chartData.properties) && Array.isArray(chartData.properties) ?
                chartData.properties[0] :
                {};
        // These params will be overriden the SugarCharts defaults
        var params = {
                chart_type: 'multibar',
                margin: {top: 0, right: 10, bottom: 10, left: 10},
                allowScroll: true,
                module: properties.base_module,
                overflowHandler: _.bind(this.overflowHandler, this),
                baseModule: properties.base_module
            };
        var state = this.context.get('chartState');

        if (!_.isEmpty(chartParams)) {
            params = _.extend(params, chartParams);
        }
        if (!_.isEmpty(state)) {
            params.state = state;
        }

        return params;
    },

    /**
     * Builds the chart config based on the type of chart
     * @return {Mixed}
     */
    getChartConfig: function(chartData, chartParams) {
        var data = chartData || this.model.get('rawChartData');
        var params = chartParams || this.model.get('rawChartParams');
        var chartConfig;
        var chartGroupType;

        // chartData artifact
        if (!_.isEmpty(chartData) && !_.isUndefined(chartData.properties)) {
            data.properties[0].type = params.chart_type;
        }

        switch (params.chart_type) {
            case 'pie chart':
                chartConfig = {
                    pieType: 'basic',
                    chartType: 'pieChart'
                };
                break;

            case 'line chart':
                chartConfig = {
                    lineType: 'grouped',
                    chartType: 'lineChart'
                };
                break;

            case 'funnel chart':
            case 'funnel chart 3D':
                chartConfig = {
                    funnelType: 'basic',
                    chartType: 'funnelChart'
                };
                break;

            case 'gauge chart':
                chartConfig = {
                    gaugeType: 'basic',
                    chartType: 'gaugeChart'
                };
                break;

            case 'stacked group by chart':
                chartConfig = {
                    orientation: 'vertical',
                    barType: 'stacked',
                    chartType: 'barChart'
                };
                break;

            case 'group by chart':
                chartConfig = {
                    orientation: 'vertical',
                    barType: 'grouped',
                    chartType: 'barChart'
                };
                break;

            case 'bar chart':
                chartConfig = {
                    orientation: 'vertical',
                    barType: 'basic',
                    chartType: 'barChart'
                };
                break;

            case 'horizontal group by chart':
                chartConfig = {
                    orientation: 'horizontal',
                    barType: 'stacked',
                    chartType: 'barChart'
                };
                break;

            case 'horizontal bar chart':
            case 'horizontal':
                chartConfig = {
                    orientation: 'horizontal',
                    barType: 'basic',
                    chartType: 'barChart'
                };
                break;

            default:
                chartConfig = {
                    orientation: 'vertical',
                    barType: 'stacked',
                    chartType: 'barChart'
                };
                break;
        }

        chartConfig.direction = app.lang.direction;

        // chartParams artifact
        chartGroupType = chartConfig.barType ||
            chartConfig.lineType ||
            chartConfig.pieType ||
            chartConfig.funnelType ||
            'basic';
        chartParams.dataType = chartGroupType === 'stacked' ? 'grouped' : chartGroupType;

        this.chartType = chartConfig.chartType;

        return chartConfig;
    },

    /**
     * Checks to see if the chart is available and is displayed before resizing
     */
    resize: function() {
        // If (this.chart_loaded && !this.sidebar_closed && !this.preview_open && !this.dashlet_collapsed) {
        if (!this.chart_loaded) {
            return;
        }
        // This handles the case of preview open and dashlet collapsed.
        // We don't need to handle the case of collapsed sidepane
        // because charts can resize when inside an invisible container.
        // It is being inside a display:none container that causes problems.
        if (!this.view.$el || !this.view.$el.is(':visible')) {
            return;
        }
        if (this.chart.render) {
            this.chart.render();
        } else {
            this.chart.update();
        }
    },

    /**
     * Attach and detach a resize method to the print event
     * @param {string} The state of print handling.
     */
    handlePrinting: function(state) {
        var self = this,
            mediaQueryList = window.matchMedia && window.matchMedia('print'),
            pausecomp = function(millis) {
                // www.sean.co.uk
                var date = new Date(),
                    curDate = null;
                do {
                    curDate = new Date();
                } while (curDate - date < millis);
            },
            printResize = function(mql) {
                if (mql.matches) {
                    if (!_.isUndefined(self.chart.legend) && _.isFunction(self.chart.legend.showAll)) {
                        self.chart.legend.showAll(true);
                    }
                    self.chart.width(640).height(320).update();
                    pausecomp(200);
                } else {
                    browserResize();
                }
            },
            browserResize = function() {
                if (!_.isUndefined(self.chart.legend) && _.isFunction(self.chart.legend.showAll)) {
                    self.chart.legend.showAll(false);
                }
                self.chart.width(null).height(null).update();
            };

        if (state === 'on') {
            if (window.matchMedia) {
                mediaQueryList.addListener(printResize);
            } else if (window.attachEvent) {
                window.attachEvent('onbeforeprint', printResize);
                window.attachEvent('onafterprint', browserResize);
            } else {
                window.onbeforeprint = printResize;
                window.onafterprint = browserResize;
            }
        } else {
            if (window.matchMedia) {
                mediaQueryList.removeListener(printResize);
            } else if (window.detachEvent) {
                window.detachEvent('onbeforeprint', printResize);
                window.detachEvent('onafterprint', browserResize);
            } else {
                window.onbeforeprint = null;
                window.onafterprint = null;
            }
        }
    },

    /**
     * Toggle display of dashlet content and NoData message
     * @param {boolean} state The visibility state of the dashlet content.
     */
    displayNoData: function(state) {
        this.$('[data-content="chart"]').toggleClass('hide', state);
        this.$('[data-content="nodata"]').toggleClass('hide', !state);
    },

    /**
     * @inheritdoc
     */
    _dispose: function() {
        if (this.view && this.view.layout) {
            this.view.layout.off(null, null, this);
        }
        if (this.view && this.view.layout) {
            this.view.layout.context.off(null, null, this);
        }
        this.$('.sc-chart').off('click');
        $(window).off('resize.' + this.sfId);
        this.handlePrinting('off');

        app.view.Field.prototype._dispose.call(this);
    }
})
