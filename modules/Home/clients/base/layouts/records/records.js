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
 * @class View.Layouts.Home.RecordsLayout
 * @alias SUGAR.App.view.layouts.HomeRecordsLayout
 * @extends View.Layout
 * @deprecated 7.9.0 Will be removed in 7.11.0. Use
 *   {@link View.Layouts.Home.Record} instead.
 */
({
    /**
     * @inheritdoc
     */
    initialize: function(options) {

        app.logger.warn('View.Layouts.Home.RecordsLayout has been deprecated since 7.9.0.0. ' +
        'It will be removed in 7.11.0.0. Please use View.Layouts.Home.Record instead.');
        this._super('initialize', [options]);
    }
})
