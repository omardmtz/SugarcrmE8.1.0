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

use Sugarcrm\IdentityProvider\Authentication\UserProvider\LdapUserProvider;

use Symfony\Component\Ldap\Entry;
use Symfony\Component\Security\Core\User\User as StandardUser;

class SugarLdapUserProvider extends LdapUserProvider
{
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
}
