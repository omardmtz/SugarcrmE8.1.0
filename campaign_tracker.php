<?php
 if(!defined('sugarEntry'))define('sugarEntry', true);
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
 define('ENTRY_POINT_TYPE', 'api');
 require_once('include/entryPoint.php');

// logic will be added here at a later date to track campaigns
// this script; currently forwards to site_URL variable of $sugar_config
// redirect URL will also be added so specified redirect URL can be used

// additionally, another script using fopen will be used to call this
// script externally

require_once('modules/Campaigns/utils.php');

if(!empty($_REQUEST['identifier'])) {
	$keys=log_campaign_activity($_REQUEST['identifier'],'link');
}

if(empty($_REQUEST['track'])) {
	$track = "";
} else {
	$track = $_REQUEST['track'];
}
$track = $db->quote($track);

if(preg_match('/^[0-9A-Za-z\-]*$/', $track))
{
	$query = "SELECT refer_url FROM campaigns WHERE tracker_key='$track'";
	$res = $db->query($query);

	$row = $db->fetchByAssoc($res);

	$redirect_URL = $row['refer_url'];
	header("Location: $redirect_URL");
}
sugar_cleanup(true);
