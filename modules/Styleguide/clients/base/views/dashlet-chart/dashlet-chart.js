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
    extendsFrom: 'OpportunityMetricsView',

    loadData: function (options) {
        if (this.meta.config) {
            return;
        }
        this.metricsCollection = {
          "won": {
            "amount_usdollar": 40000,
            "count": 4,
            "formattedAmount": "$30,000",
            "icon": "caret-up",
            "cssClass": "won",
            "dealLabel": "won",
            "stageLabel": "Won"
          },
          "lost": {
            "amount_usdollar": 10000,
            "count": 1,
            "formattedAmount": "$10,000",
            "icon": "caret-down",
            "cssClass": "lost",
            "dealLabel": "lost",
            "stageLabel": "Lost"
          },
          "active": {
            "amount_usdollar": 30000,
            "count": 3,
            "formattedAmount": "$30,000",
            "icon": "minus",
            "cssClass": "active",
            "dealLabel": "active",
            "stageLabel": "Active"
          }
        };
        this.chartCollection = {
          "data": [
            {
              "key": "Won",
              "value": 4,
              "classes": "won"
            },
            {
              "key": "Lost",
              "value": 1,
              "classes": "lost"
            },
            {
              "key": "Active",
              "value": 3,
              "classes": "active"
            }
          ],
          "properties": {
            "title": "Opportunity Metrics",
            "value": 8,
            "label": 8
          }
        };
        this.total = 8;
    }
})
