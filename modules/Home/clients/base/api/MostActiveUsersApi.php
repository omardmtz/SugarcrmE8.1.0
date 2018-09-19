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


class MostActiveUsersApi extends SugarApi
{
    public function registerApiRest()
    {
        return array(
            'mostactiveusers' => array(
                'reqType' => 'GET',
                'path' => array('mostactiveusers'),
                'pathVars' => array(),
                'method' => 'getMostActiveUsers',
                'shortHelp' => 'Returns most active users',
                'longHelp' => 'modules/Home/clients/base/api/help/MostActiveUsersApi.html',
            ),
        );
    }

    /**
     * Returns most active users for last n days
     *
     * @param ServiceBase $api
     * @param array $args
     * @return array
     */
    public function getMostActiveUsers(ServiceBase $api, array $args)
    {
        $days = isset($args['days']) ? (int) $args['days'] : 30;
        $db = DBManagerFactory::getInstance();

        // meetings
        $query = "SELECT meetings.assigned_user_id, count(meetings.id) meetings_count, users.first_name, users.last_name
                FROM meetings, users
                WHERE meetings.assigned_user_id = users.id
                    AND users.deleted = 0
                    AND meetings.status='Held'
                    AND " . $db->convert('meetings.date_modified', 'add_date', array($days, 'DAY')) . " > " . $db->convert('', 'today') . "
                GROUP BY meetings.assigned_user_id, users.first_name, users.last_name
                ORDER BY meetings_count DESC";

        $GLOBALS['log']->debug("Finding most active users for Meetings: ".$query);
        $result = $db->limitQuery($query, 0, 1);
        $meetings = array();
        
        if (false !== $row = $db->fetchByAssoc($result)) {
            if (!empty($row)) {
                $meetings['user_id'] = $row['assigned_user_id'];
                $meetings['count'] = $row['meetings_count'];
                $meetings['first_name'] = $row['first_name'];
                $meetings['last_name'] = $row['last_name'];
            }
        }

        // calls
        $query = "SELECT calls.assigned_user_id, count(calls.id) calls_count, users.first_name, users.last_name
                FROM calls, users
                WHERE calls.assigned_user_id = users.id
                    AND users.deleted = 0
                    AND calls.status='Held'
                    AND " . $db->convert('calls.date_modified', 'add_date', array($days, 'DAY')) . " > " . $db->convert('', 'today') . "
                GROUP BY calls.assigned_user_id, users.first_name, users.last_name
                ORDER BY calls_count DESC";

        $GLOBALS['log']->debug("Finding most active users for Calls: ".$query);
        $result = $db->limitQuery($query, 0, 1);
        $calls = array();

        if (false !== $row = $db->fetchByAssoc($result)) {
            if (!empty($row)) {
                $calls['user_id'] = $row['assigned_user_id'];
                $calls['count'] = $row['calls_count'];
                $calls['first_name'] = $row['first_name'];
                $calls['last_name'] = $row['last_name'];
            }
        }

        // inbound emails
        $query = "SELECT emails.assigned_user_id, count(emails.id) emails_count, users.first_name, users.last_name
                FROM emails, users
                WHERE emails.assigned_user_id = users.id
                    AND users.deleted = 0
                    AND emails.type = 'inbound'
                    AND " . $db->convert('emails.date_entered', 'add_date', array($days, 'DAY')) . " > " . $db->convert('', 'today') . "
                GROUP BY emails.assigned_user_id, users.first_name, users.last_name
                ORDER BY emails_count DESC";

        $GLOBALS['log']->debug("Finding most active users for Inbound Emails: ".$query);
        $result = $db->limitQuery($query, 0, 1);
        $inbounds = array();

        if (false !== $row = $db->fetchByAssoc($result)) {
            if (!empty($row)) {
                $inbounds['user_id'] = $row['assigned_user_id'];
                $inbounds['count'] = $row['emails_count'];
                $inbounds['first_name'] = $row['first_name'];
                $inbounds['last_name'] = $row['last_name'];
            }
        }

        // outbound emails
        $query = "SELECT emails.assigned_user_id, count(emails.id) emails_count, users.first_name, users.last_name
                FROM emails, users
                WHERE emails.assigned_user_id = users.id
                    AND users.deleted = 0
                    AND emails.status='sent'
                    AND emails.type = 'out'
                    AND " . $db->convert('emails.date_entered', 'add_date', array($days, 'DAY')) . " > " . $db->convert('', 'today') . "
                GROUP BY emails.assigned_user_id, users.first_name, users.last_name
                ORDER BY emails_count DESC";

        $GLOBALS['log']->debug("Finding most active users for Outbound Emails: ".$query);
        $result = $db->limitQuery($query, 0, 1);
        $outbounds = array();

        if (false !== $row = $db->fetchByAssoc($result)) {
            if (!empty($row)) {
                $outbounds['user_id'] = $row['assigned_user_id'];
                $outbounds['count'] = $row['emails_count'];
                $outbounds['first_name'] = $row['first_name'];
                $outbounds['last_name'] = $row['last_name'];
            }
        }

        return array(
            'meetings' => $meetings,
            'calls' => $calls,
            'inbound_emails' => $inbounds,
            'outbound_emails' => $outbounds
        );
    }
}
