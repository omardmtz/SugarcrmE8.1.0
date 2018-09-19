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


/**
 * @api
 */
class PreviouslyUsedFiltersApi extends SugarApi {
    public function registerApiRest() {
        return array(
            'setUsed' => array(
                'reqType' => 'PUT',
                'path' => array('Filters', '?', 'used',),
                'pathVars' => array('module', 'module_name', 'used',),
                'method' => 'setUsed',
                'shortHelp' => 'This method sets the filter as used for the current user',
                'longHelp' => '',
            ),
            'getUsed' => array(
                'reqType' => 'GET',
                'path' => array('Filters', '?', 'used'),
                'pathVars' => array('module', 'module_name', 'used',),
                'method' => 'getUsed',
                'shortHelp' => 'This method gets the used filter for the current user',
                'longHelp' => '',
            ),
            'deleteUsed' => array(
                'reqType' => 'DELETE',
                'path' => array('Filters', '?', 'used', '?'),
                'pathVars' => array('module', 'module_name', 'used', 'record'),
                'method' => 'deleteUsed',
                'shortHelp' => 'This method deletes the used filter for the current user',
                'longHelp' => '',
            ),
            'deleteAllUsed' => array(
                'reqType' => 'DELETE',
                'path' => array('Filters', '?', 'used',),
                'pathVars' => array('module', 'module_name', 'used'),
                'method' => 'deleteUsed',
                'shortHelp' => 'This method deletes all used filters for the current user',
                'longHelp' => '',
            ),
        );
    }
    /**
     * Set filters as used
     * @param ServiceBase $api
     * @param array $args 
     * @return array of formatted Beans
     */
    public function setUsed(ServiceBase $api, array $args)
    {
        $user_preference = new UserPreference($GLOBALS['current_user']);
        
        $used_filters = $args['filters'];
        $user_preference->setPreference($args['module_name'], $used_filters, 'filters');
        $user_preference->savePreferencesToDB(true);
        // loop over and get the Filters to return
        $beans = $this->loadFilters($used_filters);
        
        $data = $this->formatBeans($api, $args, $beans);

        return $data;
    }
    /**
     * Get filters from previously used
     * @param ServiceBase $api
     * @param array $args 
     * @return array of formatted Beans
     */
    public function getUsed(ServiceBase $api, array $args)
    {
        $user_preference = new UserPreference($GLOBALS['current_user']);
        $used_filters = $user_preference->getPreference($args['module_name'], 'filters');
        // UserPreference::getPreference returns null if the preference does not exist
        if (empty($used_filters)) {
            $used_filters = array();
        }
        // loop over the filters and return them
        $beans = $this->loadFilters($used_filters);
        $data = array();
        if(!empty($beans)) {
            $data = $this->formatBeans($api, $args, $beans);
        }

        return $data;        
    }

    /**
     * Delete a filter from previously used
     * @param ServiceBase $api
     * @param array $args 
     * @return array of formatted Beans
     */
    public function deleteUsed(ServiceBase $api, array $args)
    {
        $data = array();
        $user_preference = new UserPreference($GLOBALS['current_user']);
        $used_filters = $user_preference->getPreference($args['module_name'], 'filters');

        if(isset($args['record']) && !empty($args['record'])) {
            // if the record exists unset it
            $key = array_search($args['record'], $used_filters);
            if($key !== false) {
                unset($used_filters[$key]);
            }
        }
        else {
            // delete them all
            $used_filters = array();
        }


        $user_preference->setPreference($args['module_name'], $used_filters, 'filters');
        $user_preference->savePreferencesToDB(true);

        if(!empty($used_filters)) {
            $beans = $this->loadFilters($used_filters);
        
            $data = $this->formatBeans($api, $args, $beans);
        }

        return $data;        
    }

    protected function loadFilters( &$used_filters ) {
        $return = array();
        foreach($used_filters AS $key => $id) {
            $bean = BeanFactory::getBean('Filters', $id);
            if($bean instanceof SugarBean && !empty($bean->id)) {
                $return[] = $bean;
            }
            else {
                unset($used_filters[$key]);
            }
        }
        return $return;
    }
}
