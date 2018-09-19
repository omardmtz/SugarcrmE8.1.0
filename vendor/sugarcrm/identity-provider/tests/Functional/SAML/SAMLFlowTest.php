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

namespace Sugarcrm\IdentityProvider\Tests\Functional\SAML;

use DOMDocument;
use Sugarcrm\IdentityProvider\Tests\Functional\Config;

/**
 * Class SAMLFlowTest
 *
 * Test case to test basic SAML flow (no assertion encryption).
 *
 * @package Sugarcrm\IdentityProvider\Tests\Functional\SAML
 */
abstract class SAMLFlowTest extends AppFlowTest
{
    /**
     * Service provider URL which SAML authentication starts from.
     *
     * @var string
     */
    protected $samlLoginEndpoint;

    /**
     * Assertion consumer service URL from our SP.
     *
     * @var string
     */
    protected $samlAcsEndpoint;

    /**
     * Service provider URL which SAML logout starts from.
     *
     * @var string
     */
    protected $samlLogoutEndpoint;

    /**
     * Service provider URL where LogoutResponse sent to.
     *
     * @var string
     */
    protected $samlLogoutHandlerEndpoint;

    /**
     * Path to SAML fixtures.
     *
     * @var string
     */
    protected $fixturesPath = __DIR__ . '/fixtures';

    /**
     * Name of provider to test.
     *
     * @var string
     */
    private $providerKey;

    protected function setUp()
    {
        parent::setUp();

        $this->samlLoginEndpoint = Config::get('SAML_LOGIN_ENDPOINT');
        $this->samlAcsEndpoint = Config::get('SAML_ACS_ENDPOINT');
        $this->samlLogoutEndpoint = Config::get('SAML_LOGOUT_ENDPOINT');
        $this->samlLogoutHandlerEndpoint = Config::get('SAML_LOGOUT_HANDLER_ENDPOINT');
    }

    /**
     * Test to check that SAML login endpoint is redirecting to IdP with proper SAMLRequest.
     */
    public function testAuthnRequestFromServiceProvider()
    {
        if ($this->samlLoginEndpoint === false) {
            $this->markTestSkipped('You need to configure Login url to execute AuthnRequest tests');
        }

        $this->webClient->request('GET', $this->samlLoginEndpoint);

        $response = $this->webClient->getResponse();
        $this->assertEquals(302, $response->getStatusCode());

        $location = $response->getTargetUrl();
        preg_match_all('/(.*)\?/', $location, $matches);
        $redirectUrl = $matches[1][0];

        parse_str(parse_url($location, PHP_URL_QUERY), $parameters);
        $xml = $this->decodeSAMLAssertion($parameters['SAMLRequest']);

        $authnRequestNode = $xml->getElementsByTagName('AuthnRequest')->item(0);
        $this->assertEquals(
            $redirectUrl,
            $authnRequestNode->getAttribute('Destination')
        );
        $this->assertEquals(
            'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
            $authnRequestNode->getAttribute('ProtocolBinding')
        );
        $this->assertEquals(
            $this->samlAcsEndpoint,
            $authnRequestNode->getAttribute('AssertionConsumerServiceURL')
        );

        $issuerNode = $xml->getElementsByTagName('Issuer')->item(0);
        $this->assertNotNull($issuerNode);

        $authnContextClassRefNode = $xml->getElementsByTagName('AuthnContextClassRef')->item(0);
        $this->assertNotNull($authnContextClassRefNode);
        $this->assertEquals(
            'urn:oasis:names:tc:SAML:2.0:ac:classes:PasswordProtectedTransport',
            $authnContextClassRefNode->nodeValue
        );
    }

    /**
     * Provides url parameters info for testRelayStateFromServiceProvider
     * @return array
     **/
    public function relayStateFromServiceProviderDataProvider()
    {
        return [
            'relayStateIsEmpty' => [
                'requestParameters' => [],
                'expectedRelayState' => 'http://localhost:8000/saml/login-end-point',
            ],
            'relayStateNotEmpty' => [
                'requestParameters' => ['RelayState' => 'http://test.com/notEmptyRelay'],
                'expectedRelayState' => 'http://test.com/notEmptyRelay',
            ],
        ];
    }

    /**
     * Test to check that SAML login endpoint is redirecting to IdP with proper relayState parameter.
     *
     * @param array $requestParameters
     * @param string $expectedRelayState
     *
     * @dataProvider relayStateFromServiceProviderDataProvider
     */
    public function testRelayStateFromServiceProvider($requestParameters, $expectedRelayState)
    {
        if ($this->samlLoginEndpoint === false) {
            $this->markTestSkipped('You need to configure Login url to execute AuthnRequest tests');
        }

        $this->webClient->request('GET', $this->samlLoginEndpoint, $requestParameters);

        $response = $this->webClient->getResponse();
        parse_str(parse_url($response->getTargetUrl(), PHP_URL_QUERY), $parameters);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals($expectedRelayState, $parameters['RelayState']);
    }

    /**
     * Test to check that provider responses are processed successfully.
     *
     * @dataProvider responseProvider
     * @param $responseFile
     */
    public function testResponseFromIdPProcessedSuccessfully($responseFile)
    {
        if ($this->samlAcsEndpoint === false) {
            $this->markTestSkipped('You need to configure ACS url to execute ACS tests');
        }

        $this->webClient->request(
            'POST',
            $this->samlAcsEndpoint,
            ['SAMLResponse' => $this->encodeSAMLAssertion(file_get_contents($responseFile))]
        );
        $this->webClient->followRedirect();

        $this->assertContains(
            'User is authenticated successfully',
            $this->webClient->getResponse()->getContent()
        );
    }

    /**
     * Test to check that service provider really use RelayState and redirects to it.
     *
     * @dataProvider responseProvider
     * @param $responseFile
     */
    public function testRelayStateIsProcessed($responseFile)
    {
        if ($this->samlAcsEndpoint === false) {
            $this->markTestSkipped('You need to configure ACS url to execute ACS tests');
        }

        $urls = ['http://test.com', 'http://test.com/', 'http://test.com/foo/bar'];
        foreach ($urls as $url) {
            $this->webClient->request(
                'POST',
                $this->samlAcsEndpoint,
                [
                    'SAMLResponse' => $this->encodeSAMLAssertion(file_get_contents($responseFile)),
                    'RelayState' => $url,
                ]
            );

            $response = $this->webClient->getResponse();
            $this->assertEquals(302, $response->getStatusCode());
            $this->assertContains($url, $response->getTargetUrl());
        }
    }


    /**
     * Test to check that proper logout request is sent.
     */
    public function testLogoutRequest()
    {
        if ($this->samlLogoutEndpoint === false) {
            $this->markTestSkipped('You need to configure Logout url to execute LogoutRequest tests');
        }

        $this->webClient->request('GET', $this->samlLogoutEndpoint);

        $response = $this->webClient->getResponse();
        $this->assertEquals(302, $response->getStatusCode());

        $location = $response->getTargetUrl();
        preg_match_all('/(.*)\?/', $location, $matches);
        $redirectUrl = $matches[1][0];

        parse_str(parse_url($location, PHP_URL_QUERY), $parameters);
        $xml = $this->decodeSAMLAssertion($parameters['SAMLRequest']);

        $logoutRequestNode = $xml->getElementsByTagName('LogoutRequest')->item(0);
        $this->assertEquals(
            $redirectUrl,
            $logoutRequestNode->getAttribute('Destination')
        );

        $issuerNode = $xml->getElementsByTagName('Issuer')->item(0);
        $this->assertNotNull($issuerNode);
    }

    /**
     * Checking behaviour when SAMLRequest or SAMLResponse is not passed.
     */
    public function testIdpLogoutRequestWithEmptyData()
    {
        if ($this->samlLogoutHandlerEndpoint === false) {
            $this->markTestSkipped('You need to configure Logout Handler url to execute LogoutResponse tests');
        }

        $this->webClient->request('GET', $this->samlLogoutHandlerEndpoint);
        $this->assertContains('Invalid SAML logout data', $this->webClient->getResponse()->getContent());
    }

    /**
     * Test to check that LogoutResponse is handled properly.
     *
     * @dataProvider logoutResponseProvider
     * @param $responseFile string
     */
    public function testLogoutResponseHandledSuccessfully($responseFile)
    {
        if ($this->samlLogoutHandlerEndpoint === false) {
            $this->markTestSkipped('You need to configure Logout Handler url to execute LogoutResponse tests');
        }

        $this->webClient->request(
            'GET',
            $this->samlLogoutHandlerEndpoint,
            ['SAMLResponse' => $this->encodeSAMLAssertion(file_get_contents($responseFile))]
        );
        $this->webClient->followRedirect();
        $this->assertContains('User is logged out', $this->webClient->getResponse()->getContent());
    }

    /**
     * Returns path to files with responses from providers
     *
     * @return array
     */
    abstract public function responseProvider();

    /**
     * Returns path to files with logout responses from providers
     *
     * @return array
     */
    abstract public function logoutResponseProvider();

    /**
     * Method to decode SAML assertion into DOMDocument
     *
     * @param string $assertion
     * @return DOMDocument
     */
    protected function decodeSAMLAssertion($assertion)
    {
        $samlAssertion = gzinflate(base64_decode($assertion));
        $xml = new DOMDocument();
        $xml->loadXML($samlAssertion);

        return $xml;
    }

    /**
     * Method to encode SAML assertion to be passed to IdP or SP.
     *
     * @param string $assertion
     * @return string
     */
    protected function encodeSAMLAssertion($assertion)
    {
        return base64_encode($assertion);
    }

    /**
     * Sets provider key.
     *
     * @param string $providerKey
     */
    protected function setProviderKey($providerKey)
    {
        $this->providerKey = $providerKey;
    }
}
