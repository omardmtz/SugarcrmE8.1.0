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

namespace Sugarcrm\IdentityProvider\App\Provider;

use Sugarcrm\IdentityProvider\Authentication\UserMapping\SAMLUserMapping;
use Sugarcrm\IdentityProvider\Authentication\UserMapping\LDAPUserMapping;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class UserMappingServiceProvider
 * @package Sugarcrm\IdentityProvider\App\Provider
 */
class UserMappingServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Container $app)
    {
        $app['SAMLUserMapping'] = $app->protect(function () use ($app) {
            $mapping = (isset($app['config']['saml']['user_mapping'])) ? $app['config']['saml']['user_mapping'] : [];
            return new SAMLUserMapping($mapping);
        });
        $app['LDAPUserMapping'] = $app->protect(function () use ($app) {
            $mapping = (isset($app['config']['ldap']['user_mapping'])) ? $app['config']['ldap']['user_mapping'] : [];
            return new LDAPUserMapping($mapping);
        });
    }
}
