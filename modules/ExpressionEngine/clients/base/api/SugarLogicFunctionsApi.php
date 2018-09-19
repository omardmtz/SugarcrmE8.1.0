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


class SugarLogicFunctionsApi extends SugarApi
{
    /**
     * Rest Api Registration Method
     *
     * @return array
     */
    public function registerApiRest()
    {
        $parentApi = array(
            'sugarlogic_functions' => array(
                'reqType' => 'GET',
                'path' => array('ExpressionEngine', 'functions'),
                'pathVars' => array('', ''),
                'method' => 'getSugarLogicFunctions',
                'shortHelp' => 'Retrieve the js for SugarLogic Expressions and Actions',
                'longHelp' => '',
                'noLoginRequired' => true,
                'rawReply' => true,
                'noEtag' => true,
                'ignoreMetaHash' => true,
                'ignoreSystemStatusError' => true,
            ),
        );
        return $parentApi;
    }

    /**
     * Will return the javascript for the Sugar Logic expressions and actions installed on this instance.
     *
     * @param ServiceBase $api
     * @param array       $args
     */
    public function getSugarLogicFunctions(ServiceBase $api, array $args)
    {
        $useDebug = (!shouldResourcesBeMinified() || !empty($args['debug']));
        $phpCacheFile = sugar_cached("Expressions/functionmap.php");
        $jsCacheFile = $useDebug ?
            sugar_cached("Expressions/functions_cache_debug.js") :
            sugar_cached('Expressions/functions_cache.js');
        // @jvink - check with @dwheeler
        if (!file_exists($phpCacheFile) || !file_exists($jsCacheFile)) {
            $GLOBALS['updateSilent'] = true;
            include("include/Expressions/updatecache.php");
        }
        $api->setHeader("Content-Type", "application/javascript");
        return sugar_file_get_contents($jsCacheFile);
    }
}
