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

namespace Sugarcrm\Sugarcrm\IdentityProvider\Authentication\User;

use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\User;
use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\UserProvider\SugarLocalUserProvider;
use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\Lockout;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Ldap\Entry;

/**
 * creates sugar user after success auth if it's required
 */
class LdapUserChecker extends SugarUserChecker
{
    /**
     * @var SugarLocalUserProvider
     */
    protected $localProvider;

    /**
     * @var array
     */
    protected $ldapConfig;

    /**
     * @param Lockout $lockout
     * @param SugarLocalUserProvider $localProvider
     * @param array $ldapConfig
     */
    public function __construct(Lockout $lockout, SugarLocalUserProvider $localProvider, array $ldapConfig = [])
    {
        parent::__construct($lockout);

        $this->localProvider = $localProvider;
        $this->ldapConfig = $ldapConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function checkPostAuth(UserInterface $user)
    {
        $this->loadSugarUser($user);
        parent::checkPostAuth($user);
    }

    /**
     * load sugar user
     * @param User $user
     */
    protected function loadSugarUser(User $user)
    {
        try {
            $sugarUser = $this->localProvider->loadUserByUsername($user->getUsername())->getSugarUser();
        } catch (UsernameNotFoundException $e) {
            if (!empty($this->ldapConfig['autoCreateUser'])) {
                $sugarUser = $this->localProvider->createUser(
                    $user->getUsername(),
                    $this->getMappedValues($user->getAttribute('entry'))
                );
            } else {
                throw $e;
            }
        }
        $user->setSugarUser($sugarUser);
    }

    /**
     * return mango user mapped array from entity attributes
     * @param Entry $entry
     * @return array
     */
    protected function getMappedValues(Entry $entry)
    {
        $result = [];
        if (!empty($this->ldapConfig['user']['mapping']) && is_array($this->ldapConfig['user']['mapping'])) {
            foreach ($this->ldapConfig['user']['mapping'] as $attr => $property) {
                if ($entry->hasAttribute($attr)) {
                    $result[$property] = $entry->getAttribute($attr)[0];
                }
            }
        }

        $fixedAttributes = [
            'employee_status' => User::USER_EMPLOYEE_STATUS_ACTIVE,
            'status' => User::USER_STATUS_ACTIVE,
            'is_admin' => 0,
            'external_auth_only' => 1,
        ];

        $result = array_merge($result, $fixedAttributes);

        return $result;
    }
}
