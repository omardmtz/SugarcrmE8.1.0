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

namespace Sugarcrm\IdentityProvider\Authentication\UserProvider;

use Sugarcrm\IdentityProvider\Authentication\User;
use Symfony\Component\Ldap\Exception\ConnectionException;
use Symfony\Component\Ldap\LdapInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\InvalidArgumentException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\User as StandardUser;
use Symfony\Component\Ldap\Entry;
use Symfony\Component\Security\Core\User\LdapUserProvider as StandardLdapUserProvider;

/**
 * LdapUserProvider is a simple user provider on top of ldap.
 */
class LdapUserProvider extends StandardLdapUserProvider
{
    protected $ldap;
    protected $baseDn;
    protected $searchDn;
    protected $searchPassword;
    protected $defaultRoles;
    protected $uidKey;
    protected $defaultSearch;
    protected $passwordAttribute;

    public function __construct(
        LdapInterface $ldap,
        $baseDn,
        $searchDn = null,
        $searchPassword = null,
        array $defaultRoles = [],
        $uidKey = 'sAMAccountName',
        $filter = '({uid_key}={username})',
        $passwordAttribute = null
    ) {
        parent::__construct(
            $ldap,
            $baseDn,
            $searchDn,
            $searchPassword,
            $defaultRoles,
            $uidKey,
            $filter,
            $passwordAttribute
        );

        if (null === $uidKey) {
            $uidKey = 'sAMAccountName';
        }

        $this->ldap = $ldap;
        $this->baseDn = $baseDn;
        $this->searchDn = $searchDn;
        $this->searchPassword = $searchPassword;
        $this->defaultRoles = $defaultRoles;
        $this->uidKey = $uidKey;
        $this->defaultSearch = str_replace('{uid_key}', $uidKey, $filter);
        $this->passwordAttribute = $passwordAttribute;
    }

    /**
     * Loads a user from an LDAP entry. Saves LDAP entry as User attribute for further using.
     *
     * @param string $username
     * @param Entry $entry
     *
     * @return User
     */
    protected function loadUser($username, Entry $entry)
    {
        /* @var StandardUser $standardUser */
        $standardUser = parent::loadUser($username, $entry);

        return new User(
            $standardUser->getUsername(),
            $standardUser->getPassword(),
            ['entry' => $entry]
        );
    }

    /**
     * Loads the user for the given username from token.
     * Used token username and password for binding.
     *
     * This method must throw UsernameNotFoundException if the user is not
     * found.
     * @param TokenInterface $token
     * @return User
     * @throws \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function loadUserByToken(TokenInterface $token)
    {
        $username = $token->getUsername();
        $password = $token->getCredentials();
        try {
            $this->ldap->bind($username, $password);
            $username = $this->ldap->escape($username, '', LdapInterface::ESCAPE_FILTER);
            $query = str_replace('{username}', $username, $this->defaultSearch);
            $search = $this->ldap->query($this->baseDn, $query);
        } catch (ConnectionException $e) {
            throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username), 0, $e);
        }

        $entries = $search->execute();
        $count = count($entries);

        if (!$count) {
            throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username));
        }

        if ($count > 1) {
            throw new UsernameNotFoundException('More than one user found');
        }

        $entry = $entries[0];

        try {
            if (null !== $this->uidKey) {
                $username = $this->getAttributeValue($entry, $this->uidKey);
            }
        } catch (InvalidArgumentException $e) {
        }

        return $this->loadUser($username, $entry);
    }

    /**
     * Fetches a required unique attribute value from an LDAP entry.
     *
     * @param null|Entry $entry
     * @param string $attribute
     *
     * @throws \Symfony\Component\Security\Core\Exception\InvalidArgumentException
     */
    protected function getAttributeValue(Entry $entry, $attribute)
    {
        if (!$entry->hasAttribute($attribute)) {
            throw new InvalidArgumentException(
                sprintf('Missing attribute "%s" for user "%s".', $attribute, $entry->getDn())
            );
        }

        $values = $entry->getAttribute($attribute);

        if (count($values) != 1) {
            throw new InvalidArgumentException(sprintf('Attribute "%s" has multiple values.', $attribute));
        }

        return $values[0];
    }
}
