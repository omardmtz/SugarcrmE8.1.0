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

namespace Sugarcrm\IdentityProvider\Authentication;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * Class User.
 * Represents user instance.
 */
class User implements AdvancedUserInterface
{
    const ROLE_COMMON = 'ROLE_COMMON';

    /**
     * User status
     * @var integer
     */
    const STATUS_ACTIVE = 0;
    const STATUS_INACTIVE = 1;

    /**
     * OpenId Connect standard attributes
     */
    const OIDC_ATTRIBUTES = [
        'given_name',
        'family_name',
        'middle_name',
        'nickname',
        'address',
        'email',
        'phone_number',
    ];

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var array
     */
    private $attributes;

    /**
     * @var User
     * User that corresponds to this one and is located in the local DB with ID and all other local attributes.
     */
    private $localUser;

    /**
     * User Srn in OIDC system
     * @var string
     */
    protected $srn;

    /**
     * User constructor.
     *
     * @param string $username
     * @param string $password
     * @param array $attributes
     */
    public function __construct($username = null, $password = null, array $attributes = [])
    {
        $this->username = trim($username);
        $this->password = $password;
        $this->attributes = $attributes;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return self::getDefaultRoles();
    }

    /**
     * Returns default roles.
     *
     * @return array
     */
    public static function getDefaultRoles()
    {
        return [self::ROLE_COMMON];
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
        $this->password = null;
    }


    /**
     * @inheritDoc
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function isAccountNonLocked()
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function isEnabled()
    {
        return true;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getUsername();
    }

    /**
     * Returns whether an attribute exists.
     * @param string $name The name of the attribute
     * @return bool
     */
    public function hasAttribute($name)
    {
        return isset($this->attributes[$name]);
    }

    /**
     * Returns a specific attribute's value.
     * @param string $name The name of the attribute
     * @return null|mixed
     */
    public function getAttribute($name)
    {
        return isset($this->attributes[$name]) ? $this->attributes[$name] : null;
    }

    /**
     * Returns the complete list of attributes.
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * get oidc attribute
     * @param string $name
     * @return mixed
     */
    public function getOidcAttribute($name)
    {
        $oidcAttributes = $this->getAttribute('attributes') ?: [];
        return isset($oidcAttributes[$name]) ? $oidcAttributes[$name] : null;
    }

    /**
     * Sets a value for the given attribute.
     * @param string $name
     * @param mixed $value
     */
    public function setAttribute($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    /**
     * Removes a given attribute.
     * @param string $name
     */
    public function removeAttribute($name)
    {
        unset($this->attributes[$name]);
    }

    /**
     * Set corresponding local User.
     * @param User $localUser
     */
    public function setLocalUser(User $localUser)
    {
        $this->localUser = $localUser;
    }

    /**
     * Get corresponding local User.
     * If localUser is not set, but current one has an ID - it means it's the localUser itself.
     * @return User
     */
    public function getLocalUser()
    {
        if (!is_null($this->localUser)) {
            return $this->localUser;
        } elseif ($this->hasAttribute('id')) {
            return $this;
        }
        return null;
    }

    /**
     * Set user Srn
     * @param $srn
     */
    public function setSrn($srn)
    {
        $this->srn = $srn;
    }

    /**
     * @return string
     */
    public function getSrn()
    {
        return $this->srn;
    }
}
