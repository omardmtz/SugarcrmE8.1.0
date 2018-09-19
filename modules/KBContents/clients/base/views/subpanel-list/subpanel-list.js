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
 * Custom Subpanel Layout for Revenue Line Items.
 *
 * @class View.Views.Base.KBContents.SubpanelListView
 * @alias SUGAR.App.view.views.BaseKBContentsSubpanelListView
 * @extends View.Views.Base.SubpanelListView
 */
({
    extendsFrom: 'SubpanelListView',

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this.plugins = _.union(this.plugins || [], ['KBContent']);
        this._super('initialize', [options]);
    }
})
