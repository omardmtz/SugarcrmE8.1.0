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

if(!empty($_REQUEST['job_id'])) {
	
	
	$job_id = $_REQUEST['job_id'];

	if(empty($GLOBALS['log'])) { // setup logging
		
		$GLOBALS['log'] = LoggerManager::getLogger('SugarCRM'); 	
	}
	ob_implicit_flush();
	ignore_user_abort(true);// keep processing if browser is closed
	set_time_limit(0);// no time out
    $db = DBManagerFactory::getInstance();
	$GLOBALS['log']->debug('Job [ '.$job_id.' ] is about to FIRE. Updating Job status in DB');
    $qLastRun = "UPDATE schedulers SET last_run = " .
        $db->quoted($runTime) .
        " WHERE id = " .
        $db->quoted($job_id);
    $db->query($qLastRun);
	
	$job = new Job();
	$job->runtime = TimeDate::getInstance()->nowDb();
	if($job->startJob($job_id)) {
		$GLOBALS['log']->info('----->Job [ '.$job_id.' ] was fired successfully');
	} else {
		$GLOBALS['log']->fatal('----->Job FAILURE job [ '.$job_id.' ] could not complete successfully.');
	}
	
	$GLOBALS['log']->debug('Job [ '.$a['job'].' ] has been fired - dropped from schedulers_times queue and last_run updated');
	$this->finishJob($job_id);
	return true;
} else {
	$GLOBALS['log']->fatal('JOB FAILURE JobThread.php called with no job_id.  Suiciding this thread.');
	die();
}
?>
