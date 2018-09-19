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


class LeadConvertApi extends ModuleApi
{
    public function registerApiRest()
    {
        //Extend with test method
        $api= array (
            'convertLead' => array(
                'reqType' => 'POST',
                'path' => array('Leads', '?', 'convert'),
                'pathVars' => array('','leadId',''),
                'method' => 'convertLead',
                'shortHelp' => 'Convert Lead to a Contact and optionally link it to a new or existing module such as an Account or Opportunity',
                'longHelp' => 'modules/Leads/clients/base/api/help/LeadConvertApi.html',
            ),
        );

        return $api;
    }

    /**
     * This method handles the /Lead/:id/convert REST endpoint
     *
     * @param ServiceBase $api The API class of the request, used in cases where the API changes how the fields are pulled from the args array.
     * @param array $args The arguments array passed in from the API
     * @return array Worksheet data entries
     * @throws SugarApiExceptionNotAuthorized
     */
    public function convertLead(ServiceBase $api, array $args)
    {
        $leadConvert = new LeadConvert($args['leadId']);
        $modules = $this->loadModules($api, $leadConvert->getAvailableModules(), $args['modules']);

        $transferActivitiesModules =
            empty($args['transfer_activities_modules']) ? array() : $args['transfer_activities_modules'];
        $transferActivitiesAction =
            empty($args['transfer_activities_action']) ? '' : $args['transfer_activities_action'];

        $modules = $leadConvert->convertLead($modules, $transferActivitiesAction, $transferActivitiesModules);

        return array (
            'modules' => $this->formatBeans($api, $args, $modules)
        );
    }

    /**
     * This method loads a bean from posted data through api
     *
     * @param ServiceBase $api The API class of the request, used in cases where the API changes how the fields are pulled from the args array.
     * @param $module string The module name to be loaded/created.
     * @param $data array The posted data
     * @return SugarBean The loaded bean
     * @throws SugarApiExceptionRequestMethodFailure
     */
    protected function loadModule(ServiceBase $api, $module, $data)
    {
        $version = $api->getRequest()->getUrlVersion();
        $moduleDef = array (
            'module' => $module,
        );

        if (isset($data['id'])) {
            $moduleDef['record'] = $data['id'];
        }
        else {
            $request = array(
                array(
                    'url' => "/{$version}/{$module}",
                    'method' => 'POST',
                    'data' => json_encode($data),
                )
            );
            $apiClass = new BulkApi();
            $result = $apiClass->bulkCall($api, array('requests' => $request));

            if (isset($result) && isset($result[0]) && isset($result[0]['contents'])) {
                if ($result[0]['status'] != 200) {
                    throw new SugarApiExceptionRequestMethodFailure($result[0]['contents']['error'], array(), null, 424, $result[0]['contents']['error_message']);
                }

                $moduleDef['record'] = $result[0]['contents']['id'];
            }
        }
        $bean = $this->loadBean($api, $moduleDef);

        return $bean;
    }

    /**
     * This method loads an array of beans based on available modules for lead convert
     *
     * @param ServiceBase $api The API class of the request, used in cases where the API changes how the fields are pulled from the args array.
     * @param $modulesToConvert Array The modules that will be loaded/created.
     * @param $data The posted data
     * @return SugarBean[] The loaded beans
     */
    protected function loadModules(ServiceBase $api, $modulesToConvert, $data)
    {
        $beans = array();

        foreach ($modulesToConvert as $moduleName) {
            if (!isset($data[$moduleName])) {
                continue;
            }

            $beans[$moduleName] = $this->loadModule(
                $api,
                $moduleName,
                $data[$moduleName]
            );
        }

        return $beans;
    }
}
