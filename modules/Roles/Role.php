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

 * Description:
 ********************************************************************************/








class Role extends SugarBean {

	var $id;
	var $deleted;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $created_by;
	var $name;
	var $description;
	var $modules;
	var $disable_row_level_security = true;

	var $table_name = 'roles';
	var $rel_module_table = 'roles_modules';
	var $object_name = 'Role';
	var $module_dir = 'Roles';
	var $new_schema = true;


	public function __construct()
	{
		parent::__construct();
	}
	
	function get_summary_text()
	{
		return $this->name;
	}

	function query_modules($allow = 1)
	{
		$query = "SELECT module_id FROM roles_modules WHERE ";
        $query .= "role_id = " . $this->db->quoted($this->id) . " AND allow = " .
            $this->db->quoted($allow) . " AND deleted=0";
		$result = $this->db->query($query);
		
		$return_array = array();
		
		while($row = $this->db->fetchByAssoc($result))
		{
			array_push($return_array, $row['module_id']);
		}
		
		return $return_array;
	}
	function set_module_relationship($role_id, &$mod_ids, $allow)
	{
		foreach($mod_ids as $mod_id)
		{
			if($mod_id != '')
				$this->set_relationship('roles_modules', array( 'module_id'=>$mod_id, 'role_id'=>$role_id, 'allow'=>$allow ));
		}
	}
	
	function clear_module_relationship($role_id)
	{
		$query = "DELETE FROM roles_modules WHERE role_id='$role_id'";
		$this->db->query($query);
	}

	function set_user_relationship($role_id, &$user_ids)
	{
		foreach($user_ids as $user_id)
		{
			if($user_id != '')
				$this->set_relationship('roles_users', array( 'user_id'=>$user_id, 'role_id'=>$role_id ));
		}
	}

	function clear_user_relationship($role_id, $user_id)
	{
		$query = "DELETE FROM roles_users WHERE role_id='$role_id' AND user_id='$user_id'";
		$this->db->query($query);
	}

	function query_user_allowed_modules($user_id)
	{
		$userArray = array();
		global $app_list_strings;
		
		
	
		$sql = "SELECT role_id FROM roles_users WHERE user_id='$user_id'";
		
		$result = $this->db->query($sql);
		
		while($row = $this->db->fetchByAssoc($result))
		{
			$role_id = $row["role_id"];
			$sql = "SELECT module_id FROM roles_modules WHERE role_id='$role_id' AND allow='1'";
			$res = $this->db->query($sql);
			
			while($col = $this->db->fetchByAssoc($res))
			{
				$key = $col['module_id'];
				if(!(array_key_exists($key, $userArray)))
				{
					$userArray[$key] = $app_list_strings['moduleList'][$key];
				}
			}
		}
	
		return $userArray;
	}
	
	function query_user_disallowed_modules($user_id, &$allowed)
	{
		global $moduleList;
		
		$returnArray = array();
		
		foreach($moduleList as $key=>$val)
		{
			if(array_key_exists($val, $allowed))
				continue;
			$returnArray[$val] = $val;
		}
		
		return $returnArray;

	}

	function get_users()
	{
		// First, get the list of IDs.

		
		
		$query = "SELECT user_id as id FROM roles_users WHERE role_id='$this->id' AND deleted=0";
		
		return $this->build_related_list($query, BeanFactory::newBean('Users'));
	}

	function check_user_role_count($user_id)
	{
		$query =  "SELECT count(*) AS num FROM roles_users WHERE ";
		$query .= "user_id='$user_id' AND deleted=0";
		$result = $this->db->query($query);
		
		$row = $this->db->fetchByAssoc($result);
		
		return $row['num'];
	}		
		
}
