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
 * @class View.Layouts.Base.FooterLayout
 * @alias SUGAR.App.view.layouts.BaseFooterLayout
 * @extends View.Layout
 */
({
    /**
     * Places all components within this layout inside `btn-toolbar` div.
     *
     * @param {View.View|View.Layout} component View or layout component.
     * @override
     * @protected
     */
    _placeComponent: function(component) {
        this.$('.btn-toolbar').append(component.el);
    },

    /**
     * @inheritdoc
     */
    _render: function() {
        // FiXME SC-5765 the logo should be a separate view, so we can update it based
        // on the re-render of this layout
        this.$('[data-metadata="logo"]').attr('src', app.metadata.getLogoUrl());
        return this._super('_render');
    }
})
