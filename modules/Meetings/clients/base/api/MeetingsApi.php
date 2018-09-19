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

class MeetingsApi extends CalendarEventsApi
{
    /**
     * {@inheritdoc}
     */
    public function registerApiRest()
    {
        $register = array(
            'getAgenda' => array(
                'reqType' => 'GET',
                'path' => array('Meetings', 'Agenda'),
                'pathVars' => array('', ''),
                'method' => 'getAgenda',
                'shortHelp' => 'Fetch an agenda for a user',
                'longHelp' => 'include/api/html/meetings_agenda_get_help',
            ),
            'external' => array(
                'reqType' => 'GET',
                'path' => array('Meetings', '?', 'external'),
                'pathVars' => array('module', 'record', 'external'),
                'method' => 'getExternalInfo',
                'shortHelp' => 'This method retrieves info about launching an external meeting',
                'longHelp' => 'modules/Meetings/clients/base/api/help/MeetingsApiExternalGet.html',
            ),
        );

        return parent::getRestApi("Meetings", $register);
    }

    public function getAgenda(ServiceBase $api, array $args)
    {
        // Fetch the next 14 days worth of meetings (limited to 20)
        $end_time = new SugarDateTime("+14 days");
        $start_time = new SugarDateTime("-1 hour");


        $meeting = BeanFactory::newBean('Meetings');
        $meetingList = $meeting->get_list('date_start', "date_start > " . $GLOBALS['db']->convert($GLOBALS['db']->quoted($start_time->asDb()), 'datetime') . " AND date_start < " . $GLOBALS['db']->convert($GLOBALS['db']->quoted($end_time->asDb()), 'datetime'));

        // Setup the breaks for the various time periods
        $datetime = new SugarDateTime();
        $today_stamp = $datetime->get_day_end()->getTimestamp();
        $tomorrow_stamp = $datetime->setDate($datetime->year,$datetime->month,$datetime->day+1)->get_day_end()->getTimestamp();


        $timeDate = TimeDate::getInstance();

        $returnedMeetings = array('today'=>array(),'tomorrow'=>array(),'upcoming'=>array());
        foreach ( $meetingList['list'] as $meetingBean ) {
            $meetingStamp = $timeDate->fromDb($meetingBean->date_start)->getTimestamp();
            $meetingData = $this->formatBean($api,$args,$meetingBean);

            if ( $meetingStamp < $today_stamp ) {
                $returnedMeetings['today'][] = $meetingData;
            } else if ( $meetingStamp < $tomorrow_stamp ) {
                $returnedMeetings['tomorrow'][] = $meetingData;
            } else {
                $returnedMeetings['upcoming'][] = $meetingData;
            }
        }

        return $returnedMeetings;
    }

    /**
     * Gets the host/join information about an external meeting
     *
     * @param ServiceBase $api
     * @param array $args
     * @return array
     */
    public function getExternalInfo(ServiceBase $api, array $args)
    {
        $module = $args['module'];
        $meetingBean = BeanFactory::getBean($module, $args['record']);

        $isHostOptionAllowed = (
            $meetingBean->assigned_user_id == $api->user->id ||
            $api->user->isAdmin() ||
            $api->user->isDeveloperForModule($module)
        );

        $isJoinOptionAllowed = (
            $isHostOptionAllowed ||
            $this->isUserInvitedToMeeting($api->user->id, $meetingBean)
        );

        return array(
            'is_host_option_allowed' => $isHostOptionAllowed,
            'host_url' => $isHostOptionAllowed ? $meetingBean->host_url : '',
            'is_join_option_allowed' => $isJoinOptionAllowed,
            'join_url' => $isJoinOptionAllowed ? $meetingBean->join_url : '',
        );
    }

    /**
     * Checks to see if the given user is an invitee on the meeting
     *
     * @param $userId
     * @param $meetingBean
     * @return bool
     */
    protected function isUserInvitedToMeeting($userId, $meetingBean)
    {
        $query = new SugarQuery();
        $query->select(array('id'));
        $query->from($meetingBean);
        $query->join('users', array('alias' => 'users'));
        $query->where()->equals('meetings.id', $meetingBean->id)
            ->equals('users.id', $userId);
        $results = $query->execute();
        return count($results) > 0;
    }
}
