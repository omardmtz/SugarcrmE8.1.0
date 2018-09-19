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
 * Class ADFSSAMLFlowTest
 *
 * Test case to test that SP working with OneLogin.
 *
 * @package Sugarcrm\IdentityProvider\Tests\Functional\SAML
 */
class ADFSSAMLFlowTest extends SAMLFlowTest
{

    protected function setUp()
    {
        parent::setUp();

        $this->setProviderKey('ADFS');
    }

    public function responseProvider()
    {
        $adfsPath = $this->fixturesPath . '/ADFS';

        return [
            'Signed Response' => [$adfsPath . '/SignedResponse/Response.xml'],
        ];
    }

    public function logoutResponseProvider()
    {
        $fixturesPath = __DIR__ . '/fixtures';
        $adfsPath = $fixturesPath . '/ADFS';

        return [
            'Logout Response' => [$adfsPath . '/Logout/LogoutResponse.xml']
        ];
    }

    /**
     * To generate SAML Responses we use ADFS on Windows stack that requires relying party to be over SSL only.
     * @inheritdoc
     */
    public function testAuthnRequestFromServiceProvider()
    {
        $this->samlAcsEndpoint = 'https://localhost/saml/acs';
        parent::testAuthnRequestFromServiceProvider();
    }

    /**
     * @inheritdoc
     */
    public function getSamlParameters()
    {
        return IDMFixturesHelper::getADFSParameters();
    }
}
