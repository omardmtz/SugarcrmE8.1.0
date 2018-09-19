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
global $theme;
global $mod_strings;
global $app_list_strings;
global $current_user;

if (!$current_user->isAdminForModule("InboundEmail")) {
    sugar_die(translate('ERR_NOT_ADMIN'));
}

$focus = BeanFactory::newBean('InboundEmail');
$focus->checkImap();

///////////////////////////////////////////////////////////////////////////////
////	I-E SYSTEM SETTINGS
////	handle saving settings
if(isset($_REQUEST['save']) && $_REQUEST['save'] == 'true') {
	$focus->saveInboundEmailSystemSettings('Case', $_REQUEST['inbound_email_case_macro']);
}
////	END I-E SYSTEM SETTINGS
///////////////////////////////////////////////////////////////////////////////

if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){	
	$ListView->setHeaderText("<a href='index.php?action=index&module=DynamicLayout&from_action=ListView&from_module=".$_REQUEST['module'] ."'>".SugarThemeRegistry::current()->getImage("EditLayout","border='0' align='bottom'",null,null,'.gif',$mod_strings['LBL_EDIT_LAYOUT'])."</a>" );
}

$where = '';
$limit = '0';
$orderBy = 'date_entered';
$varName = $focus->object_name;
$allowByOverride = true;

$listView = new ListView();
$listView->initNewXTemplate('modules/InboundEmail/ListView.html', $mod_strings);
$listView->setHeaderTitle($mod_strings['LBL_MODULE_TITLE']);

echo $focus->getSystemSettingsForm();
$listView->show_export_button = false;
$listView->ignorePopulateOnly = TRUE; //Always show all records, ignore save_query performance setting.
$listView->setQuery($where, $limit, $orderBy, 'InboundEmail', $allowByOverride);
$listView->xTemplateAssign("EDIT_INLINE_IMG", SugarThemeRegistry::current()->getImage('edit_inline','align="absmiddle" border="0"', null,null,'.gif',$app_strings['LNK_EDIT']));
$listView->processListView($focus, "main", "InboundEmail");

