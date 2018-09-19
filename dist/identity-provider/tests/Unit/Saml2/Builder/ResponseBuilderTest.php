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

use Sugarcrm\IdentityProvider\Saml2\AuthRedirectBinding;
use Sugarcrm\IdentityProvider\Saml2\AuthPostBinding;
use Sugarcrm\IdentityProvider\Saml2\Builder\ResponseBuilder;
use Sugarcrm\IdentityProvider\Saml2\Response\LogoutPostResponse;

/**
 * Test class for ResponseBuilder logic.
 *
 * Class ResponseBuilderTest
 * @package Sugarcrm\IdentityProvider\Tests\Unit\Saml2\Builder
 * @coversDefaultClass Sugarcrm\IdentityProvider\Saml2\Builder\ResponseBuilder
 */
class ResponseBuilderTest extends \PHPUnit_Framework_TestCase
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
     * Checks login response builder logic.
     *
     * @covers ::buildLoginResponse
     */
    public function testBuildLoginResponse()
    {
        $response = 'PG5vdGU+DQogIDx0bz5UZXN0PC90bz4NCjwvbm90ZT4=';
        $authMock = $this->getMockBuilder(\OneLogin_Saml2_Auth::class)->disableOriginalConstructor()->getMock();
        $authMock->method('getSettings')->willReturn($this->settingsMock);
        $responseBuilder = new ResponseBuilder($authMock);
        $this->assertInstanceOf(\OneLogin_Saml2_Response::class, $responseBuilder->buildLoginResponse($response));
    }

    /**
     * Provides various set of data for testBuildLogoutResponse
     * @return array
     */
    public static function buildLogoutResponseProvider()
    {
        return [
            'OneLoginAuth' => [
                'authClass' => \OneLogin_Saml2_Auth::class,
                'expectedResponse' => \OneLogin_Saml2_LogoutResponse::class,
            ],
            'IdmAuth' => [
                'authClass' => AuthPostBinding::class,
                'expectedResponse' => LogoutPostResponse::class,
            ],
            'IdmAuthRedirect' => [
                'authClass' => AuthRedirectBinding::class,
                'expectedResponse' => \OneLogin_Saml2_LogoutResponse::class,
            ],
        ];
    }

    /**
     * Checks logout response builder logic.
     *
     * @param string $authClass
     * @param string $expectedResponse
     *
     * @covers ::buildLogoutResponse
     * @dataProvider buildLogoutResponseProvider
     */
    public function testBuildLogoutResponse($authClass, $expectedResponse)
    {
        $response = 's8nLL0m14+VSULApybcLSS0usdEHMni5bPTBMgA=';
        $authMock = $this->getMockBuilder($authClass)->disableOriginalConstructor()->getMock();
        $authMock->method('getSettings')->willReturn($this->settingsMock);

        $responseBuilder = new ResponseBuilder($authMock);
        $this->assertInstanceOf($expectedResponse, $responseBuilder->buildLogoutResponse($response));
    }
}
