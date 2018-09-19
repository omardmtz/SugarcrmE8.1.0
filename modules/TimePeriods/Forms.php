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

 * Description:  Contains a variety of utility functions used to display UI
 * components such as form headers and footers.  Intended to be modified on a per
 * theme basis.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

/**
 * Create javascript to validate the data entered into a record.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_validate_record_js () {
global $mod_strings;
global $app_strings;

$lbl_name = $mod_strings['LBL_TP_NAME'];
$lbl_start_date = $mod_strings['LBL_TP_START_DATE'];
$lbl_end_date=$mod_strings['LBL_TP_END_DATE'];
$lbl_fiscal_year=$mod_strings['LBL_TP_END_DATE'];
$lbl_fiscal_year=$mod_strings['LBL_TP_IS_FISCAL_YEAR'];


$err_missing_required_fields = $app_strings['ERR_MISSING_REQUIRED_FIELDS'];


$the_script  = <<<EOQ

<script type="text/javascript" language="Javascript">
function verify_data(form) {
	var isError = false;
	var errorMessage = "";
	if (trim(form.name.value) == "") {
		isError = true;
		errorMessage += "\\n$lbl_name";
	}
	if (trim(form.start_date.value) == "") {
		isError = true;
		errorMessage += "\\n$lbl_start_date";
	}
	if (trim(form.end_date.value) == "") {
		isError = true;
		errorMessage += "\\n$lbl_end_date";
	}

	if (parseInt(form.is_fiscal_year) == 0) {
  	    if (trim(form.parent_id.value) == "") {
		    isError = true;
		    errorMessage += "\\n$lbl_fiscal_year";
  	    }
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

function get_chooser_js()
{
$the_script  = <<<EOQ

<script type="text/javascript" language="Javascript">
<!--  to hide script contents from old browsers

function set_chooser()
{



var display_tabs_def = '';

for(i=0; i < object_refs['display_tabs'].options.length ;i++)
{
         display_tabs_def += "display_tabs[]="+object_refs['display_tabs'].options[i].value+"&";
}

document.EditView.display_tabs_def.value = display_tabs_def;



}
// end hiding contents from old browsers  -->
</script>
EOQ;

return $the_script;
}

?>
