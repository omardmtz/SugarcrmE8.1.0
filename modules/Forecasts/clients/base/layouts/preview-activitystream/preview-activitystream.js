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
 * @class View.Layouts.Base.PreviewActivityStreamLayout
 * @alias SUGAR.App.view.layouts.BasePreviewActivityStreamLayout
 * @extends View.Layouts.Base.ActivitystreamLayout
 */
({
    extendsFrom: 'ActivitystreamLayout',

    _previewOpened: false, //is the preview pane open?

    /**
     * Fetch and render activities when 'preview:render' event has been fired.
     */
    initialize: function(options) {
        this._super('initialize', [options]);
        app.events.on('preview:render', this.fetchActivities, this);
        app.events.on('preview:open', function() {
            this._previewOpened = true;
        }, this);
        app.events.on('preview:close', function() {
            this._previewOpened = false;
            this.disposeAllActivities();
        }, this);
    },

    /**
     * Fetch and render activities.
     *
     * @param model
     * @param collection
     * @param fetch
     * @param previewId
     * @param {boolean} showActivities
     */
    fetchActivities: function(model, collection, fetch, previewId, showActivities) {
        if (app.metadata.getModule(model.module).isBwcEnabled) {
            // don't fetch activities for BWC modules
            return;
        }
        this.disposeAllActivities();
        this.collection.dataFetched = false;
        this.$el.hide();

        showActivities = _.isUndefined(showActivities) ? true : showActivities;
        if (showActivities) {
            this.collection.reset();
            this.collection.resetPagination();
            this.collection.setOption('endpoint', function(method, collection, options, callbacks) {
                var url = app.api.buildURL(
                    model.module,
                    null,
                    {id: model.get('id'), link: 'activities'},
                    options.params
                );

                return app.api.call('read', url, null, callbacks);
            });
            this.collection.fetch({
                /*
                 * Render activity stream
                 */
                success: _.bind(this.renderActivities, this)
            });
        }
    },

    /**
     * Render activity stream once the preview pane opens. Hide it when there are no activities.
     * @param collection
     */
    renderActivities: function(collection) {
        var self = this;
        if (this.disposed) {
            return;
        }

        if (this._previewOpened) {
            if (collection.length === 0) {
                this.$el.hide();
            } else {
                this.$el.show();
                collection.each(function(activity) {
                    self.renderPost(activity, true);
                });
            }
        } else {
            //FIXME: MAR-2798 prevent the possibility of an infinite loop
            _.delay(function() {
                self.renderActivities(collection);
            }, 500);
        }
    },

    /**
     * No need to set collectionOptions.
     */
    setCollectionOptions: function() {},

    /**
     * No need to expose data transfer object since this activity stream is readonly.
     */
    exposeDataTransfer: function() {},

    /**
     * Don't load activity stream until 'preview:render' event has been fired.
     */
    loadData: function() {},

    /**
     * No need to bind events here because this activity stream is readonly.
     */
    bindDataChange: function() {
        this.collection.on('add', function(activity) {
            if (!this.disposed) {
                this.renderPost(activity, true);
            }
        }, this);
    }
});
