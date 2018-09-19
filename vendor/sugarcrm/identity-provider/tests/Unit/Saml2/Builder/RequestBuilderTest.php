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

namespace Sugarcrm\IdentityProvider\Tests\Unit\Saml2\Builder;

use Sugarcrm\IdentityProvider\Saml2\AuthPostBinding;
use Sugarcrm\IdentityProvider\Saml2\AuthRedirectBinding;
use Sugarcrm\IdentityProvider\Saml2\Builder\RequestBuilder;
use Sugarcrm\IdentityProvider\Saml2\Request\LogoutPostRequest;

/**
 * Test class for RequestBuilder logic.
 *
 * Class RequestBuilderTest
 * @package Sugarcrm\IdentityProvider\Tests\Unit\Saml2\Builder
 *
 * @coversDefaultClass Sugarcrm\IdentityProvider\Saml2\Builder\RequestBuilder
 */
class RequestBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \OneLogin_Saml2_Settings | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $settingsMock = null;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        $this->settingsMock = $this->getMockBuilder(\OneLogin_Saml2_Settings::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * Checks login request builder logic.
     *
     * @covers ::buildLoginRequest
     */
    public function testBuildLoginRequest()
    {
        $request = 'PG5vdGU+DQogIDx0bz5UZXN0PC90bz4NCjwvbm90ZT4=';
        $authMock = $this->getMockBuilder(\OneLogin_Saml2_Auth::class)->disableOriginalConstructor()->getMock();
        $authMock->method('getSettings')->willReturn($this->settingsMock);
        $requestBuilder = new RequestBuilder($authMock);
        $this->assertInstanceOf(\OneLogin_Saml2_AuthnRequest::class, $requestBuilder->buildLoginRequest($request));
    }

    /**
     * Provides various set of data for testBuildLogoutRequest
     * @return array
     */
    public static function buildLogoutRequestProvider()
    {
        return [
            'OneLoginAuth' => [
                'authClass' => \OneLogin_Saml2_Auth::class,
                'expectedRequest' => \OneLogin_Saml2_LogoutRequest::class,
            ],
            'IdmAuth' => [
                'authClass' => AuthPostBinding::class,
                'expectedRequest' => LogoutPostRequest::class,
            ],
            'IdmAuthRedirect' => [
                'authClass' => AuthRedirectBinding::class,
                'expectedRequest' => \OneLogin_Saml2_LogoutRequest::class,
            ],
        ];
    }

    /**
     * Checks logout request builder logic.
     *
     * @param string $authClass
     * @param string $expectedRequest
     *
     * @covers ::buildLogoutRequest
     * @dataProvider buildLogoutRequestProvider
     */
    public function testBuildLogoutRequest($authClass, $expectedRequest)
    {
        $parameters = [
            'nameId' => 'test@test.com',
            'sessionIndex' => 'sIndex',
        ];
        $authMock = $this->getMockBuilder($authClass)->disableOriginalConstructor()->getMock();
        $authMock->method('getSettings')->willReturn($this->settingsMock);

        $requestBuilder = new RequestBuilder($authMock);
        $logoutRequest = $requestBuilder->buildLogoutRequest(null, $parameters);
        $this->assertInstanceOf($expectedRequest, $logoutRequest);
        $xmlRequest = base64_decode($logoutRequest->getRequest());
        $this->assertEquals('test@test.com', \OneLogin_Saml2_LogoutRequest::getNameId($xmlRequest));
        $this->assertEquals('sIndex', \OneLogin_Saml2_LogoutRequest::getSessionIndexes($xmlRequest)[0]);
    }
}
