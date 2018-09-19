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

use Sugarcrm\IdentityProvider\Encoder\EncoderBuilder;
use Sugarcrm\IdentityProvider\Encoder\CryptPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;

class EncoderBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function buildEncoderDataProvider()
    {
        return [
            'default' => [BCryptPasswordEncoder::class, []],
            'blowfish' => [BCryptPasswordEncoder::class, ['passwordHash' => ['backend' => 'native']]],
            'sha2' => [CryptPasswordEncoder::class, ['passwordHash' => ['backend' => 'sha2']]],
        ];
    }

    /**
     * @dataProvider buildEncoderDataProvider
     */
    public function testBuildEncoder($expectedEncoderType, $config)
    {
        $encoderBuilder = new EncoderBuilder();
        $this->assertInstanceOf($expectedEncoderType, $encoderBuilder->buildEncoder($config));
    }
}
