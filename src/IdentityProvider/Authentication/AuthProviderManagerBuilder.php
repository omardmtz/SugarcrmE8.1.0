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

use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\Listener\Success\LoadUserOnSessionListener;
use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\Listener\Success\RehashPasswordListener;
use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\Listener\Success\UpdateUserLastLoginListener;
use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\Listener\Success\PostLoginAuthListener;
use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\Listener\Success\UserPasswordListener;
use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\Subscriber\SugarOnAuthLockoutSubscriber;
use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\UserProvider\SugarLocalUserProvider;

use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class AuthProviderManagerBuilder extends AuthProviderBasicManagerBuilder
{
    /**
     * @return EventDispatcherInterface
     */
    protected function getAuthenticationEventDispatcher()
    {
        $dispatcher = new EventDispatcher();
        $dispatcher->addListener(
            AuthenticationEvents::AUTHENTICATION_SUCCESS,
            [new LoadUserOnSessionListener(), 'execute']
        );
        $dispatcher->addListener(
            AuthenticationEvents::AUTHENTICATION_SUCCESS,
            [new RehashPasswordListener(), 'execute']
        );
        $dispatcher->addListener(
            AuthenticationEvents::AUTHENTICATION_SUCCESS,
            [new UserPasswordListener(), 'execute']
        );
        $dispatcher->addListener(
            AuthenticationEvents::AUTHENTICATION_SUCCESS,
            [new UpdateUserLastLoginListener(), 'execute']
        );
        $dispatcher->addListener(
            AuthenticationEvents::AUTHENTICATION_SUCCESS,
            [new PostLoginAuthListener(), 'execute']
        );

        $dispatcher->addSubscriber(new SugarOnAuthLockoutSubscriber(new Lockout(), new SugarLocalUserProvider()));
        return $dispatcher;
    }
}
