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
 * @class View.Views.Base.Home.AboutLanguagePacksView
 * @alias SUGAR.App.view.views.BaseHomeAboutLanguagePacksView
 * @extends View.View
 */
({
    linkTemplate: null,

    /**
     * @inheritdoc
     *
     * Initializes the link template to be used on the render.
     */
    initialize: function(opts) {
        app.view.View.prototype.initialize.call(this, opts);

        this.linkTemplate = app.template.getView(this.name + '.link', this.module);
    },

    /**
     * @inheritdoc
     *
     * Override the title to pass the context with the server info.
     */
    _renderHtml: function() {
        _.each(this.meta.providers, function(provider) {
            provider.link = this.linkTemplate(provider);
        }, this);

        app.view.View.prototype._renderHtml.call(this);
    }
})
