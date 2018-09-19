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

namespace Sugarcrm\IdentityProvider\Tests\Unit\App\Provider;

use Sugarcrm\IdentityProvider\App\Application;
use Sugarcrm\IdentityProvider\App\Provider\ConfigServiceProvider;

/**
 * Class ConfigServiceProviderTest
 * @package Sugarcrm\IdentityProvider\Tests\Unit\App\Provider
 * @coversDefaultClass Sugarcrm\IdentityProvider\App\Provider\ConfigServiceProvider
 */
class ConfigServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Provides various set of data for testRegister
     * @return array
     */
    public function registerProvider()
    {
        return [
            'validEnvironmentEmptyConfig' => [
                'path' => __DIR__ . '/../../../..',
                'env' => Application::ENV_TESTS,
                'currentConfig' => [],
                'configOverride' => [],
                'expectedConfig' => [
                    'logdir' => __DIR__ . '/../../../../var/logs',
                    'monolog' => [
                        'monolog.logfile' => __DIR__ . '/../../../../var/logs/' . Application::ENV_TESTS . '.log',
                    ],
                ],
            ],
            'validEmptyConfigAndNotEmptyOverride' => [
                'path' => __DIR__ . '/../../../..',
                'env' => Application::ENV_TESTS,
                'currentConfig' => [],
                'configOverride' => [
                    'logdir' => null,
                    'monolog' => null,
                ],
                'expectedConfig' => [
                    'logdir' => null,
                    'monolog' => null,
                ],
            ],
            'validNotEmptyConfigAndEmptyOverride' => [
                'path' => __DIR__ . '/../../../..',
                'env' => Application::ENV_TESTS,
                'currentConfig' => [
                    'configValue' => [
                        'value1' => 1,
                        'value2' => 2,
                    ],
                ],
                'configOverride' => [],
                'expectedConfig' => [
                    'logdir' => __DIR__ . '/../../../../var/logs',
                    'monolog' => [
                        'monolog.logfile' => __DIR__ . '/../../../../var/logs/' . Application::ENV_TESTS . '.log',
                    ],
                    'configValue' => [
                        'value1' => 1,
                        'value2' => 2,
                    ],
                ],
            ],
            'validNotEmptyConfigAndNotEmptyOverride' => [
                'path' => __DIR__ . '/../../../..',
                'env' => Application::ENV_TESTS,
                'currentConfig' => [
                    'configValue' => [
                        'value1' => 1,
                        'value2' => 2,
                    ],
                ],
                'configOverride' => [
                    'configValue' => [
                        'value1' => 3,
                    ],
                ],
                'expectedConfig' => [
                    'logdir' => __DIR__ . '/../../../../var/logs',
                    'monolog' => [
                        'monolog.logfile' => __DIR__ . '/../../../../var/logs/' . Application::ENV_TESTS . '.log',
                    ],
                    'configValue' => [
                        'value1' => 3,
                        'value2' => 2,
                    ],
                ],
            ],
            'validNotEmptyConfigWithDebug' => [
                'path' => __DIR__ . '/../../../..',
                'env' => Application::ENV_TESTS,
                'currentConfig' => [
                    'configValue' => [
                        'value1' => 1,
                        'value2' => 2,
                    ],
                    'debug' => true,
                ],
                'configOverride' => [
                    'configValue' => [
                        'value1' => 3,
                    ],
                ],
                'expectedConfig' =>
                    [
                        'logdir' => __DIR__ . '/../../../../var/logs',
                        'monolog' => [
                            'monolog.logfile' => __DIR__ . '/../../../../var/logs/' . Application::ENV_TESTS . '.log',
                        ],
                        'configValue' => [
                            'value1' => 3,
                            'value2' => 2,
                        ],
                        'debug' => true,
                    ],
            ],
        ];
    }

    /**
     * @param $path
     * @param $env
     * @param array $currentConfig
     * @param array $configOverride
     * @param array $expectedConfig
     *
     * @covers ::register
     * @dataProvider registerProvider
     */
    public function testRegister(
        $path,
        $env,
        array $currentConfig,
        array $configOverride,
        array $expectedConfig
    ) {
        $application = $this->getMockBuilder(Application::class)
                            ->disableOriginalConstructor()
                            ->setMethods(['getRootDir', 'getEnv'])
                            ->getMock();
        $application->method('getRootDir')->willReturn($path);
        $application->method('getEnv')->willReturn($env);
        $configProvider = $this->getMockBuilder(ConfigServiceProvider::class)
                               ->setConstructorArgs([$configOverride])
                               ->setMethods(['loadConfig'])->getMock();
        $configProvider->expects($this->once())->method('loadConfig')->willReturn($currentConfig);
        $configProvider->register($application);

        $this->assertEquals($application['config'], $expectedConfig);
    }

    /**
     * Provides various set of data for testRegisterWithException
     * @return array
     */
    public function registerWithInvalidConfigPathProvider()
    {
        return [
            'invalidConfigPath' => [
                'path' => __DIR__ . '/../../../../..',
                'env' => Application::ENV_TESTS,
            ],
            'invalidEnvironment' => [
                'path' => __DIR__ . '/../../../..',
                'env' => 'invalid',
            ],
        ];
    }

    /**
     * @param $path
     * @param $env
     *
     * @covers ::register
     * @dataProvider registerWithInvalidConfigPathProvider
     *
     * @expectedException \RuntimeException
     */
    public function testRegisterWithInvalidConfigPath($path, $env)
    {
        $application = $this->getMockBuilder(Application::class)
                            ->disableOriginalConstructor()
                            ->setMethods(['getRootDir', 'getEnv'])
                            ->getMock();
        $application->method('getRootDir')->willReturn($path);
        $application->method('getEnv')->willReturn($env);
        $configProvider = $this->getMockBuilder(ConfigServiceProvider::class)
                               ->setConstructorArgs([])
                               ->setMethods(['loadConfig'])->getMock();
        $configProvider->register($application);
    }
}
