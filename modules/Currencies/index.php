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

 ********************************************************************************/






global $theme;
global $mod_strings;
global $app_list_strings;
global $app_strings;
global $current_user, $focus;

echo getClassicModuleTitle(
        "Administration", 
        array(
            "<a href='index.php?module=Administration&action=index'>".translate('LBL_MODULE_NAME','Administration')."</a>",
           $mod_strings['LBL_MODULE_NAME'],
           ), 
        false
        );

if($current_user->is_admin){

$focus = BeanFactory::newBean('Currencies');
$lc = new ListCurrency();
$lc->handleAdd();

// Flag that tells the template whether to request a new metadata payload
$refreshMetadata = !empty($lc->recordSaved);

if(isset($_REQUEST['merge']) && $_REQUEST['merge'] == 'true'){
	$isMerge = true;
	
}
if(isset($_REQUEST['domerge'])){
	$currencies = $_REQUEST['mergecur'];
	
	
	$opp = BeanFactory::newBean('Opportunities');
	$opp->update_currency_id($currencies, $_REQUEST['mergeTo'] );
	
	$product = BeanFactory::newBean('ProductTemplates');
	$product->update_currency_id($currencies, $_REQUEST['mergeTo'] );

	$quote = BeanFactory::newBean('Quotes');
	$quote->update_currency_id($currencies, $_REQUEST['mergeTo'] );
	foreach($currencies as $cur){
		if($cur != $_REQUEST['mergeTo'])
		$focus->mark_deleted($cur);
	}
}
$lc->lookupCurrencies();
if (isset($focus->id)) $focus_id = $focus->id;
else $focus_id='';
$merge_button = '';
$pretable = '';
if((isset($_REQUEST['doAction']) && $_REQUEST['doAction'] == 'merge') || (isset($isMerge) && !$isMerge)){
$merge_button = '<form name= "MERGE" method="POST" action="index.php"><input type="hidden" name="module" value="Currencies"><input type="hidden" name="record" value="'.$focus_id.'"><input type="hidden" name="action" value="index"><input type="hidden" name="merge" value="true"><input title="'.$mod_strings['LBL_MERGE'].'"  class="button"  type="submit" name="button" value="'.$mod_strings['LBL_MERGE'].'" ></form>';
}
if(isset($isMerge) && $isMerge){
	$currencyList = new ListCurrency();
	$listoptions = $currencyList->getSelectOptions();
	$pretable =  <<<EOQ
		<form name= "MERGE" method="POST" action="index.php">
			<input type="hidden" name="module" value="Currencies">
			
			<input type="hidden" name="action" value="index">
		<table width="100%" cellspacing="0" cellpadding="0" border="0" class="edit view">
			<tr><td><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr>
			<td>{$mod_strings['LBL_MERGE_TXT']}</td><td width='20%'><select name='mergeTo'>{$listoptions}</select></td>
			</tr>
			<tr><td></td><td><input title="{$mod_strings['LBL_MERGE']}" class="button" type="submit" name="domerge" value="{$mod_strings['LBL_MERGE']}" >
		<input title="{$app_strings['LBL_CANCEL_BUTTON_TITLE']}" accessKey="{$app_strings['LBL_CANCEL_BUTTON_KEY']}" class="button"  type="submit" name="button" value="{$app_strings['LBL_CANCEL_BUTTON_LABEL']}" > </td></tr>
			</table></td></tr></table><br>
EOQ;
	

}
$edit_botton = '<form name="EditView" method="POST" action="index.php" >';
			$edit_botton .= '<input type="hidden" name="module" value="Currencies">';
			$edit_botton .= '<input type="hidden" name="record" value="'.$focus_id.'">';
			$edit_botton .= '<input type="hidden" name="action">';
			$edit_botton .= '<input type="hidden" name="edit">';
			$edit_botton .= '<input type="hidden" name="return_module" value="Currencies">';
			$edit_botton .= '<input type="hidden" name="return_action" value="index">';
			$edit_botton .= '<input type="hidden" name="return_id" value="">';
$header_text = '';
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){	
		$header_text = "&nbsp;<a href='index.php?action=index&module=DynamicLayout&from_action=ListView&from_module=".$_REQUEST['module'] ."'>".SugarThemeRegistry::current()->getImage("EditLayout","border='0' align='bottom'",null,null,'.gif',$mod_strings['LBL_EDIT_LAYOUT'])."</a>";
	}
$ListView = new ListView();
$ListView->initNewXTemplate( 'modules/Currencies/ListView.html',$mod_strings);
$ListView->xTemplateAssign('PRETABLE', $pretable);
$ListView->xTemplateAssign('POSTTABLE', '</form>');
//$ListView->setHeaderTitle($mod_strings['LBL_LIST_FORM_TITLE']. $header_text );
$ListView->setHeaderText($merge_button);

$ListView->processListView($lc->list, "main", "CURRENCY");

if(isset($_GET['record']) && !empty($_GET['record']) && !isset($_POST['edit'])) { 
	$focus->retrieve($_GET['record']);
	$focus->conversion_rate = format_number($focus->conversion_rate, 10, 10);
}
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){	
		$header_text = "&nbsp;<a href='index.php?action=index&module=DynamicLayout&from_action=EditView&from_module=".$_REQUEST['module'] ."'>".SugarThemeRegistry::current()->getImage("EditLayout","border='0' align='bottom'", null,null,'.gif',$mod_strings['LBL_EDIT_LAYOUT'])."</a>";
}
if ( empty($focus->id) ) {
    echo get_form_header($app_strings['LBL_CREATE_BUTTON_LABEL'] . ' Currency'. $header_text,$edit_botton , false);
}
else {
    echo get_form_header($app_strings['LBL_EDIT_BUTTON_LABEL']." &raquo; ".$focus->name . $header_text,$edit_botton , false); 
}
$sugar_smarty = new Sugar_Smarty();

	$sugar_smarty->assign("MOD", $mod_strings);
	$sugar_smarty->assign("APP", $app_strings);

// Load in the full ISO 4217 list, so we can dynamically populate the currency strings
    require_once('modules/Currencies/iso4217.php');
    $json = getJSONobj();
    $js_iso4217 = $json->encode($fullIsoList);
    $sugar_smarty->assign('JS_ISO4217',$js_iso4217);
	
	if (isset($_REQUEST['return_module'])) $sugar_smarty->assign("RETURN_MODULE", $_REQUEST['return_module']);
	if (isset($_REQUEST['return_action'])) $sugar_smarty->assign("RETURN_ACTION", $_REQUEST['return_action']);
	if (isset($_REQUEST['return_id'])) $sugar_smarty->assign("RETURN_ID", $_REQUEST['return_id']);
	
	$sugar_smarty->assign("JAVASCRIPT", get_set_focus_js());
    $sugar_smarty->assign("THEME", SugarThemeRegistry::current()->__toString());
	$sugar_smarty->assign("ID", $focus->id);
	$sugar_smarty->assign('NAME', $focus->name);
	$sugar_smarty->assign('STATUS', $focus->status);
	$sugar_smarty->assign('ISO4217', $focus->iso4217);
	$sugar_smarty->assign('CONVERSION_RATE', $focus->conversion_rate);
	$sugar_smarty->assign('SYMBOL', $focus->symbol);
	$sugar_smarty->assign('STATUS_OPTIONS', get_select_options_with_id($mod_strings['currency_status_dom'], $focus->status));
	
	//if (empty($focus->list_order)) $xtpl->assign('LIST_ORDER', count($focus->get_manufacturers(false,'All'))+1); 
	//else $xtpl->assign('LIST_ORDER', $focus->list_order);
	
	$sugar_smarty->assign('REFRESHMETADATA', $refreshMetadata);
	$sugar_smarty->display("modules/Currencies/EditView.tpl");
	
	$javascript = new javascript();
	$javascript->setFormName('EditView');
	$javascript->setSugarBean($focus);
	$javascript->addAllFields('',array('iso4217'=>'iso4217'));
	echo $javascript->getScript();
    echo("<script type='text/javascript'>addToValidateMoreThan('EditView','conversion_rate','float',true,'".$mod_strings['LBL_BELOW_MIN']."',0.000001);</script>");
}
else
{
    echo $mod_strings['LBL_ADMIN_ONLY'];
}

?>
