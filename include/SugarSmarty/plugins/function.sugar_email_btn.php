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


 /**
 * smarty_function_sugar_email_btn
 * This is the constructor for the Smarty plugin.
 * This function exists so that the proper email button based on user prefs is loaded into the quotes module.
 * 
 * @param $params The runtime Smarty key/value arguments
 * @param $smarty The reference to the Smarty object used in this invocation
 */
function smarty_function_sugar_email_btn($params, &$smarty)
{
	global $app_strings, $current_user;
	$pdfButtons = '';
	$client = $current_user->getPreference('email_link_type');
	if ($client != 'sugar') {
		$pdfButtons = '<input title="'. $app_strings["LBL_EMAIL_COMPOSE"] . '" class="button" type="submit" name="button" value="'. $app_strings["LBL_EMAIL_COMPOSE"] . '" onclick="location.href=\'mailto:\';return false;"> ';
	} else {
		$pdfButtons = '<input id="email_as_pdf_button" title="'. $app_strings["LBL_EMAIL_PDF_BUTTON_TITLE"] . '" class="button" type="submit" name="button" value="'. $app_strings["LBL_EMAIL_PDF_BUTTON_LABEL"] . '" onclick="this.form.email_action.value=\'EmailLayout\';"> ';
	}
	return $pdfButtons;
}
?>
