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

// ReportMaker is used to build advanced reports from data formats.
class ReportMaker extends SugarBean {
	// Stored fields
	var $id;
	var $deleted;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $created_by;
	var $created_by_name;
	var $modified_by_name;

	var $name;
	var $description;
	var $title;
	var $team_id;

	//UI parameters
	var $report_align;

	//variables for joining the report schedules table
	var $schedule_id;
	var $next_run;
	var $active;
	var $time_interval;



	//for the name of the parent if an interlocked data set
	var $parent_name;

	//for related fields
	var $query_name;

	var $table_name = "report_maker";
	var $module_dir = 'ReportMaker';
	var $object_name = "ReportMaker";
	var $rel_dataset = "data_sets";
	var $schedules_table = "report_schedules";

	var $new_schema = true;


	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array();

	public function __construct() {
		parent::__construct();

		//make sure only people in the same team can see the reports
		$this->disable_row_level_security =false;

	}



	function get_summary_text()
	{
		return "$this->name";
	}

    public function save_relationship_changes($is_update, $exclude = array())
    {
    }


    public function mark_deleted($id)
    {
        $query = "update data_sets set report_id='' where report_id= ? and deleted=0";
        $conn = $this->db->getConnection();
        $conn->executeQuery($query, array($id));
        parent::mark_deleted($id);
    }


	function mark_relationships_deleted($id)
	{
	}

	function fill_in_additional_list_fields()
	{
		$this->fill_in_additional_detail_fields();
	}

	function fill_in_additional_detail_fields()
	{
		parent::fill_in_additional_detail_fields();
		$this->get_scheduled_query();
	}

	function get_scheduled_query(){

		$query = "	SELECT
					$this->schedules_table.id schedule_id,
                    $this->schedules_table.active active,
                    $this->schedules_table.next_run next_run
                    from ".$this->schedules_table."
                    WHERE ".$this->schedules_table.".report_id = " . $this->db->quoted($this->id) . "
					and ".$this->schedules_table.".deleted=0
					";
		$result = $this->db->query($query,true," Error filling in additional schedule query: ");

		// Get the id and the name.
		$row = $this->db->fetchByAssoc($result);

		if($row != null){
			$this->schedule_id = $row['schedule_id'];
			$this->active = $row['active'];
			$this->next_run = $row['next_run'];
		} else {
			$this->schedule_id = "";
			$this->active = "";
			$this->next_run = "";
		}
	//end get_scheduled_query
	}


	function get_list_view_data(){
		global $timedate;
		global $app_strings, $mod_strings;
		global $app_list_strings;


		global $current_user;

		if(empty($this->published)) $this->published="0";

		$temp_array = parent::get_list_view_data();
		$temp_array['NAME'] = (($this->name == "") ? "<em>blank</em>" : $this->name);
		$temp_array['ID'] = $this->id;

		//report scheduling
		if(isset($this->schedule_id) && $this->active == 1){
			$is_scheduled_img = SugarThemeRegistry::current()->getImage('scheduled_inline.png','border="0" align="absmiddle"',null,null,'.gif',$mod_strings['LBL_SCHEDULE_EMAIL']);
			$is_scheduled = $timedate->to_display_date_time($this->next_run);
		} else {
			$is_scheduled_img = SugarThemeRegistry::current()->getImage('unscheduled_inline.png','border="0" align="absmiddle"',null,null,'.gif',$mod_strings['LBL_SCHEDULE_EMAIL']);
			$is_scheduled = $mod_strings['LBL_NONE'];
		}

		$temp_array['IS_SCHEDULED'] = $is_scheduled;
		$temp_array['IS_SCHEDULED_IMG'] = $is_scheduled_img;

		return $temp_array;
	}
	/**
		builds a generic search based on the query string using or
		do not include any $this-> because this is called on without having the class instantiated
	*/
	function build_generic_where_clause ($the_query_string) {
	$where_clauses = Array();
	$the_query_string = $GLOBALS['db']->quote($the_query_string);
	array_push($where_clauses, "name like '$the_query_string%'");
	if (is_numeric($the_query_string)) {
		array_push($where_clauses, "mft_part_num like '%$the_query_string%'");
		array_push($where_clauses, "vendor_part_num like '%$the_query_string%'");
	}

	$the_where = "";
	foreach($where_clauses as $clause)
	{
		if($the_where != "") $the_where .= " or ";
		$the_where .= $clause;
	}


	return $the_where;

	//end function
	}


	function get_data_sets($orderBy=""){
		// First, get the list of IDs.
		$query = 	"SELECT $this->rel_dataset.id from $this->rel_dataset
					 where $this->rel_dataset.report_id='$this->id'
					 AND $this->rel_dataset.deleted=0 ".$orderBy;

		return $this->build_related_list($query, BeanFactory::newBean('DataSets'));
	//end get_data_sets
	}

//end class ReportMaker
}

?>
