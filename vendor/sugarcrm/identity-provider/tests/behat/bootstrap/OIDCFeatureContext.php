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

use GuzzleHttp;

require_once '../../vendor/autoload.php';
require_once '../../vendor/phpunit/phpunit/src/Framework/Assert/Functions.php';

class OIDCFeatureContext extends FeatureContext
{
    use Oauth2ProviderTrait;

    /**
     *
     * @var string
     */
    protected $accessToken;


    /**
     *
     * @var string
     */
    protected $responseBody;

    /**
     * SetUp necessary configs.
     *
     * @param array $sugarAdmin
     * @param array $oidcClient
     */
    public function __construct(array $sugarAdmin, array $oidcClient)
    {
        parent::__construct($sugarAdmin);
        $this->oidcClient = $oidcClient;
    }

    /**
     * Gets Mango resource for provided platform
     *
     * @param string $platform
     *
     * @And /^I try to get Mango resource for "([^"]*)" platform$/
     * @Then /^I try to get Mango resource for "([^"]*)" platform$/
     */
    public function iTryToGetMangoResourceForPlatform($platform)
    {
        $usersUrl = ($this->getMinkParameter('base_url')) . '/rest/v11/Calls?platform=' . $platform;
        $ch = @curl_init($usersUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $responseArray = (array) json_decode($response, true);
        $this->oidcUrl = isset($responseArray['url']) ? $responseArray['url'] : '';
        assertNotEmpty($this->oidcUrl, "Assertion failed - oidcUrl is empty");
    }


    /**
     * Navigates to OIDC provider with tenant
     *
     * @param string $tenantSrn
     *
     * @And /^I navigate to OIDC provider with tenant "([^"]*)"$/
     * @Then /^I navigate to OIDC provider with tenant "([^"]*)"$/
     */
    public function iNavigateToOidcProviderWithTenant($tenantSrn)
    {
        $params = [
            'scope' => implode(' ', [
                'offline',
                'https://apis.sugarcrm.com/auth/crm',
                'profile',
                'email',
                'address',
                'phone',
            ])
        ];
        if (!empty($tenantSrn)) {
            $params['login_hint'] = $tenantSrn;
        }
        $this->visit($this->getOauth2Provider()->getAuthorizationUrl($params));
        $this->waitForThePageToBeLoaded();
    }

    /**
     * Verifies IdP login page is opened
     *
     * @Then I should see IdP login page
     */
    public function iShouldSeeIdpLoginPage()
    {
        $this->assertSession()->elementExists('css', "#nomad");
        $this->assertSession()->elementExists('css', "#username");
        $this->assertSession()->elementExists('css', "#password");
    }


    /**
     * Provides operation for login on IdP login screen.
     *
     * @param string $username
     * @param string $password
     *
     * @And /^I do IdP login as "([^"]*)" with password "([^"]*)"$/
     * @When /^I do IdP login as "([^"]*)" with password "([^"]*)"$/
     */
    public function iDoIdPLogin($username, $password)
    {
        $page = $this->getSession()->getPage();
        $page->fillField('user_name', $username);
        $page->fillField('password', $password);
        $page->clickLink('login_btn');
        $this->waitForThePageToBeLoaded();
    }


    /**
     * Save access_token
     * Asserts access_token is not empty
     *
     * @And I get access_token of OPI platform
     * @Then I get access_token of OPI platform
     */
    public function iGetAccessToken()
    {
        parse_str(parse_url($this->getSession()->getCurrentUrl(), PHP_URL_QUERY), $args);
        assertNotEmpty($args['code'], 'Auth code not found');
        $this->accessToken = $this->getOauth2Provider()->getAccessToken('authorization_code', [
            'code' => $args['code']
        ]);;
        assertNotEmpty($this->accessToken, "Assertion failed - accessToken is empty");
    }


    /**
     * Sends GET request with parameter $request
     * Uses accessToken
     *
     * @param string $method
     * @param string $request
     *
     * @And /^I use access_token for ([^"]*) request "([^"]*)"$/
     * @Then /^I use access_token for ([^"]*) request "([^"]*)"$/
     * @throws \RuntimeException
     *
     * @return string
     */
    public function iUseAccessTokenForRequest($method, $request)
    {
        if (!empty($this->accessToken)) {
            $url = rtrim($this->getMinkParameter('base_url'), '/') . $request;
            $client = new GuzzleHttp\Client();
            switch ($method) {
                case 'POST':
                    $response = $client->post($url, ['headers' => ['OAuth-Token' => $this->accessToken]]);
                    break;
                case 'GET':
                    $response = $client->get($url, ['headers' => ['OAuth-Token' => $this->accessToken]]);
                    break;
                default:
                    throw new \RuntimeException("Unsupported method");
            }
            $this->responseBody = $response->getBody();
            return $this->responseBody;
        } else {
            throw new \RuntimeException("Access token is empty");
        }
    }

    /**
     * @And I get access_token form sugar token response
     * @Then I get access_token form sugar token response
     */
    public function iGetAccessTokenFromSugarTokenResponse()
    {
        $token = json_decode((string) $this->responseBody, true);
        $this->accessToken = $token['access_token'];
        assertNotEmpty($this->accessToken, "Assertion failed - accessToken is empty");
    }

    /**
     * Change access toke to new value
     * @param string $newToken
     *
     * @And /^I change access token to "([^"]*)"$/
     * @Then /^I change access token to "([^"]*)"$/
     */
    public function iChangeAccessToken($newToken)
    {
        $this->setLocalStorageItem('prod:SugarCRM:AuthAccessToken', $newToken);
        assertEquals($newToken, $this->getAccessToken());
    }

    /**
     * Compare current access with some value
     *
     * @param string $tokenToCompare
     * @param string $compareStrategy
     *
     * @And /^I compare access token with "([^"]*)" as "([^"]*)"$/
     * @Then /^I compare access token with "([^"]*)" as "([^"]*)"$/
     */
    public function iCompareAccessToken($tokenToCompare, $compareStrategy)
    {
        $accessToken = $this->getAccessToken();
        switch ($compareStrategy) {
            case 'notEquals':
                assertNotEquals($tokenToCompare, $accessToken);
                break;
            default:
                throw new \RuntimeException("Unknown compare strategy");
        }
    }


    /**
     * Verifies that response contains correct value
     *
     * @param string $field
     * @param string $value
     *
     * @And /^I verify response contains "([^"]*)" with value "([^"]*)"$/
     * @Then /^I verify response contains "([^"]*)" with value "([^"]*)"$/
     */
    public function iVerifyResponseContainsCorrectValue($field, $value)
    {
        $list = json_decode((string) $this->responseBody, true);
        assertEquals($value, $list['current_user'][$field]);
    }

    /**
     * Verifies that response contains correctly matching regexp value
     *
     * @param string $field
     * @param string $regexpValue
     *
     * @And /^I verify response contains "([^"]*)" with matching regexp value "([^"]*)"$/
     * @Then /^I verify response contains "([^"]*)" with matching regexp value "([^"]*)"$/
     */
    public function iVerifyResponseContainsCorrectlyMatchingRegexpValue($field, $regexpValue)
    {
        $list = json_decode((string) $this->responseBody, true);
        assertRegExp($regexpValue, $list['current_user'][$field]);
    }

    /**
     * @Then I confirm consent request
     * @And I confirm consent request
     */
    public function iConfirmConsentRequest()
    {
        $this->waitForElement('#consent_continue_btn');
        $this->iClick('#consent_continue_btn');
    }

    /**
     * @Then I reject consent request
     * @And I reject consent request
     */
    public function iRejectConsentRequest()
    {
        $this->waitForElement('#consent_cancel_btn');
        $this->iClick('#consent_cancel_btn');
    }

    /**
     * Check that current url contains string
     * The string is urlencoded
     * @param $string
     *
     * @Then /^I check that current url contains "([^"]*)"$/
     * @And /^I check that current url contains "([^"]*)"$/
     */
    public function iCheckThatCurrentUrlContains($string)
    {
        assertTrue(strpos($this->getSession()->getCurrentUrl(), urlencode($string)) !== FALSE);
    }
}
