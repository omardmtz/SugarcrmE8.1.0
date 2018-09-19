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
require_once('include/formbase.php');

$focus = BeanFactory::newBean('Schedulers');
$focus = populateFromPost('', $focus);

///////////////////////////////////////////////////////////////////////////////
////	USE_ADV override
if(!isset($_REQUEST['adv_interval']) || $_REQUEST['adv_interval'] == 'false' || $_REQUEST['adv_interval'] == '0') {
	// days of week
	$xtDays = array(1 => 'mon',
					2 => 'tue',
					3 => 'wed',
					4 => 'thu',
					5 => 'fri',
					6 => 'sat',
					0 => 'sun');
					
	if(	(isset($_REQUEST['mon']) && $_REQUEST['mon'] == 'true') &&
		(isset($_REQUEST['tue']) && $_REQUEST['tue'] == 'true') &&
		(isset($_REQUEST['wed']) && $_REQUEST['wed'] == 'true') &&
		(isset($_REQUEST['thu']) && $_REQUEST['thu'] == 'true') &&
		(isset($_REQUEST['fri']) && $_REQUEST['fri'] == 'true') &&
		(isset($_REQUEST['sat']) && $_REQUEST['sat'] == 'true') &&
		(isset($_REQUEST['sun']) && $_REQUEST['sun'] == 'true') ) {
		$_REQUEST['day_of_week'] = '*';
	} else {
		$day_string = '';
		foreach($xtDays as $k => $day) {
			if(isset($_REQUEST[$day]) && $_REQUEST[$day] == 'true') {
				if($day_string != '') {
					$day_string .= ',';
				}
				$day_string .= $k;
			}
		}
		$_REQUEST['day_of_week'] = $day_string;
	}


	if($_REQUEST['basic_period'] == 'min') {
		$_REQUEST['mins'] = '*/'.$_REQUEST['basic_interval'];
		$_REQUEST['hours'] = '*';
	} else {
        // Bug # 44933 - hours cannot be greater than 23
        if ($_REQUEST['basic_interval'] < 24) {
		    $_REQUEST['hours'] = '*/'.$_REQUEST['basic_interval'];
        } else {
           $_REQUEST['hours'] = '0'; // setting it to run midnight every 24 hours
        }
        $_REQUEST['mins'] = '0';    // on top of the hours
	} 
}

////	END USE_ADV override
///////////////////////////////////////////////////////////////////////////////
$focus->job_interval = $_REQUEST['mins']."::".$_REQUEST['hours']."::".$_REQUEST['day_of_month']."::".$_REQUEST['months']."::".$_REQUEST['day_of_week'];
// deal with job types
// neither
if ( ($_REQUEST['job_function'] == 'url::') && ($_REQUEST['job_url'] == '' || $_REQUEST['job_url'] == 'http://') ) {
	$GLOBALS['log']->fatal('Scheduler save did not get a job_url or job_function');
} elseif ( ($_REQUEST['job_function'] != 'url::') && ($_REQUEST['job_url'] != '' && $_REQUEST['job_url'] != 'http://') ) {
	$GLOBALS['log']->fatal('Scheduler got both a job_url and job_function');
}
//function
if ( ($_REQUEST['job_function'] != 'url::')) {
	$focus->job = $_REQUEST['job_function'];
} elseif ( $_REQUEST['job_url'] != '' && $_REQUEST['job_url'] != 'http://') { // url
	$focus->job = 'url::'.$_REQUEST['job_url'];
} // url wins if both passed

// save should refresh ALL jobs
$focus->save();
$return_id = $focus->id;

$edit='';
if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = $_REQUEST['return_module'];
else $return_module = "Schedulers";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = $_REQUEST['return_action'];
else $return_action = "DetailView";
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = $_REQUEST['return_id'];
if(!empty($_REQUEST['edit'])) {
	$return_id='';
	$edit='edit=true';
}

$GLOBALS['log']->debug("Saved record with id of ".$return_id);

header("Location: index.php?action=$return_action&module=$return_module&record=$return_id&$edit");
?>