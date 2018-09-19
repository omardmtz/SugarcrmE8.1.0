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
 * Session handler for Sugar
 */
class SugarSessionHandler extends SessionHandler
{
    /**
     * Session start time
     */
    protected $session_start = null;

    /**
     * Total session time
     */
    protected $session_time = null;

    /**
     * Maximum session time
     */
    protected $max_session_time = null;

    /**
     * Log management
     * @var LoggerManager
     */
    protected $log;

    /**
     * Ctor
     */
    public function __construct()
    {
        $this->max_session_time = SugarConfig::getInstance()->get('max_session_time');
        $this->log = LoggerManager::getLogger('SugarCRM');
    }

    /**
     * {@inheritdoc}
     */
    public function open($save_path, $session_id)
    {
        $result = parent::open($save_path, $session_id);

        if ($result) {
            $this->session_start = time();
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        if ($this->isCurrentSessionExceeded() && basename($_SERVER['SCRIPT_NAME']) !== 'cron.php') {
            global $current_user;

            $id = "unknown";
            if (!empty($current_user)) {
                $id = $current_user->id;
            }

            $vars = array(
                'SERVER_NAME',
                'SERVER_ADDR',
                'SCRIPT_FILENAME',
                'REQUEST_METHOD',
                'SCRIPT_NAME',
                'REQUEST_URI',
                'QUERY_STRING',
            );

            $details = array();
            foreach ($vars as $var) {
                $value = isset($_SERVER[$var])
                    ? $_SERVER[$var] : 'Not set';
                $details[] = $var . ': ' . $value;
            }

            $this->log->fatal(sprintf(
                '[SessionLock] Session lock for user id %s was held for %d seconds which is longer than the maximum of %d seconds.'
                . ' Request details: %s',
                $id,
                $this->session_time,
                $this->max_session_time,
                implode(', ', $details)
            ));
        }

        return parent::close();
    }

    /**
     * Calculate session time
     * @return int|false
     */
    protected function getCurrentSessionTime()
    {
        if (!$this->session_start) {
            return false;
        }

        return time() - $this->session_start;
    }

    /**
     * Check if session time more than defined treshhold
     * @return bool
     */
    protected function isCurrentSessionExceeded()
    {
        $this->session_time = $this->getCurrentSessionTime();

        if ($this->max_session_time && $this->session_time) {
            return $this->session_time > $this->max_session_time;
        }

        return false;
    }
}
