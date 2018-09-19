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
 * Class OIDCSessionListener
 * Provides $_SESSION propagation for OIDC user
 */
class OIDCSessionListener
{
    /**
     * Creates or restore OIDC user session.
     *
     * @param AuthenticationEvent $event
     */
    public function execute(AuthenticationEvent $event)
    {
        $token = $event->getAuthenticationToken();
        $user = $token->getUser();

        $sugarUser = $user->getSugarUser();
        $sugarConfig = $this->getSugarConfig();
        $sessionId = hash('sha256', $token->getCredentials());

        if (session_id() != $sessionId) {
            if (session_id()) {
                session_write_close();
            }
            ini_set("session.use_cookies", false);
            session_id($sessionId);
            session_start();
        }

        if (empty($_SESSION)) {
            $_SESSION['externalLogin'] = true;
            $_SESSION['is_valid_session'] = true;
            $_SESSION['ip_address'] = query_client_ip();
            $_SESSION['user_id'] = $sugarUser->id;
            $_SESSION['type'] = 'user';
            $_SESSION['authenticated_user_id'] = $sugarUser->id;
            $_SESSION['unique_key'] = $sugarConfig->get('unique_key');
            $_SESSION['platform'] = $token->getAttribute('platform');
        }
    }

    /**
     * Gets SugarCRM config.
     *
     * @return null|\SugarConfig
     */
    protected function getSugarConfig()
    {
        return \SugarConfig::getInstance();
    }
}
