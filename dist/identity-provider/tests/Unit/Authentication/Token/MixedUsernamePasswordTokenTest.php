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

namespace Sugarcrm\IdentityProvider\Tests\Unit\Authentication\Token;

use Sugarcrm\IdentityProvider\Authentication\Token\MixedUsernamePasswordToken;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 *  @coversDefaultClass \Sugarcrm\IdentityProvider\Authentication\Token\MixedUsernamePasswordToken
 */
class MixedUsernamePasswordTokenTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::addToken
     * @covers ::getTokens
     */
    public function testAddToken()
    {
        $firstToken = new UsernamePasswordToken('first', 'first', 'first');
        $secondToken = new UsernamePasswordToken('second', 'second', 'second');

        $mixedToken = new MixedUsernamePasswordToken('mixed', 'mixed', 'mixed');
        $mixedToken->addToken($firstToken);
        $mixedToken->addToken($secondToken);

        $tokens = $mixedToken->getTokens();
        $this->assertEquals($firstToken, $tokens[0]);
        $this->assertEquals($secondToken, $tokens[1]);
    }
}
