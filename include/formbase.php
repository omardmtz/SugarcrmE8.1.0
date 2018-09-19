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

 * Description:  is a form helper
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;
use Sugarcrm\Sugarcrm\Util\Files\FileLoader;

/**
 * Check for null or zero for list of values
 * @param $prefix the prefix of value to be checked
 * @param $required array of value to be checked
 * @return boolean true if all values are set in the array
 */
function checkRequired($prefix, $required)
{
	foreach($required as $key)
	{
		if(!isset($_POST[$prefix.$key]) || number_empty($_POST[$prefix.$key]))
		{
			return false;
		}
	}
	return true;
}

function populateFromPost($prefix, &$focus, $skipRetrieve=false) {
	global $current_user;

	if(!empty($_REQUEST[$prefix.'record']) && !$skipRetrieve)
		$focus->retrieve($_REQUEST[$prefix.'record']);

	if(!empty($_POST['assigned_user_id']) &&
	    ($focus->assigned_user_id != $_POST['assigned_user_id']) &&
	    ($_POST['assigned_user_id'] != $current_user->id)) {
		$GLOBALS['check_notify'] = true;
	}
    $sfh = new SugarFieldHandler();

	foreach($focus->field_defs as $field=>$def) {
        if ( $field == 'id' && !empty($focus->id) ) {
            // Don't try and overwrite the ID
            continue;
        }
	    $type = !empty($def['custom_type']) ? $def['custom_type'] : $def['type'];
		$sf = $sfh->getSugarField($type);
        if($sf != null){
            $sf->save($focus, $_POST, $field, $def, $prefix);
        } else {
            $GLOBALS['log']->fatal("Field '$field' does not have a SugarField handler");
        }

	}

	foreach($focus->additional_column_fields as $field) {
		if(isset($_POST[$prefix.$field])) {
			$value = $_POST[$prefix.$field];
			$focus->$field = $value;
		}
	}
    populateFromPostACL($focus);
	return $focus;
}
/**
 * If current user have not permit to change field function replace default value
 *
 * @param SugarBean $focus
 */
function populateFromPostACL(SugarBean $focus)
{
    $isOwner = $focus->isOwner($GLOBALS['current_user']->id);

    // set up a default bean as per bug 46448, without bringing EditView into the mix
    // bug 58730

    if ($focus->new_with_id) {
        $beanId = null;
    } else {
        $beanId = $focus->id;
    }

    $defaultBean = BeanFactory::getBean($focus->module_name, $beanId, array('use_cache' => false));
    $defaultBean->fill_in_additional_detail_fields();

    if (empty($defaultBean->assigned_user_id)) {
        $defaultBean->assigned_user_id = $GLOBALS['current_user']->id;
    }

    foreach (array_keys($focus->field_defs) as $field) {
        $fieldAccess = ACLField::hasAccess($field, $focus->module_dir, $GLOBALS['current_user']->id, $isOwner);
        $aclStrategyAccess = $focus->ACLFieldAccess($field, 'save', array());
        if (!in_array($fieldAccess, array(2, 4)) || !$aclStrategyAccess) {
            $focus->$field = $defaultBean->$field;
        }
    }
}

function add_hidden_elements($key, $value) {

    $elements = '';

    // if it's an array, we need to loop into the array and use square brackets []
    if (is_array($value)) {
        foreach ($value as $k=>$v) {
            $elements .= "<input type='hidden' name='$key"."[$k]' value='$v'>\n";
        }
    } else {
        $elements = "<input type='hidden' name='$key' value='$value'>\n";
    }

    return $elements;
}


function getPostToForm($ignore='', $isRegularExpression=false)
{
    $htmlspecialchars = function ($value) {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    };

	$fields = '';
	if(!empty($ignore) && $isRegularExpression) {
		foreach ($_POST as $key=>$value){
			if(!preg_match($ignore, $key)) {
                $fields .= add_hidden_elements(
                    htmlspecialchars($key, ENT_QUOTES, 'UTF-8'),
                    is_array($value)
                        ? array_map($htmlspecialchars, $value)
                        : htmlspecialchars($value, ENT_QUOTES, 'UTF-8')
                );
			}
		}
	} else {
		foreach ($_POST as $key=>$value){
			if($key != $ignore) {
                $fields .= add_hidden_elements(
                    htmlspecialchars($key, ENT_QUOTES, 'UTF-8'),
                    is_array($value)
                        ? array_map($htmlspecialchars, $value)
                        : htmlspecialchars($value, ENT_QUOTES, 'UTF-8')
                    );
			}
		}
	}
	return $fields;
}

function getGetToForm($ignore='', $usePostAsAuthority = false)
{
	$fields = '';
	foreach ($_GET as $key=>$value)
	{
	    if(is_array($value)) continue;
		if($key != $ignore){
			if(!$usePostAsAuthority || !isset($_POST[$key])){
                $fields .= "<input type='hidden' name='$key' value='" .
                    htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . "'>";
			}
		}
	}
	return $fields;

}
function getAnyToForm($ignore='', $usePostAsAuthority = false)
{
	$fields = getPostToForm($ignore);
	$fields .= getGetToForm($ignore, $usePostAsAuthority);
	return $fields;

}

/**
 * Handles a redirect from a Form SugarCRM based or return_url information.
 *
 * This function redirect automatically if $_REQUEST['return_url'] isn't empty.
 * It also exits the app always.
 *
 * @see buildRedirectUrl()
 * @see SugarApplication::redirect()
 *
 * @deprecated since 7.0.0. Use buildRedirectUrl() and SugarApplication::redirect().
 *
 * @param string $return_id (optional) The record id to redirect to.
 * @param string $return_module (optional) The module to redirect to.
 * @param array $additionalFlags (optional) Additional flags to sent to the URL.
 */
function handleRedirect($return_id = '', $return_module = '', array $additionalFlags = array())
{
    if (!empty($_REQUEST['return_url'])) {
        $url = $_REQUEST['return_url'];
    } else {
        $url = buildRedirectURL($return_id, $return_module, $additionalFlags);
    }

    SugarApplication::redirect($url);
}

/**
 * Builds a redirect URL based on a Form SugarCRM.
 *
 * @param string $return_id (optional) The record id to redirect to.
 * @param string $return_module (optional) The module to redirect to.
 * @param array $additionalFlags (optional) Additional flags to sent to the URL.
 *
 * @return string The url built from the current $_REQUEST information.
 */
function buildRedirectURL($return_id = '', $return_module = '', array $additionalFlags = array())
{
    if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "")
	{
		$return_module = $_REQUEST['return_module'];
	}
	if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "")
	{

	   //if we are doing a "Close and Create New"
        if(isCloseAndCreateNewPressed())
        {
            $return_action = "EditView";
            $isDuplicate = "true";
            $status = "";

            // Meeting Integration
            if(isset($_REQUEST['meetingIntegrationFlag']) && $_REQUEST['meetingIntegrationFlag'] == 1) {
                $additionalFlags['meetingIntegrationShowForm'] = '1';
            }
            // END Meeting Integration
        }
		// if we create a new record "Save", we want to redirect to the DetailView
		else if(isset($_REQUEST['action']) && $_REQUEST['action'] == "Save"
			&& $_REQUEST['return_module'] != 'Activities'
			&& $_REQUEST['return_module'] != 'WorkFlow'
			&& $_REQUEST['return_module'] != 'Home'
			&& $_REQUEST['return_module'] != 'Forecasts'
			&& $_REQUEST['return_module'] != 'Calendar'
			&& $_REQUEST['return_module'] != 'MailMerge'
			&& $_REQUEST['return_module'] != 'TeamNotices'
			)
			{
			    $return_action = 'DetailView';
			} elseif($_REQUEST['return_module'] == 'Activities' || $_REQUEST['return_module'] == 'Calendar') {
			$return_module = $_REQUEST['module'];
			$return_action = $_REQUEST['return_action'];
			// wp: return action needs to be set for one-click close in task list
		}
		else
		{
			// if we "Cancel", we go back to the list view.
			$return_action = $_REQUEST['return_action'];
		}
	}
	else
	{
		$return_action = "DetailView";
	}

	if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "")
	{
		$return_id = $_REQUEST['return_id'];
	}

    $add = http_build_query($additionalFlags);

    if (!isset($isDuplicate) || !$isDuplicate)
    {
        $url="index.php?action=$return_action&module=$return_module&record=$return_id&return_module=$return_module&return_action=$return_action{$add}";
        if(isset($_REQUEST['offset']) && empty($_REQUEST['duplicateSave'])) {
            $url .= "&offset=".$_REQUEST['offset'];
        }
    } else {
    	$standard = "action=$return_action&module=$return_module&record=$return_id&isDuplicate=true&return_module=$return_module&return_action=$return_action&status=$status";
        $url="index.php?{$standard}{$add}";
    }

    return $url;
}

function getLikeForEachWord($fieldname, $value, $minsize=4)
{
    $db = DBManagerFactory::getInstance();
    $value = trim($value);
	$values = explode(' ',$value);
	$ret = '';
	foreach($values as $val)
	{
		if(strlen($val) >= $minsize)
		{
			if(!empty($ret))
			{
				$ret .= ' or';
			}
            $ret .= ' '. $fieldname . ' LIKE ' . $db->quoted('%'.$val.'%');
		}

	}


}

function isCloseAndCreateNewPressed() {
    return isset($_REQUEST['action']) &&
           $_REQUEST['action'] == "Save" &&
           isset($_REQUEST['isSaveAndNew']) &&
           $_REQUEST['isSaveAndNew'] == 'true';
}

/**
 * This is a helper function to return the url portion of the teams sent
 *
 * @param $module String value of module used for the prefixing
 * @return String URL format of teams sent to the form base code
 */
function get_teams_url($module='') {
	$sfh = new SugarFieldHandler();
	$sf = $sfh->getSugarField('Teamset', true);
    $teams = $sf->getTeamsFromRequest('team_name');
    $url = '';
	if(!empty($teams)) {
	   //Store the id and count values because this is the true index as duplicate teams
	   //may have been passed into the $_REQUEST variable
	   $id_to_count = array();
	   $count = 0;
	   foreach($teams as $id=>$name) {
	   	   $id_to_count[$id] = $count;
           $url .= "&{$module}id_team_name_collection_{$count}=" . urlencode($id);
           $url .= "&{$module}team_name_collection_{$count}=" . urlencode($name);
           $count++;
	   }

	   if(isset($_REQUEST['primary_team_name_collection'])) {
	   	  $primary_index = $_REQUEST['primary_team_name_collection'];
	   	  $primary_team_id = $_REQUEST["id_team_name_collection_{$primary_index}"];
	      $url .= "&{$module}primary_team_name_collection=" . $id_to_count[$primary_team_id];
	   }
	}
	return $url;
}


/**
 * get_teams_hidden_inputs
 * This is a helper function to construct a String of the hidden input parameters representing the
 * teams that were sent to the form base code
 *
 * @param $module String value of module
 * @return String HTML format of teams sent to the form base code
 */
function get_teams_hidden_inputs($module='') {
	$_REQUEST = array_merge($_REQUEST, $_POST);
	if(!empty($module)) {
		foreach($_REQUEST as $name=>$value) {
			if(preg_match("/^{$module}(.*?team_name.*?$)/", $name, $matches)) {
			   $_REQUEST[$matches[1]] = $value;
			}
		}
	}


	$sfh = new SugarFieldHandler();
	$sf = $sfh->getSugarField('Teamset', true);
    $teams = $sf->getTeamsFromRequest('team_name');
    $input = '';

	if(!empty($teams)) {
	   $count = 0;
	   foreach($teams as $id=>$name) {
           $input .= "<input type='hidden' name='id_team_name_collection_{$count}' value='" . urlencode($id) . "'>\n";
           $input .= "<input type='hidden' name='team_name_collection_{$count}' value='" . urlencode($name) . "'>\n";
           $count++;
	   }

	   if(isset($_REQUEST['primary_team_name_collection'])) {
            $escaped = htmlspecialchars($_REQUEST['primary_team_name_collection'], ENT_QUOTES, 'UTF-8');
            $input .= "<input type='hidden' name='primary_team_name_collection' value='" . $escaped . "'>\n";
	   }
	}
	return $input;
}

/**
 * Functions from Save2.php
 * @see include/generic/Save2.php
 */

function add_prospects_to_prospect_list($parent_id,$child_id)
{
    $focus=BeanFactory::newBean('Prospects');
    if(is_array($child_id)){
        $uids = $child_id;
    }
    else{
        $uids = array($child_id);
    }

    $relationship = '';
    foreach($focus->get_linked_fields() as $field => $def) {
        if ($focus->load_relationship($field)) {
            if ( $focus->$field->getRelatedModuleName() == 'ProspectLists' ) {
                $relationship = $field;
                break;
            }
        }
    }

    if ( $relationship != '' ) {
        foreach ( $uids as $id) {
            $focus->retrieve($id);
            $focus->load_relationship($relationship);
            $focus->prospect_lists->add( $parent_id );
        }
    }
}

function add_to_prospect_list($query_panel,$parent_module,$parent_type,$parent_id,$child_id,$link_attribute,$link_type,$parent)
{
    $GLOBALS['log']->debug('add_prospects_to_prospect_list:parameters:'.$query_panel);
    $GLOBALS['log']->debug('add_prospects_to_prospect_list:parameters:'.$parent_module);
    $GLOBALS['log']->debug('add_prospects_to_prospect_list:parameters:'.$parent_type);
    $GLOBALS['log']->debug('add_prospects_to_prospect_list:parameters:'.$parent_id);
    $GLOBALS['log']->debug('add_prospects_to_prospect_list:parameters:'.$child_id);
    $GLOBALS['log']->debug('add_prospects_to_prospect_list:parameters:'.$link_attribute);
    $GLOBALS['log']->debug('add_prospects_to_prospect_list:parameters:'.$link_type);


    if (!class_exists($parent_type)) {
        require_once FileLoader::validateFilePath('modules/'.$parent_module.'/'.$parent_type.'.php');
    }
    $focus = new $parent_type();
    $focus->retrieve($parent_id);
    if(empty($focus->id)) {
        return false;
    }
    if(empty($parent)) {
        return false;
    }

    //if link_type is default then load relationship once and add all the child ids.
    $relationship_attribute=$link_attribute;

    //find all prospects based on the query

    $subpanel = new SubPanelTiles($parent, $parent->module_dir);
    $thisPanel=$subpanel->subpanel_definitions->load_subpanel($query_panel);
    if(empty($thisPanel)) {
        return false;
    }

    // bugfix #57850  filter prospect list based on marketing_id (if it's present)
    if (isset($_REQUEST['marketing_id']) && $_REQUEST['marketing_id'] != 'all')
    {
        $thisPanel->_instance_properties['function_parameters']['EMAIL_MARKETING_ID_VALUE'] = $_REQUEST['marketing_id'];
    }

    $result = SugarBean::get_union_related_list($parent, '', '', '', 0, -99,-99,'', $thisPanel);

    if(!empty($result['list'])) {
        foreach($result['list'] as $object) {
            if ($link_type != 'default') {
                $relationship_attribute=strtolower($object->$link_attribute);
            }
            $GLOBALS['log']->debug('add_prospects_to_prospect_list:relationship_attribute:'.$relationship_attribute);
            // load relationship for the first time or on change of relationship atribute.
            if (empty($focus->$relationship_attribute)) {
                $focus->load_relationship($relationship_attribute);
            }
            //add
            $focus->$relationship_attribute->add($object->$child_id);
        }
    }
}

//Link rows returned by a report to parent record.
function save_from_report($report_id,$parent_id, $module_name, $relationship_attr_name) {
    global $beanFiles;
    global $beanList;

    $GLOBALS['log']->debug("Save2: Linking with report output");
    $GLOBALS['log']->debug("Save2:Report ID=".$report_id);
    $GLOBALS['log']->debug("Save2:Parent ID=".$parent_id);
    $GLOBALS['log']->debug("Save2:Module Name=".$module_name);
    $GLOBALS['log']->debug("Save2:Relationship Attribute Name=".$relationship_attr_name);

    $bean_name = $beanList[$module_name];
    $GLOBALS['log']->debug("Save2:Bean Name=".$bean_name);
    require_once($beanFiles[$bean_name]);
    $focus = new $bean_name();

    $focus->retrieve($parent_id);
    $focus->load_relationship($relationship_attr_name);

    //fetch report definition.
    global $current_language, $report_modules, $modules_report;

    $mod_strings = return_module_language($current_language,"Reports");


    $saved = new SavedReport();
    $saved->disable_row_level_security = true;
    $saved->retrieve($report_id, false);

    //initiailize reports engine with the report definition.
    $report = new SubpanelFromReports($saved);
    $report->run_query();

    $sql = $report->query_list[0];
    $GLOBALS['log']->debug("Save2:Report Query=".$sql);
    $result = $report->db->query($sql);
    while($row = $report->db->fetchByAssoc($result))
    {
        $focus->$relationship_attr_name->add($row['primaryid']);
    }
}
