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

use Sugarcrm\IdentityProvider\Authentication\User as IdmUser;

class User extends IdmUser
{
    const USER_STATUS_ACTIVE = 'Active';
    const USER_STATUS_INACTIVE = 'Inactive';

    const USER_EMPLOYEE_STATUS_ACTIVE = 'Active';
    const USER_EMPLOYEE_STATUS_INACTIVE = 'Inactive';

    // User password generator types
    const PASSWORD_TYPE_SYSTEM = 'syst';
    const PASSWORD_TYPE_USER = 'user';

    // sugar config expiration types
    const PASSWORD_EXPIRATION_TYPE_TIME = 1;
    const PASSWORD_EXPIRATION_TYPE_LOGIN = 2;

    /**
     * @var bool
     */
    protected $isPasswordExpired = false;

    /**
     * @var \User
     */
    protected $sugarUser;

    /**
     * setter for mango base user
     * @param \User $user
     */
    public function setSugarUser(\User $user)
    {
        $this->sugarUser = $user;
    }

    /**
     * getter for mango base user
     * @return \User
     */
    public function getSugarUser()
    {
        return $this->sugarUser;
    }

    /**
     * set password expired
     * @param $isPasswordExpired
     */
    public function setPasswordExpired($isPasswordExpired)
    {
        $this->isPasswordExpired = $isPasswordExpired;
    }

    /**
     * Is credentials non expired?
     * @return boolean
     */
    public function isCredentialsNonExpired()
    {
        return !$this->isPasswordExpired;
    }

    /**
     * return sugar user password's type.
     * @return string
     */
    public function getPasswordType()
    {
        if ($this->sugarUser instanceof \User && !empty($this->sugarUser->system_generated_password)) {
            return self::PASSWORD_TYPE_SYSTEM;
        }
        return self::PASSWORD_TYPE_USER;
    }

    /**
     * return password last change date
     * @return string
     */
    public function getPasswordLastChangeDate()
    {
        return $this->getSugarUser()->pwd_last_changed;
    }

    /**
     * set password last change date
     * @param $date
     */
    public function setPasswordLastChangeDate($date)
    {
        $this->getSugarUser()->pwd_last_changed = $date;
    }

    /**
     * allows to update date_modified property
     * @param boolean $flag
     */
    public function allowUpdateDateModified($flag)
    {
        $this->getSugarUser()->update_date_modified = $flag;
    }

    /**
     * Return valid user login failed.
     * @return int
     */
    public function getLoginFailed()
    {
        return intval($this->getSugarUser()->getPreference('loginfailed'));
    }

    /**
     * Return user lockout.
     * @return bool
     */
    public function getLockout()
    {
        return (bool)$this->getSugarUser()->getPreference('lockout');
    }

    /**
     * Clear lockout state of user.
     */
    public function clearLockout()
    {
        /** @var \User $sugarUser */
        $sugarUser = $this->getSugarUser();
        $sugarUser->setPreference('lockout', '');
        $sugarUser->setPreference('loginfailed', 0);
        $sugarUser->savePreferencesToDB();
    }

    /**
     * Locking user.
     * @param $dateTime
     */
    public function lockout($dateTime)
    {
        /** @var \User $sugarUser */
        $sugarUser = $this->getSugarUser();
        $sugarUser->setPreference('lockout', '1');
        $sugarUser->setPreference('logout_time', $dateTime);
        $sugarUser->setPreference('loginfailed', 0);
        $sugarUser->savePreferencesToDB();
    }

    /**
     * Incrementing Login Failed.
     */
    public function incrementLoginFailed()
    {
        /** @var \User $sugarUser */
        $sugarUser = $this->getSugarUser();
        $sugarUser->setPreference('lockout', '');
        $sugarUser->setPreference('loginfailed', $this->getLoginFailed() + 1);
        $sugarUser->savePreferencesToDB();
    }

    public function hasAttribute($name)
    {
        if ($name == 'email') {
            return true;
        }
        return isset($this->sugarUser->$name) || parent::hasAttribute($name);
    }

    public function getAttribute($name)
    {
        if ($name == 'email') {
            return $this->sugarUser->emailAddress->getPrimaryAddress($this->sugarUser);
        }
        $value = parent::getAttribute($name);
        if (!is_null($value)) {
            return $value;
        } elseif ($this->sugarUser instanceof \User && isset($this->sugarUser->$name)) {
            return $this->sugarUser->$name;
        } else {
            return null;
        }
    }
}
