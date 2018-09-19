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

namespace Sugarcrm\IdentityProvider\Authentication\Provider\SAML;

use Sugarcrm\IdentityProvider\Saml2;
use Sugarcrm\IdentityProvider\Authentication\Token\SAML\ActionTokenInterface;

/**
 * Retrieves SAML authentication IdPs based on token/configuration
 */
class AuthServiceProvider
{
    /**
     * @var array
     */
    protected $authServiceSettings = [];

    /**
     * @var \OneLogin_Saml2_Auth[]
     */
    protected $authServices = [];

    /**
     * SAML binding to classes map.
     *
     * @var array
     */
    protected $authBinding = [
        \OneLogin_Saml2_Constants::BINDING_HTTP_POST => Saml2\AuthPostBinding::class,
        \OneLogin_Saml2_Constants::BINDING_HTTP_REDIRECT => Saml2\AuthRedirectBinding::class,
    ];

    /**
     * @param array $settings
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(array $settings)
    {
        if (!count($settings)) {
            throw new \InvalidArgumentException('Invalid authentication services configuration');
        }
        $this->authServiceSettings = $settings;
    }

    /**
     * Returns SAML authentication service.
     * If system is configured as multi-service, we expected 'idp' parameter in the token.
     *
     * @param ActionTokenInterface $token
     *
     * @return \OneLogin_Saml2_Auth
     */
    public function getAuthService(ActionTokenInterface $token)
    {
        $action = $token->getAction();
        if (!isset($this->authServices[$action])) {
            $this->authServices[$action] = $this->buildAuthService($action);
        }
        return $this->authServices[$action];
    }

    /**
     * Builds proper Auth object
     *
     * @param string $action
     *
     * @return \OneLogin_Saml2_Auth
     * @throws \InvalidArgumentException
     */
    protected function buildAuthService($action)
    {
        $serviceSettings = $this->authServiceSettings;
        if (empty($serviceSettings['idp'])) {
            throw new \InvalidArgumentException('Invalid IdP configuration');
        }
        $idpSettings = $serviceSettings['idp'];
        if (($action == ActionTokenInterface::LOGIN_ACTION)
            && !empty($idpSettings['singleSignOnService']['url'])
            && !empty($idpSettings['singleSignOnService']['binding'])
            && array_key_exists($idpSettings['singleSignOnService']['binding'], $this->authBinding)
        ) {
            return new $this->authBinding[$idpSettings['singleSignOnService']['binding']]($serviceSettings);
        }

        if (($action == ActionTokenInterface::LOGOUT_ACTION)
            && !empty($idpSettings['singleLogoutService']['url'])
            && !empty($idpSettings['singleLogoutService']['binding'])
            && array_key_exists($idpSettings['singleLogoutService']['binding'], $this->authBinding)
        ) {
            return new $this->authBinding[$idpSettings['singleLogoutService']['binding']]($serviceSettings);
        }

        throw new \InvalidArgumentException('Invalid IdP configuration');
    }
}
