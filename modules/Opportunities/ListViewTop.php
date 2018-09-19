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

 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/





$current_module_strings = return_module_language($current_language, "Opportunities");
$seedOpportunity = BeanFactory::newBean('Opportunities');
	
//build top 5 opportunity list
$where = "opportunities.sales_stage <> '".$seedOpportunity::STAGE_CLOSED_LOST."' AND opportunities.sales_stage <> '".$seedOpportunity::STAGE_CLOSED_WON."' AND opportunities.assigned_user_id='".$current_user->id."'";
$header_text = '';
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){	
		$header_text = "&nbsp;<a href='index.php?action=index&module=DynamicLayout&from_action=ListView&from_module=Opportunities'>".SugarThemeRegistry::current()->getImage("EditLayout","border='0' align='bottom'",null,null,'.gif',$mod_strings['LBL_EDITLAYOUT'])."</a>";
}
$ListView = new ListView();
$ListView->initNewXTemplate( 'modules/Opportunities/ListViewTop.html',$current_module_strings);
$ListView->setHeaderTitle($current_module_strings['LBL_TOP_OPPORTUNITIES']. $header_text );
$ListView->setQuery($where, 5, "amount  DESC", "OPPORTUNITY", false);
$ListView->processListView($seedOpportunity, "main", "OPPORTUNITY");

?>
