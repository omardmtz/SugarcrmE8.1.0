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
 * View that displays the activity stream.
 * @class View.Views.ActivityView
 * @alias SUGAR.App.layout.ActivityView
 * @extends View.View
 */
({
    events: {
        'click [data-action=loadPreview]': 'loadPreview',
        'click [data-action=addNote]': 'openNoteModal',
        'click [name=show_more_button]': 'showMoreRecords'
    },

    plugins: ['RelativeTime'],

    /**
     * Default settings used for the `RelativeTime` plugin.
     *
     * - `{boolean} useRelativeTime` Set `true` if the relative time should be
     *   displayed, `false` if a formatted datetime should be used. Defaults to
     *   `true`.
     * - `{number} relativeTimeThreshold` Relative to today, this option
     *   specifies how many days the relative time should be displayed (e.g.
     *   '2 days ago'), before the formatted date-time thereafter (e.g.
     *   '2015/05/27').
     *
     * These defaults can be overridden through the metadata (shown below) or by
     * customizing this layout.
     *
     *     // ...
     *     'settings' => array(
     *         'relativeTimeThreshold' => 5,
     *         //...
     *     ),
     *     //...
     *
     * @property {Object}
     * @protected
     */
    _defaultSettings: {
        relativeTimeThreshold: 2,
        useRelativeTime: true
    },

    /**
     * Settings after applying metadata settings on top of
     * {@link #_defaultSettings}.
     *
     * @property {Object}
     * @protected
     */
    _settings: {},

    /**
     * Handlebars flag for when activity stream contains no items
     *
     * @property {boolean}
     */
    emptyStream: false,

    /**
     * @override
     * @param options
     */
    initialize: function(options) {
        this._super('initialize', [options]);
        this._addPreviewEvents();
        this._initSettings();
    },

    /**
     * Merges settings defined in the metadata with {@link #_defaultSettings}.
     *
     * @protected
     * @chainable
     */
    _initSettings: function() {
        this._settings = _.extend({},
            this._defaultSettings,
            this.meta && this.meta.settings || {}
        );
        if (this._settings.useRelativeTime === true) {
            this.useRelativeTime();
        }
        return this;
    },

    /**
     * Helper function that determines whether or not to show the date created
     * attribute (on the model) as a relative time (e.g. '2 days ago') or as a
     * date-time value instead.
     *
     * @chainable
     * @return {boolean} `true` if the relative time should be displayed,
     *   `false` if the date-time value should be shown instead.
     */
    useRelativeTime: function() {
        if (_.isEmpty(this.collection.models)) {
            return;
        }

        _.each(this.collection.models, function(model) {
            var date = model.get('date_entered'),
                diffInDays = app.date().diff(date, 'days', true),
                useRelative = diffInDays <= this._settings.relativeTimeThreshold;

            model.set('showRelativeTime', useRelative);
        }, this);

        return this;
    },

    /**
     * @inheritdoc
     */
    _renderHtml: function() {
        if (this.hasLoadedActivities()) {
            this.emptyStream = this.collection.length < 1;
        }
        // Bug 54597 activity view not respecting list ACL
        var oViewName = this.name;
        this.name = 'list';

        if (this._settings.useRelativeTime === true) {
            this.useRelativeTime();
        }
        this._super('_renderHtml');
        this.name = oViewName;
    },

    /**
     * Test if activities collection has been fetched yet
     * @return {boolean} `true` if activities have been fetched
     */
    hasLoadedActivities: function(){
        // page has a value once fetch is complete
        return _.isNumber(this.collection.page);
    },

    /**
     * @override
     */
    bindDataChange: function() {
        if (this.collection) {
            this.collection.on("reset", this.render, this);
        }
    },

    /**
     * Add preview events
     * @private
     */
    _addPreviewEvents: function() {
        //When switching to next/previous record from the preview panel, we need to update the highlighted row
        app.events.on("list:preview:decorate", this.decorateRow, this);
        this.collection.on("reset", function() {
            //When fetching more records, we need to update the preview collection
            app.events.trigger("preview:collection:change", this.collection);
            if (this._previewed) {
                this.decorateRow(this._previewed);
            }
        }, this);
    },

    /**
     * Load Preview
     * @param event
     */
    loadPreview: function(event) {
        // gets the activityId in the data attribute
        var $parent = this.$(event.currentTarget).parents("li.activity");
        var activityId = $parent.data("id");

        // gets the activity model
        var activity = this.collection.get(activityId);

        this.decorateRow(activity);
        app.events.trigger("preview:render", activity, this.collection, false);
    },

    /**
     * Decorate a row in the list that is being shown in Preview
     * @param model Model for row to be decorated.  Pass a falsy value to clear decoration.
     */
    decorateRow: function(model) {
        this._previewed = model;
        // UI fix
        this.$("li.activity").removeClass("on");
        if (model) {
            this.$("li.activity[data-id=" + model.get("id") + "]").addClass("on");
        }
    },

    /**
     * Open the modal for writing a note
     * @param event
     */
    openNoteModal: function(event) {
        if (Modernizr.touch) {
            app.$contentEl.addClass('content-overflow-visible');
        }
        // triggers an event to show the modal
        this.layout.trigger("app:view:activity:editmodal");
        this.$('li.open').removeClass('open');
        return false;
    },

    /**
     * Loads more notes
     * @param event
     */
    showMoreRecords: function(event) {
        var self = this, options;
        app.alert.show('show_more_records', {level: 'process', title: app.lang.get('LBL_LOADING')});

        // If in "search mode" (the search filter is toggled open) set q:term param
        options = self.filterOpened ? self.getSearchOptions() : {};

        // Indicates records will be added to those already loaded in to view
        options.add = true;

        if (this.collection.link) {
            options.relate = true;
        }

        options.success = function() {
            app.alert.dismiss('show_more_records');
            self.layout.trigger("list:paginate:success");
            self.render();
            window.scrollTo(0, document.body.scrollHeight);
        };
        options.limit = this.limit;
        this.collection.paginate(options);
    }
})
