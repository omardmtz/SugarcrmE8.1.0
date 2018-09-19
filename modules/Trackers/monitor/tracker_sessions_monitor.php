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

class tracker_sessions_monitor extends Monitor
{
    public function __construct($name = '', $monitorId = '', $metadata = '', $store = '')
    {
        parent::__construct($name, $monitorId, $metadata, $store);

        $this->setValue('session_id', $this->getSessionId());

        $this->populateMonitor();
    }

    public function closeSession()
    {
        $this->setValue('date_end', TimeDate::getInstance()->nowDb());
        $seconds = strtotime($this->date_end) - strtotime($this->date_start);
        $this->setValue('seconds', $seconds);
        $this->setValue('active', 0);
    }

    private function populateMonitor()
    {
        $db = DBManagerFactory::getInstance();
        $query = "SELECT date_start, active
                    FROM $this->name
                    WHERE session_id = '" . $db->quote($this->session_id) . "'
                    AND deleted = 0";
        $result = $db->query($query);

        if (isset($_SERVER['REMOTE_ADDR'])) {
            $this->setValue('client_ip', $_SERVER['REMOTE_ADDR']);
        }

        $this->setValue('user_id', $GLOBALS['current_user']->id);

        $this->new = true;

        if (($row = $db->fetchByAssoc($result))) {
            $this->setValue('date_start', $row['date_start']);
            $this->new = false;
        } else {
            $this->setValue('date_start', TimeDate::getInstance()->nowDb());
        }

        $this->setValue('seconds', 0);
        $this->setValue('active', 1);
    }

    private function getSessionId()
    {
        // Make sure we have the session
        if (session_id() === '') {
            session_start();
        }

        $sessionId = session_id();
        if (!empty($sessionId) && strlen($sessionId) > MAX_SESSION_LENGTH) {
            $sessionId = md5($sessionId);
        }

        return $sessionId;
    }
}
