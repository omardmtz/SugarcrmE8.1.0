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
require_once('include/workflow/workflow_utils.php');
require_once('include/workflow/action_utils.php');

/**
 *  WorkFlowSchedule is used to process workflow time cron objects
 */
class WorkFlowSchedule extends SugarBean {
    // Stored fields
    var $id;
    var $deleted;
    var $date_entered;
    var $date_modified;
    var $modified_user_id;
    var $created_by;
    var $created_by_name;
    var $modified_by_name;

    var $date_expired;
    var $module;
    var $workflow_id;
    var $bean_id;

    var $parameters;

    var $table_name = "workflow_schedules";
    var $module_dir = "WorkFlow";
    var $object_name = "WorkFlowSchedule";
    var $module_name = 'WorkFlowSchedule';
    var $disable_custom_fields = true;

    var $rel_triggershells_table = 	"workflow_triggershells";
    var $rel_triggers_table = 		"workflow_triggers";
    var $rel_alertshells_table = 	"workflow_alertshells";
    var $rel_alerts_table = 		"workflow_alerts";
    var $rel_actionshells_table = 	"workflow_actionshells";
    var $rel_actions_table = 		"workflow_actions";
    var $rel_workflow_table = 		"workflow";


    var $new_schema = true;

    var $column_fields = Array("id"
        ,"module"
        ,"date_entered"
        ,"date_modified"
        ,"modified_user_id"
        ,"created_by"
        ,"date_expired"
        ,"workflow_id"
        ,"bean_id"
        ,"parameters"
        );


    // This is used to retrieve related fields from form posts.
    var $additional_column_fields = Array();

    // This is the list of fields that are in the lists.
    var $list_fields = array();

    var $relationship_fields = Array();


    // This is the list of fields that are required
    var $required_fields =  array("module"=>1, 'bean_id'=>1, 'workflow_id'=>1);

    var $disable_row_level_security = true;


    public function __construct() {
        global $dictionary;
        if(isset($this->module_dir) && isset($this->object_name) && !isset($dictionary[$this->object_name])){
            require SugarAutoLoader::existingCustomOne('metadata/workflow_schedulesMetaData.php');
        }

        parent::__construct();
    }



    function get_summary_text()
    {
        return "$this->module";
    }

    public function save_relationship_changes($is_update, $exclude = array())
    {
    }


    function mark_relationships_deleted($id)
    {
    }

    function fill_in_additional_list_fields()
    {

    }

    function fill_in_additional_detail_fields()
    {


    }


    function get_list_view_data(){
        global $app_strings, $mod_strings;
        global $app_list_strings;
        global $current_user;
        return $temp_array;
    }
    /**
        builds a generic search based on the query string using or
        do not include any $this-> because this is called on without having the class instantiated
    */
    function build_generic_where_clause ($the_query_string) {
    $where_clauses = Array();
    $the_query_string = addslashes($the_query_string);
    array_push($where_clauses, "module like '$the_query_string%'");

    $the_where = "";
    foreach($where_clauses as $clause)
    {
        if($the_where != "") $the_where .= " or ";
        $the_where .= $clause;
    }


    return $the_where;
}



////////////////Time Cron Scheduling Components///////////////////////

    function check_existing_trigger($bean_id, $workflow_id){

        $query = "	SELECT id
                    FROM $this->table_name
                    WHERE $this->table_name.bean_id = '".$bean_id."'
                    AND $this->table_name.workflow_id = '".$workflow_id."'
                    AND $this->table_name.deleted=0";
        $result = $this->db->query($query,true," Error checking for existing scheduled trigger: ");

        // Get the id and the name.
        $row = $this->db->fetchByAssoc($result);

        if($row != null)
        {
            $this->retrieve($row['id']);
            return true;
        }
        else
        {

            return false;
        }
    //end function check_existing_trigger
    }


    function set_time_interval($bean_object, $time_array, $update = false){

        if($update == false && $time_array['time_int_type']=="normal") {
            //take current date and add the time interval
            $this->date_expired = get_expiry_date("datetime", $time_array['time_int']);
            //end if update is false, then create a new time expiry
        }

        if($update == true || $time_array['time_int_type']=="datetime") {
            // Bug # 46938, cannot call get_expiry_date in action_utils directly
            $this->date_expired = $this->get_expiry_date($bean_object, $time_array['time_int'], true, $time_array['target_field']);
            //end if update is true, then just update existing expiry
        }
    //end function set_time_interval
    }

    /**
     * @deprecated
     */
    function get_expiry_date($bean_object, $time_interval, $is_update = false, $target_field="none")
    {
        $target_stamp = null;

        if ($is_update) {
            if ($target_field == "none") {
                $target_stamp = TimeDate::getInstance()->nowDb();
            } else {
                if (!empty($bean_object->$target_field)) {
                    //Date fields need to be reformated to datetimes to be used with scheduler
                    if ($bean_object->field_defs[$target_field]['type'] == "date" &&
                        is_string($bean_object->$target_field)) {
                        $date = TimeDate::getInstance()->fromDbDate($bean_object->$target_field);
                        $target_stamp = TimeDate::getInstance()->asDb($date);
                    } else {
                        $target_stamp = $bean_object->$target_field;
                    }
                }
            }
        }

        return get_expiry_date("datetime", $time_interval, false, $is_update, $target_stamp);
    }

    function process_scheduled() {
        $current_stamp = $this->db->now();

        $query = "SELECT *
                    FROM $this->table_name
                    WHERE $this->table_name.date_expired < " . $current_stamp . "
                    AND $this->table_name.deleted = 0
                    ORDER BY $this->table_name.id, $this->table_name.workflow_id";

        $result = $this->db->query(
            $query,
            true,
            " Error checking scheduled triggers to process: "
        );

        // Collect workflows related to the same bean_id, and process them together
        $removeExpired = array();
        $beans = array();
        while ($row = $this->db->fetchByAssoc($result)) {
            if (!isset($beans[$row['bean_id']])) {
                $beans[$row['bean_id']] = array(
                    'id' => $row['bean_id'],
                    'module' => $row['target_module'],
                    'workflows' => array($row['workflow_id']),
                    'parameters' => array(
                        $row['workflow_id'] => $row['parameters']
                    ),
                );
            } else {
                $beans[$row['bean_id']]['workflows'][] = $row['workflow_id'];
                $beans[$row['bean_id']]['parameters'][$row['workflow_id']] = $row['parameters'];
            }
            $removeExpired[] = $row['id'];
        }

        foreach ($beans as $bean) {
            $_SESSION['workflow_cron'] = "Yes";
            $_SESSION['workflow_id_cron'] = $bean['workflows'];
            // Set the extension variables in case we need them
            $_SESSION['workflow_parameters'] = $bean['parameters'];

            $tempBean = BeanFactory::getBean($bean['module'], $bean['id']);

            if ($tempBean->fetched_row['deleted'] == '0') {
                $tempBean->update_date_modified = false;
                $tempBean->save();
            }

            unset($_SESSION['workflow_cron']);
            unset($_SESSION['workflow_id_cron']);
            unset($_SESSION['workflow_parameters']);
        }

        $this->remove_expired($removeExpired);
    }

    function remove_expired($ids) {
        $removeQuery = "DELETE FROM $this->table_name
                        WHERE $this->table_name.id IN ('" . implode("', '", $ids) . "')";

        $this->db->query(
            $removeQuery,
            true,
            " Error remove expired process: "
        );
    }
}
