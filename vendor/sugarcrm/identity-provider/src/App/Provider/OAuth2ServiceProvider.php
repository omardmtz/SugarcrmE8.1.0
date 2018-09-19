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

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Sugarcrm\IdentityProvider\App\Authentication\OAuth2Service;
use Sugarcrm\IdentityProvider\STS\EndpointInterface;
use Sugarcrm\IdentityProvider\STS\EndpointService;
use Sugarcrm\IdentityProvider\League\OAuth2\Client\Provider\HttpBasicAuth\GenericProvider as OAuth2Provider;

class OAuth2ServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Container $app)
    {
        $app['oAuth2Service'] = function ($app) {
            $oAuth2Config = (isset($app['config']['sts'])) ? $app['config']['sts'] : [];
            $stsEndpoint = new EndpointService($oAuth2Config);
            $oAuth2Provider = new OAuth2Provider(
                [
                    'clientId' => $oAuth2Config['clientId'],
                    'clientSecret' => $oAuth2Config['clientSecret'],
                    'accessTokenFile' => $oAuth2Config['accessTokenFile'],
                    'accessTokenRefreshUrl' => $oAuth2Config['accessTokenRefreshUrl'],
                    'urlAuthorize' => $stsEndpoint->getOAuth2Endpoint(EndpointInterface::AUTH_ENDPOINT),
                    'urlAccessToken' => $stsEndpoint->getOAuth2Endpoint(EndpointInterface::TOKEN_ENDPOINT),
                    'urlIntrospectToken' => $stsEndpoint->getOAuth2Endpoint(EndpointInterface::INTROSPECT_ENDPOINT),
                    'urlResourceOwnerDetails' => $stsEndpoint->getWellKnownConfigurationEndpoint(),
                    'logger' => $app->getLogger(),
                ]
            );
            return new OAuth2Service($stsEndpoint, $oAuth2Provider);
        };
    }
}
