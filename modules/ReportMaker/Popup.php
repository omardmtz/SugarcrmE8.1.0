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


$seed_object = BeanFactory::newBean('DataSets');


$where = "";
if(isset($_REQUEST['query']))
{
	$search_fields = Array("name", "description");

	$where_clauses = Array();

	append_where_clause($where_clauses, "name", "data_sets.name");
	append_where_clause($where_clauses, "description", "data_sets.description");

	$where = generate_where_statement($where_clauses);
	$GLOBALS['log']->info($where);
}




////////////////////////////////////////////////////////
// Start the output
////////////////////////////////////////////////////////
if (!isset($_REQUEST['html'])) {
	$form =new XTemplate ('modules/DataSets/Popup_picker.html');
	$GLOBALS['log']->debug("using file modules/DataSets/Popup_picker.html");
}
else {
    $_REQUEST['html'] = preg_replace("/[^a-zA-Z0-9_]/", "", $_REQUEST['html']);
    $GLOBALS['log']->debug("_REQUEST['html'] is ".$_REQUEST['html']);
	$form =new XTemplate ('modules/DataSets/'.$_REQUEST['html'].'.html');
	$GLOBALS['log']->debug("using file modules/DataSets/".$_REQUEST['html'].'.html');
}

$form->assign("MOD", $mod_strings);
$form->assign("APP", $app_strings);

// the form key is required
if(!isset($_REQUEST['form']))
	sugar_die("Missing 'form' parameter");

// This code should always return an answer.
// The form name should be made into a parameter and not be hard coded in this file.

if ($_REQUEST['form'] == 'EditView')
{
        $the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";
        $the_javascript .= "function set_return(parent_id, parent_name) {\n";
        $the_javascript .= "    window.opener.document.EditView.parent_name.value = parent_name;\n";
        $the_javascript .= "    window.opener.document.EditView.parent_id.value = parent_id;\n";
        $the_javascript .= "}\n";
        $the_javascript .= "</script>\n";
        $button  = "<form action='index.php' method='post' name='form' id='form'>\n";

        $button .= "<input title='".$app_strings['LBL_CLEAR_BUTTON_TITLE']."' class='button' LANGUAGE=javascript onclick=\"window.opener.document.EditView.parent_name.value = '';window.opener.document.EditView.parent_id.value = ''; window.close()\" type='submit' name='button' value='  Clear  '>\n";
        $button .= "<input title='".$app_strings['LBL_CANCEL_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CANCEL_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CANCEL_BUTTON_LABEL']."  '>\n";
        $button .= "</form>\n";
}


$form->assign("SET_RETURN_JS", $the_javascript);
$form->assign("MODULE_NAME", $currentModule);
$form->assign("FORM", $_REQUEST['form']);

insert_popup_header($theme);

// Quick search.
echo get_form_header($mod_strings['LBL_SEARCH_FORM_TITLE'], "", false);

if (isset($_REQUEST['description']))
{
	$last_search['DESCRIPTION'] = $_REQUEST['description'];

}

if (isset($_REQUEST['name']))
{
	$last_search['NAME'] = $_REQUEST['name'];

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



$ListView = new ListView();
$ListView->show_export_button = false;
$ListView->setXTemplate($form);
$ListView->setHeaderTitle($mod_strings['LBL_LIST_FORM_TITLE']);
$ListView->setHeaderText($button);
$ListView->setQuery($where, "", "name", "DATA_SET");
$ListView->setModStrings($mod_strings);
$ListView->processListView($seed_object, "main", "DATA_SET");
?>

<?php insert_popup_footer(); ?>
