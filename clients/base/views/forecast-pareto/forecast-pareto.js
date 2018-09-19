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
 * Dashlet that displays a chart.
 *
 * @class View.Views.Base.ForecastParetoView
 * @alias SUGAR.App.view.views.BaseForecastParetoView
 * @extends View.View
 */
({
    plugins: ['Dashlet'],

    className: 'forecasts-chart-wrapper',

    /**
     * Should we display the timeperiod Pivot options
     */
    displayTimeperiodPivot: true,

    /**
     * Track if they are a manager
     */
    isManager: false,

    /**
     * Track if the user is a top-level manager
     */
    isTopLevelManager: false,

    /**
     * When on a Record view this are fields we should listen to changes in
     */
    validChangedFields: ['amount', 'likely_case', 'best_case', 'worst_case', 'assigned_user_id',
        'date_closed', 'date_closed_timestamp', 'probability', 'commit_stage', 'sales_stage'],

    /**
     * Hold the initOptions if we have to call the Forecast/init end point cause we are not on Forecasts
     */
    initOptions: null,

    /**
     * Holds the forecast isn't set up message if Forecasts hasn't been set up yet
     */
    forecastsNotSetUpMsg: undefined,

    /**
     * Holds the Forecast Module Config
     */
    forecastConfig: undefined,

    /**
     * Are we using Opportunities with RevenueLineItems?
     */
    opportunitiesWithRevenueLineItems: false,

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this.isManager = app.user.get('is_manager');
        this._initPlugins();

        // if the user is a manager, check if they're toplevel or not
        if (this.isManager) {
            this.isTopLevelManager = app.user.get('is_top_level_manager');
        }

        // if the parent exists, use it, otherwise use the main context
        this.initOptions = options;
        this.forecastConfig = app.metadata.getModule('Forecasts', 'config');
        this.isForecastSetup = this.forecastConfig.is_setup;
        this.forecastsConfigOK = app.utils.checkForecastConfig();

        var oppConfig = app.metadata.getModule('Opportunities', 'config');
        if (oppConfig && oppConfig['opps_view_by'] === 'RevenueLineItems') {
            this.opportunitiesWithRevenueLineItems = true;
        } else {
            this.opportunitiesWithRevenueLineItems = false;
        }

        if (this.isForecastSetup && this.forecastsConfigOK) {
            this.initOptions.meta.template = undefined;

            if (!options.meta.config) {
                // we only want to call this if forecast is setup and configured
                // and this is not the dashlet config screen
                app.api.call('GET', app.api.buildURL('Forecasts/init'), null, {
                    success: _.bind(this.forecastInitCallback, this),
                    complete: this.initOptions ? this.initOptions.complete : null
                });
            }

            this.displayTimeperiodPivot = (options.context.get('module') === 'Home');
        } else {
            // set the no access template
            this.initOptions.meta.template = 'forecast-pareto.no-access';
            var isAdmin = _.isUndefined(app.user.getAcls()['Forecasts'].admin);
            this.forecastsNotSetUpMsg = app.utils.getForecastNotSetUpMessage(isAdmin);
        }

        this._super('initialize', [this.initOptions]);
    },

    /**
     * @inheritdoc
     */
    getLabel: function() {
        return app.lang.get(this.meta.label);
    },

    /**
     * @inheritdoc
     */
    initDashlet: function() {
        if (!this.isManager && this.meta.config) {
            // FIXME: Dashlet's config page is rendered from meta.panels directly.
            // See the "dashletconfiguration-edit.hbs" file.
            this.meta.panels = _.chain(this.meta.panels).filter(function(panel) {
                panel.fields = _.without(panel.fields, _.findWhere(panel.fields, {name: 'visibility'}));
                return panel;
            }).value();
        }
        if (this.isForecastSetup && this.forecastsConfigOK) {
            this.settings.module = 'Forecasts';
        }
        var fieldOptions = app.lang.getAppListStrings(this.dashletConfig.dataset.options),
            cfg = app.metadata.getModule('Forecasts', 'config');
        this.dashletConfig.dataset.options = {};

        if (cfg.show_worksheet_worst &&
            app.acl.hasAccess('view', 'ForecastWorksheets', app.user.get('id'), 'worst_case')) {
            this.dashletConfig.dataset.options['worst'] = fieldOptions['worst'];
        }

        if (cfg.show_worksheet_likely) {
            this.dashletConfig.dataset.options['likely'] = fieldOptions['likely'];
        }

        if (cfg.show_worksheet_best &&
            app.acl.hasAccess('view', 'ForecastWorksheets', app.user.get('id'), 'best_case')) {
            this.dashletConfig.dataset.options['best'] = fieldOptions['best'];
        }

        // Hide dataset drop-down if there is only one option.
        this.dashletConfig.show_dataset = true;
        if (_.size(this.dashletConfig.dataset.options) <= 1) {
            this.dashletConfig.show_dataset = false;
        }
    },

    /**
     * Callback function for Forecasts/init success
     */
    forecastInitCallback: function(initData) {
        if (this.disposed) {
            return;
        }
        var defaultOptions = {
            user_id: app.user.get('id'),
            // Default to 'user' view for reps, and 'group' view for managers.
            display_manager: this.isDisplayManager(),
            show_target_quota: (this.isManager && !this.isTopLevelManager),
            selectedTimePeriod: initData.defaultSelections.timeperiod_id.id,
            timeperiod_id: initData.defaultSelections.timeperiod_id.id,
            timeperiod_label: initData.defaultSelections.timeperiod_id.label,
            dataset: initData.defaultSelections.dataset,
            group_by: initData.defaultSelections.group_by,
            ranges: _.keys(app.lang.getAppListStrings(this.forecastConfig.buckets_dom))
        };

        var model = this._getNonForecastModel();

        if (model && !this.displayTimeperiodPivot && model.has('date_closed_timestamp') &&
            model.get('date_closed_timestamp') != 0) {
            // if we have a timestamp, use it, otherwise just default to the current time period
            defaultOptions.timeperiod_id = model.get('date_closed_timestamp');
        } else {
            this.layout.setTitle(this.getLabel() + ' ' + defaultOptions.timeperiod_label);
        }
        this.settings.set(defaultOptions);
    },

    /**
     * Overwrite loadData so the default behavior doesn't happen
     *
     * @override
     */
    loadData: function(options) {
        if (options && _.isFunction(options.complete)) {
            options.complete();
        }
    },

    _render: function() {
        this.settings.set('display_manager', this.isDisplayManager());
        this.spanSize = this.displayTimeperiodPivot && this.dashletConfig.show_dataset ? 'span4' : 'span6';
        this._super('_render');

        var chartField = this.getField('paretoChart');

        if (!_.isUndefined(chartField)) {
            chartField.renderChart();
            chartField.once('chart:pareto:rendered', function() {
                if (this.opportunitiesWithRevenueLineItems &&
                    this.context.get('module') == 'RevenueLineItems') {
                    this.addRowToChart();
                } else {
                    this.addRowToChart();
                }
            }, this);
        }
    },

    /**
     * Called after _render
     */
    toggleRepOptionsVisibility: function() {
        var mgrToggleOffset;
        if (this.settings.get('display_manager') === true) {
            mgrToggleOffset = 6;
            this.$el.find('div.groupByOptions').addClass('hide');
        } else {
            mgrToggleOffset = 3;
            this.$el.find('div.groupByOptions').removeClass('hide');
        }

        if (this.displayTimeperiodPivot) {
            mgrToggleOffset = mgrToggleOffset - 3;
        }

        if (this.isManager) {
            var el = this.$el.find('#' + this.cid + '-mgr-toggle');
            if (el.length > 0) {
                var classes = el.attr('class').split(' ').filter(function(item) {
                    return item.indexOf('offset') === -1 ? item : '';
                });
                if (mgrToggleOffset != 0) {
                    classes.push('offset' + mgrToggleOffset);
                }
                el.attr('class', classes.join(' '));
            }
        }
    },

    /**
     * @inheritdoc
     *
     * Additional logic on switch visibility event.
     */
    visibilitySwitcher: function(event) {
        var activeVisibility;
        this.settings.set({
            display_manager: this.isDisplayManager(),
            show_target_quota: (this.isDisplayManager() && !this.isTopLevelManager)
        });
        if (!this.isManager) {
            return;
        }
        activeVisibility = this.getVisibility();
        this.$el.find('[data-action=visibility-switcher]')
            .attr('aria-pressed', function() {
                return $(this).val() === activeVisibility;
            });
    },

    /**
     * Calculates "display_manager" option according to visibility.
     *
     * @return {Boolean}
     */
    isDisplayManager: function() {
        return this.isManager ? (this.getVisibility() === 'group') : false;
    },

    /**
     * @inheritdoc
     */
    bindDataChange: function() {
        // on the off chance that the init has not run yet.
        var meta = this.meta || this.initOptions.meta;
        if (meta.config) {
            return;
        }

        // if we don't have a context, this shouldn't run yet.
        if (_.isUndefined(this.context)) {
            return;
        }

        if (this.isForecastSetup && this.forecastsConfigOK) {
            this.on('render', function() {
                var chartField = this.getField('paretoChart'),
                    dashletToolbar = this.layout.getComponent('dashlet-toolbar');

                // if we have a dashlet-toolbar, then make it do the refresh icon
                // while the chart is loading from the server
                if (chartField && dashletToolbar) {
                    chartField.before('chart:pareto:render', function() {
                        this.$('[data-action=loading]').removeClass(this.cssIconDefault).addClass(this.cssIconRefresh);
                    }, dashletToolbar);
                    chartField.on('chart:pareto:rendered', function() {
                        this.$('[data-action=loading]').removeClass(this.cssIconRefresh).addClass(this.cssIconDefault);
                    }, dashletToolbar);
                }
            }, this);

            this.settings.on('change:title', function(model, title) {
                this.layout.setTitle(this.getLabel() + title);
            }, this);

            this.settings.on('change:display_manager', this.toggleRepOptionsVisibility, this);

            if (!this.displayTimeperiodPivot) {
                this.findModelToListen();
                this.listenModel.on('change', this.handleDataChange, this);
            } else {
                this.settings.on('change:selectedTimePeriod', function(context, timeperiod) {
                    this.settings.set({timeperiod_id: timeperiod});
                }, this);
            }
        }
    },

    findModelToListen: function() {
        this.listenModel = this._getNonForecastModel();
        if (this.opportunitiesWithRevenueLineItems) {
            // now that this context is set to the forecast module, we should use the parent context if it exists
            // if it doesn't then we use the context on this dashlet
            var context = this.context.parent || this.context;
            if (context.get('module') == 'Opportunities') {
                // we need to watch for when the date changes time periods on the opportunity to re-render the chart
                this._getNonForecastModel().on('change:date_closed_timestamp', function(model, changed) {
                    this.settings.set('timeperiod_id', changed);
                }, this);
                // since we are forecasting by RLI but on the Opp Module, we need to find the subpanel for RLI to watch
                // for the changes there
                var ctx = _.find(context.children, function(child) {
                    return (child.get('module') == 'RevenueLineItems');
                });
                if (ctx && ctx.has('collection')) {
                    this.listenModel = ctx.get('collection');
                }
            }
        }
    },

    /**
     * Utility Method to find the proper model to use, if this.model.module is forecasts, go up to the parent context
     * and use the model that's attached to it otherwise return this.model
     *
     * @return {Backbone.Model|Data.Bean}
     * @private
     */
    _getNonForecastModel: function() {
        if (this.model.module == 'Forecasts') {
            return this.context.parent.get('model');
        }

        return this.model;
    },

    /**
     * Handler for when the model changes
     * @param {Object} [model]      The model that changed, if not provided, it will use this.model
     */
    handleDataChange: function(model) {
        model = model || this._getNonForecastModel();
        var changed = model.changed,
            changedField = _.keys(changed),
            validChangedFields = _.intersection(this.validChangedFields, _.keys(changed)),
            changedCurrencyFields = _.intersection(
                ['amount', 'best_case', 'likely_case', 'worst_case'],
                validChangedFields
            ),
            assigned_user = model.get('assigned_user_id');

        // lets make sure that the values actually changed on the currencies,
        // this is needed because the server will send back the values with out .00 on the end and the model has .00
        // so it looks like it changed, when in fact it didn't so don't worry about this change
        if (!_.isEmpty(changedCurrencyFields)) {
            _.each(changedCurrencyFields, function(field) {
                if (parseFloat(model.get(field)) == parseFloat(model.previous(field))) {
                    validChangedFields = _.without(validChangedFields, field);
                }
            });
        }

        // dump out if it's not a field we are watching
        if (_.isEmpty(validChangedFields)) {
            return;
        }

        if (this.settings.get('display_manager') === false && assigned_user == app.user.get('id')) {
            // we can update this chart
            // get what we are currently filtered by
            // find the item in the serverData
            var field = this.getField('paretoChart'),
                serverData = field.getServerData();

            if (!field.hasServerData()) {
                // if the field does not have server data, that means it's re-rendering the chart already,
                // just bail out
                return;
            }

            // if we only have one changed field and it's the date_closed, lets map it to a timestamp.
            // this happens on the Opp -> RLI Subpanel since we don't have SugarLogic Support in ListViews
            if (changedField.length == 1 && changedField[0] == 'date_closed') {
                // convert this into the timestamp
                changedField.push('date_closed_timestamp');
                changed.date_closed_timestamp = Math.round(+app.date.parse(changed.date_closed).getTime() / 1000);
                model.set('date_closed_timestamp', changed.date_closed_timestamp, {silent: true});
            }

            // before we do anything, lets make sure that if the date_changed, make sure it's still in this range,
            // if it's not force the chart to update to the new timeperiod that is valid for this row, then add this
            // row to the new timeperiod
            if (_.contains(changedField, 'date_closed_timestamp')) {
                if (!(model.get('date_closed_timestamp') >= _.first(serverData['x-axis']).start_timestamp &&
                    model.get('date_closed_timestamp') <= _.last(serverData['x-axis']).end_timestamp)) {

                    // lets check to see, if we have a collection as the listenModel, then just remove the row if there
                    // is more than one record in the collection
                    if (this.listenModel instanceof Backbone.Collection) {
                        if (this.listenModel.length > 1) {
                            this.removeRowFromChart(model);
                            return;
                        }
                    }
                    // we just have a model, so lets just update it
                    field.once('chart:pareto:rendered', function() {
                        this[0].addRowToChart(this[1]);
                    }, [this, model]);
                    this.settings.set('timeperiod_id', model.get('date_closed_timestamp'));
                    return;
                }
            }

            // Amount on Opportunity maps to likely in the data set
            if (_.contains(changedField, 'amount')) {
                changed.likely = this._convertCurrencyValue(changed.amount, model.get('base_rate'));
                delete changed.amount;
            }
            // Likely Case in RLI
            if (_.contains(changedField, 'likely_case')) {
                changed.likely = this._convertCurrencyValue(changed.likely_case, model.get('base_rate'));
                delete changed.likely_case;
            }

            if (_.contains(changedField, 'best_case')) {
                changed.best = this._convertCurrencyValue(changed.best_case, model.get('base_rate'));
                delete changed.best_case;
            }
            if (_.contains(changedField, 'worst_case')) {
                changed.worst = this._convertCurrencyValue(changed.worst_case, model.get('base_rate'));
                delete changed.worst_case;
            }

            if (_.contains(changedField, 'commit_stage')) {
                changed.forecast = changed.commit_stage;
                delete changed.commit_stage;
            }

            var record = _.find(serverData.data, function(record, i, list) {
                if (model.get('id') == record.record_id) {
                    list[i] = _.extend({}, record, changed);
                    return true;
                }
                return false;
            });

            // the row was not found, lets add it
            if (_.isEmpty(record)) {
                this.addRowToChart(model);
            } else {
                field.setServerData(serverData, _.contains(changedField, 'probability'));
            }
        } else if (_.contains(changedField, 'assigned_user_id')) {
            if (assigned_user === app.user.get('id')) {
                this.addRowToChart(model);
            } else {
                this.removeRowFromChart(model);
            }
        }
    },

    /**
     * Add the model to the pareto chart
     * @param {Object} [model]      The Model to add, if not passed in, it will use this.model
     */
    addRowToChart: function(model) {
        model = model || this._getNonForecastModel();
        if (model.get('assigned_user_id') == app.user.get('id') && !this.settings.get('display_manager')) {
            var field = this.getField('paretoChart'),
                serverData = field.getServerData(),
            // make sure it doesn't exist in the serverdata
                found = _.find(serverData.data, function(record) {
                    return (record.record_id == model.get('id'));
                }),
                base_rate = model.get('base_rate'),
                likely_field = model.has('amount') ? model.get('amount') : model.get('likely_case');

            if (_.isEmpty(found)) {
                serverData.data.push({
                    best: this._convertCurrencyValue(model.get('best_case'), base_rate),
                    likely: this._convertCurrencyValue(likely_field, base_rate),
                    worst: this._convertCurrencyValue(model.get('worst_case'), base_rate),
                    record_id: model.get('id'),
                    date_closed_timestamp: model.get('date_closed_timestamp'),
                    probability: model.get('probability'),
                    sales_stage: model.get('sales_stage'),
                    forecast: model.get('commit_stage')
                });
                field.setServerData(serverData, true);
            }
        }
    },

    /**
     * Utility Method to convert to base rate
     * @param {Number} value
     * @param {Number} base_rate
     * @return {Number}
     * @protected
     */
    _convertCurrencyValue: function(value, base_rate) {
        return app.currency.convertWithRate(value, base_rate);
    },

    /**
     * Get the server data from the ParetoField and if the model exists in the data, remove it
     *
     * @param {Object} [model]      The Model to add, if not passed in, it will use this.model
     */
    removeRowFromChart: function(model) {
        model = model || this._getNonForecastModel();
        var field = this.getField('paretoChart'),
            serverData = field.getServerData();

        _.find(serverData.data, function(record, i, list) {
            if (model.get('id') == record.record_id) {
                list.splice(i, 1);
                return true;
            }
            return false;
        });

        field.setServerData(serverData, true);
    },

    /**
     * @inheritdoc
     * Clean up!
     */
    unbindData: function() {
        var ctx = this.context.parent;
        if (ctx) {
            ctx.off(null, null, this);
        }
        if (this.listenModel) this.listenModel.off(null, null, this);
        if (this.context) this.context.off(null, null, this);
        app.view.View.prototype.unbindData.call(this);
    },

    /**
     * Initialize plugins.
     * Only manager can toggle visibility.
     *
     * @return {BaseForecastParetoView} Instance of this view.
     * @protected
     */
    _initPlugins: function() {
        if (this.isManager) {
            this.plugins = _.union(this.plugins, [
                'ToggleVisibility'
            ]);
        }
        return this;
    }
})
