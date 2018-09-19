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

// This file checks if the external accounts for the logged in user are still valid or not.
// We only check oAuth logins right now, because usernames/passwords shouldn't really expire.


global $app_strings;

$checkList = ExternalAPIFactory::listAPI('',true);

if ( !empty($_REQUEST['api']) ) {
    // Check just one login type
    $newCheckList = array();
    if ( isset($checkList[$_REQUEST['api']]) ) {
        $newCheckList[$_REQUEST['api']] = $checkList[$_REQUEST['api']];
    }
    
    $checkList = $newCheckList;
}

$failList = array();

if ( is_array($checkList) ) {
    foreach ( $checkList as $apiName => $apiOpts ) {
        if ( $apiOpts['authMethod'] == 'oauth' ) {
            $api = ExternalAPIFactory::loadAPI($apiName);
            if ( is_object($api) ) {
                $loginCheck = $api->quickCheckLogin();
            } else {
                $loginCheck['success'] = false;
            }
            if ( ! $loginCheck['success'] ) {
                $thisFail = array();
                
                $thisFail['checkURL'] = 'index.php?module=EAPM&closeWhenDone=1&action=QuickSave&application='.$apiName;

                $translateKey = 'LBL_EXTAPI_'.strtoupper($apiName);
                if ( ! empty($app_strings[$translateKey]) ) {
                    $apiLabel = $app_strings[$translateKey];
                } else {
                    $apiLabel = $apiName;
                }

                $thisFail['label'] = str_replace('{0}',$apiLabel,translate('LBL_ERR_FAILED_QUICKCHECK','EAPM'));
                
                $failList[$apiName] = $thisFail;
            }
        }
    }
}

$json = new JSON();
echo($json->encode($failList));
