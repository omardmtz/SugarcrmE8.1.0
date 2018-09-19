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

use Sugarcrm\IdentityProvider\Tests\IDMFixturesHelper;

/**
 * Class OneLoginSAMLFlowTest
 *
 * Test case to test that SP working with OneLogin.
 *
 * @package Sugarcrm\IdentityProvider\Tests\Functional\SAML
 */
class OneLoginSAMLFlowTest extends SAMLFlowTest
{
    protected function setUp()
    {
        parent::setUp();

        $this->setProviderKey('OneLogin');
    }

    public function responseProvider()
    {
        $oneLoginPath = $this->fixturesPath . '/OneLogin';

        return [
            'Encrypted Response' => [$oneLoginPath . '/EncryptedResponse/Response.xml'],
            'SHA1 Signature' => [$oneLoginPath . '/SignedResponse-SHA1/Response.xml'],
            'SHA256 Signature' => [$oneLoginPath . '/SignedResponse-SHA256/Response.xml'],
            'SHA384 Signature' => [$oneLoginPath . '/SignedResponse-SHA384/Response.xml'],
            'SHA512 Signature' => [$oneLoginPath . '/SignedResponse-SHA512/Response.xml'],
        ];
    }

    public function logoutResponseProvider()
    {
        $fixturesPath = __DIR__ . '/fixtures';
        $oneLoginPath = $fixturesPath . '/OneLogin';

        return [
            'Logout Response' => [$oneLoginPath . '/Logout/LogoutResponse.xml']
        ];
    }

    public function logoutRequestProvider()
    {
        $config = IDMFixturesHelper::getOneLoginParameters();
        return [
            'logoutRequest' => [
                'request' => IDMFixturesHelper::getSAMLFixture('OneLogin/Logout/idpLogoutRequest.xml'),
                'expectedDestinationUrl' => $config['idp']['singleLogoutService']['url'],
                'expectedInResponseTo' => '_fa893380-dcb6-0134-d072-0664e44f8f7d',
            ],
        ];
    }

    /**
     * Checks that logout response is valid and redirect passed.
     *
     * @param string $request
     * @param string $expectedDestinationUrl
     * @param string $expectedInResponseTo
     *
     * @dataProvider logoutRequestProvider
     */
    public function testIdpLogoutRequestWithoutRelayState($request, $expectedDestinationUrl, $expectedInResponseTo)
    {
        if ($this->samlLogoutHandlerEndpoint === false) {
            $this->markTestSkipped('You need to configure Logout Handler url to execute LogoutResponse tests');
        }

        $this->webClient->request(
            'GET',
            $this->samlLogoutHandlerEndpoint,
            ['SAMLRequest' => $this->encodeSAMLAssertion($request)]
        );
        $this->webClient->followRedirect();
        $crawler = $this->webClient->getCrawler();
        $location = $crawler->getUri();
        $this->assertContains($expectedDestinationUrl, $location);
        parse_str(parse_url($location, PHP_URL_QUERY), $parameters);
        $this->assertArrayHasKey('SAMLResponse', $parameters);

        $response = $this->decodeSAMLAssertion($parameters['SAMLResponse']);
        $logoutResponseNode = $response->getElementsByTagName('LogoutResponse')->item(0);
        $statusNode = $response->getElementsByTagName('StatusCode')->item(0);

        $this->assertEquals($expectedInResponseTo, $logoutResponseNode->getAttribute('InResponseTo'));
        $this->assertEquals($expectedDestinationUrl, $logoutResponseNode->getAttribute('Destination'));
        $this->assertEquals(\OneLogin_Saml2_Constants::STATUS_SUCCESS, $statusNode->getAttribute('Value'));
    }

    /**
     * Checks that logout response is valid and redirect passed and RelayState presents in query string.
     *
     * @param string $request
     * @param string $expectedDestinationUrl
     * @param string $expectedInResponseTo
     *
     * @dataProvider logoutRequestProvider
     */
    public function testIdpLogoutRequestWithRelayState($request, $expectedDestinationUrl, $expectedInResponseTo)
    {
        if ($this->samlLogoutHandlerEndpoint === false) {
            $this->markTestSkipped('You need to configure Logout Handler url to execute LogoutResponse tests');
        }

        $relayState = 'http://relay.state';
        $this->webClient->request(
            'GET',
            $this->samlLogoutHandlerEndpoint,
            [
                'SAMLRequest' => $this->encodeSAMLAssertion($request),
                'RelayState' => $relayState,
            ]
        );
        $this->webClient->followRedirect();
        $crawler = $this->webClient->getCrawler();
        $location = $crawler->getUri();
        $this->assertContains($expectedDestinationUrl, $location);
        parse_str(parse_url($location, PHP_URL_QUERY), $parameters);
        $this->assertArrayHasKey('SAMLResponse', $parameters);
        $this->assertArrayHasKey('RelayState', $parameters);

        $response = $this->decodeSAMLAssertion($parameters['SAMLResponse']);
        $logoutResponseNode = $response->getElementsByTagName('LogoutResponse')->item(0);
        $statusNode = $response->getElementsByTagName('StatusCode')->item(0);

        $this->assertEquals($expectedInResponseTo, $logoutResponseNode->getAttribute('InResponseTo'));
        $this->assertEquals($expectedDestinationUrl, $logoutResponseNode->getAttribute('Destination'));
        $this->assertEquals(\OneLogin_Saml2_Constants::STATUS_SUCCESS, $statusNode->getAttribute('Value'));
        $this->assertEquals($relayState, $parameters['RelayState']);
    }
    /**
     * @inheritdoc
     */
    public function getSamlParameters()
    {
        return IDMFixturesHelper::getOneLoginParameters();
    }
}
