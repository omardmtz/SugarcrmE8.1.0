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
 * @class View.Fields.Base.Forecasts.QuotapointField
 * @alias SUGAR.App.view.fields.BaseForecastsQuotapointField
 * @extends View.Fields.Base.BaseField
 */
({

    /**
     * The quota amount to display in the UI
     */
    quotaAmount: undefined,

    /**
     * The current selected user object
     */
    selectedUser: undefined,

    /**
     * The current selected timeperiod id
     */
    selectedTimePeriod: undefined,

    /**
     * Hang on to the user-preferred currency id for formatting
     */
    userCurrencyID: undefined,

    /**
     * Used by the resize function to wait a certain time before adjusting
     */
    resizeDetectTimer: undefined,

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        app.view.Field.prototype.initialize.call(this, options);

        this.quotaAmount = 0.00;
        this.selectedUser = this.context.get('selectedUser');
        this.selectedTimePeriod = this.context.get('selectedTimePeriod');
        this.userCurrencyID = app.user.getPreference('currency_id');

        //if user resizes browser, adjust datapoint layout accordingly
        $(window).on('resize.datapoints', _.bind(this.resize, this));
        this.on('render', function() {
            this.resize();
            return true;
        }, this);
    },

    /**
     * @inheritdoc
     */
    bindDataChange: function() {
        this.context.on('change:selectedUser', function(ctx, user) {
            this.selectedUser = user;

            // reload data when the selectedTimePeriod changes
            this.loadData({});
        }, this);

        this.context.on('change:selectedTimePeriod', function(ctx, timePeriod) {
            this.selectedTimePeriod = timePeriod;

            // reload data when the selectedTimePeriod changes
            this.loadData({});
        }, this);

        this.loadData();
    },

    /**
     * If this is a top-level manager, we need to add an event listener for
     * forecasts:worksheet:totals so the top-level manager's quota can update live
     * with changes done in the manager worksheet reflected here
     *
     * @param isTopLevelManager {Boolean} if the user is a top-level manager or not
     */
    toggleTotalsListeners: function(isTopLevelManager) {
        if(isTopLevelManager) {
            this.hasListenerAdded = true;
            // Only for top-level manager whose quota can change on the fly
            this.context.on('forecasts:worksheet:totals', function(totals) {
                var quota = 0.00;
                if(_.has(totals, 'quota')) {
                    quota = totals.quota;
                } else {
                    quota = this.quotaAmount;
                }
                this.quotaAmount = quota;
                if (!this.disposed) {
                    this.render();
                }
            }, this);
            // if we're on the manager worksheet view, get the collection and calc quota
            if(!this.selectedUser.showOpps) {
                // in case this gets added after the totals event was dispatched
                var collection = app.utils.getSubpanelCollection(this.context, 'ForecastManagerWorksheets'),
                    quota = 0.00;

                _.each(collection.models, function(model) {
                    quota = app.math.add(quota, model.get('quota'));
                }, this);
                this.quotaAmount = quota;
                this.render();
            }
        } else if(this.hasListenerAdded) {
            this.hasListenerAdded = false;
            this.context.off('forecasts:worksheet:totals', null, this);
        }
    },

    /**
     * Builds widget url
     *
     * @return {*} url to call
     */
    getQuotasURL: function() {
        var method = (this.selectedUser.is_manager && this.selectedUser.showOpps) ? 'direct' : 'rollup',
            url = 'Forecasts/' + this.selectedTimePeriod + '/quotas/' + method + '/' + this.selectedUser.id;

        return app.api.buildURL(url, 'read');
    },

    /**
     * Overrides loadData to load from a custom URL
     *
     * @inheritdoc
     */
    loadData: function(options) {
        var url = this.getQuotasURL(),
            cb = {
                context: this,
                success: this.handleQuotaData,
                complete: options ? options.complete : null
            };

        app.api.call('read', url, null, null, cb);
    },

    /**
     * Success handler for the Quotas endpoint, sets quotaAmount to returned values and updates the UI
     * @param quotaData
     */
    handleQuotaData: function(quotaData) {
        this.quotaAmount = quotaData.amount;

        // Check to see if we need to add an event listener to the context for the worksheet totals
        this.toggleTotalsListeners(quotaData.is_top_level_manager);

        // update the UI
        if (!this.disposed) {
            this.render();
        }
    },

    /**
     * Adjusts the layout
     */
    adjustDatapointLayout: function(){
        if(this.view.$el) {
            var thisView$El = this.view.$el,
                parentMarginLeft = thisView$El.find(".topline .datapoints").css("margin-left"),
                parentMarginRight = thisView$El.find(".topline .datapoints").css("margin-right"),
                timePeriodWidth = thisView$El.find(".topline .span4").outerWidth(true),
                toplineWidth = thisView$El.find(".topline ").width(),
                collection = thisView$El.find(".topline div.pull-right").children("span"),
                collectionWidth = parseInt(parentMarginLeft) + parseInt(parentMarginRight);

            collection.each(function(index){
                collectionWidth += $(this).children("div.datapoint").outerWidth(true);
            });

            //change width of datapoint div to span entire row to make room for more numbers
            if((collectionWidth+timePeriodWidth) > toplineWidth) {
                thisView$El.find(".topline div.hr").show();
                thisView$El.find(".info .last-commit").find("div.hr").show();
                thisView$El.find(".topline .datapoints").removeClass("span8").addClass("span12");
                thisView$El.find(".info .last-commit .datapoints").removeClass("span8").addClass("span12");
                thisView$El.find(".info .last-commit .commit-date").removeClass("span4").addClass("span12");

            } else {
                thisView$El.find(".topline div.hr").hide();
                thisView$El.find(".info .last-commit").find("div.hr").hide();
                thisView$El.find(".topline .datapoints").removeClass("span12").addClass("span8");
                thisView$El.find(".info .last-commit .datapoints").removeClass("span12").addClass("span8");
                thisView$El.find(".info .last-commit .commit-date").removeClass("span12").addClass("span4");
                var lastCommitHeight = thisView$El.find(".info .last-commit .commit-date").height();
                thisView$El.find(".info .last-commit .datapoints div.datapoint").height(lastCommitHeight);
            }

            //adjust height of last commit datapoints
            var index = this.$el.index() + 1,
                width = this.$el.find("div.datapoint").outerWidth(),
                datapointLength = thisView$El.find(".info .last-commit .datapoints div.datapoint").length,
                sel = thisView$El.find('.last-commit .datapoints div.datapoint:nth-child('+index+')');
            if (datapointLength > 2 && index <= 2 || datapointLength == 2 && index == 1) {
                $(sel).width(width-18);
            }  else {
                $(sel).width(width);
            }
        }
    },

    /**
     * Sets a timer to adjust the layout
     */
    resize: function() {
        //The resize event is fired many times during the resize process. We want to be sure the user has finished
        //resizing the window that's why we set a timer so the code should be executed only once
        if (this.resizeDetectTimer) {
            clearTimeout(this.resizeDetectTimer);
        }
        this.resizeDetectTimer = setTimeout(_.bind(function() {
            this.adjustDatapointLayout();
        }, this), 250);
    }
})
