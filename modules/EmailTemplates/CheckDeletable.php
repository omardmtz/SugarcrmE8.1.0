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
 * $Header$
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/


$focus = BeanFactory::newBean('EmailTemplates');
if($_REQUEST['from'] == 'DetailView') {
	if(!isset($_REQUEST['record']))
		sugar_die("A record number must be specified to delete the template.");
	$focus->retrieve($_REQUEST['record']);
	if(check_email_template_in_use($focus)) {
		echo 'true';
		return;
	}
	echo 'false';
} else if($_REQUEST['from'] == 'ListView') {
	$returnString = '';
	$idArray = explode(',', $_REQUEST['records']);
	foreach($idArray as $key => $value) {
		if($focus->retrieve($value)) {
			if(check_email_template_in_use($focus)) {
				$returnString .= $focus->name . ',';
			}
		}
	}
	$returnString = substr($returnString, 0, -1);
	echo $returnString;
} else {
	echo '';
}

function check_email_template_in_use($focus)
{
	if($focus->is_used_by_email_marketing()) {
		return true;
	}
	$system = $GLOBALS['sugar_config']['passwordsetting'];
	if($focus->id == $system['generatepasswordtmpl'] || $focus->id == $system['lostpasswordtmpl']) {
	    return true;
	}
    return false;
}
