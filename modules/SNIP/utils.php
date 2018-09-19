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

/**
 * Get the query that produces emails related to certain object via link to certain other object
 * E.g. having Account fetch Contacts related to it and then get all emails that have some address as one of these contacts
 * @param array $params 'link' is the relation name to use
 * @return array Array representing the query
 */
function get_unlinked_email_query_via_link($params)
{
    $relation = $params['link'];
	$bean = $GLOBALS['app']->controller->bean;
    if(empty($bean->$relation)) {
        $bean->load_relationship($relation);
    }
    if(empty($bean->$relation)) {
        $GLOBALS['log']->error("Bad relation '$relation' for bean '{$bean->object_name}' id '{$bean->id}'");
        return array();
    }
    $rel_module = $bean->$relation->getRelatedModuleName();
    $rel_join = $bean->$relation->getJoin(array(
    	'join_table_alias' => 'link_bean',
    	'join_table_link_alias' => 'linkt',
    ));
    $return_array['select']='SELECT DISTINCT emails.id';
    $return_array['from']='FROM emails ';
	$return_array['join'] = " JOIN emails_email_addr_rel eear ON eear.email_id = emails.id AND eear.deleted=0
		    	JOIN email_addr_bean_rel eabr ON eabr.email_address_id=eear.email_address_id AND eabr.bean_module = '$rel_module' AND eabr.deleted=0
				$rel_join AND link_bean.id = eabr.bean_id
				LEFT JOIN emails_beans direct_link ON direct_link.bean_id = '{$bean->id}' AND direct_link.email_id = emails.id
";
// 				JOIN (select '{$bean->id}' as id) {$bean->table_name}

    $return_array['join'] = str_replace($bean->table_name.".id", "'{$bean->id}'", $return_array['join']);
    // exclude directly linked emails
    $return_array['where']="WHERE direct_link.bean_id IS NULL";
	// Special case for Case - match only proper case number
    if($bean->object_name == "Case" && !empty($bean->case_number)) {
        $where = str_replace("%1", $bean->case_number, 	$bean->getEmailSubjectMacro());
	    $return_array["where"] .= " AND emails.name LIKE '%$where%'";
    }
    $return_array['join_tables'][0] = '';
	return $return_array;
}

/**
 * Get query fetching all objects that have same email address as current email object,
 * excluding those that are connected to this email explicitly by direct relation
 * The email is specified by current controller's bean object
 * @param array $module_dir 'module' has required module dir name
 * @return array
 */
function get_beans_by_email_addr($module_dir)
{
    $bean = $GLOBALS['app']->controller->bean;
    $module_dir = $module_dir['module'];
    $module = BeanFactory::newBean($module_dir);
    $return_array['select'] = "SELECT DISTINCT {$module->table_name}.id ";
    $return_array['from'] = "FROM {$module->table_name} ";
    $return_array['join'] = " JOIN emails_email_addr_rel eear ON eear.email_id = '$bean->id' AND eear.deleted=0
		    	JOIN email_addr_bean_rel eabr ON eabr.email_address_id=eear.email_address_id AND eabr.bean_id = {$module->table_name}.id AND eabr.bean_module = '$module_dir' AND eabr.deleted=0
				LEFT JOIN emails_beans direct_link ON direct_link.bean_id = '{$bean->id}' AND direct_link.email_id = {$module->table_name}.id
";
    // exclude directly linked emails
    $return_array['where']="WHERE direct_link.bean_id IS NULL";
    $return_array['join_tables'][0] = '';
    return $return_array;
} // fn
