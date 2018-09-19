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

namespace Sugarcrm\IdentityProvider\Tests\Unit\Saml2;

use Sugarcrm\IdentityProvider\Saml2\AuthResult;

/**
 * @coversDefaultClass Sugarcrm\IdentityProvider\Saml2\AuthResult
 */
class AuthResultTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Checks class logic.
     * @covers ::__construct
     * @covers ::getUrl
     * @covers ::getMethod
     * @covers ::getAttributes
     */
    public function testParametersPopulation()
    {
        $authResult = new AuthResult('http://test.com', 'POST', ['a'=>'b']);
        $this->assertEquals('http://test.com', $authResult->getUrl());
        $this->assertEquals('POST', $authResult->getMethod());
        $this->assertEquals(['a'=>'b'], $authResult->getAttributes());
    }
}
