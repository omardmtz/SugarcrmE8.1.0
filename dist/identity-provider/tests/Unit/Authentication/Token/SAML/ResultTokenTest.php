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

use Sugarcrm\IdentityProvider\Authentication\Token\SAML\AcsToken;
use Sugarcrm\IdentityProvider\Authentication\Token\SAML\ResultToken;

/**
 * @coversDefaultClass Sugarcrm\IdentityProvider\Authentication\Token\SAML\ResultToken
 */
class ResultTokenTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::getCredentials
     */
    public function testTokenCreation()
    {
        $attributes = ['attr1' => 'a1', 'attr2' => 'a2'];
        $sourceToken = new AcsToken('credentials');
        $sourceToken->setAttributes($attributes);
        $resultToken = new ResultToken($sourceToken->getCredentials(), $sourceToken->getAttributes());
        $this->assertEquals('credentials', $resultToken->getCredentials());
        $this->assertEquals($attributes, $resultToken->getAttributes());
    }
}

