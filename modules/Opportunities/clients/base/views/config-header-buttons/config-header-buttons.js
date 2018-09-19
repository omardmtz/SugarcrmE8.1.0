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
 * @class View.Views.Base.OpportunitiesConfigHeaderButtonsView
 * @alias SUGAR.App.view.views.BaseOpportunitiesConfigHeaderButtonsView
 * @extends View.Views.Base.ConfigHeaderButtonsView
 */
({
    extendsFrom: 'ConfigHeaderButtonsView',

    /**
     * The current opps_view_by config setting when the view is initialized
     */
    currentOppsViewBySetting: undefined,

    /**
     * Stores if Forecasts is set up or not
     */
    isForecastsSetup: false,

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        this.currentOppsViewBySetting = this.model.get('opps_view_by');

        // get the boolean form of if Forecasts is configured
        this.isForecastsSetup = !!app.metadata.getModule('Forecasts', 'config').is_setup;
    },

    /**
     * Before the save triggers, we need to show the alert so the users know it's doing something.
     * @private
     */
    _beforeSaveConfig: function() {
        app.alert.show('opp.config.save', {level: 'process', title: app.lang.getAppString('LBL_SAVING')});
    },

    /**
     * @inheritdoc
     * @param {function} onClose
     */
    showSavedConfirmation: function(onClose) {
        app.alert.dismiss('opp.config.save');
        this._super('showSavedConfirmation', [onClose]);
    },

    /**
     * Displays the Forecast warning confirm alert
     */
    displayWarningAlert: function() {
        var opportunity = this.model.get('opps_view_by') === 'Opportunities';
        var message = opportunity ? app.lang.get('LBL_OPPS_CONFIG_ALERT_TO_OPPS', 'Opportunities') :
            app.lang.get('LBL_OPPS_CONFIG_ALERT', 'Opportunities');

        app.alert.show('forecast-warning', {
            level: 'confirmation',
            title: app.lang.get('LBL_WARNING'),
            messages: message,
            onConfirm: _.bind(function() {
                this._super('saveConfig');
            }, this),
            onCancel: _.bind(function() {
                this.model.set('opps_view_by', this.currentOppsViewBySetting);
            }, this)
        });
    },

    /**
     * Overriding the default saveConfig to display the warning alert first, then on confirm of the
     * warning alert, save the config settings. Reloads metadata.
     *
     * @inheritdoc
     */
    saveConfig: function() {
        if (this.isForecastsSetup && this.currentOppsViewBySetting !== this.model.get('opps_view_by')) {
            this.displayWarningAlert();
        } else {
            this._super('saveConfig');
        }
    }
})
