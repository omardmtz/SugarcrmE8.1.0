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

namespace Sugarcrm\IdentityProvider\Authentication\Provider;

use Sugarcrm\IdentityProvider\Authentication\Exception\ConfigurationException;
use Sugarcrm\IdentityProvider\Authentication\Exception\RuntimeException;
use Sugarcrm\IdentityProvider\Authentication\User;
use Sugarcrm\IdentityProvider\Authentication\UserProvider\LdapUserProvider;
use Sugarcrm\IdentityProvider\Authentication\UserMapping\MappingInterface;

use Symfony\Component\Ldap\Entry;
use Symfony\Component\Ldap\Exception\LdapException;
use Symfony\Component\Ldap\LdapInterface;
use Symfony\Component\Security\Core\Authentication\Provider\LdapBindAuthenticationProvider;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Ldap\Exception\ConnectionException;

class LdapAuthenticationProvider extends LdapBindAuthenticationProvider
{
    /**
     * Local reference to ldap object.
     * @var LdapInterface
     */
    protected $ldap;

    /**
     * app ldap config
     * @var array
     */
    protected $ldapConfig;

    /**
     * @var string
     */
    protected $entryAttribute;

    /**
     * @var string
     */
    protected $dnString;

    /**
     * @var UserProviderInterface | LdapUserProvider
     */
    protected $userProvider;

    /**
     * @var MappingInterface
     */
    protected $mapper;

    /**
     * Constructor.
     *
     * @param UserProviderInterface $userProvider A UserProvider
     * @param UserCheckerInterface $userChecker A UserChecker
     * @param string $providerKey The provider key
     * @param LdapInterface $ldap A Ldap client
     * @param MappingInterface $mapper A Mapping service to map User attributes between LDAP and Local
     * @param string|null $dnString A string used to create the bind DN
     * @param bool $hideUserNotFoundExceptions Whether to hide user not found exception or not
     * @param array $config ldap config settings
     */
    public function __construct(
        UserProviderInterface $userProvider,
        UserCheckerInterface $userChecker,
        $providerKey,
        LdapInterface $ldap,
        MappingInterface $mapper,
        $dnString = '{username}',
        $hideUserNotFoundExceptions = true,
        array $config = []
    ) {
        parent::__construct($userProvider, $userChecker, $providerKey, $ldap, $dnString, $hideUserNotFoundExceptions);

        // we need ldap instance to cover non-standard cases
        $this->ldap = $ldap;
        $this->mapper = $mapper;
        // require config for ldap group check
        $this->ldapConfig = $config;
        $this->entryAttribute = !empty($config['entryAttribute']) ? $config['entryAttribute'] : null;
        // save copy of $dnString for further using
        $this->dnString = $dnString;
        $this->userProvider = $userProvider;
    }

    /**
     * Try to bind by user credentials if admins is empty
     * @inheritdoc
     */
    protected function retrieveUser($username, UsernamePasswordToken $token)
    {
        if (!empty($this->ldapConfig['searchDn'])) {
            $user = parent::retrieveUser($username, $token);
        } else {
            $user = $this->userProvider->loadUserByToken($token);
        }
        return $user;
    }

    /**
     * Does additional checks on the user and token (like validating the credentials).
     *
     * @param UserInterface $user The retrieved UserInterface instance
     * @param UsernamePasswordToken $token The UsernamePasswordToken token to be authenticated
     *
     * @throws AuthenticationException if the credentials could not be validated
     * @throws RuntimeException if requirement data is not accessible
     */
    protected function checkAuthentication(UserInterface $user, UsernamePasswordToken $token)
    {
        /** @var User $user */
        $entry = $user->getAttribute('entry');
        if (empty($entry) || !($entry instanceof Entry)) {
            throw new RuntimeException('A valid Ldap Entry expected in User attributes');
        }

        $useDnAttribute = empty($this->entryAttribute) || strtolower($this->entryAttribute) == 'dn';

        if ($useDnAttribute) {
            $username = $entry->getDn();
        } elseif (!empty($this->entryAttribute)) {
            $username = $this->getLdapEntryAttribute($entry, $this->entryAttribute);
            if (is_array($username)) {
                $username = reset($username);
            }
            if (empty($username)) {
                throw new RuntimeException("Cannot get attribute '{$this->entryAttribute}' from Ldap Entry");
            }
        } else {
            $username = $token->getUsername();
        }

        $password = $token->getCredentials();

        if ('' === $password) {
            throw new BadCredentialsException('The presented password must not be empty.');
        }

        if ($useDnAttribute) {
            $dn = $username;
        } elseif (!empty($this->entryAttribute)) {
            $username = $this->ldap->escape($username, '', LdapInterface::ESCAPE_DN);
            if (!empty($this->dnString)) {
                $dn = str_replace('{username}', $username, $this->dnString);
            } else {
                $dn = $username;
            }
        }
        if (empty($dn)) {
            throw new BadCredentialsException('Cannot get a valid DN from Ldap Entry');
        }

        try {
            $this->ldap->bind($dn, $password);
        } catch (ConnectionException $e) {
            throw new BadCredentialsException('The presented password is invalid.', 0, $e);
        }
        if (!empty($this->ldapConfig['groupMembership'])) {
            $this->groupCheck($user);
        }

        $identityMap = $this->mapper->mapIdentity($token);
        $user->setAttribute('identityField', $identityMap['field']);
        $user->setAttribute('identityValue', $identityMap['value']);
        $user->setAttribute('attributes', $this->mapper->map($entry));
    }

    /**
     * Gets attribute by name in case-insensitive mode.
     *
     * @param Entry $entry
     * @param $name
     * @return string | null
     */
    protected function getLdapEntryAttribute(Entry $entry, $name)
    {
        $attributes = array_change_key_case($entry->getAttributes(), CASE_LOWER);
        $name = strtolower($name);
        return isset($attributes[$name]) ? $attributes[$name] : null;
    }

    /**
     * If groupMembership set to true, after successful authentication
     * it performs checking that user is member of concrete group.
     *
     * If groupMembership set to false, no checking against user group membership will be done.
     *
     * @param User $user
     * @throws AuthenticationException
     */
    protected function groupCheck(User $user)
    {
        try {
            $groupDnString = $this->buildGroupDnString();
            $userSearchString = $this->buildUserSearchString($user);
            $query = $this->ldap->query($groupDnString, $userSearchString);
            $entries = $query->execute();
            if ($entries->count() === 0) {
                throw new AuthenticationException('LDAP user does not belong to group specified');
            }
        } catch (ConfigurationException $e) {
            throw new AuthenticationException($e->getMessage());
        } catch (LdapException $e) {
            throw new AuthenticationException('Could not find group. Reason: ' . $e->getMessage());
        }
    }

    /**
     * Builds group DN string based in configuration provided.
     *
     * @return string
     *
     * @throws ConfigurationException When groupName or groupDn are empty.
     */
    protected function buildGroupDnString()
    {
        if (empty($this->ldapConfig['groupDn'])) {
            throw new ConfigurationException('LDAP config option groupDn must not be empty');
        }

        return $this->ldapConfig['groupDn'];
    }

    /**
     * Build user search filter based on $user and configuration provided.
     *
     * @param User $user
     * @return string
     *
     * @throws ConfigurationException
     */
    protected function buildUserSearchString(User $user)
    {
        if (empty($this->ldapConfig['groupAttribute'])) {
            throw new ConfigurationException('LDAP groupAttribute must not be empty');
        }

        if (empty($this->ldapConfig['baseDn'])) {
            throw new ConfigurationException('LDAP baseDn must not be empty');
        }

        $groupAttribute = $this->ldapConfig['groupAttribute'];
        $userUniqueAttribute = null;
        if (!empty($this->ldapConfig['userUniqueAttribute']) &&
            strtolower($this->ldapConfig['userUniqueAttribute']) != 'dn'
        ) {
            $userUniqueAttribute = $this->ldapConfig['userUniqueAttribute'];
        }

        $entry = $user->getAttribute('entry');
        if ($userUniqueAttribute === null) {
            $userUniqueValue = $entry->getDn();
        } else {
            $userUniqueValue = $this->getLdapEntryAttribute($entry, $userUniqueAttribute);
        }
        if (is_array($userUniqueValue)) {
            $userUniqueValue = $userUniqueValue[0];
        }
        $userUniqueValue = $this->ldap->escape($userUniqueValue, '', LdapInterface::ESCAPE_DN);
        $baseDn = $this->ldapConfig['baseDn'];

        if (array_key_exists('includeUserDN', $this->ldapConfig) && $this->ldapConfig['includeUserDN'] === true) {
            return "($groupAttribute=" . $userUniqueAttribute . "=$userUniqueValue,$baseDn)";
        }

        return "($groupAttribute=$userUniqueValue)";
    }
}
