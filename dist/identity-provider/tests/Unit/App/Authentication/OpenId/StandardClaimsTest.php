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

namespace Sugarcrm\IdentityProvider\Tests\Unit\App\Authentication\OpenId;

use Sugarcrm\IdentityProvider\App\Authentication\OpenId\StandardClaims;
use Sugarcrm\IdentityProvider\Authentication\User;

class StandardClaimsTest extends \PHPUnit_Framework_TestCase
{
    public function testGetUserClaims()
    {
        $user = new User(null, 'password', [
            'id' => 'user_id',
            'identity_value' => 'openid_identity',
            'status' => 0,
            'create_time' => '2017-12-31',
            'attributes' => [
                'given_name' => 'user_first_name',
                'family_name' => 'user_family_name',
                'middle_name' => 'user_middle_name',
                'nickname' => 'user_nickname',
                'email' => 'user@email.com',
                'phone_number' => '+1234567890',
                'address' => [
                    'country' => 'US',
                ],
            ],
        ]);

        $claims = (new StandardClaims())->getUserClaims($user);
        $this->assertEquals('openid_identity', $claims['preferred_username']);
        $this->assertEquals('2017-12-31', $claims['created_at']);
        $this->assertArrayNotHasKey('updated_at', $claims);
        $this->assertEquals('user_first_name', $claims['given_name']);
        $this->assertEquals('user_family_name', $claims['family_name']);
        $this->assertEquals('user_middle_name', $claims['middle_name']);
        $this->assertEquals('user_nickname', $claims['nickname']);
        $this->assertEquals('user@email.com', $claims['email']);
        $this->assertEquals('+1234567890', $claims['phone_number']);
        $this->assertArrayHasKey('country', $claims['address']);
    }

    public function testGetUserClaimsUsernameSets()
    {
        $user = new User('user_name', null, [
            'id' => 'user_id',
        ]);
        $claims = (new StandardClaims())->getUserClaims($user);
        $this->assertEquals('user_name', $claims['preferred_username']);

    }
}
