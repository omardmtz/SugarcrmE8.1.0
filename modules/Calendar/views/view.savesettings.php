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

class CalendarViewSaveSettings extends SugarView {
    /**
     * {@inheritDoc}
     *
     * @param array $params Ignored
     */
    public function process($params = array())
    {
		$this->display();
	}
	
	function display(){
		global $current_user;
		
		$db_start = $this->to_db_time($_REQUEST['day_start_hours'],$_REQUEST['day_start_minutes'],$_REQUEST['day_start_meridiem']);
		$db_end = $this->to_db_time($_REQUEST['day_end_hours'],$_REQUEST['day_end_minutes'],$_REQUEST['day_end_meridiem']);
		
		$current_user->setPreference('day_start_time', $db_start, 0, 'global', $current_user);
		$current_user->setPreference('day_end_time', $db_end, 0, 'global', $current_user);

		$current_user->setPreference('calendar_display_timeslots', $_REQUEST['display_timeslots'], 0, 'global', $current_user);
		$current_user->setPreference('show_tasks', $_REQUEST['show_tasks'], 0, 'global', $current_user);
		$current_user->setPreference('show_calls', $_REQUEST['show_calls'], 0, 'global', $current_user);

		if(isset($_REQUEST['day']) && !empty($_REQUEST['day']))
			header("Location: index.php?module=Calendar&action=index&view=".$_REQUEST['view']."&hour=0&day=".$_REQUEST['day']."&month=".$_REQUEST['month']."&year=".$_REQUEST['year']);
		else
			header("Location: index.php?module=Calendar&action=index");
	}
	
	private function to_db_time($hours,$minutes,$mer){
		$hours = intval($hours);
		$minutes = intval($minutes);
		$mer = strtolower($mer);
		if(!empty($mer)){
			if(($mer) == 'am')
				if($hours == 12)
					$hours = $hours - 12;
			if(($mer) == 'pm')
				if($hours != 12)
					$hours = $hours + 12;		
		}
		if($hours < 10)
			$hours = "0".$hours;
		if($minutes < 10)
			$minutes = "0".$minutes;	
		return $hours . ":". $minutes; 
	}
	

}

?>
