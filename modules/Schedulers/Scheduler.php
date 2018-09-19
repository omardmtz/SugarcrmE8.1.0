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

use Sugarcrm\Sugarcrm\Denormalization\TeamSecurity\Job\RebuildJob;

require_once 'install/install_utils.php';

class Scheduler extends SugarBean {
	// table columns
	var $id;
	var $deleted;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $created_by;
	var $created_by_name;
	var $modified_by_name;
	var $name;
	var $job;
	var $date_time_start;
	var $date_time_end;
	var $job_interval;
	var $time_from;
	var $time_to;
	var $last_run;
	var $status;
	var $catch_up;
	// object attributes
	var $user;
	var $intervalParsed;
	var $intervalHumanReadable;
	var $metricsVar;
	var $metricsVal;
	var $dayInt;
	var $dayLabel;
	var $monthsInt;
	var $monthsLabel;
	var $suffixArray;
	var $datesArray;
	var $scheduledJobs;
	var $timeOutMins = 60;
	// standard SugarBean attrs
	var $table_name				= "schedulers";
	var $object_name			= "Scheduler";
	var $module_dir				= "Schedulers";
	var $new_schema				= true;
	var $process_save_dates 	= true;
	var $order_by;
	public $disable_row_level_security = true;

	public static $job_strings;

    public function __construct($init=true)
    {
        parent:: __construct();
        $this->job_queue_table = BeanFactory::newBean('SchedulersJobs')->table_name;
    }

    protected function getUser()
    {
        if(empty($this->user)) {
            $this->user = Scheduler::initUser();
        }
        return $this->user;
    }

    /**
     * Function returns an Admin user for running Schedulers or false if no admin users are present in the system
     * (which means the Scheduler Jobs which need admin rights will fail to execute)
     */
    public static function initUser()
    {
        $user = BeanFactory::newBean('Users');
        $user->getSystemUser();
        if (empty($user->id)) {
            $GLOBALS['log']->fatal('No Admin account found!');
            return false;
        }
        return $user;
    }


	///////////////////////////////////////////////////////////////////////////
	////	SCHEDULER HELPER FUNCTIONS

	/**
	 * calculates if a job is qualified to run
	 */
	public function fireQualified()
	{
		if(empty($this->id)) { // execute only if we have an instance
			$GLOBALS['log']->fatal('Scheduler called fireQualified() in a non-instance');
			return false;
		}

		$now = TimeDate::getInstance()->getNow();
		$now = $now->setTime($now->hour, $now->min, "00")->asDb();
		$validTimes = $this->deriveDBDateTimes($this);

		if(is_array($validTimes) && in_array($now, $validTimes)) {
			$GLOBALS['log']->debug('----->Scheduler found valid job ('.$this->name.') for time GMT('.$now.')');
			return true;
		} else {
			$GLOBALS['log']->debug('----->Scheduler did NOT find valid job ('.$this->name.') for time GMT('.$now.')');
			return false;
		}
	}

	/**
	 * Create a job from this scheduler
	 * @return SchedulersJob
	 */
	public function createJob()
	{
	    $job = BeanFactory::newBean('SchedulersJobs');
	    $job->scheduler_id = $this->id;
        $job->name = $this->name;
        $job->execute_time = $GLOBALS['timedate']->nowDb();
        $job->assigned_user_id = $this->getUser()->id;
        $job->target = $this->job;
        return $job;
	}

	/**
	 * Checks if any jobs qualify to run at this moment
	 * @param SugarJobQueue $queue
	 */
	public function checkPendingJobs($queue)
	{
		$allSchedulers = $this->get_full_list('', "schedulers.status='Active' AND NOT EXISTS(SELECT id FROM {$this->job_queue_table} WHERE scheduler_id=schedulers.id AND status!='".SchedulersJob::JOB_STATUS_DONE."')");

		$GLOBALS['log']->info('-----> Scheduler found [ '.count($allSchedulers).' ] ACTIVE jobs');

		if(!empty($allSchedulers)) {
			foreach($allSchedulers as $focus) {
				if($focus->fireQualified()) {
				    $job = $focus->createJob();
				    $queue->submitJob($job, $this->getUser());
				}
			}
		} else {
			$GLOBALS['log']->debug('----->No Schedulers found');
		}
	}

	/**
	 * This function takes a Scheduler object and uses its job_interval
	 * attribute to derive DB-standard datetime strings, as many as are
	 * qualified by its ranges.  The times are from the time of calling the
	 * script.
	 *
	 * @param	$focus		Scheduler object
	 * @return	$dateTimes	array loaded with DB datetime strings derived from
	 * 						the	 job_interval attribute
	 * @return	false		If we the Scheduler is not in scope, return false.
	 */
	function deriveDBDateTimes($focus)
	{
        global $timedate;
		$GLOBALS['log']->debug('----->Schedulers->deriveDBDateTimes() got an object of type: '.$focus->object_name);
		/* [min][hr][dates][mon][days] */
		$dateTimes = array();
		$ints	= explode('::', str_replace(' ','',$focus->job_interval));
		$days	= $ints[4];
		$mons	= $ints[3];
		$dates	= $ints[2];
		$hrs	= $ints[1];
		$mins	= $ints[0];
        $now = $timedate->tzUser($timedate->getNow(), $this->getUser());

		// derive day part
		if($days == '*') {
			$GLOBALS['log']->debug('----->got * day');

		} elseif(strstr($days, '*/')) {
			// the "*/x" format is nonsensical for this field
			// do basically nothing.
			$theDay = str_replace('*/','',$days);
			$dayName[] = $theDay;
		} elseif($days != '*') { // got particular day(s)
			if(strstr($days, ',')) {
				$exDays = explode(',',$days);
				foreach($exDays as $k1 => $dayGroup) {
					if(strstr($dayGroup,'-')) {
						$exDayGroup = explode('-', $dayGroup); // build up range and iterate through
						for($i=$exDayGroup[0];$i<=$exDayGroup[1];$i++) {
							$dayName[] = $i;
						}
					} else { // individuals
						$dayName[] = $dayGroup;
					}
				}
			} elseif(strstr($days, '-')) {
				$exDayGroup = explode('-', $days); // build up range and iterate through
				for($i=$exDayGroup[0];$i<=$exDayGroup[1];$i++) {
					$dayName[] = $i;
				}
			} else {
				$dayName[] = $days;
			}

			// check the day to be in scope:
            if (!in_array($now->day_of_week, $dayName)) {
				return false;
			}
		} else {
			return false;
		}


		// derive months part
		if($mons == '*') {
			$GLOBALS['log']->debug('----->got * months');
		} elseif(strstr($mons, '*/')) {
			$mult = str_replace('*/','',$mons);
			$startMon = $timedate->fromDb(date_time_start)->month;
			$startFrom = ($startMon % $mult);

			for($i=$startFrom;$i<=12;$i+$mult) {
				$compMons[] = $i+$mult;
				$i += $mult;
			}
			// this month is not in one of the multiplier months
            if (!in_array($now->month, $compMons)) {
				return false;
			}
		} elseif($mons != '*') {
			if(strstr($mons,',')) { // we have particular (groups) of months
				$exMons = explode(',',$mons);
				foreach($exMons as $k1 => $monGroup) {
					if(strstr($monGroup, '-')) { // we have a range of months
						$exMonGroup = explode('-',$monGroup);
						for($i=$exMonGroup[0];$i<=$exMonGroup[1];$i++) {
							$monName[] = $i;
						}
					} else {
						$monName[] = $monGroup;
					}
				}
			} elseif(strstr($mons, '-')) {
				$exMonGroup = explode('-', $mons);
				for($i=$exMonGroup[0];$i<=$exMonGroup[1];$i++) {
					$monName[] = $i;
				}
			} else { // one particular month
				$monName[] = $mons;
			}

			// check that particular months are in scope
            if (!in_array($now->month, $monName)) {
				return false;
			}
		}

		// derive dates part
		if($dates == '*') {
			$GLOBALS['log']->debug('----->got * dates');
		} elseif(strstr($dates, '*/')) {
			$mult = str_replace('*/','',$dates);
			$startDate = $timedate->fromDb($focus->date_time_start)->day;
			$startFrom = ($startDate % $mult);

			for($i=$startFrom; $i<=31; $i+$mult) {
				$dateName[] = str_pad(($i+$mult),2,'0',STR_PAD_LEFT);
				$i += $mult;
			}

            if (!in_array($now->day, $dateName)) {
				return false;
			}
		} elseif($dates != '*') {
			if(strstr($dates, ',')) {
				$exDates = explode(',', $dates);
				foreach($exDates as $k1 => $dateGroup) {
					if(strstr($dateGroup, '-')) {
						$exDateGroup = explode('-', $dateGroup);
						for($i=$exDateGroup[0];$i<=$exDateGroup[1];$i++) {
							$dateName[] = $i;
						}
					} else {
						$dateName[] = $dateGroup;
					}
				}
			} elseif(strstr($dates, '-')) {
				$exDateGroup = explode('-', $dates);
				for($i=$exDateGroup[0];$i<=$exDateGroup[1];$i++) {
					$dateName[] = $i;
				}
			} else {
				$dateName[] = $dates;
			}

			// check that dates are in scope
            if (!in_array($now->day, $dateName)) {
				return false;
			}
		}

		// derive hours part
		//$currentHour = gmdate('G');
		//$currentHour = date('G', strtotime('00:00'));
		if($hrs == '*') {
			$GLOBALS['log']->debug('----->got * hours');
			for($i=0;$i<24; $i++) {
				$hrName[]=$i;
			}
		} elseif(strstr($hrs, '*/')) {
			$mult = str_replace('*/','',$hrs);
			for($i=0; $i<24; $i) { // weird, i know
				$hrName[]=$i;
				$i += $mult;
			}
		} elseif($hrs != '*') {
			if(strstr($hrs, ',')) {
				$exHrs = explode(',',$hrs);
				foreach($exHrs as $k1 => $hrGroup) {
					if(strstr($hrGroup, '-')) {
						$exHrGroup = explode('-', $hrGroup);
						for($i=$exHrGroup[0];$i<=$exHrGroup[1];$i++) {
							$hrName[] = $i;
						}
					} else {
						$hrName[] = $hrGroup;
					}
				}
			} elseif(strstr($hrs, '-')) {
				$exHrs = explode('-', $hrs);
				for($i=$exHrs[0];$i<=$exHrs[1];$i++) {
					$hrName[] = $i;
				}
			} else {
				$hrName[] = $hrs;
			}
		}
		//_pp($hrName);
		// derive minutes
		//$currentMin = date('i');
		$currentMin = $timedate->getNow()->format('i');
		if(substr($currentMin, 0, 1) == '0') {
			$currentMin = substr($currentMin, 1, 1);
		}
		if($mins == '*') {
			$GLOBALS['log']->debug('----->got * mins');
			for($i=0; $i<60; $i++) {
				if(($currentMin + $i) > 59) {
					$minName[] = ($i + $currentMin - 60);
				} else {
					$minName[] = ($i+$currentMin);
				}
			}
		} elseif(strstr($mins,'*/')) {
			$mult = str_replace('*/','',$mins);
			$startMin = $timedate->fromDb($focus->date_time_start)->format('i');
			$startFrom = $startMin - $startMin % $mult;
			for($i=0; $i<=59; $i) {
				if(($startFrom + $i) > 59) {
					$minName[] = ($i + $startFrom - 60);
				} else {
					$minName[] = ($i+$startFrom);
				}
				$i += $mult;
			}

		} elseif($mins != '*') {
			if(strstr($mins, ',')) {
				$exMins = explode(',',$mins);
				foreach($exMins as $k1 => $minGroup) {
					if(strstr($minGroup, '-')) {
						$exMinGroup = explode('-', $minGroup);
						for($i=$exMinGroup[0]; $i<=$exMinGroup[1]; $i++) {
							$minName[] = $i;
						}
					} else {
						$minName[] = $minGroup;
					}
				}
			} elseif(strstr($mins, '-')) {
				$exMinGroup = explode('-', $mins);
				for($i=$exMinGroup[0]; $i<=$exMinGroup[1]; $i++) {
					$minName[] = $i;
				}
			} else {
				$minName[] = $mins;
			}
		}
		//_pp($minName);
		// prep some boundaries - these are not in GMT b/c gmt is a 24hour period, possibly bridging 2 local days
		if(empty($focus->time_from)  && empty($focus->time_to) ) {
			$timeFromTs = 0;
			$timeToTs = $timedate->getNow(true)->get('+1 day')->ts;
		} else {
		    $tfrom = $timedate->fromDbType($focus->time_from, 'time');
			$timeFromTs = $timedate->getNow(true)->setTime($tfrom->hour, $tfrom->min)->ts;
		    $tto = $timedate->fromDbType($focus->time_to, 'time');
			$timeToTs = $timedate->getNow(true)->setTime($tto->hour, $tto->min)->ts;
		}
		$timeToTs++;

		if(empty($focus->last_run)) {
			$lastRunTs = 0;
		} else {
			$lastRunTs = $timedate->fromDb($focus->last_run)->ts;
		}


		/**
		 * initialize return array
		 */
		$validJobTime = array();

		global $timedate;
		$timeStartTs = $timedate->fromDb($focus->date_time_start)->ts;
		if(!empty($focus->date_time_end)) { // do the same for date_time_end if not empty
			$timeEndTs = $timedate->fromDb($focus->date_time_end)->ts;
		} else {
			$timeEndTs = $timedate->getNow(true)->get('+1 day')->ts;
//			$dateTimeEnd = '2020-12-31 23:59:59'; // if empty, set it to something ridiculous
		}
		$timeEndTs++;
		/*_pp('hours:'); _pp($hrName);_pp('mins:'); _pp($minName);*/
		$dateobj = $timedate->getNow();
		$nowTs = $dateobj->ts;
        $GLOBALS['log']->debug(sprintf("Constraints: start: %s from: %s end: %s to: %s now: %s",
            gmdate('Y-m-d H:i:s', $timeStartTs), gmdate('Y-m-d H:i:s', $timeFromTs), gmdate('Y-m-d H:i:s', $timeEndTs),
            gmdate('Y-m-d H:i:s', $timeToTs), $timedate->nowDb()
            ));
//		_pp('currentHour: '. $currentHour);
//		_pp('timeStartTs: '.date('r',$timeStartTs));
//		_pp('timeFromTs: '.date('r',$timeFromTs));
//		_pp('timeEndTs: '.date('r',$timeEndTs));
//		_pp('timeToTs: '.date('r',$timeToTs));
//		_pp('mktime: '.date('r',mktime()));
//		_pp('timeLastRun: '.date('r',$lastRunTs));
//
//		_pp('hours: ');
//		_pp($hrName);
//		_pp('mins: ');
//		_ppd($minName);
		foreach($hrName as $kHr=>$hr) {
			foreach($minName as $kMin=>$min) {
			    $timedate->tzUser($dateobj);
		        $dateobj->setTime($hr, $min, 0);
		        $tsGmt = $dateobj->ts;

				if( $tsGmt >= $timeStartTs ) { // start is greater than the date specified by admin
					if( $tsGmt >= $timeFromTs ) { // start is greater than the time_to spec'd by admin
                        if($tsGmt > $lastRunTs) { // start from last run, last run should not be included
                            if( $tsGmt <= $timeEndTs ) { // this is taken care of by the initial query - start is less than the date spec'd by admin
                                if( $tsGmt <= $timeToTs ) { // start is less than the time_to
                                    $validJobTime[] = $dateobj->asDb();
                                } else {
                                    //_pp('Job Time is NOT smaller that TimeTO: '.$tsGmt .'<='. $timeToTs);
                                }
                            } else {
                                //_pp('Job Time is NOT smaller that DateTimeEnd: '.date('Y-m-d H:i:s',$tsGmt) .'<='. $dateTimeEnd); //_pp( $tsGmt .'<='. $timeEndTs );
                            }
                        }
					} else {
						//_pp('Job Time is NOT bigger that TimeFrom: '.$tsGmt .'>='. $timeFromTs);
					}
				} else {
					//_pp('Job Time is NOT Bigger than DateTimeStart: '.date('Y-m-d H:i',$tsGmt) .'>='. $dateTimeStart);
				}
			}
		}
		//_ppd($validJobTime);
		// need ascending order to compare oldest time to last_run
		sort($validJobTime);
		/**
		 * If "Execute If Missed bit is set
		 */
        $now = TimeDate::getInstance()->getNow();
		$now = $now->setTime($now->hour, $now->min, "00")->asDb();

		if($focus->catch_up == 1) {
			if($focus->last_run == null) {
				// always "catch-up"
				$validJobTime[] = $now;
			} else {
				// determine what the interval in min/hours is
				// see if last_run is in it
				// if not, add NOW
                if(!empty($validJobTime) && ($focus->last_run < $validJobTime[0]) && ($now > $validJobTime[0])) {
				// cn: empty() bug 5914;
				// if(!empty) should be checked, becasue if a scheduler is defined to run every day 4pm, then after 4pm, and it runs as 4pm,
				// the $validJobTime will be empty, and it should not catch up.
				// If $focus->last_run is the the day before yesterday,  it should run yesterday and tomorrow,
				// but it hadn't run yesterday, then it should catch up today.
				// But today is already filtered out when doing date check before. The catch up will not work on this occasion.
				// If the scheduler runs at least one time on each day, I think this bug can be avoided.
					$validJobTime[] = $now;
				}
			}
		}
		return $validJobTime;
	}

	function handleIntervalType($type, $value, $mins, $hours) {
		global $mod_strings;
		/* [0]:min [1]:hour [2]:day of month [3]:month [4]:day of week */
		$days = array (	1 => $mod_strings['LBL_MON'],
						2 => $mod_strings['LBL_TUE'],
						3 => $mod_strings['LBL_WED'],
						4 => $mod_strings['LBL_THU'],
						5 => $mod_strings['LBL_FRI'],
						6 => $mod_strings['LBL_SAT'],
						0 => $mod_strings['LBL_SUN'],
						'*' => $mod_strings['LBL_ALL']);
		switch($type) {
			case 0: // minutes
				if($value == '0') {
					//return;
					return trim($mod_strings['LBL_ON_THE']).$mod_strings['LBL_HOUR_SING'];
				} elseif(!preg_match('/[^0-9]/', $hours) && !preg_match('/[^0-9]/', $value)) {
					return;

				} elseif(preg_match('/\*\//', $value)) {
					$value = str_replace('*/','',$value);
					return $value.$mod_strings['LBL_MINUTES'];
				} elseif(!preg_match('[^0-9]', $value)) {
					return $mod_strings['LBL_ON_THE'].$value.$mod_strings['LBL_MIN_MARK'];
				} else {
					return $value;
				}
			case 1: // hours
				global $current_user;
				if(preg_match('/\*\//', $value)) { // every [SOME INTERVAL] hours
					$value = str_replace('*/','',$value);
					return $value.$mod_strings['LBL_HOUR'];
				} elseif(preg_match('/[^0-9]/', $mins)) { // got a range, or multiple of mins, so we return an 'Hours' label
					return $value;
				} else {	// got a "minutes" setting, so it will be at some o'clock.
					$datef = $current_user->getUserDateTimePreferences();
					return date($datef['time'], strtotime($value.':'.str_pad($mins, 2, '0', STR_PAD_LEFT)));
				}
			case 2: // day of month
				if(preg_match('/\*/', $value)) {
					return $value;
				} else {
					return date('jS', strtotime('December '.$value));
				}

			case 3: // months
				return date('F', strtotime('2005-'.$value.'-01'));
			case 4: // days of week
				return $days[$value];
			default:
				return 'bad'; // no condition to touch this branch
		}
	}

	function setIntervalHumanReadable() {
		global $current_user;
		global $mod_strings;

		/* [0]:min [1]:hour [2]:day of month [3]:month [4]:day of week */
		$ints = $this->intervalParsed;
		$intVal = array('-', ',');
		$intSub = array($mod_strings['LBL_RANGE'], $mod_strings['LBL_AND']);
		$intInt = array(0 => $mod_strings['LBL_MINS'], 1 => $mod_strings['LBL_HOUR']);
		$tempInt = '';
		$iteration = '';

		foreach($ints['raw'] as $key => $interval) {
			if($tempInt != $iteration) {
				$tempInt .= '; ';
			}
			$iteration = $tempInt;

			if($interval != '*' && $interval != '*/1') {
				if(false !== strpos($interval, ',')) {
					$exIndiv = explode(',', $interval);
					foreach($exIndiv as $val) {
						if(false !== strpos($val, '-')) {
							$exRange = explode('-', $val);
							foreach($exRange as $valRange) {
								if($tempInt != '') {
									$tempInt .= $mod_strings['LBL_AND'];
								}
								$tempInt .= $this->handleIntervalType($key, $valRange, $ints['raw'][0], $ints['raw'][1]);
							}
						} elseif($tempInt != $iteration) {
							$tempInt .= $mod_strings['LBL_AND'];
						}
						$tempInt .= $this->handleIntervalType($key, $val, $ints['raw'][0], $ints['raw'][1]);
					}
				} elseif(false !== strpos($interval, '-')) {
					$exRange = explode('-', $interval);
					$tempInt .= $mod_strings['LBL_FROM'];
					$check = $tempInt;

					foreach($exRange as $val) {
						if($tempInt == $check) {
							$tempInt .= $this->handleIntervalType($key, $val, $ints['raw'][0], $ints['raw'][1]);
							$tempInt .= $mod_strings['LBL_RANGE'];

						} else {
							$tempInt .= $this->handleIntervalType($key, $val, $ints['raw'][0], $ints['raw'][1]);
						}
					}

				} elseif(false !== strpos($interval, '*/')) {
					$tempInt .= $mod_strings['LBL_EVERY'];
					$tempInt .= $this->handleIntervalType($key, $interval, $ints['raw'][0], $ints['raw'][1]);
				} else {
					$tempInt .= $this->handleIntervalType($key, $interval, $ints['raw'][0], $ints['raw'][1]);
				}
			}
		} // end foreach()

		if($tempInt == '') {
			$this->intervalHumanReadable = $mod_strings['LBL_OFTEN'];
		} else {
			$tempInt = trim($tempInt);
			if(';' == substr($tempInt, (strlen($tempInt)-1), strlen($tempInt))) {
				$tempInt = substr($tempInt, 0, (strlen($tempInt)-1));
			}
			$this->intervalHumanReadable = $tempInt;
		}
	}


	/* take an integer and return its suffix */
	function setStandardArraysAttributes() {
		global $mod_strings;
		global $app_list_strings; // using from month _dom list

		$suffArr = array('','st','nd','rd');
		for($i=1; $i<32; $i++) {
			if($i > 3 && $i < 21) {
				$this->suffixArray[$i] = $i."th";
			} elseif (substr($i,-1,1) < 4 && substr($i,-1,1) > 0) {
				$this->suffixArray[$i] = $i.$suffArr[substr($i,-1,1)];
			} else {
				$this->suffixArray[$i] = $i."th";
			}
			$this->datesArray[$i] = $i;
		}

		$this->dayInt = array('*',1,2,3,4,5,6,0);
		$this->dayLabel = array('*',$mod_strings['LBL_MON'],$mod_strings['LBL_TUE'],$mod_strings['LBL_WED'],$mod_strings['LBL_THU'],$mod_strings['LBL_FRI'],$mod_strings['LBL_SAT'],$mod_strings['LBL_SUN']);
		$this->monthsInt = array(0,1,2,3,4,5,6,7,8,9,10,11,12);
		$this->monthsLabel = $app_list_strings['dom_cal_month_long'];
		$this->metricsVar = array("*", "/", "-", ",");
		$this->metricsVal = array(' every ','',' thru ',' and ');
	}

	/**
	 *  takes the serialized interval string and renders it into an array
	 */
	function parseInterval() {
		global $metricsVar;
		$ws = array(' ', '\r','\t');
		$blanks = array('','','');

		$intv = $this->job_interval;
		$rawValues = explode('::', $intv);
		$rawProcessed = str_replace($ws,$blanks,$rawValues); // strip all whitespace

		$hours = $rawValues[1].':::'.$rawValues[0];
		$months = $rawValues[3].':::'.$rawValues[2];

		$intA = array (	'raw' => $rawProcessed,
						'hours' => $hours,
						'months' => $months,
						);

		$this->intervalParsed = $intA;
	}

	/**
	 * checks for cURL libraries
	 */
	function checkCurl() {
		global $mod_strings;

		if(!function_exists('curl_init')) {
			echo '
			<table cellpadding="0" cellspacing="0" width="100%" border="0" class="list view">
				<tr height="20">
					<th width="25%" colspan="2"><slot>
						'.$mod_strings['LBL_WARN_CURL_TITLE'].'
					</slot></td>
				</tr>
				<tr class="oddListRowS1" >
					<td scope="row" valign=TOP width="20%"><slot>
						'.$mod_strings['LBL_WARN_CURL'].'
					<td scope="row" valign=TOP width="80%"><slot>
						<span class=error>'.$mod_strings['LBL_WARN_NO_CURL'].'</span>
					</slot></td>
				</tr>
			</table>
			<br>';
		}
	}

	function displayCronInstructions() {
		global $mod_strings;
		global $sugar_config;
		$error = '';
		if (!isset($_SERVER['Path'])) {
            $_SERVER['Path'] = getenv('Path');
        }
        if(is_windows()) {
			if(isset($_SERVER['Path']) && !empty($_SERVER['Path'])) { // IIS IUSR_xxx may not have access to Path or it is not set
				if(!strpos($_SERVER['Path'], 'php')) {
//					$error = '<em>'.$mod_strings['LBL_NO_PHP_CLI'].'</em>';
				}
			}
		} else {
			if(isset($_SERVER['Path']) && !empty($_SERVER['Path'])) { // some Linux servers do not make this available
				if(!strpos($_SERVER['PATH'], 'php')) {
//					$error = '<em>'.$mod_strings['LBL_NO_PHP_CLI'].'</em>';
				}
			}
		}



		if(is_windows()) {
			echo '<br>';
			echo '
				<table cellpadding="0" cellspacing="0" width="100%" border="0" class="list view">
				<tr height="20">
					<th><slot>
						'.$mod_strings['LBL_CRON_INSTRUCTIONS_WINDOWS'].'
					</slot></th>
				</tr>
				<tr class="evenListRowS1">
					<td scope="row" valign="top" width="70%"><slot>
						'.$mod_strings['LBL_CRON_WINDOWS_DESC'].'<br>
						<b>cd '.realpath('./').'<br>
						php.exe -f cron.php</b>
					</slot></td>
				</tr>
			</table>';
		} else {
			echo '<br>';
			echo '
				<table cellpadding="0" cellspacing="0" width="100%" border="0" class="list view">
				<tr height="20">
					<th><slot>
						'.$mod_strings['LBL_CRON_INSTRUCTIONS_LINUX'].'
					</slot></th>
				</tr>
				<tr>
					<td scope="row" valign=TOP class="oddListRowS1" bgcolor="#fdfdfd" width="70%"><slot>
						'.$mod_strings['LBL_CRON_LINUX_DESC'].'<br>
						<b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;
						cd '.realpath('./').'; php -f cron.php > /dev/null 2>&1</b>
						<br>'.$error.'
					</slot></td>
				</tr>
			</table>';
		}
	}

	/**
	 * Archives schedulers of the same functionality, then instantiates new
	 * ones.
	 */
    function rebuildDefaultSchedulers()
    {
        // truncate scheduler-related tables
        $this->db->query('DELETE FROM schedulers');

        $schedulers = $this->getDefaultSystemSchedulers();

        foreach ($schedulers as $scheduler) {
            $scheduler->save();
        }
    }

    /**
     * Return OOTB Schedulers.
     * @return array
     */
    public function getDefaultSystemSchedulers()
    {
        $schedulers = array();
        $mod_strings = return_module_language($GLOBALS['current_language'], 'Schedulers');

        /** @var Scheduler $scheduler */
        $scheduler = BeanFactory::newBean('Schedulers');
        $scheduler->name = $mod_strings['LBL_OOTB_WORKFLOW'];
        $scheduler->job = 'function::processWorkflow';
        $scheduler->date_time_start = create_date(2005, 1, 1) . ' ' . create_time(0, 0, 1);
        $scheduler->date_time_end = create_date(2020, 12, 31) . ' ' . create_time(23, 59, 59);
        $scheduler->job_interval = '*::*::*::*::*';
        $scheduler->status = 'Active';
        $scheduler->created_by = '1';
        $scheduler->modified_user_id = '1';
        $scheduler->catch_up = '0';
        $schedulers[$scheduler->job] = $scheduler;

        $scheduler = BeanFactory::newBean('Schedulers');
        $scheduler->name = $mod_strings['LBL_OOTB_REPORTS'];
        $scheduler->job = 'function::processQueue';
        $scheduler->date_time_start = create_date(2005, 1, 1) . ' ' . create_time(0, 0, 1);
        $scheduler->date_time_end = create_date(2020, 12, 31) . ' ' . create_time(23, 59, 59);
        $scheduler->job_interval = '0::6::*::*::*';
        $scheduler->status = 'Inactive';
        $scheduler->created_by = '1';
        $scheduler->modified_user_id = '1';
        $scheduler->catch_up = '1';
        $schedulers[$scheduler->job] = $scheduler;

        $scheduler = BeanFactory::newBean('Schedulers');
        $scheduler->name = $mod_strings['LBL_OOTB_TRACKER'];
        $scheduler->job = 'function::trimTracker';
        $scheduler->date_time_start = create_date(2005, 1, 1) . ' ' . create_time(0, 0, 1);
        $scheduler->date_time_end = create_date(2020, 12, 31) . ' ' . create_time(23, 59, 59);
        $scheduler->job_interval = '0::2::1::*::*';
        $scheduler->status = 'Active';
        $scheduler->created_by = '1';
        $scheduler->modified_user_id = '1';
        $scheduler->catch_up = '1';
        $schedulers[$scheduler->job] = $scheduler;

        $scheduler = BeanFactory::newBean('Schedulers');
        $scheduler->name = $mod_strings['LBL_OOTB_IE'];
        $scheduler->job = 'function::pollMonitoredInboxes';
        $scheduler->date_time_start = create_date(2005, 1, 1) . ' ' . create_time(0, 0, 1);
        $scheduler->date_time_end = create_date(2020, 12, 31) . ' ' . create_time(23, 59, 59);
        $scheduler->job_interval = '*::*::*::*::*';
        $scheduler->status = 'Active';
        $scheduler->created_by = '1';
        $scheduler->modified_user_id = '1';
        $scheduler->catch_up = '0';
        $schedulers[$scheduler->job] = $scheduler;

        $scheduler = BeanFactory::newBean('Schedulers');
        $scheduler->name = $mod_strings['LBL_OOTB_BOUNCE'];
        $scheduler->job = 'function::pollMonitoredInboxesForBouncedCampaignEmails';
        $scheduler->date_time_start = create_date(2005, 1, 1) . ' ' . create_time(0, 0, 1);
        $scheduler->date_time_end = create_date(2020, 12, 31) . ' ' . create_time(23, 59, 59);
        $scheduler->job_interval = '0::2-6::*::*::*';
        $scheduler->status = 'Active';
        $scheduler->created_by = '1';
        $scheduler->modified_user_id = '1';
        $scheduler->catch_up = '1';
        $schedulers[$scheduler->job] = $scheduler;

        $scheduler = BeanFactory::newBean('Schedulers');
        $scheduler->name = $mod_strings['LBL_OOTB_CAMPAIGN'];
        $scheduler->job = 'function::runMassEmailCampaign';
        $scheduler->date_time_start = create_date(2005, 1, 1) . ' ' . create_time(0, 0, 1);
        $scheduler->date_time_end = create_date(2020, 12, 31) . ' ' . create_time(23, 59, 59);
        $scheduler->job_interval = '0::2-6::*::*::*';
        $scheduler->status = 'Active';
        $scheduler->created_by = '1';
        $scheduler->modified_user_id = '1';
        $scheduler->catch_up = '1';
        $schedulers[$scheduler->job] = $scheduler;

        $scheduler = BeanFactory::newBean('Schedulers');
        $scheduler->name = $mod_strings['LBL_OOTB_PRUNE'];
        $scheduler->job = 'function::pruneDatabase';
        $scheduler->date_time_start = create_date(2005, 1, 1) . ' ' . create_time(0, 0, 1);
        $scheduler->date_time_end = create_date(2020, 12, 31) . ' ' . create_time(23, 59, 59);
        $scheduler->job_interval = '0::4::1::*::*';
        $scheduler->status = 'Inactive';
        $scheduler->created_by = '1';
        $scheduler->modified_user_id = '1';
        $scheduler->catch_up = '0';
        $schedulers[$scheduler->job] = $scheduler;

        $scheduler = BeanFactory::newBean('Schedulers');
        $scheduler->name = $mod_strings['LBL_UPDATE_TRACKER_SESSIONS'];
        $scheduler->job = 'function::updateTrackerSessions';
        $scheduler->date_time_start = create_date(2005, 1, 1) . ' ' . create_time(0, 0, 1);
        $scheduler->date_time_end = create_date(2020, 12, 31) . ' ' . create_time(23, 59, 59);
        $scheduler->job_interval = '*::*::*::*::*';
        $scheduler->status = 'Active';
        $scheduler->created_by = '1';
        $scheduler->modified_user_id = '1';
        $scheduler->catch_up = '1';
        $schedulers[$scheduler->job] = $scheduler;

        $scheduler = BeanFactory::newBean('Schedulers');
        $scheduler->name = $mod_strings['LBL_OOTB_SEND_EMAIL_REMINDERS'];
        $scheduler->job = 'function::sendEmailReminders';
        $scheduler->date_time_start = create_date(2008, 1, 1) . ' ' . create_time(0, 0, 1);
        $scheduler->date_time_end = create_date(2020, 12, 31) . ' ' . create_time(23, 59, 59);
        $scheduler->job_interval = '*::*::*::*::*';
        $scheduler->status = 'Active';
        $scheduler->created_by = '1';
        $scheduler->modified_user_id = '1';
        $scheduler->catch_up = '0';
        $schedulers[$scheduler->job] = $scheduler;

        $scheduler = BeanFactory::newBean('Schedulers');
        $scheduler->name = $mod_strings['LBL_OOTB_CLEANUP_QUEUE'];
        $scheduler->job = 'function::cleanJobQueue';
        $scheduler->date_time_start = create_date(2012, 1, 1) . ' ' . create_time(0, 0, 1);
        $scheduler->date_time_end = create_date(2030, 12, 31) . ' ' . create_time(23, 59, 59);
        $scheduler->job_interval = '0::5::*::*::*';
        $scheduler->status = 'Active';
        $scheduler->created_by = '1';
        $scheduler->modified_user_id = '1';
        $scheduler->catch_up = '0';
        $schedulers[$scheduler->job] = $scheduler;

        $scheduler = new Scheduler();
        $scheduler->name = $mod_strings['LBL_OOTB_CREATE_NEXT_TIMEPERIOD'];
        $scheduler->job = 'class::SugarJobCreateNextTimePeriod';
        $scheduler->date_time_start = create_date(2012, 1, 1) . ' ' . create_time(0, 0, 1);
        $scheduler->date_time_end = create_date(2030, 12, 31) . ' ' . create_time(23, 59, 59);
        $scheduler->job_interval = '0::23::*::*::*';
        $scheduler->status = 'Active';
        $scheduler->created_by = '1';
        $scheduler->modified_user_id = '1';
        $scheduler->catch_up = '0';
        $schedulers[$scheduler->job] = $scheduler;

        $scheduler = BeanFactory::newBean('Schedulers');
        $scheduler->name = $mod_strings['LBL_OOTB_PRUNE_RECORDLISTS'];
        $scheduler->job = 'function::cleanOldRecordLists';
        $scheduler->date_time_start = create_date(2005, 1, 1) . ' ' . create_time(0, 0, 1);
        $scheduler->date_time_end = create_date(2020, 12, 31) . ' ' . create_time(23, 59, 59);
        $scheduler->job_interval = '*::*::*::*::*';
        $scheduler->status = 'Active';
        $scheduler->created_by = '1';
        $scheduler->modified_user_id = '1';
        $scheduler->catch_up = '1';
        $schedulers[$scheduler->job] = $scheduler;

        // Sugar heartbeat
        $scheduler = BeanFactory::newBean('Schedulers');
        $scheduler->name = $mod_strings['LBL_OOTB_HEARTBEAT'];
        $scheduler->job = 'class::SugarJobHeartbeat';
        $scheduler->date_time_start = create_date(2005, 1, 1) . ' ' . create_time(0, 0, 1);
        $scheduler->date_time_end = create_date(2030, 12, 31) . ' ' . create_time(23, 59, 59);
        $scheduler->job_interval = '0::4::*::*::*';
        $scheduler->status = 'Active';
        $scheduler->created_by = '1';
        $scheduler->modified_user_id = '1';
        $scheduler->catch_up = '0';
        $schedulers[$scheduler->job] = $scheduler;

        // Remove temporary uploaded files
        $scheduler = BeanFactory::newBean('Schedulers');
        $scheduler->name = $mod_strings['LBL_OOTB_REMOVE_TMP_FILES'];
        $scheduler->job = 'class::SugarJobRemoveTmpFiles';
        $scheduler->date_time_start = create_date(2005, 1, 1) . ' ' . create_time(0, 0, 1);
        $scheduler->date_time_end = create_date(2030, 12, 31) . ' ' . create_time(23, 59, 59);
        $scheduler->job_interval = '0::4::*::*::*';
        $scheduler->status = 'Active';
        $scheduler->created_by = '1';
        $scheduler->modified_user_id = '1';
        $scheduler->catch_up = '1';
        $schedulers[$scheduler->job] = $scheduler;

        // Remove diagnostic tool files
        $scheduler = BeanFactory::newBean('Schedulers');
        $scheduler->name = $mod_strings['LBL_OOTB_REMOVE_DIAGNOSTIC_FILES'];
        $scheduler->job = 'class::SugarJobRemoveDiagnosticFiles';
        $scheduler->date_time_start = create_date(2005, 1, 1) . ' ' . create_time(0, 0, 1);
        $scheduler->date_time_end = create_date(2030, 12, 31) . ' ' . create_time(23, 59, 59);
        $scheduler->job_interval = '0::4::*::*::0';
        $scheduler->status = 'Active';
        $scheduler->created_by = '1';
        $scheduler->modified_user_id = '1';
        $scheduler->catch_up = '1';
        $schedulers[$scheduler->job] = $scheduler;

        // Remove temporary PDF files
        $scheduler = BeanFactory::newBean('Schedulers');
        $scheduler->name = $mod_strings['LBL_OOTB_REMOVE_PDF_FILES'];
        $scheduler->job = 'class::SugarJobRemovePdfFiles';
        $scheduler->date_time_start = create_date(2005, 1, 1) . ' ' . create_time(0, 0, 1);
        $scheduler->date_time_end = create_date(2030, 12, 31) . ' ' . create_time(23, 59, 59);
        $scheduler->job_interval = '0::4::*::*::*';
        $scheduler->status = 'Active';
        $scheduler->created_by = '1';
        $scheduler->modified_user_id = '1';
        $scheduler->catch_up = '1';
        $schedulers[$scheduler->job] = $scheduler;

        // Advanced Workflow OOTB Job
        $scheduler = BeanFactory::newBean('Schedulers');
        $scheduler->name = $mod_strings['LBL_OOTB_PROCESS_AUTHOR_JOB'];
        $scheduler->job = 'function::PMSEEngineCron';
        $scheduler->date_time_start = create_date(2015, 1, 1) . ' ' . create_time(0, 0, 1);
        $scheduler->date_time_end = create_date(2030, 12, 31)
            . ' ' . create_time(23, 59, 59);
        $scheduler->job_interval = '*::*::*::*::*';;
        $scheduler->status = 'Active';
        $scheduler->created_by = '1';
        $scheduler->modified_user_id = '1';
        $scheduler->catch_up = '1';
        $schedulers[$scheduler->job] = $scheduler;

        // Update expired KB Articles
        $scheduler = BeanFactory::newBean('Schedulers');
        $scheduler->name               = $mod_strings['LBL_OOTB_KBSCONTENT_EXPIRE'];
        $scheduler->job                = 'class::SugarJobKBContentUpdateArticles';
        $scheduler->date_time_start    = create_date(2005, 1, 1) . ' ' . create_time(0, 0, 1);
        $scheduler->date_time_end      = create_date(2030, 12, 31) . ' ' . create_time(23, 59, 59);
        $scheduler->job_interval       = '0::5::*::*::*';
        $scheduler->status             = 'Active';
        $scheduler->created_by         = '1';
        $scheduler->modified_user_id   = '1';
        $scheduler->catch_up           = '1';
        $schedulers[$scheduler->job] = $scheduler;

        //Handle the rebuild of Team Security denormalized table
        $scheduler = BeanFactory::newBean('Schedulers');
        $scheduler->name = $mod_strings['LBL_OOTB_TEAM_SECURITY_DENORM_REBUILD'];
        $scheduler->job = 'class::' . RebuildJob::class;
        $scheduler->date_time_start = create_date(2005, 1, 1) . ' ' . create_time(0, 0, 1);
        $scheduler->date_time_end = create_date(2030, 12, 31) . ' ' . create_time(23, 59, 59);
        $scheduler->job_interval = '*/15::*::*::*::*';
        $scheduler->status = 'Inactive';
        $scheduler->created_by = '1';
        $scheduler->modified_user_id = '1';
        $scheduler->catch_up = '0';
        $schedulers[$scheduler->job] = $scheduler;

        return $schedulers;
    }

	////	END SCHEDULER HELPER FUNCTIONS
	///////////////////////////////////////////////////////////////////////////


	///////////////////////////////////////////////////////////////////////////
	////	STANDARD SUGARBEAN OVERRIDES
	/**
	 * function overrides the one in SugarBean.php
	 */
	function fill_in_additional_list_fields() {
		$this->fill_in_additional_detail_fields();
	}

	/**
	 * function overrides the one in SugarBean.php
	 */
	function get_list_view_data()
	{
		global $mod_strings;
		$temp_array = $this->get_list_view_array();
        $temp_array["ENCODED_NAME"]=$this->name;
        $this->parseInterval();
        $this->setIntervalHumanReadable();
        $temp_array['JOB_INTERVAL'] = $this->intervalHumanReadable;
        if($this->date_time_end == '2020-12-31 23:59' || $this->date_time_end == '') {
        	$temp_array['DATE_TIME_END'] = $mod_strings['LBL_PERENNIAL'];
        }
    	return $temp_array;

	}

	/**
	 * returns the bean name - overrides SugarBean's
	 */
	function get_summary_text()
	{
		return $this->name;
	}
	////	END STANDARD SUGARBEAN OVERRIDES
	///////////////////////////////////////////////////////////////////////////
	static public function getJobsList()
	{
		if(empty(self::$job_strings)) {
			global $mod_strings;
			include_once('modules/Schedulers/_AddJobsHere.php');

            // job functions
            self::$job_strings = array('url::' => 'URL');

            foreach($job_strings as $v) {
                if(preg_match('/^class\:\:(.+)$/', $v, $match)) {
                    self::$job_strings[$v] = $mod_strings['LBL_' . strtoupper(str_replace('\\', '_', $match[1]))];
                } else {
                    self::$job_strings['function::' . $v] = $mod_strings['LBL_'.strtoupper($v)];
                }
            }
		}

		return self::$job_strings;
	}
} // end class definition
