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

// $Id: DefaultNotices.php 45763 2009-04-01 19:16:18Z majed $

$notices = array(
);


foreach($notices as $notice){
	$teamNotice = BeanFactory::newBean('TeamNotices');
	$teamNotice->name = $notice['name'];
	$teamNotice->description = $notice['description'];
	if(!empty($notice['url'])){
		$teamNotice->url = $notice['url'];
		$teamNotice->url_title = 'View '.$notice['name'];
	}
	$teamNotice->date_start = $timedate->nowDate();
	$teamNotice->date_end = $timedate->asUserDate($timedate->getNow()->get('+1 week'));
	$teamNotice->status = 'Visible';
	$teamNotice->save(false);
}

?>
