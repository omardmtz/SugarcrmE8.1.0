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

namespace Sugarcrm\Sugarcrm\IdentityProvider\Authentication;

use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\Exception\PermanentLockedUserException;
use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\Exception\TemporaryLockedUserException;

class Lockout
{
    const LOCKOUT_DISABLED = 0;
    const LOCK_TYPE_PERMANENT = 1;
    const LOCK_TYPE_TIME = 2;

    /**
     * password setting config
     * @var array
     */
    protected $config = [];

    /**
     * Is enabled lockout.
     * @return bool
     */
    public function isEnabled()
    {
        return $this->getLockType() != self::LOCKOUT_DISABLED;
    }

    /**
     * Calculation expire time of user.
     * @param User $user
     * @return bool|string
     */
    protected function calculateExpireTime(User $user)
    {
        $logoutTime = $user->getSugarUser()->getPreference('logout_time');
        if (empty($logoutTime)) {
            return false;
        }
        $lockoutDurationMins =
            $this->getConfigValue('lockoutexpirationtime') * $this->getConfigValue('lockoutexpirationtype');
        return $this->getTimeDate()
            ->fromDb($logoutTime)
            ->modify("+$lockoutDurationMins minutes")
            ->asDb();
    }

    /**
     * @param User $user
     * @return TemporaryLockedUserException|PermanentLockedUserException
     */
    public function throwLockoutException(User $user)
    {
        if ($this->getLockType() == self::LOCK_TYPE_TIME) {
            $exception = $this->getTimeLockedException($user);
        } else {
            $exception = $this->getPermanentLockedException();
        }
        $exception->setUser($user);
        throw $exception;
    }

    /**
     * return exception for lock out by time
     * @param User $user
     * @return TemporaryLockedUserException
     */
    protected function getTimeLockedException(User $user)
    {
        $expireTime = $this->calculateExpireTime($user);
        $message = trim($this->getAppString('LBL_LOGIN_ATTEMPTS_OVERRUN'));
        if ($expireTime) {
            $timeLeft = strtotime($expireTime) - strtotime($this->getTimeDate()->nowDb());

            $message .= sprintf(' %s ', trim($this->getAppString('LBL_LOGIN_LOGIN_TIME_ALLOWED')));

            switch (true) {
                case (floor($timeLeft/86400) != 0):
                    $message .= floor($timeLeft/86400) . $this->getAppString('LBL_LOGIN_LOGIN_TIME_DAYS');
                    break;
                case (floor($timeLeft/3600) != 0):
                    $message .= floor($timeLeft/3600) . $this->getAppString('LBL_LOGIN_LOGIN_TIME_HOURS');
                    break;
                case (floor($timeLeft/60) != 0):
                    $message .= floor($timeLeft/60) . $this->getAppString('LBL_LOGIN_LOGIN_TIME_MINUTES');
                    break;
                case (floor($timeLeft) != 0):
                    $message .= floor($timeLeft) . $this->getAppString('LBL_LOGIN_LOGIN_TIME_SECONDS');
                    break;
            }
        }
        return new TemporaryLockedUserException($message);
    }

    /**
     * return exception for permanent lock out
     * @return PermanentLockedUserException
     */
    protected function getPermanentLockedException()
    {
        $exception = new PermanentLockedUserException($this->getAppString('LBL_LOGIN_ATTEMPTS_OVERRUN'));
        $exception->setWaitingErrorMessage($this->getAppString('LBL_LOGIN_ADMIN_CALL'));
        return $exception;
    }

    /**
     * Is user locked?
     * @param User $user
     * @return bool
     */
    public function isUserLocked(User $user)
    {
        $result = false;
        $lockType = $this->getLockType();
        if ($lockType == self::LOCK_TYPE_PERMANENT) {
            $result = $user->getLockout();
        } elseif ($lockType == self::LOCK_TYPE_TIME) {
            $expireTime = $this->calculateExpireTime($user);
            if ($expireTime) {
                $result = strtotime($this->getTimeDate()->nowDb()) < strtotime($expireTime);
            }
        }
        return $result;
    }

    /**
     * Return Lockout Expiration Login from config.
     * @return int
     */
    public function getFailedLoginsCount()
    {
        return (int) $this->getConfigValue('lockoutexpirationlogin', 0);
    }

    /**
     * @return \TimeDate
     */
    public function getTimeDate()
    {
        return \TimeDate::getInstance();
    }

    /**
     * return password settings config value
     * @param $key
     * @param null $default
     * @return int|mixed
     */
    protected function getConfigValue($key, $default = null)
    {
        if (!$this->config) {
            $this->config = \SugarConfig::getInstance()->get('passwordsetting');
        }
        return array_key_exists($key, $this->config) ? $this->config[$key] : $default;
    }

    /**
     * return lockout type
     * @return int
     */
    protected function getLockType()
    {
        return $this->getConfigValue('lockoutexpiration', self::LOCKOUT_DISABLED);
    }

    /**
     * return message from global app strings
     * @param $key
     * @return mixed
     */
    protected function getAppString($key)
    {
        return $GLOBALS['app_strings'][$key];
    }
}
