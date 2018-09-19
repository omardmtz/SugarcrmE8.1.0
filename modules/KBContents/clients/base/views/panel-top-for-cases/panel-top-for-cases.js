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
 * @class View.Views.Base.KBContentsPanelTopForCases
 * @alias SUGAR.App.view.views.BaseKBContentsPanelTopForCases
 * @extends View.Views.Base.PanelTopView
 */

({
    extendsFrom: 'PanelTopView',
    plugins: ['KBContent'],
    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);
    },
    /**
     * Event handler for the create button.
     *
     * @param {Event} event The click event.
     */
    createRelatedClicked: function(event) {
        this.createArticleSubpanel();
    },
})
