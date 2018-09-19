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
 * Datapoints in the info pane for Forecasts
 *
 * @class View.Fields.Base.Forecasts.DatapointField
 * @alias SUGAR.App.view.fields.BaseForecastsDatapointField
 * @extends View.Fields.Base.BaseField
 */
({

    /**
     * Tracking the type of totals we are seeing
     */
    previous_type: '',

    /**
     * Arrow Colors
     */
    arrow: '',

    /**
     * What was the first total we got for a given type
     */
    initial_total: '',

    /**
     * The total we want to display
     */
    total: 0,

    /**
     * Can we actually display this field and have the data binding on it
     */
    hasAccess: true,

    /**
     * Do we have access from the ForecastWorksheet Level to show data here?
     */
    hasDataAccess: true,

    /**
     * What to show when we don't have access to the data
     */
    noDataAccessTemplate: undefined,

    /**
     * Holds the totals field name
     */
    total_field: '',

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        this.total_field = this.total_field || this.name;

        this.hasAccess = app.utils.getColumnVisFromKeyMap(this.name, 'forecastsWorksheet');
        this.hasDataAccess = app.acl.hasAccess('read', 'ForecastWorksheets', app.user.get('id'), this.name);
        if (this.hasDataAccess === false) {
            this.noDataAccessTemplate = app.template.getField('base', 'noaccess')(this);
        }

        // before we try and render, lets see if we can actually render this field
        this.before('render', function() {
            if (!this.hasAccess) {
                return false;
            }
            // adjust the arrow
            this.arrow = this._getArrowIconColorClass(this.total, this.initial_total);
            this.checkIfNeedsCommit();
            return true;
        }, this);
        //if user resizes browser, adjust datapoint layout accordingly
        $(window).on('resize.datapoints', _.bind(this.resize, this));
        this.on('render', function() {
            if (!this.hasAccess) {
                return false;
            }
            this.resize();
            return true;
        }, this);
    },

    /**
     * Check to see if the worksheet needs commit
     */
    checkIfNeedsCommit: function() {
        // if the initial_total is an empty string (default value) don't run this
        if (!_.isEqual(this.initial_total, '') && app.math.isDifferentWithPrecision(this.total, this.initial_total)) {

            this.context.trigger('forecasts:worksheet:needs_commit', null);
        }
    },

    /**
     * @inheritdoc
     */
    _dispose: function() {
        $(window).off('resize.datapoints');

        // make sure we've cleared the resize timer before navigating away
        clearInterval(this.resizeDetectTimer);

        this._super('_dispose');
    },

    /**
     * Overwrite this to only place the placeholder if we actually have access to view it
     *
     * @return {*}
     */
    getPlaceholder: function() {
        if (this.hasAccess) {
            return this._super('getPlaceholder');
        }

        return '';
    },

    /**
     * Adjusts the CSS for the datapoint
     */
    adjustDatapointLayout: function() {
        if (this.hasAccess) {
            var parentMarginLeft = this.view.$('.topline .datapoints').css('margin-left'),
                parentMarginRight = this.view.$('.topline .datapoints').css('margin-right'),
                timePeriodWidth = this.view.$('.topline .span4').outerWidth(true),
                toplineWidth = this.view.$('.topline ').width(),
                collection = this.view.$('.topline div.pull-right').children('span'),
                collectionWidth = parseInt(parentMarginLeft) + parseInt(parentMarginRight);

            collection.each(function(index) {
                collectionWidth += $(this).children('div.datapoint').outerWidth(true);
            });

            //change width of datapoint div to span entire row to make room for more numbers
            if ((collectionWidth + timePeriodWidth) > toplineWidth) {
                this.view.$('.topline div.hr').show();
                this.view.$('.info .last-commit').find('div.hr').show();
                this.view.$('.topline .datapoints').removeClass('span8').addClass('span12');
                this.view.$('.info .last-commit .datapoints').removeClass('span8').addClass('span12');
                this.view.$('.info .last-commit .commit-date').removeClass('span4').addClass('span12');

            } else {
                this.view.$('.topline div.hr').hide();
                this.view.$('.info .last-commit').find('div.hr').hide();
                this.view.$('.topline .datapoints').removeClass('span12').addClass('span8');
                this.view.$('.info .last-commit .datapoints').removeClass('span12').addClass('span8');
                this.view.$('.info .last-commit .commit-date').removeClass('span12').addClass('span4');
                var lastCommitHeight = this.view.$('.info .last-commit .commit-date').height();
                this.view.$('.info .last-commit .datapoints div.datapoint').height(lastCommitHeight);
            }
            //adjust height of last commit datapoints
            var index = this.$el.index(),
                width = this.$('div.datapoint').outerWidth(),
                datapointLength = this.view.$('.info .last-commit .datapoints div.datapoint').length,
                sel = this.view.$('.last-commit .datapoints div.datapoint:nth-child(' + index + ')');
            if (datapointLength > 2 && index <= 2 || datapointLength == 2 && index == 1) {
                // RTL was off 1px
                var widthMod = (app.lang.direction === 'rtl') ? 7 : 8;
                $(sel).width(width - widthMod);
            } else {
                $(sel).width(width);
            }
        }
    },

    /**
     * Resizes the datapoint on window resize
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
    },

    /**
     * @inheritdoc
     */
    bindDataChange: function() {
        if (!this.hasAccess) {
            return;
        }

        this.context.on('change:selectedUser change:selectedTimePeriod', function() {
            this.initial_total = '';
            this.total = 0;
            this.arrow = '';
        }, this);

        // any time the main forecast collection is reset this contains the commit history
        this.collection.on('reset', this._onCommitCollectionReset, this);
        this.context.on('forecasts:worksheet:totals', this._onWorksheetTotals, this);
        this.context.on('forecasts:worksheet:committed', this._onWorksheetCommit, this);
    },

    /**
     * Collection Reset Handler
     * @param {Backbone.Collection} collection
     * @private
     */
    _onCommitCollectionReset: function(collection) {
        // get the first line
        var model = _.first(collection.models);
        if (!_.isUndefined(model)) {
            this.initial_total = model.get(this.total_field);
            if (!this.disposed) {
                this.render();
            }
        }
    },

    /**
     * Worksheet Totals Handler
     * @param {Object} totals       The totals from the worksheet
     * @param {String} type         Which worksheet are we dealing with it
     * @private
     */
    _onWorksheetTotals: function(totals, type) {
        var field = this.total_field;
        if (type == 'manager') {
            // split off '_case'
            field = field.split('_')[0] + '_adjusted';
        }
        this.total = totals[field];
        this.previous_type = type;

        if (!this.disposed) {
            this.render();
        }
    },

    /**
     * What to do when the worksheet is committed
     *
     * @param {String} type     What type of worksheet was committed
     * @param {Object} forecast What was committed for the timeperiod
     * @private
     */
    _onWorksheetCommit: function(type, forecast) {
        var field = this.total_field;
        if (type == 'manager') {
            // split off '_case'
            field = field.split('_')[0] + '_adjusted';
        }
        this.total = forecast[field];
        this.initial_total = forecast[field];
        this.arrow = '';
        if (!this.disposed) {
            this.render();
        }
    },

    /**
     * Returns the CSS classes for an up or down arrow icon
     *
     * @param {String|Number} newValue the new value
     * @param {String|Number} oldValue the previous value
     * @return {String} css classes for up or down arrow icons, if the values didn't change, returns ''
     * @private
     */
    _getArrowIconColorClass: function(newValue, oldValue) {
        var cls = '';

        // figure out if it changed here based
        if (app.math.isDifferentWithPrecision(newValue, oldValue)) {
            cls = (newValue > oldValue) ? ' fa-arrow-up font-green' : ' fa-arrow-down font-red';
        }
        return cls;
    }
})
