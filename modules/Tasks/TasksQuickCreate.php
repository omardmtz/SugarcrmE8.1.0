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
 



class TasksQuickCreate extends QuickCreate {
    
    var $javascript;
    
    function process() {
        global $current_user, $timedate, $app_list_strings, $current_language, $mod_strings;
        $mod_strings = return_module_language($current_language, 'Tasks');
        
        parent::process();

        $this->ss->assign("PRIORITY_OPTIONS", get_select_options_with_id($app_list_strings['task_priority_dom'], $app_list_strings['task_priority_default']));
        $this->ss->assign("STATUS_OPTIONS", get_select_options_with_id($app_list_strings['task_status_dom'], $app_list_strings['task_status_default']));
		$this->ss->assign("TIME_FORMAT", '('. $timedate->get_user_time_format().')');
        
        $focus = BeanFactory::newBean('Tasks');
        $time_start_hour = intval(substr($focus->time_start, 0, 2));
	    $time_start_minutes = substr($focus->time_start, 3, 5);
		if($time_start_minutes > 45) {
		   $time_start_hour += 1;
		}

        $time_pref = $timedate->get_time_format();
		if (strpos($time_pref, 'a')) {
               if(!isset($focus->meridiem_am_values)) {
                  $focus->meridiem_am_values = array('am'=>'am', 'pm'=>'pm');
               } 		
               $this->ss->assign("TIME_MERIDIEM", get_select_options_with_id($focus->meridiem_am_values, $time_start_hour < 12 ? 'am' : 'pm'));               
		} else if(strpos($time_pref, 'A')) {
		       if(!isset($focus->meridiem_AM_values)) {
		          $focus->meridiem_AM_values = array('AM'=>'AM', 'PM'=>'PM');
		       }       
		       $this->ss->assign("TIME_MERIDIEM", get_select_options_with_id($focus->meridiem_AM_values, $time_start_hour < 12 ? 'AM' : 'PM'));
		} //if-else

		$this->ss->assign("USER_DATEFORMAT", '('. $timedate->get_user_date_format().')');
		$this->ss->assign("CALENDAR_DATEFORMAT", $timedate->get_cal_date_format());

        
        if($this->viaAJAX) { // override for ajax call
            $this->ss->assign('saveOnclick', "onclick='if(check_form(\"tasksQuickCreate\")) return SUGAR.subpanelUtils.inlineSave(this.form.id, \"activities\"); else return false;'");
            $this->ss->assign('cancelOnclick', "onclick='return SUGAR.subpanelUtils.cancelCreate(\"subpanel_activities\")';");
        }
        
        $this->ss->assign('viaAJAX', $this->viaAJAX);

        $this->javascript = new javascript();
        $this->javascript->setFormName('tasksQuickCreate');
        
        $focus = BeanFactory::newBean('Tasks');
        $this->javascript->setSugarBean($focus);
        $this->javascript->addAllFields('');

        $this->ss->assign('additionalScripts', $this->javascript->getScript(false));
    }   
}
?>
