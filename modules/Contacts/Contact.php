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

use Sugarcrm\Sugarcrm\Security\Password\Hash;


/**
 *  Contact is used to store customer information.
 */
class Contact extends Person {
	// Stored fields
	var $id;
	var $name = '';
	var $lead_source;
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
	var $title;
	var $department;
	var $birthdate;
	var $reports_to_id;
	var $do_not_call;
	var $phone_home;
	var $phone_mobile;
	var $phone_work;
	var $phone_other;
	var $phone_fax;
	var $email1;
	var $email_and_name1;
	var $email_and_name2;
	var $email2;
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
	var $portal_name;
	var $portal_app;
	var $portal_active;
	var $contacts_users_id;
	// These are for related fields
	var $bug_id;
	var $account_name;
	var $account_id;
	var $report_to_name;
	var $opportunity_role;
	var $opportunity_rel_id;
	var $opportunity_id;
	var $case_role;
	var $case_rel_id;
	var $case_id;
	var $task_id;
	var $note_id;
	var $meeting_id;
	var $call_id;
	var $email_id;
	var $assigned_user_name;
	var $accept_status;
    var $accept_status_id;
    var $accept_status_name;
    var $alt_address_street_2;
    var $alt_address_street_3;
    var $opportunity_role_id;
    var $portal_password;
    var $primary_address_street_2;
    var $primary_address_street_3;
    var $campaign_id;
    var $sync_contact;
	var $team_name;
	var $quote_role;
	var $quote_rel_id;
	var $quote_id;
	var $full_name; // l10n localized name
	var $invalid_email;
	var $table_name = "contacts";
	var $rel_account_table = "accounts_contacts";
	//This is needed for upgrade.  This table definition moved to Opportunity module.
	var $rel_opportunity_table = "opportunities_contacts";
	var $rel_quotes_table = "quotes_contacts";

    //Marketo
    var $mkto_sync;
    var $mkto_id;
    var $mkto_lead_score;

	var $object_name = "Contact";
	var $module_dir = 'Contacts';
	var $emailAddress;
	var $new_schema = true;
	var $importable = true;

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('bug_id', 'assigned_user_name', 'account_name', 'account_id', 'opportunity_id', 'case_id', 'task_id', 'note_id', 'meeting_id', 'call_id', 'email_id'
	,'quote_id'
	);

	var $relationship_fields = Array(
        'account_id'=> 'accounts',
        'bug_id' => 'bugs',
        'call_id'=>'calls',
        'case_id'=>'cases',
        'email_id'=>'emails',
        'meeting_id'=>'meetings',
        'note_id'=>'notes',
        'task_id'=>'tasks',
        'opportunity_id'=>'opportunities',
        'contacts_users_id' => 'user_sync',
    );


	public function __construct() {
		parent::__construct();
	}

	function add_list_count_joins(&$query, $where)
	{
		// accounts.name
		if(stristr($where, "accounts.name"))
		{
			// add a join to the accounts table.
			$query .= "
	            LEFT JOIN accounts_contacts
	            ON contacts.id=accounts_contacts.contact_id
	            LEFT JOIN accounts
	            ON accounts_contacts.account_id=accounts.id
			";
		}
        $custom_join = $this->getCustomJoin();
        $query .= $custom_join['join'];


	}

	function listviewACLHelper(){
		$array_assign = parent::listviewACLHelper();
		$is_owner = false;
		//MFH BUG 18281; JChi #15255
		$is_owner = !empty($this->assigned_user_id) && $this->assigned_user_id == $GLOBALS['current_user']->id;
			if(!ACLController::moduleSupportsACL('Accounts') || ACLController::checkAccess('Accounts', 'view', $is_owner)){
				$array_assign['ACCOUNT'] = 'a';
			}else{
				$array_assign['ACCOUNT'] = 'span';

			}
		return $array_assign;
	}

    public function create_new_list_query(
        $order_by,
        $where,
        $filter = array(),
        $params = array(),
        $show_deleted = 0,
        $join_type = '',
        $return_array = false,
        $parentbean = null,
        $singleSelect = false,
        $ifListForExport = false
    ) {
		//if this is from "contact address popup" action, then process popup list query
		if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'ContactAddressPopup') {
			return $this->address_popup_create_new_list_query($order_by, $where, $filter, $params, $show_deleted, $join_type, $return_array, $parentbean, $singleSelect);

		} else {
			//any other action goes to parent function in sugarbean
			if (strpos($order_by,'sync_contact') !== false) {
				//we have found that the user is ordering by the sync_contact field, it would be troublesome to sort by this field
				//and perhaps a performance issue, so just remove it
				$order_by = '';
			}
            return parent::create_new_list_query(
                $order_by,
                $where,
                $filter,
                $params,
                $show_deleted,
                $join_type,
                $return_array,
                $parentbean,
                $singleSelect,
                $ifListForExport
            );
		}
	}

	function address_popup_create_new_list_query($order_by, $where,$filter=array(),$params=array(), $show_deleted = 0,$join_type='', $return_array = false,$parentbean=null, $singleSelect = false)
	{
		//if this is any action that is not the contact address popup, then go to parent function in sugarbean
		if(isset($_REQUEST['action']) && $_REQUEST['action'] !== 'ContactAddressPopup'){
			return parent::create_new_list_query($order_by, $where, $filter, $params, $show_deleted, $join_type, $return_array, $parentbean, $singleSelect);
		}

        $custom_join = $this->getCustomJoin();
		// MFH - BUG #14208 creates alias name for select
		$select_query = "SELECT ";
		$select_query .= db_concat($this->table_name,array('first_name','last_name')) . " name, ";
		$select_query .= "
				$this->table_name.*,
                accounts.name as account_name,
                accounts.id as account_id,
                accounts.assigned_user_id account_id_owner,
                users.user_name as assigned_user_name ";
		$select_query .= ",teams.name AS team_name ";
        $select_query .= $custom_join['select'];
 		$ret_array['select'] = $select_query;

 		$from_query = "
                FROM contacts ";
		// We need to confirm that the user is a member of the team of the item.
 		$this->addVisibilityFrom($from_query, array('where_condition' => true));

		$from_query .=		"LEFT JOIN users
	                    ON contacts.assigned_user_id=users.id
	                    LEFT JOIN accounts_contacts
	                    ON contacts.id=accounts_contacts.contact_id  and accounts_contacts.deleted = 0
	                    LEFT JOIN accounts
	                    ON accounts_contacts.account_id=accounts.id AND accounts.deleted=0 ";
		$from_query .=		"LEFT JOIN teams ON contacts.team_id=teams.id AND (teams.deleted=0) ";
		$from_query .= "LEFT JOIN email_addr_bean_rel eabl  ON eabl.bean_id = contacts.id AND eabl.bean_module = 'Contacts' and eabl.primary_address = 1 and eabl.deleted=0 ";
        $from_query .= "LEFT JOIN email_addresses ea ON (ea.id = eabl.email_address_id) ";
        $from_query .= $custom_join['join'];
		$ret_array['from'] = $from_query;
		$ret_array['from_min'] = 'from contacts';

		$where_auto = '1=1';
		if($show_deleted == 0){
            	$where_auto = " $this->table_name.deleted=0 ";
            	//$where_auto .= " AND accounts.deleted=0  ";
		}else if($show_deleted == 1){
				$where_auto = " $this->table_name.deleted=1 ";
		}

		if($where != ""){
			$where_query = "where ($where) AND ".$where_auto;
		}else{
			$where_query = "where ".$where_auto;
		}

		$this->addVisibilityWhere($where_query, array('where_condition' => true));
		$acc = BeanFactory::newBean('Accounts');
		$acc->addVisibilityWhere($where_query, array('where_condition' => true, 'table_alias' => 'accounts'));

		$ret_array['where'] = $where_query;
		$ret_array['order_by'] = '';

         	//process order by and add if it's not empty
         	$order_by = $this->process_order_by($order_by);
         	if (!empty($order_by)) {
        	     $ret_array['order_by'] = ' ORDER BY ' . $order_by;
 	        }

		if($return_array)
    	{
    		return $ret_array;
    	}

	    return $ret_array['select'] . $ret_array['from'] . $ret_array['where']. $ret_array['order_by'];

	}

    function fill_in_additional_list_fields()
    {
        parent::fill_in_additional_list_fields();
        $this->_create_proper_name_field();

        if ($this->force_load_details == true) {
            $this->fill_in_additional_detail_fields();
        }
    }

	function fill_in_additional_detail_fields() {
		parent::fill_in_additional_detail_fields();
        if(empty($this->id)) return;

        global $locale;

		$this->load_relationship('user_sync');
        if ($this->user_sync->_relationship->relationship_exists($GLOBALS['current_user'], $this)) {
			$this->sync_contact = true;
		} else {
			$this->sync_contact = false;
		}
		if(!empty($this->fetched_row)) {
		    $this->fetched_row['sync_contact'] = $this->sync_contact;
		}

		/** concating this here because newly created Contacts do not have a
		 * 'name' attribute constructed to pass onto related items, such as Tasks
		 * Notes, etc.
		 */
        $this->name = $locale->formatName($this);
	}

    function get_list_view_data($filter_fields = array())
    {
        $temp_array = parent::get_list_view_data();

        if ($filter_fields && !empty($filter_fields['sync_contact'])) {
            $this->load_relationship('user_sync');
            $temp_array['SYNC_CONTACT'] = $this->user_sync->_relationship->relationship_exists(
                $GLOBALS['current_user'],
                $this
            ) ? 1 : 0;
        }

        $temp_array['EMAIL_AND_NAME1'] = "{$this->full_name} &lt;" . $temp_array['EMAIL1'] . "&gt;";

        return $temp_array;
    }

	/**
		builds a generic search based on the query string using or
		do not include any $this-> because this is called on without having the class instantiated
	*/
	function build_generic_where_clause ($the_query_string)
	{
		$where_clauses = Array();
		$the_query_string = $this->db->quote($the_query_string);

		array_push($where_clauses, "contacts.last_name like '$the_query_string%'");
		array_push($where_clauses, "contacts.first_name like '$the_query_string%'");
		array_push($where_clauses, "accounts.name like '$the_query_string%'");
		array_push($where_clauses, "contacts.assistant like '$the_query_string%'");
		array_push($where_clauses, "ea.email_address like '$the_query_string%'");

		if (is_numeric($the_query_string))
		{
			array_push($where_clauses, "contacts.phone_home like '%$the_query_string%'");
			array_push($where_clauses, "contacts.phone_mobile like '%$the_query_string%'");
			array_push($where_clauses, "contacts.phone_work like '%$the_query_string%'");
			array_push($where_clauses, "contacts.phone_other like '%$the_query_string%'");
			array_push($where_clauses, "contacts.phone_fax like '%$the_query_string%'");
			array_push($where_clauses, "contacts.assistant_phone like '%$the_query_string%'");
		}

		$the_where = "";
		foreach($where_clauses as $clause)
		{
			if($the_where != "") $the_where .= " or ";
			$the_where .= $clause;
		}


		return $the_where;
	}

	function set_notification_body($xtpl, $contact)
	{
	    global $locale;

        $xtpl->assign("CONTACT_NAME", $locale->formatName($contact));
		$xtpl->assign("CONTACT_DESCRIPTION", $contact->description);

		return $xtpl;
	}

	function get_contact_id_by_email($email)
	{
		$email = trim($email);
		if(empty($email)){
			//email is empty, no need to query, return null
			return null;
		}

		$where_clause = "(email1='$email' OR email2='$email') AND deleted=0";

        $query = "SELECT id FROM $this->table_name WHERE $where_clause";
        $GLOBALS['log']->debug("Retrieve $this->object_name: ".$query);
		$result = $this->db->getOne($query, true, "Retrieving record $where_clause:");

		return empty($result)?null:$result;
	}

    public function save_relationship_changes($is_update, $exclude = array())
    {
		//if account_id was replaced unlink the previous account_id.
		//this rel_fields_before_value is populated by sugarbean during the retrieve call.
		if (!empty($this->account_id) and !empty($this->rel_fields_before_value['account_id']) and
				(trim($this->account_id) != trim($this->rel_fields_before_value['account_id']))) {
				//unlink the old record.
				$this->load_relationship('accounts');
				$this->accounts->delete($this->id,$this->rel_fields_before_value['account_id']);
		}
		parent::save_relationship_changes($is_update);
	}

	function bean_implements($interface)
	{
		switch($interface){
			case 'ACL':return true;
		}
		return false;
	}

	function get_unlinked_email_query($type=array())
	{
		return get_unlinked_email_query($type, $this);
	}

    /**
     * used by import to add a list of users
     *
     * Parameter can be one of the following:
     * - string 'all': add this contact for all users
     * - comma deliminated lists of teams and/or users
     *
     * @param string $list_of_user
     */
    function process_sync_to_outlook($list_of_users)
    {
        static $focus_user;

        // cache this object since we'll be reusing it a bunch
        if ( !($focus_user instanceof User) ) {

            $focus_user = BeanFactory::newBean('Users');
        }

        static $focus_team;

        // cache this object since we'll be reusing it a bunch
        if ( !($focus_team instanceof Team) ) {

            $focus_team = BeanFactory::newBean('Teams');
        }

		if ( empty($list_of_users) ) {
            return;
		}
        if ( !isset($this->users) ) {
            $this->load_relationship('user_sync');
        }

		if ( strtolower($list_of_users) == 'all' ) {
            // add all non-deleted users
			$sql = "SELECT id FROM users WHERE deleted=0 AND is_group=0 AND portal_only=0";
			$result=$this->db->query($sql);
			while ( $hash = $this->db->fetchByAssoc($result) ) {
                $this->user_sync->add($hash['id']);
			}
		}
        else {
            $theList = explode(",",$list_of_users);
            foreach ($theList as $eachItem) {
                if ( ($user_id = $focus_user->retrieve_user_id($eachItem))
                        || $focus_user->retrieve($eachItem)) {
                    // it is a user, add user
                    $this->user_sync->add($user_id ? $user_id : $focus_user->id);
                    return;
                }
                if ( $focus_team->retrieve($eachItem)
                        || $focus_team->retrieve_team_id($eachItem)) {
                    // it is a team, add all team members
                    $sql = "SELECT DISTINCT(user_id)
                                FROM team_memberships
                                WHERE team_id='{$focus_team->id}'
                                    AND deleted=0";
                    $result = $this->db->query($sql);
                    while ( $hash = $this->db->fetchByAssoc($result) ) {
                        $this->user_sync->add($hash['user_id']);
                    }
				}
			}
		}
	}

	/**
     *
     * @see parent::save()
     */
    public function save($check_notify = false)
    {
        if(!is_null($this->sync_contact)) {
            if(empty($this->fetched_row) || $this->fetched_row['sync_contact'] != $this->sync_contact) {
                $this->load_relationship('user_sync');
                if($this->sync_contact) {
                    // They want to sync_contact
                    $this->user_sync->add($GLOBALS['current_user']->id);
                } else {
                    $this->user_sync->delete($this->id, $GLOBALS['current_user']->id);
                }

            }
        }
        return parent::save($check_notify);
    }

    /**
     * Attempt to rehash the current portal password hash
     * @param string $password Clear text password
     */
    public function rehashPortalPassword($password)
    {
        if (empty($this->id) || empty($this->portal_password) || empty($password)) {
            return;
        }

        $hashBackend = Hash::getInstance();

        if ($hashBackend->needsRehash($this->portal_password)) {
            if ($newHash = $hashBackend->hash($password)) {
                $update = sprintf(
                    'UPDATE %s SET portal_password = %s WHERE id = %s',
                    $this->table_name,
                    $this->db->quoted($newHash),
                    $this->db->quoted($this->id)
                );
                $this->db->query($update);
                $GLOBALS['log']->info("Rehashed portal password for contact id '{$this->id}'");
            } else {
                $GLOBALS['log']->warn("Error trying to rehash portal password for contact id '{$this->id}'");
            }
        }
    }

    /**
     * {@inheritDoc}
     *
     * In Portal, allows to access only the logged in Contact.
     */
    public function getOwnerWhere($user_id, $table_alias = null)
    {
        if (isset($_SESSION['type'], $_SESSION['contact_id']) && $_SESSION['type'] === 'support_portal') {
            if ($table_alias === null) {
                $table_alias = $this->table_name;
            }
            return $table_alias  . '.id = ' . $this->db->quoted($_SESSION['contact_id']);
        }

        return parent::getOwnerWhere($user_id, $table_alias);
    }
}
