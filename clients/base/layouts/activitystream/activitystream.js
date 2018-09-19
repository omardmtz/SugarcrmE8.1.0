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
 * @class View.Layouts.Base.ActivitystreamLayout
 * @alias SUGAR.App.view.layouts.BaseActivitystreamLayout
 * @extends View.Layout
 */
({
    className: "block filtered activitystream-layout",

    initialize: function(opts) {
        this.renderedActivities = {};
        this.isActivtyStreamsEnabled = app.config.activityStreamsEnabled;

        this._super('initialize', [opts]);

        this.setCollectionOptions();
        this.exposeDataTransfer();

        this.context.on("activitystream:post:prepend", this.prependPost, this);
        this.context.on('activitystream:paginate', this.paginate, this);

        // Remove active state from all preview buttons
        app.events.on('preview:close', function() {
            this.clearRowDecorations();
        }, this);
    },

    /**
     * Removes highlighted styling from stream activities.
     */
    clearRowDecorations: function() {
        if (_.isUndefined(app.drawer) || app.drawer.isActive(this.$el)) {
            var activities = this.$('.activitystream-posts-comments-container');
            activities.removeClass('highlighted');
            activities.find('.preview-btn')
                .removeClass('active')
                .attr('aria-pressed', false);
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
            switch (layoutType) {
                case "activities":
                    url = app.api.buildURL(real_module, null, {}, options.params);
                    break;
                case "records":
                    url = app.api.buildURL(real_module, action, {}, options.params);
                    break;
                case "record":
                    url = app.api.buildURL(real_module, null, {id: modelId, link: 'activities'}, options.params);
                    break;
            }
            return app.api.call("read", url, null, callbacks);
        };

        this.context.get('collection').setOption({
            endpoint: endpoint,
            success: function(collection) {
                collection.each(function(model) {
                    self.renderPost(model);
                });
            },
            error: function() {
                self.collection.dataFetched = true;
                self.collection.reset();
            }
        });
    },

    /**
     * Expose the dataTransfer object for drag and drop file uploads.
     */
    exposeDataTransfer: function() {
        jQuery.event.props.push('dataTransfer');
    },

    bindDataChange: function() {
        if (this.collection) {
            this.collection.on('add', function(model) {
                this.renderPost(model);
            }, this);
            this.collection.on('reset', function() {
                this.disposeAllActivities();
                this.collection.each(function(post) {
                    this.renderPost(post);
                }, this);
            }, this);
        }

        if (this.context.parent) {
            var model = this.context.parent.get("model");
            this.listenTo(model, "sync", _.once(function() {
                // Only attach to the sync event after the inital sync is done.
                this.listenTo(model, "sync", function() {
                    var options = this.context.get("collectionOptions");
                    this.collection.fetch(options);
                });
            }));
        }
    },

    prependPost: function(model) {
        var view = this.renderPost(model);
        view.$el.parent().prepend(view.$el);
    },

    loadData: function(options) {
        // We want to ensure the data related to this activity loads before the
        // stream for a better user experience.
        var parentCol = this.context.parent.get("collection");
        if (parentCol.isEmpty()) {
            parentCol.once("sync", function(){
                this._load(options);
            }, this);
        } else {
            this._load(options);
        }
    },

    _load: function(options) {
        if (_.isUndefined(this.context.parent.get('layout'))) {
            return;
        }
        options = _.extend({}, options, this.context.get('collectionOptions'));
        this.collection.fetch(options);
    },

    renderPost: function(model, readonly) {
        var view;
        if(_.has(this.renderedActivities, model.id)) {
            view = this.renderedActivities[model.id];
        } else {
            view = app.view.createView({
                context: this.context,
                type: "activitystream",
                module: this.module,
                layout: this,
                model: model,
                readonly: readonly
            });
            this.addComponent(view);
            this.renderedActivities[model.id] = view;
            view.render();
        }
        return view;
    },

    _placeComponent: function(component) {
        if (this.disposed)
            return;

        if(component.name === "activitystream") {
            this.$el.find(".activitystream-list").append(component.el);
        } else if (component.name === 'activitystream-bottom') {
            this.$el.append(component.el);
            component.render();
        } else {
            this.$el.prepend(component.el);
        }
    },

    unbindData: function() {
        var model, collection;

        if (this.context.parent) {
            model = this.context.parent.get("model");
            collection = this.context.parent.get("collection");

            if (model) {
                model.off("change sync", null, this);
            }
            if (collection) {
                collection.off("sync", null, this);
            }
        }

        app.view.Layout.prototype.unbindData.call(this);
    },

    /**
     * Dispose all previously rendered activities
     */
    disposeAllActivities: function() {
        var nonActivities = [];
        _.each(this._components, function(component) {
            if (component.name !== 'activitystream') {
                nonActivities.push(component);
            } else {
                component.dispose();
            }
        });
        this._components = nonActivities;
        this.renderedActivities = {};
    },

    /**
     * Get the next set of activity stream posts.
     */
    paginate: function() {
        this.collection.paginate({
            add: true
        });
    }
})
