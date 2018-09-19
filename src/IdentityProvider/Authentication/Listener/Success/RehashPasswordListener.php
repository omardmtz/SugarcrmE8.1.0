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

namespace Sugarcrm\Sugarcrm\IdentityProvider\Authentication\Listener\Success;

use Symfony\Component\Security\Core\Event\AuthenticationEvent;

/**
 * TODO delete this when strtolower+md5 encrypt will be deleted
 */
class RehashPasswordListener
{
    /**
     * rehash encrypted user's password on success auth
     * @param AuthenticationEvent $event
     */
    public function execute(AuthenticationEvent $event)
    {
        $token = $event->getAuthenticationToken();
        if ($token->hasAttribute('isPasswordEncrypted') && !$token->getAttribute('isPasswordEncrypted')) {
            $token->getUser()->getSugarUser()->rehashPassword($token->getAttribute('rawPassword'));
        }
    }
}
