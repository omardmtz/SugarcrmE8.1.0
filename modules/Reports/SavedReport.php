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

// Contact is used to store customer information.
class SavedReport extends Basic
{
	// Stored fields
	var $id;
	var $name;
	var $module;
	var $report_type;
	var $content;
	var $deleted;
	var $date_entered;
	var $date_modified;
	var $assigned_user_id;
	var $assigned_user_name;
	var $modified_user_id;
	var $created_by;
	var $created_by_name;
	var $modified_by_name;
	var $is_published;
	var $team_id;
	var $team_name;
	var $chart_type;

	//variables for joining the report schedules table
	var $schedule_id;
	var $next_run;
	var $active;
	var $time_interval;
	var $last_run_date;


	var $table_name = "saved_reports";
	var $schedules_table = "report_schedules";
	var $report_cache_table = "report_cache";
	var $object_name = "SavedReport";
	var $module_dir = 'Reports';
	var $new_schema = true;

	var $column_fields = Array("id"
		,"name"
		,"module"
		,"report_type"
		,"content"
                ,"deleted"
                ,"date_entered"
                ,"date_modified"
                ,"assigned_user_id"
                ,"team_id"
                ,"modified_user_id"
                ,"created_by"
		,"is_published"
		,"is_chart_dashlet"
		);

	var $list_fields = array('id', 'name', 'module', 'report_type', 'schedule_id', 'active', 'next_run', 'last_run_date');

    function save_report(
        $id,
        $owner_id,
        $name,
        $module,
        $report_type,
        $content,
        $is_published = 0,
        $team_id,
        $chart_type = 'none',
        $teamSetSelectedId = null
    ) {
		global $json;
		global $current_user;

		if ( $id != -1) {
			$query_arr = array( 'id'=>$id);
			$this->retrieve_by_string_fields($query_arr, false);

			if (! isset($this->id) ) {
				//.. don't do it!
				return -1;
			}
		}

		// cn: SECURITY bug 12272
		$name = filter_var($name, FILTER_SANITIZE_STRIPPED, FILTER_FLAG_NO_ENCODE_QUOTES);
        if (empty($name))
        {
            $name = translate('LBL_UNTITLED', 'Reports');
        }
		$json = getJSONobj();
		$tmpContent = $json->decode($content, false);

		// clean stored report_name too
		$tmpContent['report_name'] = $name;
		$content = $json->encode($tmpContent, false);
		// end SECURITY

		$result = 1;
		$this->assigned_user_id = $owner_id;
		$this->name = $name;
		$this->content = $content;
		$this->deleted = 0;
		$this->report_type = $report_type;
		$this->team_id = $team_id;
		$this->module = $module;
		$this->is_published = $is_published;
		$this->chart_type = $chart_type;
        $this->acl_team_set_id = $teamSetSelectedId;

		$this->save();
		return $result;
	}

	function mark_deleted($id){
	    $report_schedule = new ReportSchedule();
	    $scheduled_reports = $report_schedule->get_report_schedule($id);
	    foreach($scheduled_reports as $rs_row){
	        $report_schedule->mark_deleted($rs_row['id']);
	    }
	    parent::mark_deleted($id);
	}

		function mark_published($flag)
		{
			global $current_user;

			if ( ! is_admin($current_user) )
			{
						//.. don't do it!
						return -1;
			}

			if ( $flag == 'no'
			&& $this->team_id == '1'
			)
			{
				$owner_user = BeanFactory::getBean('Users', $this->assigned_user_id);
				$default_team = $owner_user->getPrivateTeamID();
				$query = "UPDATE $this->table_name set is_published='no' ";
				$query .= ", team_id='{$default_team}' ";
				$query .= " where id='".$this->id."'";
			}
			else {
				$query = "UPDATE $this->table_name set is_published='$flag'";
				$query .= ", team_id='1' ";
				$query .= " where id='".$this->id."'";

			}
				$this->db->query($query,true,"Error marking import map published: ");
				return 1;
	}


	function retrieve_all_by_string_fields($fields_array,$order_by='')
	{
		$where_clause = $this->get_where($fields_array);
		$query = "SELECT * FROM $this->table_name $where_clause";
		if ( ! empty($order_by))
		{
			$query .= parent::create_qualified_order_by( $order_by, 'saved_reports');
		}
		$GLOBALS['log']->debug("Retrieve $this->object_name: ".$query);
		$result = $this->db->query($query,true," Error: ");
		$obj_arr = array();

		while ($row = $this->db->fetchByAssoc($result,FALSE) )
		{
			$focus = BeanFactory::newBean('Reports');

			foreach($this->column_fields as $field)
			{
				if(isset($row[$field]))
				{
					$focus->$field = $row[$field];
				}
			}
			$focus->fill_in_additional_detail_fields();
			array_push($obj_arr,$focus);
		}

		return $obj_arr;

	}

	function fill_in_additional_list_fields()
	{
        parent::fill_in_additional_detail_fields();
        $this->get_scheduled_query();
	}

	function fill_in_additional_detail_fields()
	{
		if ($this->report_type == "Matrix") {
			$this->report_type = "summary";
		} // if
		parent::fill_in_additional_detail_fields();
		$this->get_scheduled_query();
	}


	function get_scheduled_query(){
		global $current_user;
		$query = "	SELECT
					$this->schedules_table.id as schedule_id,
                    $this->schedules_table.active as active,
                    $this->schedules_table.next_run as next_run
                    from ".$this->schedules_table."
                    WHERE ".$this->schedules_table.".report_id = " . $this->db->quoted($this->id) . "
                    AND ".$this->schedules_table.".user_id = " . $this->db->quoted($current_user->id) . "
					and ".$this->schedules_table.".deleted=0
					";
		$result = $this->db->query($query,true," Error filling in additional schedule query detail fields: ");

		// Get the id and the name.
		$row = $this->db->fetchByAssoc($result);

		if($row != null)
		{
			$this->schedule_id = $row['schedule_id'];
			$this->active = $row['active'];
			$this->next_run = $this->db->fromConvert($row['next_run'],'datetime');
		}
		else
		{
			$this->schedule_id = "";
			$this->active = "";
			$this->next_run = "";
		}
	//end get_scheduled_query
	}



   	function create_list_query($order_by, $where, $show_deleted = 0)
	{

                $query = "
							SELECT
								$this->table_name.*,
								$this->schedules_table.id as schedule_id,
                            	$this->schedules_table.active as active,
                            	$this->schedules_table.next_run as next_run,
                            	$this->report_cache_table.date_modified as last_run_date
                            FROM
								$this->table_name
							LEFT OUTER JOIN
								$this->report_cache_table
								ON $this->table_name.id = $this->report_cache_table.id
								AND $this->table_name.assigned_user_id = $this->report_cache_table.assigned_user_id
							LEFT JOIN
								$this->schedules_table
                                ON $this->table_name.id=$this->schedules_table.report_id
								AND $this->schedules_table.deleted=0
								";
            $this->add_team_security_where_clause($query,'','INNER',false,true);
                $where_auto = '1=1';
				if($show_deleted == 0){
                	$where_auto = "$this->table_name.deleted=0";
				}else if($show_deleted == 1){
                	$where_auto = "$this->table_name.deleted=1";
				}

                if($where != "")
                        $query .= "where ($where) AND ".$where_auto;
                else
                        $query .= "where ".$where_auto;



                if(!empty($order_by))
                        $query .=  parent::create_qualified_order_by( $order_by, 'saved_reports');

                return $query;
	}

/*The following function has been modified in order to better integrate the Report Lists with the core Sugar functions
* Normally you would see this type of code in a editview.php or detailview.php file.  However, the nature of of the
* report lists is different than most listview scenarios.
*
*/

	function get_list_view_data(){
        $temp_array = $this->get_list_view_array();
		global $timedate;
		global $app_strings;
		global $mod_strings;
		global $app_list_strings;

		global $current_user;
        global $current_language;

        $mod_strings = return_module_language($current_language, 'Reports');
		//set the following four buttons.

		if(isset($this->schedule_id) && $this->active == 1){
			$is_scheduled = $timedate->to_display_date_time($this->next_run);
		} else {
			$is_scheduled = $mod_strings['LBL_NONE'];
		}

		$view = sprintf("%s %s", SugarThemeRegistry::current()->getImage('view_inline','border="0" align="absmiddle"',null,null,'.gif',$mod_strings['LBL_VIEW']), $mod_strings['LBL_VIEW']);

	//logic for showing delete, publish, and unpublish buttons
			$delete_img = SugarThemeRegistry::current()->getImage('delete_inline','border="0" align="absmiddle"',null,null,'.gif',$mod_strings['LBL_DELETE']);
			$delete_line = "<a href=\"index.php?module=Reports&action=index&delete_report_id=".$this->id."\" class=\"listViewTdToolsS1\"> $delete_img ". $mod_strings['LBL_DELETE']."</a>";

			$unpublish_img = SugarThemeRegistry::current()->getImage('unpublish_inline','border="0" align="absmiddle"',null,null,'.gif',$mod_strings['LBL_UN_PUBLISH']);
			$publish_img = SugarThemeRegistry::current()->getImage('publish_inline','border="0" align="absmiddle"',null,null,'.gif',$mod_strings['LBL_PUBLISH']);
			$unpublish_line = "<a title='{$mod_strings['LBL_UN_PUBLISH']}' href=\"index.php?module=Reports&action=index&publish=no&publish_report_id=$this->id\" class=\"listViewTdToolsS1\">$unpublish_img</a>";
			$publish_line = "<a title='{$mod_strings['LBL_PUBLISH']}' href=\"index.php?module=Reports&action=index&publish=yes&publish_report_id=$this->id\" class=\"listViewTdToolsS1\">$publish_img</a>";

		if ( is_admin($current_user) ){
			$delete = $delete_line;
			$unpublish = $unpublish_line;
			$publish_my_reports = $publish_line;
		} else {
			$delete = "<!--not_in_theme!--><img src=\"".getJSPath("include/images/blank.gif")."\" width=\"1\" height=\"1\" alt=\"\">";
			$unpublish = "<!--not_in_theme!--><img src=\"".getJSPath("include/images/blank.gif")."\" width=\"1\" height=\"1\" alt=\"\">";
			$publish_my_reports = "<!--not_in_theme!--><img src=\"".getJSPath("include/images/blank.gif")."\" width=\"1\" height=\"1\" alt=\"\">";
		}

		$delete_my_reports = $delete_line;


        $temp_array['MODULE'] = (!empty($this->module) ? $app_list_strings['moduleList'][$this->module] : '');
        $temp_array['REPORT_TYPE_TRANS'] = (!empty($this->report_type) ? $app_list_strings['dom_report_types'][$this->report_type] : '');
        $temp_array['IS_SCHEDULED'] = $is_scheduled;
		$temp_array['IS_SCHEDULED_IMG'] = "";
		$temp_array['VIEW'] = $view;
		$temp_array['DELETE'] = $delete;
		$temp_array['DELETE_MY_REPORTS'] = $delete_my_reports;
		$temp_array['UNPUBLISH_REPORT'] = $unpublish;
		$temp_array['PUBLISH_MY_REPORTS'] = $publish_my_reports;
		$temp_array['LAST_RUN_DATE'] = $timedate->to_display_date_time($this->last_run_date);

        return $temp_array;
	}

	function bean_implements($interface){
		switch($interface){
			case 'ACL': return true;
		}
		return false;
	}

	function retrieveReportIdByName($name){
		$query = "SELECT id FROM saved_reports WHERE name = '" . $name . "' AND deleted=0";

		$result = $this->db->query($query);
		$row = $this->db->fetchByAssoc($result);

		if ($row != null)
			return $row['id'];
		else
			return;
	}

	function get_summary_text(){
		return $this->name;
	}

    /**
    * @see SugarBean::cleanBean
    */
    function cleanBean() {
        foreach($this->field_defs as $key => $def) {

            if (isset($def['type'])) {
                $type=$def['type'];
            }
            if(isset($def['dbType']))
                $type .= $def['dbType'];

            if((strpos($type, 'char') !== false ||
                strpos($type, 'text') !== false ||
                $type == 'enum') &&
                !empty($this->$key)
            ) {
                // Bug51621: the report contents JSON string getting converted to html as a whole
                //    breaks reports that get cleaned
                if ($key !== "content") {
                    $str = from_html($this->$key);
                } else {
                    $str = $this->$key;
                }
                // Julian's XSS cleaner
                $potentials = clean_xss($str, false);

                if(is_array($potentials) && !empty($potentials)) {
                    foreach($potentials as $bad) {
                        $str = str_replace($bad, "", $str);
                    }
                    if ($key !== "content") {
                        $this->$key = to_html($str);
                    } else {
                        $this->$key = $str;
                    }
                }
            }
        }
    }

    /**
     * Runs the query from the saved report and returns the records
     *
     * @return array Array of records from result of report query
     */
    public function runReportQuery()
    {
        $records = array();
        $report = new SubpanelFromReports($this);
        if(!empty($report)){
            $report->run_query();
            $sql = $report->query_list[0];

            $results = $report->db->query($sql);

            while ($row = $report->db->fetchByAssoc($results)) {
                $records[] = $row;
            }
        }
        return $records;
    }
}

  // returns the available modules for the specific user
/**
 * @param bool $ignoreSessionCache  When true, just ignore any session and re-generate it
 * @return array
 */
function getACLAllowedModules($ignoreSessionCache = false) {

	if ($ignoreSessionCache === false && isset($_SESSION['reports_getACLAllowedModules'])) {
        return (array) $_SESSION['reports_getACLAllowedModules'];
    }

     require_once('modules/Reports/config.php');

     global $beanFiles, $modListHeader;

     $report_modules = getAllowedReportModules($modListHeader);

     foreach($report_modules as $module=>$class_name) {
         $seed = BeanFactory::newBean($module);

         if(empty($seed) || !$seed->ACLAccess('DetailView')) {
                unset($report_modules[$module]);
        }
     }
     $_SESSION['reports_getACLAllowedModules'] = $report_modules;
     return $report_modules;
  }

  /**
   * Return a list of modules that are not allowed to be shown in Reports listview due to ACL
   *
   * @return array of modules to be removed from Reports listview.
   */
  function getACLDisAllowedModules(){

     require_once('modules/Reports/config.php');

     global $modListHeader;

     $report_modules = getAllowedReportModules($modListHeader);

	 $unallowed_modules = array();
     foreach($report_modules as $module=>$class_name) {
         $seed = BeanFactory::newBean($module);
         if(empty($seed) || !$seed->ACLAccess('DetailView')) {
                $unallowed_modules[$module] = $class_name;
        }
     }

     return $unallowed_modules;
  }

/**
 * Gets a list of allowed report modules in the current language.
 *
 * @return array List of translated module names.
 */
function getModulesDropdown()
{
    require_once 'modules/Reports/config.php';
    global $app_list_strings, $report_modules;

    $allowed_modules = array();

    foreach ($report_modules as $module => $value) {
        $allowed_modules[$module] = $app_list_strings['moduleList'][$module];
    }
    asort($allowed_modules);
    return $allowed_modules;
}
