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

namespace Sugarcrm\IdentityProvider\App\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Simple config service.
 *
 * Class ConfigServiceProvider
 * @package Sugarcrm\IdentityProvider\App\Provider
 */
class ConfigServiceProvider implements ServiceProviderInterface
{
    protected $configOverride = [];
    public function __construct(array $configOverride = [])
    {
        $this->configOverride = $configOverride;
    }

    public function register(Container $app)
    {
        $configPath = sprintf('%s/app/config/%s.php', $app->getRootDir(), $app->getEnv());

        if (!file_exists($configPath)) {
            throw new \RuntimeException(sprintf('The config "%s" is not found.', $configPath));
        }

        $config = $this->loadConfig($configPath);
        // check default configs
        if (empty($config['monolog']['monolog.logfile'])) {
            if (empty($config['logdir'])) {
                $config['logdir'] = $app->getRootDir() . '/var/logs';
            }
            $config['monolog']['monolog.logfile'] = $config['logdir'] . '/' . $app->getEnv() . '.log';
        }

        if (isset($config['debug']) && $config['debug'] === true) {
            $app['debug'] = true;
        }

        $config = array_replace_recursive($config, $this->configOverride);

        $app['config'] = $config;
    }

    /**
     * Load config by path.
     *
     * @param $configPath
     * @return array
     */
    protected function loadConfig($configPath)
    {
        /* @var array $config here */
        require $configPath;

        return $config;
    }
}
