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
 * @class View.Views.Base.SavedReportsChartView
 * @alias SUGAR.App.view.views.BaseSavedReportsChartView
 * @extends View.View
 */
({
    plugins: ['Dashlet'],

    events: {
        'click a[name=editReport]': 'editSavedReport'
    },

    /**
     * @inheritdoc
     */
    initDashlet: function(view) {
        // check if we're on the config screen
        if (this.meta.config) {
            this.meta.panels = this.dashletConfig.dashlet_config_panels;
        } else {
            var autoRefresh = this.settings.get('auto_refresh');
            if (autoRefresh > 0) {
                if (this.timerId) {
                    clearTimeout(this.timerId);
                }

                this._scheduleReload(autoRefresh * 1000 * 60);
            }
        }
    },

    /**
     * Schedules chart data reload
     *
     * @param {Number} delay Number of milliseconds which the reload should be delayed for
     * @private
     */
    _scheduleReload: function(delay) {
        this.timerId = setTimeout(_.bind(function() {
            this.context.resetLoadFlag();
            this.loadData({
                success: function() {
                    this._scheduleReload(delay);
                }
            });
        }, this), delay);
    },

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        // Holds report data from the server's endpoint once we fetch it
        this.reportData = app.data.createBean();
        this.reportOptions = [];
        this._super('initialize', [options]);
        this.on('chart:complete', this.chartComplete, this);
    },

    /**
     * Route to the bwc edit view of the currently selected Saved Report. If User clicks 'save' or 'cancel' or 'delete'
     * from there, return the user to the current page.
     *
     * @deprecated
     */
    editSavedReport: function() {
        app.logger.warn('View.Views.Base.SavedReportsChartView#editSavedReport' +
            'has been deprecated since 8.0.0.0 and will be removed in a future release');
        var currentTargetId = this.dashModel.get('saved_report_id'),
            params = {
                dashletEdit: 1
            },
            route = app.bwc.buildRoute('Reports', currentTargetId, 'ReportsWizard', params);

        //If this button was clicked too early, the saved_report_id may not be populated. Then we want to return
        //because moving on will result in a php error
        if (!currentTargetId) {
            return;
        }
        app.alert.show('navigate_confirmation', {
            level: 'confirmation',
            messages: 'LBL_NAVIGATE_TO_REPORTS',
            onConfirm: _.bind(function() {
                //Save current location to this so we can use it in the event listener
                this.currentLocation = Backbone.history.getFragment();

                //Add event listener for when the user finishes up the edit
                $(window).one('dashletEdit', _.bind(this.postEditListener, this));

                //Once we've successfully routed to the dashletEdit location,
                //any successive route should be checked. If the user moves away from the edit without
                //either cancelling or finishing the edit, we should forget that we have to come back to the current location
                var dashletEditVisited = false;
                app.router.on('route', function() {
                    var routeLocation = Backbone.history.getFragment();
                    if (routeLocation.indexOf('dashletEdit=1') >= 0) {
                        dashletEditVisited = true;
                    }
                    if (routeLocation.indexOf('dashletEdit=1') < 0 && dashletEditVisited) {
                        app.router.off('route');
                        $(window).off('dashletEdit');
                    }
                });

                //Go to edit page
                app.router.navigate(route, {trigger: true});
            }, this)
        });
    },

    /**
     * Call after the user is done editing the saved report. Return the user to the page that was stored when the
     * event was set
     *
     * @param {Object} jquery event
     */
    postEditListener: function(event) {
        //Go back from whence we came
        if (this.currentLocation) {
            app.router.navigate(this.currentLocation, {trigger: true});
        }
    },

    /**
     * @inheritdoc
     */
    bindDataChange: function() {
        if (this.meta.config) {
            this.settings.on('change:saved_report_id', function(model) {
                var reportId = model.get('saved_report_id');
                var options;
                if (_.isEmpty(reportId)) {
                    return;
                }
                options = {
                    success: function(data) {
                        var label;
                        this.setChartParams(data, true);
                        // set the title of the dashlet to the report title
                        label = this.$('[name="label"]');
                        if (label.length) {
                            label.val(this.settings.get('label'));
                        }
                    }
                };
                this.getSavedReportById(reportId, options);
            }, this);

            this.settings.on('change:chart_type', function(model) {
                // reset settings xAxisLabel because of line chart
                var reportData = this.reportData.get('rawReportData');
                var chartData = this.reportData.get('rawChartData');
                var axisLabel;
                // report might no be loaded yet
                if (reportData && chartData) {
                    axisLabel = this._getXaxisLabel(
                        reportData.group_defs,
                        chartData.properties[0],
                        model.get('chart_type')
                    );
                    model.set('x_axis_label', axisLabel);
                }
                // toggle display of chart display option controls based on chart type
                this._toggleChartFields();
            }, this);
        }
    },

    /**
     * Check acls to show/hide 'Edit Selected Report' link
     *
     * @deprecated
     */
    updateEditLink: function(reportId) {
        app.logger.warn('View.Views.Base.SavedReportsChartView#updateEditLink' +
            'has been deprecated since 8.0.0.0 and will be removed in a future release');

        var acls = this.reportAcls[reportId || this.settings.get('saved_report_id')],
            showEditLink = !acls || acls['edit'] !== 'no';
        this.$('[name="editReport"]').toggle(showEditLink);
    },

    /**
     * @inheritdoc
     */
    loadData: function(options) {
        options = options || {};
        var reportId = this.settings.get('saved_report_id');

        if (!_.isEmpty(reportId)) {
            // set callback for successful get of report data in getSavedReportById()
            _.extend(options, {
                success: _.bind(function(data) {
                    this.setChartParams(data, false);
                }, this)
            });
            this.getSavedReportById(reportId, options);
        }
    },

    getDefaultSettings: function() {
        // By default, settings only has: label, type, config, module
        // Module is normally null so we want to rehit that
        var settings = _.clone(this.settings.attributes);
        var defaults = {
                allowScroll:     true,
                auto_refresh:    0,
                colorData:       'class',
                config:          true,
                hideEmptyGroups: true,
                reduceXTicks:    true,
                rotateTicks:     true,
                show_controls:   false,
                show_title:      false,
                show_x_label:    false,
                show_y_label:    false,
                showValues:      0,
                staggerTicks:    true,
                wrapTicks:       true,
                x_axis_label:    '',
                y_axis_label:    ''
            };
        return _.defaults(settings, defaults);
    },

    /**
     * Process the chart data from the server
     *
     * @param {Object|String} serverData The Report Data from the server
     * @param {Boolean} [update] Is this an update to the report?
     */
    setChartParams: function(serverData, update) {
        var updated;
        var data;
        var properties;
        var config;
        var params;
        var settings;
        var chartType;

        // only called by bindDataChange when the report id is changed in config panel
        if (!serverData.reportData || !serverData.chartData) {
            if (!this.meta.config && this.chartField) {
                this.chartField.displayNoData(true);
            }
            return;
        }
        updated = _.isUndefined(update) ? false : update;
        data = serverData.reportData;
        properties = serverData.chartData.properties[0];

        config = this.getChartConfig(properties.type); // this is chart type in Report

        // default settings is current settings with defaults
        settings = this.getDefaultSettings();

        // this does what extend/defaults does but we need it for x_axis_label before
        chartType = updated ? config.chartType : settings.chart_type || config.chartType;

        params = {
            label: data.label,
            chart_type: chartType, // this is renamed chart type from Report
            report_title: properties.title,
            show_legend: properties.legend === 'on' ? true : false,
            stacked: config.barType === 'stacked' || config.barType === 'basic' ? true : false,
            x_axis_label: this._getXaxisLabel(data.group_defs, properties, chartType),
            y_axis_label: this._getYaxisLabel(data),
            module: properties.base_module,
            allow_drillthru: properties.allow_drillthru,
            vertical: config.orientation === 'vertical' ? true : false,
            direction: app.lang.direction
        };

        // override settings when new report is selected
        if (updated) {
            _.extend(settings, params);
        } else {
            _.defaults(settings, params);
        }

        // persist the chart settings for use by SugarCharts
        this.reportData.set('rawChartParams', settings);

        // update the settings model for use by chart field
        this.settings.set(settings);

        // update chart state for drillthru
        if (this.context && this.context.get('chartState') && this.context.get('chartLabels')) {
            var newState = this.getChartState(serverData.chartData);
            if (newState) {
                this.context.set('chartState', newState);
            } else {
                this.context.unset('chartState');
                this.context.unset('chartLabels');
            }
        }

        // toggle display of chart display option controls based on chart type
        this._toggleChartFields();
    },

    /**
     * Gets chart state
     * @param {Object} chartData chart data
     * @return {Object} chart state
     */
    getChartState: function(chartData) {
        var chartState = null;
        var chartLabels = this.context.get('chartLabels');
        var config = this.context.get('dashConfig');
        var reportData = this.context.get('reportData');
        switch (config.chart_type) {
            case 'funnel chart':
            case 'pie chart':
                if (reportData.group_defs.length > 1) {
                    var seriesIndex = _.findIndex(chartData.values, function(value) {
                        return value.label === chartLabels.seriesLabel;
                    });
                    if (seriesIndex >= 0) {
                        chartState = {seriesIndex: chartData.values.length - seriesIndex - 1};
                    }
                } else {
                    var seriesIndex = _.indexOf(chartData.label, chartLabels.seriesLabel);
                    if (seriesIndex >= 0) {
                        chartState = {seriesIndex: chartData.label.length - seriesIndex - 1};
                    }
                }
                break;
            case 'horizontal bar chart':
            case 'bar chart':
                if (reportData.group_defs.length > 1) {
                    var seriesIndex = _.findIndex(chartData.values, function(value) {
                        return value.label === chartLabels.seriesLabel;
                    });
                    if (seriesIndex >= 0) {
                        chartState = {groupIndex: seriesIndex, pointIndex: seriesIndex, seriesIndex: seriesIndex};
                    }
                } else {
                    var groupIndex = _.indexOf(chartData.label, chartLabels.groupLabel);
                    if (groupIndex >= 0) {
                        chartState = {groupIndex: groupIndex, pointIndex: groupIndex, seriesIndex: 0};
                    }
                }
                break;
            case 'horizontal group by chart':
            case 'group by chart':
            case 'stacked group by chart':
                if (reportData.group_defs.length > 1) {
                    var groupIndex = _.findIndex(chartData.values, function(value) {
                        return value.label === chartLabels.groupLabel;
                    });
                    var seriesIndex = _.indexOf(chartData.label, chartLabels.seriesLabel);
                    if (groupIndex >= 0 && seriesIndex >= 0) {
                        chartState = {groupIndex: groupIndex, pointIndex: groupIndex, seriesIndex: seriesIndex};
                    }
                } else {
                    var groupIndex = _.indexOf(chartData.label, chartLabels.groupLabel);
                    if (groupIndex >= 0) {
                        chartState = {groupIndex: groupIndex, pointIndex: groupIndex, seriesIndex: groupIndex};
                    }
                }
                break;
            case 'line chart':
                if (reportData.group_defs.length > 1) {
                    var seriesIndex = _.findIndex(chartData.values, function(value) {
                        return value.label === chartLabels.groupLabel;
                    });
                    var groupIndex = _.indexOf(chartData.label, chartLabels.seriesLabel);
                    if (groupIndex >= 0 && seriesIndex >= 0) {
                        chartState = {groupIndex: groupIndex, pointIndex: groupIndex, seriesIndex: seriesIndex};
                    }
                } else {
                    var groupIndex = _.indexOf(chartData.label, chartLabels.groupLabel);
                    if (groupIndex >= 0) {
                        chartState = {groupIndex: groupIndex, pointIndex: 0, seriesIndex: groupIndex};
                    }
                }
                break;
        }
        return chartState;
    },

    /**
     * Builds the chart config based on the type of chart
     * @return {Mixed}
     */
    getChartConfig: function(chartType) {
        var chartConfig;

        switch (chartType) {
            case 'pie chart':
                chartConfig = {
                    chartType: 'pie chart'
                };
                break;

            case 'line chart':
                chartConfig = {
                    lineType: 'grouped',
                    chartType: 'line chart'
                };
                break;

            case 'funnel chart 3D':
                chartConfig = {
                    chartType: 'funnel chart'
                };
                break;

            case 'gauge chart':
                chartConfig = {
                    chartType: 'gauge chart'
                };
                break;

            case 'stacked group by chart':
                chartConfig = {
                    orientation: 'vertical',
                    barType: 'stacked',
                    chartType: 'group by chart'
                };
                break;

            case 'group by chart':
                chartConfig = {
                    orientation: 'vertical',
                    barType: 'grouped',
                    chartType: 'group by chart'
                };
                break;

            case 'bar chart':
                chartConfig = {
                    orientation: 'vertical',
                    barType: 'basic',
                    chartType: 'group by chart'
                };
                break;

            case 'horizontal group by chart':
                chartConfig = {
                    orientation: 'horizontal',
                    barType: 'stacked',
                    chartType: 'horizontal group by chart'
                };
                break;

            case 'horizontal bar chart':
            case 'horizontal':
                chartConfig = {
                    orientation: 'horizontal',
                    barType: 'basic',
                    chartType: 'horizontal group by chart'
                };
                break;

            default:
                chartConfig = {
                    orientation: 'vertical',
                    barType: 'stacked',
                    chartType: 'bar chart'
                };
                break;
        }

        return chartConfig;
    },

    /**
     * Callback function on chart render complete.
     *
     * @param {Function} chart sucrose chart instance
     * @param {Object} params chart display parameters
     * @param {Object} reportData report data with properties and data array
     */
    chartComplete: function(chart, params, reportData, chartData) {
        if (!_.isFunction(chart.seriesClick) || !params.allow_drillthru) {
            return;
        }

        // This seriesClick callback overrides the default set
        // in sugarCharts for use in the Report module charts
        chart.seriesClick(_.bind(function(data, eo, chart, labels) {
            var state = SUGAR.charts.buildChartState(eo, labels);
            if (!_.isFinite(state.seriesIndex)) {
                return;
            }

            if (params.chart_type === 'line chart') {
                params.groupLabel = SUGAR.charts.extractSeriesLabel(state, data);
                params.seriesLabel = SUGAR.charts.extractGroupLabel(state, labels);
            } else {
                params.seriesLabel = SUGAR.charts.extractSeriesLabel(state, data);
                params.groupLabel = SUGAR.charts.extractGroupLabel(state, labels);
            }

            chart.clearActive();
            if (chart.cellActivate) {
                chart.cellActivate(state);
            } else if (chart.seriesActivate) {
                chart.seriesActivate(state);
            } else {
                chart.dataSeriesActivate(eo);
            }
            // keep track of chart state for refresh
            // needed for drillthru chart only
            if (this.context.get('chartState')) {
                this.context.set('chartState', state);
                var chartLabels = {
                    groupLabel: params.groupLabel,
                    seriesLabel: params.seriesLabel
                };
                this.context.set('chartLabels', chartLabels);
            }
            chart.dispatch.call('tooltipHide', this);

            this._handleFilter(chart, params, state, reportData, chartData);
        }, this));
    },

    /**
     * Handle either navigating to target module or update list view filter.
     *
     * @param {Function} chart sucrose chart instance
     * @param {Object} params chart display parameters
     * @param {Object} state chart display and data state
     * @param {Object} reportData report data as returned from API
     * @param {Object} chartData chart data with properties and data array
     * @protected
     */
    _handleFilter: function(chart, params, state, reportData, chartData) {
        var module = params.baseModule;
        var reportId = this.settings.get('saved_report_id');

        var enums;
        var groupDefs;
        var drawerContext;

        app.alert.show('listfromreport_loading', {level: 'process', title: app.lang.get('LBL_LOADING')});

        if (this.$el.parents('.drawer.active').length === 0) {
            enums = SUGAR.charts.getEnums(reportData);
            groupDefs = SUGAR.charts.getGrouping(reportData);
            drawerContext = {
                chartData: chartData,
                chartModule: module,
                chartState: state,
                dashModel: null,
                dashConfig: params,
                enumsToFetch: enums,
                filterOptions: {
                    auto_apply: false
                },
                groupDefs: groupDefs,
                layout: 'drillthrough-drawer',
                module: 'Reports',
                reportData: reportData,
                reportId: reportId,
                skipFetch: true,
                useSavedFilters: true
            };

            chart.clearActive();
            this.openDrawer(drawerContext);
        } else {
            this.updateList(params, state);
        }
    },

    /**
     * Open a drill through drawer with list and dashlet replica.
     *
     * @param {Object} drawerContext drillthrough content and display parameters
     */
    openDrawer: function(drawerContext) {
        var currentModule = app.drawer.context.get('module');

        // This needs to set to target module for Merge to show the target module fields
        app.drawer.context.set('module', drawerContext.chartModule);

        app.drawer.open({
            layout: 'drillthrough-drawer',
            context: drawerContext
        }, _.bind(function() {
            if (currentModule) {
                // reset the drawer module
                app.drawer.context.set('module', currentModule);
            }
        }, this, currentModule));
    },

    /**
     * Update the record list in drill through drawer.
     *
     * @param {Object} params chart display parameters
     * @param {Object} state chart display and data state
     */
    updateList: function(params, state) {
        var drawer = this.closestComponent('drawer').getComponent('drillthrough-drawer');
        drawer.context.set('dashConfig', params);
        drawer.context.set('chartState', state);
        drawer.updateList();
    },

    /**
     * Returns the x-axis label based on report data
     * @return {String}
     */
    _getXaxisLabel: function(groups, properties, chartType) {
        return chartType === 'line chart' ?
            properties.seriesName || _.last([].concat(groups)).label :
            properties.groupName || _.first([].concat(groups)).label;
    },

    /**
     * Returns the y-axis label based on report data
     * @return {String}
     */
    _getYaxisLabel: function(data) {
        var label = '';
        if (data && data.summary_columns) {
            _.each(data.summary_columns, function(column) {
                if (!_.isUndefined(column.group_function)) {
                    label = column.label;
                }
            });
        }
        return label;
    },

    /**
     * Makes a call to filter api to get all reports with chart stored in the saved_reports table
     *
     * @deprecated
     */
    getAllReportsWithCharts: function() {
        app.logger.warn('View.Views.Base.SavedReportsChartView#getAllReportsWithCharts' +
            'has been deprecated since 8.0.0.0 and will be removed in a future release');
        var params = {
                fields: 'id,name,module,report_type,content,chart_type,assigned_user_id',
                order_by: 'name:asc',
                filter: [{chart_type: {$not_equals: 'none'}}],
                // get all reports with charts
                max_num: -1
            },
            url = app.api.buildURL('Reports', null, null, params);

        app.api.call('read', url, null, {
            success: _.bind(this.parseAllSavedReports, this)
        });
    },

    /**
     * Parses items passed back from filter api endpoint into enum options
     *
     * @param {Array} reports an array of saved reports returned from the endpoint
     * @deprecated
     */
    parseAllSavedReports: function(reports) {
        app.logger.warn('View.Views.Base.SavedReportsChartView#parseAllSavedReports' +
            'has been deprecated since 8.0.0.0 and will be removed in a future release');

        reports = reports.records || [];
        this.reportOptions = {};
        this.reportAcls = {};

        _.each(reports, function(report) {
            if (app.acl.hasAccess('view', report.module)) {
                // build the reportOptions key/value pairs
                this.reportOptions[report.id] = report.name;
                this.reportAcls[report.id] = report._acl;
            }
        }, this);

        // find the saved_report_id field
        var reportsField = _.find(this.fields, function(field) {
            return field.name == 'saved_report_id';
        });

        if (reportsField) {
            // set the initial saved_report_id to the first report in the list
            // if there are reports to show and we have not already saved this
            // dashlet yet with a report ID
            if (reports && (!this.settings.has('saved_report_id') || _.isEmpty(this.settings.get('saved_report_id')))) {
                this.settings.set('saved_report_id', _.first(reports).id);
            }

            // set field options and render
            reportsField.items = this.reportOptions;
            reportsField._render();

            // check acls to show or hide 'Edit Selected Report' link
            this.updateEditLink();
        }
    },

    /**
     * Makes a call to Reports/:id/chart to fetch specific saved report data
     *
     * @param {String} reportId the ID for the report we're looking for
     */
    getSavedReportById: function(reportId, options) {
        var dt = this.layout.getComponent('dashlet-toolbar');
        if (dt) {
            // manually set the icon class to spiny
            this.$('[data-action=loading]').removeClass(dt.cssIconDefault).addClass(dt.cssIconRefresh);
        }
        var useSavedFilters = this.context && this.context.get('useSavedFilters') ? 'true' : 'false';
        var url = app.api.buildURL('Reports/' + reportId + '/chart?use_saved_filters=' + useSavedFilters);
        app.api.call('read', url, null, {
            success: _.bind(function(serverData) {
                if (options && options.success) {
                    // options.success is usually setChartParams()
                    // defines this.reportData 'rawChartParams';
                    options.success.apply(this, arguments);
                }

                this.reportData.set('rawReportData', serverData.reportData);
                // set reportData's rawChartData to the chartData from the server
                // this will trigger chart.js' change:rawChartData and the chart will update
                this.reportData.set('rawChartData', serverData.chartData);
            }, this),
            complete: options ? options.complete : null
        });
    },

    /**
     * @inheritdoc
     */
    _render: function() {
        // if we're in config, or if the chartField doesn't exist yet... render
        // otherwise do not render again as this destroys and re-draws the chart and looks awful
        if (this.meta.config || _.isUndefined(this.chartField)) {
            this._super('_render');
        }
    },

    /**
     * Handle the display of the chart display option controls based on chart type
     *
     * @private
     */
    _toggleChartFields: function() {
        if (this.meta.config) {

            var xOptionsFieldset = this.getField('x_label_options'),
                tickDisplayMethods = this.getField('tickDisplayMethods'),
                yOptionsFieldset = this.getField('y_label_options'),
                showValuesField = this.getField('showValues'),
                groupDisplayOptions = this.getField('groupDisplayOptions'),
                stackedField = this.getField('stacked'),
                showDimensionOptions = false,
                showBarOptions = false,
                showTickOptions = false,
                showStacked = false,
                xOptionsLabel = app.lang.get('LBL_CHART_CONFIG_SHOW_XAXIS_LABEL'),
                yOptionsLabel = app.lang.get('LBL_CHART_CONFIG_SHOW_YAXIS_LABEL');

            switch (this.settings.get('chart_type')) {
                case 'pie chart':
                case 'gauge chart':
                case 'funnel chart 3D':
                    showDimensionOptions = false;
                    showBarOptions = false;
                    break;

                case 'line chart':
                    showDimensionOptions = true;
                    showBarOptions = false;
                    break;

                case 'stacked group by chart':
                case 'horizontal group by chart':
                case 'group by chart':
                    showDimensionOptions = true;
                    showBarOptions = true;
                    showStacked = true;
                    break;

                case 'vertical bar chart':
                case 'vertical':
                case 'bar chart':
                case 'horizontal bar chart':
                case 'horizontal':
                    showDimensionOptions = true;
                    showBarOptions = true;
                    showStacked = false;
                    break;

                default:
                    showDimensionOptions = false;
                    showBarOptions = false;
            }

            if (showDimensionOptions) {
                switch (this.settings.get('chart_type')) {
                    case 'horizontal group by chart':
                    case 'horizontal bar chart':
                    case 'horizontal':
                        showTickOptions = false;
                        xOptionsLabel = app.lang.get('LBL_CHART_CONFIG_SHOW_YAXIS_LABEL');
                        yOptionsLabel = app.lang.get('LBL_CHART_CONFIG_SHOW_XAXIS_LABEL');
                        break;
                    case 'line chart':
                        showTickOptions = true;
                        break;
                    default:
                        showTickOptions = true;
                        xOptionsLabel = app.lang.get('LBL_CHART_CONFIG_SHOW_XAXIS_LABEL');
                        yOptionsLabel = app.lang.get('LBL_CHART_CONFIG_SHOW_YAXIS_LABEL');
                }
            }

            if (xOptionsFieldset) {
                xOptionsFieldset.$el.closest('.record-cell').toggleClass('hide', !showDimensionOptions);
                xOptionsFieldset.$el.closest('.record-cell').find('.record-label').text(xOptionsLabel);
                yOptionsFieldset.$el.closest('.record-cell').find('.record-label').text(yOptionsLabel);
            }
            if (tickDisplayMethods) {
                tickDisplayMethods.$el.closest('.record-cell').toggleClass('hide', !showDimensionOptions || !showTickOptions);
                tickDisplayMethods.$el.find('.disabled').find('input').prop( 'checked', true ).prop('disabled', true);
            }

            if (yOptionsFieldset) {
                yOptionsFieldset.$el.closest('.record-cell').toggleClass('hide', !showDimensionOptions);
            }

            if (showValuesField) {
                showValuesField.$el.closest('.record-cell').toggleClass('hide', !showBarOptions);
            }
            if (groupDisplayOptions) {
                groupDisplayOptions.$el.closest('.record-cell').toggleClass('hide', !showBarOptions);
                if (stackedField) {
                    stackedField.$el.toggleClass('hide', !showStacked);
                }
            }

        }
    },

    /**
     * Handle the conditional display of settings input field based on checkbox toggle state
     *
     * @param {Object} toggle a checkbox control that determines display state of field
     * @param {Object} dependent the input field that holds the setting value
     * @private
     */
    _toggleDepedent: function(toggle, dependent) {
        var inputField = dependent.$el.find(dependent.fieldTag),
            enabled = this.settings.get(toggle.name),
            value = enabled ? this.settings.get(dependent.name) : '';
        inputField
            .prop('disabled', !enabled)
            .val(value);
    },

    /**
     * @inheritdoc
     * When rendering fields, get a reference to the chart field if we don't have one yet
     */
    _renderField: function(field) {
        this._super('_renderField', [field]);

        // Manage display state of fieldsets with toggle
        if (this.meta.config) {

            if (!_.isUndefined(field.def.toggle)) {
                var toggle = this.getField(field.def.toggle),
                    dependent = this.getField(field.def.dependent);

                this._toggleDepedent(toggle, dependent);

                this.settings.on('change:' + toggle.name, _.bind(function(event) {
                    this._toggleDepedent(toggle, dependent);
                }, this));
                this.settings.on('change:' + dependent.name, _.bind(function(event) {
                    this._toggleDepedent(toggle, dependent);
                }, this));
            }
        }

        // hang on to a reference to the chart field
        if (_.isUndefined(this.chartField) && field.name === 'chart') {
            this.chartField = field;
        }
    }
})
