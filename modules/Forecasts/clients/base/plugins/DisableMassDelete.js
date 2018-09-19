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
         * This plugin disallows mass-deleting for closed won/lost items (for use in Opps and Products)
         */
        app.plugins.register('DisableMassDelete', ['view'], {
            /**
             * Attach code for when the plugin is registered on a view
             *
             * @param {Object} component
             * @param {Mixed} plugin
             */
            onAttach: function(component, plugin) {
                this.once('init', function() {
                    // we need to stop listening to the original warnDelete message
                    this.layout.off('list:massdelete:fire', this.warnDelete, this);
                    // but instead listen to our custom one
                    this.layout.on('list:massdelete:fire', this._warnDelete, this);
                }, this);
            },

            /**
             * override of parent deleteModels. Removes closed lost/won items from the list to be deleted, and
             * throws a warning if it removes anything
             *
             * @return {String} message
             */
            _warnDelete: function() {
                // get the mass_collection
                var massCollection = this.context.get('mass_collection');
                if (!massCollection) {
                    return;
                }

                var massUpdateModel = this.getMassUpdateModel(this.module),
                    closedModelObj = this._getClosedModels(massUpdateModel, true),
                    closedModels = closedModelObj.closedModels,
                    message = closedModelObj.message;

                if (closedModels.length) {
                    this._uncheckClosedModels(massCollection, closedModels, 'delete_warning', message);
                } else if (massUpdateModel.models.length > 0) {
                    this.warnDelete();
                }
                return message;
            },

            /**
             * Gets the MassUpdate model collection and checks it for closed models for Mass Update
             *
             * @returns {boolean} True if there are closed models, false if not
             */
            checkMassUpdateClosedModels: function() {
                // get the mass_collection
                var massCollection = this.context.get('mass_collection');
                if (!massCollection) {
                    return;
                }

                var massUpdateModel = this.getMassUpdateModel(this.module),
                    closedModelObj = this._getClosedModels(massUpdateModel, false),
                    closedModels = closedModelObj.closedModels,
                    message = closedModelObj.message;

                if (closedModels.length) {
                    // uncheck items
                    this._uncheckClosedModels(massCollection, closedModels, 'massupdate_closed_models_warning', message);
                }

                return !!closedModels.length;
            },

            /**
             * Unchecks any closed model rows and displays a warning message to the user
             *
             * @param {BeanCollection} massCollection The context's mass_collection
             * @param {Array} closedModels An array of models with Closed Won/Lost Status/Stages
             * @param {String} alertId The ID of the alert message
             * @param {String} message The alert message to display to the user
             * @protected
             */
            _uncheckClosedModels: function(massCollection, closedModels, alertId, message) {
                var progressView = this.getProgressView();

                // remove the closed models from the massCollection
                massCollection.remove(closedModels);
                _.each(closedModels, function(item) {
                    var id = item.module + '_' + item.id;
                    $("[name='" + id + "'] input").attr('checked', false);
                });

                app.alert.show(alertId, {
                    level: 'warning',
                    messages: message
                });

                //remove progressView since there is no progress
                progressView.dispose();
                this.layout.removeComponent(progressView);
            },

            /**
             *
             * @param {BeanCollection} massUpdateModel The Mass Update model collection
             * @param {Boolean} isDelete True if this is a delete check, false if mass update
             * @returns {{closedModels, message: *}}
             * @protected
             */
            _getClosedModels: function(massUpdateModel, isDelete) {
                var config = app.metadata.getModule('Forecasts', 'config') || {},
                    sales_stage_won = config.sales_stage_won || ['Closed Won'],
                    sales_stage_lost = config.sales_stage_lost || ['Closed Lost'],
                    closed_RLI_count = 0,
                    lang_key = isDelete ? 'WARNING_NO_DELETE_' : 'WARNING_NO_MASSUPDATE_',
                    label_key = '_STAGE',
                    message = null,
                    status = null,
                    opp_view_by = app.metadata.getModule('Opportunities', 'config').opps_view_by,
                    closedModels = _.filter(massUpdateModel.models, function(model) {
                        status = null;
                        if (opp_view_by === 'RevenueLineItems') {
                            //ENT allows sales_status, so we need to check to see if this module has it and use it
                            status = model.get('sales_status');

                            //grab the closed RLI count (when on opps)
                            closed_RLI_count = model.get('closed_revenue_line_items');
                            if (_.isNull(closed_RLI_count)) {
                                closed_RLI_count = 0;
                            }
                            label_key = '_STATUS';
                        }
                        if (_.isEmpty(status)) {
                            status = model.get('sales_stage');
                        }

                        if (_.contains(sales_stage_won, status) || _.contains(sales_stage_lost, status)) {
                            message = app.lang.get(lang_key + 'SELECTED' + label_key);
                            return true;
                        }

                        if (closed_RLI_count > 0) {
                            message = app.lang.get(lang_key + 'CLOSED_SELECTED' + label_key, 'Opportunities');
                            return true;
                        }

                        return false;
                    });

                return {
                    closedModels: closedModels,
                    message: message
                };
            }
        });
    });
})(SUGAR.App);
