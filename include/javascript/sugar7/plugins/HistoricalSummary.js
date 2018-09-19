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
(function(app) {
    app.events.on('app:init', function() {
        app.plugins.register('HistoricalSummary', ['view'], {
            /**
             * @inheritdoc
             *
             * Bind the historical summary button handler.
             */
            onAttach: function(component, plugin) {
                this.on('init', function() {
                    this.context.on('button:historical_summary_button:click', this.historicalSummaryClicked, this);
                });
            },

            /**
             * Handles the click event, and open the historical-summary-list view
             */
            historicalSummaryClicked: function() {
                app.drawer.open({
                    layout: 'history-summary',
                    context: {
                        name: 'history'
                    }
                });
            },

            /**
             * @inheritdoc
             *
             * Clean up associated event handlers.
             */
            onDetach: function(component, plugin) {
                this.context.off('button:historical_summary_button:click', this.auditClicked, this);
            }
        });
    });
})(SUGAR.App);
