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

namespace Sugarcrm\IdentityProvider\Authentication\User;

use Sugarcrm\IdentityProvider\Authentication\UserProvider\LocalUserProvider;
use Sugarcrm\IdentityProvider\Authentication\Provider\Providers;

use Symfony\Component\Security\Core\User\UserChecker;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

/**
 * This class performs post authentication checking for LDAP user.
 *
 * @package Sugarcrm\IdentityProvider\Authentication\User
 */
class LDAPUserChecker extends UserChecker
{
    /**
     * @var LocalUserProvider
     */
    protected $localUserProvider;

    /**
     * LDAP provider configuration.
     * @var array
     */
    protected $config;

    public function __construct(LocalUserProvider $localUserProvider, array $config)
    {
        $this->localUserProvider = $localUserProvider;
        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    public function checkPostAuth(UserInterface $user)
    {
        $value = $user->getAttribute('identityValue');
        try {
            $localUser = $this->localUserProvider->loadUserByFieldAndProvider($value, Providers::LDAP);
        } catch (UsernameNotFoundException $e) {
            if (empty($this->config['auto_create_users'])) {
                throw $e;
            }
            $localUser = $this->localUserProvider->createUser(
                $value,
                Providers::LDAP,
                $user->getAttribute('attributes')
            );
        }
        $user->setLocalUser($localUser);

        parent::checkPostAuth($user);
    }
}
