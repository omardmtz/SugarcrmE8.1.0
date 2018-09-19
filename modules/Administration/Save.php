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
/*********************************************************************************

 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/


global $current_user;

if (!is_admin($current_user)) sugar_die("Unauthorized access to administration.");

$focus = BeanFactory::newBean('Administration');

// filter for relevant POST data and update config table
foreach ($_POST as $key => $val) {
	$prefix = $focus->get_config_prefix($key);
	if (in_array($prefix[0], $focus->config_categories)) {
        if ( $prefix[0] == "license" )
        {
        	if ( $prefix[1] == "expire_date" )
        	{
        		global $timedate;
            	$val = $timedate->swap_formats( $val, $timedate->get_date_format(), $timedate->dbDayFormat );
        	}
        	else
        	if ( $prefix[1] == "key" )
        	{
        		$val = trim($val); // bug 16860 tyoung - trim whitespace from the start and end of the licence key value	
        	}
        }

        $focus->saveSetting($prefix[0], $prefix[1], $val); 
	}
}


if(isset($_POST['license_key'])){
	
	
	loadLicense(true);
	check_now(get_sugarbeat());
	
}






unset($_SESSION['license_seats_needed']);
unset($_SESSION['LICENSE_EXPIRES_IN']);
unset($_SESSION['VALIDATION_EXPIRES_IN']);
unset($_SESSION['HomeOnly']);


apiLoadSystemStatus(true);

// Lock down access to the application for sidecar modules until
// we have a license validation available.
if (!authenticateDownloadKey()) {
    $_SESSION['HomeOnly'] = true;
}

header("Location: index.php?action={$_POST['return_action']}&module={$_POST['return_module']}");
