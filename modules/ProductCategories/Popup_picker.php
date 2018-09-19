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

 * Description:  This file is used for all popups on this module
 * The popup_picker.html file is used for generating a list from which to find and
 * choose one instance.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

global $theme;

// do not remove these extra lines, we need the file size to be bigger than 8k to workaround
// a php 5.4 bug that causes honeycomb builds to fail






global $app_strings;
global $app_list_strings;
global $mod_strings;

global $urlPrefix;
global $currentModule;

//include tree view classes.
require_once('vendor/ytree/Tree.php');
require_once('vendor/ytree/Node.php');

require_once('modules/ProductCategories/TreeData.php');


class Popup_Picker
{
    function process_page()
    {
        global $mod_strings, $app_strings, $currentModule,
               $seed_object, $sugar_version, $sugar_config;
        $focus = BeanFactory::newBean('ProductCategories');

        if (!isset($_REQUEST['html'])) {
            $xtpl =new XTemplate ('modules/ProductCategories/Popup_picker.html');
            $GLOBALS['log']->debug("using file modules/ProductCategories/Popup_picker1.html");
        }
        else {
            $_REQUEST['html'] = preg_replace("/[^a-zA-Z0-9_]/", "", $_REQUEST['html']);
            $GLOBALS['log']->debug("_REQUEST['html'] is ".$_REQUEST['html']);
            $xtpl =new XTemplate ('modules/ProductCategories/'.$_REQUEST['html'].'.html');
            $GLOBALS['log']->debug("using file modules/ProductCategories/".$_REQUEST['html'].'.html');
        }
        insert_popup_header();

        //tree header.
        $prodcattree=new Tree('productcategories');
        $prodcattree->set_param('module','ProductCategories');

        $parents=array();
        if (!empty($_REQUEST['parent_category_id'])) {
            $parents=$this->find_parents($_REQUEST['parent_category_id']);
        }

       $nodes=get_product_categories(null,$parents);
       foreach ($nodes as $node) {
          $prodcattree->add_node($node);
       }
        $xtpl->assign("TREEHEADER",$prodcattree->generate_header());
        $xtpl->assign("TREEINSTANCE",$prodcattree->generate_nodes_array());

        $xtpl->assign("MODULE_NAME", $currentModule);

        global $sugar_config;

        $site_data = "<script> var site_url= {\"site_url\":\"".getJavascriptSiteURL()."\"};</script>\n";
        $xtpl->assign("SITEURL",$site_data);

        if (!empty($_REQUEST['form']) && $_REQUEST['form'] == 'EditView')
        {
            $the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";
            $the_javascript .= "function set_return(treeid) { \n";
            $the_javascript .= "    node=YAHOO.namespace(treeid).selectednode;";
            $the_javascript .= "    window.opener.document.EditView.parent_id.value = node.data.id;\n";
            $the_javascript .= "    window.opener.document.EditView.parent_name.value = node.label;\n";
            $the_javascript .="     window.close();\n";
            $the_javascript .= "}\n";
            $the_javascript .= "</script>\n";
            $clear_button = "<input title='".$app_strings['LBL_CLEAR_BUTTON_TITLE']."' class='button' LANGUAGE=javascript onclick=\"window.opener.document.EditView.parent_id.value = '';window.opener.document.EditView.parent_name.value = ''; window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CLEAR_BUTTON_LABEL']."  '>\n";
            $cancel_button = "<input title='".$app_strings['LBL_CANCEL_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CANCEL_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CANCEL_BUTTON_LABEL']."  '>\n";
        }

        if (empty($_REQUEST['form']) || $_REQUEST['form'] == 'EditView' && $_REQUEST['tree'] == 'ProdCat')
        {
            $seed_object->show_products = FALSE;
            $the_javascript = <<<END
            <script type='text/javascript' language='JavaScript'>
                var field_id = null;
                var field_name = null;

                function set_return(treeid) {

                    if(typeof treeid != 'undefined') {
                        node=YAHOO.namespace(treeid).selectednode;
                    } else {
                        node = {'data': {'id': ''}, 'label': ''};
                    }

                    if (typeof window.opener.document.forms.search_form != 'undefined' || window.opener.document.forms.popup_query_form != 'undefined') { // Search
                        var form = (typeof window.opener.document.forms.search_form != 'undefined') ? window.opener.document.forms.search_form : window.opener.document.forms.popup_query_form;
                        var searchType = (typeof form.searchFormTab != 'undefined' && form.searchFormTab.value == 'basic_search') ? 'basic' : 'advanced';

                        field_id = form['category_id_'   + searchType];
                        field_name = form['category_name_'   + searchType];
                    } else if(typeof window.opener.document.ReportsWizardForm != 'undefined') { // reports
                        field_id = window.opener.document.ReportsWizardForm['ProductCategories:name:id:1'];
                        field_name = window.opener.document.ReportsWizardForm['ProductCategories:name:name:1'];
                    } else if(typeof window.opener.document.EditView != 'undefined') {
                        field_id = window.opener.document.EditView.category_id;
                        field_name = window.opener.document.EditView.category_name;
                    }

                    if(field_id != null && field_name != null) {
                        field_id.value = node.data.id;
                        field_name.value = node.label;
                    }

                    window.close();
                }
            </script>
END;
            $clear_button = "<input title='".$app_strings['LBL_CLEAR_BUTTON_TITLE']."' class='button' LANGUAGE=javascript onclick=\"set_return()\" type='submit' name='button' value='  ".$app_strings['LBL_CLEAR_BUTTON_LABEL']."  '>\n";
            $cancel_button = "<input title='".$app_strings['LBL_CANCEL_BUTTON_TITLE']."' class='button' LANGUAGE=javascript onclick=\"window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CANCEL_BUTTON_LABEL']."  '>\n";
        }

        $xtpl->assign("SET_RETURN_JS", $the_javascript);
        $xtpl->assign("CLEAR_BUTTON", $clear_button);
        $xtpl->assign("CANCEL_BUTTON", $cancel_button);

        $xtpl->parse('main');
        $xtpl->out('main');
    }

    function find_parents($current_id) {
        $ctr=0;
        $parents=array();
        $parents[$ctr]=$current_id;
        $notdone=true;
        do {
            $current_id = $GLOBALS['db']->quoted($current_id);
            $query="select id,name, parent_id from product_categories where id=$current_id and deleted=0";
            $result=$GLOBALS['db']->query($query);
            $row=$GLOBALS['db']->fetchByAssoc($result);
            if ($row != null and !empty($row['parent_id']) and $row['parent_id']!= '') {
            	$ctr++;
                $parents[$ctr]=$row['parent_id'];
                $current_id=$row['parent_id'];
            } else {
            	$notdone=false;
            }
        } while ($notdone);

        //toplevel item is the only parent, return nothing.
        if (count($parents) < 2) {
        	$parents=array();
        } else {
            krsort($parents);reset($parents);
        }
        return $parents;
    }


}
