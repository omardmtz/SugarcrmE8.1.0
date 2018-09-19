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

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        /**
         * The instance of the {@link Core.Context preview layout context}.
         * @type {Core.Context}
         */
        this._previewContext = this.context.parent;

        app.events.on('preview:close', function() {
            this.disposeAllActivities();
        }, this);
    },

    /**
     * @inheritdoc
     */
    _render: function() {
        this.fetchActivities(this._previewContext.get('model'), this._previewContext.get('collection'));
        this._super('_render');
    },

    /**
     * Fetch and render activities.
     *
     * @param {Data.Bean} model The {@link Data.Bean model} being previewed.
     * @param {Data.BeanCollection} collection The
     *   {@link Data.BeanCollection collection} of preview models.
     */
    fetchActivities: function(model, collection) {
        if (app.metadata.getModule(model.module).isBwcEnabled) {
            // don't fetch activities for BWC modules
            return;
        }
        this.disposeAllActivities();
        this.collection.dataFetched = false;
        this.$el.hide();

        this.collection.reset();
        this.collection.resetPagination();
        this.collection.setOption('endpoint', function(method, collection, options, callbacks) {
            var url = app.api.buildURL(model.module, null, {id: model.get('id'), link: 'activities'}, options.params);

            return app.api.call('read', url, null, callbacks);
        });
        this.collection.fetch({
            /*
             * Render activity stream
             */
            showAlerts: true,
            success: _.bind(this.renderActivities, this)
        });
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

        if (collection.length) {
            collection.each(function(activity) {
                self.renderPost(activity, true);
            });
            this.$el.show();
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
     * @inheritdoc
     */
    bindDataChange: function() {
        this.collection.on('add', function(activity) {
            if (!this.disposed) {
                this.renderPost(activity, true);
            }
        }, this);

        this._previewContext.on('change:model', this.render, this);
    },

    /**
     * @inheritdoc
     */
    unbind: function() {
        this._previewContext.off('change:model', this.render, this);
        this._super('unbind');
    }
})
