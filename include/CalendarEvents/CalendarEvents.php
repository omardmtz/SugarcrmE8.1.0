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
 * @var CalendarEvents
 */
class CalendarEvents
{
    public static $old_assigned_user_id = null;

    /**
     * Schedulable calendar events (modules) supported
     * @var array
     */
    public $calendarEventModules = array(
        'Meetings',
        'Calls',
        'Tasks',
    );

    /**
     * @param SugarBean $bean
     * @return bool
     * @throws SugarException
     */
    public function isEventRecurring(SugarBean $bean)
    {
        if (!in_array($bean->module_name, $this->calendarEventModules)) {
            $logmsg = 'Recurring Calendar Event - Module Unexpected: ' . $bean->module_name;
            $GLOBALS['log']->error($logmsg);
            throw new SugarException('LBL_CALENDAR_EVENT_RECURRENCE_MODULE_NOT_SUPPORTED', array($bean->module_name));
        }

        $isRecurring = !empty($bean->repeat_type) && !empty($bean->date_start);

        if ($isRecurring) {
            $GLOBALS['log']->debug(sprintf('%s/%s is recurring', $bean->module_name, $bean->id));
        } else {
            $GLOBALS['log']->debug(sprintf('%s/%s is not recurring', $bean->module_name, $bean->id));
        }

        return $isRecurring;
    }

    /**
     * Return Configured recurrence limit.
     * @return int
     */
    public function getRecurringLimit()
    {
        return SugarConfig::getInstance()->get('calendar.max_repeat_count', 1000);
    }

    /**
     * Rebuild the FreeBusy Vcal Cache for specified user
     */
    public function rebuildFreeBusyCache(User $user)
    {
        vCal::cache_sugar_vcal($user);
    }

    /**
     * Build array with recurring parameters.
     *
     * @param SugarBean $parentBean
     * @return array
     */
    public function buildParamsForRecurring(SugarBean $parentBean)
    {
        $params = array();
        $params['type'] = $parentBean->repeat_type;
        $params['interval'] = $parentBean->repeat_interval;
        $params['count'] = $parentBean->repeat_count;
        $params['until'] = $this->formatDateTime('date', $parentBean->repeat_until, 'user');
        $params['dow'] = $parentBean->repeat_dow;

        $params['selector'] = isset($parentBean->repeat_selector) ? $parentBean->repeat_selector : '';
        $params['days'] = isset($parentBean->repeat_days) ? $parentBean->repeat_days : '';
        $params['ordinal'] = isset($parentBean->repeat_ordinal) ? $parentBean->repeat_ordinal : '';
        $params['unit'] = isset($parentBean->repeat_unit) ? $parentBean->repeat_unit : '';

        return $params;
    }

    /**
     * @param SugarBean $parentBean
     * @return array events saved
     * @throws SugarException
     */
    public function saveRecurringEvents(SugarBean $parentBean)
    {
        if (!$this->isEventRecurring($parentBean)) {
            $logmsg = 'SaveRecurringEvents() : Event is not a Recurring Event';
            $GLOBALS['log']->error($logmsg);
            throw new SugarException('LBL_CALENDAR_EVENT_NOT_A_RECURRING_EVENT', array($parentBean->object_name));
        }

        if (!empty($parentBean->repeat_parent_id)) {
            $logmsg = 'SaveRecurringEvents() : Event received is not the Parent Occcurrence';
            $GLOBALS['log']->error($logmsg);
            throw new SugarException('LBL_CALENDAR_EVENT_IS_NOT_A_PARENT_OCCURRENCE', array($parentBean->object_name));
        }

        $dateStart = $this->formatDateTime('datetime', $parentBean->date_start, 'user');

        $params = $this->buildParamsForRecurring($parentBean);

        $repeatDateTimeArray = $this->buildRecurringSequence($dateStart, $params);

        $limit = $this->getRecurringLimit();
        if (count($repeatDateTimeArray) > $limit) {
            $logMessage = sprintf(
                'Calendar Events (%d) exceed Event Limit: (%d)',
                count($repeatDateTimeArray),
                $limit
            );
            $GLOBALS['log']->warning($logMessage);
        }

        // Turn off The Cache Updates while deleting the multiple recurrences.
        // The current Cache Enabled status is returned so it can be appropriately
        // restored when all the recurrences have been deleted.
        $cacheEnabled = vCal::setCacheUpdateEnabled(false);
        $this->markRepeatDeleted($parentBean);
        // Restore the Cache Enabled status to its previous state
        vCal::setCacheUpdateEnabled($cacheEnabled);

        return $this->saveRecurring($parentBean, $repeatDateTimeArray);
    }

    /**
     * Reconcile Tags on Child Bean to Match Parent
     * @param array Tag Beans on the Parent Calendar Event
     * @param SugarBean Child Calendar Event Bean
     * @param array Tag Beans currently existing on Child (optional - defaults to empty array)
     */
    public function reconcileTags(array $parentTagBeans, SugarBean $childBean, $childTagBeans = array())
    {
        $sf = SugarFieldHandler::getSugarField('tag');
        $parentTags = $sf->getOriginalTags($parentTagBeans);
        $childTags = $sf->getOriginalTags($childTagBeans);
        list($addTags, $removeTags) = $sf->getChangedValues($childTags, $parentTags);

        // Handle removal of tags
        $sf->removeTagsFromBean($childBean, $childTagBeans, 'tag_link', $removeTags);

        // Handle addition of new tags
        $sf->addTagsToBean($childBean, $parentTagBeans, 'tag_link', $addTags);
    }

    /**
     * Build the set of Dates/Times for the Recurring Meeting parameters specified
     * @param string $date_start
     * @param array $params
     * @return array datetime Strings
     */
    public function buildRecurringSequence($date_start, $params)
    {
        $options = $params;

        $type = $params['type'];
        if ($type === "Weekly") {
            $dow = $params['dow'];
            if ($dow === "") {
                return array();
            }
        }
        $options['type'] = $type;

        $interval = intval($params['interval']);
        if ($interval < 1) {
            $interval = 1;
        }
        $options['interval'] = $interval;

        if (!empty($params['count'])) {
            $count = $params['count'];
            if ($count < 1) {
                $count = 1;
            }
        } else {
            $count = 0;
        }
        $options['count'] = $count;
        $options['until'] = empty($params['until']) ? '' : $params['until'];

        if ($options['count'] == 0 && empty($options['until'])) {
            return array();
        }

        $start = SugarDateTime::createFromFormat($GLOBALS['timedate']->get_date_time_format(), $date_start);
        $options['start'] = $start;

        if (!empty($options['until'])) {
            $end = SugarDateTime::createFromFormat($GLOBALS['timedate']->get_date_format(), $options['until']);
            $end->setTime(23, 59, 59);   // inclusive
        } else {
            $end = $start;
        }
        $options['end'] = $end;

        $current = clone $start;
        $scratchPad = array();
        $days = array();
        if ($params['type'] === 'Monthly' && !empty($params['selector']) && $params['selector'] === 'Each') {
            if (!empty($params['days'])) {
                $dArray = explode(',', $params['days']);
                foreach ($dArray as $day) {
                    $day = intval($day);
                    if ($day >= 1 && $day <= 31) {
                        $days[$day] = true;
                    }
                }
                ksort($days);
                $days = array_keys($days);
            }
        }
        $options['days'] = $days;
        $scratchPad['days'] = $days;

        $scratchPad['ResultTotal'] = 0;
        $scratchPad['Results'] = array();

        $limit = SugarConfig::getInstance()->get('calendar.max_repeat_count', 1000);

        $loop = true;
        while ($loop) {
            switch ($type) {
                case "Daily":
                    $loop = $this->nextDaily($current, $interval, $options, $scratchPad);
                    break;
                case "Weekly":
                    $loop = $this->nextWeekly($current, $interval, $options, $scratchPad);
                    break;
                case "Monthly":
                    $loop = $this->nextMonthly($current, $interval, $options, $scratchPad);
                    break;
                case "Yearly":
                    $loop = $this->nextYearly($current, $interval, $options, $scratchPad);
                    break;
                default:
                    return array();
            }

            if ($scratchPad['ResultTotal'] > $limit + 100) {
                break;
            }
        }
        return $scratchPad['Results'];
    }

    /**
     * Determine whether recurrence iteration meets the  count or until terminating criteria
     * and Update the Result Array and Result Count Totals Appropriately if the current Date
     * is part of the recurring result set
     * @param SugarDateTime $current
     * @param array $options : the recurrence rules in effect
     * @param array $scratchPad  : Scratchpad Area for intermediate and final result computation
     * @return bool  true=Complete   false=Incomplete
     */
    protected function isComplete($current, $options, &$scratchPad)
    {
        if (($options['count'] == 0 &&
                !empty($options['until']) &&
                !empty($options['end']) &&
                $current->format("U") <= $options['end']->format("U")) ||
            ($options['count'] > 0 &&
                $scratchPad['ResultTotal'] < $options['count'])
        ) {
            $scratchPad['Results'][] = $current->format($GLOBALS['timedate']->get_date_time_format());
            $scratchPad['ResultTotal']++;
            return false;
        }
        return true;
    }

    /**
     * Process the current Datetime for Repeat type = 'Daily'
     * @param SugarDateTime $current : the next Date to be considered as a Result Candidate
     * @param array $interval : interval size
     * @param array $options : array of processing options
     * @param array $scratchPad  : Scratchpad Area for intermediate and final result computation
     * @return boolean : true=continue false=quit
     */
    protected function nextDaily($current, $interval, $options, &$scratchPad)
    {
        if (!$this->isComplete($current, $options, $scratchPad)) {
            $current->modify("+{$interval} Days");
            return true; // Continue
        }
        return false;
    }

    /**
     * Process the current Datetime for Repeat type = 'Weekly'
     * @param SugarDateTime $current : the next Date to be considered as a Result Candidate
     * @param array $interval : interval size
     * @param array $options : array of processing options
     * @param array $scratchPad  : Scratchpad Area for intermediate and final result computation
     * @return boolean : true=continue false=quit
     */
    protected function nextWeekly($current, $interval, $options, &$scratchPad)
    {
        $dow = $current->getDayOfWeek();
        $days = 0;
        while (($pos = strpos($options['dow'], "{$dow}")) === false) {
            $dow++;
            $dow = $dow % 7;
            $days++;
        }
        $current->modify("+{$days} Days");
        if (!$this->isComplete($current, $options, $scratchPad)) {
            if ($pos + 1 == strlen($options['dow'])) {
                $skip = (7 * ($interval - 1)) + 1;
                $current->modify("+{$skip} Days");
            } else {
                $current->modify("+1 Days");
            }
            return true; // Continue
        }
        return false;
    }

    /**
     * Process the current Datetime for Repeat type = 'Monthly'
     * @param SugarDateTime $current : the next Date to be considered as a Result Candidate
     * @param array $interval : interval size
     * @param array $options : array of processing options
     * @param array $scratchPad  : Scratchpad Area for intermediate and final result computation
     * @return boolean : true=continue false=quit
     */
    protected function nextMonthly($current, $interval, $options, &$scratchPad)
    {
        global $app_list_strings;
        if (empty($options['selector']) || $options['selector'] === 'None') {
            if (!$this->isComplete($current, $options, $scratchPad)) {
                $current->modify("+{$interval} Months");
                return true; // Continue
            }
            return false; // Quit
        }

        switch ($options['selector']) {
            case 'On': {
                if (!empty($options['ordinal']) && !empty($options['unit'])) {
                    $ordinal = $options['ordinal'];
                    $unit = $options['unit'];
                    $current->setDateForFirstDayOfMonth();
                    if (!empty($app_list_strings['repeat_ordinal_dom'][$ordinal]) &&
                        !empty($app_list_strings['repeat_unit_dom'][$unit])
                    ) {
                        switch ($ordinal) {
                            case 'first': {
                                $offset = 0;
                                break;
                            }
                            case 'second': {
                                $offset = 1;
                                break;
                            }
                            case 'third': {
                                $offset = 2;
                                break;
                            }
                            case 'fourth': {
                                $offset = 3;
                                break;
                            }
                            case 'fifth': {
                                $offset = 4;
                                break;
                            }
                            default: { // 'last'
                                $offset = -1;
                                break;
                            }
                        }
                        switch ($unit) {
                            case 'Sun': {
                                $targetDay = SugarDateTime::DOW_SUN;
                                break;
                            }
                            case 'Mon': {
                                $targetDay = SugarDateTime::DOW_MON;
                                break;
                            }
                            case 'Tue': {
                                $targetDay = SugarDateTime::DOW_TUE;
                                break;
                            }
                            case 'Wed': {
                                $targetDay = SugarDateTime::DOW_WED;
                                break;
                            }
                            case 'Thu': {
                                $targetDay = SugarDateTime::DOW_THU;
                                break;
                            }
                            case 'Fri': {
                                $targetDay = SugarDateTime::DOW_FRI;
                                break;
                            }
                            case 'Sat': {
                                $targetDay = SugarDateTime::DOW_SAT;
                                break;
                            }
                            default: { // Not Day of the Week: WD (Weekday) or WE (Weekend Day)
                                $targetDay = -1;
                                break;
                            }
                        }

                        $result = null;
                        $last = ($offset == -1);
                        if ($targetDay >= 0) {    // Day Of Week (0=>6)
                            $dates = $current->getMonthDatesForDaysOfWeek(array($targetDay));
                            if ($last) {
                                $offset = count($dates) - 1;
                            }
                            if (isset($dates[$offset])) {
                                $result = $dates[$offset];
                            }
                        } elseif ($unit === 'Day') {
                            if ($last) {
                                $day = $current->getDaysInMonth();
                            } else {
                                $day = $offset + 1;
                            }
                            $result = $current->setDate($current->getYear(), $current->getMonth(), $day);
                        } else {
                            if ($unit === 'WD') { // WeekDay
                                $dates = $current->getMonthDatesForNonWeekEndDays();
                            } else { // 'WE' = Weekend Day
                                $dates = $current->getMonthDatesForWeekEndDays();
                            }
                            if ($last) {
                                $offset = count($dates) - 1;
                            }
                            if (isset($dates[$offset])) {
                                $result = $dates[$offset];
                            }
                        }

                        if (empty($result)) { // Month does not have an instance of the requested Date (e.g. fifth Fri)
                            $current->setDateForFirstDayOfMonth();
                            $current->modify("+{$interval} Months");
                            return true;  // Bypass and Continue
                        }

                        $startDatetime = $options['start'];
                        $temp = clone $startDatetime;
                        $temp->setDate($result->getYear(), $result->getMonth(), $result->getDay());
                        $diffInterval = $startDatetime->diff($temp);
                        if ($diffInterval->invert) {
                            $current->setDateForFirstDayOfMonth();
                            $current->modify("+{$interval} Months");
                            return true;  // Bypass and Continue
                        }

                        if (!$this->isComplete($result, $options, $scratchPad)) {
                            $current->setDateForFirstDayOfMonth();
                            $current->modify("+{$interval} Months");
                            return true; // Continue
                        }

                        return false;  // Quit
                    }
                }
                return false;  // Quit
            }
            case 'Each': {
                /* Current Day of Month need not be considered in the "Each" case - We have specific days to consider */
                $current->setDateForFirstDayOfMonth();
                $startDatetime = $options['start'];
                $temp = clone $startDatetime;
                foreach ($options['days'] as $day) {
                    if ($day <= $current->days_in_month) {
                        $temp->setDate($current->getYear(), $current->getMonth(), $day);
                        $diffInterval = $startDatetime->diff($temp);
                        if ($diffInterval->invert == 0) { // Now or in the future
                            if ($this->isComplete($temp, $options, $scratchPad)) {
                                return false;  // Quit
                            }
                        }
                    }
                }
                $current->modify("+{$interval} Months");
                return true; // Continue
            }
        }
        return false;  // Quit
    }

    /**
     * Process the current Datetime for Repeat type = 'Yearly'
     * @param SugarDateTime $current : the next Date to be considered as a Result Candidate
     * @param array $interval : interval size
     * @param array $options : array of processing options
     * @param array $scratchPad  : Scratchpad Area for intermediate and final result computation
     * @return boolean : true=continue false=quit
     */
    protected function nextYearly($current, $interval, $options, &$scratchPad)
    {
        global $app_list_strings;
        if (empty($options['selector']) || $options['selector'] === 'None') {
            if (!$this->isComplete($current, $options, $scratchPad)) {
                $current->modify("+{$interval} Years");
                return true; // Continue
            }
            return false; // Quit
        }

        $startDatetime = $options['start'];
        $temp = clone $startDatetime;
        $temp->setDate($current->getYear(), $current->getMonth(), $current->getDay());
        $diffInterval = $startDatetime->diff($temp);
        if ($diffInterval->invert) {
            $current->modify("+{$interval} Years");
            return true;  // PastDate: Bypass and Continue
        }

        if ($options['selector'] === 'On') {
            if (!empty($options['ordinal']) && !empty($options['unit'])) {
                $ordinal = $options['ordinal'];
                $unit = $options['unit'];
                if (!empty($app_list_strings['repeat_ordinal_dom'][$ordinal]) &&
                    !empty($app_list_strings['repeat_unit_dom'][$unit])
                ) {
                    switch ($ordinal) {
                        case 'first': {
                            $offset = 0;
                            break;
                        }
                        case 'second': {
                            $offset = 1;
                            break;
                        }
                        case 'third': {
                            $offset = 2;
                            break;
                        }
                        case 'fourth': {
                            $offset = 3;
                            break;
                        }
                        case 'fifth': {
                            $offset = 4;
                            break;
                        }
                        default: { // 'last'
                            $offset = -1;
                            break;
                        }
                    }
                    switch ($unit) {
                        case 'Sun': {
                            $targetDay = SugarDateTime::DOW_SUN;
                            break;
                        }
                        case 'Mon': {
                            $targetDay = SugarDateTime::DOW_MON;
                            break;
                        }
                        case 'Tue': {
                            $targetDay = SugarDateTime::DOW_TUE;
                            break;
                        }
                        case 'Wed': {
                            $targetDay = SugarDateTime::DOW_WED;
                            break;
                        }
                        case 'Thu': {
                            $targetDay = SugarDateTime::DOW_THU;
                            break;
                        }
                        case 'Fri': {
                            $targetDay = SugarDateTime::DOW_FRI;
                            break;
                        }
                        case 'Sat': {
                            $targetDay = SugarDateTime::DOW_SAT;
                            break;
                        }
                        default: { // Not Day of the Week: WD (Weekday) or WE (Weekend Day)
                            $targetDay = -1;
                            break;
                        }
                    }

                    $result = null;
                    $last = ($offset == -1);
                    if ($targetDay >= 0) {    // Day Of Week (0=>6)
                        $dates = $current->getYearDatesForDaysOfWeek(array($targetDay));
                        if ($last) {
                            $offset = count($dates) - 1;
                        }
                        if (isset($dates[$offset])) {
                            $result = $dates[$offset];
                        }
                    } elseif ($unit === 'Day') {
                        if ($last) {
                            $current->setDate($current->getYear(), 12, 31);
                        } else {
                            $day = $offset + 1;
                            $current->setDate($current->getYear(), 1, $day);
                        }
                        $result = $current;
                    } else {
                        if ($last) {
                            $current->setDate($current->getYear(), 12, 1);
                            if ($unit === 'WD') { // WeekDay
                                 $dates = $current->getMonthDatesForNonWeekEndDays();
                             } else { // 'WE' = Weekend Day
                                 $dates = $current->getMonthDatesForWeekEndDays();
                             }
                            $offset = count($dates) - 1;
                            if (isset($dates[$offset])) {
                                $result = $dates[$offset];
                            }
                        } else {
                            $current->setDate($current->getYear(), 1, 1);
                            if ($unit === 'WD') { // WeekDay
                                 $dates = $current->getMonthDatesForNonWeekEndDays();
                             } else { // 'WE' = Weekend Day
                                 $dates = $current->getMonthDatesForWeekEndDays();
                             }
                            if (isset($dates[$offset])) {
                                $result = $dates[$offset];
                            }
                        }
                    }

                    if (empty($result)) { // Month does not have an instance of the requested Date (e.g. fifth Fri)
                        $current->modify("+{$interval} Years");
                        return true;  // Bypass and Continue
                    }

                    $startDatetime = $options['start'];
                    $temp = clone $startDatetime;
                    $temp->setDate($result->getYear(), $result->getMonth(), $result->getDay());
                    $diffInterval = $startDatetime->diff($temp);
                    if ($diffInterval->invert) {
                        $current->modify("+{$interval} Years");
                        return true;  // Bypass and Continue
                    }

                    if (!$this->isComplete($result, $options, $scratchPad)) {
                        $current->modify("+{$interval} Years");
                        return true; // Continue
                    }

                    return false;  // Quit - Complete
                }
            }

            return false;  // Quit  -  Ordinal and/or Unit Options invalid or missing

        }
        return false;  // Quit   -  Selector option invalid
    }

    /**
     * Mark recurring event deleted
     * @param SugarBean parent Bean
     */
    protected function markRepeatDeleted(SugarBean $parentBean)
    {
        CalendarUtils::markRepeatDeleted($parentBean);
    }

    /**
     * Reload all invitees relationships.
     *
     * This guarantees that any changes to the parent event's invitees will be replicated to all children. This is of
     * particular importance to the users relationship (and users_arr), which must be up-to-date during the child bean's
     * save operation because of the auto-accept logic that exists in {@link Meeting::save()} and {@link Call::save()}.
     * The contacts and leads relationships need to be up-to-date while {@link CalendarUtils::saveRecurring} is saving
     * the invitees for each child event.
     *
     * @param SugarBean $parentBean
     * @param array $repeatDateTimeArray
     * @return array events saved
     */
    protected function saveRecurring(SugarBean $parentBean, array $repeatDateTimeArray)
    {
        if ($parentBean->load_relationship('contacts')) {
            $parentBean->contacts->resetLoaded();
            $parentBean->contacts_arr = $parentBean->contacts->get();
        }

        if ($parentBean->load_relationship('leads')) {
            $parentBean->leads->resetLoaded();
            $parentBean->leads_arr = $parentBean->leads->get();
        }

        if ($parentBean->load_relationship('users')) {
            $parentBean->users->resetLoaded();
            $parentBean->users_arr = $parentBean->users->get();
        }

        /*--- Parent Bean previously Created - Remove it from the List ---*/
        if (count($repeatDateTimeArray) > 0) {
            unset ($repeatDateTimeArray[0]);
        }

        return CalendarUtils::saveRecurring($parentBean, $repeatDateTimeArray);
    }

    /**
     * Convert A Date, Time  or DateTime String from one format to Another
     * @param string type of the second argument : one of 'date', 'time', 'datetime', 'datetimecombo'
     * @param string formatted date, time or datetime field in DB, ISO, or User Format
     * @param string output format - one of: 'db', 'iso' or 'user'
     * @param User whose formatting preferences are to be used if output format is 'user'
     * @return string formatted result
     */
    public function formatDateTime($type, $dtm, $toFormat, $user=null)
    {
        $result = '';
        if (empty($user)) {
            $user = $GLOBALS['current_user'];
        }
        $sugarDateTime = $this->getSugarDateTime($type, $dtm, $user);
        if (!empty($sugarDateTime)) {
            $result = $sugarDateTime->formatDateTime($type, $toFormat, $user);
        }
        return $result;
    }

    /**
     * Return a SugarDateTime Object given any Date to Time Format
     * @param string type of the second argument : one of 'date', 'time', 'datetime', 'datetimecombo'
     * @param string  formatted date, time or datetime field in DB, ISO, or User Format
     * @param User whose timezone preferences are to be used (optional - defaults to current user)
     * @return SugarDateTime
     */
    public function getSugarDateTime($type, $dtm, $user=null)
    {
        global $timedate;
        $sugarDateTime = null;
        if (!empty($dtm)) {
            $sugarDateTime = $timedate->fromUserType($dtm, $type, $user);
            if (empty($sugarDateTime)) {
                $sugarDateTime = $timedate->fromDBType($dtm, $type);
            }
            if (empty($sugarDateTime)) {
                switch($type) {
                    case 'time':
                        $sugarDateTime = $timedate->fromIsoTime($dtm);
                        break;
                    case 'date':
                    case 'datetime':
                    case 'datetimecombo':
                    default:
                        $sugarDateTime = $timedate->fromIso($dtm);
                        break;
                }
            }
        }
        return $sugarDateTime;
    }

    public static function getOldAssignedUser($module, $id = null)
    {
        if (static::$old_assigned_user_id === null) { // lazy load
            static::setOldAssignedUser($module, $id);
        }
        return self::$old_assigned_user_id;
    }

    public static function setOldAssignedUserValue($value)
    {
        static::$old_assigned_user_id = $value;
    }

    /**
     * Store Current Assignee Id or blank if New Bean (Create)
     */
    public static function setOldAssignedUser($module, $id = null)
    {
        static::$old_assigned_user_id = '';
        if (!empty($module) && !empty($id)) {
            $old_record = BeanFactory::getBean($module, $id);
            if (!empty($old_record->assigned_user_id)) {
                static::$old_assigned_user_id = $old_record->assigned_user_id;
            }
        }
    }

    /**
     * Add record defined by parent field as an invitee if it is a Contact or Lead record
     *
     * @param $bean
     * @param $parentType
     * @param $parentId
     */
    public function inviteParent($bean, $parentType, $parentId)
    {
        $inviteeRelationships = array(
            'Contacts' => 'contacts',
            'Leads' => 'leads',
        );

        foreach($inviteeRelationships as $module => $relationship) {
            if ($parentType == $module) {
                $bean->load_relationship($relationship);
                if (!$bean->$relationship->relationship_exists($relationship, array('id' => $parentId))) {
                    $bean->$relationship->add($parentId);
                }
            }
        }
    }

    /**
     * Set Start Datetime and End Datetime for a Meeting or Call
     *
     * @param SugarBean $bean - Schedulable Event - i.e Meeting, Call
     * @param SugarDateTime $userDateTime in Database Format (UTC)
     */
    public function setStartAndEndDateTime(SugarBean $bean, SugarDateTime $dateStart)
    {
        global $current_user;

        $dtm = clone $dateStart;
        $bean->duration_hours = empty($bean->duration_hours) ? 0 : intval($bean->duration_hours);
        $bean->duration_minutes =  empty($bean->duration_minutes) ? 0 : intval($bean->duration_minutes);
        $bean->date_start = $dtm->asDb();
        if ($bean->duration_hours > 0) {
            $dtm->modify("+{$bean->duration_hours} hours");
        }
        if ($bean->duration_minutes > 0) {
            $dtm->modify("+{$bean->duration_minutes} mins");
        }
        $bean->date_end = $dtm->asDb();

        if (!$this->isEventRecurring($bean)) {
            $bean->recurrence_id = '';
        } elseif (!$bean->recurrence_id) {
            $bean->recurrence_id = $bean->date_start;
        }
    }

    /**
     * Update an invitee's accept status for a particular event. Update all future events in the series if the event is
     * recurring.
     *
     * Future events are those that have a status that is neither "Held" nor "Not Held".
     *
     * @param SugarBean $event
     * @param SugarBean $invitee
     * @param string $status
     * @param array $options See {@link BeanFactory::retrieveBean}.
     * @return bool True if at least one accept status was updated.
     * @throws SugarException
     */
    public function updateAcceptStatusForInvitee(
        SugarBean $event,
        SugarBean $invitee,
        $status = 'accept',
        $options = array()
    ) {
        $changeWasMade = false;
        if (in_array($event->status, array('Held', 'Not Held'))) {
            $GLOBALS['log']->debug(
                sprintf(
                    'Do not update the %s/%s accept status for the parent event %s/%s when the event status is %s',
                    $invitee->module_name,
                    $invitee->id,
                    $event->module_name,
                    $event->id,
                    $event->status
                )
            );
        } else {
            $GLOBALS['log']->debug(
                sprintf(
                    'Set %s/%s accept status to %s for %s/%s',
                    $invitee->module_name,
                    $invitee->id,
                    $status,
                    $event->module_name,
                    $event->id
                )
            );
            $event->update_vcal = false;
            $event->set_accept_status($invitee, $status);
            $changeWasMade = true;
        }

        if ($this->isEventRecurring($event)) {
            /**
             * Updates the invitee's accept status for one occurrence in the series.
             *
             * @param array $row The child record to update. Only the ID is used.
             */
            $callback = function (array $row) use (
                $event,
                $invitee,
                $status,
                $options,
                &$changeWasMade
            ) {
                $child = BeanFactory::retrieveBean($event->module_name, $row['id'], $options);

                if ($child) {
                    $GLOBALS['log']->debug(sprintf(
                        'Set %s/%s accept status to %s for %s/%s',
                        $invitee->module_name,
                        $invitee->id,
                        $status,
                        $child->module_name,
                        $child->id
                    ));
                    $child->update_vcal = false;
                    $child->set_accept_status($invitee, $status);
                    $changeWasMade = true;
                } else {
                    $GLOBALS['log']->error("Could not set acceptance status for {$event->module_name}/{$row['id']}");
                }
            };

            $query = $this->getChildrenQuery($event);
            $GLOBALS['log']->debug('Only update occurrences that have not been held or canceled');
            $query->where()
                ->notEquals('status', 'Held')
                ->notEquals('status', 'Not Held');
            $this->repeatAction($query, $callback);
        }

        if ($changeWasMade) {
            if ($invitee instanceof User) {
                $GLOBALS['log']->debug(sprintf('Update vCal cache for %s/%s', $invitee->module_name, $invitee->id));
                vCal::cache_sugar_vcal($invitee);
            }
        }


        return $changeWasMade;
    }

    /**
     * Returns a SugarQuery object that can be used to fetch all of the child events in a recurring series.
     *
     * @param SugarBean $parent
     * @return SugarQuery Modify the object to restrict the result set based on additional conditions.
     * @throws SugarQueryException
     */
    protected function getChildrenQuery(SugarBean $parent)
    {
        $GLOBALS['log']->debug(sprintf(
            'Building a query to retrieve the IDs for %s records where the repeat_parent_id is %s',
            $parent->module_name,
            $parent->id
        ));
        $query = new SugarQuery();
        $query->select(array('id'));
        $query->from($parent);
        $query->where()->equals('repeat_parent_id', $parent->id);
        $query->orderBy('date_start', 'ASC');
        return $query;
    }

    /**
     * Repeat the same action for each record returned by a query. This is useful for repeating an action for each child
     * record in a series.
     *
     * Retrieves, from the database, a max of 200 records at a time upon which to perform the action. This is done to
     * reduce the memory footprint in the event that too many records would be loaded into memory.
     *
     * @param SugarQuery $query The SugarQuery object to use to retrieve the records.
     * @param Closure $callback The function to call for each child record. The database row -- as an array -- is
     * passed to the callback.
     */
    protected function repeatAction(SugarQuery $query, Closure $callback)
    {
        $limit = 200;
        $offset = 0;

        do {
            $GLOBALS['log']->debug(sprintf('Retrieving the next %d records beginning at %d', $limit, $offset));
            $query->limit($limit)->offset($offset);
            $rows = $query->execute();
            $rowCount = count($rows);
            $GLOBALS['log']->debug(sprintf('Repeating the action on %d events', $rowCount));
            array_walk($rows, $callback);
            $offset += $rowCount;
        } while ($rowCount === $limit);

        $GLOBALS['log']->debug(sprintf(
            'Finished repeating because the row count %d does not equal the limit %d',
            $rowCount,
            $limit
        ));
    }
}
