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

({
    /**
     * The plugins used by this view.
     */
    plugins: ['Dashlet'],

    events: {
        'click .addComment' : 'addComment'
    },

    className: 'block filtered activitystream-layout',

    /**
     * The default settings for activity stream.
     *
     * @property {Object}
     */
    _defaultSettings: {
        limit: 5,
        auto_refresh: 0
    },

    /**
     * Omnibar ActivityStream view
     */
    omnibarView: null,

    initialize: function(opts) {
        this.renderedActivities = {};

        var moduleMeta = app.metadata.getModule(opts.context.parent.get('module'));
        this.activityStreamEnabled = moduleMeta.activityStreamEnabled;
        if (this.activityStreamEnabled && this.activityStreamEnabled === true) {
            this.plugins.push('Pagination');
        }

        this._super('initialize', [opts]);

        this.setCollectionOptions();
        this.context.on('activitystream:post:prepend', this.prependPost, this);

        this.omnibarView = app.view.createView({
            context: this.context,
            type: 'activitystream-omnibar',
            module: this.module,
            layout: this
        });

        if (this.meta.config) {
            this.listenTo(this.layout, 'init', this._addFilterComponent);
            this.layout.before('dashletconfig:save', this.saveDashletFilter, this);
        }
    },

    /**
     * Init dashlet settings
     */
    initDashlet: function()
    {
        var options = {};
        var self = this;
        var refreshRate;

        options.limit = this.settings.get('limit') || this._defaultSettings.limit;
        this.settings.set('limit', options.limit);

        options.auto_refresh = this.settings.get('auto_refresh') || this._defaultSettings.auto_refresh;
        this.settings.set('auto_refresh', options.auto_refresh);

        options = _.extend({}, this.context.get('collectionOptions'), options);
        this.context.set('collectionOptions', options);

        // Set the refresh rate for setInterval so it can be checked ahead of
        // time.  60000 is 1000 miliseconds times 60 seconds in a minute.
        refreshRate = options.auto_refresh * 60000;

        // Only set up the interval handler if there is a refreshRate higher than 0
        if (refreshRate > 0) {
            if (this.timerId) {
                clearInterval(this.timerId);
            }
            this.timerId = setInterval(_.bind(function() {
                if (self.context) {
                    self.context.resetLoadFlag();
                    self.loadData();
                }
            }, this), refreshRate);
        }
    },

    /**
     * This function adds the `dashablelist-filter` component to the layout
     * (dashletconfiguration), if the component doesn't already exist.
     */
    _addFilterComponent: function() {
        var filterComponent = this.layout.getComponent('asdashlet-filter');
        if (filterComponent) {
            return;
        }

        this.layout.initComponents([{
            layout: 'asdashlet-filter'
        }]);
    },

    /**
     * Gets Filter Definition from metadata given a filter id
     */
    _getFilterDefFromMeta: function(id) {
        if (!id) {
            return;
        }

        var moduleMeta = app.utils.deepCopy(app.metadata.getModule('Activities'));
        if (_.isObject(moduleMeta)) {
            var filters = _.compact(_.flatten(_.pluck(_.compact(_.pluck(moduleMeta.filters, 'meta')), 'filters')));
            var filter = _.find(filters, function(filter) {
                return filter.id === id;
            }, this);

            if (filter) {
                return filter;
            }
        }
    },

    /**
     * Set endpoint and the success callback for retrieving activities.
     */
    setCollectionOptions: function() {
        var self = this;

        var endpoint = function(method, model, options, callbacks) {
            var real_module = self.context.parent.get('module'),
                layoutType = self.context.parent.get('layout'),
                modelId = self.context.parent.get('modelId'),
                action = model.module, // equal to 'Activities'
                url;

            // For :Home/:record case we should call for global ActivityStream
            if (real_module == 'Home' && layoutType == 'record') {
                real_module = self.module;
                layoutType = 'activities';
            }

            switch (layoutType) {
                case 'activities':
                    url = app.api.buildURL(real_module, null, {}, options.params);
                    break;
                case 'records':
                    url = app.api.buildURL(real_module, action, {}, options.params);
                    break;
                case 'record':
                    url = app.api.buildURL(real_module, null, {id: modelId, link: 'activities'}, options.params);
                    break;
            }

            return app.api.call('read', url, null, callbacks);
        };

        this.context.set('collectionOptions', {
            endpoint: endpoint,
            success: function(collection) {
                // Toogle no-data visibility
                self.$el.find('.block-footer').toggleClass('hide', (collection.length > 0));
                collection.each(function(model) {
                    self.renderPost(model);
                });
            },
            limit: this._defaultSettings.limit
        });
    },

    /**
     * This function is invoked by the `dashletconfig:save` event. It takes the
     * `currentFilterId` stored on the context, and saves it on the dashlet.
     *
     * @param {Bean} model The dashlet mo`del.
     */
    saveDashletFilter: function() {
        this.layout.trigger('asdashlet:config:save');
    },

    /**
     * Show\hide activity stream omnibar
     */
    addComment: function()
    {
        this.$el.find('.omnibar-dashlet').toggleClass('hide');
    },

    /**
     * Bind data changes and comments\post adding
     */
    bindDataChange: function() {
        if (this.collection) {
            this.collection.on('add', function(model) {
                this.renderPost(model);
            }, this);
            this.collection.on('reset', function() {
                this.disposeAllActivities();

                // Clean up post container
                this.$el.find('.activitystream-list').html('');
                this.collection.each(function(post) {
                    this.renderPost(post);
                }, this);
            }, this);
        }

        if (this.context.parent) {
            var model = this.context.parent.get('model');
            this.listenTo(model, 'sync', _.once(function() {
                // Only attach to the sync event after the inital sync is done.
                this.listenTo(model, 'sync', function() {
                    var options = this.context.get('collectionOptions');
                    var filterDef = this._getFilterDefFromMeta(this.meta.currentFilterId);
                    if (filterDef) {
                        options.filter = filterDef.filter_definition;
                    }
                    this.collection.fetch(options);
                });
            }));
        }
    },

    /**
     * Add posts to activity stream on data change
     *
     * @param model
     */
    prependPost: function(model) {
        var view = this.renderPost(model);
        view.$el.parent().prepend(view.$el);
    },

    loadData: function(options) {
        if (_.isUndefined(this.context.parent.get('layout'))) {
            return;
        }

        // If activity streams are disabled for the current module on which the dashlet sits
        // then display a warning saying as much
        if (!this.activityStreamEnabled) {
            this.template = app.template.get(this.name + '.disabled');
            this._super('_render', [options]);
            return;
        }

        options = _.extend({}, options, this.context.get('collectionOptions'));

        if (this.collection) {
            var filterDef = this._getFilterDefFromMeta(this.meta.currentFilterId);
            if (filterDef) {
                options.filter = filterDef.filter_definition;
            }

            this.collection.fetch(options);
        }
    },

    /**
     * Render each ActivityStream model
     *
     * @param model
     * @return {Mixed}
     */
    renderPost: function(model) {
        var view;

        if (_.has(this.renderedActivities, model.id)) {
            view = this.renderedActivities[model.id];
        } else {
            view = app.view.createView({
                context: this.context,
                type: 'activitystream',
                module: this.module,
                layout: this,
                model: model,
                nopreview: true  // hide preview button
            });

            // Place view to dashlet layout container
            this.$el.find('.activitystream-list').append(view.el);
            this.renderedActivities[model.id] = view;
            view.render();
        }

        return view;
    },

    /**
     * Skip rendering on pagination update
     * This is a hack to prevent dashlet re-render
     *
     * @param options
     */
    render: function(options)
    {
        if (!this.rendered) {
            this.rendered = true;
            this._super('render', [options]);

            // Append sub view - aka add component
            this.$el.find('.omnibar-dashlet').append(this.omnibarView.el);
            this.omnibarView.render();
        }
    },

    unbindData: function() {
        var model, collection;

        if (this.context.parent) {
            model = this.context.parent.get('model');
            collection = this.context.parent.get('collection');

            if (model) {
                model.off('change sync', null, this);
            }
            if (collection) {
                collection.off('sync', null, this);
            }
        }

        this._super('unbindData');
    },

    /**
     * @inheritdoc
     */
    _dispose: function() {
        this.omnibarView.dispose();
        this.disposeAllActivities();
        this._super('_dispose');
    },

    /**
     * Dispose all previously rendered activities
     */
    disposeAllActivities: function() {
        _.each(this.renderedActivities, function(component) {
            component.dispose();
        });
        this.renderedActivities = {};
    }
})
