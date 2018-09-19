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
 * @class View.Layouts.Base.SubpanelCreateLayout
 * @alias SUGAR.App.view.layouts.BaseSubpanelCreateLayout
 * @extends View.Layouts.Base.SubpanelLayout
 */
({
    extendsFrom: 'SubpanelLayout',

    initialize: function(options) {
        app.logger.warn('`BaseSubpanelCreateLayout` controller ' +
            'has been deprecated since 7.8.0 and will be removed in 7.9.0. To use `BaseSubpanelLayout` controller, ' +
            'specify the `type` property in your `subpanel-create` metadata file instead.');

        this._super('initialize', [options]);
    }
})
