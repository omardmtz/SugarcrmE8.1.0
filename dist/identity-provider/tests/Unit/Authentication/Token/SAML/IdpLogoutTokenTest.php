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
namespace Sugarcrm\IdentityProvider\Tests\Unit\Authentication\Token\SAML;

use Sugarcrm\IdentityProvider\Authentication\Token\SAML\ActionTokenInterface;
use Sugarcrm\IdentityProvider\Authentication\Token\SAML\IdpLogoutToken;
use Sugarcrm\IdentityProvider\Tests\IDMFixturesHelper;

/**
 * @coversDefaultClass Sugarcrm\IdentityProvider\Authentication\Token\SAML\IdpLogoutToken
 */
class IdpLogoutTokenTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test to check that AcsLogoutToken has credentials.
     *
     * @covers ::getCredentials
     */
    public function testGetCredentials()
    {
        $samlRequest = IDMFixturesHelper::getSAMLFixture('OneLogin/Logout/idpLogoutRequest.xml');
        $token = new IdpLogoutToken($samlRequest);
        $this->assertEquals($samlRequest, $token->getCredentials());
    }

    /**
     * Tests token action.
     *
     * @covers ::getAction
     */
    public function testGetAction()
    {
        $token = new IdpLogoutToken('');
        $this->assertEquals(ActionTokenInterface::LOGOUT_ACTION, $token->getAction());
    }
}
