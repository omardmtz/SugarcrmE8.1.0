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
 * @class View.Views.Base.ForecastdetailsView
 * @alias SUGAR.App.view.views.BaseForecastdetailsView
 * @extends View.View
 */
({
    plugins: ['Dashlet'],

    /**
     * Manager totals for likely_adjusted
     */
    likelyTotal: 0,

    /**
     * Manager totals for best_adjusted
     */
    bestTotal: 0,

    /**
     * Manager totals for worst_adjusted
     */
    worstTotal: 0,

    /**
     * If we need to get the rollup or direct forecast data
     */
    shouldRollup: false,

    /**
     * Necessary for Forecast module as the selectedUser can change and be different from currently-loggged-in user
     */
    selectedUser: {},

    /**
     * Has Forecast module been set up
     */
    isForecastSetup: false,

    /**
     * Is the user a Forecast admin
     */
    isForecastAdmin: false,

    /**
     * Track if current user is manager.
     */
    isManager: false,

    /**
     * Holds the subDetails template so the timeperiod field doesn't re-fetch every re-render
     */
    subDetailsTpl: {},

    /**
     * Holds the detailsMsg template
     */
    detailsMsgTpl: {},

    /**
     * Holds the dom values for best/likely/worst show/hide dropdown
     */
    detailsDataSet: {},

    /**
     * Config metadata from Forecasts module
     */
    forecastConfig: {},

    /**
     * If timeperiod dropdown should be shown (not in Forecasts)
     */
    showTimeperiod: true,

    /**
     * Holds if the forecasts config has proper closed won/lost keys
     */
    forecastsConfigOK: false,

    /**
     * Contains the latest saved data from the server
     */
    serverData: {},

    /**
     * The parent module for the dashlet
     */
    currentModule: '',

    /**
     * The span class number to use span12, span4, etc
     */
    spanCSS: '',

    /**
     * Flag for if we've run getInitData yet or not
     */
    initDataLoaded: false,

    /**
     * events on the view for which to watch
     */
    events : {
        'click #forecastsProgressDisplayOptions div.datasetOptions label.radio' : 'changeDisplayOptions'
    },

    /**
     * Holds previous totals for math
     */
    oldTotals: {},

    /**
     * Holds a collection of quota Objects by the quota's record ID
     */
    quotaCollection: undefined,

    /**
     * What to show when we don't have access to the data
     */
    noDataAccessTemplate: undefined,

    /**
     * Holds likely/best/worst field access boolean values
     * ex: { likely: true, best: false, worst: false }
     */
    fieldDataAccess: {},

    /**
     * Managers that are not top-level managers should also
     * show the target quota (exact quota assigned by their mgr)
     */
    showTargetQuota: false,

    /**
     * Holds the forecast isn't set up message if Forecasts hasn't been set up yet
     */
    forecastsNotSetUpMsg: undefined,

    /**
     * Flag for if loadData is currently running
     */
    loading: false,

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        this.currentModule = app.controller.context.get("module");

        // check to make sure that forecast is configured
        this.forecastConfig = app.metadata.getModule('Forecasts', 'config');
        this.isForecastSetup = this.forecastConfig.is_setup;
        this.forecastsConfigOK = app.utils.checkForecastConfig();
        this.isForecastAdmin = _.isUndefined(app.user.getAcls()['Forecasts'].admin);
        this.isManager = app.user.get('is_manager');

        if(!this.isForecastSetup) {
            this.forecastsNotSetUpMsg = app.utils.getForecastNotSetUpMessage(this.isForecastAdmin);
        }

        if(this.isForecastSetup && this.forecastsConfigOK) {
            this.serverData = new Backbone.Model();

            // Check field access
            var aclModule = this.forecastConfig.forecast_by,
                likelyFieldName = (aclModule == 'RevenueLineItems') ? 'likely_case' : 'amount';
            this.fieldDataAccess = {
                likely: app.acl.hasAccess('read', aclModule, app.user.get('id'), likelyFieldName),
                best: app.acl.hasAccess('read', aclModule, app.user.get('id'), 'best_case'),
                worst: app.acl.hasAccess('read', aclModule, app.user.get('id'), 'worst_case')
            };
            var hasAccess = (this.fieldDataAccess.likely && this.fieldDataAccess.best && this.fieldDataAccess.worst);
            // if any field has no access, get the noaccess field template
            if(hasAccess === false) {
                this.noDataAccessTemplate = app.template.getField('base', 'noaccess')(this);
            }

            // set up the model data
            this.resetModel();

            // since we need the timeperiods from 'Forecasts' set the models module to 'Forecasts'
            this.context.get('model').module = 'Forecasts';

            // use the object version of user not a Model
            this.selectedUser = app.user.toJSON();

            if (this.currentModule != 'Home') {
                // On Forecasts, this is based on whether user is viewing manager or rep worksheet
                this.shouldRollup = this.isManagerView();
            } else {
                // On Home, the dashlet should default to manager data for managers, and rep for non-manager
                this.shouldRollup = this.selectedUser.is_manager;
            }

            // once selectedUser & shouldRollup is set, check if user is a sub-manager
            this.checkShowTargetQuota();

            // set up the subtemplate
            this.subDetailsTpl = app.template.getView('forecastdetails.sub-details');
            this.detailsMsgTpl = app.template.getView('forecastdetails.details-msg');

            this.detailsDataSet = this.setUpShowDetailsDataSet(this.forecastConfig);

            this.checkSpanCSS();
        }
    },

    /**
     * @inheritdoc
     */
    initDashlet: function() {
        this.settings.module = 'Forecasts';
    },

    /**
     * Checks config show_worksheet_ settings for likely/best/worst and sets the spanCSS
     */
    checkSpanCSS: function() {
        var ct = 0;
        _.each([this.forecastConfig.show_worksheet_likely,
            this.forecastConfig.show_worksheet_best,
            this.forecastConfig.show_worksheet_worst], function(val)
        {
            if(val) {
                ct++;
            }
        });

        switch(ct) {
            case 3:
                this.spanCSS = '4';
                break;
            case 2:
                this.spanCSS = '6';
                break;
            case 1:
                this.spanCSS = '12';
                break;
            case 0:
                this.spanCSS = '';
                break;
        }

        this.model.set({spanCSS: this.spanCSS}, {silent: true});
    },

    /**
     * Returns an object of key: value pairs to be used in the select dropdowns to choose Likely/Best/Worst data to show/hide
     *
     * @param cfg Metadata config object for forecasts
     * @return {Object}
     */
    setUpShowDetailsDataSet: function(cfg) {
        var ds = app.metadata.getStrings('app_list_strings')['forecasts_options_dataset'] || [];

        var returnDs = {};
        _.each(ds, function(value, key) {
            if(cfg['show_worksheet_' + key] == 1) {
                returnDs[key] = value
            }
        }, this);
        return returnDs;
    },

    /**
     * Resets the model to default data
     */
    resetModel: function() {
        var model = {
            opportunities : 0,
            closed_amount : undefined,
            quota_amount : undefined,
            target_quota_amount: undefined,
            deficit_amount: undefined,
            worst_details: undefined,
            likely_details: undefined,
            best_details: undefined,
            show_details_likely: this.forecastConfig.show_worksheet_likely,
            show_details_best: this.forecastConfig.show_worksheet_best,
            show_details_worst: this.forecastConfig.show_worksheet_worst,
            spanCSS: this.spanCSS,
            quota_amount_str: undefined,
            target_quota_amount_str: undefined,
            closed_amount_str: undefined,
            deficit_class: undefined,
            deficit_amount_str: undefined,
            isForecastSetup: this.isForecastSetup,
            isForecastAdmin: this.isForecastAdmin
        };
        if(this.context.get('model')) {
            this.context.get('model').set(model)
        } else {
            this.model.set(model);
        }
    },

    /**
     * Builds dashlet url
     *
     * @return {Mixed} url to call
     */
    getProjectedURL: function() {
        var method = this.shouldRollup ? 'progressManager' : 'progressRep',
            url = 'Forecasts/' + this.model.get('selectedTimePeriod') + '/' + method + '/' + this.selectedUser.id,
            params = {};

        // if this is a manager view, send the target_quota param to the endpoint
        if(this.shouldRollup) {
            params = {
                target_quota: (this.showTargetQuota) ? 1 : 0
            };
        }

        return app.api.buildURL(url, 'create', null, params);
    },

    /**
     * @inheritdoc
     */
    bindDataChange: function() {
        if(this.meta.config) {
            return;
        }

        var ctx = this.model;
        if (this.currentModule == 'Forecasts') {
            ctx = this.context.parent || this.context;
            this.showTimeperiod = false;
        } else if (this.currentModule == 'Home') {
            // listen to the TimePeriod field's model changes to set the dashlet
            this.settings.on('change:selectedTimePeriod', function(model) {
                this.updateDetailsForSelectedTimePeriod(model.get('selectedTimePeriod'));
                // reload dashlet data when the selectedTimePeriod changes
                this.loadData({});
            }, this);
        }

        ctx.on('change:selectedTimePeriod', function(model) {
            if(this.currentModule == 'Forecasts') {
                this.updateDetailsForSelectedTimePeriod(model.get('selectedTimePeriod'));
            }
            // reload dashlet data when the selectedTimePeriod changes
            this.loadData({});
        }, this);

        // Home module doesn't have a changing selectedUser
        if(this.currentModule == 'Forecasts') {

            this.quotaCollection = app.utils.getSubpanelCollection(ctx, 'ForecastManagerWorksheets');

            this.quotaCollection.on('reset', this.processQuotaCollection, this);

            this.quotaCollection.on('change:quota', function(data) {
                var oldQuota = (this.getOldTotalFromCollectionById(data.get('user_id'))) ? this.getOldTotalFromCollectionById(data.get('user_id')).quota : 0,
                    newQuota = data.get('quota'),
                    diff = app.math.sub(data.get('quota'), oldQuota),
                    newQuotaTotal = app.math.add(this.serverData.get('quota_amount'), diff);
                // set the new "oldTotals" value
                this.setOldTotalFromCollectionById(data.get('user_id'), {quota: newQuota});
                // calculate and update the Quota on the frontend
                this.calculateData({quota_amount: newQuotaTotal});
            }, this);

            this.processQuotaCollection();

            ctx.on('change:selectedUser', function(model) {
                this.updateDetailsForSelectedUser(model.get('selectedUser'));
                // reload dashlet data when the selectedUser changes
                this.loadData({});
            }, this);

            ctx.on('forecasts:worksheet:totals', function(data) {
                this.calculateData(this.mapAllTheThings(data, true), true);
            }, this);

            // Using LHS Model to store the initial values of the LHS model so we don't have
            // to ping the server every dashlet load for the true original DB values of the LHS model
            if(!_.has(ctx.attributes, 'lhsData')) {
                ctx.set({
                    lhsData: {
                        quotas: this.oldTotals
                    }
                });
            }
        }
    },

    /**
     * @inheritdoc
     */
    unbindData: function() {
        var ctx;
        if (this.currentModule) {
            if(this.currentModule == 'Forecasts') {
                ctx = this.context.parent || this.context;
                if(this.quotaCollection) {
                    this.quotaCollection.off(null, null, this);
                }
            } else {
                ctx = this.model;
            }

            if(ctx) {
                ctx.off(null, null, this);
            }

            if (this.currentModule == 'Home') {
                this.settings.off(null, null, this);
            }
        }

        this._super('unbindData');
    },

    /**
     * Overrides loadData to load from a custom URL
     *
     * @override
     */
    loadData: function(options) {
        // if in dashlet config, or if Forecasts is not configured properly,
        // do not load data
        if(this.meta.config || !this.forecastsConfigOK || !this.isForecastSetup || this.loading) {
            return;
        }

        if(!this.initDataLoaded) {
            this.getInitData(options);
        }

        if(!_.isEmpty(this.model.get('selectedTimePeriod'))) {
            this.loading = true;
            var url = this.getProjectedURL(),
                cb = {
                    context: this,
                    success: _.bind(function(options, data) {
                        if(options && options.beforeParseData) {
                            data = options.beforeParseData(data);
                            data.parsedData = true;
                        }
                        this.handleNewDataFromServer(data)
                    }, this, options),
                    complete: _.bind(function(){
                        this.loading = false;
                        if (options && options.complete && _.isFunction(options.complete)) {
                            options.complete();
                        }
                    }, this)
                };

            app.api.call('read', url, null, null, cb);
        }
    },

    /**
     * Extensible function for getting initial data
     *
     * @param options
     */
    getInitData: function(options) {
        // get the current timeperiod
        app.api.call('GET', app.api.buildURL('TimePeriods/current'), null, {
            success: _.bind(function(currentTP) {
                // Make sure the model is here when we get back and this isn't mid-pageload or anything
                if(this.model) {
                    this.initDataLoaded = true;
                    this.model.set({selectedTimePeriod: currentTP.id}, {silent: true});
                    this.settings.set({selectedTimePeriod: currentTP.id}, {silent: true});
                    this.loadData();
                }
            }, this),
            error: _.bind(function() {
                // Needed to catch the 404 in case there isnt a current timeperiod
            }, this),
            complete: options ? options.complete : null
        });
    },

    /**
     * Processes this.quotaCollection.models to determine which models IDs should be
     * saved into the closedWonIds array
     */
    processQuotaCollection: function() {
        var model = this.context.get('model') || this.model,
            newQuota = 0,
            oldQuota = model.get('quota_amount'),
            quota = 0;
        this.oldTotals.models = new Backbone.Model();
        _.each(this.quotaCollection.models, function(model) {
            quota = model.get('quota');
            newQuota = app.math.add(newQuota, quota);
            // save all the initial likely values
            this.setOldTotalFromCollectionById(model.get('user_id'), {
                quota: quota
            });
        }, this);

        if(oldQuota !== newQuota) {
            this.calculateData({quota_amount: newQuota});
        }
    },

    /**
     * Gets an object from the oldTotals Model
     *
     * @param id the model ID for the Object
     * @return {Object}
     */
    getOldTotalFromCollectionById: function(id) {
        return this.oldTotals.models.get(id);
    },

    /**
     * Sets a totals Object on the oldTotals Model by id
     *
     * @param id model id
     * @param totals object to set
     * @return {Mixed}
     */
    setOldTotalFromCollectionById: function(id, totals) {
        this.oldTotals.models.set(id, totals);
    },

    /**
     * @inheritdoc
     */
    _render: function() {
        this._super('_render');
        this.renderSubDetails();
    },

    /**
     * Used to re-render only the projected data inside the dashlet so render doesnt
     * get called and dispose the select2 timeperiod field, which would then go
     * re-fetch its data at least once every render
     */
    renderSubDetails: function() {
        if(this.$el && this.subDetailsTpl) {
            var subEl = this.$el.find('.forecast-details'),
                model = this.context.get('model') || this.model;
            // Check if closed or quota is undefined (during opps/rli loading when those numbers aren't available yet)
            if(!_.isUndefined(model.get('closed_amount')) && !_.isUndefined(model.get('quota_amount'))) {
                subEl.html(this.subDetailsTpl(model.toJSON()));
                this.renderCSSChanges(model);
            } else {
                subEl.html('');
            }
        }
    },

    /**
     * Adds the CSS to elements classes post-render
     *
     * @param {Backbone.Model} [model]          The Model to use
     */
    renderCSSChanges: function(model) {
        model = model || this.context.get('model') || this.model;
        var isDeficit = model.get('is_deficit');

        // using getClassBasedOnAmount and sending 0 or 1 to resolve which class to use so the class names
        // are only in one place
        if(isDeficit) {
            this.$el.find('.deficitRow').addClass(this.getClassBasedOnAmount(0, 1, 'color'));
        } else {
            this.$el.find('.deficitRow').addClass(this.getClassBasedOnAmount(1, 0, 'color'));
        }

        this.checkPropertySetCSS('worst', model);
        this.checkPropertySetCSS('likely', model);
        this.checkPropertySetCSS('best', model);
    },

    /**
     * Checks a property on the config and sets the background color of an element
     * @param {String} prop 'likely', 'best', or 'worst'
     * @param {Backbone.Model} [model]      The model to use
     */
    checkPropertySetCSS: function(prop, model) {
        model = model || this.context.get('model') || this.model;
        // if we're showing the field
        // And this is the mgr view or it's the rep view and the user has access to the field
        if(this.forecastConfig['show_worksheet_' + prop]
            && (this.shouldRollup || (!this.shouldRollup && this.fieldDataAccess[prop]))) {
            var css = this.getClassBasedOnAmount(app.math.add(this.serverData.get(prop), this.serverData.get('closed_amount')), model.get('quota_amount'), 'background-color');
            this.$el.find('#forecast_details_' + prop + '_feedback').addClass(css);
        }
    },

    /**
     * Any variable mapping happens here
     *
     * @param data an Object
     */
    mapAllTheThings: function(data, fromModel) {
        if(this.shouldRollup) {
            // Manager View
            data.likely = data.likely_adjusted || data.likely_case;
            data.best = data.best_adjusted || data.best_case;
            data.worst = data.worst_adjusted || data.worst_case;
        } else {
            // Rep View
            if(fromModel) {
                data.likely = data.likely_case;
            } else {
                data.likely = data.amount;
            }

            data.best = data.best_case;
            data.worst = data.worst_case;
            data.closed_amount = data.won_amount;

            // can happen if data comes fromModel and won_amount isnt there
            if(_.isUndefined(data.closed_amount)) {
                // unset closed_amount so it doesnt impact totals
                delete data.closed_amount;
            }
        }

        if (fromModel) {
            data.worst = app.math.sub(data.worst, (data.closed_amount || 0));
            data.likely = app.math.sub(data.likely, (data.closed_amount || 0));
            data.best = app.math.sub(data.best, (data.closed_amount || 0));
        }

        return data;
    },

    /**
     * Success callback function for loadData to call
     *
     * @param data
     */
    handleNewDataFromServer: function(data) {
        // since the user might add this dashlet after they have changed the quota models, but before they saved it
        // we have to check and make sure that we're accounting for any changes in the dashlet totals that come
        // from the server
        if(this.currentModule == 'Forecasts' && this.context && this.shouldRollup) {
            var lhsData = this.context.get('lhsData');
            if(!lhsData && _.has(this.context, 'parent') && !_.isNull(this.context.parent)) {
                lhsData = this.context.parent.get('lhsData');
            }

            if(lhsData && !_.isEmpty(lhsData.quotas.models.attributes)) {
                var lhsTotal = 0;
                _.each(lhsData.quotas.models.attributes, function(val, key) {
                    lhsTotal = app.math.add(lhsTotal, val.quota);
                }, this);
                if(lhsTotal != parseFloat(data.quota_amount)) {
                    data.quota_amount = app.math.sub(data.quota_amount, app.math.sub(data.quota_amount, lhsTotal));
                }
            }
        }
        this.calculateData(this.mapAllTheThings(data, false));
    },

    /**
     * Handles parsing data objects into model
     *
     * @param data
     * @param fromModel if this request is from the model or the server
     */
    calculateData: function(data, fromModel) {
        fromModel = fromModel || false;
        // update serverData with changes from data
        this.serverData.set(data);

        // update data with any values serverData had but data doesn't
        // we create a new variable here, since we don't want to update the data param back on the worksheet table
        // and maybe break something
        var d = _.extend({}, data, this.serverData.toJSON());

        this.likelyTotal = d.likely;
        this.bestTotal = d.best;
        this.worstTotal = d.worst;

        d.quota_amount_str = app.currency.formatAmountLocale(d.quota_amount);
        d.closed_amount_str = app.currency.formatAmountLocale(d.closed_amount);

        if(this.showTargetQuota) {
            d.target_quota_amount_str = app.currency.formatAmountLocale(d.target_quota_amount);
        } else {
            this.serverData.unset('target_quota_amount_str');
        }
        d.showTargetQuota = this.showTargetQuota;

        // handle deficit
        d.deficit_amount = Math.abs(app.math.sub(d.quota_amount, d.closed_amount));
        d.deficit_amount_str = app.currency.formatAmountLocale(d.deficit_amount);
        d.is_deficit = (parseFloat(d.quota_amount) > parseFloat(d.closed_amount));

        var deficitLabelKey = (d.is_deficit) ? 'LBL_FORECAST_DETAILS_DEFICIT' : 'LBL_FORECAST_DETAILS_SURPLUS';
        d.deficit_label = app.lang.get(deficitLabelKey, 'Forecasts');

        // convert detailsForCase params to html template
        d.worst_details = this.detailsMsgTpl(this.getDetailsForCase('worst', this.worstTotal, d.quota_amount, d.closed_amount, fromModel));
        d.likely_details = this.detailsMsgTpl(this.getDetailsForCase('likely', this.likelyTotal, d.quota_amount, d.closed_amount, fromModel));
        d.best_details = this.detailsMsgTpl(this.getDetailsForCase('best', this.bestTotal, d.quota_amount, d.closed_amount, fromModel));

        if(this.shouldRollup && !_.isEmpty(this.selectedUser.reports_to_id)) {
            d.quota_label = app.lang.get('LBL_QUOTA_ADJUSTED', 'Forecasts');
        } else {
            d.quota_label = app.lang.get('LBL_QUOTA', 'Forecasts');
        }

        if(this.context || this.model) {
            var model = this.context.get('model') || this.model;
            if(model) {
                model.set(d);
                this.renderSubDetails();
            }
        }
    },

    /**
     * Determine if one value is bigger than another then build the language string to be used
     *
     * @param caseStr case string "likely", "best", or "worst"
     * @param caseValue the value of the case
     * @param stageValue the value of the quota or closed amount
     * @param closedAmt the value of closed_amount from the model
     * @param fromServer if this is coming from the model or the server
     * @return {Object} params for details-msg template
     */
    getDetailsForCase: function (caseStr, caseValue, stageValue, closedAmt, fromModel) {
        var params = {},
            // get Number versions of values for comparison
            caseValueN = app.math.add(caseValue, closedAmt),
            stageValueN = parseFloat(stageValue),
            openPipeline = 0,
            calcValue = 0;

        params.label = app.lang.get('LBL_' + caseStr.toUpperCase(), 'Forecasts');
        params.spanCSS = this.spanCSS;
        params.case = caseStr;
        params.shortOrExceed = '&nbsp;';
        params.openPipeline = '&nbsp;';
        params.feedbackLn1 = '';
        params.feedbackLn2 = '';

        var hasAccess = true;
        // if this is the rep view and the user doesnt have access to this field set to false
        if(!this.shouldRollup && !this.fieldDataAccess[caseStr]) {
            hasAccess = false;
        }
        // Check field access, in 2 of 3 cases below this works, otherwise it gets overwritten
        // in caseValueN == 0 && stageValueN == 0
        if(hasAccess)
        {
            if (caseValue) {
                params.amount = app.currency.formatAmountLocale(caseValue);
                params.labelAmount = params.label + ': ' + params.amount.toString();
            }

            if (caseValueN == 0 && stageValueN == 0) {
                // if we have no data
                params.amount = app.lang.get('LBL_FORECAST_DETAILS_NO_DATA', "Forecasts");
            } else if (caseValueN != 0 && stageValueN != 0 && caseValueN == stageValueN) {
                // if the values are equal but we have data
                params.shortOrExceed = app.lang.get('LBL_FORECAST_DETAILS_MEETING_QUOTA', "Forecasts");
            } else {
                /**
                 *  The bottom rectangles are supposed to tell the user if there is sufficient open pipeline in the current quarter to cover the deficit or not.
                 *  If there is surplus (meaning there is no deficit), the open pipeline will actually take the user above quota and that is always represented with green color.
                 */
                //if we are exceeding, we need to subtract to get the amount we exceed by
                if (caseValueN > stageValueN) {
                    params.shortOrExceed = app.lang.get('LBL_FORECAST_DETAILS_EXCEED', "Forecasts");
                    calcValue = app.math.sub(caseValueN, stageValueN);
                } else {
                    params.shortOrExceed = app.lang.get('LBL_FORECAST_DETAILS_SHORT', "Forecasts");
                    calcValue = app.math.sub(stageValueN, caseValueN);
                }

                params.percent = this.getPercent(calcValue, stageValueN);
                params.openPipeline = '(' + app.currency.formatAmountLocale(calcValue) + ')';

            }

            params.feedbackLn1 = params.shortOrExceed;

            if (params.percent) {
                params.feedbackLn1 += ' ' + params.percent;
            }

            params.feedbackLn2 = params.openPipeline;
        } else {
            params.amount = this.noDataAccessTemplate;
            params.labelAmount = params.label + ': ' + app.lang.get('LBL_NO_FIELD_ACCESS');
        }

        return params;
    },

    /**
     * Return the difference of two values and make sure it's a positive value
     *
     * used as a shortcut function for determine best/likely to closed/quota
     * @param caseValue
     * @param stageValue
     * @return {Number}
     */
    getAbsDifference: function (caseValue, stageValue) {
        return app.currency.formatAmountLocale(Math.abs(stageValue - caseValue));
    },

    /**
     * Gets a css class based on the amount relative to stageValue
     *
     * @param {Number} caseValue the value to check
     * @param {Number} stageValue the value to check against
     * @param {String} type the property to get
     * @return {string}
     */
    getClassBasedOnAmount: function (caseValue, stageValue, type) {
        var cssClass = '';
        // convert values to Numbers for comparison
        caseValue = parseFloat(caseValue);
        stageValue = parseFloat(stageValue);
        if(type == 'color') {
            if(caseValue == stageValue) {
                //
            } else if(caseValue > stageValue) {
                cssClass = 'font-green';
            } else {
                cssClass = 'font-red'
            }
        } else if(type == 'background-color') {
            if(caseValue == stageValue) {
                cssClass = 'grayLight';
            } else if(caseValue > stageValue) {
                cssClass = 'green';
            } else {
                cssClass = 'red';
            }
        }

        return cssClass;
    },

    /**
     * Returns a percent string based on the best/likely/worst case number vs. quota/closed amount
     *
     * @param caseValue likely/best/worst case value
     * @param stageValue the closed/quota amount from the model
     * @return {String}
     */
    getPercent: function (caseValue, stageValue) {
        var percent = 0,
            calcValue = caseValue;
        if (stageValue > 0 && caseValue > 0) {


            // divide the numbers and multiply times 100
            percent = (calcValue / stageValue) * 100;

            if (percent > 1) {
                // round to a whole number
                percent = Math.round(percent);
            } else {
                // Round the less-than-one percent to two decimal places
                // eg. percent=0.1234 -- percent*100 = 12.34, Math.round makes that 12
                // then percent/100 makes that back to 0.12
                percent = Math.round(percent*100)/100;
            }
        }
        return Math.abs(percent) + '%';
    },

    /**
     * Checks the selectedUser to see if they are a sub-manager
     */
    checkShowTargetQuota: function() {
        if(this.shouldRollup && this.selectedUser.is_manager && !this.selectedUser.is_top_level_manager) {
            this.showTargetQuota = true;
        } else {
            this.showTargetQuota = false;
        }
    },

    /**
     * checks the selectedUser to make sure it's a manager and if we should show the manager view
     * @return {Boolean}
     */
    isManagerView: function () {
        var isMgrView = false;
        if (this.currentModule == 'Forecasts') {
            // Forecasts has a more dynamic state, so make sure we check which worksheet is showing now
            if (this.context && this.context.parent && this.context.parent.has('model')) {
                isMgrView = this.context.parent.get('model').get('forecastType') == 'Rollup';
            }
        } else if(this.selectedUser.is_manager == true
            && (this.selectedUser.showOpps == undefined || this.selectedUser.showOpps === false))
        {
            isMgrView = true;
        }

        return isMgrView;
    },

    /**
     * Set the new time period
     *
     * @param {String} timePeriod id in string form
     */
    updateDetailsForSelectedTimePeriod: function (timePeriod) {
        // setting the model will trigger loadData()
        this.model.set({selectedTimePeriod: timePeriod});
    },

    /**
     * Set the new selected user
     *
     * @param {Object} selectedUser
     */
    updateDetailsForSelectedUser: function (selectedUser) {
        // don't directly set model selectedUser so we can handle selectedUser param in case it comes in as
        // just an id or something from somewhere else, so we can set it the right way for this dashlet
        this.selectedUser.last_name = selectedUser.last_name;
        this.selectedUser.first_name = selectedUser.first_name;
        this.selectedUser.full_name = selectedUser.full_name;
        this.selectedUser.id = selectedUser.id;
        this.selectedUser.is_manager = selectedUser.is_manager;
        this.selectedUser.reportees = selectedUser.reportees;
        this.selectedUser.showOpps = selectedUser.showOpps;
        this.selectedUser.user_name = selectedUser.user_name;
        this.selectedUser.reports_to_id = selectedUser.reports_to_id;
        this.selectedUser.reports_to_name = selectedUser.reports_to_name;
        this.selectedUser.is_top_level_manager = selectedUser.is_top_level_manager;

        this.shouldRollup = this.isManagerView();

        // update showTargetQuota on every user change
        this.checkShowTargetQuota();

        // setting the model will trigger loadData()
        this.model.set({selectedUser: selectedUser});
    },

    /**
     * Event handler to update which dataset is used.
     *
     * @param {jQuery.Event} evt click event
     */
    changeDisplayOptions : function(evt) {
        evt.preventDefault();
        this.handleOptionChange(evt);
    },

    /**
     * Handle the click event for the options menu
     *
     * @param {jQuery.Event} evt click event
     */
    handleOptionChange: function(evt) {
        var $el = $(evt.currentTarget),
            changedSegment = $el.attr('data-set');

        //check what needs to be done to the target
        if($el.hasClass('checked')) {
            //item was checked, uncheck it
            $el.removeClass('checked');
            $('div .projected_' + changedSegment).hide();
        } else {
            //item was unchecked and needs checked now
            $el.addClass('checked');
            $('div .projected_' + changedSegment).show();
        }
    }
})
