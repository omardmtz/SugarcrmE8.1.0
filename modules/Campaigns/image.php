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
require_once('modules/Campaigns/utils.php');

$GLOBALS['log']->debug('identifier from the image request is'.$_REQUEST['identifier']);
if(!empty($_REQUEST['identifier'])) {
	$keys=log_campaign_activity($_REQUEST['identifier'],'viewed');
}
sugar_cleanup();
Header("Content-Type: image/gif");
$fn=sugar_fopen(SugarThemeRegistry::current()->getImageURL("blank.gif",false),"r");
fpassthru($fn);
?>
