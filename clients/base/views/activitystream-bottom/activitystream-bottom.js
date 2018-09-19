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
 * @class View.Views.Base.ActivitystreamBottomView
 * @alias SUGAR.App.view.views.BaseActivitystreamBottomView
 * @extends View.View
 */
({
    events: {
        'click [data-action=show-more]': 'paginate'
    },

    /**
     * Load list-bottom template.
     * @inheritdoc
     */
    _loadTemplate: function(options) {
        this.tplName = 'list-bottom';
        this.template = app.template.getView(this.tplName);
    },

    /**
     * @inheritdoc
     */
    _render: function() {
        this._super('_render');

        if (!app.config.activityStreamsEnabled) {
            this.$el.addClass('hide');
        }

        return this;
    },

    /**
     * Display appropriate label depending on the state of activity stream collection.
     * @inheritdoc
     */
    _renderHtml: function() {
        if ((this.collection.next_offset === -1) && (this.collection.length > 0)) {
            this.$el.addClass('hide');
        } else {
            this.dataFetched = this.collection.dataFetched;
            this.showMoreLabel = app.lang.get('TPL_SHOW_MORE_MODULE', this.module);
            this.showLoadMsg = true;
            this._super('_renderHtml');
            this.$el.removeClass('hide');
        }
    },

    /**
     * Re-render when activity stream is fetched.
     * @inheritdoc
     */
    bindDataChange: function() {
        this.collection.on('reset add', this.render, this);
    },

    /**
     * Call to paginate activity stream.
     */
    paginate: function() {
        this.context.trigger('activitystream:paginate', true);
    }
})
