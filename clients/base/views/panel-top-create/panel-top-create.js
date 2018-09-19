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
 * Header section for Subpanel layouts.
 *
 * @class View.Views.Base.PanelTopView
 * @alias SUGAR.App.view.views.BasePanelTopView
 * @extends View.View
 */
({
    extendsFrom: 'PanelTopView',

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);
        this.context.set('collapsed', false);
    },

    /**
     * @inheritdoc
     *
     * Overrides the default related-record create to add the new item inline
     *
     * @override
     */
    createRelatedClicked: function(event) {},

    /**
     * @inheritdoc
     *
     * Overrides the parent togglePanel since we don't allow panel toggling in create
     *
     * @override
     */
    togglePanel: function() {}
})
