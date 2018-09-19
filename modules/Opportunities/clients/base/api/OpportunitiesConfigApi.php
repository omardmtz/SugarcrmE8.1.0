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


class OpportunitiesConfigApi extends ConfigModuleApi
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
                'opportunitiesConfigCreate' => array(
                    'reqType' => 'POST',
                    'path' => array('Opportunities', 'config'),
                    'pathVars' => array('module', ''),
                    'method' => 'configSave',
                    'shortHelp' => 'Save the config settings for the Opportunities Module',
                    'longHelp' => 'modules/Opportunities/clients/base/api/help/config_post_help.html',
                )
            );
    }

    /**
     * Opportunity Override since we have custom logic that needs to be ran
     *
     * {@inheritdoc}
     */
    public function configSave(ServiceBase $api, array $args)
    {
        //acl check, only allow if they are module admin
        if (!$api->user->isAdmin() && !$api->user->isDeveloperForModule('Opportunities')) {
            // No create access so we construct an error message and throw the exception
            $failed_module_strings = return_module_language($GLOBALS['current_language'], 'Opportunities');
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

        $prior_settings = Opportunity::getSettings();

        // figure out how we should rollup when going back to Opps only
        $date_rollup_type = 'latest';
        if (isset($args['opps_closedate_rollup'])) {
            $date_rollup_type = $args['opps_closedate_rollup'];
            unset($args['opps_closedate_rollup']);
        }

        $this->skipMetadataRefresh = true;
        $settings = parent::configSave($api, $args);

        // reload the settings in the opportunity bean
        Opportunity::getSettings(true);

        $hasChanged = ($prior_settings['opps_view_by'] !== $settings['opps_view_by']);

        if ($hasChanged) {
            $max_execution_time = ini_get('max_execution_time');
            if ($max_execution_time != 0 && $max_execution_time < 300) {
                ini_set('max_execution_time', 300);
            }
            /* @var $converter OpportunityWithOutRevenueLineItem|OpportunityWithRevenueLineItem */
            switch ($settings['opps_view_by']) {
                case 'Opportunities':
                    $converter = new OpportunityWithOutRevenueLineItem();
                    $converter->setDateClosedMigrationParam($date_rollup_type);
                    break;
                case 'RevenueLineItems':
                    $converter = new OpportunityWithRevenueLineItem();
                    break;
            }

            // actually trigger the conversion here
            // do metadata first
            $converter->doMetadataConvert();
            // then do data
            $converter->doDataConvert();

            register_shutdown_function(array('UnifiedSearchAdvanced', 'clearCache'));

            // we need to refresh the cache but do it in the shutdown for this process
            register_shutdown_function(array('MetaDataManager', 'refreshCache'));
        }

        return $settings;
    }
}
