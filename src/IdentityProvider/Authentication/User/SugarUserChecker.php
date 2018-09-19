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
use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\Lockout;

use Symfony\Component\Security\Core\User\UserChecker;
use Symfony\Component\Security\Core\User\UserInterface;

class SugarUserChecker extends UserChecker
{
    /**
     * @var Lockout
     */
    protected $lockout;

    /**
     * @inheritDoc
     * @param Lockout $lockout
     */
    public function __construct(Lockout $lockout)
    {
        $this->lockout = $lockout;
    }

    /**
     * {@inheritdoc}
     */
    public function checkPreAuth(UserInterface $user)
    {
        parent::checkPreAuth($user);

        if ($user instanceof User && $this->lockout->isEnabled() && $this->lockout->isUserLocked($user)) {
            $this->lockout->throwLockoutException($user);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function checkPostAuth(UserInterface $user)
    {
        /**
         * All password expiration requests are processed in Mango after login
         * Disable IdM auth password expire check by default
         * @see \Sugarcrm\Sugarcrm\IdentityProvider\Authentication\Listener\Success\UserPasswordListener
         * @var User $user
         */
        $user->setPasswordExpired(false);
        parent::checkPostAuth($user);
    }
}
