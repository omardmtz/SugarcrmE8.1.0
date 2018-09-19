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

namespace Sugarcrm\IdentityProvider\Tests\Unit\STS;

use Sugarcrm\IdentityProvider\STS\EndpointInterface;
use Sugarcrm\IdentityProvider\STS\EndpointService;

/**
 * @coversDefaultClass Sugarcrm\IdentityProvider\STS\EndpointService
 */
class EndpointServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EndpointService
     */
    protected $endpointService;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $hydraConfig = [
            'host' => 'http://oath.host',
            'clientId' => 'clientId',
            'clientSecret' => 'clientSecret',
        ];
        $this->endpointService = new EndpointService($hydraConfig);
    }

    /**
     * @covers ::__construct
     * @expectedException \InvalidArgumentException
     */
    public function testServiceCreationWithoutHost()
    {
        new EndpointService([]);
    }

    /**
     * Checks a valid endpoint generation.
     *
     * @covers ::getOAuth2Endpoint
     */
    public function testGetOAuth2ValidEndpoint()
    {
        $this->assertEquals(
            'http://oath.host/oauth2/token',
            $this->endpointService->getOAuth2Endpoint(EndpointInterface::TOKEN_ENDPOINT)
        );
    }

    /**
     * Checks a not valid endpoint generation.
     *
     * @covers ::getOAuth2Endpoint
     * @expectedException \InvalidArgumentException
     */
    public function testGetOAuth2InvalidEndpoint()
    {
        $this->endpointService->getOAuth2Endpoint('test');
    }

    /**
     * @covers ::getWellKnownConfigurationEndpoint
     */
    public function testGetWellKnownConfigurationEndpoint()
    {
        $this->assertEquals(
            'http://oath.host/.well-known/openid-configuration',
            $this->endpointService->getWellKnownConfigurationEndpoint()
        );
    }

    /**
     * Provides data for testGetKeysValidEndpoint.
     *
     * @return array
     */
    public function getKeysValidEndpointDataProvider()
    {
        return [
            'endpointWithAllKeysType' => [
                'endpoint' => EndpointInterface::CONSENT_RESPONSE_KEYS,
                'keyType' => null,
                'expected' => 'http://oath.host/keys/hydra.consent.response',
            ],
            'endpointWithPrivateKeyType' => [
                'endpoint' => EndpointInterface::CONSENT_RESPONSE_KEYS,
                'keyType' => EndpointInterface::PRIVATE_KEY,
                'expected' => 'http://oath.host/keys/hydra.consent.response/private',
            ],
        ];
    }

    /**
     * Checks getKeysEndpoint logic with valid data.
     *
     * @param string $endpoint
     * @param string $keyType
     * @param string $expected
     *
     * @covers ::getKeysEndpoint
     * @dataProvider getKeysValidEndpointDataProvider
     */
    public function testGetKeysValidEndpoint($endpoint, $keyType, $expected)
    {
        $this->assertEquals(
            $expected,
            $this->endpointService->getKeysEndpoint($endpoint, $keyType)
        );
    }

    /**
     * Provides data for testGetKeysInvalidEndpoint.
     *
     * @return array
     */
    public function getKeysInvalidEndpointDataProvider()
    {
        return [
            'endpointWithAllKeysType' => [
                'endpoint' => 'test',
                'keyType' => null,
                'expectedMessage' => 'Endpoint test is not allowed',
            ],
            'endpointWithPrivateKeyType' => [
                'endpoint' => EndpointInterface::CONSENT_RESPONSE_KEYS,
                'keyType' => 'test',
                'expectedMessage' => 'Key test is not allowed',
            ],
        ];
    }

    /**
     * Checks getKeysEndpoint logic with not valid data.
     *
     * @param string $endpoint
     * @param string $keyType
     *
     * @covers ::getKeysEndpoint
     * @dataProvider getKeysInvalidEndpointDataProvider
     * @expectedException \InvalidArgumentException
     */
    public function testGetKeysInvalidEndpoint($endpoint, $keyType, $expectedMessage)
    {
        $this->expectExceptionMessage($expectedMessage);
        $this->endpointService->getKeysEndpoint($endpoint, $keyType);
    }

    /**
     * @covers ::getConsentDataRequestEndpoint
     */
    public function testGetConsentDataRequestEndpoint()
    {
        $this->assertEquals(
            'http://oath.host/oauth2/consent/requests/test-consent-id',
            $this->endpointService->getConsentDataRequestEndpoint('test-consent-id')
        );
    }

    /**
     * @covers ::getConsentAcceptRequestEndpoint
     */
    public function testGetConsentAcceptRequestEndpoint()
    {
        $this->assertEquals(
            'http://oath.host/oauth2/consent/requests/test-consent-id/accept',
            $this->endpointService->getConsentAcceptRequestEndpoint('test-consent-id')
        );
    }

    /**
     * @covers ::getUserInfoEndpoint
     */
    public function testGetUserInfoEndpoint()
    {
        $this->assertEquals('http://oath.host/userinfo', $this->endpointService->getUserInfoEndpoint());
    }
}
