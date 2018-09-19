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
// Be sure to include the base ACL's as well
require_once 'modules/ACLActions/actiondefs.php';

$tbaConfig = new TeamBasedACLConfigurator();
$tbaEnabled = $tbaConfig->isEnabledGlobally();
$tbaFieldOptions = $tbaConfig->getFieldOptions();

 if(!defined('ACL_READ_ONLY')){
 	define('ACL_READ_ONLY', 50);
 	define('ACL_READ_WRITE', 99);
    define('ACL_READ_SELECTED_TEAMS_WRITE', $tbaFieldOptions['ACL_READ_SELECTED_TEAMS_WRITE']);
    define('ACL_SELECTED_TEAMS_READ_OWNER_WRITE', $tbaFieldOptions['ACL_SELECTED_TEAMS_READ_OWNER_WRITE']);
    define('ACL_SELECTED_TEAMS_READ_WRITE', $tbaFieldOptions['ACL_SELECTED_TEAMS_READ_WRITE']);
 	define('ACL_OWNER_READ_WRITE', 40);
 	define('ACL_READ_OWNER_WRITE', 60);
 	 if(!defined('ACL_ALLOW_NONE')){  
 	 	define('ACL_ALLOW_NONE', -99);
 	 	define('ACL_ALLOW_DEFAULT', 0);
 	 }
 	 define('ACL_FIELD_DEFAULT', 99);
 }

$GLOBALS['aclFieldOptions'] = array(
    ACL_ALLOW_DEFAULT => 'LBL_DEFAULT',
    ACL_READ_WRITE => 'LBL_READ_WRITE',
    ACL_READ_OWNER_WRITE => 'LBL_READ_OWNER_WRITE',
);
if ($tbaEnabled) {
    $GLOBALS['aclFieldOptions'][ACL_READ_SELECTED_TEAMS_WRITE] = 'LBL_READ_SELECTED_TEAMS_WRITE';
}
$GLOBALS['aclFieldOptions'][ACL_READ_ONLY] = 'LBL_READ_ONLY';
$GLOBALS['aclFieldOptions'][ACL_OWNER_READ_WRITE] = 'LBL_OWNER_READ_WRITE';

if ($tbaEnabled) {
    $GLOBALS['aclFieldOptions'][ACL_SELECTED_TEAMS_READ_OWNER_WRITE] = 'LBL_SELECTED_TEAMS_READ_OWNER_WRITE';
    $GLOBALS['aclFieldOptions'][ACL_SELECTED_TEAMS_READ_WRITE] = 'LBL_SELECTED_TEAMS_READ_WRITE';
}
$GLOBALS['aclFieldOptions'][ACL_ALLOW_NONE] = 'LBL_ALLOW_NONE';
