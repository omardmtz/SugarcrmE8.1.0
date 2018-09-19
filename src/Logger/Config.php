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

namespace Sugarcrm\Sugarcrm\Logger;

use Monolog\Logger;

/**
 * Logger configuration
 */
class Config
{
    /**
     * Mapping of SugarCRM log levels to the Monolog's ones
     *
     * @var array
     */
    protected static $levels = array(
        'debug' => Logger::DEBUG,
        'info'  => Logger::INFO,
        'warn' => Logger::WARNING,
        'deprecated' => Logger::NOTICE,
        'error' => Logger::ERROR,
        'fatal' => Logger::ALERT,
        'security' => Logger::CRITICAL,
    );

    /**
     * @var \SugarConfig
     */
    protected $config;

    /**
     * Constructor
     *
     * @param \SugarConfig $config Application configuration
     */
    public function __construct(\SugarConfig $config)
    {
        $params = array(
            'log_dir' => 'logger.handlers.file.dir',
            'logger.file' => 'logger.handlers.file',
        );

        // copy file logger settings under the "handlers" section for unification
        foreach ($params as $src => $dst) {
            $config->get($dst, $config->get($src));
        }

        $this->config = $config;
    }

    /**
     * Returns the configuration of the given channel. It will contain all needed parameters for handlers.
     *
     * @param string $channel Channel name
     * @return array
     */
    public function getChannelConfig($channel)
    {
        $config = $this->config->get('logger.channels.' . $channel, array());

        if (isset($config['handlers'])) {
            $config['handlers'] = $this->normalizeConfig($config['handlers']);
        } else {
            // set empty handler definition which will be later populated with the default values
            $config['handlers'] = array(array());
        }

        if (isset($config['processors'])) {
            $config['processors'] = $this->normalizeConfig($config['processors']);
        } else {
            $config['processors'] = array();
        }

        $config = $this->expandChannelConfig($channel, $config);

        return $config;
    }

    /**
     * Normalizes the value retrieved from the configuration file
     *
     * @param mixed $components
     * @return array
     */
    protected function normalizeConfig($components)
    {
        $result = array();

        if (is_string($components)) {
            $components = array($components);
        }

        $isAssociativeArray = array_keys($components) !== range(0, count($components) - 1);
        foreach ($components as $key => $value) {
            if (is_string($value)) {
                $result[] = array(
                    'type' => $value,
                );
            } else {
                $normalized = array();
                if (isset($value['type'])) {
                    $normalized['type'] = $value['type'];
                    unset($value['type']);
                } elseif ($isAssociativeArray) {
                    $normalized['type'] = $key;
                }

                if (isset($value['level'])) {
                    $normalized['level'] = Logger::toMonologLevel($value['level']);
                    unset($value['level']);
                }

                if ($value) {
                    $normalized['params'] = $value;
                }

                $result[] = $normalized;
            }
        }

        return $result;
    }

    /**
     * Populates configuration of the channel handlers with the values from the channel configuration,
     * default handler configuration and default system configuration.
     *
     * @param string $channel Channel name
     * @param array $config Channel configuration
     *
     * @return array
     */
    protected function expandChannelConfig($channel, array $config)
    {
        foreach ($config['handlers'] as &$handler) {
            if (!isset($handler['type'])) {
                $handler['type'] = $this->config->get('logger.handler', 'file');
            }
            $type = $handler['type'];
            if (!isset($handler['level'])) {
                $handler['level'] = $this->getHandlerLevel($channel, $type);
            }

            $params = $this->config->get('logger.handlers.' . $type, array());
            if (isset($handler['params'])) {
                $params = array_merge($params, $handler['params']);
            }
            $handler['params'] = $params;
            unset($handler);
        }

        foreach ($config['processors'] as &$processor) {
            $processor['params'] = array();
            unset($processor);
        }

        return array(
            'handlers' => $config['handlers'],
            'processors' => $config['processors'],
        );
    }

    /**
     * Returns handler level for the given channel in case if it's not explicitly defined
     *
     * @param string $channel Channel name
     * @param string $handler Handler type
     *
     * @return int
     */
    protected function getHandlerLevel($channel, $handler)
    {
        $levels = array_filter(array(
            $this->config->get('logger.channels.' . $channel . '.level'),
            $this->config->get('logger.handlers.' . $handler . '.level'),
        ));
        $level = array_shift($levels);
        if ($level) {
            return Logger::toMonologLevel($level);
        }

        $level = $this->config->get('logger.level');
        if ($level === 'off') {
            return 0;
        }

        if ($level && isset(self::$levels[$level])) {
            $level = self::$levels[$level];
        } else {
            $level = Logger::ALERT;
        }

        return $level;
    }
}
