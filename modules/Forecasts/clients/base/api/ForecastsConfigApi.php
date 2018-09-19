<?php
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


class ForecastsConfigApi extends ConfigModuleApi
{
    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function registerApiRest()
    {
        return
            array(
                'forecastsConfigGet' => array(
                    'reqType' => 'GET',
                    'path' => array('Forecasts', 'config'),
                    'pathVars' => array('module', ''),
                    'method' => 'config',
                    'shortHelp' => 'Retrieves the config settings for a given module',
                    'longHelp' => 'include/api/help/config_get_help.html',
                ),
                'forecastsConfigCreate' => array(
                    'reqType' => 'POST',
                    'path' => array('Forecasts', 'config'),
                    'pathVars' => array('module', ''),
                    'method' => 'forecastsConfigSave',
                    'shortHelp' => 'Creates the config entries for the Forecasts module.',
                    'longHelp' => 'modules/Forecasts/clients/base/api/help/ForecastsConfigPut.html',
                ),
                'forecastsConfigUpdate' => array(
                    'reqType' => 'PUT',
                    'path' => array('Forecasts', 'config'),
                    'pathVars' => array('module', ''),
                    'method' => 'forecastsConfigSave',
                    'shortHelp' => 'Updates the config entries for the Forecasts module',
                    'longHelp' => 'modules/Forecasts/clients/base/api/help/ForecastsConfigPut.html',
                ),
            );
    }

    /**
     * Forecast Override since we have custom logic that needs to be ran
     *
     * {@inheritdoc}
     */
    public function forecastsConfigSave(ServiceBase $api, array $args)
    {
        //acl check, only allow if they are module admin
        if (!$api->user->isAdmin() && !$api->user->isDeveloperForModule('Forecasts')) {
            // No create access so we construct an error message and throw the exception
            $failed_module_strings = return_module_language($GLOBALS['current_language'], 'forecasts');
            $moduleName = $failed_module_strings['LBL_MODULE_NAME'];

            $args = null;
            if (!empty($moduleName)) {
                $args = array('moduleName' => $moduleName);
            }
            throw new SugarApiExceptionNotAuthorized(
                $GLOBALS['app_strings']['EXCEPTION_CHANGE_MODULE_CONFIG_NOT_AUTHORIZED'],
                $args
            );
        }

        $admin = BeanFactory::newBean('Administration');
        //track what settings have changed to determine if timeperiods need rebuilt
        $prior_forecasts_settings = $admin->getConfigForModule('Forecasts', $api->platform);

        //If this is a first time setup, default prior settings for timeperiods to 0 so we may correctly recalculate
        //how many timeperiods to build forward and backward.  If we don't do this we would need the defaults to be 0
        if (empty($prior_forecasts_settings['is_setup'])) {
            $prior_forecasts_settings['timeperiod_shown_forward'] = 0;
            $prior_forecasts_settings['timeperiod_shown_backward'] = 0;
        }

        $upgraded = 0;
        if (!empty($prior_forecasts_settings['is_upgrade'])) {
            $db = DBManagerFactory::getInstance();
            // check if we need to upgrade opportunities when coming from version below 6.7.x.
            $upgraded = $db->getOne(
                "SELECT count(id) AS total FROM upgrade_history
                    WHERE type = 'patch' AND status = 'installed' AND version LIKE '6.7.%'"
            );
            if ($upgraded == 1) {
                //TODO-sfa remove this once the ability to map buckets when they get changed is implemented (SFA-215).
                $args['has_commits'] = true;
            }
        }

        if (isset($args['show_custom_buckets_options'])) {
            $json = getJSONobj();
            $_args = array(
                'dropdown_lang' => isset($_SESSION['authenticated_user_language']) ?
                    $_SESSION['authenticated_user_language'] : $GLOBALS['current_language'],
                'dropdown_name' => 'commit_stage_custom_dom',
                'view_package' => 'studio',
                'list_value' => $json->encode($args['show_custom_buckets_options']),
                'skip_sync' => true,
            );
            $_REQUEST['view_package'] = 'studio';
            $parser = ParserFactory::getParser('dropdown');
            $parser->saveDropDown($_args);
            unset($args['show_custom_buckets_options']);
        }

        // we do the double check here since the front ent will send one one value if the input is empty
        if (empty($args['worksheet_columns']) || empty($args['worksheet_columns'][0])) {
            // set the defaults
            $args['worksheet_columns'] = array(
                'commit_stage',
                'parent_name',
                'likely_case',
            );
            if ($args['show_worksheet_best'] == 1) {
                $args['worksheet_columns'][] = 'best_case';
            }
            if ($args['show_worksheet_worst'] == 1) {
                $args['worksheet_columns'][] = 'worst_case';
            }
        }

        //reload the settings to get the current settings
        $current_forecasts_settings = parent::configSave($api, $args);

        // setting are saved, reload the setting in the ForecastBean just in case.
        Forecast::getSettings(true);

        // now that we have saved the setting, we need to sync all the data if
        // this is being upgraded or the forecast was not setup before.
        if ($upgraded || empty($prior_forecasts_settings['is_setup'])) {
            if ($args['forecast_by'] === 'Opportunities') {
                SugarAutoLoader::load('include/SugarQueue/jobs/SugarJobUpdateOpportunities.php');
                SugarJobUpdateOpportunities::updateOpportunitiesForForecasting();
            } else {
                SugarAutoLoader::load('include/SugarQueue/jobs/SugarJobUpdateRevenueLineItems.php');
                SugarJobUpdateRevenueLineItems::scheduleRevenueLineItemUpdateJobs();
            }
        }

        // did this change?
        if ($prior_forecasts_settings['worksheet_columns'] !== $args['worksheet_columns']) {
            $this->setWorksheetColumns($api, $args['worksheet_columns'], $current_forecasts_settings['forecast_by']);
        }

        //if primary settings for timeperiods have changed, then rebuild them
        if ($this->timePeriodSettingsChanged($prior_forecasts_settings, $current_forecasts_settings)) {
            $timePeriod = TimePeriod::getByType($current_forecasts_settings['timeperiod_interval']);
            $timePeriod->rebuildForecastingTimePeriods($prior_forecasts_settings, $current_forecasts_settings);
        }

        //If the Opps/RLI switch was done before forecasts was set up, then things were partially configured. Let's
        //reset that stuff now.
        $forecast_by = $current_forecasts_settings['forecast_by'];
        $this->refreshForecastByMetadata($forecast_by);
        $this->rebuildExtensions($forecast_by);

        return $current_forecasts_settings;
    }

    /**
     * Refreshes the ForecastBy module's metadata.  Needed for when forecasts is set up after any opps/rli switching.
     * @param $forecast_by
     */
    public function refreshForecastByMetadata($forecast_by)
    {
        $convert = $this->getOpportunityConfigObject($forecast_by);
        $convert->doMetadataConvert();
    }

    /**
     * Gets the proper config object to run metadata updates through
     * @param $forecast_by
     * @return null|OpportunityWithOutRevenueLineItem|OpportunityWithRevenueLineItem
     */
    public function getOpportunityConfigObject($forecast_by)
    {
        $convert = null;

        if ($forecast_by === 'RevenueLineItems') {
            SugarAutoLoader::load('modules/Opportunities/include/OpportunityWithRevenueLineItem.php');
            $convert = new OpportunityWithRevenueLineItem();
        } else {
            SugarAutoLoader::load('modules/Opportunities/include/OpportunityWithOutRevenueLineItem.php');
            $convert = new OpportunityWithOutRevenueLineItem();
        }

        return $convert;
    }

    /**
     * Rebuilds the metadata for a given module
     * @param $modules List of modules to rebuild extensions for
     */
    public function rebuildExtensions($modules)
    {
        if (!is_array($modules)) {
            $modules = array($modules);
        }

        $rac = $this->getRepairAndClear();
        $rac->show_output = false;
        $rac->module_list = $modules;
        $rac->clearVardefs();
        $rac->rebuildExtensions($modules);
    }

    /**
     * Utility function to return a RepairandClear Object;
     * @return RepairAndClear
     */
    public function getRepairAndClear()
    {
        SugarAutoLoader::load('modules/Administration/QuickRepairAndRebuild.php');
        return new RepairAndClear();
    }

    /**
     * Compares two sets of forecasting settings to see if the primary timeperiods settings are the same
     *
     * @param array $priorSettings The Prior Settings
     * @param array $currentSettings The New Settings Coming from the Save
     *
     * @return boolean
     */
    public function timePeriodSettingsChanged($priorSettings, $currentSettings)
    {
        if (!isset($priorSettings['timeperiod_shown_backward']) ||
            (isset($currentSettings['timeperiod_shown_backward']) &&
                ($currentSettings['timeperiod_shown_backward'] != $priorSettings['timeperiod_shown_backward'])
            )
        ) {
            return true;
        }
        if (!isset($priorSettings['timeperiod_shown_forward']) ||
            (isset($currentSettings['timeperiod_shown_forward']) &&
                ($currentSettings['timeperiod_shown_forward'] != $priorSettings['timeperiod_shown_forward'])
            )
        ) {
            return true;
        }
        if (!isset($priorSettings['timeperiod_interval']) ||
            (isset($currentSettings['timeperiod_interval']) &&
                ($currentSettings['timeperiod_interval'] != $priorSettings['timeperiod_interval'])
            )
        ) {
            return true;
        }
        if (!isset($priorSettings['timeperiod_type']) ||
            (isset($currentSettings['timeperiod_type']) &&
                ($currentSettings['timeperiod_type'] != $priorSettings['timeperiod_type'])
            )
        ) {
            return true;
        }
        if (!isset($priorSettings['timeperiod_start_date']) ||
            (isset($currentSettings['timeperiod_start_date']) &&
                ($currentSettings['timeperiod_start_date'] != $priorSettings['timeperiod_start_date'])
            )
        ) {
            return true;
        }
        if (!isset($priorSettings['timeperiod_leaf_interval']) ||
            (isset($currentSettings['timeperiod_leaf_interval']) &&
                ($currentSettings['timeperiod_leaf_interval'] != $priorSettings['timeperiod_leaf_interval'])
            )
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param ServiceBase $api
     * @param $worksheetColumns
     */
    public function setWorksheetColumns(ServiceBase $api, $worksheetColumns, $forecastBy)
    {
        SugarAutoLoader::load('modules/Forecasts/include/ForecastReset.php');

        $fr = new ForecastReset();
        $fr->updateConfigWorksheetColumnsMetadata($forecastBy);
        $fr->setWorksheetColumns($api->platform, $worksheetColumns, $forecastBy);
    }
}
