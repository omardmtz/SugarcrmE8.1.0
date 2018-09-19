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
 * @class View.Views.Base.Home.WebpageView
 * @alias SUGAR.App.view.views.BaseHomeWebpageView
 * @extends View.View
 */
({
    plugins: ['Dashlet'],

    /**
     * @property {Object} _defaultOptions
     * @property {number} _defaultOptions.limit Default number of rows displayed
     *   in a dashlet.
     *
     * @protected
     */
    _defaultOptions: {
        limit: 10,
    },

    bindDataChange: function(){
        if(!this.meta.config) {
            this.model.on("change", this.render, this);
        }
    },

    _render: function() {
        if (!this.meta.config) {
            this.dashletConfig.view_panel[0].height = this.settings.get('limit') * this.rowHeight;
        }
        app.view.View.prototype._render.call(this);
    },

    initDashlet: function(view) {
        this.viewName = view;
        var settings = _.extend({}, this._defaultOptions, this.settings.attributes);
        this.settings.set(settings);
    },

    loadData: function(options) {
        if (options && options.complete) {
            options.complete();
        }
    }
})
