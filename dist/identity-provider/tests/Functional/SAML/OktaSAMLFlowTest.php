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
 * Class OktaSAMLFlowTest
 *
 * Test case to test that SP working with Okta.
 *
 * @package Sugarcrm\IdentityProvider\Tests\Functional\SAML
 */
class OktaSAMLFlowTest extends SAMLFlowTest
{
    protected function setUp()
    {
        parent::setUp();

        $this->setProviderKey('Okta');
    }

    /**
     * @inheritdoc
     */
    public function getSamlParameters()
    {
        return IDMFixturesHelper::getOktaParameters();
    }

    public function responseProvider()
    {
        $oktaPath = $this->fixturesPath . '/Okta';

        return [
            'Encrypted Assertion(AES128)' => [$oktaPath . '/EncryptedAssertion-AES128/Response.xml'],
            'Encrypted Assertion(AES256)' => [$oktaPath . '/EncryptedAssertion-AES256/Response.xml'],
            'Signed Assertion' => [$oktaPath . '/SignedAssertion/Response.xml'],
            'SHA1 Signature, SHA1 Digest' => [$oktaPath . '/SignedResponseSHA1-SHA1/Response.xml'],
            'SHA1 Signature, SHA256 Digest' => [$oktaPath . '/SignedResponseSHA1-SHA256/Response.xml'],
            'SHA256 Signature, SHA1 Digest' => [$oktaPath . '/SignedResponseSHA256-SHA1/Response.xml'],
            'SHA256 Signature, SHA256 Digest' => [$oktaPath . '/SignedResponseSHA256-SHA256/Response.xml'],
        ];
    }

    public function logoutResponseProvider()
    {
        $fixturesPath = __DIR__ . '/fixtures';
        $oktaPath = $fixturesPath . '/Okta';

        return [
            'Logout Response' => [$oktaPath . '/Logout/LogoutResponse.xml']
        ];
    }

    /**
     * @inheritdoc
     */
    public function testLogoutRequest()
    {
        if ($this->samlLogoutEndpoint === false) {
            $this->markTestSkipped('You need to configure Logout url to execute LogoutRequest tests');
        }
        $this->webClient->request('GET', $this->samlLogoutEndpoint);
        $this->assertEquals(200, $this->webClient->getResponse()->getStatusCode());
        $this->assertContains('<form method="POST"', $this->webClient->getResponse()->getContent());
    }
}
