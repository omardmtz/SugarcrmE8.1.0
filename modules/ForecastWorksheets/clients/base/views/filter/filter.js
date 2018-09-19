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
 * @class View.Views.Base.ForecastsWorksheets.FilterView
 * @alias SUGAR.App.view.views.BaseForecastsWorksheetsFilterView
 * @extends View.View
 */
({
    /**
     * Front End Javascript Events
     */
    events: {
        'keydown .select2-input': 'disableSelect2KeyPress'
    },

    /**
     * Since we don't what the user to be able to type in the filter input
     * just disable all key press events for the .select2-input boxes
     *
     * @param event
     */
    disableSelect2KeyPress: function(event) {
        event.preventDefault();
    },

    /**
     * Key for saving the users last selected filters
     */
    userLastWorksheetFilterKey: undefined,

    /**
     * Initialize because we need to set the selectedUser variable
     * @param {Object} options
     */
    initialize: function(options) {
        this._super('initialize', [options]);
        this.userLastWorksheetFilterKey = app.user.lastState.key('worksheet-filter', this);
        this.selectedUser = {
            id: app.user.get('id'),
            is_manager: app.user.get('is_manager'),
            showOpps: false
        };
    },

    // prevent excessive renders when things change.
    bindDomChange: function() {},

    /**
     * Override the render to have call the group by toggle
     *
     * @private
     */
    _render:function () {
        app.view.View.prototype._render.call(this);

        this.node = this.$el.find("#" + this.cid);

        // set up the filters
        this._setUpFilters();

        return this;
    },


    /**
     * Set up select2 for driving the filter UI
     * @param node the element to use as the basis for select2
     * @private
     */
    _setUpFilters: function() {
        var ctx = this.context.parent || this.context,
            user_ranges = app.user.lastState.get(this.userLastWorksheetFilterKey),
            selectedRanges = user_ranges || ctx.get('selectedRanges') || app.defaultSelections.ranges;

        this.node.select2({
            data:this._getRangeFilters(),
            initSelection: function(element, callback) {
                callback(_.filter(
                    this.data,
                    function(obj) {
                        return _.contains(this, obj.id);
                    },
                    $(element.val().split(","))
                ));
            },
            multiple:true,
            placeholder: app.lang.get("LBL_MODULE_FILTER"),
            dropdownCss: {width:"auto"},
            containerCssClass: "select2-choices-pills-close",
            containerCss: "border: none",
            formatSelection: this.formatCustomSelection,
            dropdownCssClass: "search-filter-dropdown",
            escapeMarkup: function(m) { return m; },
            width: '100%'
        });

        // set the default selections
        this.node.select2("val", selectedRanges);

        // add a change handler that updates the forecasts context appropriately with the user's selection
        this.node.change(
            {
                context: ctx
            },
            _.bind(function(event) {
                app.alert.show('worksheet_filtering',
                    {level: 'process', title: app.lang.get('LBL_LOADING')}
                );
                app.user.lastState.set(this.userLastWorksheetFilterKey, event.val);
                _.delay(function() {
                    event.data.context.set('selectedRanges', event.val);
                }, 25);
            }, this)
        );
    },
    /**
     * Formats pill selections
     * 
     * @param item selected item
     */
    formatCustomSelection: function(item) {        
        return '<span class="select2-choice-type" disabled="disabled">' + app.lang.get("LBL_FILTER") + '</span><a class="select2-choice-filter" rel="'+ item.id + '" href="javascript:void(0)">'+ item.text +'</a>';
    },

    /**
     * Gets the list of filters that correspond to the forecasts range settings that were selected by the admin during
     * configuration of the forecasts module.
     * @return {Array} array of the selected ranges
     */
    _getRangeFilters: function() {
        var options = app.metadata.getModule('Forecasts', 'config').buckets_dom || 'commit_stage_binary_dom';

        return _.map(app.lang.getAppListStrings(options), function(value, key)  {
            return {id: key, text: value};
        });
    }

})
