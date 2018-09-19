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
 * @class View.Views.Base.NestedSetHeaderpaneView
 * @alias SUGAR.App.view.views.BaseNestedSetHeaderpaneView
 * @extends View.Views.Base.HeaderpaneView
 */
({
    extendsFrom: 'HeaderpaneView',

    /**
     * @inheritdoc
     */
    _renderHtml: function() {
        var titleTemplate = Handlebars.compile(this.context.get('title') || app.lang.getAppString('LBL_SEARCH_AND_SELECT')),
            moduleName = app.lang.get('LBL_MODULE_NAME', this.module);
        this.title = titleTemplate({module: moduleName});
        this._super('_renderHtml');

        this.layout.on('selection:closedrawer:fire', _.once(_.bind(function() {
            this.$el.off();
            app.drawer.close();
        }, this)));
    }
})
