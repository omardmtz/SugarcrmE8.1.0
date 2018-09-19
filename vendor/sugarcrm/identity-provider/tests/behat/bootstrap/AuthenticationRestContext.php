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

use Behat\Behat\Context\Context;
use Rezzza\RestApiBehatExtension\Rest\RestApiBrowser;
use League\OAuth2\Client\Token\AccessToken;

class AuthenticationRestContext implements Context
{
    use Oauth2ProviderTrait;

    /**
     * @var AccessToken
     */
    protected $accessToken;

    protected $restApiBrowser;

    /**
     * AuthenticationRestContext constructor.
     * @param array $oidcClient
     * @param string $oidcUrl
     * @param RestApiBrowser $restApiBrowser
     */
    public function __construct(array $oidcClient, $oidcUrl, RestApiBrowser $restApiBrowser)
    {
        $this->oidcClient = $oidcClient;
        $this->oidcUrl = $oidcUrl;
        $this->restApiBrowser = $restApiBrowser;
    }

    /**
     * I get access_token for scope
     *
     * @When /^I get access_token for "([^"]+)" scope$/
     * @param string $scope
     */
    public function iGetAccessTokenForScope($scope)
    {
        $this->accessToken = $this->getOauth2Provider()->getAccessToken('client_credentials', [
            'scope' => $scope
        ]);
    }

    /**
     * @Then I add access_token to header
     */
    public function iAddAccessTokenToHeader()
    {
        $authorizationHeader = ucfirst($this->accessToken->getValues()['token_type']) . ' ' . $this->accessToken;
        $this->restApiBrowser->addRequestHeader('Authorization', $authorizationHeader);
    }
}
