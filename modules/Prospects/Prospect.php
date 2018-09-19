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


class Prospect extends Person {
	// Stored fields
	var $id;
	var $name = '';
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $assigned_user_id;
	var $created_by;
	var $created_by_name;
	var $modified_by_name;
	var $team_id;
	var $description;
	var $salutation;
	var $first_name;
	var $last_name;
	var $full_name;
	var $title;
	var $department;
	var $birthdate;
	var $do_not_call;
	var $phone_home;
	var $phone_mobile;
	var $phone_work;
	var $phone_other;
	var $phone_fax;
	var $email1;
	var $email2;
	var $email_and_name1;
	var $assistant;
	var $assistant_phone;
	var $email_opt_out;
	var $primary_address_street;
	var $primary_address_city;
	var $primary_address_state;
	var $primary_address_postalcode;
	var $primary_address_country;
	var $alt_address_street;
	var $alt_address_city;
	var $alt_address_state;
	var $alt_address_postalcode;
	var $alt_address_country;
	var $tracker_key;
	var $lead_id;
	var $account_name;
	var $assigned_real_user_name;
	// These are for related fields
	var $assigned_user_name;
	var $team_name;
	var $module_dir = 'Prospects';
	var $table_name = "prospects";
	var $object_name = "Prospect";
	var $new_schema = true;
	var $emailAddress;

	var $importable = true;
    // This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('assigned_user_name');


	public function __construct() {
		parent::__construct();
		global $current_user;
		if(!empty($current_user)) {
			$this->team_id = $current_user->default_team;	//default_team is a team id
		} else {
			$this->team_id = 1; // make the item globally accessible
		}
	}

	function fill_in_additional_list_fields()
	{
		parent::fill_in_additional_list_fields();
		$this->_create_proper_name_field();
		$this->email_and_name1 = $this->full_name." &lt;".$this->email1."&gt;";
	}

	function fill_in_additional_detail_fields()
	{
		parent::fill_in_additional_list_fields();
		$this->_create_proper_name_field();
   	}

	/**
		builds a generic search based on the query string using or
		do not include any $this-> because this is called on without having the class instantiated
	*/
	function build_generic_where_clause ($the_query_string)
	{
		$where_clauses = Array();
		$the_query_string = $GLOBALS['db']->quote($the_query_string);

		array_push($where_clauses, "prospects.last_name like '$the_query_string%'");
		array_push($where_clauses, "prospects.first_name like '$the_query_string%'");
		array_push($where_clauses, "prospects.assistant like '$the_query_string%'");

		if (is_numeric($the_query_string))
		{
			array_push($where_clauses, "prospects.phone_home like '%$the_query_string%'");
			array_push($where_clauses, "prospects.phone_mobile like '%$the_query_string%'");
			array_push($where_clauses, "prospects.phone_work like '%$the_query_string%'");
			array_push($where_clauses, "prospects.phone_other like '%$the_query_string%'");
			array_push($where_clauses, "prospects.phone_fax like '%$the_query_string%'");
			array_push($where_clauses, "prospects.assistant_phone like '%$the_query_string%'");
		}

		$the_where = "";
		foreach($where_clauses as $clause)
		{
			if($the_where != "") $the_where .= " or ";
			$the_where .= $clause;
		}


		return $the_where;
	}

    function converted_prospect($prospectid, $contactid, $accountid, $opportunityid){
    	$query = "UPDATE prospects set  contact_id=$contactid, account_id=$accountid, opportunity_id=$opportunityid where  id=$prospectid and deleted=0";
		$this->db->query($query,true,"Error converting prospect: ");
		//todo--status='Converted', converted='1',
    }
     function bean_implements($interface){
		switch($interface){
			case 'ACL':return true;
		}
		return false;
	}

    /**
     *  This method will be used by Mail Merge in order to retieve the targets as specified in the query
     *  @param query String - this is the query which contains the where clause for the query
     */
    function retrieveTargetList($query, $fields, $offset = 0, $limit= -99, $max = -99, $deleted = 0, $module = ''){
        global  $beanList, $beanFiles;
        $module_name = $this->module_dir;

        if(empty($module))
        {
            //The call to retrieveTargetList contains a query that may contain a pound token
            $pattern = '/AND related_type = [\'#]([a-zA-Z]+)[\'#]/i';
            if(preg_match($pattern, $query, $matches))
            {
                $module_name = $matches[1];
                $query = preg_replace($pattern, "", $query);
            }
        }

        $count = count($fields);
        $index = 1;
        $sel_fields = "";
        if(!empty($fields))
        {
            foreach($fields as $field){
                if($field == 'id'){
                	$sel_fields .= 'prospect_lists_prospects.id id';
                }else{
                	$sel_fields .= strtolower($module_name).".".$field;
                }
                if($index < $count){
                    $sel_fields .= ",";
                }
                $index++;
            }
        }

        $module_name = ucfirst($module_name);
        $seed = BeanFactory::newBean($module_name);
        if(empty($sel_fields)){
            $sel_fields = $seed->table_name.'.*';
        }
        $select = "SELECT ".$sel_fields." FROM ".$seed->table_name;
        $select .= " INNER JOIN prospect_lists_prospects ON prospect_lists_prospects.related_id = ".$seed->table_name.".id";
        $select .= " INNER JOIN prospect_lists ON prospect_lists_prospects.prospect_list_id = prospect_lists.id";
        $select .= " INNER JOIN prospect_list_campaigns ON prospect_list_campaigns.prospect_list_id = prospect_lists.id";
        $select .= " INNER JOIN campaigns on campaigns.id = prospect_list_campaigns.campaign_id";
        $select .= " WHERE prospect_list_campaigns.deleted = 0";
        $select .= " AND prospect_lists_prospects.deleted = 0";
        $select .= " AND prospect_lists.deleted = 0";
        if (!empty($query)) {
            $select .= " AND ".$query;
        }

        return $this->process_list_query($select, $offset, $limit, $max, $query);
    }

    /**
     *  Given an id, looks up in the prospect_lists_prospects table
     *  and retrieve the correct type for this id
     */
    function retrieveTarget($id){
        $query = "SELECT related_id, related_type FROM prospect_lists_prospects WHERE id = '".$this->db->quote($id)."'";
        $result = $this->db->query($query);
        if(($row = $this->db->fetchByAssoc($result))){
             return BeanFactory::retrieveBean($row['related_type'], $row['related_id']);
        }else{
            return null;
        }
    }


	function get_unlinked_email_query($type=array()) {

		return get_unlinked_email_query($type, $this);
	}
}
