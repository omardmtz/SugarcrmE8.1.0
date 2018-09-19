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
 * Data access layer for the project table
 */
class Project extends SugarBean {
	// database table columns
	var $id;
	var $date_entered;
	var $date_modified;
	var $assigned_user_id;
	var $modified_user_id;
	var $created_by;
	var $team_id;
	var $name;
	var $description;
	var $deleted;

	var $is_template;

	// related information
	var $assigned_user_name;
	var $modified_by_name;
	var $created_by_name;
	var $team_name;

	var $account_id;
	var $contact_id;
	var $opportunity_id;
	var $quote_id;
	var $email_id;
    var $estimated_start_date;

	// calculated information
	var $total_estimated_effort;
	var $total_actual_effort;

	var $object_name = 'Project';
	var $module_dir = 'Project';
	var $new_schema = true;
	var $table_name = 'project';

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = array(
		'account_id',
		'contact_id',
		'quote_id',
		'opportunity_id',
	);

	var $relationship_fields = array(
		'account_id' => 'accounts',
		'contact_id'=>'contacts',
		'quote_id'=>'quotes',
		'opportunity_id'=>'opportunities',
		'email_id' => 'emails',
		'holiday_id' => 'holidays',
	);

	//////////////////////////////////////////////////////////////////
	// METHODS
	//////////////////////////////////////////////////////////////////

    /**
    * Save changes that have been made to a relationship.
    *
    * @param $is_update true if this save is an update.
    */
    function save_relationship_changes($is_update, $exclude=array())
    {
        parent::save_relationship_changes($is_update, $exclude);
        $new_rel_id = false;
        $new_rel_link = false;
        //this allows us to dynamically relate modules without adding it to the relationship_fields array
        if(!empty($_REQUEST['relate_id']) && !in_array($_REQUEST['relate_to'], $exclude) && $_REQUEST['relate_id'] != $this->id){
            $new_rel_id = $_REQUEST['relate_id'];
            $new_rel_relname = $_REQUEST['relate_to'];
            if(!empty($this->in_workflow) && !empty($this->not_use_rel_in_req)) {
                $new_rel_id = $this->new_rel_id;
                $new_rel_relname = $this->new_rel_relname;
            }
            $new_rel_link = $new_rel_relname;
            //Try to find the link in this bean based on the relationship
            foreach ( $this->field_defs as $key => $def ) {
                if (isset($def['type']) && $def['type'] == 'link'
                && isset($def['relationship']) && $def['relationship'] == $new_rel_relname) {
                    $new_rel_link = $key;
                }
            }
            if ($new_rel_link == 'contacts') {
                $accountId = $this->db->getOne('SELECT account_id FROM accounts_contacts WHERE contact_id=' . $this->db->quoted($new_rel_id));
                if ($accountId !== false) {
                    if($this->load_relationship('accounts')){
                        $this->accounts->add($accountId);
                    }
                }
            }
        }
    }
	/**
	 *
	 */
	function _get_total_estimated_effort($project_id)
	{
		$return_value = '';

        $query = 'SELECT SUM('.$this->db->convert('estimated_effort', "IFNULL", array('0')).') total_estimated_effort';
		$query.= ' FROM project_task';
        $query.= " WHERE project_id='{$project_id}' AND deleted=0";

		$result = $this->db->query($query,true," Error filling in additional detail fields: ");
		$row = $this->db->fetchByAssoc($result);
		if($row != null)
		{
			$return_value = $row['total_estimated_effort'];
		}

		return $return_value;
	}

	/**
	 *
	 */
	function _get_total_actual_effort($project_id)
	{
		$return_value = '';

        $query = 'SELECT SUM('.$this->db->convert('actual_effort', "IFNULL", array('0')).') total_actual_effort';
		$query.=  ' FROM project_task';
        $query.=  " WHERE project_id='{$project_id}' AND deleted=0";

		$result = $this->db->query($query,true," Error filling in additional detail fields: ");
		$row = $this->db->fetchByAssoc($result);
		if($row != null)
		{
			$return_value = $row['total_actual_effort'];
		}

		return $return_value;
	}

	/**
	 *
	 */
	function get_summary_text()
	{
		return $this->name;
	}

	/**
	 *
	 */
	function build_generic_where_clause ($the_query_string)
	{
		$where_clauses = array();
		$the_query_string = $GLOBALS['db']->quote($the_query_string);
		array_push($where_clauses, "project.name LIKE '%$the_query_string%'");

		$the_where = '';
		foreach($where_clauses as $clause)
		{
			if($the_where != '') $the_where .= " OR ";
			$the_where .= $clause;
		}

		return $the_where;
	}

	function get_list_view_data()
	{
		$field_list = $this->get_list_view_array();
		$field_list['USER_NAME'] = empty($this->user_name) ? '' : $this->user_name;
		$field_list['ASSIGNED_USER_NAME'] = $this->assigned_user_name;
		return $field_list;
	}
	  function bean_implements($interface){
		switch($interface){
			case 'ACL':return true;
		}
		return false;
	}

    /** {@inheritDoc} */
    public function mark_deleted($id)
    {
        $this->markTasksDeleted($id);

        parent::mark_deleted($id);
    }

    /**
     * Marks project tasks deleted
     *
     * @param string $id
     */
    protected function markTasksDeleted($id)
    {
        if ($this->id != $id) {
            $bean = BeanFactory::newBean($this->module_name, $id);
            $bean->id = $id;
        } else {
            $bean = $this;
        }

        if (!$bean->load_relationship('projecttask')) {
            return;
        }

        foreach ($bean->projecttask->getBeans() as $task) {
            $task->mark_deleted($task->id);
        }
    }

	function getProjectHolidays()
	{
	    $firstName = $this->db->convert($this->db->convert('users.first_name', "IFNULL", array('contacts.first_name')), "IFNULL", array("''"));
	    $lastName = array("' '", $this->db->convert($this->db->convert('users.last_name', "IFNULL", array('contacts.last_name')), "IFNULL", array("''")));
	    $resource_select = "LTRIM(RTRIM(" . $this->db->convert($firstName, "CONCAT", $lastName) . "))";

	    $query = "SELECT holidays.id, holidays.holiday_date, holidays.description as description, "
				. $resource_select . " as resource_name " .
				" FROM holidays LEFT JOIN users on users.id = holidays.person_id" .
				" LEFT JOIN contacts on contacts.id = holidays.person_id" .
				" WHERE related_module_id = '".$this->id."'" .
				" AND holidays.related_module like 'Project'" .
				" AND holidays.deleted = 0";
		return $query;
	}

	function isTemplate(){
		if (!empty( $this->is_template) && $this->is_template=='1')
			return true;
		else
			return false;
	}
	function getAllProjectTasks(){
		$projectTasks = array();

		$query = "SELECT * FROM project_task WHERE project_id = '" . $this->id. "' AND deleted = 0 ORDER BY project_task_id";
		$result = $this->db->query($query,true,"Error retrieving project tasks");
		while (($row = $this->db->fetchByAssoc($result)) != null){
		    $projectTaskBean = BeanFactory::retrieveBean('ProjectTask', $row['id']);
		    if(empty($projectTaskBean)) continue;
			array_push($projectTasks, $projectTaskBean);
		}

		return $projectTasks;
	}
	/* helper function for UserHoliday subpanel -- display javascript that cannot be achieved through AJAX call */
	function resourceSelectJS(){
       	$userBean = BeanFactory::newBean('Users');
    	$contactBean = BeanFactory::newBean('Contacts');

    	$this->load_relationship("user_resources");
    	$userResources = $this->user_resources->getBeans($userBean);
    	$this->load_relationship("contact_resources");
    	$contactResources = $this->contact_resources->getBeans($contactBean);

		ksort($userResources);
		ksort($contactResources);

		$userResourceOptions = "";
		$contactResourceOptions = "";

		$i=0;
		$userResourceArr = "var userResourceArr = document.getElementById('person_id').options;\n";
        //set the dropdown to '-none', or retrieve User Resources
        if(empty($userResources)){
            $userResourceOptions .= "var userResource$i = new Option('".$GLOBALS['app_strings']['LBL_NONE']."', '');\n";
            $userResourceOptions .= "userResourceArr[userResourceArr.length] = userResource$i;\n";
        }else{
            foreach($userResources as $userResource){
                $userResourceOptions .= "var userResource$i = new Option('$userResource->full_name', '$userResource->id');\n";
                $userResourceOptions .= "userResourceArr[userResourceArr.length] = userResource$i;\n";
                $i = $i+1;
            }
        }

		$i=0;
		$contactResourceArr = "var contactResourceArr = document.getElementById('person_id').options;\n";
		//set the dropdown to '-none', or retrieve Contact Resources
        if(empty($contactResources)){
            $contactResourceOptions .= "var contactResource$i = new Option('".$GLOBALS['app_strings']['LBL_NONE']."', '');\n";
            $contactResourceOptions .= "contactResourceArr[contactResourceArr.length] = contactResource$i;\n";
        }else{
            foreach($contactResources as $contactResource){
                $contactResourceOptions .= "var contactResource$i = new Option('$contactResource->full_name', '$contactResource->id');\n";
                $contactResourceOptions .= "contactResourceArr[contactResourceArr.length] = contactResource$i;\n";
                $i = $i+1;
            }
        }

		return "
function showResourceSelect(){
	if (document.getElementById('person_type').value=='Users') {
		constructUserSelect();
	}
	else if (document.getElementById('person_type').value=='Contacts') {
		constructContactSelect();
	}
	else{
		if (document.getElementById('person_id') != null){
			document.getElementById('resourceSelector').removeChild(document.getElementById('person_id'));
		}
	}
}
function constructSelect(){
	document.getElementById('resourceSelector').innerHTML = '<select id=\"person_id\" name=\"person_id\"></select>'
}

function constructUserSelect(){
	if (document.getElementById('person_id') != null){
		document.getElementById('resourceSelector').removeChild(document.getElementById('person_id'));
	}

	constructSelect();
	$userResourceArr
	$userResourceOptions
}

function constructContactSelect(){
	if (document.getElementById('person_id') != null){
		document.getElementById('resourceSelector').removeChild(document.getElementById('person_id'));
	}

	constructSelect();
	$contactResourceArr
	$contactResourceOptions
}
";
	}
}
