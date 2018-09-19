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

 // $Id: html.php 51719 2009-10-22 17:18:00Z mitani $
function get_body($ss, $vardef)
{
	$edit_mod_strings = return_module_language($GLOBALS['current_language'], 'EditCustomFields');
	$ss->assign('MOD', $edit_mod_strings);

	$edValue = '';
    if(!empty($vardef['default_value'])) {
        $edValue = $vardef['default_value'];
        $edValue = str_replace(array("\r\n", "\n"), " ",$edValue);
    }
    $ss->assign('HTML_EDITOR', $edValue);
    $ss->assign('preSave', 'document.popup_form.presave();');
    $ss->assign('hideReportable', true);
	///////////////////////////////////
	return $ss->fetch('modules/DynamicFields/templates/Fields/Forms/html.tpl');
}
?>
