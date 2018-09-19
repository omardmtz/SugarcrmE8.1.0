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
 * @class View.Layouts.Home.DashboardLayout
 * @alias SUGAR.App.view.layouts.HomeDashboardLayout
 * @extends View.Layouts.DashboardLayout
 * @deprecated 7.9.0 Will be removed in 7.11.0. Use
 *   {@link View.Layouts.Dashboards.DashboardLayout} instead.
 */
({
    extendsFrom: 'DashboardLayout',

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        app.logger.warn('View.Layouts.Home.DashboardLayout has been deprecated since 7.9.0.0. ' +
        'It will be removed in 7.11.0.0. Please use View.Layouts.Dashboards.DashboardLayout instead.');
        this._super('initialize', [options]);
    }
})
