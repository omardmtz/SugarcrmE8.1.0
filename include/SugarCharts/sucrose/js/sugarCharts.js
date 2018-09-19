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

function loadSugarChart(chartId, jsonFilename, css, chartConfig, chartParams, callback) {
    // get chartId from params or use the default for sugar
    var d3ChartId = 'd3_' + chartId || 'd3_c3090c86-2b12-a65e-967f-51b642ac6165';

    // make sure the chart container exists
    if (!document.getElementById(d3ChartId)) {
        return;
    }

    var chartGroupType = chartConfig.barType ||
            chartConfig.lineType ||
            chartConfig.pieType ||
            chartConfig.funnelType ||
            'basic';

    // fix report view
    if (_.isUndefined(chartParams.chart_type) && !_.isUndefined(chartParams.type)) {
        chartParams.chart_type = chartParams.type;
        chartParams.type = 'saved-report-view';
    }

    // update default params from chartConfig and then chartParams
    var params = _.extendOwn({
        allowScroll: false,
        baseModule: 'Reports',
        chart_type: 'bar chart',
        colorData: 'class',
        dataType: chartGroupType === 'stacked' ? 'grouped' : chartGroupType,
        direction: 'ltr',
        hideEmptyGroups: true,
        label: SUGAR.charts.translateString('LBL_DASHLET_SAVED_REPORTS_CHART'),
        margin: {top: 10, right: 10, bottom: 10, left: 10},
        module: 'Reports',
        overflowHandler: false,
        reduceXTicks: false,
        reportView: false,
        rotateTicks: true,
        saved_report_id: chartId,
        show_controls: false,
        show_legend: 'on',
        show_title: true,
        show_tooltips: true,
        show_x_label: false,
        show_y_label: false,
        showValues: false,
        stacked: true,
        staggerTicks: true,
        type: 'saved-report-view',
        vertical: true,
        wrapTicks: true,
        x_axis_label: '',
        y_axis_label: '',
        allow_drillthru: true
    }, chartConfig, chartParams);

    params.vertical = (chartConfig.orientation ? chartConfig.orientation === 'vertical' : false);

    // controls if chart image is auto-saved
    var imageExportType = chartConfig.imageExportType;
    // determines Report viewer settings in BWC module view
    // and if basic bar chart is displayed as discrete by default
    var isReportView = chartConfig.reportView || false;

    // locale config object based on user/system preferences
    var myLocale = SUGAR.charts.getLocale();
    var tooltipTemplate = SUGAR.charts._getTooltipTemplate(chartConfig.chartType);

    // chart display strings
    var chartStrings = SUGAR.charts.getChartStrings(chartConfig.chartType);
    var displayErrorMsg = SUGAR.charts.translateString('LBL_CANNOT_DISPLAY_CHART_MESSAGE', 'Reports');
    var noDataAvailable = SUGAR.charts.translateString('LBL_NO_DATA_AVAILABLE');

    // get and save the fiscal start date
    SUGAR.charts.defineFiscalYearStart();

    // instantiate Sucrose chart
    switch (chartConfig.chartType) {

        case 'barChart':
            SUGAR.charts.get(jsonFilename, params, function(data) {
                var json;
                var barChart;
                // it will be set to chart.locality() after instantiation
                var locality = {};

                if (SUGAR.charts.dataIsEmpty(data)) {
                    SUGAR.charts.renderError(chartId, noDataAvailable);
                    return;
                }

                json = SUGAR.charts.transformDataToD3(data, params, chartConfig);

                if (json.properties && json.properties.labels && json.properties.labels.length > 50) {
                    SUGAR.charts.renderError(chartId, displayErrorMsg);
                    return;
                }

                barChart = sucrose.charts.multibarChart()
                    .id(d3ChartId)
                    .vertical(params.vertical)
                    .margin(params.margin)
                    .showTitle(params.show_title)
                    .tooltips(params.show_tooltips)
                    .tooltipContent(function(eo, properties) {
                        return SUGAR.charts.formatTooltipMultiple(eo, properties, barChart, tooltipTemplate, params);
                    })
                    .direction(params.direction)
                    .showLegend(params.show_legend)
                    .showControls(params.show_controls)
                    .wrapTicks(params.wrapTicks)
                    .staggerTicks(params.staggerTicks)
                    .rotateTicks(params.rotateTicks)
                    .reduceXTicks(params.reduceXTicks)
                    .colorData(params.colorData)
                    .stacked(params.stacked)
                    .allowScroll(params.allowScroll)
                    .overflowHandler(params.overflowHandler)
                    .showValues(params.showValues)
                    .valueFormat(function(d, i, label, isCurrency) {
                        return sucrose.utility.numberFormatSI(d, 0, isCurrency, locality);
                    })
                    .strings(chartStrings)
                    .locality(myLocale);

                barChart.textureFill(true);

                barChart.yAxis.tickSize(0);

                locality = barChart.locality();

                //check to see if thousands symbol is in use
                if (
                    typeof data.properties[0] === 'object' &&
                    (typeof data.properties[0].thousands !== 'undefined' &&
                    parseInt(data.properties[0].thousands) === 1)
                ) {
                    //TODO: evaluate use of sucrose.utility.numberFormat
                    //create formatter with thousands symbol
                    var cFormat = (d3sugar.format('s'));
                    //the tick value comes in shortened from api,
                    //multiply times 1k and apply formatting
                    barChart.yAxis
                        .tickFormat(function(d) {
                            return cFormat(d * 1000);
                        });
                }

                if (params.show_x_label) {
                    barChart.xAxis.axisLabel(params.x_axis_label);
                }

                if (params.show_y_label) {
                    barChart.yAxis.axisLabel(params.y_axis_label);
                }

                if (isReportView) {

                    if (chartConfig.orientation === 'vertical') {
                        barChart.legend.rowsCount(5);
                        barChart.legend.showAll(false);
                    } else {
                        barChart.legend.showAll(true);
                    }

                    SUGAR.charts.trackWindowResize(barChart);

                    if (imageExportType) {
                        SUGAR.charts.saveImageFile(chartId, barChart, json, jsonFilename, imageExportType);
                    } else {
                        SUGAR.charts.renderChart(chartId, barChart, json);
                    }
                } else {
                    SUGAR.charts.renderChart(chartId, barChart, json);

                    if (params.state) {
                        barChart.cellActivate(params.state);
                    }
                }

                SUGAR.charts.callback(callback, barChart, chartId, params, data);
            });
            break;

        case 'lineChart':
            SUGAR.charts.get(jsonFilename, params, function(data) {
                var json;
                var lineChart;
                var xTickLabels;
                var tickFormat = function(d) { return d; };

                if (SUGAR.charts.dataIsEmpty(data)) {
                    SUGAR.charts.renderError(chartId, noDataAvailable);
                    return;
                }

                json = SUGAR.charts.transformDataToD3(data, params, chartConfig);

                lineChart = sucrose.charts.lineChart()
                    .id(d3ChartId)
                    .margin(params.margin)
                    .tooltips(params.show_tooltips)
                    .tooltipContent(function(eo, properties) {
                        return SUGAR.charts.formatTooltipMultiple(eo, properties, lineChart, tooltipTemplate, params);
                    })
                    .direction(params.direction)
                    .showTitle(params.show_title)
                    .showLegend(params.show_legend)
                    .showControls(params.show_controls)
                    .useVoronoi(true)
                    .clipEdge(false)
                    .wrapTicks(params.wrapTicks)
                    .staggerTicks(params.staggerTicks)
                    .rotateTicks(params.rotateTicks)
                    .colorData(params.colorData)
                    .strings(chartStrings)
                    .locality(myLocale);

                if (params.show_x_label) {
                    lineChart.xAxis.axisLabel(params.x_axis_label);
                }

                if (params.show_y_label) {
                    lineChart.yAxis.axisLabel(params.y_axis_label);
                }

                if (json.data.length) {
                    xTickLabels = json.properties.labels ?
                        json.properties.labels.map(function(d) { return d.l || d; }) :
                        [];

                    if (xTickLabels.length > 0) {
                        tickFormat = function(d) { return xTickLabels[d - 1] || ' '; };
                    } else if (json.properties.xDataType === 'datetime') {
                        //TODO: this is incorrect?
                        tickFormat = function(d) { return d3sugar.timeFormat('%x')(new Date(d)); };
                    }
                }

                lineChart.xAxis
                    .tickFormat(tickFormat)
                    .highlightZero(false)
                    .reduceXTicks(false);

                if (isReportView) {
                    lineChart.legend.showAll(true);

                    SUGAR.charts.trackWindowResize(lineChart);

                    if (imageExportType) {
                        SUGAR.charts.saveImageFile(chartId, lineChart, json, jsonFilename, imageExportType);
                    } else {
                        SUGAR.charts.renderChart(chartId, lineChart, json);
                    }
                } else {
                    SUGAR.charts.renderChart(chartId, lineChart, json);

                    if (params.state) {
                        lineChart.cellActivate(params.state);
                    }
                }

                SUGAR.charts.callback(callback, lineChart, chartId, params, data);
            });
            break;

        case 'pieChart':
            SUGAR.charts.get(jsonFilename, params, function(data) {
                var json;
                var yIsCurrency;
                var pieChart;

                if (SUGAR.charts.dataIsEmpty(data)) {
                    SUGAR.charts.renderError(chartId, noDataAvailable);
                    return;
                }

                json = SUGAR.charts.transformDataToD3(data, params, chartConfig);

                if (json.properties && json.properties.labels && json.properties.labels.length > 50) {
                    SUGAR.charts.renderError(chartId, displayErrorMsg);
                    return;
                }

                yIsCurrency = json.properties.yDataType === 'currency';

                pieChart = sucrose.charts.pieChart()
                    .id(d3ChartId)
                    .margin(params.margin)
                    .tooltips(params.show_tooltips)
                    .tooltipContent(function(eo, properties) {
                        return SUGAR.charts.formatTooltipSingle(eo, properties, pieChart, tooltipTemplate, params);
                    })
                    .showTitle(params.show_title)
                    .showLegend(params.show_legend)
                    .colorData(params.colorData)
                    .donut(params.donut || false)
                    .donutLabelsOutside(params.donutLabelsOutside || false)
                    .hole(params.hole || false)
                    .donutRatio(params.donutRatio || 0.5)
                    .rotateDegrees(0)
                    .arcDegrees(360)
                    // .fixedRadius(function(chart) {
                    //     var n = d3sugar.select('#d3_' + chartId).node(),
                    //         r = Math.min(n.clientWidth * 0.25, n.clientHeight * 0.4);
                    //     return Math.max(r, 75);
                    // })
                    .direction(params.direction)
                    .fmtValue(function(d) {
                        return d.label || d.value || d;
                    })
                    .fmtCount(function(d) {
                        return !isNaN(d.count) ? (' (' + d.count + ')') : '';
                    })
                    .strings(chartStrings)
                    .locality(myLocale);

                pieChart.textureFill(true);

                if (isReportView) {
                    pieChart.legend.showAll(true);

                    SUGAR.charts.trackWindowResize(pieChart);

                    if (imageExportType) {
                        SUGAR.charts.saveImageFile(chartId, pieChart, json, jsonFilename, imageExportType);
                    } else {
                        SUGAR.charts.renderChart(chartId, pieChart, json);
                    }
                } else {
                    SUGAR.charts.renderChart(chartId, pieChart, json);

                    if (params.state) {
                        pieChart.seriesActivate(params.state);
                    }
                }

                SUGAR.charts.callback(callback, pieChart, chartId, params, data);
            });
            break;

        case 'funnelChart':
            SUGAR.charts.get(jsonFilename, params, function(data) {
                var json;
                var yIsCurrency;
                var funnelChart;

                if (SUGAR.charts.dataIsEmpty(data)) {
                    SUGAR.charts.renderError(chartId, noDataAvailable);
                    return;
                }

                json = SUGAR.charts.transformDataToD3(data, params, chartConfig);

                if (json.properties && json.properties.labels && json.properties.labels.length > 16) {
                    SUGAR.charts.renderError(chartId, displayErrorMsg);
                    return;
                }

                yIsCurrency = json.properties.yDataType === 'currency';

                funnelChart = sucrose.charts.funnelChart()
                    .id(d3ChartId)
                    .margin(params.margin)
                    .showTitle(params.show_title)
                    .tooltips(params.show_tooltips)
                    .tooltipContent(function(eo, properties) {
                        return SUGAR.charts.formatTooltipSingle(eo, properties, funnelChart, tooltipTemplate, params);
                    })
                    .direction(params.direction)
                    .colorData(params.colorData)
                    .fmtValue(function(d) {
                        return d.label || d.value || d;
                    })
                    .fmtCount(function(d) {
                        return !isNaN(d.count) ? (' (' + d.count + ')') : '';
                    })
                    .strings(chartStrings)
                    .locality(myLocale);

                funnelChart.textureFill(true);

                if (isReportView) {
                    funnelChart.legend.showAll(true);

                    SUGAR.charts.trackWindowResize(funnelChart, chartId, data);

                    if (imageExportType) {
                        SUGAR.charts.saveImageFile(chartId, funnelChart, json, jsonFilename, imageExportType);
                    } else {
                        SUGAR.charts.renderChart(chartId, funnelChart, json);
                    }
                } else {
                    SUGAR.charts.renderChart(chartId, funnelChart, json);

                    if (params.state) {
                        funnelChart.seriesActivate(params.state);
                    }
                }

                SUGAR.charts.callback(callback, funnelChart, chartId, params, data);
            });
            break;

        case 'gaugeChart':
            SUGAR.charts.get(jsonFilename, params, function(data) {
                var json;
                var maxValue;
                var gaugeChart;

                if (SUGAR.charts.dataIsEmpty(data)) {
                    SUGAR.charts.renderError(chartId, noDataAvailable);
                    return;
                }

                json = SUGAR.charts.transformDataToD3(data, params, chartConfig);
                maxValue = d3sugar.max(json.data.map(function(d) { return d.y; }));

                if (maxValue === 0) {
                    json.data[0].y = 1;
                    maxValue = 1;
                }

                json.data.map(function(d, i) {
                    d.classes = 'sc-fill0' + (i + 1);
                });

                //init Gauge Chart
                gaugeChart = sucrose.charts.gaugeChart()
                    .id(d3ChartId)
                    .x(function(d) { return d.key; })
                    .y(function(d) { return d.y; })
                    .direction(params.direction)
                    .showLabels(true)
                    .showTitle(true)
                    .colorData('class')
                    .ringWidth(50)
                    .maxValue(maxValue)
                    .transitionMs(4000);

                if (isReportView) {
                    gaugeChart.legend.showAll(true);

                    SUGAR.charts.trackWindowResize(gaugeChart);

                    if (imageExportType) {
                        SUGAR.charts.saveImageFile(chartId, gaugeChart, json, jsonFilename, imageExportType);
                    } else {
                        SUGAR.charts.renderChart(chartId, gaugeChart, json);
                    }
                } else {
                    SUGAR.charts.renderChart(chartId, gaugeChart, json);
                }

                SUGAR.charts.callback(callback, gaugeChart, chartId, params, data);
            });
            break;
    }
}

/**
 * Global sugar chart class
 */
(function($) {
    if (typeof SUGAR === 'undefined' || !SUGAR) {
        SUGAR = {};
    }

    SUGAR.charts = {
        sugarApp: (SUGAR.App || SUGAR.app || app),

        /**
         * Execute callback function if specified
         *
         * @param callback function to invoke after chart rendering
         * @param chart Sucrose chart instance to render
         * @param chartId chart id used to select the chart container
         * @param params chart display control parameters
         */
        callback: function(callback, chart, chartId, params, chartData) {
            if (!_.isFunction(chart.update)) {
                return;
            }

            if (callback) {
                // if the call back is provided, include the chart as the only param
                callback(chart);
                return;
            }

            // only assign the event handler if chart supports it
            if (!_.isFunction(chart.seriesClick) || !params.allow_drillthru) {
                return;
            }

            // This default seriesClick callback is normally used
            // by the Report module charts. Saved Reports Chart
            // dashlets override with their own handler
            chart.seriesClick(_.bind(function(data, eo, chart, labels) {
                var chartState;
                var drawerContext;

                chartState = this.buildChartState(eo, labels);
                if (!_.isFinite(chartState.seriesIndex)) {
                    return;
                }

                if (params.chart_type === 'line chart') {
                    params.groupLabel = this.extractSeriesLabel(chartState, data);
                    params.seriesLabel = this.extractGroupLabel(chartState, labels);
                } else {
                    params.seriesLabel = this.extractSeriesLabel(chartState, data);
                    params.groupLabel = this.extractGroupLabel(chartState, labels);
                }

                // report_def is defined as a global in _reportCriteriaWithResult
                // but only in Reports module
                //TODO: fix usage of global report_def
                var enums = this.getEnums(report_def);
                var groupDefs = this.getGrouping(report_def);

                drawerContext = {
                    chartData: chartData,
                    chartModule: report_def.module,
                    chartState: chartState,
                    dashConfig: params,
                    dashModel: null,
                    enumsToFetch: enums,
                    filterOptions: {
                        auto_apply: false
                    },
                    groupDefs: groupDefs,
                    layout: 'drillthrough-drawer',
                    module: 'Reports',
                    reportData: report_def,
                    reportId: chartId,
                    skipFetch: true,
                    useSavedFilters: true
                };

                chart.clearActive();
                if (chart.cellActivate) {
                    chart.cellActivate(chartState);
                } else if (chart.seriesActivate) {
                    chart.seriesActivate(chartState);
                } else {
                    chart.dataSeriesActivate(eo);
                }
                chart.dispatch.call('tooltipHide', this);

                this.sugarApp.alert.show('listfromreport_loading', {
                    level: 'process',
                    title: this.translateString('LBL_LOADING')
                });
                chart.clearActive();
                chart.render();
                this.openDrawer(drawerContext);

            }, this));
        },

        /**
         * Create an active state object based on chart element clicked
         *
         * @param eo an event object with extended properties
         * constructed from a clicked chart element
         * @param labels an array of grouping labels
         */
        buildChartState: function(eo, labels) {
            var seriesIndex;
            var state = {};

            if (!_.isEmpty(eo.series) && _.isFinite(eo.series.seriesIndex)) {
                seriesIndex = eo.series.seriesIndex;
            } else if (_.isFinite(eo.seriesIndex)) {
                seriesIndex = eo.seriesIndex;
            }
            if (_.isEmpty(labels)) {
                if (!_.isFinite(seriesIndex) && _.isFinite(eo.pointIndex)) {
                    seriesIndex = eo.pointIndex;
                }
            } else {
                if (_.isFinite(eo.groupIndex)) {
                    state.groupIndex = eo.groupIndex;
                }
                if (_.isFinite(eo.pointIndex)) {
                    state.pointIndex = eo.pointIndex;
                }
            }
            state.seriesIndex = seriesIndex;

            return state;
        },

        /**
         * Get the series label from chart data based on chart element clicked
         *
         * @param eo an event object with extended properties
         * constructed from a clicked chart element
         * @param data report data
         */
        extractSeriesLabel: function(state, data) {
            return data[state.seriesIndex].key;
        },

        /**
         * Get the group label from chart labels based on chart element clicked
         *
         * @param eo an event object with extended properties
         * constructed from a clicked chart element
         * @param labels an array of grouping labels
         */
        extractGroupLabel: function(state, labels) {
            return _.isEmpty(labels) ? null : labels[state.pointIndex || state.groupIndex];
        },

        /**
         * Get the first or second grouping from report definition
         * or the first grouping if there is only one
         *
         * @param reportDef report definition object
         * @param i group definition index
         * @return {Object}
         */
        getGrouping: function(reportDef, i) {
            var groupDefs = reportDef.group_defs;
            if (isNaN(i)) {
                return groupDefs;
            }
            return i > 0 && groupDefs.length > 1 ? groupDefs[1] : groupDefs[0];
        },

        /**
         * Get and save the fiscal year start date as an application cached variable
         */
        defineFiscalYearStart: function() {
            var fiscalYear = this.getFiscalStartDate();

            if (!_.isEmpty(fiscalYear)) {
                return;
            }

            fiscalYear = new Date().getFullYear();

            this.sugarApp.api.call('GET', this.sugarApp.api.buildURL('TimePeriods/' + fiscalYear + '-01-01'), null, {
                success: _.bind(this.setFiscalStartDate, this),
                error: _.bind(function() {
                    // Needed to catch the 404 in case there isnt a current timeperiod
                }, this)
            });
        },

        /**
         * Process and set the defined fiscal time period in the application cache
         *
         * @param firstQuarter the currently configured fiscal time period
         */
        setFiscalStartDate: function(firstQuarter) {
            var fiscalYear = firstQuarter.start_date.split('-')[0];
            var quarterNumber = firstQuarter.name.match(/.*Q(\d{1})/)[1];  // [1-4]
            var quarterDateStart = new Date(firstQuarter.start_date);      // 2017-01-01
            var hourUTCOffset = quarterDateStart.getTimezoneOffset() / 60; // 5
            var fiscalMonth = quarterDateStart.getUTCMonth() - (quarterNumber - 1) * 3; // 1
            var fiscalYearStart = new Date(fiscalYear, fiscalMonth, 1, -hourUTCOffset, 0, 0).toUTCString();
            this.sugarApp.cache.set('fiscaltimeperiods', {'annualDate': fiscalYearStart});
        },

        /**
         * Get the currently defined fiscal time period from the application cache
         *
         * @return {string} a string representation of a UTC datetime
         */
        getFiscalStartDate: function() {
            var timeperiods = this.sugarApp.cache.get('fiscaltimeperiods');
            var datetime = !_.isEmpty(timeperiods) && !_.isUndefined(timeperiods.annualDate) ?
                timeperiods.annualDate :
                null;
            return datetime;
        },

        /**
         * Process the user selected chart date label based on the report def
         * column function
         *
         * @param label chart group or series label
         * @param type group or series column function
         * @return {Array} a date range from a date parsed label
         */
        getDateValues: function(label, type) {
            var dateParser = this.sugarApp.date;
            var userLangPref = this.sugarApp.user.getLanguage() || 'en_us';
            var datePatterns = {
                year: 'YYYY', // 2017
                quarter: 'Q YYYY', // Q3 2017
                month: 'MMMM YYYY', // March 2017
                week: 'W YYYY', // W56 2017
                day: 'YYYY-MM-DD' //2017-12-31
            };
            var startDate;
            var endDate;
            var y1;
            var y2;
            var m1;
            var m2;
            var d1;
            var d2;
            var values = [];

            switch (type) {

                case 'fiscalYear':
                    // 2017
                    var fy = new Date(this.getFiscalStartDate() || new Date().getFullYear() + '-01-01');
                    fy.setUTCFullYear(label);
                    y1 = fy.getUTCFullYear();
                    m1 = fy.getUTCMonth() + 1;
                    d1 = fy.getUTCDate();
                    fy.setUTCMonth(fy.getUTCMonth() + 12);
                    fy.setUTCDate(fy.getUTCDate() - 1);
                    y2 = fy.getUTCFullYear();
                    m2 = fy.getUTCMonth() + 1;
                    d2 = fy.getUTCDate(); //1-31
                    startDate = y1 + '-' + m1 + '-' + d1;
                    endDate = y2 + '-' + m2 + '-' + d2;
                    break;

                case 'fiscalQuarter':
                    // Q1 2017
                    var fy = new Date(this.getFiscalStartDate() || new Date().getFullYear() + '-01-01');
                    var re = /Q([1-4]{1})\s(\d{4})/;
                    var rm = label.match(re);
                    fy.setUTCFullYear(rm[2]);
                    fy.setUTCMonth((rm[1] - 1) * 3 + fy.getUTCMonth());
                    y1 = fy.getUTCFullYear();
                    m1 = fy.getUTCMonth() + 1;
                    d1 = fy.getUTCDate();
                    fy.setUTCMonth(m1 + 2);
                    fy.setUTCDate(fy.getUTCDate() - 1);
                    y2 = fy.getUTCFullYear();
                    m2 = fy.getUTCMonth() + 1;
                    d2 = fy.getUTCDate();
                    startDate = y1 + '-' + m1 + '-' + d1;
                    endDate = y2 + '-' + m2 + '-' + d2;
                    break;

                case 'day':
                    var pattern = datePatterns[type];
                    var parsedDate = dateParser(label, pattern, userLangPref);
                    startDate = parsedDate.formatServer(true); //2017-12-31
                    endDate = 'on';
                    break;

                default:
                    var pattern = datePatterns[type] || 'YYYY';
                    var parsedDate = dateParser(label, pattern, userLangPref);
                    var momentType = type === 'week' ? 'isoweek' : type;
                    startDate = parsedDate.startOf(momentType).formatServer(true); //2017-01-01
                    endDate = parsedDate.endOf(momentType).formatServer(true); //2017-12-31
                    break;
            }

            values.push(startDate);
            if (type !== 'day') {
                values.push(endDate);
                values.push(type);
            }

            return values;
        },

        /**
         * Process the user selected chart label and return an array with a
         * single filter input value, or three if a date range
         *
         * @param label chart group or series label
         * @param def report definition object
         * @param type the data type for the field
         * @param enums list of enums with their key value data translations
         *
         * @return {Array} a single element if not a date else three
         */
        getValues: function(label, def, type, enums) {
            var dateFunctions = ['year', 'quarter', 'month', 'week', 'day', 'fiscalYear', 'fiscalQuarter'];
            var columnFn = def.column_function;
            var isDateFn = !_.isEmpty(columnFn) && dateFunctions.indexOf(columnFn) !== -1;
            var values = [];

            // Send empty string if value is undefined
            if (this.translateString('LBL_CHART_UNDEFINED') === label) {
                label = '';
            }

            if (isDateFn) {
                // returns [dateStart, dateEnd, columnFn]
                values = this.getDateValues(label, columnFn);
            } else {
                switch (type) {
                    case 'bool':
                        if (this.translateListStrings('dom_switch_bool').on === label) {
                            values.push('1');
                        } else if (this.translateListStrings('dom_switch_bool').off === label) {
                            values.push('0');
                        }
                        break;
                    case 'enum':
                    case 'radioenum':
                        values.push(enums[def.table_key + ':' + def.name][label]);
                        break;
                    default:
                        // returns [label]
                        values.push(label);
                        break;
                }
            }

            return values;
        },

        /**
         * Construct a new report definition filter
         *
         * @param reportDef report definition object
         * @param params chart display control parameters
         * @param enums list of enums with their key value data translations
         * @return {Array}
         */
        buildFilter: function(reportDef, params, enums) {
            var filter = [];

            var groups = this.getGrouping(reportDef, 0);
            var series = this.getGrouping(reportDef, 1);
            var groupType = this.getFieldDef(groups, reportDef).type;
            var seriesType = this.getFieldDef(series, reportDef).type;
            var isGroupType = params.dataType === 'grouped';
            var groupLabel = params.groupLabel;
            var seriesLabel = params.seriesLabel;

            var hasSameLabel = !_.isEmpty(seriesLabel) &&
                               !_.isEmpty(groupLabel) &&
                               seriesLabel === groupLabel;
            var hasSameGroup = groups.name === series.name &&
                               groups.label === series.label &&
                               groups.table_key === series.table_key;

            var addGroupRow = _.bind(function() {
                var groupsName = groups.table_key + ':' + groups.name;
                var groupsValues = this.getValues(groupLabel, groups, groupType, enums);
                addFilterRow(groupsName, groupsValues);
            }, this);

            var addSeriesRow = _.bind(function() {
                var seriesName = series.table_key + ':' + series.name;
                var seriesValues = this.getValues(seriesLabel, series, seriesType, enums);
                addFilterRow(seriesName, seriesValues);
            }, this);

            function addFilterRow(name, values) {
                var row = {};
                row[name] = values;
                filter.push(row);
            }

            // PIE | FUNNEL CHART & DISCRETE DATA
            if (!isGroupType && hasSameGroup && !_.isEmpty(seriesLabel) && _.isEmpty(groupLabel)) {
                // then use series
                groupLabel = groupLabel || seriesLabel;
                params.groupLabel = groupLabel;
                addSeriesRow();
            }
            // BASIC TYPE & DISCRETE DATA
            /*
                Accounts by Type ::
                Bar Chart :: industry == industry && Apparel != Accounts
            */
            else if (!isGroupType && hasSameGroup && !hasSameLabel) {
                // then use group
                addGroupRow();
            }
            // PIE | FUNNEL CHART & GROUPED DATA
            // this happens when data with multiple groupings is displayed as pie or funnel
            /*
                Accounts by Type by Industry ::
                Bar Chart :: type != industry
            */
            else if (!isGroupType && !hasSameGroup) {
                // then use group
                if (!hasSameLabel) {
                    groupLabel = groupLabel || seriesLabel;
                    params.groupLabel = groupLabel;
                }
                addGroupRow();
            }
            // GROUPED OR BASIC TYPE & DISCRETE DATA (isGroupType ignored)
            /*
                Accounts by Type
                Bar Grouped Chart :: type == type && Apparel == Apparel
            */
            else if (hasSameGroup && hasSameLabel) {
                // then use either, but only one
                addSeriesRow();
            }
            // GROUPED TYPE & GROUPED DATA
            /*
                Accounts by Type by Industry ::
                Bar Grouped Chart :: type != industry
            */
            else if (isGroupType && !hasSameGroup) {
                // then use both
                addGroupRow();
                addSeriesRow();
            }

            return filter;
        },

        /**
         * If the type for the group by field is an enum type, return it
         *
         * @param reportDef
         * @return {Array} array of enums group defs
         */
        getEnums: function(reportDef) {
            var enumTypes = ['enum', 'radioenum'];
            var groups = this.getGrouping(reportDef);
            var enums = [];
            _.each(groups, function(group) {
                var groupType = this.getFieldDef(group, reportDef).type;
                if (groupType && _.contains(enumTypes, groupType)) {
                    enums.push(group);
                }
            }, this);
            return enums;
        },

        /**
         * Gets the field def from the group def
         *
         * @param groupDef
         * @param reportDef
         * @return {*} array
         */
        getFieldDef: function(groupDef, reportDef) {
            var module = reportDef.module || reportDef.base_module;

            if (groupDef.table_key === 'self') {
                return this.sugarApp.metadata.getField({name: groupDef.name, module: module});
            }

            // Need to parse something like 'Accounts:contacts:assigned_user_link:user_name'
            var relationships = groupDef.table_key.split(':');
            var fieldsMeta = this.sugarApp.metadata.getModule(module, 'fields');
            var fieldDef;
            for (var i = 1; i < relationships.length; i++) {
                var relationship = relationships[i];
                fieldDef = fieldsMeta[relationship];
                module = fieldDef.module || this._getModuleFromRelationship(fieldDef.relationship, module);
                fieldsMeta = this.sugarApp.metadata.getModule(module, 'fields');
            }
            fieldDef = fieldsMeta[groupDef.name];
            fieldDef.module = fieldDef.module || module;
            return fieldDef;
        },

        /**
         * Get the other side's module name
         *
         * @param relationshipName
         * @param module
         * @return {string} module name
         * @private
         */
        _getModuleFromRelationship: function(relationshipName, module) {
            var relationship = this.sugarApp.metadata.getRelationship(relationshipName);
            return module === relationship.lhs_module ? relationship.rhs_module : relationship.lhs_module;
        },

        /**
         * Open a drill through drawer
         */
        openDrawer: function(drawerContext) {
            var currentModule = this.sugarApp.drawer.context.get('module');

            // This needs to set to target module for Merge to show the target module fields
            this.sugarApp.drawer.context.set('module', drawerContext.chartModule);

            this.sugarApp.drawer.open({
                layout: 'drillthrough-drawer',
                context: drawerContext
            }, _.bind(function() {
                // reset the drawer module
                if (currentModule) {
                    this.sugarApp.drawer.context.set('module', currentModule);
                }
            }, this, currentModule));
        },

        /**
         * Main render chart method
         *
         * @param id chart id used to select the chart container
         * @param chart Sucrose chart instance to render
         * @param json report data to render
         */
        renderChart: function(id, chart, json) {
            $('#d3_' + id).empty();
            d3sugar.select('#d3_' + id)
                .append('svg')
                .attr('class', 'sucrose')
                .datum(json)
                .transition().duration(500)
                .call(chart);
        },

        /**
         * Display an error message in chart container
         *
         * @param id chart id used to select the chart container
         * @param str error message string
         */
        renderError: function(id, message) {
            $('#d3_' + id).empty();
            d3sugar.select('.reportChartContainer')
                .style('height', 'auto');
            d3sugar.select('.reportChartContainer .chartContainer')
                .style('float', 'none')
                .style('position', 'relative')
                .style('width', '100%');
            d3sugar.select('#d3_' + id)
                .style('height', 'auto')
                .append('div')
                    .attr('class', 'sc-data-error')
                    .attr('align', 'center')
                    .style('padding', '12px')
                    .text(message);
        },

        /**
         * Calls the server to retrieve chart data, but
         * For D3 charts we already have the data, don't need to make an ajax call to get anything
         * so this is now a polymorphic method.
         *
         * @param urlordata - JSON data for the chart field or target url for Reports module
         * @param param - object of parameters to pass to the server
         * @param success - callback function to be executed after a successful call
         */
        get: function(urlordata, params, success) {
            var data;

            if (_.isString(urlordata)) {
                data = {
                    r: new Date().getTime()
                };
                $.extend(data, params);
                $.ajax({
                    url: urlordata,
                    data: data,
                    dataType: 'json',
                    async: false,
                    success: success
                });
            } else {
                success(urlordata);
            }
        },

        /**
         * Translate a chart string using current application language
         *
         * @param {string} appString string to translate
         * @param {string} module module where the string is defined
         * @return {string}
         */
        translateString: function(appString, module) {
            if (SUGAR.App) {
                // Sidecar
                if (module) {
                    return SUGAR.App.lang.get(appString, module);
                } else {
                    return SUGAR.App.lang.get(appString);
                }
            } else if (typeof app !== 'undefined' && app && app.lang) {
                // BWC works
                if (module) {
                    return app.lang.get(appString, module);
                } else {
                    return app.lang.get(appString);
                }
            } else if (SUGAR.language) {
                // BWC not works?
                if (module) {
                    return SUGAR.language.get(module, appString);
                } else {
                    return SUGAR.language.get('app_strings', appString);
                }
            } else {
                return appString;
            }
        },

        /**
         * Translate a chart string using current application language
         *
         * @param {string} appString string to translate
         * @return {string}
         */
        translateListStrings: function(appList) {
            if (SUGAR.App) {
                // Sidecar
                return SUGAR.App.lang.getAppListStrings(appList);
            } else if (app) {
                // BWC works
                return app.lang.getAppListStrings(appList);
            } else if (SUGAR.language) {
                // BWC not works?
                return SUGAR.language.get('app_list_strings', appList);
            } else {
                return appList;
            }
        },

        /**
         * Translate data from Report module to format used by Sucrose
         *
         * @param json the report data to transform
         * @param params chart display control parameters
         * @param config chart configuration settings
         * @return {Object} contains chart properties object and data array
         * @deprecated Use transformDataToD3(json, params, config) method instead.
         */
        translateDataToD3: function(json, params, config) {
            var msg = 'The SUGAR.charts.translateDataToD3(json, params, config) method is deprecated. ' +
                    'Please use SUGAR.charts.transformDataToD3(json, params, config) method instead.';
            this.sugarApp.logger.warn(msg);
            return this.transformDataToD3(json, params, config);
        },

        /**
         * Transform data from Report module to format used by Sucrose
         *
         * @param json the report data to transform
         * @param params chart display control parameters
         * @param config chart configuration settings
         * @return {Object} contains chart properties object and data array
         */
        transformDataToD3: function(json, params, config) {
            var data = [];
            var properties = {};
            var groups = [];
            var values = [];
            var value = 0;
            var props = json.properties[0] || {};
            var chartStrings = this.getChartStrings(config.chartType);
            var hasValues = json.values.filter(function(d) {
                    return Array.isArray(d.values) && d.values.length;
                }).length;
            var isGroupedBarType;
            var isDiscreteData = hasValues && Array.isArray(json.label) &&
                    json.label.length === json.values.length &&
                    json.values.reduce(function(a, c, i) {
                        return a && Array.isArray(c.values) && c.values.length === 1 &&
                            pickLabel(c.label) === pickLabel(json.label[i]);
                    }, true);

            function sumValues(values) {
                // 0 is default value if reducing an empty list
                return values.reduce(function(a, b) { return parseFloat(a) + parseFloat(b); }, 0);
            }

            function pickLabel(label) {
                var l = [].concat(label)[0];
                return !_.isEmpty(l) ? l : chartStrings.noLabel;
            }

            function pickValueLabel(d, i) {
                var l = d.valuelabels && d.valuelabels[i] ? d.valuelabels[i] : d.values[i];
                return !_.isEmpty(l) ? l : null;
            }

            if (hasValues) {
                switch (config.chartType) {

                    case 'barChart':
                        if ((params.reportView && isDiscreteData) || config.barType === 'stacked') {
                            params.dataType = config.barType = 'grouped';
                        }
                        isGroupedBarType = params.dataType === 'grouped';

                        data = isGroupedBarType && !isDiscreteData ?
                            // is grouped bar type on grouped data
                            json.label.map(function(d, i) {
                                return {
                                    key: pickLabel(d),
                                    type: 'bar',
                                    values: json.values.map(function(e, j) {
                                        var value = {
                                            series: i,
                                            label: pickValueLabel(e, i),
                                            x: j + 1,
                                            y: parseFloat(e.values[i]) || 0
                                        };
                                        return value;
                                    })
                                };
                            }) :
                            (isGroupedBarType && isDiscreteData) || (!isGroupedBarType && !isDiscreteData) ?
                                // is grouped bar type on discrete data OR basic bar type on grouped data
                                json.values.map(function(d, i) {
                                    return {
                                        key: d.values.length > 1 ? d.label : pickLabel(d.label),
                                        type: 'bar',
                                        values: json.values.map(function(e, j) {
                                            var value = {
                                                series: i,
                                                x: j + 1,
                                                y: i === j ? sumValues(e.values) : 0
                                            };
                                            //TODO: when collapsing grouped data into basic bar chart
                                            // we lose the label formatting (fix with localization)
                                            if (isDiscreteData) {
                                                value.label = value.y !== 0 ? pickValueLabel(e, 0) : '';
                                            }
                                            return value;
                                        })
                                    };
                                }) :
                                // is basic bar type on discrete data
                                [{
                                    key: params.module || props.base_module || props.seriesName,
                                    type: 'bar',
                                    values: json.values.map(function(e, j) {
                                        var value = {
                                            series: j,
                                            label: pickValueLabel(e, j),
                                            x: j + 1,
                                            y: sumValues(e.values)
                                        };
                                        return value;
                                    })
                                }];

                        break;

                    case 'lineChart':
                        data = json.values.map(function(d, i) {
                            return {
                                key: pickLabel(d.label),
                                values: isDiscreteData ?
                                    d.values.map(function(e, j) {
                                        return {x: i + 1, y: parseFloat(e)};
                                    }) :
                                    d.values.map(function(e, j) {
                                        return {x: j + 1, y: parseFloat(e)};
                                    })
                            };
                        });
                        break;

                    case 'pieChart':
                    case 'funnelChart':
                        data = json.values.map(function(d, i) {
                            var value = {
                                    series: i,
                                    x: 0,
                                    y: sumValues(d.values)
                                };
                            // some data provided to sugarCharts do not include
                            // valueLabels, like KB usefulness pie chart
                            if (d.valuelabels && d.valuelabels.length === 1) {
                                value.label = d.valuelabels[0];
                            } else {
                                value.label = sumValues(d.values);
                            }
                            var data = {
                                key: pickLabel(d.label),
                                values: []
                            };
                            data.values.push(value);
                            if (!_.isUndefined(d.color)) {
                                data.color = d.color;
                            }
                            if (!_.isUndefined(d.classes)) {
                                data.classes = d.classes;
                            }
                            return data;
                        });
                        if (config.chartType) {
                            data.reverse();
                        }
                        break;

                    case 'gaugeChart':
                        value = json.values.shift().gvalue;
                        var y0 = 0;

                        data = json.values.map(function(d, i) {
                            var values = {
                                key: pickLabel(d.label),
                                y: parseFloat(d.values[0]) + y0
                            };
                            y0 += parseFloat(d.values[0]);
                            return values;
                        });
                        break;
                }
            }

            //TODO: remove? this is legacy stuff.
            values = config.chartType === 'gaugeChart' ?
                [{group: 1, total: value}] :
                hasValues ?
                    json.values.map(function(d, i) {
                        return {
                            group: i + 1,
                            total: sumValues(d.values)
                        };
                    }) :
                    [];

            groups = config.chartType === 'lineChart' && json.label ?
                json.label.map(function(d, i) {
                    return {
                        group: i + 1,
                        label: pickLabel(d)
                    };
                }) :
                hasValues ?
                    json.values.map(function(d, i) {
                        return {
                            group: i + 1,
                            label: pickLabel(d.label)
                        };
                    }) :
                    [];

            properties = {
                title: props.title,
                xDataType: groups.length ? 'ordinal' : (props.xDataType || 'ordinal'),
                yDataType: props.yDataType || 'numeric',
                groups: groups,
                values: values
            };

            //TODO: move line chart flip to ChartDisplay.php?
            properties.groupName = (
                config.chartType === 'lineChart' ? props.seriesName : props.groupName
            ) || chartStrings.tooltip.group;
            properties.groupType = (
                config.chartType === 'lineChart' ? props.seriesType : props.groupType
            ) || 'string';

            properties.seriesName = (
                config.chartType === 'lineChart' ? props.groupName : props.seriesName
            ) || chartStrings.tooltip.key;
            properties.seriesType = (
                config.chartType === 'lineChart' ? props.groupType : props.seriesType
            ) || 'string';

            return {
                properties: properties,
                data: data
            };
        },

        /**
         * Is data returned from the server empty?
         *
         * @param {Object} data
         * @return {boolean}
         * @deprecated Use dataIsEmpty(data) method instead.
         */
        isDataEmpty: function(data) {
            if (data !== undefined && data !== 'No Data' && data !== '') {
                return true;
            } else {
                return false;
            }
        },

        /**
         * Is data returned from the server empty?
         *
         * @param {Object} data
         * @return {boolean}
         */
        dataIsEmpty: function(data) {
            if (
                _.isUndefined(data) ||
                _.isUndefined(data.values) ||
                !_.isArray(data.values) ||
                _.isEmpty(data.values)
            ) {
                return true;
            }
            return false;
        },

        /**
         * Resize graph on window resize
         *
         * @param {Function} chart Sucrose chart instance to render
         */
        trackWindowResize: function(chart) {
            // var resizer = chart.render ? chart.render : chart.update;
            $(window).on('resize.' + this.sfId, _.debounce(_.bind(function() {
                if (chart.render) {
                    chart.render();
                } else {
                    chart.update();
                }
            }, this), 300));
        },

        /**
         * Save the current chart to an image
         *
         * @param id chart id used to construct the chart container id
         * @param chart Sucrose chart instance to call
         * @param json report data to render
         * @param jsonfilename name of the data file to save image as
         * @param imageExt type of image to save
         * @param saveTo url of service to post the image to
         * @param complete the callback to reset chart instance after saving image
         */
        saveImageFile: function(id, chart, json, jsonfilename, imageExt, saveTo, complete) {
            var d3ChartId = id ? '#d3_' + id + '_print' : 'd3_c3090c86-2b12-a65e-967f-51b642ac6165_print';
            var canvasChartId = id ? 'canvas_' + id : 'canvas_c3090c86-2b12-a65e-967f-51b642ac6165';
            var svgChartId = id ? 'svg_' + id : 'svg_c3090c86-2b12-a65e-967f-51b642ac6165';
            var legendShowState = chart.legend.showAll();
            var textureFillState = true;

            var completeCallback = complete || _.bind(function() {
                chart.legend.showAll(legendShowState); //restore showAll state for web render
                // reenable texture fill for onclick feedback
                if (chart.textureFill) {
                    chart.textureFill(textureFillState);
                }
                // now that image is generated
                // it is ok to render the visible chart
                this.renderChart(id, chart, json);
            }, this);

            chart.legend.showAll(true); //set showAll legend property for images

            // temporarily turn off texture filling for onclick feedback
            if (chart.textureFill) {
                textureFillState = chart.textureFill()
                chart.textureFill(false);
            }

            d3sugar.select(d3ChartId + ' svg').remove();

            d3sugar.select(d3ChartId)
                .append('svg')
                .attr('class', 'sucrose sc-chart')
                .attr('id', svgChartId)
                .datum(json)
                .call(chart);

            d3sugar.select(d3ChartId).selectAll('.sc-axis line')
              .style('stroke', '#DDD')
              .style('stroke-width', 1)
              .style('stroke-opacity', 1);

            var parts = jsonfilename.split('/');
            var filename = parts[parts.length - 1].replace('.js', '.' + imageExt);
            var oCanvas = document.getElementById(canvasChartId);
            var d3Container = document.getElementById(svgChartId);
            var serializer = new XMLSerializer();
            var saveToUrl = saveTo || 'index.php?action=DynamicAction&DynamicAction=saveImage&module=Charts&to_pdf=1';

            if (!oCanvas) {
                return;
            }

            $.ajax({
                url: 'styleguide/assets/css/sucrose_print.css',
                dataType: 'text',
                success: function(css) {
                    var canvgOptions = {
                            ignoreMouse: true,
                            ignoreAnimation: false,
                            ignoreClear: true,
                            ignoreDimensions: true,
                            scaleWidth: 1440,
                            scaleHeight: 960,
                            renderCallback: function() {
                                var uri = oCanvas.toDataURL((imageExt === 'jpg' ? 'image/jpeg' : 'image/png'));
                                var ctx = oCanvas.getContext('2d');
                                $.post(saveToUrl, {imageStr: uri, filename: filename});
                                ctx.clearRect(0, 0, 1440, 960);
                                completeCallback();
                            }
                        };

                    setTimeout(function() {
                        var svg = serializer.serializeToString(d3Container);
                        var svgAttr = ' id="' + svgChartId + '"' +
                            ' xmlns:xlink="http://www.w3.org/1999/xlink" width="720"' +
                            ' height="480" viewBox="0 0 1440 960">';
                        var cssCdata = '<style type="text/css"><![CDATA[' + css.trim() + ']]></style>';
                        var d3Chart = svg.replace((' id="' + svgChartId + '">'), (svgAttr + cssCdata));

                        canvg(canvasChartId, d3Chart, canvgOptions);
                    }, 1000);
                }
            });
        },

        /**
         * Format a chart tooltip using a template for multiple data variables
         * @param eo rich event object with chart source element properties
         * @param properties chart data properties object
         * @param chart rendered Sucrose chart instance
         * @param template Sidecar handlebars template view
         * @param params chart display control parameters
         */
        formatTooltipSingle: function(eo, properties, chart, template, params) {
            var strings = chart.strings();
            var locale = chart.locality();
            var value = chart.getValue()(eo);
            var yIsCurrency = properties.yDataType === 'currency';
            var point = {};

            point.key = chart.getKey()(eo);
            point.label = yIsCurrency ?
                strings.tooltip.amount :
                strings.tooltip.count;
            point.value = eo.data && eo.data.label ?
                eo.data.label :
                yIsCurrency ?
                    this.sugarApp.currency.formatAmountLocale(value, locale.currency_id) :
                    sucrose.utility.numberFormat(value, locale.precision, false, locale);
            point.percent = sucrose.utility.numberFormatPercent(value, properties.total, locale);
            if (!params.allow_drillthru) {
                point.msg = strings.noDrillthru;
            }

            return template(point).replace(/(\r\n|\n|\r)/gm, '');
        },

        /**
         * Format a chart tooltip using a template for a single data variable
         * @param eo rich event object with chart source element properties
         * @param properties chart data properties object
         * @param chart rendered Sucrose chart instance
         * @param template Sidecar handlebars template view
         * @param params chart display control parameters
         */
        formatTooltipMultiple: function(eo, properties, chart, template, params) {
            var strings = chart.strings();
            var locale = chart.locality();
            var xIsDatetime = properties.xDataType === 'datetime';
            var yIsCurrency = properties.yDataType === 'currency';
            var seriesType = properties.seriesType || 'string';
            var label = eo.group ? eo.group.label : '';
            var key = eo.series ? eo.series.key : '';
            var point = {};

            // the event object group is set by event dispatcher if x is ordinal
            var index = eo.point ? eo.point.x : 0; // this is the ordinal index [0+1..n+1] or value index [0..n]
            // var value = yValueFormat(y, eo.seriesIndex, null, yIsCurrency, 2);
            // we can't use yValueFormat because it needs SI units for axis
            // for tooltip, we want the full value
            var value = eo.point ? eo.point.y : 0;

            point.valueName = yIsCurrency ?
                strings.tooltip.amount :
                strings.tooltip.count;
            point.valueLabel = eo.point && eo.point.label ?
                eo.point.label :
                yIsCurrency ?
                    this.sugarApp.currency.formatAmountLocale(value, locale.currency_id) :
                    sucrose.utility.numberFormat(value, locale.precision, false, locale);

            point.groupName = properties.groupName;
            //TODO: shouldn't %x be user date pref?
            point.groupLabel = chart.xValueFormat()(index, eo.pointIndex, label, xIsDatetime, '%x');

            if (!_.isUndefined(key) && key !== label) {
                point.seriesName = properties.seriesName;
                point.seriesLabel = seriesType === 'string' ?
                    key :
                    seriesType === 'numeric' ?
                        sucrose.utility.numberFormat(key, locale.precision, false, locale) :
                        seriesType === 'currency' ?
                            this.sugarApp.currency.formatAmountLocale(key, locale.currency_id) :
                            key;
            }

            if (eo.group && sucrose.utility.isNumeric(eo.group._height)) {
                if (value !== eo.group._height) {
                    point.percent = sucrose.utility.numberFormatPercent(value, eo.group._height, locale);
                }
            }

            if (!params.allow_drillthru) {
                point.msg = strings.noDrillthru;
            }

            return template(point).replace(/(\r\n|\n|\r)/gm, '');
        },

        /**
         * Build a set of translated strings for intra-chart rendering
         * @param type the chart type
         */
        getChartStrings: function(type) {
            var noLabelStr = this.translateString('LBL_CHART_UNDEFINED');
            return {
                legend: {
                    close: this.translateString('LBL_CHART_LEGEND_CLOSE'),
                    open: this.translateString('LBL_CHART_LEGEND_OPEN'),
                    noLabel: noLabelStr
                },
                tooltip: {
                    amount: this.translateString('LBL_CHART_AMOUNT'),
                    count: this.translateString('LBL_CHART_COUNT'),
                    date: this.translateString('LBL_CHART_DATE'),
                    group: this.translateString('LBL_CHART_GROUP'),
                    key: this.translateString('LBL_CHART_KEY'),
                    percent: this.translateString('LBL_CHART_PERCENT')
                },
                noData: this.translateString('LBL_CHART_NO_DATA'),
                noLabel: noLabelStr,
                noDrillthru: this.translateString('LBL_CHART_NO_DRILLTHRU', 'Reports')
            };
        },

        /**
         * Construct a locale settings object in a format consumable by D3's locale() method
         *
         * @param {Object} pref (optional)  The associative array of preferences from which to build locale
         * @return {Object}  An associate array of locale settings
         */
        getLocale: function(pref) {
            var preferences = pref || this.getUserPreferences();

            return {
                'decimal': preferences.decimal_separator,
                'thousands': preferences.number_grouping_separator,
                'grouping': [3],
                'currency': [preferences.currency_symbol, ''],
                'currency_id': preferences.currency_id,
                'dateTime': '%a %b %e %X %Y',
                'date': this._dateFormat(preferences.datepref),
                'time': this._timeFormat(preferences.timepref),
                'periods': this._timePeriods(preferences.timepref),
                'days': this._dateStringArray('dom_cal_day_long'),
                'shortDays': this._dateStringArray('dom_cal_day_short'),
                'months': this._dateStringArray('dom_cal_month_long'),
                'shortMonths': this._dateStringArray('dom_cal_month_short'),
                'precision': preferences.decimal_precision
            };
        },

        /**
         * Construct a locale settings object for the current user
         *
         * @return {Object}  An associate array of locale settings
         */
        getUserLocale: function() {
            return this.getLocale(this.getUserPreferences());
        },

        /**
         * Retrieve the user preferences from which to build a locale
         *
         * @return {Object}  An associative array object of preferences
         * @private
         */
        getUserPreferences: function() {
            return this.sugarApp.user.get('preferences') || {};
        },

        /**
         * Get a preference setting from the currently loaded user object
         *
         * @param {string} pref  The name of the preference to retrieve
         * @return {string|Array|Object}
         */
        userPreference: function(pref) {
            return this.sugarApp.user.getPreference(pref) || pref;
        },

        /**
         * Construct a system locale settings object for the system
         *
         * @return {Object}  An associate array of locale settings
         */
        getSystemLocale: function() {
            return this.getLocale(this._getSystemPreferences());
        },

        /**
         * Retrieve the system preferences from which to build a locale
         *
         * @return {Object}  An associative array object of preferences
         * @private
         */
        _getSystemPreferences: function() {
            var config = this.sugarApp.config;
            var currency = this.sugarApp.currency;

            return {
                decimal_separator: config.defaultDecimalSeparator,
                number_grouping_separator: config.defaultNumberGroupingSeparator,
                currency_symbol: currency.getCurrencySymbol(currency.getBaseCurrencyId()),
                // TODO: datef and timef in config.php don't seem to be available in js
                datepref: 'm/d/Y',
                timepref: 'H:i',
                decimal_precision: config.defaultCurrencySignificantDigits
            };
        },

        /**
         * Given a user date format preference in a form like 'mm/dd/yyyy'
         * returns a D3 formatting specifier like '%b/%d/%Y'
         *
         * @param {string} pref  A string encoding in the form 'm/d/y'
         * which can contain one or more upper or lower case characters
         * in any order with optional separators or spaces
         * @return {string}  A date format pattern string in the form of '%b %-d, %Y'
         * @private
         */
        _dateFormat: function(pref) {
            if (!pref) {
                return '%b %-d, %Y';
            }
            return pref
                .replace(/([mMyYdD]+)/ig, '%$1');
        },

        /**
         * Given a Sugar user time format preference
         * returns a D3 time formatting specifier
         *
         * @param {string} pref  A string encoding in the form 'h:ia'
         * where 'h' indicates 12 hour clock and 'H' indicates 24 hour clock
         * and 'i' with a colon as separator
         * @return {string}  A time format pattern string in the form of '%-I:%M'
         * @private
         */
        _timeFormat: function(pref) {
            if (!pref) {
                return '%-I:%M:%S';
            }
            return pref
                .replace('h', 'I')
                    .replace('i', 'M')
                        .replace(/[aA\s]+/, '')
                            .replace(/([HIM]+)/ig, '%$1');
        },

        /**
         * Given a Sugar user time format preference
         * returns a D3 time period formatting specifier
         *
         * @param {string} pref  A string encoding in the form 'h:ia'
         * where the final character is expected to be 'a', 'A' or empty
         * with an optional leading space,
         * @return {Array}  A nominal array of time period options in the form ['am', 'pm']
         * @private
         */
        _timePeriods: function(pref) {
            if (!pref) {
                return ['AM', 'PM'];
            }
            var period = pref.indexOf(' A') !== -1 ?
                [' AM', ' PM'] :
                pref.indexOf('A') !== -1 ?
                    ['AM', 'PM'] :
                    pref.indexOf(' a') !== -1 ?
                        [' am', ' pm'] :
                        pref.indexOf('a') !== -1 ?
                            ['am', 'pm'] :
                            ['', ''];
            return period;
        },

        /**
         * Given the name of a Sugar language pack set of date strings
         * returns an array of date name strings for D3
         *
         * @param {string} listLabel  The name of a list that references an
         * object as structural array in the form {0: '', 1: 'Monday', ...}
         * with integer keys for each date string and a zero padding element
         * @return {Array}  A nominal array of date name strings in the form ['Monday', ...]
         * with the zero padding element removed
         * @private
         */
        _dateStringArray: function(listLabel) {
            return _.filter(_.values(this.translateListStrings(listLabel)));
        },

        /**
         * Determine the correct tooltip template for a given chart type
         * @param type the chart type
         */
        _getTooltipTemplate: function(type) {
            var template = type === 'barChart' || type === 'lineChart' ?
                'multipletooltiptemplate' :
                'singletooltiptemplate';
            return this.sugarApp.template.getField('chart', template);
        }
    };
})(jQuery);
