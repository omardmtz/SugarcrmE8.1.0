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

/* 
 * func: query_module_access 
 * param: $moduleName
 * 
 * returns 1 if user has access to a module, else returns 0
 * 
 */

$modules_exempt_from_availability_check['Activities']='Activities';
$modules_exempt_from_availability_check['History']='History';
$modules_exempt_from_availability_check['Calls']='Calls';
$modules_exempt_from_availability_check['Meetings']='Meetings';
$modules_exempt_from_availability_check['Tasks']='Tasks';
//$modules_exempt_from_availability_check['Notes']='Notes';

$modules_exempt_from_availability_check['CampaignLog']='CampaignLog';
$modules_exempt_from_availability_check['CampaignTrackers']='CampaignTrackers';
$modules_exempt_from_availability_check['Prospects']='Prospects';
$modules_exempt_from_availability_check['ProspectLists']='ProspectLists';
$modules_exempt_from_availability_check['EmailMarketing']='EmailMarketing';
$modules_exempt_from_availability_check['EmailMan']='EmailMan';
$modules_exempt_from_availability_check['ProjectTask']='ProjectTask';
$modules_exempt_from_availability_check['Users']='Users';
$modules_exempt_from_availability_check['Teams']='Teams';
$modules_exempt_from_availability_check['SchedulersJobs']='SchedulersJobs';
$modules_exempt_from_availability_check['DocumentRevisions']='DocumentRevisions';
function query_module_access_list(&$user)
{
	$controller = new TabController();
	$tabArray = $controller->get_tabs($user); 

	return $tabArray[0];
		
}

function query_user_has_roles($user_id)
{
	
	
	$role = BeanFactory::newBean('Roles');
	
	return $role->check_user_role_count($user_id);
}

function get_user_allowed_modules($user_id)
{
	

	$role = BeanFactory::newBean('Roles');
	
	$allowed = $role->query_user_allowed_modules($user_id);
	return $allowed;
}

function get_user_disallowed_modules($user_id, &$allowed)
{
	

	$role = BeanFactory::newBean('Roles');
	$disallowed = $role->query_user_disallowed_modules($user_id, $allowed);
	return $disallowed;
}
// grabs client ip address and returns its value
function query_client_ip()
{
	global $_SERVER;
	$clientIP = false;
	if(!empty($GLOBALS['sugar_config']['ip_variable']) && !empty($_SERVER[$GLOBALS['sugar_config']['ip_variable']])){
		$clientIP = $_SERVER[$GLOBALS['sugar_config']['ip_variable']];
	}else if(isset($_SERVER['HTTP_CLIENT_IP']))
	{
		$clientIP = $_SERVER['HTTP_CLIENT_IP'];
	}
	elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches))
	{
		// check for internal ips by looking at the first octet
		foreach($matches[0] AS $ip)
		{
			if(!preg_match("#^(10|172\.16|192\.168)\.#", $ip))
			{
				$clientIP = $ip;
				break;
			}
		}

	}
	elseif(isset($_SERVER['HTTP_FROM']))
	{
		$clientIP = $_SERVER['HTTP_FROM'];
	}
	else
	{
		$clientIP = $_SERVER['REMOTE_ADDR'];
	}
	return $clientIP;
}

// sets value to key value
function get_val_array($arr){
	$new = array();
	if(!empty($arr)){
	foreach($arr as $key=>$val){
		$new[$key] = $key;
	}
	}
	return $new;
}

