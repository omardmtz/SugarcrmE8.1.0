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
 * @class View.Views.Base.ForecastdetailsRecordView
 * @alias SUGAR.App.view.views.BaseForecastdetailsRecordView
 * @extends View.Views.Base.ForecastdetailsView
 */
({
    extendsFrom: 'ForecastdetailsView',

    /**
     * Holds the logged-in user's ID
     */
    selectedUserId: '',

    /**
     * Holds the current timeperiod object
     */
    currentTP: undefined,

    /**
     * Holds the business card's model's timeperiod object (original Opps TP)
     */
    modelTP: undefined,

    /**
     * Holds a reference to the RevenueLineItems subpanel collection
     */
    rliCollection: undefined,

    /**
     * An array of the RLI ids that go into Closed Won
     */
    closedWonIds: [],

    /**
     * An array of the RLI ids that are included in likely/best/worst values
     */
    includedIds: [],

    /**
     * An array of the RLI ids that are included in likely/best/worst values in this timeperiod
     */
    includedIdsInTP: [],

    /**
     * Holds Sales Stage values that get added to Closed Won amounts
     */
    salesStageWon: [],

    /**
     * Array of commit_stages that are included in totals
     */
    commitStagesInIncludedTotal: [],

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this.selectedUserId = app.user.get('id');
        this._super('initialize', [options]);

        var config = app.metadata.getModule('Forecasts', 'config') || {};

        this.salesStageWon = config.sales_stage_won || ['Closed Won'];
        var forecastRanges = config.forecast_ranges || [];

        this.modelTP = new Backbone.Model();

        if (forecastRanges == 'show_custom_buckets') {
            var ranges = config[forecastRanges + '_ranges'] || [];
            _.each(ranges, function(value, key) {
                if (!_.isUndefined(value.in_included_total) && value.in_included_total) {
                    this.commitStagesInIncludedTotal.push(key);
                }
            });
        } else {
            this.commitStagesInIncludedTotal.push('include');
        }
    },

    /**
     * Builds dashlet url
     * @override
     * @return {String} url to call
     */
    getProjectedURL: function() {
        var method = this.shouldRollup ? "progressManager" : "progressRep",
            url = 'Forecasts/' + this.model.get('selectedTimePeriod') + '/' + method + '/' + this.selectedUserId,
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
    renderSubDetails: function() {
        // clear the footer class
        var subEl = this.$el.find('.forecast-details');
        if(subEl && subEl.hasClass('block-footer')) {
            subEl.removeClass('block-footer');
        }

        if(this.currentModule != 'Opportunities'
            || (this.currentModule == 'Opportunities' && this.model.get('selectedTimePeriod'))) {
            this._super('renderSubDetails');
        } else {
            subEl.addClass('block-footer');
            subEl.html(app.lang.get('LBL_NO_DATA_AVAILABLE'));
        }
    },

    /**
     * @inheritdoc
     */
    bindDataChange: function() {
        if (this.meta.config || !this.isForecastSetup) {
            return;
        }

        // reset closedWonIds
        this.closedWonIds = [];
        this.includedIds = [];
        this.includedIdsInTP = [];

        var ctx = this.context.parent || this.context,
            ctxMdl = ctx.get('model');

        ctxMdl.on('sync', function(model) {
            this.fetchNewTPByDate(model.get('date_closed'), undefined, true);
        }, this);

        if(this.currentModule == 'Opportunities') {

            this.rliCollection = app.utils.getSubpanelCollection(ctx, 'RevenueLineItems');

            if(this.rliCollection) {
                // because the user may be loading this dashlet by switching
                // between the Help dashboard and a user's dashboard,
                // process the collection now to init the dashlet properly
                this.processRLICollection(this.rliCollection);
                
                this.rliCollection.on('reset', this.processRLICollection, this);

                this.rliCollection.on('change:likely_case change:best_case change:worst_case change:amount', this.processCases, this);

                this.rliCollection.on('change:sales_stage', this.processSalesStage, this);

                this.rliCollection.on('change:commit_stage', this.processCommitStage, this);

                this.rliCollection.on('change:date_closed', this.checkFetchNewTPByDate, this);

                ctx.on('editablelist:cancel', function(a,b,c) {
                    // no way to really tell what all manual math we've done, so when the row
                    // gets cancelled, just completely reload the data
                    this.loadData();
                }, this);

                /**
                 * Init the Opp record TimePeriod model to receive new TP data and set a change listener
                 * so we can update which model IDs are included and in the actual timeperiod
                 */
                this.modelTP.on('change', function(model) {
                    var rliModel;

                    // empty array
                    this.includedIdsInTP = [];

                    _.each(this.includedIds, function(id) {
                        rliModel = this.rliCollection.get(id);
                        // check to see if this RLI's date is inside the current Opp timeperiod
                        if(this.isDateInTimePeriod(rliModel.get('date_closed'), this.modelTP.toJSON())) {
                            this.includedIdsInTP.push(rliModel.get('id'));
                        }
                    }, this);
                }, this);
            }
        } else if(this.currentModule == 'RevenueLineItems') {
            // RLI only listeners

            // set up closedWonIds when we change dashboards
            this.initClosedWonIds(ctxMdl);

            ctxMdl.on('change:likely_case change:best_case change:worst_case change:amount', this.processCases, this);

            ctxMdl.on('change:sales_stage', this.processSalesStage, this);

            ctxMdl.on('change:commit_stage', this.processCommitStage, this);

            this.context.parent.on('button:cancel_button:click', function(model, date) {
                var ctx = this.context.parent || this.context,
                    ctxModel = ctx.get('model'),
                    options = {
                        beforeParseData: _.bind(this.addModelTotalsToServerData, this, ctxModel)
                    };
                // no way to really tell what all manual math we've done, so when the row
                // gets cancelled, just completely reload the data
                this.loadData(options);
            }, this);

            ctxMdl.on('change:date_closed', this.checkFetchNewTPByDate, this);

            ctxMdl.on('sync', function(model) {
                // updates our lhsData when the user saves the model
                if(_.has(ctxMdl.attributes, 'lhsData')) {
                    ctxMdl.set({
                        lhsData: {
                            best: model.get('best_case'),
                            likely: model.get('likely_case'),
                            worst: model.get('worst_case')
                        }
                    });
                }
            }, this);

            // Using LHS Model to store the initial values of the LHS model so we don't have
            // to ping the server every dashlet load for the true original DB values of the LHS model
            if(!_.has(ctxMdl.attributes, 'lhsData')) {
                ctxMdl.set({
                    lhsData: {
                        best: ctxMdl.get('best_case'),
                        likely: ctxMdl.get('likely_case'),
                        worst: ctxMdl.get('worst_case')
                    }
                });
            }

            if(_.contains(this.closedWonIds, ctxMdl.get('id'))) {
                this.processSalesStage(ctxMdl);
            }
        }
    },

    /**
     * @inheritdoc
     *
     * @override just calls calculateData on it's own instead of going back to the parent
     */
    handleNewDataFromServer: function(data) {
        // since the user might add this dashlet after they have changed the RLI model, but before they saved it
        // we have to check and make sure that we're accounting for any changes in the dashlet totals that come
        // from the server, but only if this has not already been parsed by a beforeParseData function
        if(this.currentModule == 'RevenueLineItems' && this.context && _.isUndefined(data.parsedData)) {
            var mdl = this.context.parent.get('model') || this.context.get('model'),
                lhsData = mdl.get('lhsData');

            if(lhsData.likely != mdl.get('likely_case')) {
                data.amount = data.amount - (lhsData.likely - mdl.get('likely_case'));
            }
            if(lhsData.best != mdl.get('best_case')) {
                data.best_case = data.best_case - (lhsData.best - mdl.get('best_case'));
            }
            if(lhsData.worst != mdl.get('worst_case')) {
                data.worst_case = data.worst_case - (lhsData.worst - mdl.get('worst_case'));
            }
        }

        this.calculateData(this.mapAllTheThings(data, false));
    },

    /**
     * Processes this.rliCollection.models to determine which models IDs should be
     * saved into the closedWonIds array
     */
    processRLICollection: function(collection) {
        this.oldTotals.models = new Backbone.Model();
        _.each(this.rliCollection.models, function(model) {
            // save all the initial likely values
            this.oldTotals.models.set(model.get('id'), {
                likely: model.get('likely_case'),
                best: model.get('best_case'),
                worst: model.get('worst_case')
            });
            this.initClosedWonIds(model);
        }, this);

        if(collection) {
            // if this is coming from the rliCollection reset, fetch server data
            this.loadData();
        }
    },

    /**
     * @inheritdoc
     */
    unbindData: function() {
        if(this.context.parent) {
            this.context.parent.off(null, null, this);
            if(this.context.parent.get('model')) {
                this.context.parent.get('model').off(null, null, this);
            }
        }
        if(this.context) {
            this.context.off(null, null, this);
            if(this.context.get('model')) {
                this.context.get('model').off(null, null, this);
            }
        }

        if(this.currentModule == 'Opportunities' && this.rliCollection) {
            this.rliCollection.off(null, null, this);
            this.rliCollection = undefined;
        }

        app.view.View.prototype.unbindData.call(this);
    },

    /**
     * Handles when likely/best/worst case changes, processes numbers and does math before sending
     * to calculateTotals
     *
     * @param {Backbone.Model} model the RLI/Opp model
     */
    processCases: function(model) {
        // model is undefined when users change currency symbols,
        // it throws a change:best_case but there's no model
        if(!_.isUndefined(model) && (app.user.get('id') == model.get('assigned_user_id'))
            && _.contains(this.includedIds, model.get('id'))) {
            var data = _.clone(model.toJSON()),
                diff = 0,
                old = 0,
                totals = {};

            if(this.currentModule == 'Opportunities') {
                // if amount is not undefined, push amount into likely_case
                data.likely_case = (!_.isUndefined(data.amount)) ? data.amount : data.likely_case;
                totals = this.getOldTotalFromCollectionById(model.get('id'));
            } else {
                totals = this.oldTotals;
            }

            // process numbers before parent calculateData
            if(_.has(model.changed, 'likely_case') || _.has(model.changed, 'amount')) {
                data.likely_case = (_.isNaN(Number(data.likely_case)) || _.isEqual(data.likely_case, '')) ? 0 : data.likely_case;
                old = data.likely_case;
                diff = app.math.sub(data.likely_case, totals.likely);
                data.likely_case = app.math.add(this.likelyTotal, diff);
                totals.likely = old;
            } else {
                data.likely_case = this.likelyTotal;
            }

            if(_.has(model.changed, 'best_case')) {
                data.best_case = (_.isNaN(Number(data.best_case)) || _.isEqual(data.best_case, '')) ? 0 : data.best_case;
                old = data.best_case;
                diff = app.math.sub(data.best_case, totals.best);
                data.best_case = app.math.add(this.bestTotal, diff);
                totals.best = old;
            } else {
                data.best_case = this.bestTotal;
            }

            if(_.has(model.changed, 'worst_case')) {
                data.worst_case = (_.isNaN(Number(data.worst_case)) || _.isEqual(data.worst_case, '')) ? 0 : data.worst_case;
                old = data.worst_case;
                diff = app.math.sub(data.worst_case, totals.worst);
                data.worst_case = app.math.add(this.worstTotal, diff);
                totals.worst = old;
            } else {
                data.worst_case = this.worstTotal;
            }

            // set oldTotals back
            if(this.currentModule == 'Opportunities') {
                this.setOldTotalFromCollectionById(model.get('id'), totals);
            } else {
                this.oldTotals = totals;
            }

            return this.calculateData(this.mapAllTheThings(data, true));
        }
    },

    /**
     * Checks a model to see if it should be added to closedWonIds
     *
     * @param {Backbone.Model} model
     */
    initClosedWonIds: function(model) {
        var ss = model.get('sales_stage'),
            cs = model.get('commit_stage'),
            id = model.get('id');

        // If this model's sales_stage and commit_stage both are included in Closed Won totals
        // and the id isnt already in closedWonIds
        if(_.contains(this.salesStageWon, ss)
            && _.contains(this.commitStagesInIncludedTotal, cs)
            && !_.contains(this.closedWonIds, id)) {
            this.closedWonIds.push(id);
        }

        // If this model's commit_stage is included in included totals
        // and the id isnt already in includedIds
        if(_.contains(this.commitStagesInIncludedTotal, cs) && !_.contains(this.includedIds, id)) {
            this.includedIds.push(id);
        }
    },

    /**
     * Process model changes when sales_stage is changed
     * @param model
     */
    processSalesStage: function(model) {
        var shouldBeIncluded = false,
            updatedData = false,
            ss = model.get('sales_stage'),
            cs = model.get('commit_stage'),
            id = model.get('id');

        if(_.contains(this.salesStageWon, ss) && _.contains(this.commitStagesInIncludedTotal, cs)) {
            shouldBeIncluded = true;
        }

        // If the ID was already included in the totals, and now should not be
        if(_.contains(this.closedWonIds, id) && !shouldBeIncluded) {
            // remove the model's ID from the array
            this.closedWonIds = _.without(this.closedWonIds, id);

            // remove this model's likely from the closed won amount
            this.serverData.set({closed_amount: app.math.sub(this.serverData.get('closed_amount'), model.get('likely_case'))});

            updatedData = true;
        } else if(!_.contains(this.closedWonIds, id) && shouldBeIncluded) {
            // model needs to be included in closed_amount
            this.closedWonIds.push(id);

            // add likely amount to closed won
            this.serverData.set({closed_amount: app.math.add(this.serverData.get('closed_amount'), model.get('likely_case'))});

            updatedData = true;
        }

        if(updatedData) {
            // update the calculations
            this.calculateData(this.serverData.toJSON());
        }
    },

    /**
     * Processes a model to see if it should be add/subtracted from likely/best/worst totals
     * based on it's commit_stage
     *
     * @param model
     */
    processCommitStage: function(model) {
        var shouldBeIncluded = false,
            updatedData = false,
            cs = model.get('commit_stage'),
            id = model.get('id');

        if(_.contains(this.commitStagesInIncludedTotal, cs)) {
            shouldBeIncluded = true;
        }

        // If the ID was already included in the totals, and now should not be
        if(_.contains(this.includedIds, id) && !shouldBeIncluded) {
            // remove the model's ID from the array
            this.includedIds = _.without(this.includedIds, id);

            // remove amounts from best/likely/worst
            this.serverData.set({
                likely: app.math.sub(this.serverData.get('likely'), model.get('likely_case')),
                best: app.math.sub(this.serverData.get('best'), model.get('best_case')),
                worst: app.math.sub(this.serverData.get('worst'), model.get('worst_case'))
            });

            updatedData = true;
        } else if(!_.contains(this.includedIds, id) && shouldBeIncluded) {
            // model needs to be included in closed_amount
            this.includedIds.push(id);

            // add amounts to best/likely/worst
            this.serverData.set({
                likely: app.math.add(this.serverData.get('likely'), model.get('likely_case')),
                best: app.math.add(this.serverData.get('best'), model.get('best_case')),
                worst: app.math.add(this.serverData.get('worst'), model.get('worst_case'))
            });
            updatedData = true;
        }

        if(updatedData) {
            // update the calculations
            this.calculateData(this.serverData.toJSON());
        }
    },

    /**
     * Given a model that had its closed_date field changed, check to see if we need to
     * fetch a new timeperiod or not by the date changed and which module we're in
     *
     * @param {Backbone.Model} model the changed model
     */
    checkFetchNewTPByDate: function(model) {
        var newDate = model.get('date_closed'),
            shouldFetch = false,
            inTimePeriod = this.isDateInTimePeriod(newDate, this.modelTP.toJSON()),
            options = {},
            inOpps = (this.currentModule == 'Opportunities'),
            modelId = model.get('id');

        if(!inOpps) {
            // RevenueLineItems

            // always fetch for RLIs
            shouldFetch = true;

            if(!inTimePeriod) {
                // since we don't have parent/Opp data available here, whatever TP the new closed date
                // falls in should be fetched and this new total added to it if it isn't already included
                // after fetching, add this model to the server data that comes back
                options.beforeParseData = _.bind(this.addModelTotalsToServerData, this, model);
            }
        } else {
            // Opportunities
            var alreadyInTP = _.contains(this.includedIdsInTP, modelId),
                newTotals;

            // check if date falls outside current timeperiod, if outside of current timeperiod
            // we need to fetch new timeperiod & projected data
            if(inTimePeriod) {
                // check if RLI is being moved into the TP,
                // if it has already been in the TP, dont do anything
                if(!alreadyInTP) {
                    // item has been moved into the TP

                    // add model ID to included ids in timeperiod
                    this.includedIdsInTP.push(modelId);

                    // fetch new TP based on the new date if user changed item's date
                    // to be outside & after the current timeperiod
                    shouldFetch = true;

                    // after fetching, add this model to the server data that comes back
                    options.beforeParseData = _.bind(this.addModelTotalsToServerData, this, model);
                }
            } else {
                // date is not inside the current timeperiod

                // check if the newDate is before or after the current TP
                if (app.date(newDate).isAfter(this.modelTP.get('end_date'))) {
                    // handle if date is after model (Opportunity) timeperiod

                    // fetch new TP based on the new date if user changed item's date
                    // to be outside & after the current timeperiod
                    shouldFetch = true;

                    // after fetching, add this model to the server data that comes back
                    options.beforeParseData = _.bind(this.addModelTotalsToServerData, this, model);
                } else if (app.date(newDate).isBefore(this.modelTP.get('start_date'))) {
                    // handle if date is before model (Opportunity) timeperiod

                    // check if this RLI has already been inside the TP
                    if(alreadyInTP) {
                        // RLI was in the TP, but is being moved out to before the TP

                        // check to see if this is the last RLI in the TP
                        var isLastRLIInTP = (this.includedIdsInTP.length === 1 && this.includedIdsInTP[0] === modelId);

                        if(isLastRLIInTP) {
                            // since this is the last RLI in the timeperiod, when we move this to an
                            // older timeperiod, fetch new TP based on the new date
                            shouldFetch = true;

                            // after fetching, add this model to the server data that comes back
                            options.beforeParseData = _.bind(this.addModelTotalsToServerData, this, model);
                        } else {
                            // item has been moved out of the TP, but other RLIs in the TP are keeping
                            // the dashlet from updating, so subtract the model totals from the current TP totals
                            newTotals = this.removeModelTotalsFromServerData(model, this.serverData.toJSON());
                            this.calculateData(this.mapAllTheThings(newTotals));
                        }
                    } else {
                        // if trying to move the RLI to a timeperiod before the Opportunity timeperiod start date
                        // set the date to the same start date as the Opp so we don't pull an older timeperiod
                        newDate = this.modelTP.get('start_date');

                        // fetch new TP
                        shouldFetch = true;
                    }
                }

                // if this model is already in the timeperiod, remove it
                if(alreadyInTP) {
                    this.includedIdsInTP = _.without(this.includedIdsInTP, modelId);
                }
            }
        }

        // if we should fetch a new timeperiod, make the call
        if(shouldFetch) {
            this.fetchNewTPByDate(newDate, options);
        }
    },

    /**
     * Given a date, this function makes a call to TimePeriods/<date> to get the whole timeperiod bean
     *
     * @param {string} date the date to use to search for the new timeperiod
     * @param {Backbone.Model} [model] param isn't used but is passed when the model changes
     * @param {boolean} [updateModelTP] if we need to update the modelTP or not
     */
    fetchNewTPByDate: function(date, options, updateModelTP) {
        app.api.call('GET', app.api.buildURL('TimePeriods/' + date), null, {
            success: _.bind(function(data) {
                // Make sure the model is here when we get back and this isn't mid-pageload or anything
                if(this.model) {
                    // if we're updating the model timeperiod
                    if(updateModelTP) {
                        // if the Opp model changed, update the model's TP
                        this.modelTP.set(_.clone(data));
                    }

                    this.currentTP = data;
                    this.model.set({selectedTimePeriod: data.id}, {silent: true});
                    this.loadData(options);
                }
            }, this)
        });
    },

    /**
     * Adds the model's likely/best/worst totals to the data totals
     *
     * @param {Backbone.Model} model the model with values to add to server data
     * @param {Object} data values being returned from the server endpoint with totals
     * @return {Object} returns the data Object back with updated totals
     */
    addModelTotalsToServerData: function(model, data) {
        // if these totals haven't already been added into the data from the server
        // occurs when an RLI was previously saved outside of a timeperiod and is being
        // brought back into this timeperiod. If the RLI *did* start in the current TP
        // and has been moved around and is being brought back in, then don't re-add the totals
        if(!_.contains(data.includedIdsInLikelyTotal, model.get('id'))) {
            data.amount = app.math.add(data.amount, model.get('likely_case'));
            data.best_case = app.math.add(data.best_case, model.get('best_case'));
            data.worst_case = app.math.add(data.worst_case, model.get('worst_case'));
        }

        return data;
    },

    /**
     * Removes the model's likely/best/worst totals from the data totals
     *
     * @param {Backbone.Model} model the model with values to remove from server data
     * @param {Object} data values being returned from the server endpoint with totals
     * @return {Object} returns the data Object back with updated totals
     */
    removeModelTotalsFromServerData: function(model, data) {
        data.amount = app.math.sub(data.amount, model.get('likely_case'));
        data.best_case = app.math.sub(data.best_case, model.get('best_case'));
        data.worst_case = app.math.sub(data.worst_case, model.get('worst_case'));

        return data;
    },

    /**
     * Called during initialize to fetch any relevant data
     *
     * @override
     * @param options
     */
    getInitData: function(options) {
        // This needs to be set going into the function. loadData() checks to see if the init data is loaded and
        // makes an async call to load it, causing this function to be called multiple times until something
        // returns and sets initDataLoaded to be false.  Since everything is async, we are safe to set this on function
        // call to indicate that it HAS been called, eliminating extra api calls.
        this.initDataLoaded = true;

        var ctx = this.context.parent || this.context,
            ctxModel = ctx.get('model'),
            date = ctxModel.get('date_closed');

        // set selectedUser id for progress endpoint param
        this.selectedUser.id = ctxModel.get('assigned_user_id');

        // set old totals in case they change
        this.oldTotals = _.extend(this.oldTotals, {
            best: ctxModel.get('best_case'),
            likely: ctxModel.get('likely_case') || ctxModel.get('amount'),
            worst: ctxModel.get('worst_case')
        });

        if(!_.isEmpty(date)) {
            // get the current timeperiod
            app.api.call('GET', app.api.buildURL('TimePeriods/' + date), null, {
                success: _.bind(function(data) {
                    if(this.model) {
                        // update the initial timeperiod
                        this.modelTP.set(_.clone(data));

                        this.currentTP = data;
                        this.model.set({selectedTimePeriod: data.id}, {silent: true});
                        this.loadData();
                    }
                }, this),
                complete: options ? options.complete : null
            });
        } else {
            // this model doesn't have a selectedTimePeriod yet, so use the current date
            var d = new Date(),
                month = (d.getUTCMonth().toString().length == 1) ? '0' + d.getUTCMonth() : d.getUTCMonth(),
                day = (d.getUTCDate().toString().length == 1) ? '0' + d.getUTCDate() : d.getUTCDate()
            date = d.getFullYear() + '-' + month + '-' + day;
            this.fetchNewTPByDate(date);
        }
    },

    /**
     * Checks a given date from the datepicker against the start/end timestamps of the current
     * timeperiod to see if the user selected a date that needs new data
     *
     * @param {string} date the date we're checking to see if it falls inside the timePeriod
     * @param {Object} timePeriod this is the timeperiod Object to check against
     * @return {boolean} true if a new timeperiod should be fetched from server
     */
    isDateInTimePeriod: function(date, timePeriod) {
        var inTimePeriod = false;

        // check if date is between the timePeriod
        if (app.date(date).isBetween(timePeriod.start_date, timePeriod.end_date)) {
            inTimePeriod = true;
        }

        return inTimePeriod;
    }
})
