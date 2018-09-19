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

namespace Sugarcrm\Sugarcrm\Security\Csrf;

use Sugarcrm\Sugarcrm\Session\SessionStorage;
use Symfony\Component\Security\Csrf\Exception\TokenNotFoundException;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

/**
 * Token storage that uses PHP's native session handling. This class
 * assumes the users session is already started and available.
 *
 * Code based on symfony/security-csrf:
 * @see \Symfony\Component\Security\Csrf\TokenStorage\NativeSessionTokenStorage
 */
class CsrfTokenStorage implements TokenStorageInterface
{
    /**
     * The namespace used to store values in the session.
     * @var string
     */
    const SESSION_NAMESPACE = 'csrf_tokens';

    /**
     * @var Sugarcrm\Sugarcrm\Util\Arrays\TrackableArray\TrackableArray
     */
    protected $sessionStore;

    public function __construct(SessionStorage $store) {
        if(!$store->offsetExists(static::SESSION_NAMESPACE)) {
            $store->offsetSet(static::SESSION_NAMESPACE, array());
        }
        $this->sessionStore = $store->offsetGet(static::SESSION_NAMESPACE);
    }

    /**
     * {@inheritdoc}
     */
    public function getToken($tokenId)
    {
        if (!$this->sessionStore->offsetExists($tokenId)) {
            throw new TokenNotFoundException('The CSRF token with ID '.$tokenId.' does not exist.');
        }

        return (string) $this->sessionStore->offsetGet($tokenId);
    }

    /**
     * {@inheritdoc}
     */
    public function setToken($tokenId, $token)
    {
        $this->sessionStore->offsetSet($tokenId, (string) $token);
    }

    /**
     * {@inheritdoc}
     */
    public function hasToken($tokenId)
    {
        return $this->sessionStore->offsetExists($tokenId);
    }

    /**
     * {@inheritdoc}
     */
    public function removeToken($tokenId)
    {
        $token = $this->hasToken($tokenId) ? $this->getToken($tokenId) : null;

        $this->sessionStore->offsetUnset($tokenId);

        return $token;
    }
}
