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

namespace Sugarcrm\IdentityProvider\Tests\Unit\Encoder;

use Sugarcrm\IdentityProvider\Encoder\CryptPasswordEncoder;

class CryptPasswordEncoderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \LogicException
     */
    public function testEncodePasswordWrongAlgorithm()
    {
        $encoder = new CryptPasswordEncoder('WRONG_ALGO');
        $encoder->encodePassword('secret', 'salt');
    }

    public function encodePasswordDataProvider()
    {
        return [
            'sha256' => ['$5$rounds=5000$1234567812345678$Sa4x4MSMvm7BYJ.3QjZ3L78pJMU43uVzWiLcyaHKO/6', 'CRYPT_SHA256', 'secret', '1234567812345678'],
            'sha512' => ['$6$rounds=5000$1234567812345678$Wp0T1sROWYtpVmjGEkJgfyqU.0GplKlcpJhL5r9XUJkVRZI/B50T3KBzYtemPYzHRfNV/stOdfwdgcjkt7tY7.', 'CRYPT_SHA512', 'secret', '1234567812345678'],
        ];
    }

    /**
     * @dataProvider encodePasswordDataProvider
     */
    public function testEncodePassword($expectedHash, $algo, $secret, $salt)
    {
        $encoder = new CryptPasswordEncoder($algo);
        $this->assertEquals($expectedHash, $encoder->encodePassword($secret, $salt));
    }

    public function isPasswordValidDataProvider()
    {
        return [
            'sha256_valid' => [true, 'CRYPT_SHA256', '$5$rounds=5000$1234567812345678$Sa4x4MSMvm7BYJ.3QjZ3L78pJMU43uVzWiLcyaHKO/6', 'secret', '1234567812345678'],
            'sha512_valid' => [true, 'CRYPT_SHA512', '$6$rounds=5000$1234567812345678$Wp0T1sROWYtpVmjGEkJgfyqU.0GplKlcpJhL5r9XUJkVRZI/B50T3KBzYtemPYzHRfNV/stOdfwdgcjkt7tY7.', 'secret', '1234567812345678'],
            'sha256_empty_salt' => [true, 'CRYPT_SHA256', '$5$rounds=5000$1234567812345678$Sa4x4MSMvm7BYJ.3QjZ3L78pJMU43uVzWiLcyaHKO/6', 'secret', ''],
            'sha256_any_salt' => [true, 'CRYPT_SHA256', '$5$rounds=5000$1234567812345678$Sa4x4MSMvm7BYJ.3QjZ3L78pJMU43uVzWiLcyaHKO/6', 'secret', 'anything, we ignore it'],
            'sha256_invalid' => [false, 'CRYPT_SHA256', '$5$rounds=5000$1234567812345678$wrongHash', 'secret', '1234567812345678'],
        ];
    }

    /**
     * @dataProvider isPasswordValidDataProvider
     */
    public function testIsPasswordValid($expected, $algo, $hash, $secret, $salt)
    {
        $encoder = new CryptPasswordEncoder($algo);
        $this->assertEquals($expected, $encoder->isPasswordValid($hash, $secret, $salt));
    }
}
