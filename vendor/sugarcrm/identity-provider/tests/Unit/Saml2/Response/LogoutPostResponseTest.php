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

namespace Sugarcrm\IdentityProvider\Tests\Unit\Saml2\Response;

use Sugarcrm\IdentityProvider\Saml2\Response\LogoutPostResponse;
use Sugarcrm\IdentityProvider\Tests\IDMFixturesHelper;

/**
 * Class LogoutPostResponseTest
 * @package Sugarcrm\IdentityProvider\Tests\Unit\Saml2\Response
 * @coversDefaultClass Sugarcrm\IdentityProvider\Saml2\Response\LogoutPostResponse
 */
class LogoutPostResponseTest extends \PHPUnit_Framework_TestCase
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

        $this->settingsMock->method('getSPData')->willReturn(
            [
                'entityId' => 'spEntityId',
                'assertionConsumerService' => [
                    'url' => 'http://sp.com/acs',
                    'binding' => \OneLogin_Saml2_Constants::BINDING_HTTP_POST,
                ],
                'singleLogoutService' => [
                    'url' => 'http://sp.com/logout',
                    'binding' => \OneLogin_Saml2_Constants::BINDING_HTTP_REDIRECT,
                ],
                'NameIDFormat' => \OneLogin_Saml2_Constants::NAMEID_EMAIL_ADDRESS,
                'x509cert' => IDMFixturesHelper::getSpPublicKey(),
                'privateKey' => IDMFixturesHelper::getSpPrivateKey(),
            ]
        );
        $this->settingsMock->method('getIdPData')->willReturn(
            [
                'entityId' => 'idpEntityId',
                'singleSignOnService' => [
                    'url' => 'http://idp.com/saml/sso',
                    'binding' => \OneLogin_Saml2_Constants::BINDING_HTTP_REDIRECT,
                ],
                'singleLogoutService' => [
                    'url' => 'http://idp.com/saml/slo',
                    'binding' => \OneLogin_Saml2_Constants::BINDING_HTTP_POST,
                ],
                'x509cert' => IDMFixturesHelper::getIdpX509Key('Okta'),
            ]
        );
    }

    /**
     * Provides various set of data for testIsValid.
     *
     * @return array
     */
    public static function isValidProvider()
    {
        return [
            'validNotSignedResponse' => [
                'response' => 'OneLogin/Logout/LogoutResponse.xml',
                'strict' => false,
                'security' => [],
                'expectedResult' => true,
                'errorMessage' => '',
            ],
            'validSignedResponse' => [
                'response' => 'Okta/Logout/LogoutResponse.xml',
                'strict' => false,
                'security' => [
                    'wantMessagesSigned' => true,
                    'signatureAlgorithm' => 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256',
                ],
                'expectedResult' => true,
                'errorMessage' => '',
            ],
            'invalidInResponseToNotSignedResponse' => [
                'response' => 'OneLogin/Logout/LogoutResponse.xml',
                'strict' => true,
                'security' => [
                    'wantXMLValidation' => true,
                ],
                'expectedResult' => false,
                'errorMessage' => 'The InResponseTo of the Logout Response: '
                    . 'ONELOGIN_5725d3ab89492543a4c29f5d00d35a6a1783b192, does not'
                    . ' match the ID of the Logout request sent by the SP: test',
            ],
            'invalidSignedResponse' => [
                'response' => 'OneLogin/Logout/LogoutResponse.xml',
                'strict' => false,
                'security' => [
                    'wantMessagesSigned' => true,
                    'signatureAlgorithm' => 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256',
                ],
                'expectedResult' => false,
                'errorMessage' => 'Cannot locate Signature Node',
            ],
        ];
    }

    /**
     * Checks isValid logic.
     *
     * @param string $response
     * @param bool $strict
     * @param array $security
     * @param bool $expectedResult
     * @param string $errorMessage
     *
     * @covers ::isValid
     * @dataProvider isValidProvider
     */
    public function testIsValid($response, $strict, array $security, $expectedResult, $errorMessage)
    {
        $this->settingsMock->method('getSecurityData')->willReturn($security);
        $this->settingsMock->method('isStrict')->willReturn($strict);
        $response = base64_encode(IDMFixturesHelper::getSAMLFixture($response));
        $logoutResponse = new LogoutPostResponse($this->settingsMock, $response);
        $this->assertEquals($expectedResult, $logoutResponse->isValid('test'));
        $this->assertEquals($errorMessage, $logoutResponse->getError());
    }
}
