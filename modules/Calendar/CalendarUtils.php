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
use Sugarcrm\Sugarcrm\ProcessManager\Registry;
class CalendarUtils
{
	/**
	 * Find first day of week according to user's settings
	 * @param SugarDateTime $date
	 * @return SugarDateTime $date
	 */
	static function get_first_day_of_week(SugarDateTime $date){
		$fdow = $GLOBALS['current_user']->get_first_day_of_week();
		if($date->day_of_week < $fdow)
				$date = $date->get('-7 days');
		return $date->get_day_by_index_this_week($fdow);
	}


	/**
	 * Get list of needed fields for modules
	 * @return array
	 */
	static function get_fields(){
		return array(
			'Meetings' => array(
				'name',
				'duration_hours',
				'duration_minutes',
				'status',
				'related_to',
			),
			'Calls' => array(
				'name',
				'duration_hours',
				'duration_minutes',
				'status',
				'related_to',
			),
			'Tasks' => array(
				'name',
				'status',
				'related_to',
			),
		);
	}

    /**
     * Get array of needed time data
     * @param SugarBean $bean
     * @return array
     */
    public static function get_time_data(SugarBean $bean)
    {
        $arr = array();

        $start_field = "date_start";
        $end_field = "date_end";

        if ($bean->object_name == 'Task') {
            $start_field = $end_field = "date_due";
        }
        if (empty($bean->$start_field)) {
            return array();
        }
        if (empty($bean->$end_field)) {
            $bean->$end_field = $bean->$start_field;
        }

        if ($GLOBALS['timedate']->check_matching_format($bean->$start_field, TimeDate::DB_DATETIME_FORMAT)) {
            $userStartDate = $GLOBALS['timedate']->to_display_date_time($bean->$start_field);
            $userEndDate = $GLOBALS['timedate']->to_display_date_time($bean->$end_field);
        } else {
            $userStartDate = $bean->$start_field;
            $userEndDate = $bean->$end_field;
        }

        $dtmStart = SugarDateTime::createFromFormat(
            $GLOBALS['timedate']->get_date_time_format(),
            $userStartDate,
            new DateTimeZone('UTC')
        );
        $dtmEnd = SugarDateTime::createFromFormat(
            $GLOBALS['timedate']->get_date_time_format(),
            $userEndDate,
            new DateTimeZone('UTC')
        );

        $arr['timestamp'] = $dtmStart->format('U');
        $arr['time_start'] = $GLOBALS['timedate']->fromTimestamp($arr['timestamp'])->format(
            $GLOBALS['timedate']->get_time_format()
        );

        $arr['ts_start'] = $dtmStart->get(
            '-' . $dtmStart->format('H') . ' hours -' . $dtmStart->format('i') . ' minutes -' . $dtmStart->format(
                's'
            ) . ' seconds'
        )->format('U');

        $arr['offset'] = $dtmStart->format('H') * 3600 + $dtmStart->format('i') * 60;

        if ($bean->object_name != 'Task') {
            $dtmEnd->modify('-1 minute');
        }

        $arr['ts_end'] = $dtmEnd->get('+1 day')->get(
            '-' . $dtmEnd->format('H') . ' hours -' . $dtmEnd->format('i') . ' minutes -' . $dtmEnd->format(
                's'
            ) . ' seconds'
        )->format('U');

        $arr['days'] = ($arr['ts_end'] - $arr['ts_start']) / (3600 * 24);

        return $arr;
    }

	/**
	 * Get array that will be sent back to ajax frontend
	 * @param SugarBean $bean
	 * @return array
	 */
	static function getBeanDataArray(SugarBean $bean)
	{
			if(isset($bean->parent_name) && isset($_REQUEST['parent_name']))
				$bean->parent_name = $_REQUEST['parent_name'];

			$users = array();
			if($bean->object_name == 'Call')
				$users = $bean->get_call_users();
			else if($bean->object_name == 'Meeting')
				$users = $bean->get_meeting_users();
			$user_ids = array();
			foreach($users as $u)
				$user_ids[] = $u->id;

			$field_list = CalendarUtils::get_fields();
			$field_arr = array();
			foreach($field_list[$bean->module_dir] as $field){
			    if ($field == 'related_to')
			    {
			        $focus = BeanFactory::getBean($bean->parent_type, $bean->parent_id);
			        $field_arr[$field] = $focus->name;
			    }
			    else
			    {
			        $field_arr[$field] = $bean->$field;
			    }
			}

			$date_field = "date_start";
			if($bean->object_name == 'Task')
				$date_field = "date_due";

			$arr = array(
				'access' => 'yes',
				'type' => strtolower($bean->object_name),
				'module_name' => $bean->module_dir,
				'user_id' => $GLOBALS['current_user']->id,
				'detail' => 1,
				'edit' => 1,
				'name' => $bean->name,
				'record' => $bean->id,
				'users' => $user_ids,
			);
			if(!empty($bean->repeat_parent_id))
				$arr['repeat_parent_id'] = $bean->repeat_parent_id;
			$arr = array_merge($arr,$field_arr);
			$arr = array_merge($arr,CalendarUtils::get_time_data($bean));

			return $arr;
	}

	/**
	 * Get array of repeat data
	 * @param SugarBean $bean
	 * @return array
	 */
	 static function getRepeatData(SugarBean $bean, $editAllRecurrences = false, $dateStart = false)
	 {
	 	if ($bean->module_dir == "Meetings" || $bean->module_dir == "Calls") {
	 		if (!empty($bean->repeat_parent_id) || (!empty($bean->repeat_type) && empty($editAllRecurrences))) {
				if (!empty($bean->repeat_parent_id)) {
					$repeat_parent_id = $bean->repeat_parent_id;
				} else {
					$repeat_parent_id = $bean->id;
				}
	 			return array("repeat_parent_id" => $repeat_parent_id);
	 		}

	 		$arr = array();
	 		if (!empty($bean->repeat_type)) {
	 			$arr = array(
	 				'repeat_type' => $bean->repeat_type,
	 				'repeat_interval' => $bean->repeat_interval,
	 				'repeat_dow' => $bean->repeat_dow,
	 				'repeat_until' => $bean->repeat_until,
	 				'repeat_count' => $bean->repeat_count,
	 			);
	 		}

	 		if (empty($dateStart)) {
	 			$dateStart = $bean->date_start;
	 		}

            $date = SugarDateTime::createFromFormat($GLOBALS['timedate']->get_date_time_format(), $dateStart);
            if (empty($date)) {
                $date = $GLOBALS['timedate']->getNow(true);
            }
            $arr = array_merge($arr,array(
                'current_dow' => $date->format("w"),
                'default_repeat_until' => $date->get("+1 Month")->format($GLOBALS['timedate']->get_date_format()),
            ));

		 	return $arr;
		}
	 	return false;
	 }

	/**
	 * Build array of datetimes for recurring meetings
	 * @param string $date_start
	 * @param array $params
	 * @return array
	 */
	static function buildRecurringSequence($date_start, $params)
	{
		$arr = array();

		$type = $params['type'];
		$interval = intval($params['interval']);
		if($interval < 1)
			$interval = 1;

		if(!empty($params['count'])){
			$count = $params['count'];
			if($count < 1)
				$count = 1;
		}else
			$count = 0;

		if(!empty($params['until'])){
			$until = $params['until'];
		}else
			$until = $date_start;

		if($type == "Weekly"){
			$dow = $params['dow'];
			if($dow == ""){
				return array();
			}
		}

        /**
		 * @var SugarDateTime $start Recurrence start date.
		 */
		$start = SugarDateTime::createFromFormat($GLOBALS['timedate']->get_date_time_format(),$date_start);
        $current = clone $start;

        /**
		 * @var SugarDateTime $end Recurrence end date. Used if recurrence ends by date.
         * To Make the RepeatUntil Date Inclusive, we need to Add 1 Day to End
		 */
		if (!empty($params['until'])) {
			$end = SugarDateTime::createFromFormat($GLOBALS['timedate']->get_date_format(), $until);
            $end->setTime(23, 59, 59);   // inclusive
		} else {
			$end = $start;
		}

		$i = 1; // skip the first iteration
		$w = $interval; // for week iteration
		$last_dow = $start->format("w");

		$limit = SugarConfig::getInstance()->get('calendar.max_repeat_count',1000);

		while($i < $count || ($count == 0 && $current->format("U") <= $end->format("U"))){
			$skip = false;
			switch($type){
				case "Daily":
					$current->modify("+{$interval} Days");
					break;
				case "Weekly":
					$day_index = $last_dow;
					for($d = $last_dow + 1; $d <= $last_dow + 7; $d++){
						$day_index = $d % 7;
						if(strpos($dow,(string)($day_index)) !== false){
							break;
						}
					}
					$step = $day_index - $last_dow;
					$last_dow = $day_index;
					if($step <= 0){
						$step += 7;
						$w++;
					}
					if($w % $interval != 0)
						$skip = true;

					$current->modify("+{$step} Days");
					break;
				case "Monthly":
					$current->modify("+{$interval} Months");
					break;
				case "Yearly":
					$current->modify("+{$interval} Years");
					break;
				default:
					return array();
			}

			if($skip)
				continue;

			if ($i < $count || ($count == 0 && $current->format("U") <= $end->format("U"))) {
				$arr[] = $current->format($GLOBALS['timedate']->get_date_time_format());
			}
			$i++;

			if($i > $limit + 100)
				break;
		}
		return $arr;
	}

    /**
     * Save repeat activities.
     *
     * @param SugarBean $bean
     * @param array $timeArray Array of start datetimes for each occurrence in the series.
     * @return array
     */
    public static function saveRecurring(SugarBean $bean, $timeArray)
    {
        set_time_limit(0); // Required to prevent inadvertent timeouts for large recurring series

        $contacts = $bean->get_linked_beans('contacts', 'Contact');
        $leads = $bean->get_linked_beans('leads', 'Lead');
        $users = $bean->get_linked_beans('users', 'User');

        Activity::disable();

        $calendarEvents = new CalendarEvents();
        $bean->load_relationship('tag_link');
        $parentTagBeans = $bean->tag_link->getBeans();

        $arr = array();
        $i = 0;
        $clone = clone $bean;

        // $clone is a new bean being created - so throw away the cloned fetched_row attribute that incorrectly makes it
        // look like an existing bean.
        $clone->fetched_row = false;

        foreach ($timeArray as $dateStart) {
            //TODO: CHECK DATETIME VARIABLE
            $date = SugarDateTime::createFromFormat($GLOBALS['timedate']->get_date_time_format(), $dateStart);
            $date = $date->get("+{$bean->duration_hours} Hours")->get("+{$bean->duration_minutes} Minutes");
            $dateEnd = $date->format($GLOBALS['timedate']->get_date_time_format());

            $clone->id = create_guid();
            $clone->new_with_id = true;
            $clone->date_start = $dateStart;
            $clone->date_end = $dateEnd;
            $clone->recurring_source = "Sugar";
            $clone->repeat_parent_id = $bean->id;
            $clone->update_vcal = false;
            $clone->send_invites = false;

            // make sure any store relationship info is not saved
            $clone->rel_fields_before_value = array();
            // Before calling save, we need to clear out any existing registered AWF
            // triggered start events so they can continue to trigger.
            Registry\Registry::getInstance()->drop('triggered_starts');
            $clone->save(false);

            if ($clone->id) {
                if ($clone->load_relationship('tag_link')) {
                    $calendarEvents->reconcileTags($parentTagBeans, $clone);
                }

                if ($clone->load_relationship('contacts')) {
                    $clone->contacts->add($contacts);
                }

                if ($clone->load_relationship('leads')) {
                    $clone->leads->add($leads);
                }

                if ($clone->load_relationship('users')) {
                    // We want to preserve user's accept status for the event.
                    foreach ($users as $user) {
                        $additionalFields = array();
                        if (isset($bean->users->rows[$user->id]) &&
                            isset($bean->users->rows[$user->id]['accept_status'])) {
                            $additionalFields['accept_status'] = $bean->users->rows[$user->id]['accept_status'];
                        }
                        $clone->users->add($user, $additionalFields);
                    }
                }

                if ($i < 44) {
                    $clone->date_start = $dateStart;
                    $clone->date_end = $dateEnd;
                    $arr[] = array_merge(array('id' => $clone->id), CalendarUtils::get_time_data($clone));
                }

                $i++;
            }
        }

        Activity::restoreToPreviousState();

		vCal::cache_sugar_vcal($GLOBALS['current_user']);
		return $arr;
	}

    /**
     * Delete recurring activities and their invitee relationships.
     *
     * {@link SugarBean::mark_deleted()} is not used because it runs slowly. The before_delete and after_delete logic
     * hooks are triggered, but the before_relationship_delete and after_relationship_delete logic hooks are not
     * triggered.
     *
     * @param SugarBean $bean
     */
    public static function markRepeatDeleted(SugarBean $bean)
    {
        $db = DBManagerFactory::getInstance();
        $modified_user_id = empty($GLOBALS['current_user']) ? 1 : $GLOBALS['current_user']->id;
        $date_modified = $GLOBALS['timedate']->nowDb();
        $lower_name = strtolower($bean->object_name);

        $sq = new SugarQuery();
        $sq->select(array('id'));
        $sq->from($bean);
        $sq->where()->equals('repeat_parent_id', $bean->id);
        $rows = $sq->execute();

        foreach ($rows as $row) {
            $bean = BeanFactory::retrieveBean($bean->module_name, $row['id']);

            if ($bean) {
                $bean->call_custom_logic('before_delete', array('id' => $bean->id));

                // Delete the occurrence.
                $db->query("UPDATE {$bean->table_name} SET deleted = 1, date_modified = "
                    . $db->convert($db->quoted($date_modified), 'datetime')
                    . ", modified_user_id = " . $db->quoted($modified_user_id)
                    . " WHERE id = " . $db->quoted($row['id']));

                // Remove the contacts invitees.
                $db->query("UPDATE {$bean->rel_contacts_table} SET deleted = 1, date_modified = "
                    . $db->convert($db->quoted($date_modified), 'datetime')
                    . " WHERE {$lower_name}_id = " . $db->quoted($row['id']));

                if ($bean->load_relationship('contacts')) {
                    $bean->contacts->resetLoaded();
                }

                // Remove the leads invitees.
                $db->query("UPDATE {$bean->rel_leads_table} SET deleted = 1, date_modified = "
                    . $db->convert($db->quoted($date_modified), 'datetime')
                    . " WHERE {$lower_name}_id = " . $db->quoted($row['id']));

                if ($bean->load_relationship('leads')) {
                    $bean->leads->resetLoaded();
                }

                // Remove the users invitees.
                $db->query("UPDATE {$bean->rel_users_table} SET deleted = 1, date_modified = "
                    . $db->convert($db->quoted($date_modified), 'datetime')
                    . " WHERE {$lower_name}_id = " . $db->quoted($row['id']));

                if ($bean->load_relationship('users')) {
                    $bean->users->resetLoaded();
                }

                $bean->call_custom_logic('after_delete', array('id' => $bean->id));
            }
        }

        vCal::cache_sugar_vcal($GLOBALS['current_user']);
    }

    /**
     * check if meeting has repeat children and pass repeat_parent over to the 2nd meeting in sequence
     * @param Call|Meeting|SugarBean $bean
     * @param string $beanId
     */
    static function correctRecurrences(SugarBean $bean, $beanId)
    {
        global $db;

        if (!$beanId || trim($beanId) == '') {
            return;
        }

        $query = "SELECT id FROM {$bean->table_name} WHERE repeat_parent_id = '{$beanId}' AND deleted = 0 ORDER BY date_start";
        $result = $db->query($query);

        $date_modified = $GLOBALS['timedate']->nowDb();

        $new_parent_id = false;
        while ($row = $db->fetchByAssoc($result)) {
            $id = $row['id'];
            if (!$new_parent_id) {
                $new_parent_id = $id;
                $query = "UPDATE {$bean->table_name} SET repeat_parent_id = NULL, recurring_source = NULL, date_modified = " . $db->convert($db->quoted($date_modified), 'datetime') . " WHERE id = '{$id}'";
            } else {
                $query = "UPDATE {$bean->table_name} SET repeat_parent_id = '{$new_parent_id}', date_modified = " . $db->convert($db->quoted($date_modified), 'datetime') . " WHERE id = '{$id}'";
            }
            $db->query($query);
        }
    }

    /**
     * get all invites for bean, such as  contacts, leads and users
     * @deprecated This is an unused method. Guests are loaded without consideration for changes to the set that are
     * yet to be processed. This method should not be used.
     * @param SugarBean|Call|Meeting $bean
     * @return array
     */
    public static function getInvitees(\SugarBean $bean)
    {
        /** @var Localization $locale */
        global $locale;

        $GLOBALS['log']->deprecated('CalendarUtils::getInvitees should not be used');

        $definitions = \VardefManager::getFieldDefs($bean->module_name);
        if (isset($definitions['invitees']['links'])) {
            $requiredRelations = $definitions['invitees']['links'];
        } else {
            $requiredRelations = array('contacts', 'leads', 'users');
        }

        $invitees = array();
        foreach ($requiredRelations as $relationship) {
            if ($bean->load_relationship($relationship)) {
                $bean->$relationship->resetLoaded();
                $bean->$relationship->load();
                foreach ($bean->$relationship->rows as $beanId => $row) {
                    /** @var SugarBean $person */
                    $person = BeanFactory::getBean(ucfirst($relationship), $beanId,
                        array('disable_row_level_security' => true));
                    if (!$person) {
                        continue;
                    }
                    if ($person instanceof \User && $beanId == $bean->created_by) {
                        continue;
                    }
                    $invitee = array(
                        $person->module_name,
                        $person->id,
                        $person->emailAddress->getPrimaryAddress($person),
                        $row['accept_status'],
                        $locale->formatName($person),
                    );
                    $invitees[] = $invitee;
                }
            }
        }
        return $invitees;
    }

    /**
     * Build notification list for Calls and Meetings.
     *
     * @param Call|Meeting|SugarBean $event
     * @return string[]
     * @throws Exception
     */
    public static function buildInvitesList(\SugarBean $event)
    {
        if (!($event instanceof \Call) && !($event instanceof \Meeting)) {
            throw new Exception('$event should be instance of Call or Meeting. Get:' . get_class($event));
        }

        $list = array();
        if (!is_array($event->contacts_arr)) {
            $event->contacts_arr = array();
        }

        if (!is_array($event->users_arr)) {
            $event->users_arr = array();
        }

        if (!is_array($event->leads_arr)) {
            $event->leads_arr = array();
        }

        foreach ($event->users_arr as $userId) {
            $notifyUser = BeanFactory::retrieveBean('Users', $userId);
            if (!empty($notifyUser->id)) {
                $notifyUser->new_assigned_user_name = $notifyUser->full_name;
                $GLOBALS['log']->info("Notifications: recipient is $notifyUser->new_assigned_user_name");
                $list[$notifyUser->id] = $notifyUser;
            }
        }

        foreach ($event->contacts_arr as $contactId) {
            $notifyUser = BeanFactory::retrieveBean('Contacts', $contactId);
            if (!empty($notifyUser->id)) {
                $notifyUser->new_assigned_user_name = $notifyUser->full_name;
                $GLOBALS['log']->info("Notifications: recipient is $notifyUser->new_assigned_user_name");
                $list[$notifyUser->id] = $notifyUser;
            }
        }

        foreach ($event->leads_arr as $leadId) {
            $notifyUser = BeanFactory::retrieveBean('Leads', $leadId);
            if (!empty($notifyUser->id)) {
                $notifyUser->new_assigned_user_name = $notifyUser->full_name;
                $GLOBALS['log']->info("Notifications: recipient is $notifyUser->new_assigned_user_name");
                $list[$notifyUser->id] = $notifyUser;
            }
        }

        return $list;
    }
}
