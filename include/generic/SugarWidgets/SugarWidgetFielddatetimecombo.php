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



class SugarWidgetFieldDateTimecombo extends SugarWidgetFieldDateTime {
	var $reporter;
	var $assigned_user=null;

    public function __construct(&$layout_manager)
    {
        parent::__construct($layout_manager);
        $this->reporter = $this->layout_manager->getAttribute('reporter');
    }
	//TODO:now for date time field , we just search from date start to date end. The time is from 00:00:00 to 23:59:59
	//If there is requirement, we can modify report.js::addFilterInputDatetimesBetween and this function
	function queryFilterBetween_Datetimes(& $layout_def) {
		global $timedate;
		if($this->getAssignedUser()) {
			$begin = $timedate->handle_offset($layout_def['input_name0'], $timedate->get_db_date_time_format(), false, $this->assigned_user);
			$end = $timedate->handle_offset($layout_def['input_name2'], $timedate->get_db_date_time_format(), false, $this->assigned_user);
		}
		else {
			$begin = $layout_def['input_name0'];
			$end = $layout_def['input_name1'];
		}
		return "(".$this->_get_column_select($layout_def).">=".$this->reporter->db->convert($this->reporter->db->quoted($begin), "datetime").
			" AND\n ".$this->_get_column_select($layout_def)."<=".$this->reporter->db->convert($this->reporter->db->quoted($end), "datetime").
			")\n";
	}

}
