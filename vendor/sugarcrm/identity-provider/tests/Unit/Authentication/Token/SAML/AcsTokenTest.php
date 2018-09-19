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

use PHPUnit_Framework_TestCase;
use Sugarcrm\IdentityProvider\Authentication\Token\SAML\AcsToken;
use Sugarcrm\IdentityProvider\Authentication\Token\SAML\ActionTokenInterface;

/**
 * Class SAMLAcsTokenTest
 *
 * Class to test SAML Token.
 *
 * @package Sugarcrm\IdentityProvider\Tests\Unit\Token\SAML
 *
 * @coversDefaultClass Sugarcrm\IdentityProvider\Authentication\Token\SAML\AcsToken
 */
class AcsTokenTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test to check that AcsToken credentials is equal to SAML Response passed to it.
     *
     * @covers ::getCredentials
     */
    public function testCredentials()
    {
        $response = 'SOME RESPONSE';
        $token = new AcsToken($response);
        $this->assertEquals($response, $token->getCredentials());
    }

    /**
     * Tests token action.
     *
     * @covers ::getAction
     */
    public function testGetAction()
    {
        $token = new AcsToken('');
        $this->assertEquals(ActionTokenInterface::LOGIN_ACTION, $token->getAction());
    }
}
