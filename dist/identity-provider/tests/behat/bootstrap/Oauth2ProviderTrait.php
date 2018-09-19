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

namespace Sugarcrm\IdentityProvider\IntegrationTests\Bootstrap;

use Sugarcrm\IdentityProvider\League\OAuth2\Client\Provider\HttpBasicAuth\GenericProvider;
use Monolog\Logger;

trait Oauth2ProviderTrait
{
    /**
     *
     * @var string
     */
    protected $oidcUrl;

    /**
     * @var array
     */
    protected $oidcClient;

    /**
     * @var GenericProvider
     */
    protected $oauth2Provider = null;

    /**
     * @return GenericProvider
     */
    protected function getOauth2Provider()
    {
        if (is_null($this->oauth2Provider)) {
            $this->oauth2Provider = new GenericProvider([
                'clientId'                => $this->oidcClient['clientId'],
                'clientSecret'            => $this->oidcClient['clientSecret'],
                'accessTokenFile'         => $this->oidcClient['accessTokenFile'],
                'accessTokenRefreshUrl'   => $this->oidcClient['accessTokenRefreshUrl'],
                'redirectUri'             => $this->oidcClient['redirectUrl'],
                'urlAuthorize'            => $this->oidcUrl . '/oauth2/auth',
                'urlAccessToken'          => $this->oidcUrl . '/oauth2/token',
                'urlResourceOwnerDetails' => $this->oidcUrl . '/.well-known/jwks.json',
                'logger'                  => new Logger('test'),
            ]);
        }
        return $this->oauth2Provider;
    }
}
