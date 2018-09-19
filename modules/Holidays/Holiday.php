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
 * $Id$
 * Description:
 ********************************************************************************/





class Holiday extends SugarBean {

	var $id;
	var $deleted;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $created_by;
	var $name;
	var $holiday_date;
	var $description;
	var $person_id;
	var $person_type;
	var $related_module;
	var $related_module_id;

	var $table_name = 'holidays';
	var $object_name = 'Holiday';
	var $module_dir = 'Holidays';
	var $new_schema = true;


	public function __construct()
	{
		parent::__construct();
		$this->disable_row_level_security = true;
	}
	
	function get_summary_text()
	{
		return $this->name;
	}
}
