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

/**
 * IdM LDAP login
 */
class IdMLDAPAuthenticate extends BaseAuthenticate implements LoginInterface
{
    /**
     * AuthN user over ldap service.
     *
     * @inheritdoc
     */
    public function loginAuthenticate($username, $password, $fallback = false, array $params = [])
    {
        $authManager = $this->getAuthProviderBuilder(new Config(\SugarConfig::getInstance()))->buildAuthProviders();
        $tokenFactory = $this->getUsernamePasswordTokenFactory($username, $password, $params);
        $ldapToken = $tokenFactory->createLdapAuthenticationToken();
        $localToken = $tokenFactory->createLocalAuthenticationToken();

        $mixedToken = $tokenFactory->createMixedAuthenticationToken();
        $mixedToken->addToken($ldapToken);
        $mixedToken->addToken($localToken);

        $token = $authManager->authenticate($mixedToken);
        return $token && $token->isAuthenticated();
    }
}
