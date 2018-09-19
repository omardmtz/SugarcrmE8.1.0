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
 * Dashlet that displays a chart
 */
({
    plugins: ['Dashlet'],

    /**
     * This is the values model for the template
     */
    values: new Backbone.Model(),

    className: 'forecasts-chart-wrapper',

    /**
     * Hold the initOptions if we have to call the Forecast/init end point cause we are not on Forecasts
     */
    initOptions: null,

    /**
     * The context of the ForecastManagerWorksheet Module if one exists
     */
    forecastManagerWorksheetContext: undefined,

    /**
     * The context of the ForecastWorksheet Module if one exists
     */
    forecastWorksheetContext: undefined,

    /**
     * Track if current user is manager.
     */
    isManager: false,
    
    /**
     * @inheritdoc
     */
    initialize: function(options) {
        // after we init, find and bind to the Worksheets Contexts
        this.once('init', this.findWorksheetContexts, this);
        this.once('render', function() {
            this.parseCollectionForData();
        }, this);
        this.isManager = app.user.get('is_manager');
        this._super('initialize', [options]);
        if (!this.meta.config) {
            var ctx = this.context.parent,
                user = ctx.get('selectedUser') || app.user.toJSON(),
                showMgr = ctx.get('model').get('forecastType') == 'Rollup';

            this.values.set({
                user_id: user.id,
                display_manager: showMgr,
                show_target_quota: (user.is_manager && !user.is_top_level_manager),
                ranges: ctx.get('selectedRanges') || ['include'],
                timeperiod_id: ctx.get('selectedTimePeriod'),
                dataset: 'likely',
                group_by: 'forecast',
                no_data: true
            });
        }
    },

    /**
     * Specific code to run after a dashlet Init Code has ran
     */
    initDashlet: function() {
        var fieldOptions,
            cfg = app.metadata.getModule('Forecasts', 'config');
        fieldOptions = app.lang.getAppListStrings(this.dashletConfig.dataset.options);
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
     * Loop though the parent context children context to find the worksheet, if they exist
     */
    findWorksheetContexts: function() {
        // loop though the children context looking for the ForecastWorksheet and ForecastManagerWorksheet Modules
        _.filter(this.context.parent.children, function(item) {
            if (item.get('module') == 'ForecastWorksheets') {
                this.forecastWorksheetContext = item;
                return true;
            } else if (item.get('module') == 'ForecastManagerWorksheets') {
                this.forecastManagerWorksheetContext = item;
                return true;
            }
            return false;
        }, this);

        var collection;

        if (this.forecastWorksheetContext) {
            // listen for collection change events
            collection = this.forecastWorksheetContext.get('collection');
            if (collection) {
                collection.on('change', this.repWorksheetChanged, this);
                collection.on('reset', function(collection) {
                    this.parseCollectionForData(collection);
                }, this);
            }
        }

        if (this.forecastManagerWorksheetContext) {
            // listen for collection change events
            collection = this.forecastManagerWorksheetContext.get('collection');
            if (collection) {
                collection.on('change', this.mgrWorksheetChanged, this);
                collection.on('reset', function(collection) {
                    this.parseCollectionForData(collection);
                }, this);
            }
        }
    },

    /**
     * Figure out which way we need to parse a collection
     *
     * @param {Backbone.Collection} [collection]
     */
    parseCollectionForData: function(collection) {
        if (this.meta.config) {
            return;
        }
        // get the field
        var field = this.getField('paretoChart');
        if(field && !field.hasServerData()) {
            // if the field does not have any data, wait for the xhr call to run and then just call this
            // method again
            field.once('chart:pareto:rendered', this.parseCollectionForData, this);
            return;
        }

        if (this.values.get('display_manager')) {
            this.parseManagerWorksheet(collection || this.forecastManagerWorksheetContext.get('collection'));
        } else {
            this.parseRepWorksheet(collection || this.forecastWorksheetContext.get('collection'));
        }
    },

    /**
     * Parses a chart data collection for the Rep worksheet
     *
     * @param {Backbone.Collection} collection
     */
    parseRepWorksheet: function(collection) {
        var field = this.getField('paretoChart');
        if(field) {
            var serverData = field.getServerData();

            serverData.data = collection.map(function(item) {
                var i = {
                    id: item.get('id'),
                    forecast: item.get('commit_stage'),
                    probability: item.get('probability'),
                    sales_stage: item.get('sales_stage'),
                    likely: app.currency.convertWithRate(item.get('likely_case'), item.get('base_rate')),
                    date_closed_timestamp: parseInt(item.get('date_closed_timestamp'))
                };

                if (!_.isUndefined(this.dashletConfig.dataset.options['best'])) {
                    i.best = app.currency.convertWithRate(item.get('best_case'), item.get('base_rate'));
                }
                if (!_.isUndefined(this.dashletConfig.dataset.options['worst'])) {
                    i.worst = app.currency.convertWithRate(item.get('worst_case'), item.get('base_rate'));
                }

                return i;
            }, this);

            field.setServerData(serverData, true);
        }
    },

    /**
     * Parses a chart data collection for the Manager worksheet
     *
     * @param {Backbone.Collection} collection
     */
    parseManagerWorksheet: function(collection) {
        var field = this.getField('paretoChart');
        if(field) {
            var serverData = field.getServerData();

            serverData.data = collection.map(function(item) {
                var i = {
                    id: item.get('id'),
                    user_id: item.get('user_id'),
                    name: item.get('name'),
                    likely: app.currency.convertWithRate(item.get('likely_case'), item.get('base_rate')),
                    likely_adjusted: app.currency.convertWithRate(item.get('likely_case_adjusted'), item.get('base_rate')),
                    quota: app.currency.convertWithRate(item.get('quota'), item.get('base_rate'))
                };

                if (!_.isUndefined(this.dashletConfig.dataset.options['best'])) {
                    i.best = app.currency.convertWithRate(item.get('best_case'), item.get('base_rate'));
                    i.best_adjusted = app.currency.convertWithRate(item.get('best_case_adjusted'), item.get('base_rate'));
                }
                if (!_.isUndefined(this.dashletConfig.dataset.options['worst'])) {
                    i.worst = app.currency.convertWithRate(item.get('worst_case'), item.get('base_rate'));
                    i.worst_adjusted = app.currency.convertWithRate(item.get('worst_case_adjusted'), item.get('base_rate'));
                }

                return i;
            }, this);

            serverData.quota = _.reduce(serverData.data, function(memo, item) {
                return app.math.add(memo, item.quota, undefined, true);
            }, 0);

            field.setServerData(serverData);
        }
    },

    /**
     * Handler for when the Rep Worksheet Changes
     * @param {Object} model
     */
    repWorksheetChanged: function(model) {
        // get what we are currently filtered by
        // find the item in the serverData
        var changed = model.changed,
            changedField = _.keys(changed),
            field = this.getField('paretoChart'),
            serverData = field.getServerData();

        // if the changedField is date_closed, we need to adjust the timestamp as well since SugarLogic doesn't work
        // on list views yet
        if (changedField.length == 1 && changedField[0] == 'date_closed') {
            // convert this into the timestamp
            changedField.push('date_closed_timestamp');
            changed.date_closed_timestamp = Math.round(+app.date.parse(changed.date_closed).getTime() / 1000);
            model.set('date_closed_timestamp', changed.date_closed_timestamp, {silent: true});
        }

        if (_.contains(changedField, 'likely_case')) {
            changed.likely = app.currency.convertWithRate(changed.likely_case, model.get('base_rate'));
            delete changed.likely_case;
        }
        if (_.contains(changedField, 'best_case')) {
            changed.best = app.currency.convertWithRate(changed.best_case, model.get('base_rate'));
            delete changed.best_case;
        }
        if (_.contains(changedField, 'worst_case')) {
            changed.worst = app.currency.convertWithRate(changed.worst_case, model.get('base_rate'));
            delete changed.worst_case;
        }

        if (_.contains(changedField, 'commit_stage')) {
            changed.forecast = changed.commit_stage;
            delete changed.commit_stage;
        }

        _.find(serverData.data, function(record, i, list) {
            if (model.get('id') == record.id) {
                list[i] = _.extend({}, record, changed);
                return true;
            }
            return false;
        });

        field.setServerData(serverData, _.contains(changedField, 'probability'));
    },

    /**
     * Handler for when the Manager Worksheet Changes
     * @param {Object} model
     */
    mgrWorksheetChanged: function(model) {
        var fieldsChanged = _.keys(model.changed),
            changed = model.changed,
            field = this.getField('paretoChart');
        if(field && field.hasServerData()) {
            var serverData = field.getServerData();

            if (_.contains(fieldsChanged, 'quota')) {
                var q = parseInt(serverData.quota, 10);
                q = app.math.add(app.math.sub(q, model.previous('quota')), model.get('quota'));
                serverData.quota = q;
            } else {
                var f = _.first(fieldsChanged),
                    fieldChartName = f.replace('_case', '');

                // find the user
                _.find(serverData.data, function(record, i, list) {
                    if (model.get('user_id') == record.user_id) {
                        list[i][fieldChartName] = changed[f];
                        return true;
                    }
                    return false;
                });
            }

            field.setServerData(serverData);
        }
    },

    /**
     * When loadData is called, find the paretoChart field, if it exist, then have it render the chart
     *
     * @inheritdoc
     */
    loadData: function(options) {
        var field = this.getField('paretoChart');

        if (!_.isUndefined(field)) {
            field.once('chart:pareto:rendered', this.parseCollectionForData, this);
            field.renderChart(options);
        }
        if (options && _.isFunction(options.complete)) {
            options.complete();
        }
    },

    /**
     * Called after _render
     */
    toggleRepOptionsVisibility: function() {
        this.$('div.groupByOptions').toggleClass('hide', this.values.get('display_manager') === true);
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

        this.values.on('change:title', function(model, title) {
            this.layout.setTitle(app.lang.get(this.meta.label) + title);
        }, this);

        this.on('render', function() {
            var field = this.getField('paretoChart'),
                dashToolbar = this.layout.getComponent('dashlet-toolbar');

            // if we have a dashlet-toolbar, then make it do the refresh icon while the chart is loading from the
            // server
            if (dashToolbar) {
                field.before('chart:pareto:render', function() {
                    this.$("[data-action=loading]").removeClass(this.cssIconDefault).addClass(this.cssIconRefresh);
                }, dashToolbar);
                field.on('chart:pareto:rendered', function() {
                    this.$("[data-action=loading]").removeClass(this.cssIconRefresh).addClass(this.cssIconDefault);
                }, dashToolbar);
            }
            this.toggleRepOptionsVisibility();
            this.parseCollectionForData();
        }, this);

        var ctx = this.context.parent;

        ctx.on('change:selectedUser', function(context, user) {
            var displayMgr = ctx.get('model').get('forecastType') == 'Rollup',
                showTargetQuota = (displayMgr && !user.is_top_level_manager);
            this.values.set({
                user_id: user.id,
                display_manager: displayMgr,
                show_target_quota: showTargetQuota
            });
            this.toggleRepOptionsVisibility();
        }, this);
        ctx.on('change:selectedTimePeriod', function(context, timePeriod) {
            this.values.set({timeperiod_id: timePeriod});
        }, this);
        ctx.on('change:selectedRanges', function(context, value) {
            this.values.set({ranges: value});
        }, this);
    },

    /**
     * @inheritdoc
     */
    unbindData: function() {
        var ctx = this.context.parent;
        if (ctx) {
            ctx.off(null, null, this);
        }

        if (this.forecastManagerWorksheetContext && this.forecastManagerWorksheetContext.get('collection')) {
            this.forecastManagerWorksheetContext.get('collection').off(null, null, this);
        }

        if (this.forecastWorksheetContext && this.forecastWorksheetContext.get('collection')) {
            this.forecastWorksheetContext.get('collection').off(null, null, this);
        }

        if (this.context) {
            this.context.off(null, null, this);
        }

        if (this.values) {
            this.values.off(null, null, this);
        }

        this._super('unbindData');
    }
})
