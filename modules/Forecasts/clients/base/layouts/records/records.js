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
 * Forecasts Records Layout
 *
 * @class View.Layouts.Base.Forecasts.RecordsLayout
 * @alias SUGAR.App.view.layouts.BaseForecastsRecordsLayout
 * @extends View.Layouts.Base.RecordsLayout
 *
 * Events
 *
 * forecasts:worksheet:committed
 *  on: this.context
 *  by: commitForecast
 *  when: after a successful Forecast Commit
 */
({
    /**
     * bool to store if a child worksheet is dirty
     */
    isDirty: false,
    
    /**
     * worksheet type
     */
    worksheetType: '',
    
    /**
     * the forecast navigation message
     */
    navigationMessage: "",
    
    /**
     * The options from the initialize call
     */
    initOptions: undefined,

    /**
     * Are the event already bound to the context and the models?
     */
    eventsBound: false,

    /**
     * Overrides the Layout.initialize function and does not call the parent so we can defer initialization
     * until _onceInitSelectedUser is called
     *
     * @inheritdoc
     */
    initialize: function(options) {
        this.initOptions = options;
        this._super('initialize', [options]);
        this.syncInitData();

    },

    /**
     * @inheritdoc
     */
    initComponents: function() {
    },

    /**
     * Overrides loadData to defer it running until we call it in _onceInitSelectedUser
     *
     * @inheritdoc
     */
    loadData: function() {
    },

    /**
     * @inheritdoc
     */
    bindDataChange: function() {
        // we need this here to track when the selectedTimeperiod changes and then also move it up to the context
        // so the recordlists can listen for it.
        if (!_.isUndefined(this.model) && this.eventsBound == false) {
            this.eventsBound = true;
            this.collection.on('reset', function() {
                // get the first model and set the last commit date
                var lastCommit = _.first(this.collection.models);
                var commitDate = undefined;
                if (lastCommit instanceof Backbone.Model && lastCommit.has('date_modified')) {
                    commitDate = lastCommit.get('date_modified');
                }
                this.context.set({'currentForecastCommitDate': commitDate});
            }, this);
            // since the selected user change on the context, update the model
            this.context.on('change:selectedUser', function(model, changed) {
                var update = {
                    'selectedUserId': changed.id,
                    'forecastType': app.utils.getForecastType(changed.is_manager, changed.showOpps)
                };
                this.model.set(update);
            }, this);

            // if the model changes, run a fetch
            this.model.on('change', function() {
                // clear this out as something on the model changed,
                // this will be set once the collection resets
                // set the value to null since it can be undefined
                this.context.set({'currentForecastCommitDate' : null}, {silent: true});
                this.collection.fetch();
            }, this);

            this.context.on('change:selectedTimePeriod', function() {
                // clear this out if the timeperiod changed on the context,
                // this will be set once the collection resets
                // set the value to null since it can be undefined
                this.context.set({'currentForecastCommitDate' : null}, {silent: true});
                this.collection.fetch();
            }, this);

            // listen on the context for a commit trigger
            this.context.on('forecasts:worksheet:commit', function(user, worksheet_type, forecast_totals) {
                this.commitForecast(user, worksheet_type, forecast_totals);
            }, this);

            //listen for the worksheets to be dirty/clean
            this.context.on("forecasts:worksheet:dirty", function(type, isDirty) {
                this.isDirty = isDirty;
                this.worksheetType = type;
            }, this);

            //listen for the worksheet navigation messages
            this.context.on("forecasts:worksheet:navigationMessage", function(message) {
                this.navigationMessage = message;
            }, this);

            //listen for the user to change
            this.context.on("forecasts:user:changed", function(selectedUser, context) {
                if (this.isDirty) {
                    app.alert.show('leave_confirmation', {
                        level: 'confirmation',
                        messages: app.lang.get(this.navigationMessage, 'Forecasts').split('<br>'),
                        onConfirm: _.bind(function() {
                            app.utils.getSelectedUsersReportees(selectedUser, context);
                        }, this),
                        onCancel: _.bind(function() {
                            this.context.trigger('forecasts:user:canceled');
                        }, this)
                    });
                } else {
                    app.utils.getSelectedUsersReportees(selectedUser, context);
                }
            }, this);

            //handle timeperiod change events
            this.context.on('forecasts:timeperiod:changed', function(model, startEndDates) {
                // create an anonymous function to combine the two calls where this is used
                var onSuccess = _.bind(function() {
                    this.context.set('selectedTimePeriod', model.get('selectedTimePeriod'));
                    this._saveTimePeriodStatEndDates(startEndDates['start'], startEndDates['end']);
                }, this);

                if (this.isDirty) {
                    app.alert.show('leave_confirmation', {
                        level: 'confirmation',
                        messages: app.lang.get(this.navigationMessage, 'Forecasts').split('<br>'),
                        onConfirm: onSuccess,
                        onCancel: _.bind(function() {
                            this.context.trigger('forecasts:timeperiod:canceled');
                        }, this)
                    });
                } else {
                    // call the on success handler
                    onSuccess();
                }
            }, this);
        }
    },

    /**
     * Utility Method to handle saving of the timeperiod start and end dates so we can use them in other parts
     * of the forecast application
     *
     * @param {String} startDate        Start Date
     * @param {String} endDate          End Date
     * @param {Boolean} [doSilent]      When saving to the context, should this be silent to supress events
     * @return {Object} The object that is saved to the context if the context is there.
     * @private
     */
    _saveTimePeriodStatEndDates: function(startDate, endDate, doSilent)
    {
        // if do silent is not passed in or it's not a boolean, then just default it to false, so the events will fire
        if (_.isUndefined(doSilent) || !_.isBoolean(doSilent)) {
            doSilent = false;
        }
        var userPref = app.date.convertFormat(app.user.getPreference('datepref')),
            systemPref = 'YYYY-MM-DD',
            dateObj = {
                start: app.date(startDate, [userPref, systemPref]).format(systemPref),
                end: app.date(endDate, [userPref, systemPref]).format(systemPref)
            };

        if (!_.isUndefined(this.context)) {
            this.context.set(
                'selectedTimePeriodStartEnd',
                dateObj,
                {silent: doSilent}
            );
        }

        return dateObj;
    },

    /**
     * Opens the Forecasts Config drawer
     */
    openConfigDrawer: function() {
        // if there is no drawer open, then we need to open the drawer.
        if(app.drawer._components.length == 0) {
            // trigger the forecast config by going to the config route, while replacing what
            // is currently there so when we use app.route.goBack() from the cancel button
            app.router.navigate('Forecasts/config', {replace: true, trigger: true});
        }
    },

    /**
     * Get the Forecast Init Data from the server
     *
     * @param {Object} options
     */
    syncInitData: function(options) {
        var callbacks,
            url;

        options = options || {};
        // custom success handler
        options.success = _.bind(function(data) {
            // Add Forecasts-specific stuff to the app.user object
            app.user.set(data.initData.userData);
            if (data.initData.forecasts_setup === 0) {
                // Immediately open the config drawer so user can set up config
                this.openConfigDrawer();
            } else {
                this.initForecastsModule(data);
            }
        }, this);

        // since we have not initialized the view yet, pull the model from the initOptions.context
        var model = this.initOptions.context.get('model');
        callbacks = app.data.getSyncCallbacks('read', model, options);
        this.trigger("data:sync:start", 'read', model, options);

        url = app.api.buildURL("Forecasts/init", null, null, options.params);

        var params = {},
            cfg = app.metadata.getModule('Forecasts', 'config');
        if (cfg && cfg.is_setup === 0) {
            // add no-cache header if forecasts isnt set up yet
            params = {
                headers: {
                    'Cache-Control': 'no-cache'
                }
            };
        }
        app.api.call("read", url, null, callbacks, params);
    },

    /**
     * Process the Forecast Data
     *
     * @param {Object} data contains the data passed back from Forecasts/init endpoint
     */
    initForecastsModule: function(data) {
        var ctx = this.initOptions.context;
        // we watch for the first selectedUser change to actually init the Forecast Module case then we know we have
        // a proper selected user
        ctx.once('change:selectedUser', this._onceInitSelectedUser, this);

        // lets see if the user has ranges selected, so lets generate the key from the filters
        var ranges_key = app.user.lastState.buildKey('worksheet-filter', 'filter', 'ForecastWorksheets'),
            default_selection = app.user.lastState.get(ranges_key) || data.defaultSelections.ranges;

        // set items on the context from the initData payload
        ctx.set({
            // set the value to null since it can be undefined
            currentForecastCommitDate: null,
            selectedTimePeriod: data.defaultSelections.timeperiod_id.id,
            selectedRanges: default_selection,
            selectedTimePeriodStartEnd: this._saveTimePeriodStatEndDates(
                data.defaultSelections.timeperiod_id.start,
                data.defaultSelections.timeperiod_id.end,
                true
            )
        }, {silent: true});

        ctx.get('model').set({'selectedTimePeriod': data.defaultSelections.timeperiod_id.id}, {silent: true});

        // set the selected user to the context
        app.utils.getSelectedUsersReportees(app.user.toJSON(), ctx);
    },

    /**
     * Event handler for change:selectedUser
     * Triggered once when the user is set for the first time.  After setting user it calls
     * the init of the records layout
     *
     * @param {Backbone.Model} model the model from the change event
     * @param {String} change the updated selectedUser value from the change event
     * @private
     */
    _onceInitSelectedUser: function(model, change) {
        // init the recordlist view
        app.view.Layout.prototype.initialize.call(this, this.initOptions);
        app.view.Layout.prototype.initComponents.call(this);

        // set the selected user and forecast type on the model
        this.model.set('selectedUserId', change.id, {silent: true});
        this.model.set('forecastType', app.utils.getForecastType(change.is_manager, change.showOpps));
        // bind the collection sync to our custom sync
        this.collection.sync = _.bind(this.sync, this);

        // load the data
        app.view.Layout.prototype.loadData.call(this);
        if (this.eventsBound === false) {
            // bind the data change
            this.bindDataChange();
        }
        // render everything
        if (!this.disposed) this.render();
    },

    /**
     * Custom sync method used by this.collection
     *
     * @param {String} method
     * @param {Backbone.Model} model
     * @param {Object} options
     */
    sync: function(method, model, options) {
        var callbacks,
            url;

        options = options || {};

        options.params = options.params || {};

        var args_filter = [],
            filter = null;
        if (this.context.has('selectedTimePeriod')) {
            args_filter.push({"timeperiod_id": this.context.get('selectedTimePeriod')});
        }
        if (this.model.has('selectedUserId')) {
            args_filter.push({"user_id": this.model.get('selectedUserId')});
            args_filter.push({"forecast_type": this.model.get('forecastType')});
        }

        if (!_.isEmpty(args_filter)) {
            filter = {"filter": args_filter};
        }

        options.params.order_by = 'date_entered:DESC'
        options = app.data.parseOptionsForSync(method, model, options);

        // custom success handler
        options.success = _.bind(function(data) {
            if (!this.disposed) {
                this.collection.reset(data);
            }
        }, this);

        callbacks = app.data.getSyncCallbacks(method, model, options);

        // if there's a 412 error dismiss the custom loading alert
        this.collection.once('data:sync:error', function() {
            app.alert.dismiss('worksheet_loading');
        }, this);

        this.collection.trigger("data:sync:start", method, model, options);

        url = app.api.buildURL("Forecasts/filter", null, null, options.params);
        app.api.call("create", url, filter, callbacks);
    },

    /**
     * Commit A Forecast
     *
     * @fires forecasts:worksheet:committed
     * @param {Object} user
     * @param {String} worksheet_type
     * @param {Object} forecast_totals
     */
    commitForecast: function(user, worksheet_type, forecast_totals) {
        var forecast = new this.collection.model(),
            forecastType = app.utils.getForecastType(user.is_manager, user.showOpps),
            forecastData = {};


        // we need a commit_type so we know what to do on the back end.
        forecastData.commit_type = worksheet_type;
        forecastData.timeperiod_id = forecast_totals.timeperiod_id || this.context.get('selectedTimePeriod');
        forecastData.forecast_type = forecastType;

        forecast.save(forecastData, { success: _.bind(function(model, response) {
            // we need to make sure we are not disposed, this handles any errors that could come from the router and window
            // alert events
            if (!this.disposed) {
                // Call sync again so commitLog has the full collection
                // method gets overridden and options just needs an
                this.collection.fetch();
                this.context.trigger("forecasts:worksheet:committed", worksheet_type, response);
                var msg, managerName;
                if (worksheet_type === 'sales_rep') {
                    if (user.is_manager) {
                        // as manager, use own name
                        managerName = user.full_name;
                    } else {
                        // as sales rep, use manager name
                        managerName = user.reports_to_name;
                    }
                } else {
                    if (user.reports_to_id) {
                        // if manager has a manager, use reports to name
                        managerName = user.reports_to_name;
                    }
                }
                if (managerName) {
                    msg = Handlebars.compile(app.lang.get('LBL_FORECASTS_WORKSHEET_COMMIT_SUCCESS_TO', 'Forecasts'))(
                        {
                            manager: managerName
                        }
                    );
                } else {
                    // user does not report to anyone, don't use any name
                    msg = Handlebars.compile(app.lang.get('LBL_FORECASTS_WORKSHEET_COMMIT_SUCCESS', 'Forecasts'))();
                }

                app.alert.show('success', {
                    level: 'success',
                    autoClose: true,
                    autoCloseDelay: 10000,
                    title: app.lang.get('LBL_FORECASTS_WIZARD_SUCCESS_TITLE', 'Forecasts') + ':',
                    messages: [msg]
                });
            }
        }, this),
            error: _.bind(function(model, error) {
                //if the metadata error comes back, we saved successfully, so we need to clear the is_dirty flag so the
                //page can reload
                if (error.status === 412) {
                    this.context.trigger('forecasts:worksheet:is_dirty', worksheet_type, false);
                }
            }, this),
            silent: true, alerts: { 'success': false }});
    }
})
