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


abstract class PMSEFilterApi extends FilterApi
{
    /**
     * @var String Define the PA module name
     */
    public $apiRoute = '';
    /**
     * @var String Define field that storage Target Module
     */
    public static $filterModuleField = '';

    /**
     * Return $apiRoute value
     * @return String
     */
    public function getApiRoute() {
        return $this->apiRoute;
    }

    /**
     * Returns $filterModuleField value
     * @return String
     */
    public static function getFilterModuleField() {
        return static::$filterModuleField;
    }


    /**
     * @inheritdoc
     */
    public function registerApiRest()
    {
        return array(
            'filterModuleAll' => array(
                'reqType' => 'GET',
                'path' => array($this->getApiRoute()),
                'pathVars' => array('module'),
                'method' => 'filterList',
                'jsonParams' => array('filter'),
                'shortHelp' => 'List of all records in this module',
                'longHelp' => 'include/api/help/module_filter_get_help.html',
                'exceptions' => array(
                    'SugarApiExceptionNotFound',
                    'SugarApiExceptionError',
                    'SugarApiExceptionInvalidParameter',
                    'SugarApiExceptionNotAuthorized',
                ),
            ),
        );
    }

    /**
     * @inheritdoc
     */
    protected static function addFilters(array $filterDefs, SugarQuery_Builder_Where $where, SugarQuery $q) {
        // Adding new filter to respect ACL PA Target module
        $newFilter = array(
            self::getFilterModuleField() => array(
                '$in' => SugarACL::filterModuleList(PMSEEngineUtils::getSupportedModules(), 'access', true)
            )
        );
        $filterDefs[] = $newFilter;
        parent::addFilters($filterDefs, $where, $q);
    }
}
