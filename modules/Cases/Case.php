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

// aCase is used to store case information.
class aCase extends Basic
{
    // Stored fields
    var $id;
    var $date_entered;
    var $date_modified;
    var $modified_user_id;
    var $assigned_user_id;
    var $team_id;
    var $case_number;
    var $resolution;
    var $description;
    var $name;
    var $status;
    var $priority;

    var $created_by;
    var $created_by_name;
    var $modified_by_name;

    // These are related
    var $bug_id;
    var $account_name;
    var $account_id;
    var $contact_id;
    var $task_id;
    var $note_id;
    var $meeting_id;
    var $call_id;
    var $email_id;
    var $assigned_user_name;
    var $team_name;

    var $table_name = "cases";
    var $rel_account_table = "accounts_cases";
    var $rel_contact_table = "contacts_cases";
    var $module_dir = 'Cases';
    var $object_name = "Case";
    var $importable = true;
    /** "%1" is the case_number, for emails
     * leave the %1 in if you customize this
     * YOU MUST LEAVE THE BRACKETS AS WELL*/
    var $emailSubjectMacro = '[CASE:%1]';

    // This is used to retrieve related fields from form posts.
    var $additional_column_fields = array(
        'bug_id',
        'assigned_user_name',
        'assigned_user_id',
        'contact_id',
        'task_id',
        'note_id',
        'meeting_id',
        'call_id',
        'email_id'
    );

    var $relationship_fields = array(
        'account_id'=>'accounts',
        'bug_id' => 'bugs',
        'task_id'=>'tasks',
        'note_id'=>'notes',
        'meeting_id'=>'meetings',
        'call_id'=>'calls',
        'email_id'=>'emails',
    );


    public function __construct()
    {
        parent::__construct();
        global $sugar_config;
        if (empty($sugar_config['require_accounts'])) {
            unset($this->required_fields['account_name']);
        }

        $this->setupCustomFields('Cases');
        foreach ($this->field_defs as $name => $field) {
            $this->field_defs[$name] = $field;
        }
    }

    var $new_schema = true;





    function get_summary_text()
    {
        return "$this->name";
    }

    function listviewACLHelper()
    {
        $array_assign = parent::listviewACLHelper();
        $is_owner = false;
        if (!empty($this->account_id)) {
            if (!empty($this->account_id_owner)) {
                global $current_user;
                $is_owner = $current_user->id == $this->account_id_owner;
            }
        }
        if (!ACLController::moduleSupportsACL('Accounts') ||
            ACLController::checkAccess('Accounts', 'view', $is_owner)
        ) {
            $array_assign['ACCOUNT'] = 'a';
        } else {
            $array_assign['ACCOUNT'] = 'span';
        }

        return $array_assign;
    }

    /**
     * This function is a good location to save changes that have been made to a relationship.
     * This should be overridden in subclasses that have something to save.
     *
     * @param boolean $is_update    true if this save is an update.
     * @param array $exclude        a way to exclude relationships
     *
     * @see SugarBean::save_relationship_changes()
     */
    public function save_relationship_changes($is_update, $exclude = array())
    {
        parent::save_relationship_changes($is_update);

        if (!empty($this->contact_id)) {
            $this->set_case_contact_relationship($this->contact_id);
        }
    }

    function set_case_contact_relationship($contact_id)
    {
        global $app_list_strings;
        $default = $app_list_strings['case_relationship_type_default_key'];
        $this->load_relationship('contacts');
        $this->contacts->add($contact_id, array('contact_role'=>$default));
    }

    /**
     * Returns a list of the associated contacts
     */
    function get_contacts()
    {
        $this->load_relationship('contacts');
        $query_array=$this->contacts->getQuery(true);

        // update the select clause in the returned query.
        $query_array['select'] = "SELECT contacts.id, contacts.first_name, contacts.last_name, contacts.title, contacts.email1, contacts.phone_work, contacts_cases.contact_role as case_role, contacts_cases.id as case_rel_id ";

        $query='';
        foreach ($query_array as $qstring) {
            $query.=' '.$qstring;
        }
        $temp = array('id', 'first_name', 'last_name', 'title', 'email1', 'phone_work', 'case_role', 'case_rel_id');
        return $this->build_related_list2($query, BeanFactory::newBean('Contacts'), $temp);
    }

    function get_list_view_data()
    {
        global $current_language;
        $app_list_strings = return_app_list_strings_language($current_language);

        $temp_array = $this->get_list_view_array();
        $temp_array['NAME'] = (($this->name == "") ? "<em>blank</em>" : $this->name);
        $temp_array['PRIORITY'] = empty($this->priority)
            ? ""
            : (!isset($app_list_strings[$this->field_defs['priority']['options']][$this->priority])
                ? $this->priority
                : $app_list_strings[$this->field_defs['priority']['options']][$this->priority]);
        $temp_array['STATUS'] = empty($this->status)
            ? ""
            : (!isset($app_list_strings[$this->field_defs['status']['options']][$this->status])
                ? $this->status
                : $app_list_strings[$this->field_defs['status']['options']][$this->status]);
        $temp_array['ENCODED_NAME'] = $this->name;
        $temp_array['CASE_NUMBER'] = $this->case_number;
        $temp_array['SET_COMPLETE'] =  "<a href='index.php?return_module=Home&return_action=index&action=EditView&module=Cases&record=$this->id&status=Closed'>".SugarThemeRegistry::current()->getImage("close_inline", "title=".translate('LBL_LIST_CLOSE', 'Cases')." border='0'", null, null, '.gif', translate('LBL_LIST_CLOSE', 'Cases'))."</a>";
        $temp_array['CASE_NUMBER'] = format_number_display($this->case_number);
        return $temp_array;
    }

    /**
        builds a generic search based on the query string using or
        do not include any $this-> because this is called on without having the class instantiated
    */
    function build_generic_where_clause($the_query_string)
    {
        $where_clauses = array();
        $the_query_string = $this->db->quote($the_query_string);
        array_push($where_clauses, "cases.name like '$the_query_string%'");
        array_push($where_clauses, "accounts.name like '$the_query_string%'");

        if (is_numeric($the_query_string)) {
            array_push($where_clauses, "cases.case_number like '$the_query_string%'");
        }

        $the_where = "";

        foreach ($where_clauses as $clause) {
            if ($the_where != "") {
                $the_where .= " or ";
            }
            $the_where .= $clause;
        }

        if ($the_where != "") {
            $the_where = "(".$the_where.")";
        }

        return $the_where;
    }

    function set_notification_body($xtpl, $case)
    {
        global $app_list_strings;

        $xtpl->assign("CASE_SUBJECT", $case->name);
        $xtpl->assign(
            "CASE_PRIORITY",
            (isset($case->priority) ? $app_list_strings['case_priority_dom'][$case->priority]:""));
        $xtpl->assign("CASE_STATUS", (isset($case->status) ? $app_list_strings['case_status_dom'][$case->status]:""));
        $xtpl->assign("CASE_DESCRIPTION", $case->description);

        return $xtpl;
    }

    function bean_implements($interface)
    {
        switch ($interface) {
            case 'ACL':
                return true;
        }
        return false;
    }

    /**
     * retrieves the Subject line macro for InboundEmail parsing
     * @return string
     */
    function getEmailSubjectMacro()
    {
        global $sugar_config;
        return (isset($sugar_config['inbound_email_case_subject_macro']) && !empty($sugar_config['inbound_email_case_subject_macro'])) ?
            $sugar_config['inbound_email_case_subject_macro'] : $this->emailSubjectMacro;
    }
}
