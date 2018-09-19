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
({
    extendsFrom: 'SalesPipelineView',

    /**
     * Is the forecast Module setup??
     */
    forecastSetup: 0,

    /**
     * Holds the forecast isn't set up message if Forecasts hasn't been set up yet
     */
    forecastsNotSetUpMsg: undefined,

    /**
     * Track if current user is manager.
     */
    isManager: false,

    /**
     * @inheritDoc
     */
    initialize: function(options) {
        options.meta.type = 'sales-pipeline';
        options.meta = _.extend({}, app.metadata.getView(this.module, 'sales-pipeline'), options.meta);

        this._super('initialize', [options]);
    }
})
