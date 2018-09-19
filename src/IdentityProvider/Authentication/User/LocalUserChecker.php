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

use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\Exception\ExternalAuthUserException;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Checks User local authentication regarding Sugar-specific business logic.
 * Does not include logic for common checks that encompass Local, Ldap, SAML; but only specific to the Local auth.
 *
 * Class LocalUserChecker
 * @package Sugarcrm\Sugarcrm\IdentityProvider\Authentication\User
 */
class LocalUserChecker extends SugarUserChecker
{
    /**
     * @inheritdoc
     *
     * @throws ExternalAuthUserException
     */
    public function checkPreAuth(UserInterface $user)
    {
        parent::checkPreAuth($user);

        // There is a checkbox in User's profile - External (LDAP, SAML) auth only.
        // If it's on, User is not allowed to use local Sugar authentication, but only an external one.
        if (!empty($user->getSugarUser()->external_auth_only)) {
            throw new ExternalAuthUserException(
                'External auth only user is not allowed to login using Sugar credentials.'
            );
        }
    }
}
