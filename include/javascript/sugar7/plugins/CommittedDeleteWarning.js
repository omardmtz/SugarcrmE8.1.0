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
        app.plugins.register('CommittedDeleteWarning', ['view'], {
            /**
             * @inheritdoc
             *
             * Add delete listeners
             */
            onAttach: function(component, plugin) {
                this.on('init', function() {
                    // Record View - If the main Record is deleted
                    this.context.on('record:deleted', this.showDeleteRecordCommitWarning, this);

                    // List View - If list rows deleted from Mass Delete
                    this.layout.on('list:records:deleted', this.showDeleteRecordCommitWarning, this);

                    // List View - If a list row is deleted from right-side row actions
                    this.layout.on('list:record:deleted', this.showDeleteRecordCommitWarning, this);
                });
            },

            /**
             * Shows a warning message if an Opp or RLI that is included in a forecast is deleted.
             *
             * @param deletedModel
             * @returns {*}
             */
            showDeleteRecordCommitWarning: function(deletedModel) {
                var message = null;

                if(this.checkDeletedModel(deletedModel)) {
                    var forecastModuleSingular = app.lang.getModuleName('Forecasts');

                    message = app.lang.get('WARNING_DELETED_RECORD_RECOMMIT_1', 'Forecasts')
                        + '<a href="#Forecasts">' + forecastModuleSingular + '</a>.  '
                        + app.lang.get('WARNING_DELETED_RECORD_RECOMMIT_2', 'Forecasts')
                        + '<a href="#Forecasts">' + forecastModuleSingular + '</a>.';
                    app.alert.show('included_list_delete_warning', {
                        level: 'warning',
                        messages: message,
                        onLinkClick: function() {
                            app.alert.dismissAll();
                        }
                    });
                }

                return message;
            },

            /**
             * Wraps _checkDeletedModel and handles if deletedModel comes in as an array (mass delete from list)
             *
             * @param deletedModel The model or array of models that were deleted
             * @returns {boolean} If we should show the warning
             */
            checkDeletedModel: function(deletedModel) {
                var showWarning = false;
                if(_.isArray(deletedModel)) {
                    showWarning = _.find(deletedModel, function(model) {
                        return this._checkDeletedModel(model);
                    }, this);
                } else {
                    showWarning = this._checkDeletedModel(deletedModel);
                }
                return showWarning;
            },

            /**
             * Checks to see if the deleted model(s) are included in a forecast
             *
             * @param deletedModel The model or array of models that were deleted
             * @returns {boolean} If we should show the warning
             * @private
             */
            _checkDeletedModel: function(deletedModel) {
                var showDeleteWarning = false;
                if(deletedModel.module === 'Opportunities' &&
                    deletedModel.get('included_revenue_line_items')) {
                    showDeleteWarning = true;
                } else {
                    var config = app.metadata.getModule('Forecasts', 'config');

                    if(_.contains(config.commit_stages_included, deletedModel.get('commit_stage'))) {
                        showDeleteWarning = true;
                    }
                }

                return showDeleteWarning;
            },

            /**
             * @inheritdoc
             *
             * Clean up associated event handlers.
             */
            onDetach: function(component, plugin) {
                this.context.off('record:deleted', null, this);
                this.layout.off('list:record:deleted', null, this);
                this.layout.off('list:records:deleted', null, this);
            }
        });
    });
})(SUGAR.App);
