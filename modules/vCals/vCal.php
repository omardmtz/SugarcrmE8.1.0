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


class vCal extends SugarBean {

    private static $cacheUpdate_enabled = true;

    private static $backtrace_log_enabled = 'none';  // 'none', 'cache', 'freebusy', 'all'

    // Stored fields
	var $id;
	var $date_modified;
	var $user_id;
	var $content;
	var $deleted;
	var $type;
	var $source;
	var $module_dir = "vCals";
	var $table_name = "vcals";

	var $object_name = "vCal";

	var $new_schema = true;

	var $field_defs = array(
	);

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array();

	const UTC_FORMAT = 'Ymd\THi00\Z';
    const EOL = "\r\n";
    const TAB = "\t";
    const CHARSPERLINE = 75;

	public function __construct()
	{

		parent::__construct();
		$this->disable_row_level_security = true;
	}

    /**
     * Enable or Disable Cache Updates
     * @param bool - CacheUpdate status - true if enabled else false
     * @return bool - Previous cache setting returned so it can be later restored.
     */
    public static function setCacheUpdateEnabled($enabled)
    {
        $previousValue = static::$cacheUpdate_enabled;
        static::$cacheUpdate_enabled = $enabled;
        return $previousValue;
    }

	function get_summary_text()
	{
		return "";
	}


	function fill_in_additional_list_fields()
	{
	}

	function fill_in_additional_detail_fields()
	{
	}

	function get_list_view_data()
	{
	}

        // combines all freebusy vcals and returns just the FREEBUSY lines as a string
	function get_freebusy_lines_cache(&$user_bean)
	{
        global $sugar_config;
        $ical_array = array();

        if (empty($sugar_config['freebusy_use_vcal_cache'])) {
            return ''; // VCal FreeBusy Cache Is Not Enabled - No Data will be returned from vcals table
        }

		// First, get the list of IDs.
		$query = "SELECT id from vcals where user_id='{$user_bean->id}' AND type='vfb' AND deleted=0";
		$vcal_arr = $this->build_related_list($query, BeanFactory::newBean('vCals'));

		foreach ($vcal_arr as $focus)
		{
			if (empty($focus->content))
			{
				return '';
			}

            $ical_arr = self::create_ical_array_from_string($focus->content);
            $ical_array = array_merge($ical_array, $ical_arr);
		}

        return self::create_ical_string_from_array($ical_array);
	}

	// query and create the FREEBUSY lines for SugarCRM Meetings and Calls and
        // return the string
	function create_sugar_freebusy($user_bean, $start_date_time, $end_date_time)
	{
        $ical_array = array();
		global $DO_USER_TIME_OFFSET,$timedate;

        $DO_USER_TIME_OFFSET = true;
		if(empty($GLOBALS['current_user']) || empty($GLOBALS['current_user']->id)) {
		    $GLOBALS['current_user'] = $user_bean;
		}
		// get activities.. queries Meetings and Calls
		$acts_arr =
		CalendarActivity::get_activities($user_bean->id,
            false,
			$start_date_time,
			$end_date_time,
			'freebusy',
            true,
            false);   // don't need to get parent data. Only need time slots.

		// loop thru each activity, get start/end time in UTC, and return FREEBUSY strings
		foreach($acts_arr as $act)
		{
            if(empty($act->start_time) || empty($act->end_time)){
                //create error message
                $errMSG = "ERROR in Vcal::create_sugar_freebusy.  Calendar Activity was not created because of missing start or end time";
                if(!empty($act->sugar_bean->id)){
                    $errMSG .= ", id is ".$act->sugar_bean->id;
                }
                if(!empty($act->sugar_bean->name)){
                    $errMSG .= ", name is: ".$act->sugar_bean->name;
                }
                //log message and continue
                $GLOBALS['log']->fatal($errMSG);
                continue;
            }
			$startTimeUTC = $act->start_time->format(self::UTC_FORMAT);
			$endTimeUTC = $act->end_time->format(self::UTC_FORMAT);

            $ical_array[] = array("FREEBUSY", $startTimeUTC ."/". $endTimeUTC);
		}
        return self::create_ical_string_from_array($ical_array);

	}

    /**
     * @param $user_focus
     * @param bool  $cached
     * @param SugarDateTime $startDate  optional: if Not cached
     * @param SugarDateTime $endDate    optional: if Not cached
     * @return string freebusy vcal string
     */
    // return a freebusy vcal string
    function get_vcal_freebusy($user_focus, $cached = true, SugarDateTime $startDate = null, SugarDateTime $endDate = null)
    {
        global $locale, $timedate;
        $ical_array = array();
        $ical_array[] = array("BEGIN", "VCALENDAR");
        $ical_array[] = array("VERSION", "2.0");
        $ical_array[] = array("PRODID", "-//SugarCRM//SugarCRM Calendar//EN");
        $ical_array[] = array("BEGIN", "VFREEBUSY");

        if (static::$backtrace_log_enabled == 'freebusy' || static::$backtrace_log_enabled == 'all') {
            $trace = $this->getBackTrace("VCAL:FREEBUSY - ");
            $GLOBALS['log']->fatal("VCAL:FREEBUSY - get_vcal_freebusy()\n" . $trace);
        }


        $name = $locale->formatName($user_focus);
        $email = $user_focus->email1;
        // get current date for the user
        $now_date_time = $timedate->getNow(true);
        $timeOffset = 2;

        $realTimeSearch = false;
        if (!$cached && !empty($startDate) && !empty($endDate)) {
            $realTimeSearch = true;

            // Use Start and End Dates provided
            $start_date_time = $startDate;
            $end_date_time = $endDate;
        } else {
            // get start date ( 1 day ago )
            $start_date_time = $now_date_time->get("yesterday");

            // get date 2 months from start date
            global $sugar_config;
            if (isset($sugar_config['vcal_time']) && $sugar_config['vcal_time'] != 0 && $sugar_config['vcal_time'] < 13)
            {
                $timeOffset = $sugar_config['vcal_time'];
            }
            $end_date_time = $start_date_time->get("+$timeOffset months");
        }

        // get UTC time format
        $utc_start_time = $start_date_time->asDb();
        $utc_end_time = $end_date_time->asDb();
        $utc_now_time = $now_date_time->asDb();

        $ical_array[] = array("ORGANIZER;CN=$name", "VFREEBUSY");
        $ical_array[] = array("DTSTART", $utc_start_time);
        $ical_array[] = array("DTEND", $utc_end_time);

        $str = self::create_ical_string_from_array($ical_array);

        if ($realTimeSearch || (!$cached && $timeOffset != 0)) {
            // insert the freebusy lines
            $str .= $this->create_sugar_freebusy($user_focus, $start_date_time, $end_date_time);
        } else {
            // retrieve cached freebusy lines from vcals
            $str .= $this->get_freebusy_lines_cache($user_focus);
        }

        // UID:20030724T213406Z-10358-1000-1-12@phoenix
        $str .= self::fold_ical_lines("DTSTAMP", $utc_now_time) . self::EOL;
        $str .= "END:VFREEBUSY".self::EOL;
        $str .= "END:VCALENDAR".self::EOL;
        return $str;

    }

	// static function:
        // cache vcals
        public static function cache_sugar_vcal(&$user_focus)
        {
            self::cache_sugar_vcal_freebusy($user_focus);
        }

	// static function:
    // caches vcal for Activities in Sugar database
    public static function cache_sugar_vcal_freebusy(&$user_focus)
    {
        global $sugar_config;
        if (!static::$cacheUpdate_enabled) {
            return;
        }

        if (empty($sugar_config['freebusy_use_vcal_cache'])) {
            return; // VCal FreeBusy Cache Not Enabled - No Updates to vcals table will Occur
        }

        $focus = BeanFactory::newBean('vCals');

        if (static::$backtrace_log_enabled == 'cache' || static::$backtrace_log_enabled == 'all') {
            $trace = $focus->getBackTrace("VCAL:CACHE - ");
            $GLOBALS['log']->fatal("VCAL:CACHE - cache_sugar_vcal_freebusy()\n" . $trace);
        }

        // set freebusy members and save
        $arr = array('user_id' => $user_focus->id, 'type' => 'vfb', 'source' => 'sugar');
        $focus->retrieve_by_string_fields($arr);


        $focus->content = $focus->get_vcal_freebusy($user_focus, false);
        $focus->type = 'vfb';
        $focus->date_modified = null;
        $focus->source = 'sugar';
        $focus->user_id = $user_focus->id;
        $focus->save();
    }

    /*
     * Lines of text SHOULD NOT be longer than 75 octets, excluding the line break.
     * Long content lines SHOULD be split into a multiple line representations using a line "folding" technique
     */
    public static function fold_ical_lines($key, $value)
    {
        $iCalValue = $key . ":" . $value;

        if (strlen($iCalValue) <= self::CHARSPERLINE) {
            return $iCalValue;
        }

        $firstchars = substr($iCalValue, 0, self::CHARSPERLINE);
        $remainingchars = substr($iCalValue, self::CHARSPERLINE);
        $end = self::EOL . self::TAB;

        $remainingchars = substr(
            chunk_split(
                $end . $remainingchars,
                self::CHARSPERLINE + strlen(self::EOL),
                $end
            ),
            0,
            -strlen($end) // exclude last EOL and TAB chars
        );

        return $firstchars . $remainingchars;
    }

    /**
     * this function takes an iCal string and converts it to iCal array while following RFC rules
     */
    public static function create_ical_array_from_string($ical_string)
    {
        $ical_string = preg_replace("/\r\n\s+/", "", $ical_string);
        $lines = preg_split("/\r?\n/", $ical_string);
        $ical_array = array();

        foreach ($lines as $line) {
            $line = self::unescape_ical_chars($line);
            $line = explode(":", $line, 2);
            if (count($line) != 2) {
                continue;
            }
            $ical_array[] = array($line[0], $line[1]);
        }

        return $ical_array;
    }

    /**
     * this function takes an iCal array and converts it to iCal string while following RFC rules
     */
    public static function create_ical_string_from_array($ical_array)
    {
        $str = "";
        foreach ($ical_array as $ical) {
            $str .= self::fold_ical_lines($ical[0], self::escape_ical_chars($ical[1])) . self::EOL;
        }
        return $str;
    }

    /**
     * escape iCal chars as per RFC 5545: http://tools.ietf.org/html/rfc5545#section-3.3.11
     *
     * @param string $string string to escape chars
     * @return escaped string
     */
    public static function escape_ical_chars($string)
    {
        $string = str_replace(array("\\", "\r", "\n", ";", ","), array("\\\\", "\\r", "\\n", "\\;", "\\,"), $string);
        return $string;
    }

    /**
     * unescape iCal chars as per RFC 5545: http://tools.ietf.org/html/rfc5545#section-3.3.11
     *
     * @param string $string string to escape chars
     * @return unescaped string
     */
    public static function unescape_ical_chars($string)
    {
        $string = str_replace(array("\\r", "\\n", "\\;", "\\,", "\\\\"), array("\r", "\n", ";", ",", "\\"), $string);
        return $string;
    }

    private function getBacktrace($prepend = "", $ignore = 2)
    {
        $trace = '';
        foreach (debug_backtrace() as $k => $v) {
            if ($k < $ignore) {
                continue;
            }
            $trace .= $prepend . '#' . ($k - $ignore) . ' ' . $v['file'] . '(' . $v['line'] . '): ' . (isset($v['class']) ? $v['class'] . '->' : '') . $v['function'] . "()\n";
        }
        return $trace;
        }

    /**
     * get ics file content for meeting invite email
     */
    public static function get_ical_event(SugarBean $bean, User $user)
    {
        global $timedate;
        $ical_array = array();

        $ical_array[] = array("BEGIN", "VCALENDAR");
        $ical_array[] = array("VERSION", "2.0");
        $ical_array[] = array("PRODID", "-//SugarCRM//SugarCRM Calendar//EN");
        $ical_array[] = array("BEGIN", "VEVENT");
        $ical_array[] = array("UID", $bean->id);
        $ical_array[] = array("ORGANIZED;CN=" . $user->full_name, $user->email1);
        $ical_array[] = array("DTSTART", $timedate->fromDb($bean->date_start)->format(self::UTC_FORMAT));
        $ical_array[] = array("DTEND", $timedate->fromDb($bean->date_end)->format(self::UTC_FORMAT));

        $ical_array[] = array(
            "DTSTAMP",
            $GLOBALS['timedate']->getNow(false)->format(self::UTC_FORMAT)
        );
        $ical_array[] = array("SUMMARY", $bean->name);
        $ical_array[] = array("LOCATION", $bean->location);

        $descPrepend = empty($bean->join_url) ? "" : $bean->join_url . self::EOL . self::EOL;
        $ical_array[] = array("DESCRIPTION", $descPrepend . $bean->description);

        $ical_array[] = array("END", "VEVENT");
        $ical_array[] = array("END", "VCALENDAR");

        return self::create_ical_string_from_array($ical_array);
    }

}

?>
