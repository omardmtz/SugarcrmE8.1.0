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
 * @class View.Fields.Base.Reports.ChartTypeField
 * @alias SUGAR.App.view.fields.BaseReportsChartTypeField
 * @extends View.Fields.Base.BaseField
 */
({

    extendsFrom: 'BaseField',

    /**
     * The mapping for each of the chart types
     */
    mapping: {
        none: 'LBL_NO_CHART',
        hBarF: 'LBL_HORIZ_BAR',
        vBarF: 'LBL_VERT_BAR',
        pieF: 'LBL_PIE',
        funnelF: 'LBL_FUNNEL',
        lineF: 'LBL_LINE',
    },

    /**
     * Gets the correct mapping for the DB value
     *
     * @param {string} value The value from the server
     * @return {string} The mapped and translated value
     */
    format: function(value) {
        return app.lang.get(this.mapping[value], this.module);
    }
})
