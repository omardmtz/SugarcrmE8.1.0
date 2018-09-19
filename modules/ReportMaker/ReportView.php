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

 * Description:
 ********************************************************************************/









global $mod_strings;
global $app_strings;
global $app_list_strings;


$focus = BeanFactory::newBean('ReportMaker');
if(!empty($_REQUEST['record'])) {
    $result = $focus->retrieve($_REQUEST['record']);
    if($result == null)
    {
    	sugar_die($app_strings['ERROR_NO_RECORD']);
    }
}
else {
	header("Location: index.php?module=ReportMaker&action=index");
}
echo getClassicModuleTitle("Report Maker", array($mod_strings['LBL_MODULE_TITLE'] . " " . $focus->name), true);

$button  = "<table cellspacing='0' border='0'><form  action='index.php' method='post' name='form' id='form'>\n";
$button .= "<input type='hidden' name='module' value='ReportMaker'>\n";
$button .= "<input type='hidden' name='return_module' value='".$currentModule."'>\n";
$button .= "<input type='hidden' name='return_action' value='".$action."'>\n";
$button .= "<input type='hidden' name='return_id' value='".$focus->id."'>\n";
$button .= "<input type='hidden' name='record' value='".$focus->id."'>\n";
$button .= "<input type='hidden' name='action'>\n";
$button .= "<input title='".$mod_strings['LBL_DETAILS_BUTTON_TITLE']."' class='button' onclick=\"this.form.action.value='DetailView'\" type='submit' name='button' value='  ".$mod_strings['LBL_DETAILS_BUTTON_LABEL']."  '>\n";

if (SugarACL::checkAccess($currentModule, 'edit')) {
    $button .= "<input title='".$mod_strings['LBL_EDIT_BUTTON_TITLE']."' accessKey='".$mod_strings['LBL_EDIT_BUTTON_KEY']."' class='button' onclick=\"this.form.action.value='EditView'\" type='submit' name='button' value='  ".$mod_strings['LBL_EDIT_BUTTON_LABEL']."  '>\n";
}

$button .= "</form></table>\n";

echo "$button";


//This is where we run the report itself

$data_set_list = $focus->get_data_sets("ORDER BY list_order_y ASC");

$header_xtpl=new XTemplate ('modules/ReportMaker/ReportHeaderView.html');
$header_xtpl->assign("REPORT_ALIGN", $focus->report_align);
$header_xtpl->assign("REPORT_TITLE", $focus->title);

echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\"> \n";
echo "<tr><td>";

	$header_xtpl->parse("main");
	$header_xtpl->out("main");	

echo "</td></tr>";

foreach($data_set_list AS $item => $data_object){

	echo "<tr><td align=\"$focus->report_align\"> \n";

	if($data_object->prespace_y=="on"){
		echo "<p><p>";	
	}		

	//CHECK FOR SUB-QUERIES
	$data_object->check_interlock();
	
//first check to see if the data format actually has a query_id for it	
	if(!empty($data_object->query_id) && $data_object->query_id!=""){
	
	//OUTPUT THE DATASET
	$data_set = BeanFactory::getBean('CustomQueries', $data_object->query_id);
	$QueryView = new ReportListView();
	
	//pass the previous width array if available
	if(isset($prev_width_array) && $prev_width_array!=''){
		$QueryView->prev_width_array = $prev_width_array;
	}	
	
	$QueryView->initNewXTemplate( 'modules/CustomQueries/QueryView.html',$mod_strings);
	$QueryView->setDisplayHeaderAndFooter(false);
	$QueryView->setHeaderTitle($data_object->name);
	
	//below: make sure to aquire the custom layout headers if available
	$QueryView->export_type = "Ent";
	
	$QueryView->final_report_view = true;
	$QueryView->setup($data_set, $data_object, "main", "CUSTOMQUERY");
	$query_results = $QueryView->processDataSet();
	//capture previous width array if necessary
	$prev_width_array = $QueryView->prev_width_array;
		
	if($query_results['result']=="Error"){
	
		if (is_admin($current_user))
		{	
			echo "<font color=\"red\"><b>".$query_results['result_msg']."".$app_strings['ERROR_EXAMINE_MSG']."</font><BR>".$query_results['msg']."</b>";	
		} else {
			echo "<font color=\"red\"><b>".$query_results['result_msg']."</font></b><BR>";	
		}	

	}
	
	//end if there is even a query for the data set
	} else {
		echo "<font color=\"red\"><b>".$app_strings['NO_QUERY_SELECTED']."</font></b><BR>";	
	}
	
echo "</td></tr> \n";
//end foreach
}


echo "</table> \n";
		
?>
