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

if (!is_admin($current_user)&& !is_admin_for_module($current_user,'Forecasts')) {
    sugar_die("Unauthorized access to administration.");
}

$focus = BeanFactory::newBean('TimePeriods');

if ($_POST['isDuplicate'] != 1) {
	$focus->retrieve($_POST['record']);
}

//validate, start_date <= end_Date.
global $timedate;

$start_date = $timedate->to_db_date($_POST['start_date'], false);
$end_date=$timedate->to_db_date($_POST['end_date'], false);

if ($end_date >= $start_date) {
	//defult checbox value to 0;
	$focus->is_fiscal_year=0;
	
	foreach ($focus->column_fields as $field) {
		if (isset($_POST[$field])) {
			
			if ($field == "is_fiscal_year" && $_POST[$field] == "on") {
					$value=1;
			} else {
				$value = $_POST[$field];
			}
			$focus->$field = $value;
		}
	}
	$focus->save();
	$return_id = $focus->id;
		
	$return_module = (!empty($_POST['return_module'])) ? $_POST['return_module'] : "TimePeriods";
	$return_action = (!empty($_POST['return_action'])) ? $_POST['return_action'] : "DetailView";
	
	$GLOBALS['log']->debug("Saved record with id of {$return_id}");
	
	header("Location: index.php?action={$return_action}&module={$return_module}&record={$return_id}");	
	}
else {
	header("Location: index.php?action=Error&module=TimePeriods&error_string=ERR_TIME_PERIOD_DATE_RANGE");
}

?>
