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

/**
 * @param $focus
 * @param $field
 * @param $value
 * @param $view
 * @return string
 */
function getDurationMinutesOptions($focus, $field, $value, $view) {

    if (isset($_REQUEST['duration_minutes'])) {
        $focus->duration_minutes = $_REQUEST['duration_minutes'];
    }
    
	if (!isset($focus->duration_minutes)) {
		$focus->duration_minutes = $focus->minutes_value_default;
	}   
    
    global $timedate;
    //setting default date and time
	if (is_null($focus->date_start))
		$focus->date_start = $timedate->to_display_date(gmdate($timedate->get_date_time_format()));
	if (is_null($focus->duration_hours))
		$focus->duration_hours = "0";
	if (is_null($focus->duration_minutes))
		$focus->duration_minutes = "1";
	
    if($view == 'EditView' || $view == 'MassUpdate' || $view == "QuickCreate"

    || ($view == 'wirelessedit' && $focus->ACLFieldAccess($field, 'write'))
    ) {
       $html = '<select id="duration_minutes" ';
       if($view != 'MassUpdate' 

       		&& $view != 'wirelessedit'
       	 ) {
            $html .= 'onchange="SugarWidgetScheduler.update_time();" ';
       }

       $html .=  'name="duration_minutes">';
       $html .= get_select_options_with_id($focus->minutes_values, $focus->duration_minutes);
       $html .= '</select>';
       return $html;	
    }

    return $focus->duration_minutes;		
}

/**
 * @param $focus
 * @param $field
 * @param $value
 * @param $view
 * @return string
 *
 * @deprecated 6.5.0
 */
function getReminderTime($focus, $field, $value, $view) {

	global $current_user, $app_list_strings;
	$reminder_t = -1;
    
	if (!empty($_REQUEST['full_form']) && !empty($_REQUEST['reminder_time'])) {
		$reminder_t = $_REQUEST['reminder_time'];
	} else if (isset($focus->reminder_time)) {
		$reminder_t = $focus->reminder_time;
	} else if (isset($value)) {
        $reminder_t = $value;
    }

	if($view == 'EditView' || $view == 'MassUpdate' || $view == "SubpanelCreates" || $view == "QuickCreate"

    || $view == 'wirelessedit'
    ) {
		global $app_list_strings;
        $html = '<select id="reminder_time" name="reminder_time">';
        $html .= get_select_options_with_id($app_list_strings['reminder_time_options'], $reminder_t);
        $html .= '</select>';
        return $html;
    }
 
    if($reminder_t == -1) {
       return "";
    }
       
    return translate('reminder_time_options', '', $reminder_t);    
}

?>
