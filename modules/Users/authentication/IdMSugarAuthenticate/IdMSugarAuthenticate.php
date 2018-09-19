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

use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\Config;

class IdMSugarAuthenticate extends BaseAuthenticate implements LoginInterface
{
    /**
     * Authenticates a user based on the username and password
     * returns true if the user was authenticated false otherwise
     * it also will load the user into current user if he was authenticated
     *
     * @inheritdoc
     */
    public function loginAuthenticate($username, $password, $fallback = false, array $params = [])
    {
        $authManager = $this->getAuthProviderBuilder(new Config(\SugarConfig::getInstance()))->buildAuthProviders();
        $token = $this->getUsernamePasswordTokenFactory($username, $password, $params)
                      ->createLocalAuthenticationToken();
        $token = $authManager->authenticate($token);
        return $token && $token->isAuthenticated();
    }
}
