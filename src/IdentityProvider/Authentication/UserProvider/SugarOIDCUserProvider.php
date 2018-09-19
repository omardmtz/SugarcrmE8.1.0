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

namespace Sugarcrm\Sugarcrm\IdentityProvider\Authentication\UserProvider;

use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class SugarOIDCUserProvider implements UserProviderInterface
{
    /**
     * @var SugarLocalUserProvider
     */
    protected $sugarLocalUserProvider;
    /**
     * SugarOIDCUserProvider constructor.
     * @param UserProviderInterface $sugarLocalUserProvider
     */
    public function __construct(UserProviderInterface $sugarLocalUserProvider)
    {
        $this->sugarLocalUserProvider = $sugarLocalUserProvider;
    }

    /**
     * @param string $username
     * @return User
     */
    public function loadUserByUsername($username)
    {
        return new User($username);
    }

    /**
     * @param string $srn
     * @return User
     */
    public function loadUserBySrn($srn)
    {
        $user = new User(null, null);
        $user->setSrn($srn);
        return $user;
    }

    /**
     * Get user by field value.
     *
     * @param string $value
     * @param string $field
     * @return User
     */
    public function loadUserByField($value, $field)
    {
        return $this->sugarLocalUserProvider->loadUserByField($value, $field);
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user)
    {
        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        return $class === User::class;
    }
}
