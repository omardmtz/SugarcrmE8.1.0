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
 * @class View.Views.Base.ForecastsConfigHeaderButtonsView
 * @alias SUGAR.App.view.layouts.BaseForecastsConfigHeaderButtonsView
 * @extends View.Views.Base.ConfigHeaderButtonsView
 */
({
    extendsFrom: 'ConfigHeaderButtonsView',

    /**
     * @inheritdoc
     */
    _beforeSaveConfig: function() {
        var ctxModel = this.context.get('model');

        // Set config settings before saving
        ctxModel.set({
            is_setup:true,
            show_forecasts_commit_warnings: true
        });

        // update the commit_stages_included property and
        // remove 'include_in_totals' from the ranges so it doesn't get saved
        if(ctxModel.get('forecast_ranges') == 'show_custom_buckets') {
            var ranges = ctxModel.get('show_custom_buckets_ranges'),
                labels = ctxModel.get('show_custom_buckets_options'),
                commitStages = [],
                finalLabels = [];

            ctxModel.unset('commit_stages_included');
            _.each(ranges, function(range, key) {
                if (range.in_included_total) {
                    commitStages.push(key);
                }
                delete range.in_included_total;

                finalLabels.push([key, labels[key]]);
            }, this);

            ctxModel.set({
                commit_stages_included: commitStages,
                show_custom_buckets_ranges: ranges,
                show_custom_buckets_options: finalLabels
            }, {silent: true});
        }
    },

    /**
     * @inheritdoc
     */
    cancelConfig: function() {
        if (app.metadata.getModule('Forecasts', 'config').is_setup) {
            return this._super('cancelConfig');
        }
        if (this.triggerBefore('cancel')) {
            if (app.drawer.count()) {
                app.drawer.close(this.context, this.context.get('model'));
            }
            // Redirect to Admin panel if Forecasts has not been set up
            app.router.navigate('#Administration', {trigger: true});
        }
    },


    /**
     * @inheritdoc
     */
    _saveConfig: function() {
        var url = app.api.buildURL(this.module, 'config');
        app.api.call('create', url, this.model.attributes, {
                success: _.bind(function() {
                    if (app.drawer.count()) {
                        this.showSavedConfirmation();
                        // close the drawer and return to Forecasts
                        app.drawer.close(this.context, this.context.get('model'));
                        // Forecasts requires a refresh, always, so we force it
                        Backbone.history.loadUrl(app.api.buildURL(this.module));
                    } else {
                        app.router.navigate(this.module, {trigger: true});
                    }
                }, this),
                error: _.bind(function() {
                    this.getField('save_button').setDisabled(false);
                }, this)
            }
        );
    }
})
