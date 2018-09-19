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
function get_validate_record_js () {
global $mod_strings;
global $app_strings;

$err_missing_required_fields = $app_strings['ERR_MISSING_REQUIRED_FIELDS'];
$err_lbl_send_message= $mod_strings['LBL_MESSAGE_FOR'];
$the_script  = <<<EOQ

<script type="text/javascript" language="Javascript">
function verify_data(form,formname) {
	if (!check_form(formname))
		return false;

	var isError = false;
	var errorMessage = "";
		
	var thecheckbox=document.getElementById('all_prospect_lists');
	var theselectbox=document.getElementById('message_for');		

	if (!thecheckbox.checked && theselectbox.selectedIndex < 0)  {
		isError=true;
		errorMessage="$err_lbl_send_message";
	}
			
	if (isError == true) {
		alert("$err_missing_required_fields" + errorMessage);
		return false;
	}
	return true;
}
</script>

EOQ;

return $the_script;

}

/**
 * Create HTML form to enter a new record with the minimum necessary fields.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */

?>
