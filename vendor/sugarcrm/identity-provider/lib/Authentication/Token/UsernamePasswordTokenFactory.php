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

namespace Sugarcrm\IdentityProvider\Authentication\Token;

use Sugarcrm\IdentityProvider\App\Authentication\AuthProviderManagerBuilder;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Factory for UsernamePasswordTokens creation.
 *
 * Class UsernamePasswordTokenFactory
 * @package Sugarcrm\IdentityProvider\Authentication\Token
 */
class UsernamePasswordTokenFactory
{
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
    protected $config = [];

    /**
     * UsernamePasswordTokenFactory constructor.
     * @param array $config
     * @param $username
     * @param $password
     */
    public function __construct(array $config, $username, $password)
    {
        $this->config = $config;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Create token for local auth.
     *
     * @return null|UsernamePasswordToken
     */
    protected function createLocalAuthenticationToken()
    {
        if (!in_array('local', $this->config['enabledProviders'])) {
            return null;
        }
        return new UsernamePasswordToken(
            $this->username,
            $this->password,
            AuthProviderManagerBuilder::PROVIDER_KEY_LOCAL
        );
    }

    /**
     * Create token for ldap auth.
     *
     * @return null|UsernamePasswordToken
     */
    protected function createLdapAuthenticationToken()
    {
        if (!in_array('ldap', $this->config['enabledProviders'])) {
            return null;
        }
        return new UsernamePasswordToken(
            $this->username,
            $this->password,
            AuthProviderManagerBuilder::PROVIDER_KEY_LDAP
        );
    }

    /**
     * Create token for local or/and LDAP auth.
     *
     * @return TokenInterface
     */
    public function createAuthenticationToken()
    {
        $tokens = [
            $this->createLdapAuthenticationToken(),
            $this->createLocalAuthenticationToken(),
        ];
        // remove not configured items
        $tokens = array_filter($tokens);

        if (count($tokens) == 1) {
            return array_shift($tokens);
        }

        $token = new MixedUsernamePasswordToken(
            $this->username,
            $this->password,
            AuthProviderManagerBuilder::PROVIDER_KEY_MIXED
        );
        array_walk($tokens, [$token, 'addToken']);
        return $token;
    }
}
