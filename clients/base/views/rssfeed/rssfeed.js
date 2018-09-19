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
 * RSS Feed dashlet consumes an RSS Feed URL and displays it's content as a list
 * of entries.
 * 
 * The following items are configurable.
 *
 * - {number} limit Limit imposed to the number of records pulled.
 * - {number} refresh How often (minutes) should refresh the data collection.
 *
 * @class View.Views.Base.RssfeedView
 * @alias SUGAR.App.view.views.BaseRssfeedView
 * @extends View.View
 */
({
    plugins: ['Dashlet'],

    /**
     * Default options used when none are supplied through metadata.
     *
     * Supported options:
     * - timer: How often (minutes) should refresh the data collection.
     * - limit: Limit imposed to the number of records pulled.
     *
     * @property {Object}
     * @protected
     */
    _defaultOptions: {
        limit: 5,
        auto_refresh: 0
    },

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        options.meta = options.meta || {};
        this._super('initialize', [options]);
        this.loadData(options.meta);
    },

    /**
     * Init dashlet settings
     */
    initDashlet: function() {
        // We only need to handle this if we are NOT in the configure screen
        if (!this.meta.config) {
            var options = {};
            var self = this;
            var refreshRate;

            // Get and set values for limits and refresh
            options.limit = this.settings.get('limit') || this._defaultOptions.limit;
            this.settings.set('limit', options.limit);

            options.auto_refresh = this.settings.get('auto_refresh') || this._defaultOptions.auto_refresh;
            this.settings.set('auto_refresh', options.auto_refresh);

            // There is no default for this so there's no pointing in setting from it
            options.feed_url = this.settings.get('feed_url');

            // Set the refresh rate for setInterval so it can be checked ahead
            // of time. 60000 is 1000 miliseconds times 60 seconds in a minute.
            refreshRate = options.auto_refresh * 60000;

            // Only set up the interval handler if there is a refreshRate higher
            // than 0
            if (refreshRate > 0) {
                if (this.timerId) {
                    clearInterval(this.timerId);
                }
                this.timerId = setInterval(_.bind(function() {
                    if (self.context) {
                        self.context.resetLoadFlag();
                        self.loadData(options);
                    }
                }, this), refreshRate);
            }
        }

        // Validation handling for individual fields on the config
        this.layout.before('dashletconfig:save', function() {
            // Fields on the metadata
            var fields = _.flatten(_.pluck(this.meta.panels, 'fields'));

            // Grab all non-valid fields from the model
            var notValid = _.filter(fields, function(field) {
                return field.required && !this.dashModel.get(field.name);
            }, this);

            // If there no invalid fields we are good to go
            if (notValid.length === 0) {
                return true;
            }

            // Otherwise handle notification of invalidation
            _.each(notValid, function(field) {
                 var fieldOnView = _.find(this.fields, function(comp, cid) { 
                    return comp.name === field.name;
                 });

                 fieldOnView.model.trigger('error:validation:' + field.name, {required: true});
            }, this);

            // False return tells the drawer that it shouldn't close
            return false;
        }, this);
    },

    /**
     * Handles the response of the feed consumption request and sets data from 
     * the result
     * 
     * @param {Object} data Response from the rssfeed API call
     */
    handleFeed: function (data) {
        if (this.disposed) {
            return;
        }

        // Load up the template
        _.extend(this, data);
        this.render();
    },

    /**
     * Loads an RSS feed from the RSS Feed endpoint.
     * 
     * @param {Object} options The metadata that drives this request
     */
    loadData: function(options) {
        if (options && options.feed_url) {
            var callbacks = {success: _.bind(this.handleFeed, this), error: _.bind(this.handleFeed, this)},
                limit = options.limit || this._defaultOptions.limit,
                params = {feed_url: options.feed_url, limit: limit},
                apiUrl = app.api.buildURL('rssfeed', 'read', '', params);

            app.api.call('read', apiUrl, {}, callbacks);
        }
    },

    /**
     * @inheritdoc
     *
     * New model related properties are injected into each model:
     *
     * - {Boolean} overdue True if record is prior to now.
     */
    _renderHtml: function() {
        if (this.meta.config) {
            this._super('_renderHtml');
            return;
        }

        this._super('_renderHtml');
    }
})
