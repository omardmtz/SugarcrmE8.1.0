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


require_once 'include/SugarSmarty/plugins/function.sugar_csrf_form_token.php';

// CustomQuery is used to store custom sql queries.
class CustomQuery extends SugarBean {
	// Stored fields
	var $id;
	var $deleted;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $created_by;
	var $created_by_name;
	var $modified_by_name;

	var $team_id;

	var $name;
	var $description;
	var $custom_query;
	var $query_type;
	var $query_locked;

	//for sub_query work
	var $sub_query_array;

	//for saving
	var $statis_query;


	var $data_set;
	var $column_quantity;
	var $column_array;

	var $table_name = "custom_queries";

	var $object_name = "CustomQuery";
	var $module_dir = 'CustomQueries';
	var $new_schema = true;

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array();

    /**
     * @var DBManager
     */
    protected $db_slave;


	public function __construct() {
		parent::__construct();
		$this->disable_row_level_security =false;

	}

	function get_summary_text()
	{
		return "$this->name";
	}


	function get_custom_queries($add_blank=false)
	{
		$query = "SELECT id, name FROM $this->table_name where deleted=0 order by list_order asc";
        $result = $this->getSlaveDb()->query($query, false);
		$GLOBALS['log']->debug("get_custom_queries: result is ".print_r($result,true));

		$list = array();
		if ($add_blank) {
			$list['']='';
		}
        while (($row = $this->getSlaveDb()->fetchByAssoc($result)) != null) {
			$list[$row['id']] = $row['name'];
			$GLOBALS['log']->debug("row id is:".$row['id']);
			$GLOBALS['log']->debug("row name is:".$row['name']);
		}
		return $list;
	}

	function get_custom_results($check_valid=false, $get_columns=false, $building_query=false, $listview_only=false){
	    global $current_user;
	    global $current_language;
	    $temp_mod_strings = return_module_language($current_language, "CustomQueries");
		//Store query, in case we are saving;
		$this->statis_query = $this->custom_query;
		$customQuery = $this->custom_query;

		//If check valid is set to true, then we are just checking for a valid query

		// if this is a sub_query, then prepare
		if(!empty($this->sub_query_array)){
			$split_query = preg_split('{{sub}}', $customQuery);
			if(!empty($split_query[1])){
				$split_query_chunk = $split_query[1];
			} else {
				$split_query_chunk = "";
			}
			$sub_chunk = "{sub}".$split_query_chunk."{sub}";

			if(!empty($split_query_chunk) && $split_query_chunk !=""){
				$replacement_chunk = $this->sub_query_array[$split_query_chunk];
			} else {
				$replacement_chunk = "";
			}

			$customQuery = str_replace($sub_chunk, $replacement_chunk, $customQuery);

		//end if this is a sub-query
		} else {

			//only run through this if it not just a simple check of validity.
			if($building_query!=true){
			//check for the word sub and return some sort of special message saying this is only
			//accessable via the parent data set
				$is_sub_query = strpos($customQuery, '{sub}');
				if($is_sub_query!==false){
					//if this function is not called from custom_layout then just return error
					if($get_columns==true){
						//otherwise do nothing and continue
					}else {
						$valid['result'] = "Error";
						$valid['result_type'] = "Child";
						$valid['result_msg'] = $temp_mod_strings['CHILD_RESULT_MSG'];
						$valid['msg'] = $temp_mod_strings['CHILD_ERROR_MSG'];
						return $valid;
					}
				//end if is_sub_query is not false
				}

			//end if this is not just a validity check
			}

		//end if else
		}

		if($check_valid==true || $get_columns==true){
			$split_query = preg_split('{{sub}}', $customQuery);
			if(!empty($split_query[1])){

				$sub_chunk = "{sub}".$split_query[1]."{sub}";
			} else {

				$sub_chunk = "{sub} {sub}";
			}
			$replacement_chunk = "1";
			$customQuery = str_replace($sub_chunk, $replacement_chunk, $customQuery);
		//replace the sub_query with dummy value
		}

		//Add the team membership join if the {{teamjoin}}basemodule{{teamjoin}} tag exists

		//first check query for the following {{tj}}
		if(strpos($customQuery, "{tj}")!==false){

			//Not adin
			if(!is_admin($current_user) || $building_query==true){
				$split_query = preg_split('{{tj}}', $customQuery);

				$team_join_part = 	"INNER JOIN team_memberships
								ON team_memberships.deleted=0 AND ".$split_query[1].".team_id = team_memberships.team_id
								AND team_memberships.user_id = '".$current_user->id."'";

				$sub_chunk = "{tj}".$split_query[1]."{tj}";

				$replacement_chunk = $team_join_part;

				$customQuery = str_replace($sub_chunk, $replacement_chunk, $customQuery);
			} else {
				//If admin, remove the tj tags from the statement
				$split_query = preg_split('{{tj}}', $customQuery);
				$sub_chunk = "{tj}".$split_query[1]."{tj}";
				$replacement_chunk = "";
				$customQuery = str_replace($sub_chunk, $replacement_chunk, $customQuery);
			}

		//end if we need to add team_join_part
		}
		//This checks for either a bad query or checks for a wrong type of query.  Will only pass if
		//it is a select statement.
		$decoded_query = html_entity_decode($customQuery, ENT_QUOTES);
        $result = $this->getSlaveDb()->validateQuery($decoded_query);

        if(!$result){

			$valid['result'] = "Error";
			$valid['result_msg'] = $temp_mod_strings['ERROR_RESULT_MSG'];
			return $valid;
		//Else Query is valid
		} else {

			if($listview_only==false){
				//Check to see if the query has any blank columns or duplicate columns
				if($check_valid==true){
					$blankdup_check = false;

                    $result =$this->getSlaveDb()->query($decoded_query, false);
					$GLOBALS['log']->debug("get_custom_queries: result is ".print_r($result,true));

						if(!empty($result)) {
							//get the column array
                            $fields_array = $this->getSlaveDb()->getFieldsArray($result, true);
							foreach($fields_array as $key => $column){

								//check for blank
								if(empty($column) || $column ==""){
									$blankdup_check = true;
								}
							//end foreach loop
							}
						//end if rows exist
						}

					if($blankdup_check == true){

						$valid['result'] = "Error";
						$valid['result_msg'] = $temp_mod_strings['ERROR_RESULT_MSG'];
						$valid['msg'] = $temp_mod_strings['DUPBLANK_ERROR_MSG'];
						unset($temp_mod_strings);
						return $valid;
					}

				//end blank or dup check
				}
			//end if listview_only is false
			}



			//if we are just checking validity, then return at this point
			if($check_valid==true){
				$valid['result'] = "Valid";
				return $valid;
			}
            $result =$this->getSlaveDb()->query($decoded_query, false);
			$GLOBALS['log']->debug("get_custom_queries: result is ".print_r($result,true));

			if(!empty($result)){
                $this->column_array = $this->getSlaveDb()->getFieldsArray($result, true);
				$this->column_quantity = count($this->column_array);
				$this->data_set = $result;
			} else {
				$this->data_set = 0;
			}

		//end else is valid
		}

	//end function get_custom_results
	}

	function create_export_query($order_by="", $where=""){

		$this->retrieve($_REQUEST['record']);

		return html_entity_decode($this->custom_query, ENT_QUOTES);

	//end function export query
	}

    public function save_relationship_changes($is_update, $exclude = array())
    {
    }


	function mark_relationships_deleted($query_id){

	//find data sets where query is linked and custom layout is enabled

		//disable any custom layouts
        $query = sprintf(
            "SELECT id FROM data_sets WHERE query_id = %s AND custom_layout = 'Enabled' AND deleted = 0",
            $this->db->quoted($query_id)
        );
        $result = $this->getSlaveDb()->query($query, true, "Error selecting related datasets: ");
		$GLOBALS['log']->debug("selecting related datasets: result is ".print_r($result,true));
		//if($this->db->getRowCount($result) > 0){
		//data sets exists with this query and custom layout enabled
        while (($row = $this->getSlaveDb()->fetchByAssoc($result)) != null) {

				$dataset_object = BeanFactory::getBean('DataSets', $row['id']);
				$dataset_object->disable_custom_layout();

			//end while rows
			}

		//end if there are rows
		//}

		//remove query_id
        $query = sprintf(
            "UPDATE data_sets SET query_id = '' WHERE query_id = %s AND deleted = 0",
            $this->db->quoted($query_id)
        );
		$this->db->query($query,true,"Error deleting query_id from datasets: ");

	}

	function fill_in_additional_list_fields(){
		$this->fill_in_additional_detail_fields();
	}

	function get_list_view_data(){
		global $app_strings;
        global $mod_strings;

		$temp_array = $this->get_list_view_array();

		$temp_array["ENCODED_NAME"]=$this->name;

		    //Always return Valid for now.  This was done to prevent performance issues.
            $valid = array();
		    $valid['result'] = "Valid";

         if($valid['result']=="Error"){
         	if(isset($valid['result_type']) && $valid['result_type']=="Child"){
         		$temp_array["VALID"] = "<font color='blue'>".$app_strings['LBL_QUERY_CHILD']."</font>";
         	} else {
         		$temp_array["VALID"] = "<font color='red'>".$app_strings['LBL_QUERY_ERROR']."</font>";
         	}
         } else {
         	$temp_array["VALID"] = "<font color='green'>".$app_strings['LBL_QUERY_VALID']."</font>";
         }

        if (SugarACL::checkAccess($this->module_name, 'delete')) {
            $image = SugarThemeRegistry::current()->getImage(
                'delete_inline',
                'align="absmiddle" border="0"',
                null,
                null,
                '.gif',
                $app_strings['LNK_DELETE']
            );

            $url = 'index.php?' . http_build_query(array(
                'module' => $this->module_name,
                'action' => 'Delete',
                'record' => $this->id,
                'return_module' => $this->module_name,
                'return_action' => 'index',
            ));
            $url = htmlspecialchars($url);

            $csrfToken = smarty_function_sugar_csrf_form_token(array(), $smarty);

            $temp_array['DELETE_BUTTON_INLINE'] = <<<BUTTON
<form id="{$this->id}" method="post" action="{$url}">
    {$csrfToken}
    <a class="listViewTdToolsS1" href="javascript:void(0);" onclick="if (confirm('{$mod_strings['NTC_DELETE_CONFIRMATION']}')) document.getElementById('{$this->id}').submit();">{$image}&nbsp;{$app_strings['LNK_REMOVE']}</a>
</form>
BUTTON;
        }

        if (SugarACL::checkAccess($this->module_name, 'edit')) {
            $url = 'index.php?' . http_build_query(array(
                    'module' => $this->module_name,
                    'action' => 'index',
                    'record' => $this->id,
                    'edit' => 'true',
                ));
            $temp_array['LINK'] = '<a href="' . htmlspecialchars($url) . '">' . $temp_array['NAME'] . '</a>';
        } else {
            $temp_array['LINK'] = $temp_array['NAME'];
        }

         return $temp_array;

	}
	/**
		builds a generic search based on the query string using or
		do not include any $this-> because this is called on without having the class instantiated
	*/
	function build_generic_where_clause ($the_query_string) {
		$where_clauses = Array();
        $the_query_string = $this->getSlaveDb()->quote($the_query_string);
		array_push($where_clauses, "name like '$the_query_string%'");

		$the_where = "";
		foreach($where_clauses as $clause){
			if($the_where != "") $the_where .= " or ";
			$the_where .= $clause;
		}


	return $the_where;
	//end function build_generic_where_clause
	}

	function get_column_array(){
        $column_array = $this->getSlaveDb()->getFieldsArray($this->data_set, true);
		if(!empty($column_array)){
			foreach($column_array as $key => $value){
				if(empty($value)) $column_name[$key] = "&nbsp;";
			}
		}

		return $column_array;
	//end function get_column_array
	}

/*
The next group of functions handles whenever you change a custom query that is currently
in use by a data set, especially if the data set has the custom layout enabled.
-----------------------------------------------------------------------------------------
	function repair_column_binding
	function check_broken_bind
	function remove_layout
	function modify_layout
	function add_column_to_layouts
-----------------------------------------------------------------------------------------
*/

	function repair_column_binding($array_only=false){
		global $current_language;
		$temp_mod_strings = return_module_language($current_language, "CustomQueries");
	//This function will repair column bindings, when you change a column name in the csql

	//step 1.  Get  New Array
		$this->get_custom_results();
		$new_column_array = $this->get_column_array();

	//step 2. make select from new array

		$temp_select = array();

		if($array_only==false){
			$temp_select['Remove'] = $temp_mod_strings['LBL_REMOVE_LAYOUT_DATA'];
		}

		foreach($new_column_array as $key => $value){
			$temp_select[$value] = $value;
		}

		//return the array and let the bindmap handle the output
		return $temp_select;

	//end function repair_column_binding
	}


	function check_broken_bind($old_column_array){

		$check_bind = false;
		//check to see if any of the column names have changed
		$this->get_custom_results();
		$new_column_array = $this->get_column_array();

		foreach($new_column_array as $key => $value){
			$temp_select[$value] = $value;
		}

		foreach($old_column_array as $key => $value){
			if(empty($temp_select[$value])){
			//a column name has changed
				$check_bind = true;
			//end if column name has changed check
			}

		//end foreach if column names have changed
		}

		if($check_bind==true){
		//check to see if query is attached to data set with custom layout enabled
			$query = "	SELECT id FROM data_sets
						WHERE query_id='".$this->id."'
						AND custom_layout = 'Enabled'
						AND deleted='0'
						";
            $result = $this->getSlaveDb()->query($query, true, "error check custom binding: $query");
			$GLOBALS['log']->debug("check custom binding: result is ".print_r($result, true));
            if (($row = $this->getSlaveDb()->fetchByAssoc($result)) == null) {
			//if($this->db->getRowCount($result) > 0){
				//data sets exists with this query and custom layout enabled
				$check_bind=false;
				//end if rows exist
			}
		//end if check_bind is true to even see if there are any data sets with this query
		}

		return $check_bind;

	//end function check_broken_bind
	}

	function remove_layout($column_name){


		$query = "	SELECT dataset_layouts.id 'id', dataset_layouts.parent_id 'parent_id'
					FROM dataset_layouts
					LEFT JOIN data_sets ON data_sets.id = dataset_layouts.parent_id
					WHERE data_sets.query_id = '".$this->id."'
					AND dataset_layouts.parent_value='".$column_name."'
					AND data_sets.deleted = 0
					AND dataset_layouts.deleted = 0
					";
        $result = $this->getSlaveDb()->query($query, true, "Error running query removing layout for column");
		$GLOBALS['log']->debug("check custom binding remove layout: result is ".print_r($result,true));
		//data sets exists with this query and custom layout enabled
        while (($row = $this->getSlaveDb()->fetchByAssoc($result)) != null) {

			//First re-order the list_order_x
				$layout_object = new DataSet_Layout();
				$layout_object->retrieve($row['id']);
				$controller = new Controller();
				$controller->init($layout_object, "Delete");
				$controller->delete_adjust_order($row['parent_id']);


			//Second, remove the record for the layout
				$layout_object->mark_deleted($row['id']);

			//Third, remove the attribute records
				$query = "	UPDATE dataset_attributes
							SET deleted=1
							WHERE parent_id='".$row['id']."'
							AND deleted=0
						";
				$this->db->query($query,true,"Error deleting query_id from datasets: ");

			//end while
			}
		//end if rows exist
		//}
	//end function remove_layout
	}


	function modify_layout($column_name, $new_column_name){

		$query = "	SELECT dataset_layouts.id 'id'
					FROM dataset_layouts
					LEFT JOIN data_sets ON data_sets.id = dataset_layouts.parent_id
					WHERE data_sets.query_id = '".$this->id."'
					AND dataset_layouts.parent_value='".$column_name."'
					AND data_sets.custom_layout='Enabled'
					AND data_sets.deleted = '0'
					";

        $result = $this->getSlaveDb()->query($query, true, "Error running query modify layout for column");
		$GLOBALS['log']->debug("check custom binding modify layout: result is ".print_r($result, true));
		//data sets exists with this query and custom layout enabled
        while (($row = $this->getSlaveDb()->fetchByAssoc($result)) != null) {
				$dataset_object = new DataSet_Layout();
				$dataset_object->retrieve($row['id']);
				$dataset_object->parent_value = $new_column_name;
				$dataset_object->save();
			//end while
			}
		//end if rows exist
		//}

	//end function modify_layout
	}

	function add_column_to_layouts($new_column_name){

		//find out where this query exists
		$query = "	SELECT data_sets.id 'parent_id'
					FROM data_sets
					WHERE data_sets.query_id = '".$this->id."'
					AND data_sets.deleted = '0'
					";

        $result = $this->getSlaveDb()->query($query, true, "Error finding where query exists");
		$GLOBALS['log']->debug("check custom binding add columns to layout: result is ".print_r($result, true));
		//data sets exists with this query and custom layout enabled
        while (($row = $this->getSlaveDb()->fetchByAssoc($result)) != null) {
				//Get new position
				$layout_object = new DataSet_Layout();
				$controller = new Controller();
				$controller->init($layout_object, "New");
				$controller->change_component_order("", "", $row['parent_id']);
				$layout_object->construct($row['parent_id'], "Column", false, "Normal", $new_column_name);

			//end while
			}
		//end if rows exist
		//}

	//end function add_column_to_layouts
	}

    /**
     * Instantiates and returns slave database connection
     *
     * @return DBManager
     */
    protected function getSlaveDb()
    {
        if (!$this->db_slave) {
            $this->db_slave = DBManagerFactory::getInstance('reports');
        }
        return $this->db_slave;
    }

/*
	End function group dealing with changes to custom query
*/
//end class
}

?>
