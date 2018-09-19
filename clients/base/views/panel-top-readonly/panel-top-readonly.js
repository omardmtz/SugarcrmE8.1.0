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
 * @class View.Views.Base.PanelTopReadonlyView
 * @alias SUGAR.App.view.views.BasePanelTopReadonlyView
 * @extends View.PanelTopView
 */
({
    /**
     * @inheritdoc
     */
    extendsFrom: 'PanelTopView',

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);
    }
})
