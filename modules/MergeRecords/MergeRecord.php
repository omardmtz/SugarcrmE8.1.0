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
class MergeRecord extends SugarBean {
    var $object_name = 'MergeRecord';
    var $module_dir = 'MergeRecords';
	var $merge_module;

    var $merge_module2;

    var $master_id;

    //these arrays store the fields and params to search on
    var $field_search_params = Array ();

    //this is a object for the bean you are merging on
    var $merge_bean;
    var $merge_bean2;

    //store a copy of the merge bean related strings
    var $merge_bean_strings = Array ();

    public function __construct($merge_module = '', $merge_id = '') {
        global $sugar_config;
       //parent::__construct();

        if ($merge_module != '')
            $this->load_merge_bean($merge_module, $merge_id);
    }

    public function retrieve($id = '-1', $encode = true, $deleted = true)
    {
        if (isset ($_REQUEST['action']) && $_REQUEST['action'] == 'Step2')
            $this->load_merge_bean($this->merge_bean, false, $id);
        else
            parent::retrieve($id, $encode, $deleted);
    }

    function load_merge_bean($merge_module, $load_module_strings = false, $merge_id = '') {
        global $moduleList;
        global $beanList;
        global $beanFiles;
        global $current_language;

        $this->merge_module = $merge_module;
        $this->merge_bean = BeanFactory::getBean($this->merge_module, $merge_id);

        // Bug 18853 - Disable this view if the user doesn't have edit and delete permissions
        if ( !$this->merge_bean->ACLAccess('edit') || !$this->merge_bean->ACLAccess('delete') ) {
            ACLController::displayNoAccess();
            sugar_die('');
        }

        //load master module strings
        if ($load_module_strings)
            $this->merge_bean_strings = return_module_language($current_language, $merge_module);
    }

    // Bug 22994, when the search key words are in other module, there needs to be another merge_bean.
    function load_merge_bean2($merge_module, $load_module_strings = false, $merge_id = '') {
        global $moduleList;
        global $beanList;
        global $beanFiles;
        global $current_language;

        $this->merge_bean2 = BeanFactory::getBean($this->merge_module2, $merge_id);
        //load master module strings
        if ($load_module_strings)
            $this->merge_bean_strings2 = return_module_language($current_language, $merge_module);
    }

    var $new_schema = true;

    //-----------------------------------------------------------------------
    //-------------Wrapping Necessary Merge Bean Calls-----------------------
    //-----------------------------------------------------------------------

    function fill_in_additional_list_fields() {
        return $this->merge_bean->fill_in_additional_list_fields();
    }

    function fill_in_additional_detail_fields() {
        return $this->merge_bean->fill_in_additional_detail_fields();
    }

    function get_summary_text() {
        return $this->merge_bean->get_summary_text();
    }

    function get_list_view_data() {
        return $this->merge_bean->get_list_view_data();
    }
    //-----------------------------------------------------------------------
    //-----------------------------------------------------------------------
    //-----------------------------------------------------------------------

    /**
    	builds a generic search based on the query string using or
    	do not include any $this-> because this is called on without having the class instantiated
    */
    function build_generic_where_clause($the_query_string) {
        return $this->merge_bean->build_generic_where_clause($the_query_string);
    }

    //adding in 4.0+ acl function for possible acl stuff down the line
    function bean_implements($interface) {
        switch ($interface) {
            case 'ACL' :
                return true;
        }
        return false;
    }

    function ACLAccess($view,$is_owner='not_set'){
        global $current_user;

        //if the module doesn't implement ACLS or is empty
        if(empty($this->merge_bean) || !$this->merge_bean->bean_implements('ACL'))
        {
        	return true;
        }

        if($is_owner == 'not_set'){
            $is_owner = $this->merge_bean->isOwner($current_user->id);
        }
        return ACLController::checkAccess($this->merge_bean->module_dir,'edit', true);
    }


    //keep save function to handle anything special on merges
    function save($check_notify = FALSE) {
            //something here
    return parent::save($check_notify);
    }

    function populate_search_params($search_params) {
       foreach ($this->merge_bean->field_defs as $key=>$value) {
            $searchFieldString=$key.'SearchField';
            $searchTypeString=$key.'SearchType';

            if (isset($search_params[$searchFieldString]) ) {

                if (isset($search_params[$searchFieldString]) == '') {
                    $this->field_search_params[$key]['value']='NULL';
                } else {
                    $this->field_search_params[$key]['value']=$search_params[$searchFieldString];
                }
                if (isset ($search_params[$searchTypeString])) {
                    $this->field_search_params[$key]['search_type'] = $search_params[$searchTypeString];
                } else {
                    $this->field_search_params[$key]['search_type'] = 'Exact';
                }
                //add field_def to the array.
                $this->field_search_params[$key] = array_merge($value,$this->field_search_params[$key] );
            }
       }
    }

    function get_inputs_for_search_params($search_params)
    {
        $returnString = '';
        foreach ($this->merge_bean->field_defs as $key=>$value) {
            $searchFieldString=$key.'SearchField';
            $searchTypeString=$key.'SearchType';

            if (isset($search_params[$searchFieldString]) ) {
                $searchFieldStringValue = SugarCleaner::cleanHtml($search_params[$searchFieldString], false);
                $searchTypeStringValue = SugarCleaner::cleanHtml($search_params[$searchTypeString], false);

                $returnString .= "<input type='hidden' name='$searchFieldString' value='{$searchFieldStringValue}'/>\n";
                $returnString .= "<input type='hidden' name='$searchTypeString' value='{$searchTypeStringValue}' />\n";
            }
        }

        return $returnString;
    }

    function email_addresses_query($table, $module, $bean_id) {
    	$query = $table.".id IN (SELECT ear.bean_id FROM email_addresses ea
									LEFT JOIN email_addr_bean_rel ear ON ea.id = ear.email_address_id
									WHERE ear.bean_module = '{$module}'
									AND ear.bean_id != '{$bean_id}'
									AND ear.deleted = 0";
    	return $query;
    }

    function release_name_query($search_type, $value) {
    	$this->load_merge_bean2('Releases');
    	if($search_type=='like') {
        	$where = "releases.name LIKE '%".$GLOBALS['db']->quote($value)."%'";
    	}
    	elseif($search_type=='start'){
    	    $where = "releases.name LIKE '".$GLOBALS['db']->quote($value)."%'";
    	}
    	else {
    	    $where = "releases.name = '".$GLOBALS['db']->quote($value)."'";
    	}
    	$list=$this->merge_bean2->get_releases(false,'Active',$where);
        foreach($list as $key => $value){
            $list_to_join[]="'".$GLOBALS['db']->quote($key)."'";
        }
        $in=implode(', ', $list_to_join);
        return $in;
    }

    function create_where_statement() {
        $where_clauses = array ();
        foreach ($this->field_search_params as $merge_field => $vDefArray) {
        	if (isset ($vDefArray['source']) && $vDefArray['source'] == 'custom_fields') {
        		$table_name = $this->merge_bean->table_name."_cstm";
            } else {
                $table_name = $this->merge_bean->table_name;
            }

            //Should move these if's into a central location for extensibility and addition for other search filters
            //Must do the same for pulling values in js dropdown
            if (isset ($vDefArray['search_type']) && $vDefArray['search_type'] == 'like') {
                if ($merge_field != "email1" && $merge_field != "email2" && $merge_field !="release_name") {
                	if ($vDefArray['value'] != '') {
	                	array_push($where_clauses, $table_name.".".$merge_field." LIKE '%".$GLOBALS['db']->quote($vDefArray['value'])."%'");
                	}
                }
                elseif($merge_field =="release_name"){
                    if(isset($vDefArray['value'])){
						$in = $this->release_name_query('like',$vDefArray['value']);
                        array_push($where_clauses, $table_name.".found_in_release IN ($in)");
                    }
                }
                else {
	                $query = $this->email_addresses_query($table_name, $this->merge_module, $this->merge_bean->id);
	                $query .= " AND ea.email_address LIKE '%".$GLOBALS['db']->quote($vDefArray['value'])."%')";
	                $where_clauses[] = $query;
                }
            }
            elseif (isset ($vDefArray['search_type']) && $vDefArray['search_type'] == 'start') {
                if ($merge_field != "email1" && $merge_field != "email2" && $merge_field !="release_name") {
            		array_push($where_clauses, $table_name.".".$merge_field." LIKE '".$GLOBALS['db']->quote($vDefArray['value'])."%'");
                }
                elseif($merge_field =="release_name"){
                        if(isset($vDefArray['value'])){
						$in = $this->release_name_query('start',$vDefArray['value']);
                        array_push($where_clauses, $table_name.".found_in_release IN ($in)");
                    }
                }
                else {
	                $query = $this->email_addresses_query($table_name, $this->merge_module, $this->merge_bean->id);
	                $query .= " AND ea.email_address LIKE '".$GLOBALS['db']->quote($vDefArray['value'])."%')";
	                $where_clauses[] = $query;
                }
            }
            else {
                if ($merge_field != "email1" && $merge_field != "email2" && $merge_field !="release_name") {
		            array_push($where_clauses, $table_name.".".$merge_field."='".$GLOBALS['db']->quote($vDefArray['value'])."'");
                }
                elseif($merge_field =="release_name"){
                    if(isset($vDefArray['value'])){
						$in = $this->release_name_query('exact',$vDefArray['value']);
                        array_push($where_clauses, $table_name.".found_in_release IN ($in)");
                    }
                }
                else {
	                $query = $this->email_addresses_query($table_name, $this->merge_module, $this->merge_bean->id);
	                $query .= " AND ea.email_address = '".$GLOBALS['db']->quote($vDefArray['value'])."')";
	                $where_clauses[] = $query;
                }
            }
        }
        // Add ACL Check
        $where_acl = '';
        $this->merge_bean->addVisibilityWhere($where_acl, array('action' => 'delete'));
        if(!empty($where_acl)) {
            $where_clauses[] = $where_acl;
        }
        array_push($where_clauses, $this->merge_bean->table_name.".id !='".$GLOBALS['db']->quote($this->merge_bean->id)."'");
        return $where_clauses;
    }

    //duplicating utils function for now for possiblity of future or/and and
    //other functionality
    function generate_where_statement($where_clauses) {
        $where = '';

        foreach ($where_clauses as $clause) {
            if ($where != "")
                $where .= " AND ";
            $where .= $clause;
        }
        return $where;
    }

    /**
     *  override default behavior
     * {@inheritDoc}
     */
    public function isACLRoleEditable(){
        return false;
    }
}
