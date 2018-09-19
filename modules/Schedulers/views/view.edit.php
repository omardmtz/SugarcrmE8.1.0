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


class SchedulersViewEdit extends ViewEdit {
	protected static $xtDays = array(
				1 => 'MON',
				2 => 'TUE',
				3 => 'WED',
				4 => 'THU',
				5 => 'FRI',
				6 => 'SAT',
				0 => 'SUN');

	public function __construct()
 	{
        parent::__construct();
 		$this->useForSubpanel = true;
 		//$this->useModuleQuickCreateTemplate = true;
 	}

    /**
     * {@inheritDoc}
     *
     * @param bool $browserTitle Ingored
     */
    protected function _getModuleTitleListParam($browserTitle = false)
	{
	    global $mod_strings;

    	return "<a href='index.php?module=Schedulers&action=index'>".$mod_strings['LBL_MODULE_TITLE']."</a>";
    }
    

    function display(){
		global $mod_strings;
		global $app_list_strings;

		// job functions
		$this->bean->job_function = $this->bean->job;
		$this->ss->assign('JOB', $this->bean->job);
		if(substr($this->bean->job, 0, 5) == "url::") {
			$this->bean->job_url = substr($this->bean->job, 5);
			$this->ss->assign('JOB', 'url::');
		}
		// interval
		if(!empty($this->bean->job_interval)) {
			$exInterval = explode("::", $this->bean->job_interval);
		} else {
			$exInterval = array('*','*','*','*','*');
		}
		$this->ss->assign('mins', $exInterval[0]);
		$this->ss->assign('hours', $exInterval[1]);
		$this->ss->assign('day_of_month', $exInterval[2]);
		$this->ss->assign('months', $exInterval[3]);
		$this->ss->assign('day_of_week', $exInterval[4]);

		// Handle cron weekdays
		if($exInterval[4] == '*') {
			$this->ss->assign('ALL', "CHECKED");
			foreach(self::$xtDays as $day) {
				$this->ss->assign($day, "CHECKED");
			}
		} elseif(strpos($exInterval[4], ',')) {
			// 1,2,4
			$exDays = explode(',', trim($exInterval[4]));
			foreach($exDays as $days) {
				if(strpos($days, '-')) {
					$exDaysRange = explode('-', $days);
					for($i=$exDaysRange[0]; $i<=$exDaysRange[1]; $i++) {
						$this->ss->assign(self::$xtDays[$days], "CHECKED");
					}
				} else {
					$this->ss->assign(self::$xtDays[$days], "CHECKED");
				}
			}
		} elseif(strpos($exInterval[4], '-')) {
			$exDaysRange = explode('-', $exInterval[4]);
			for($i=$exDaysRange[0]; $i<=$exDaysRange[1]; $i++) {
				$this->ss->assign(self::$xtDays[$i], "CHECKED");
			}
		} else {
			$this->ss->assign(self::$xtDays[$exInterval[4]], "CHECKED");
		}

		// Hours
		for($i=1; $i<=30; $i++) {
			$ints[$i] = $i;
		}
		$this->bean->adv_interval = false;
		$this->ss->assign('basic_intervals', $ints);
		$this->ss->assign('basic_periods', $app_list_strings['scheduler_period_dom']);
		if($exInterval[0] == '*' && $exInterval[1] == '*') {
		// hours
		} elseif(strpos($exInterval[1], '*/') !== false && $exInterval[0] == '0') {
		// we have a "BASIC" type of hour setting
			$exHours = explode('/', $exInterval[1]);
			$this->ss->assign('basic_interval', $exInterval[1]);
			$this->ss->assign('basic_period', 'hour');
		// Minutes
		} elseif(strpos($exInterval[0], '*/') !== false && $exInterval[1] == '*' ) {
			// we have a "BASIC" type of min setting
			$exMins = explode('/', $exInterval[0]);
			$this->ss->assign('basic_interval', $exMins[1]);
			$this->ss->assign('basic_period', 'min');
		// we've got an advanced time setting
		} else {
			$this->ss->assign('basic_interval', 12);
			$this->ss->assign('basic_period', 'hour');
			$this->bean->adv_interval = true;
		}
		if($this->bean->time_from || $this->bean->time_to) {
			$this->bean->adv_interval = true;
		}
	
		$this->ss->assign("adv_interval", $this->bean->adv_interval?"true":"false");
		$this->ss->assign("adv_visibility", $this->bean->adv_interval?"":"display: none");
		$this->ss->assign("basic_visibility", $this->bean->adv_interval?"display: none":"");
		
		parent::display();
	}
}
