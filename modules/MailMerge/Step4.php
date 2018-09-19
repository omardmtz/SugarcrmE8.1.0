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
global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_user;
global $locale;

$xtpl = new XTemplate('modules/MailMerge/Step4.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

if(!empty($_POST['document_id']))
{
	$_SESSION['MAILMERGE_DOCUMENT_ID'] = $_POST['document_id'];
}
$document_id = $_SESSION['MAILMERGE_DOCUMENT_ID'];
$revision = BeanFactory::getBean('DocumentRevisions', $document_id);

if(!empty($_POST['selected_objects']))
{
	$selObjs = urldecode($_POST['selected_objects']);
	$_SESSION['SELECTED_OBJECTS_DEF'] = $selObjs;
}
$selObjs = $_SESSION['SELECTED_OBJECTS_DEF'];
$sel_obj = array();

parse_str(stripslashes(html_entity_decode($selObjs, ENT_QUOTES)),$sel_obj);
foreach($sel_obj as $key=>$value)
{
	$sel_obj[$key] = stripslashes($value);
}
$relArray = array();
//build relationship array
foreach($sel_obj as $key=>$value)
{
	$id = 'rel_id_'.md5($key);
	if(isset($_POST[$id]) && !empty($_POST[$id]))
	{
		$relArray[$key] = $_POST[$id];
	}
}


$builtArray = array();
if(count($relArray) > 0)
{
    $_SESSION['MAILMERGE_RELATED_CONTACTS'] = $relArray;

    $relModule = $_SESSION['MAILMERGE_CONTAINS_CONTACT_INFO'];
	$seed = BeanFactory::newBean($relModule);
	foreach($sel_obj as $key=>$value)
	{
		$builtArray[$key] = $value;
		if(isset($relArray[$key]))
		{
			$seed->retrieve($relArray[$key]);
			$name = "";
			if($relModule  == "Contacts"){
                $name = $locale->formatName($seed);
			}
			else{
				$name = $seed->name;
			}
				$builtArray[$key] = str_replace('##', '&', $value)." (".$name.")";
		}
	}

}
else
{
	$builtArray = $sel_obj;
}

$xtpl->assign("MAILMERGE_MODULE", $_SESSION['MAILMERGE_MODULE']);
$xtpl->assign("MAILMERGE_DOCUMENT_ID", $document_id);
$xtpl->assign("MAILMERGE_TEMPLATE", $revision->filename." (rev. ".$revision->revision.")");
$xtpl->assign("MAILMERGE_SELECTED_OBJECTS", get_select_options_with_id($builtArray,'0'));
$xtpl->assign("MAILMERGE_SELECTED_OBJECTS_DEF", urlencode($selObjs));
$step_num = 4;

if(isset($_SESSION['MAILMERGE_SKIP_REL']) && $_SESSION['MAILMERGE_SKIP_REL'] || !isset($_SESSION['MAILMERGE_RELATED_CONTACTS']) || empty($_SESSION['MAILMERGE_RELATED_CONTACTS']))
{
	$xtpl->assign("PREV_STEP", "2");
	$step_num = 3;
}
else
{
	$xtpl->assign("PREV_STEP", "3");
}

$xtpl->assign("STEP_NUM", "Step ".$step_num.":");

$xtpl->parse("main");
$xtpl->out("main");

?>
