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

namespace Sugarcrm\IdentityProvider\App\Authentication;

use Sugarcrm\IdentityProvider\App\Application;
use Sugarcrm\IdentityProvider\Authentication\Provider\LdapAuthenticationProvider;
use Sugarcrm\IdentityProvider\Authentication\Provider\SAMLAuthenticationProvider;
use Sugarcrm\IdentityProvider\Authentication\User;
use Sugarcrm\IdentityProvider\Authentication\Provider\MixedAuthenticationProvider;
use Sugarcrm\IdentityProvider\Authentication\UserProvider\LdapUserProvider;
use Sugarcrm\IdentityProvider\Authentication\UserProvider\LocalUserProvider;
use Sugarcrm\IdentityProvider\Authentication\UserProvider\SAMLUserProvider;
use Sugarcrm\IdentityProvider\Encoder\EncoderBuilder;
use Sugarcrm\IdentityProvider\Authentication\User\LDAPUserChecker;
use Sugarcrm\IdentityProvider\Authentication\User\SAMLUserChecker;
use Sugarcrm\IdentityProvider\Srn\Converter;
use Sugarcrm\IdentityProvider\Srn\Srn;
use Symfony\Component\Ldap\Adapter\ExtLdap\Adapter;
use Symfony\Component\Ldap\Ldap;
use Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager;
use Symfony\Component\Security\Core\Authentication\Provider\DaoAuthenticationProvider;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\User\UserChecker;

class AuthProviderManagerBuilder
{
    const PROVIDER_KEY_LOCAL = 'PROVIDER_KEY_LOCAL';
    const PROVIDER_KEY_LDAP = 'PROVIDER_KEY_LDAP';
    const PROVIDER_KEY_SAML = 'PROVIDER_KEY_SAML';
    const PROVIDER_KEY_MIXED = 'PROVIDER_KEY_MIXED';

    const SAMLIDP_OKTA = 'SAMLIDP_OKTA';

    /**
     * tenant srn object
     * @var Srn
     */
    protected $tenant;

    /**
     * @param Application $app Silex application instance.
     * @throws \RuntimeException
     * @return AuthenticationProviderManager
     */
    public function buildAuthProviderManager(Application $app)
    {
        $this->tenant = Converter::fromString($app->getSession()->get('tenant'));

        // todo this is an example. need update manager to make it more flexible ane configurable
        $providers = [
            $this->getLdapAuthProvider($app),
            $this->getLocalAuthProvider($app),
            $this->getSamlAuthIDP($app),
        ];
        // remove not configured items
        $providers = array_filter($providers);
        if (count($providers) > 1) {
            $providers[] = new MixedAuthenticationProvider($providers, self::PROVIDER_KEY_MIXED);
        }

        $authManager = new AuthenticationProviderManager($providers);

        return $authManager;
    }

    /**
     * @param Application $app Silex application instance.
     * @return DaoAuthenticationProvider
     */
    protected function getLocalAuthProvider(Application $app)
    {
        $userProvider = new LocalUserProvider($app->getDoctrineService(), $this->tenant->getTenantId());

        // standard library  user checker
        $userChecker = new UserChecker();

        $encoderBuilder = new EncoderBuilder();

        $encoderFactory = new EncoderFactory([
            User::class => $encoderBuilder->buildEncoder($app['config']),
        ]);

        // local auth provider
        $authProvider = new DaoAuthenticationProvider(
            $userProvider,
            $userChecker,
            self::PROVIDER_KEY_LOCAL,
            $encoderFactory
        );

        return $authProvider;
    }

    /**
     * @param Application $app Silex application instance.
     * @return LdapAuthenticationProvider|null
     */
    protected function getLdapAuthProvider(Application $app)
    {
        if (empty($app['config']['ldap'])) {
            return null;
        }

        $config = $app['config']['ldap'];

        $adapter = new Adapter($config['adapter_config']);
        if (!empty($config['adapter_connection_protocol_version'])) {
            $adapter->getConnection()->setOption('PROTOCOL_VERSION', $config['adapter_connection_protocol_version']);
        }

        $ldap = new Ldap($adapter);

        $userProvider = new LdapUserProvider(
            $ldap,
            $config['baseDn'],
            $config['searchDn'],
            $config['searchPassword'],
            User::getDefaultRoles(),
            $config['uidKey'],
            $config['filter']
        );

        $authProvider = new LdapAuthenticationProvider(
            $userProvider,
            new LDAPUserChecker($this->getLocalUserProvider($app), $config),
            self::PROVIDER_KEY_LDAP,
            $ldap,
            $app->getUserMappingService('ldap'),
            $config['dnString'],
            true,
            $config
        );

        return $authProvider;
    }

    /**
     * @param Application $app Silex application instance.
     * @return SAMLAuthenticationProvider|null
     */
    protected function getSamlAuthIDP($app)
    {
        if (empty($app['config']['saml'])) {
            return null;
        }

        $config = $app['config']['saml'];

        return new SAMLAuthenticationProvider(
            $config,
            new SAMLUserProvider(),
            new SAMLUserChecker($this->getLocalUserProvider($app), $config),
            $app->getSession(),
            $app->getUserMappingService('saml')
        );
    }

    /**
     * @param Application $app Silex application instance.
     * @return LocalUserProvider
     */
    protected function getLocalUserProvider($app)
    {
        return new LocalUserProvider($app->getDoctrineService(), $this->tenant->getTenantId());
    }
}
