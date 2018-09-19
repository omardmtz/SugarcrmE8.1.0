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

// $Id: customSugarCharts.js 2010-12-01 23:11:36Z lhuynh $

/**
 * This chart engine is now deprecated. Use the sucrose chart engine instead.
 * @deprecated This file will removed in a future release.
 */
function loadSugarChart (chartId, jsonFilename, css, chartConfig, params, callback) {
    (app || SUGAR.App).logger.warn('The Jit chart engine is deprecated.');

    this.chartObject = "";
    if(document.getElementById(chartId) == null) {
        return false;
    }

    var labelType = 'Native',
        useGradients = false,
        animate = false,
        that = this,
        /**
         * the main container to render chart
         */
        contentEl = 'content',
        /**
         * with of one column to render bars
         */
        minColumnWidth = 40;

    params = params ? params : {};

    contentEl = params.contentEl || contentEl;
    minColumnWidth = params.minColumnWidth || minColumnWidth;

			switch(chartConfig["chartType"]) {
                case "d3-barChart":
                    SUGAR.charts.get(jsonFilename, params, function(data) {

                        if(SUGAR.charts.isDataEmpty(data)){
                            var json = data;

                            var marginBottom = (chartConfig["orientation"] == 'vertical' && data.values.length > 8) ? 20*4 : 20;

                            var paretoChart = nv.models.paretoChart()
                                .margin({top: 0, right: 10, bottom: 20, left: 30})
                                .showTitle(false)
                                .tooltips(true)
                                .tooltipLine(function(key, x, y, e, graph) {
                                    // Format the value using currency class and user settings
                                    var val = App.currency.formatAmountLocale(e.point.y)
                                    return '<p>' + key +': <b>' + val + '</b></p>'
                                })
                                .tooltipBar(function(key, x, y, e, graph) {
                                    // Format the value using currency class and user settings
                                    var val = App.currency.formatAmountLocale(e.value)
                                    return '<p>' + SUGAR.App.lang.get('LBL_SALES_STAGE', 'Forecasts') + ': <b>' + key + '</b></p>' +
                                        '<p>' + SUGAR.App.lang.get('LBL_AMOUNT', 'Forecasts') + ': <b>' + val + '</b></p>' +
                                        '<p>' + SUGAR.App.lang.get('LBL_PERCENT', 'Forecasts') + ': <b>' + x + '%</b></p>'
                                })
                                .showControls(false)
                                .colorData( 'default' )
                                .colorFill( 'default' )
                                .stacked(!params.display_manager)
                                .id(chartId)
                                .strings({
                                    legend: {
                                        close: SUGAR.App.lang.get('LBL_CHART_LEGEND_CLOSE'),
                                        open: SUGAR.App.lang.get('LBL_CHART_LEGEND_OPEN')
                                    },
                                    noData: SUGAR.App.lang.get('LBL_CHART_NO_DATA')
                                });

                            // get chartId from params or use the default for sugar
                            var chartId = params.chartId || 'db620e51-8350-c596-06d1-4f866bfcfd5b';

                            d3.select('#' + chartId + ' svg').remove();

                            // After the .call(paretoChart) line, we are selecting the text elements for the Y-Axis
                            // only so we can custom format the Y-Axis values
                            d3.select('#' + chartId)
                                .append('svg')
                                .datum( SUGAR.charts.translateDataToD3(json,params) )
                                .transition().duration(500)
                                .call(paretoChart)
                                .selectAll('.nv-y.nv-axis .tick')
                                .select('text')
                                .text(function(d) {
                                    return App.user.get('preferences').currency_symbol + d3.format(',.2s')(d);
                                });

                            nv.utils.windowResize(paretoChart.update);

                            that.chartObject = paretoChart;

                            SUGAR.charts.setChartObject(paretoChart);
                        }
                        SUGAR.charts.callback(callback);
                    });
                    break;
			case "barChart":
                SUGAR.charts.get(jsonFilename, params, function(data) {
                    if(SUGAR.charts.isDataEmpty(data)){
                        var json = data;
                        var properties = $jit.util.splat(data.properties)[0];
                        var marginBottom = (chartConfig["orientation"] == 'vertical' && data.values.length > 8) ? 20*4 : 20;

                        // Bug #49732 : Bars in charts overlapping
                        // if to many data to display fix canvas width and set up width to container to allow overflow
                        if ( chartConfig["orientation"] == 'vertical' )
                        {
                            function fixChartContainer(event, itemsCount)
                            {
                                var chartCanvas = $("div.chartCanvas");
                                var chartContainer = $("div.chartContainer");
                                var region = $('#' + contentEl);
                                if ( chartContainer.length > 0 && chartCanvas.length > 0 )
                                {
                                    if ( region && region.width )
                                    {
                                        // one bar needs about minColumnWidth px to correct display data and labels
                                        var realWidth = itemsCount * parseInt(minColumnWidth, 10);
                                        chartContainer = chartContainer.first();
                                        chartCanvas = chartCanvas.first();
                                        if ( realWidth > region.width )
                                        {
                                            chartContainer.width(region.width() + 'px');
                                            chartCanvas.width(realWidth + 'px');
                                        }
                                        else
                                        {
                                            chartContainer.width(region.width() + 'px');
                                            chartCanvas.width(region.width() + 'px');
                                        }
                                    }
                                }
                                if (!event)
                                {
                                    $(window).resize(function(length) {
                                        return function(event) {
                                            fixChartContainer(event, length);
                                        }
                                    }(json.values.length));
                                }
                            }
                            fixChartContainer(null, json.values.length);
                        }

                        //init BarChart
                        var barChart = new $jit.BarChart({
                            //id of the visualization container
                            injectInto: chartId,
                            //whether to add animations
                            animate: animate,
                            nodeCount: data.values.length,
                            renderBackground: chartConfig['imageExportType'] == "jpg" ? true: false,
                            dataPointSize: chartConfig["dataPointSize"],
                            backgroundColor: 'rgb(255,255,255)',
                            colorStop1: 'rgba(255,255,255,.8)',
                            colorStop2: 'rgba(255,255,255,0)',
                            shadow: {
                                enable: false,
                                size: 2
                            },
                            //horizontal or vertical barcharts
                            orientation: chartConfig["orientation"],
                            hoveredColor: false,
                            Title: {
                                text: properties['title'],
                                size: 16,
                                color: '#444444',
                                offset: 20
                            },
                            Subtitle: {
                                text: properties['subtitle'],
                                size: 11,
                                color: css["color"],
                                offset: 20
                            },
                            Ticks: {
                                enable: true,
                                color: css["gridLineColor"]
                            },
                            //bars separation
                            barsOffset: (chartConfig["orientation"] == "vertical") ? 20 : 20,
                            //visualization offset
                            Margin: {
                                top:20,
                                left: 30,
                                right: 20,
                                bottom: marginBottom
                            },
                            ScrollNote: {
                                text: (chartConfig["scroll"] && $jit.util.isTouchScreen()) ? "Use two fingers to scroll" : "",
                                size: 12
                            },
                            Events: {
                                enable: true,
                                onClick: function(node) {
                                    if(!node || $jit.util.isTouchScreen()) return;
                                    if(node.link == 'undefined' || node.link == undefined || node.link == '') return;
                                    window.location.href=node.link;
                                }
                            },
                            //labels offset position
                            labelOffset: 5,
                            //bars style
                            type: useGradients? chartConfig["barType"]+':gradient' : chartConfig["barType"],
                            //whether to show the aggregation of the values
                            showAggregates: (chartConfig["showAggregates"] != undefined) ? chartConfig["showAggregates"] : true,
                            showNodeLabels: (chartConfig["showNodeLabels"] != undefined) ? chartConfig["showNodeLabels"] : true,
                            segmentStacked: (chartConfig["segmentStacked"] != undefined) ? chartConfig["segmentStacked"] : false,
                            //whether to show the labels for the bars
                            showLabels:true,
                            //labels style
                            Label: {
                                type: labelType, //Native or HTML
                                size: 12,
                                family: css["font-family"],
                                color: css["color"],
                                colorAlt: "#ffffff"
                            },
                            //add tooltips
                            Tips: {
                                enable: true,
                                onShow: function(tip, elem) {

                                    if(elem.type == 'marker') {
                                        tip.innerHTML = '<b>' + elem.name + '</b>: ' + elem.valuelabel ;
                                    } else {
                                        if(elem.link != 'undefined' && elem.link != undefined && elem.link != '') {
                                            drillDown = ($jit.util.isTouchScreen()) ? "<br><a href='"+ elem.link +"'>"+SUGAR.language.get('app_strings','LBL_CLICK_TO_DRILLDOWN')+"</a>" : "<br>"+SUGAR.language.get('app_strings','LBL_CLICK_TO_DRILLDOWN');
                                        } else {
                                            drillDown = "";
                                        }

                                        if(elem.valuelabel != 'undefined' && elem.valuelabel != undefined && elem.valuelabel != '') {
                                            value = "elem.valuelabel";
                                        } else {
                                            value = "elem.value";
                                        }

                                        if(properties.label_name != undefined && properties.label_name != "") {
                                            eval("tip.innerHTML = properties.label_name + ': <b>' + elem."+chartConfig["tip"]+" + '</b><br> '+properties.value_name+': <b>' + "+value+" + '</b>' + drillDown");
                                        } else {
                                            eval("tip.innerHTML = '<b>' + elem."+chartConfig["tip"]+" + '</b>: ' + "+value+" + ' - ' + elem.percentage + '%' + drillDown");
                                        }
                                    }
                                }
                            }
                        });
                        //load JSON data.
                        barChart.loadJSON(data);

                        var list = SUGAR.charts.generateLegend(barChart, chartId);

                        //save canvas to image for pdf consumption
                        $jit.util.saveImageTest(chartId,jsonFilename,chartConfig["imageExportType"],chartConfig['saveImageTo']);

                        SUGAR.charts.trackWindowResize(barChart, chartId, data);
                        barChart.json = json;
                        that.chartObject = barChart;

                        SUGAR.charts.setChartObject(barChart);

                    }
                    SUGAR.charts.callback(callback);
                });

				break;

			case "lineChart":
                SUGAR.charts.get(jsonFilename, params, function(data) {
                    if(SUGAR.charts.isDataEmpty(data)){
                        var properties = $jit.util.splat(data.properties)[0];
                        //init Linecahrt
                        var lineChart = new $jit.LineChart({
                            //id of the visualization container
                            injectInto: chartId,
                            //whether to add animations
                            animate: animate,
                            renderBackground: chartConfig['imageExportType'] == "jpg" ? true: false,
                            backgroundColor: 'rgb(255,255,255)',
                            colorStop1: 'rgba(255,255,255,.8)',
                            colorStop2: 'rgba(255,255,255,0)',
                            selectOnHover: false,
                            Title: {
                                text: properties['title'],
                                size: 16,
                                color: '#444444',
                                offset: 20
                            },
                            Subtitle: {
                                text: properties['subtitle'],
                                size: 11,
                                color: css["color"],
                                offset: 20
                            },
                            Ticks: {
                                enable: true,
                                color: css["gridLineColor"]
                            },
                            //visualization offset
                            Margin: {
                                top:20,
                                left: 40,
                                right: 40,
                                bottom: 20
                            },
                            Events: {
                                enable: true,
                                onClick: function(node) {
                                    if(!node || $jit.util.isTouchScreen()) return;
                                    if(node.link == 'undefined' || node.link == undefined || node.link == '') return;
                                    window.location.href=node.link;
                                }
                            },
                            //labels offset position
                            labelOffset: 5,
                            //bars style
                            type: useGradients? chartConfig["lineType"]+':gradient' : chartConfig["lineType"],
                            //whether to show the aggregation of the values
                            showAggregates:true,
                            //whether to show the labels for the bars
                            showLabels:true,
                            //labels style
                            Label: {
                                type: labelType, //Native or HTML
                                size: 12,
                                family: css["font-family"],
                                color: css["color"],
                                colorAlt: "#ffffff"
                            },
                            //add tooltips
                            Tips: {
                                enable: true,
                                onShow: function(tip, elem) {
                                    if(elem.link != 'undefined' && elem.link != undefined && elem.link != '') {
                                        drillDown = ($jit.util.isTouchScreen()) ? "<br><a href='"+ elem.link +"'>"+SUGAR.language.get('app_strings','LBL_CLICK_TO_DRILLDOWN')+"</a>" : "<br>"+SUGAR.language.get('app_strings','LBL_CLICK_TO_DRILLDOWN');
                                    } else {
                                        drillDown = "";
                                    }

                                    if(elem.valuelabel != 'undefined' && elem.valuelabel != undefined && elem.valuelabel != '') {
                                        var value = "elem.valuelabel";
                                    } else {
                                        var value = "elem.value";
                                    }

                                    if(elem.collision) {
                                        eval("var name = elem."+chartConfig["tip"]+";");
                                        var content = '<table>';

                                        for(var i=0; i<name.length; i++) {
                                            content += '<tr><td><b>' + name[i] + '</b>:</td><td> ' + elem.value[i] + ' - ' + elem.percentage[i] + '%' + '</td></tr>';
                                        }
                                        content += '</table>';
                                        tip.innerHTML = content;
                                    } else {
                                        eval("tip.innerHTML = '<b>' + elem."+chartConfig["tip"]+" + '</b>: ' + "+value+" + ' - ' + elem.percentage + '%' + drillDown");
                                    }
                                }
                            }
                        });
                        //load JSON data.
                        lineChart.loadJSON(data);
                        //end

                        //dynamically add legend to list
                        var list = SUGAR.charts.generateLegend(lineChart, chartId);

                        //save canvas to image for pdf consumption
                        $jit.util.saveImageTest(chartId,jsonFilename,chartConfig["imageExportType"]);

                        SUGAR.charts.trackWindowResize(lineChart, chartId, data);
                        that.chartObject = lineChart;
                    }
                    SUGAR.charts.callback(callback);
                });

                break;

			case "pieChart":
                SUGAR.charts.get(jsonFilename, params, function(data) {
                    if(SUGAR.charts.isDataEmpty(data)){
                        var properties = $jit.util.splat(data.properties)[0];

                        //init BarChart
                        var pieChart = new $jit.PieChart({
                            //id of the visualization container
                            injectInto: chartId,
                            //whether to add animations
                            animate: animate,
                            renderBackground: chartConfig['imageExportType'] == "jpg" ? true: false,
                            backgroundColor: 'rgb(255,255,255)',
                            colorStop1: 'rgba(255,255,255,.8)',
                            colorStop2: 'rgba(255,255,255,0)',
                            labelType: properties['labels'],
                            hoveredColor: false,
                            //offsets
                            offset: 50,
                            sliceOffset: 0,
                            labelOffset: 30,
                            //slice style
                            type: useGradients? chartConfig["pieType"]+':gradient' : chartConfig["pieType"],
                            //whether to show the labels for the slices
                            showLabels:true,
                            Title: {
                                text: properties['title'],
                                size: 16,
                                color: '#444444',
                                offset: 20
                            },
                            Subtitle: {
                                text: properties['subtitle'],
                                size: 11,
                                color: css["color"],
                                offset: 20
                            },
                            Margin: {
                                top:20,
                                left: 20,
                                right: 20,
                                bottom: 20
                            },
                            Events: {
                                enable: true,
                                onClick: function(node) {
                                    if(!node || $jit.util.isTouchScreen()) return;
                                    if(node.link == 'undefined' || node.link == undefined || node.link == '') return;
                                    window.location.href=node.link;
                                }
                            },
                            //label styling
                            Label: {
                                type: labelType, //Native or HTML
                                size: 12,
                                family: css["font-family"],
                                color: css["color"]
                            },
                            //enable tips
                            Tips: {
                                enable: true,
                                onShow: function(tip, elem) {
                                    if(elem.link != 'undefined' && elem.link != undefined && elem.link != '') {
                                        drillDown = ($jit.util.isTouchScreen()) ? "<br><a href='"+ elem.link +"'>"+SUGAR.language.get('app_strings','LBL_CLICK_TO_DRILLDOWN')+"</a>" : "<br>"+SUGAR.language.get('app_strings','LBL_CLICK_TO_DRILLDOWN');
                                    } else {
                                        drillDown = "";
                                    }

                                    if(elem.valuelabel != 'undefined' && elem.valuelabel != undefined && elem.valuelabel != '') {
                                        value = "elem.valuelabel";
                                    } else {
                                        value = "elem.value";
                                    }
                                    eval("tip.innerHTML = '<b>' + elem.label + '</b>: ' + "+ value +" + ' - ' + elem.percentage + '%' + drillDown");
                                }
                            }
                        });
                        //load JSON data.
                        pieChart.loadJSON(data);
                        //end
                        //dynamically add legend to list
                        var list = SUGAR.charts.generateLegend(pieChart, chartId);


                        //save canvas to image for pdf consumption
                        $jit.util.saveImageTest(chartId,jsonFilename,chartConfig["imageExportType"]);

                        SUGAR.charts.trackWindowResize(pieChart, chartId, data);
                        that.chartObject = pieChart;
                    }
                    SUGAR.charts.callback(callback);
                });

				break;

			case "funnelChart":
                SUGAR.charts.get(jsonFilename, params, function(data) {
                    if(SUGAR.charts.isDataEmpty(data)){
                        var properties = $jit.util.splat(data.properties)[0];

                        //init Funnel Chart
                        var funnelChart = new $jit.FunnelChart({
                            //id of the visualization container
                            injectInto: chartId,
                            //whether to add animations
                            animate: animate,
                            renderBackground: chartConfig['imageExportType'] == "jpg" ? true: false,
                            backgroundColor: 'rgb(255,255,255)',
                            colorStop1: 'rgba(255,255,255,.8)',
                            colorStop2: 'rgba(255,255,255,0)',
                            //orientation setting should not be changed
                            orientation: "vertical",
                            hoveredColor: false,
                            Title: {
                                text: properties['title'],
                                size: 16,
                                color: '#444444',
                                offset: 20
                            },
                            Subtitle: {
                                text: properties['subtitle'],
                                size: 11,
                                color: css["color"],
                                offset: 20
                            },
                            //segment separation
                            segmentOffset: 20,
                            //visualization offset
                            Margin: {
                                top:20,
                                left: 20,
                                right: 20,
                                bottom: 20
                            },
                            Events: {
                                enable: true,
                                onClick: function(node) {
                                    if(!node || $jit.util.isTouchScreen()) return;
                                    if(node.link == 'undefined' || node.link == undefined || node.link == '') return;
                                    window.location.href=node.link;
                                }
                            },
                            //labels offset position
                            labelOffset: 10,
                            //bars style
                            type: useGradients? chartConfig["funnelType"]+':gradient' : chartConfig["funnelType"],
                            //whether to show the aggregation of the values
                            showAggregates:true,
                            //whether to show the labels for the bars
                            showLabels:true,
                            //labels style
                            Label: {
                                type: labelType, //Native or HTML
                                size: 12,
                                family: css["font-family"],
                                color: css["color"],
                                colorAlt: "#ffffff"
                            },
                            //add tooltips
                            Tips: {
                                enable: true,
                                onShow: function(tip, elem) {
                                    if(elem.link != 'undefined' && elem.link != undefined && elem.link != '') {
                                        drillDown = ($jit.util.isTouchScreen()) ? "<br><a href='"+ elem.link +"'>"+SUGAR.language.get('app_strings','LBL_CLICK_TO_DRILLDOWN')+"</a>" : "<br>"+SUGAR.language.get('app_strings','LBL_CLICK_TO_DRILLDOWN');
                                    } else {
                                        drillDown = "";
                                    }

                                    if(elem.valuelabel != 'undefined' && elem.valuelabel != undefined && elem.valuelabel != '') {
                                        value = "elem.valuelabel";
                                    } else {
                                        value = "elem.value";
                                    }
                                    eval("tip.innerHTML = '<b>' + elem."+chartConfig["tip"]+" + '</b>: ' + "+value+"  + ' - ' + elem.percentage + '%' +  drillDown");
                                }
                            }
                        });
                        //load JSON data.
                        funnelChart.loadJSON(data);
                        //end

                        //dynamically add legend to list
                        var list = SUGAR.charts.generateLegend(funnelChart, chartId);

                        //save canvas to image for pdf consumption
                        $jit.util.saveImageTest(chartId,jsonFilename,chartConfig["imageExportType"]);

                        SUGAR.charts.trackWindowResize(funnelChart, chartId, data);
                        that.chartObject = funnelChart;
                    }
                    SUGAR.charts.callback(callback);
                });

				break;

			case "gaugeChart":
                SUGAR.charts.get(jsonFilename, params, function(data) {
                    if(SUGAR.charts.isDataEmpty(data)){
                        var properties = $jit.util.splat(data.properties)[0];

                        //init Gauge Chart
                        var gaugeChart = new $jit.GaugeChart({
                            //id of the visualization container
                            injectInto: chartId,
                            //whether to add animations
                            animate: animate,
                            renderBackground: chartConfig['imageExportType'] == "jpg" ? true: false,
                            backgroundColor: 'rgb(255,255,255)',
                            colorStop1: 'rgba(255,255,255,.8)',
                            colorStop2: 'rgba(255,255,255,0)',
                            labelType: properties['labels'],
                            hoveredColor: false,
                            Title: {
                                text: properties['title'],
                                size: 16,
                                color: '#444444',
                                offset: 20
                            },
                            Subtitle: {
                                text: properties['subtitle'],
                                size: 11,
                                color: css["color"],
                                offset: 5
                            },
                            //offsets
                            offset: 20,
                            gaugeStyle: {
                                backgroundColor: '#aaaaaa',
                                borderColor: '#999999',
                                needleColor: 'rgba(255,0,0,.8)',
                                borderSize: 4,
                                positionFontSize: 24,
                                positionOffset: 2
                            },
                            //slice style
                            type: useGradients? chartConfig["gaugeType"]+':gradient' : chartConfig["gaugeType"],
                            //whether to show the labels for the slices
                            showLabels:true,
                            Events: {
                                enable: true,
                                onClick: function(node) {
                                    if(!node || $jit.util.isTouchScreen()) return;
                                    if(node.link == 'undefined' || node.link == undefined || node.link == '') return;
                                    window.location.href=node.link;
                                }
                            },
                            //label styling
                            Label: {
                                type: labelType, //Native or HTML
                                size: 12,
                                family: css["font-family"],
                                color: css["color"]
                            },
                            //enable tips
                            Tips: {
                                enable: true,
                                onShow: function(tip, elem) {
                                    if(elem.link != 'undefined' && elem.link != undefined && elem.link != '') {
                                        drillDown = ($jit.util.isTouchScreen()) ? "<br><a href='"+ elem.link +"'>Click to drilldown</a>" : "<br>Click to drilldown";
                                    } else {
                                        drillDown = "";
                                    }
                                    if(elem.valuelabel != 'undefined' && elem.valuelabel != undefined && elem.valuelabel != '') {
                                        value = "elem.valuelabel";
                                    } else {
                                        value = "elem.value";
                                    }
                                    eval("tip.innerHTML = '<b>' + elem.label + '</b>: ' + "+ value +" + drillDown");
                                }
                            }
                        });
                        //load JSON data.
                        gaugeChart.loadJSON(data);

                        var list = SUGAR.charts.generateLegend(gaugeChart, chartId);

                        //save canvas to image for pdf consumption
                        $jit.util.saveImageTest(chartId,jsonFilename,chartConfig["imageExportType"]);

                        SUGAR.charts.trackWindowResize(gaugeChart, chartId, data);
                        that.chartObject = gaugeChart;
                    }
                    SUGAR.charts.callback(callback);
                });

				break;

			}
		}

function updateChart(jsonFilename, chart, params) {
    params = params ? params : {};
    SUGAR.charts.get(jsonFilename, params, function(data) {
        if(SUGAR.charts.isDataEmpty(data)){
            chart.busy = false;
            chart.updateJSON(data);
        }
    });
}

function swapChart(chartId,jsonFilename,css,chartConfig){
    $("#"+chartId).empty();
    $("#legend"+chartId).empty();
    $("#tiptip_holder").empty();
    var chart = new loadSugarChart(chartId,jsonFilename,css,chartConfig);
    return chart;

}

/**
 * As you touch the code above, migrate the code to use the pattern below.
 */
(function($) {

    if (typeof SUGAR == "undefined" || !SUGAR) {
        SUGAR = {};
    }
    SUGAR.charts = {

        chart : null,
        /**
         * Execute callback function if specified
         *
         * @param callback
         */
        callback: function(callback) {
            if (callback) {
                // if the call back is fired, include the chart as the only param
                callback(this.chart);
            }
        },

        setChartObject : function(chart) {
            this.chart = chart;
        },

        /**
         * Handle the Legend Generation
         *
         * @param chart
         * @param chartId
         * @return {*}
         */
        generateLegend: function(chart, chartId) {
            var list = $jit.id('legend'+chartId);
            var legend = chart.getLegend();
            if(typeof legend['wmlegend'] != "undefined" && legend['wmlegend']['name'].length > 0) {
                var table = "<div class='col'>";
            } else {
                var table = "<div class='row'>";
            }
            for(var i=0;i<legend['name'].length;i++) {
                if(legend["name"][i] != undefined) {
                    table += "<div class='legendGroup'>";
                    table += '<div class=\'query-color\' style=\'background-color:'
                        + legend["color"][i] +'\'></div>';
                    table += '<div class=\'label\'>';
                    table += legend["name"][i];
                    table += '</div>';
                    table += "</div>";
                }
            }

            table += "</div>";


            if(typeof legend['wmlegend'] != "undefined" && legend['wmlegend']['name'].length > 0) {

                table += "<div class='col2'>";
                for(var i=0;i<legend['wmlegend']['name'].length;i++) {
                    table += "<div class='legendGroup'>";
                    table += "<div class='waterMark  "+ legend["wmlegend"]['type'][i] +"' style='background-color: "+ legend["wmlegend"]['color'][i] +";'></div>";
                    table += "<div class='label'>"+ legend["wmlegend"]['name'][i] +"</div>";
                    table += "</div>";
                }
                table += "</div>";

            }

            list.innerHTML = table;

            //adjust legend width to chart width
            jQuery('#legend'+chartId).ready(function() {
                var chartWidth = jQuery('#'+chartId).width();
                chartWidth = chartWidth - 20;
                $('#legend'+chartId).width(chartWidth);
                var legendGroupWidth = new Array();
                if(typeof legend['wmlegend'] != "undefined" && legend['wmlegend']['name'].length > 0) {
                    var sel = ".col .legendGroup";
                } else {
                    var sel = ".row .legendGroup";
                }
                $(sel).each(function(index) {
                    legendGroupWidth[index] = $(this).width();
                });
                var largest = Math.max.apply(Math, legendGroupWidth);
                $(sel).width(largest+2);
            });



            return list;
        },

        /**
         * Calls the server to retrieve chart data
         *
         * @param url - target url
         * @param param - object of parameters to pass to the server
         * @param success - callback function to be executed after a successful call
         */
        get: function(url, params, success) {
            var data = {
                r: new Date().getTime()
            };
            $.extend(data, params);

            $.ajax({
                url: url,
                data: data,
                dataType: 'json',
                async: false,
                success: success
            });
        },

        /**
         * Is data returned from the server empty?
         *
         * @param data
         * @return {Boolean}
         */
        isDataEmpty: function(data) {
            if (data !== undefined && data !== "No Data" && data !== "") {
                return true;
            } else {
                return false;
            }
        },

        /**
         * Resize graph on window resize
         *
         * @param chart
         * @param chartId
         * @param json
         */
        trackWindowResize: function(chart, chartId, json) {
            var timeout,
                delay = 500,
                origWindowWidth = document.documentElement.scrollWidth,
                container = document.getElementById(chartId),
                widget = document.getElementById(chartId + "-canvaswidget");

            // refresh graph on window resize
            $(window).resize(function() {
                if (timeout) {
                    clearTimeout(timeout);
                }

                timeout = setTimeout(function() {
                    var newWindowWidth = document.documentElement.scrollWidth;

                    // if window width has changed during resize
                    if (newWindowWidth != origWindowWidth) {
                        // hide widget in order to let it's container have
                        // width corresponding to current window size,
                        // not it's contents
                        widget.style.display = "none";

                        // add one more timeout in order to let all widgets
                        // on the page hide
                        setTimeout(function() {
                            // measure container width
                            var width = container.offsetWidth;
                            var chartWidth = width - 20;
                            $('#legend'+chartId).width(chartWidth);

                            // display widget before resize, otherwise
                            // it will be rendered incorrectly in IE
                            widget.style.display = "";

                            chart.resizeGraph(json, width);
                            origWindowWidth = newWindowWidth;
                        }, 0);
                    }
                }, delay);
            });
        },

        /**
         * Update chart with new data from server
         *
         * @param chart
         * @param url
         * @param params
         * @param callback
         */
        update: function(chart, url, params, callback) {
            var self = this;
            params = params ? params : {};
            self.chart = chart;
            this.get(url, params, function(data) {
                if(self.isDataEmpty(data)){
                    self.chart.busy = false;
                    self.chart.updateJSON(data);
                    self.callback(callback);
                }
            });
        }
    }
})(jQuery);
