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

global $theme;







global $app_strings;
global $app_list_strings;
global $mod_strings;

global $urlPrefix;
global $currentModule;

use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;

$seed_object = BeanFactory::newBean('DataSets');
$request = InputValidation::getService();

$where = "";
if(isset($_REQUEST['query']))
{
	$search_fields = Array("name", "description");

	$where_clauses = Array();

	append_where_clause($where_clauses, "name", "data_sets.name");

	$where = generate_where_statement($where_clauses);
	$GLOBALS['log']->info($where);
}




////////////////////////////////////////////////////////
// Start the output
////////////////////////////////////////////////////////
$reqHTML = $request->getValidInputRequest('html', 'Assert\ComponentName');
if ($reqHTML === null) {
	$form =new XTemplate ('modules/DataSets/Popup_picker.html');
	$GLOBALS['log']->debug("using file modules/DataSets/Popup_picker.html");
}
else {
	$GLOBALS['log']->debug("_REQUEST['html'] is ".$reqHTML);
	$form =new XTemplate ('modules/DataSets/'.$reqHTML.'.html');
	$GLOBALS['log']->debug("using file modules/DataSets/".$reqHTML.'.html');
}

$reqForm = $request->getValidInputRequest('form');
$description = $request->getValidInputRequest('description');
$name = $request->getValidInputRequest('name');
$selfId = $request->getValidInputRequest('self_id', 'Assert\Guid', '');

$form->assign("MOD", $mod_strings);
$form->assign("APP", $app_strings);

// the form key is required
if($reqForm === null)
	sugar_die("Missing 'form' parameter");

// This code should always return an answer.
// The form name should be made into a parameter and not be hard coded in this file.

if ($reqForm == 'EditView')
{
        $the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";
        $the_javascript .= "function set_return(parent_id, parent_name, list_order_x, list_order_y) {\n";
        $the_javascript .= "    window.opener.document.EditView.parent_name.value = parent_name;\n";
        $the_javascript .= "    window.opener.document.EditView.parent_id.value = parent_id;\n";
        $the_javascript .= "}\n";
        $the_javascript .= "</script>\n";
        $button  = "<form action='index.php' method='post' name='form' id='form'>\n";

        $button .= "<input title='".$app_strings['LBL_CLEAR_BUTTON_TITLE']."' class='button' LANGUAGE=javascript onclick=\"window.opener.document.EditView.parent_name.value = '';window.opener.document.EditView.parent_id.value = ''; window.close()\" type='submit' name='button' value='  Clear  '>\n";
        $button .= "<input title='".$app_strings['LBL_CANCEL_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CANCEL_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CANCEL_BUTTON_LABEL']."  '>\n";
        $button .= "</form>\n";
}

//if requesting from the add data_set form in the detailview of the reportmaker
if ($reqForm == 'AddDataSetEditView')
{
        $the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";
        $the_javascript .= "function set_return(data_set_id, name) {\n";
        $the_javascript .= "    window.opener.document.AddDataSetEditView.name.value = name;\n";
        $the_javascript .= "    window.opener.document.AddDataSetEditView.data_set_id.value = data_set_id;\n";
        $the_javascript .= "    window.opener.document.AddDataSetEditView.submit()\n";
        $the_javascript .= "    window.close();\n";
        $the_javascript .= "}\n";
        $the_javascript .= "</script>\n";
        $button  = "<form action='index.php' method='post' name='form' id='form'>\n";
        $button .= "<input title='".$app_strings['LBL_CANCEL_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CANCEL_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CANCEL_BUTTON_LABEL']."  '>\n";
        $button .= "</form>\n";
}

$form->assign("SET_RETURN_JS", $the_javascript);

$form->assign("MODULE_NAME", $currentModule);
$form->assign("FORM", $reqForm);

insert_popup_header($theme);

// Quick search.
echo get_form_header($mod_strings['LBL_SEARCH_FORM_TITLE'], "", false);

if ($description !== null)
{
	$last_search['DESCRIPTION'] = $description;

}

if ($name !== null)
{
	$last_search['NAME'] = $name;

}

if (isset($last_search))
{
	$form->assign("LAST_SEARCH", $last_search);
}

$form->parse("main.SearchHeader");
$form->out("main.SearchHeader");

$form->parse("main.SearchHeaderEnd");
$form->out("main.SearchHeaderEnd");

// Reset the sections that are already in the page so that they do not print again later.
$form->reset("main.SearchHeader");
$form->reset("main.SearchHeaderEnd");

// Stick the form header out there.

if ($reqForm  == 'AddDataSetEditView') {

	if(!empty($where)){
		$where .= "AND ( report_id='' OR report_id IS NULL ) AND ( parent_id='' OR parent_id IS NULL )";
	} else {
		$where = " ( report_id='' OR report_id IS NULL ) AND ( parent_id='' OR parent_id IS NULL )";	
	}

}



if ($reqForm == 'EditView') {

	if (empty($selfId)) $selfId = '';
	//Don't allow picking of parents that are itself
	if(!empty($where)) {
		$where .= "AND data_sets.id!='". $selfId ."' AND data_sets.deleted=0 ";
	} else {
		$where = "data_sets.id!='". $selfId ."' AND data_sets.deleted=0 ";
	}
	
	if(!empty($selfId)) {
		$special_where_part = "WHERE id!='". $selfId ."' AND data_sets.deleted=0";
	} else {
		$special_where_part = "WHERE data_sets.deleted=0";
	}
	//Don't allow picking of parents that already have children
	if(!empty($where)){
		$where .= " AND data_sets.id NOT IN
					 (SELECT DISTINCT parent_id from data_sets ".$special_where_part." and parent_id is not null )";
	} else {
		$where = " data_sets.id NOT IN
					 (SELECT DISTINCT parent_id from data_sets ".$special_where_part." and parent_id is not null )";	
	}
	

	
//if form is editview
}
$ListView = new ListView();
$ListView->show_delete_button = false;
$ListView->show_select_menu = false;
$ListView->show_export_button = false;
$ListView->setXTemplate($form);
$ListView->setHeaderTitle($mod_strings['LBL_LIST_FORM_TITLE']);
$ListView->setHeaderText($button);
$ListView->setQuery($where, "", "name", "DATA_SET");
$ListView->setModStrings($mod_strings);
$ListView->processListView($seed_object, "main", "DATA_SET");
?>

<?php insert_popup_footer(); ?>
