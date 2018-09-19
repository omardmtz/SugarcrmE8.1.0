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

if(isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'show_raw') {
	if(!class_exists("Email")) {

	}
	$email = BeanFactory::getBean('Emails', $_REQUEST['metadata']);
	echo nl2br(SugarCleaner::cleanHtml($email->raw_source));
} else {
	require_once('include/Popups/Popup_picker.php');
	$popup = new Popup_Picker();
	echo $popup->process_page();
}

?>
