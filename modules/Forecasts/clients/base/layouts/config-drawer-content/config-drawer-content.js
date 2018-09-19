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
 * @class View.Layouts.Base.ForecastsConfigDrawerContentLayout
 * @alias SUGAR.App.view.layouts.BaseForecastsConfigDrawerContentLayout
 * @extends View.Layouts.Base.ConfigDrawerContentLayout
 */
({
    extendsFrom: 'ConfigDrawerContentLayout',

    timeperiodsTitle: undefined,
    timeperiodsText: undefined,
    scenariosTitle: undefined,
    scenariosText: undefined,
    rangesTitle: undefined,
    rangesText: undefined,
    forecastByTitle: undefined,
    forecastByText: undefined,
    wkstColumnsTitle: undefined,
    wkstColumnsText: undefined,

    /**
     * @inheritdoc
     */
    _initHowTo: function() {
        var appLang = app.lang,
            forecastBy = app.metadata.getModule('Forecasts', 'config').forecast_by,
            forecastByLabels = {
                forecastByModule: appLang.getAppListStrings('moduleList')[forecastBy],
                forecastByModuleSingular: appLang.getAppListStrings('moduleListSingular')[forecastBy]
            };

        this.timeperiodsTitle = appLang.get('LBL_FORECASTS_CONFIG_TITLE_TIMEPERIODS', 'Forecasts');
        this.timeperiodsText = appLang.get('LBL_FORECASTS_CONFIG_HELP_TIMEPERIODS', 'Forecasts');
        this.scenariosTitle = appLang.get('LBL_FORECASTS_CONFIG_TITLE_SCENARIOS', 'Forecasts');
        this.scenariosText = appLang.get('LBL_FORECASTS_CONFIG_HELP_SCENARIOS', 'Forecasts', forecastByLabels);
        this.rangesTitle = appLang.get('LBL_FORECASTS_CONFIG_TITLE_RANGES', 'Forecasts');
        this.rangesText = appLang.get('LBL_FORECASTS_CONFIG_HELP_RANGES', 'Forecasts', forecastByLabels);
        this.forecastByTitle = appLang.get('LBL_FORECASTS_CONFIG_HOWTO_TITLE_FORECAST_BY', 'Forecasts');
        this.forecastByText = appLang.get('LBL_FORECASTS_CONFIG_HELP_FORECAST_BY', 'Forecasts');
        this.wkstColumnsTitle = appLang.get('LBL_FORECASTS_CONFIG_TITLE_WORKSHEET_COLUMNS', 'Forecasts');
        this.wkstColumnsText = appLang.get('LBL_FORECASTS_CONFIG_HELP_WORKSHEET_COLUMNS', 'Forecasts');
    },

    /**
     * @inheritdoc
     */
    _switchHowToData: function(helpId) {
        switch(helpId) {
            case 'config-timeperiods':
                this.currentHowToData.title = this.timeperiodsTitle;
                this.currentHowToData.text = this.timeperiodsText;
                break;

            case 'config-ranges':
                this.currentHowToData.title = this.rangesTitle;
                this.currentHowToData.text = this.rangesText;
                break;

            case 'config-scenarios':
                this.currentHowToData.title = this.scenariosTitle;
                this.currentHowToData.text = this.scenariosText;
                break;

            case 'config-forecast-by':
                this.currentHowToData.title = this.forecastByTitle;
                this.currentHowToData.text = this.forecastByText;
                break;

            case 'config-worksheet-columns':
                this.currentHowToData.title = this.wkstColumnsTitle;
                this.currentHowToData.text = this.wkstColumnsText;
                break;
        }
    }
})
