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
/*********************************************************************************

 * Description: TODO:  To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/



// Employee is used to store customer information.
class Employee extends Person {
	// Stored fields
	var $name = '';
	var $id;
	var $is_admin;
	var $first_name;
	var $last_name;
	var $full_name;
	var $user_name;
	var $title;
	var $description;
	var $department;
	var $reports_to_id;
	var $reports_to_name;
	var $phone_home;
	var $phone_mobile;
	var $phone_work;
	var $phone_other;
	var $phone_fax;
	var $email1;
	var $email2;
	var $address_street;
	var $address_city;
	var $address_state;
	var $address_postalcode;
	var $address_country;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $created_by;
	var $created_by_name;
	var $modified_by_name;
	var $status;
	var $messenger_id;
	var $messenger_type;
	var $employee_status;
	var $error_string;

	var $module_dir = "Employees";
    public $module_name = 'Employees';

	var $default_team;

	var $table_name = "users";

	var $object_name = "Employee";
	var $user_preferences;

	var $encodeFields = Array("first_name", "last_name", "description");

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('reports_to_name');



	var $new_schema = true;


	public function __construct() {
		parent::__construct();
		$this->setupCustomFields('Users');
		$this->disable_row_level_security =true;
		$this->emailAddress = BeanFactory::newBean('EmailAddresses');
	}


	function get_summary_text() {
        $this->_create_proper_name_field();
        return $this->name;
    }


	function fill_in_additional_list_fields() {
		$this->fill_in_additional_detail_fields();
	}

	/**
	 * @return -- returns a list of all employees in the system.
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	 */
	function verify_data()
	{
		//none of the checks from the users module are valid here since the user_name and
		//is_admin_on fields are not editable.
		return TRUE;
	}

	function get_list_view_data(){

        $user_fields = parent::get_list_view_data();

		// Copy over the reports_to_name
		if ( isset($GLOBALS['app_list_strings']['messenger_type_dom'][$this->messenger_type]) )
            $user_fields['MESSENGER_TYPE'] = $GLOBALS['app_list_strings']['messenger_type_dom'][$this->messenger_type];
		if ( isset($GLOBALS['app_list_strings']['employee_status_dom'][$this->employee_status]) )
            $user_fields['EMPLOYEE_STATUS'] = $GLOBALS['app_list_strings']['employee_status_dom'][$this->employee_status];
		$user_fields['REPORTS_TO_NAME'] = $this->reports_to_name;

        return $user_fields;
	}

    public function list_view_parse_additional_sections(&$list_form)
    {
		return $list_form;
	}

	/**
	 * When the user's reports to id is changed, this method is called.  This method needs to remove all
	 * of the implicit assignements that were created based on this user, then recreated all of the implicit
	 * assignments in the new location
	 */

	function update_team_memberships($old_reports_to_id)
	{

		$team = BeanFactory::newBean('Teams');
		$team->user_manager_changed($this->id, $old_reports_to_id, $this->reports_to_id);
	}

	function preprocess_fields_on_save(){
		parent::preprocess_fields_on_save();

		$upload_file = new UploadFile("picture");

		//remove file
		if (isset($_REQUEST['remove_imagefile_picture']) && $_REQUEST['remove_imagefile_picture'] == 1)
		{
			UploadFile::unlink_file($this->picture);
			$this->picture="";
		}

		//uploadfile
		if (isset($_FILES['picture']))
		{
			//confirm only image file type can be uploaded
			$imgType = array('image/gif', 'image/png', 'image/bmp', 'image/jpeg', 'image/jpg', 'image/pjpeg');
			if (in_array($_FILES['picture']["type"], $imgType))
			{
				if ($upload_file->confirm_upload())
				{
					$this->picture = create_guid();
					$upload_file->final_move($this->picture);
				}
			}
		}
	}


    /**
     * create_new_list_query
     *
     * Return the list query used by the list views and export button. Next generation of create_new_list_query function.
     *
     * We overrode this function in the Employees module to add the additional filter check so that we do not retrieve portal users for the Employees list view queries
     *
     * @param string $order_by custom order by clause
     * @param string $where custom where clause
     * @param array $filter Optioanal
     * @param array $params Optional     *
     * @param int $show_deleted Optional, default 0, show deleted records is set to 1.
     * @param string $join_type
     * @param boolean $return_array Optional, default false, response as array
     * @param object $parentbean creating a subquery for this bean.
     * @param boolean $singleSelect Optional, default false.
     * @param boolean $ifListForExport Optional, default false.
     * @return String select query string, optionally an array value will be returned if $return_array= true.
     */
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
        //create the filter for portal only users, as they should not be showing up in query results
        if(empty($where)){
            $where = ' users.portal_only = 0 ';
        }else{
            $where .= ' and users.portal_only = 0 ';
        }
        $where .= ' and users.show_on_employees = 1 ';
        //return parent method, specifying for array to be returned
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

    /*
     * Overwrite Sugar bean which returns the current objects custom fields.  Lets return User custom fields instead
     */
    function hasCustomFields()
    {

        //Check to see if there are custom user fields that we should report on, first check the custom_fields array
        $userCustomfields = !empty($GLOBALS['dictionary']['Employee']['custom_fields']);
        if(!$userCustomfields){
            //custom Fields not set, so traverse employee fields to see if any custom fields exist
            foreach ($GLOBALS['dictionary']['Employee']['fields'] as $k=>$v){
                if(!empty($v['source']) && $v['source'] == 'custom_fields'){
                    //custom field has been found, set flag to true and break
                    $userCustomfields = true;
                    break;
                }

            }
        }

        //return result of search for custom fields
        return $userCustomfields;
    }

    public function getPrivateTeamID()
    {
        return User::staticGetPrivateTeamID($this->id);
    }
}
