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

        /**
         * This plugin disables the delete button for closed won/lost items (for use in Opps and Products)
         */
        app.plugins.register('DisableDelete', ['field'], {

            /**
             * semaphore to prevent multiple executions of the plugin
             */
            running: false,

            /**
             * Attach code for when the plugin is registered on a view
             *
             * @param {object} component
             * @param {object} plugin
             */
            onAttach: function(component, plugin) {
                this.once('init', function() {
                    if (_.contains(['list:deleterow:fire', 'button:delete_button:click'], this.def.event)) {
                        this.on('render', this.removeDelete, this);
                        this.model.on('change:' + this._getFieldName(), this.removeDelete, this);
                    }
                }, this);

            },

            /**
             * Marks delete option as disabled and adds tooltip for listview items that are closed lost/won
             *
             * @return {string} message that was set
             */
            removeDelete: function() {
                //if we are currently running the plugin on an element, prevent overlapping calls
                if (this.running) {
                    return;
                }

                this.running = true;

                var config = app.metadata.getModule('Forecasts', 'config') || {},
                    sales_stage_won = config.sales_stage_won || ['Closed Won'],
                    sales_stage_lost = config.sales_stage_lost || ['Closed Lost'],
                    label_key = '_STAGE',
                    closed_RLI_count = 0,
                    message = null,
                    status = null,
                    button = this.getFieldElement(),
                    field = this._getFieldName();

                if (button.length && _.contains(["list:deleterow:fire", "button:delete_button:click"], this.def.event)) {
                    if (app.metadata.getModule('Opportunities', 'config').opps_view_by === 'RevenueLineItems') {
                        //grab the closed RLI count (when on opps)
                        closed_RLI_count = this.model.get('closed_revenue_line_items');
                        if (_.isNull(closed_RLI_count)) {
                            closed_RLI_count = 0;
                        }
                        label_key = '_STATUS';
                    }
                    status = this.model.get(field);

                    //if we have closed RLIs, set the message here
                    if (closed_RLI_count > 0) {
                        message = app.lang.get('NOTICE_NO_DELETE_CLOSED_RLIS', 'Opportunities');
                    }

                    //if this item has a closed status, this message wins, so set it accordingly
                    if (_.contains(sales_stage_won, status) || _.contains(sales_stage_lost, status)) {
                        message = app.lang.get('NOTICE_NO_DELETE_CLOSED' + label_key);
                    }

                    //if we have a message, disable the button.
                    if (!_.isEmpty(message)) {
                        this.setDisabled(true);
                        button.attr('data-event', '');
                        button.tooltip({title: message});
                    } else {
                        this.setDisabled(false);
                        button.attr('data-event', this.def.event);
                        button.tooltip('destroy');
                    }
                }
                this.running = false;
                return message;
            },

            _getFieldName: function() {
                if (this.model.module == 'Opportunities' &&
                    app.metadata.getModule('Opportunities', 'config').opps_view_by === 'RevenueLineItems') {
                    return 'sales_status';
                }

                return 'sales_stage';
            }
        });
    });
})(SUGAR.App);
