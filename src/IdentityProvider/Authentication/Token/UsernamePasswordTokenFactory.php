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

namespace Sugarcrm\Sugarcrm\IdentityProvider\Authentication\Token;

use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\AuthProviderManagerBuilder;
use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\Encoder\SugarPreAuthPassEncoder;
use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\User;

use Sugarcrm\IdentityProvider\Authentication\Token\MixedUsernamePasswordToken;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Factory for UsernamePasswordTokens creation.
 *
 * Class UsernamePasswordTokenFactory
 * @package Sugarcrm\Sugarcrm\IdentityProvider\Authentication\Token
 */
class UsernamePasswordTokenFactory
{
    /**
     * @var string Tenant SRN
     */
    protected $tenant;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var array
     */
    protected $params;

    /**
     * UsernamePasswordTokenFactory constructor.
     * @param string $username
     * @param string $password
     * @param array $params
     */
    public function __construct($username, $password, $params = [])
    {
        $this->username = $username;
        $this->password = $password;
        $this->params = $params;
        $this->tenant = !empty($params['tenant']) ? $params['tenant'] : '';
    }

    /**
     * Create token for local auth.
     *
     * @return UsernamePasswordToken
     */
    public function createLocalAuthenticationToken()
    {
        $isPasswordEncrypted = !empty($this->params['passwordEncrypted']);

        $token = new UsernamePasswordToken(
            $this->username,
            (new SugarPreAuthPassEncoder())->encodePassword($this->password, '', $isPasswordEncrypted),
            AuthProviderManagerBuilder::PROVIDER_KEY_LOCAL,
            User::getDefaultRoles()
        );

        // TODO delete this when strtolower+md5 encrypt will be deleted
        $token->setAttribute('isPasswordEncrypted', $isPasswordEncrypted);
        // Raw password is required for password rehash on success auth
        $token->setAttribute('rawPassword', $this->password);

        return $token;
    }

    /**
     * Create token for ldap auth.
     *
     * @return UsernamePasswordToken
     */
    public function createLdapAuthenticationToken()
    {
        return new UsernamePasswordToken(
            $this->username,
            $this->password,
            AuthProviderManagerBuilder::PROVIDER_KEY_LDAP,
            User::getDefaultRoles()
        );
    }

    /**
     * Create token for remote IdP auth.
     *
     * @return IdpUsernamePasswordToken
     */
    public function createIdPAuthenticationToken()
    {
        return new IdpUsernamePasswordToken(
            $this->tenant,
            $this->username,
            $this->password,
            AuthProviderManagerBuilder::PROVIDER_KEY_IDP,
            User::getDefaultRoles()
        );
    }

    /**
     * Create token for mixed auth.
     *
     * @return MixedUsernamePasswordToken
     */
    public function createMixedAuthenticationToken()
    {
        return new MixedUsernamePasswordToken(
            $this->username,
            $this->password,
            AuthProviderManagerBuilder::PROVIDER_KEY_MIXED,
            User::getDefaultRoles()
        );
    }
}
