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

require_once('include/utils/expression_utils.php');
global $app_strings;
global $app_list_strings;
global $mod_strings;

global $urlPrefix;
global $currentModule;
global $sugar_version, $sugar_config;

$focus = BeanFactory::newBean('Expressions');

if(!empty($_REQUEST['type'])) {
    $type = $_REQUEST['type'];
} else {
	sugar_die("You shouldn't be here");
}
if(!empty($_REQUEST['value'])) {
    $value = $_REQUEST['value'];
} else {
	$value ="";
}
if(!empty($_REQUEST['dom_name'])) {
    $dom_name = $_REQUEST['dom_name'];
} else {
	$dom_name ="";
}
if(!empty($_REQUEST['meta_filter_name'])) {
    $meta_filter_name = $_REQUEST['meta_filter_name'];
} else {
	$meta_filter_name ="";
}
if(!empty($_REQUEST['trigger_type'])) {
    $trigger_type = $_REQUEST['trigger_type'];
} else {
	$trigger_type = "";
}


////////////////////////////////////////////////////////
// Start the output
////////////////////////////////////////////////////////
	$form =new XTemplate ('modules/Expressions/SingleSelector.html');
	$GLOBALS['log']->debug("using file modules/Expressions/SingleSelector.html");

$form->assign("MOD", $mod_strings);
$form->assign("APP", $app_strings);
$form->assign("PLEASE_SELECT", $mod_strings['LBL_PLEASE_SELECT']);
$form->assign("OPENER_ID", $_REQUEST['opener_id']);
$form->assign("HREF_OBJECT", $_REQUEST['href_object']);

$select_options = $focus->get_selector_array($type, $value, $dom_name, false, $meta_filter_name, true, $trigger_type, false);

$form->assign("SELECTOR_DROPDOWN", $select_options);

$form->assign("MODULE_NAME", $currentModule);
$form->assign("GRIDLINE", $gridline);

insert_popup_header($theme);

$form->parse("embeded");
$form->out("embeded");


$form->parse("main");
$form->out("main");

insert_popup_footer();