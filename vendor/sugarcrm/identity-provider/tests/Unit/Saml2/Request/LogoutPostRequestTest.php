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

namespace Sugarcrm\IdentityProvider\Tests\Unit\Saml2\Request;

use Sugarcrm\IdentityProvider\Saml2\Request\LogoutPostRequest;
use Sugarcrm\IdentityProvider\Tests\IDMFixturesHelper;

/**
 *
 * Class LogoutPostRequestTest
 * @package Sugarcrm\IdentityProvider\Tests\Unit\Saml2\Request
 * @coversDefaultClass Sugarcrm\IdentityProvider\Saml2\Request\LogoutPostRequest
 */
class LogoutPostRequestTest extends \PHPUnit_Framework_TestCase
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
            ]
        );
    }

    /**
     * Checks that request is not signed on creation.
     *
     * @covers ::__construct
     */
    public function testNotSignedLogoutRequest()
    {
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
        $logoutRequest = new LogoutPostRequest($this->settingsMock);
        $request = $logoutRequest->getRequest();
        $xmlRequest = base64_decode($request);
        $this->assertNotContains('<ds:SignatureMethod Algorithm', $xmlRequest);
    }

    /**
     * Checks that request is signed on creation.
     *
     * @covers ::__construct
     */
    public function testSignedLogoutRequest()
    {
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

        $this->settingsMock->method('getSecurityData')->willReturn(
            [
                'logoutRequestSigned' => true,
                'signatureAlgorithm' => 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256',
            ]
        );

        $logoutRequest = new LogoutPostRequest($this->settingsMock);
        $request = $logoutRequest->getRequest();
        $xmlRequest = base64_decode($request);
        $this->assertContains(
            '<ds:SignatureMethod Algorithm="http://www.w3.org/2001/04/xmldsig-more#rsa-sha256"/>',
            $xmlRequest
        );
    }

    /**
     * Checks that ConfigurationException is throw.
     *
     * @covers ::__construct
     * @expectedException Sugarcrm\IdentityProvider\Authentication\Exception\ConfigurationException
     */
    public function testSignedRequestWithoutKeys()
    {
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
            ]
        );

        $this->settingsMock->method('getSecurityData')->willReturn(
            [
                'logoutRequestSigned' => true,
                'signatureAlgorithm' => 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256',
            ]
        );
        new LogoutPostRequest($this->settingsMock);
    }
}
