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

class CalendarEventsApi extends ModuleApi
{
    /**
     * @var CalendarEvents
     */
    protected $calendarEvents;

    /**
     * {@inheritDoc}
     */
    public function registerApiRest()
    {
        // Return any API definition that exists for this class
        return array();
    }

    /**
     * Tailor the specification (e.g. path) for the specified module and merge in the API specification passed in
     * @param string $module
     * @param array $childApi defaults to empty array
     * @return array
     */
    protected function getRestApi($module, $childApi = array())
    {
        $calendarEventsApi = array(
            'create' => array(
                'reqType' => 'POST',
                'path' => array($module),
                'pathVars' => array('module'),
                'method' => 'createRecord',
                'shortHelp' => 'This method creates a single event record or a series of event records of the specified type',
                'longHelp' => 'include/api/help/calendar_events_record_create_help.html',
            ),
            'update' => array(
                'reqType' => 'PUT',
                'path' => array($module, '?'),
                'pathVars' => array('module', 'record'),
                'method' => 'updateCalendarEvent',
                'shortHelp' => 'This method updates a single event record or a series of event records of the specified type',
                'longHelp' => 'include/api/help/calendar_events_record_update_help.html',
            ),
            'delete' => array(
                'reqType' => 'DELETE',
                'path' => array($module, '?'),
                'pathVars' => array('module', 'record'),
                'method' => 'deleteCalendarEvent',
                'shortHelp' => 'This method deletes a single event record or a series of event records of the specified type',
                'longHelp' => 'include/api/help/calendar_events_record_delete_help.html',
            ),
        );

        return array_merge($calendarEventsApi, $childApi);
    }

    /**
     * Create either a single event record or a set of recurring events if record is a recurring event
     * @param ServiceBase $api
     * @param array $args API arguments
     * @param array $additionalProperties Additional properties to be set on the bean
     * @return SugarBean
     */
    public function createBean(ServiceBase $api, array $args, array $additionalProperties = array())
    {
        $this->requireArgs($args, array('module'));

        if (empty($args['date_start'])) {
            throw new SugarApiExceptionMissingParameter('Missing parameter: date_start');
        }
        $args = $this->initializeArgs($args, null);
        $this->adjustStartDate($args); // adjust start date as necessary if this is a recurring event

        CalendarEvents::setOldAssignedUser($args['module'], null);

        $bean = parent::createBean($api, $args, $additionalProperties);
        if (!empty($bean->id)) {
            if ($this->shouldAutoInviteParent($bean, $args)) {
                $this->getCalendarEvents()->inviteParent($bean, $args['parent_type'], $args['parent_id']);
            }

            if ($this->getCalendarEvents()->isEventRecurring($bean)) {
                $this->generateRecurringCalendarEvents($bean);
            } else {
                $this->getCalendarEvents()->rebuildFreeBusyCache($GLOBALS['current_user']);
            }
        }

        return $bean;
    }

    /**
     * Updates either a single event record or a set of recurring events based on all_recurrences flag
     * @param ServiceBase $api
     * @param array $args
     * @return array
     */
    public function updateCalendarEvent(ServiceBase $api, array $args)
    {
        $this->requireArgs($args, array('module', 'record'));
        CalendarEvents::setOldAssignedUser($args['module'], $args['record']);

        $api->action = 'view';
        /** @var Call|Meeting $bean */
        $bean = $this->loadBean($api, $args, 'view');

        $args = $this->initializeArgs($args, $bean);

        if ($this->shouldAutoInviteParent($bean, $args)) {
            $this->getCalendarEvents()->inviteParent($bean, $args['parent_type'], $args['parent_id']);
        }

        if ($this->getCalendarEvents()->isEventRecurring($bean)) {
            if (isset($args['all_recurrences']) && $args['all_recurrences'] === 'true') {
                $updateResult = $this->updateRecurringCalendarEvent($bean, $api, $args);
            } else {
                // when updating a single occurrence of a recurring meeting without the
                // `all_recurrences` flag, no updates to recurrence fields are allowed
                $updateResult = $this->updateRecord($api, $this->filterOutRecurrenceFields($args));
                $this->getCalendarEvents()->rebuildFreeBusyCache($GLOBALS['current_user']);
            }
        } else {
            // adjust start date as necessary if being updated to a recurring event
            $this->adjustStartDate($args);
            $updateResult = $this->updateRecord($api, $args);

            // check if it changed from a non-recurring to recurring & generate events if necessary
            $bean = $this->reloadBean($api, $args);
            if ($this->getCalendarEvents()->isEventRecurring($bean)) {
                $this->generateRecurringCalendarEvents($bean);
            } else {
                $this->getCalendarEvents()->rebuildFreeBusyCache($GLOBALS['current_user']);
            }
        }
        return $updateResult;
    }

    /**
     * Deletes either a single event record or a set of recurring events based on all_recurrences flag
     * @param ServiceBase $api
     * @param array $args
     * @return array
     */
    public function deleteCalendarEvent(ServiceBase $api, array $args)
    {
        if (isset($args['all_recurrences']) && $args['all_recurrences'] === 'true') {
            $result = $this->deleteRecordAndRecurrences($api, $args);
        } else {
            $result = $this->deleteRecord($api, $args);
        }
        return $result;
    }

    /**
     * Creates child events in recurring series
     * @param SugarBean $bean
     */
    public function generateRecurringCalendarEvents(SugarBean $bean)
    {
        $this->getCalendarEvents()->saveRecurringEvents($bean);
    }

    /**
     * Re-generates child events in recurring series
     * @param SugarBean $bean
     * @param ServiceBase $api
     * @param array $args
     * @return array
     * @throws SugarApiExceptionInvalidParameter - when updating using the 'all_recurrences' option, the id of the
     *         Parent (root) bean must be provided.
     */
    public function updateRecurringCalendarEvent(SugarBean $bean, ServiceBase $api, array &$args)
    {
        if (!empty($bean->repeat_parent_id) && ($bean->repeat_parent_id !== $bean->id)) {
            throw new SugarApiExceptionInvalidParameter('ERR_CALENDAR_CANNOT_UPDATE_FROM_CHILD');
        }

        $this->adjustStartDate($args); // adjust start date as necessary

        $api->action = 'save';
        $this->updateRecord($api, $args);

        // if event is still recurring after update, save recurring events
        if ($this->getCalendarEvents()->isEventRecurring($bean)) {
            $this->getCalendarEvents()->saveRecurringEvents($bean);
        } else {
            // event is not recurring anymore, delete child instances
            $this->deleteRecurrences($bean);
        }

        return $this->getLoadedAndFormattedBean($api, $args, $bean);
    }

    /**
     * Deletes the parent and associated child events in a series.
     * @param ServiceBase $api
     * @param array $args
     * @return array
     */
    public function deleteRecordAndRecurrences(ServiceBase $api, array $args)
    {
        /** @var Call|Meeting $bean */
        $bean = $this->loadBean($api, $args, 'delete');

        if (!empty($bean->repeat_parent_id)) {
            $parentArgs = array_merge(
                $args,
                array('record' => $bean->repeat_parent_id)
            );

            $bean = $this->loadBean($api, $parentArgs, 'delete');
        }

        // Turn off The Cache Updates while deleting the multiple recurrences.
        // The current Cache Enabled status is returned so it can be appropriately
        // restored when all the recurrences have been deleted.
        $cacheEnabled = vCal::setCacheUpdateEnabled(false);
        $this->deleteRecurrences($bean);
        $bean->mark_deleted($bean->id);
        // Restore the Cache Enabled status to its previous state
        vCal::setCacheUpdateEnabled($cacheEnabled);

        $this->getCalendarEvents()->rebuildFreeBusyCache($GLOBALS['current_user']);

        return array('id' => $bean->id);
    }

    /**
     * Deletes the child recurrences of the given bean
     *
     * @param SugarBean $bean
     */
    public function deleteRecurrences(SugarBean $bean)
    {
        CalendarUtils::markRepeatDeleted($bean);
    }

    /**
     * If the event specifies a recurring series, ensure that the series date_start represents
     * the first date in the series.
     *
     * @param array $args
     * @param SugarBean $bean
     * @return array
     */
    protected function initializeArgs(array $args, SugarBean $bean = null)
    {
        $repeatType = '';
        if (!empty($bean)) {
            $repeatType = empty($bean->repeat_type) ? '' : $bean->repeat_type;
        }
        if (array_key_exists('repeat_type', $args)) {
            $repeatType = empty($args['repeat_type']) ? '' : $args['repeat_type'];
        }

        if (empty($repeatType)) {
            $args['repeat_count'] = 0;
            $args['repeat_until'] = '';
            $args['repeat_interval'] = 0;
            $args['repeat_dow'] = '';
            $args['repeat_selector'] = 'None';
            $args['repeat_days'] = '';
            $args['repeat_ordinal'] = '';
            $args['repeat_unit'] = '';
        } else {
            $repeatCount = 0;
            if (!empty($bean)) {
                $repeatCount = empty($bean->repeat_count) ? 0 : intval($bean->repeat_count);
                if (!empty($args['repeat_until'])) {
                    $repeatCount = 0;
                }
            }
            if (array_key_exists('repeat_count', $args)) {
                $repeatCount = empty($args['repeat_count']) ? 0 : intval($args['repeat_count']);
            }
            $repeatInterval = 0;
            if (!empty($bean)) {
                $repeatInterval = empty($bean->repeat_interval) ? 0 : intval($bean->repeat_interval);
            }
            if (array_key_exists('repeat_interval', $args)) {
                $repeatInterval = empty($args['repeat_interval']) ? 0 : intval($args['repeat_interval']);
            }
            if (empty($repeatInterval)) {
                $repeatInterval = 1;
            }
            if ($repeatCount > 0) {
                $args['repeat_until'] = '';
            }
            if ($repeatType != 'Monthly' && $repeatType != 'Yearly') {
                $args['repeat_selector'] = 'None';
                $args['repeat_days'] = '';
                $args['repeat_ordinal'] = '';
                $args['repeat_unit'] = '';
            }
            if ($repeatType != 'Weekly') {
                $args['repeat_dow'] = '';
            }
            $args['repeat_count'] = $repeatCount;
            $args['repeat_interval'] = $repeatInterval;
        }
        return $args;
    }

    /**
     * If the event specifies a recurring series, ensure that the series date_start represents
     * the first date in the series.
     * @param array $args
     */
    protected function adjustStartDate(array &$args)
    {
        if (!empty($args['repeat_type']) && !empty($args['date_start'])) {
            $sequence = $this->getRecurringSequence($args);
            if (empty($sequence)) {
                throw new SugarApiExceptionMissingParameter('ERR_CALENDAR_NO_EVENTS_GENERATED');
            }
            $firstEventDate = $this->getCalendarEvents()->formatDateTime('datetime', $sequence[0], 'iso');
            $args['date_start'] = $firstEventDate;
        }
    }

    /**
     * Filter out recurrence fields from the API arguments
     *
     * @param array $args
     * @return array
     */
    protected function filterOutRecurrenceFields(array $args)
    {
        $recurrenceFieldBlacklist = array(
            'repeat_type',
            'repeat_interval',
            'repeat_dow',
            'repeat_until',
            'repeat_count',
            'repeat_selector',
            'repeat_days',
            'repeat_ordinal',
            'repeat_unit',
        );
        foreach($recurrenceFieldBlacklist as $fieldName) {
            unset($args[$fieldName]);
        }
        return $args;
    }

    /**
     * Lazily loads CalendarEvents service
     *
     * @return CalendarEvents
     */
    protected function getCalendarEvents()
    {
        if (!$this->calendarEvents) {
            $this->calendarEvents = new CalendarEvents();
        }

        return $this->calendarEvents;
    }

    /**
     * Determine if parent field record should be automatically added as an
     * invitee on the event.
     *
     * On create, happens if parent field is set and auto_invite_parent is not
     * false. On update, happens if parent field is updated and
     * auto_invite_parent is not false.
     *
     * @param SugarBean $bean
     * @param array $args
     * @return bool
     */
    protected function shouldAutoInviteParent(SugarBean $bean, array $args)
    {
        $isUpdate = isset($args['id']);

        // allow auto invite to be turned off with flag on the request
        if (isset($args['auto_invite_parent']) && $args['auto_invite_parent'] === false) {
            return false;
        }

        // if parent field is empty, nothing to auto-invite
        if (empty($args['parent_type']) || empty($args['parent_id'])) {
            return false;
        }

        // if updating and parent field has not changed, no auto-invite
        if ($isUpdate
            && ($bean->parent_type === $args['parent_type'])
            && ($bean->parent_id === $args['parent_id'])
        ) {
            return false;
        }

        return true;
    }

    /**
     * Generate the recurring DateTime sequence for a Recurring Event given the Recurring Parent Bean
     *
     * @param array $args
     * @return array
     */
    protected function getRecurringSequence(array $args)
    {
        $calEvents = $this->getCalendarEvents();

        $dateStart = $calEvents->formatDateTime('datetime', $args['date_start'], 'user');

        $params = array();
        $params['type'] = isset($args['repeat_type']) ? $args['repeat_type'] : '';
        $params['interval'] = isset($args['repeat_interval']) ? $args['repeat_interval'] : '';
        $params['count'] = isset($args['repeat_count']) ? $args['repeat_count'] : '';
        $params['until'] = isset($args['repeat_until']) ? $args['repeat_until'] : '';
        $params['until'] = $calEvents->formatDateTime('date', $params['until'], 'user');
        $params['dow'] = isset($args['repeat_dow']) ? $args['repeat_dow'] : '';

        $params['selector'] = isset($args['repeat_selector']) ? $args['repeat_selector'] : '';
        $params['days'] = isset($args['repeat_days']) ? $args['repeat_days'] : '';
        $params['ordinal'] = isset($args['repeat_ordinal']) ? $args['repeat_ordinal'] : '';
        $params['unit'] = isset($args['repeat_unit']) ? $args['repeat_unit'] : '';

        $repeatDateTimeArray = $calEvents->buildRecurringSequence($dateStart, $params);
        return $repeatDateTimeArray;
    }

}
