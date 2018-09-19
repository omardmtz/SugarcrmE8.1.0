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
use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\Token\UsernamePasswordTokenFactory;
use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\AuthProviderBasicManagerBuilder;

/**
 * Class OAuth2Authenticate
 */

class OAuth2Authenticate extends BaseAuthenticate implements SugarAuthenticateExternal
{
    /**
     * {@inheritdoc}
     */
    public function getLoginUrl($returnQueryVars = [])
    {
        $config = new Config(\SugarConfig::getInstance());
        $idmModeConfig = $config->getIDMModeConfig();
        if (isset($idmModeConfig['stsUrl'])) {
            return $idmModeConfig['stsUrl'];
        }

        throw new \RuntimeException('IDM-mode config and URL were not found.');
    }

    /**
     * {@inheritdoc}
     */
    public function getLogoutUrl()
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function loginAuthenticate($username, $password, $fallback = false, $params = [])
    {
        $config = new Config(\SugarConfig::getInstance());
        $token = (new UsernamePasswordTokenFactory($username, $password, ['tenant' => $this->getTenant($config)]))
            ->createIdPAuthenticationToken();
        $manager = $this->getAuthProviderBasicBuilder($config)
            ->buildAuthProviders();
        $resultToken = $manager->authenticate($token);
        if ($resultToken->isAuthenticated()) {
            return [
                'user_id' => $resultToken->getUser()->getSugarUser()->id,
                'scope' => null,
            ];
        }
        return false;
    }

    /**
     * @param Config $config
     *
     * @return string
     */
    protected function getTenant(Config $config)
    {
        $idmModeConfig = $config->get('idm_mode', []);
        return !empty($idmModeConfig['tid']) ? $idmModeConfig['tid'] : '';
    }

    /**
     * @param Config $config
     *
     * @return AuthProviderBasicManagerBuilder
     */
    protected function getAuthProviderBasicBuilder(Config $config)
    {
        return new AuthProviderBasicManagerBuilder($config);
    }
}
