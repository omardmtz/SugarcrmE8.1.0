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

namespace Sugarcrm\Sugarcrm\IdentityProvider\Authentication\Subscriber;

use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\Lockout;
use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\User;
use Sugarcrm\Sugarcrm\Logger\Factory as LoggerFactory;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\Security\Core\Event\AuthenticationEvent;
use Symfony\Component\Security\Core\User\UserProviderInterface;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

/**
 * example subscriber
 */
class SugarOnAuthLockoutSubscriber implements EventSubscriberInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var Lockout
     */
    protected $lockout;

    /**
     * @var UserProviderInterface
     */
    protected $userProvider;

    /**
     * @param Lockout $lockout
     * @param UserProviderInterface $userProvider
     */
    public function __construct(Lockout $lockout, UserProviderInterface $userProvider)
    {
        $this->lockout = $lockout;
        $this->userProvider = $userProvider;
        $this->setLogger(LoggerFactory::getLogger('authentication'));
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            AuthenticationEvents::AUTHENTICATION_SUCCESS => 'onSuccess',
            AuthenticationEvents::AUTHENTICATION_FAILURE => 'onFailure',
        ];
    }

    /**
     * runs on success
     * @param AuthenticationEvent $event
     */
    public function onSuccess(AuthenticationEvent $event)
    {
        if (!$this->lockout->isEnabled()) {
            return;
        }

        /** @var User $user */
        $user = $event->getAuthenticationToken()->getUser();
        if ($user->getLoginFailed() || $user->getLockout()) {
            $user->clearLockout();
        }

        return;
    }

    /**
     * runs on failure
     * @param AuthenticationEvent $event
     */
    public function onFailure(AuthenticationEvent $event)
    {
        $username = $event->getAuthenticationToken()->getUsername();
        /** @var User $user */
        $user = $this->userProvider->loadUserByUsername($username);
        if ($user) {
            $user->incrementLoginFailed();
            $this->logger->fatal('FAILED LOGIN:attempts[' . $user->getLoginFailed() .'] - '. $username);
        } else {
            $this->logger->fatal('FAILED LOGIN: ' . $username);
        }

        if (!$this->lockout->isEnabled()) {
            return;
        }

        if (!$user) {
            return;
        }

        if ($user->getLoginFailed() >= $this->lockout->getFailedLoginsCount()) {
            $user->lockout($this->lockout->getTimeDate()->nowDb());
        }
        return;
    }
}
