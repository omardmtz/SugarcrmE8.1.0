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

use Monolog\Handler\HandlerInterface;
use Monolog\Handler\NullHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use SugarAutoLoader;
use SugarConfig;
use Sugarcrm\Sugarcrm\Logger\Handler\Factory as HandlerFactory;
use Sugarcrm\Sugarcrm\Logger\Processor\Factory as ProcessorFactory;

/**
 * Logger factory
 */
class Factory
{
    /**
     * Instantiated factory
     *
     * @var self
     */
    protected static $instance;

    /**
     * Instantiated loggers
     *
     * @var self[]
     */
    protected static $loggers;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var HandlerFactory[]
     */
    protected $handlerFactories;

    /**
     * @var ProcessorFactory[]
     */
    protected $processorFactories;

    /**
     * Constructor
     *
     * @param Config $config Logger configuration
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Instantiates the factory
     *
     * @return Factory
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            $sugarConfig = SugarConfig::getInstance();
            $config = new Config($sugarConfig);
            self::$instance = new self($config);
        }

        return self::$instance;
    }

    /**
     * Returns logger for the given channel
     *
     * @param string $channel Channel name
     * @return LoggerInterface
     */
    public static function getLogger($channel)
    {
        if (!isset(self::$loggers[$channel])) {
            self::$loggers[$channel] = self::getInstance()->createLogger($channel);
        }

        return self::$loggers[$channel];
    }

    /**
     * Creates logging channel for LoggerManager
     *
     * @param string $channel Channel name
     * @return Logger
     */
    public function createLoggerForLoggerManager($channel)
    {
        return $this->createLogger($channel, Logger::DEBUG);
    }

    /**
     * Creates logger for the given channel
     *
     * @param string $channel Channel name
     * @param int|null $forceLevel The logging level to be applied to all handlers disregarding configuration
     *                             (only needed for LoggerManager compatibility)
     * @return LoggerInterface
     */
    protected function createLogger($channel, $forceLevel = null)
    {
        $config = $this->config->getChannelConfig($channel);

        $handlers = array();
        foreach ($config['handlers'] as $handler) {
            $handlers[] = $this->createHandler($handler['type'], $forceLevel ?: $handler['level'], $handler['params']);
        }

        $processors = array();
        foreach ($config['processors'] as $processor) {
            $processors[] = $this->createProcessor($processor['type'], $processor['params']);
        }

        // we will remove this wrapper once we drop support for old levels
        return new BackwardCompatibleAdapter(
            new Logger($channel, $handlers, $processors)
        );
    }

    /**
     * Creates handler instance
     *
     * @param string $type Handler type
     * @param int $level Logging level
     * @param array $params Handler-specific parameters
     * @return HandlerInterface
     */
    protected function createHandler($type, $level, array $params)
    {
        if ($level == 0) {
            return new NullHandler();
        }

        $factory = $this->getHandlerFactory($type);
        $handler = $factory->create($level, $params);

        return $handler;
    }

    /**
     * Creates a processor instance
     *
     * @param string $type Processor type
     * @param array $params Processor-specific parameters
     * @return callable
     */
    protected function createProcessor($type, array $params)
    {
        return $this->getProcessorFactory($type)->create($params);
    }

    /**
     * Returns factory for handlers of the given type
     *
     * @param string $type Handler type
     * @return HandlerFactory
     */
    protected function getHandlerFactory($type)
    {
        if (!isset($this->handlerFactories[$type])) {
            $class = 'Sugarcrm\\Sugarcrm\\Logger\\Handler\\Factory\\' . ucfirst($type);
            $class = SugarAutoLoader::customClass($class);
            if (!class_exists($class)) {
                throw new \InvalidArgumentException('Unsupported handler type ' . $type);
            }
            $this->handlerFactories[$type] = new $class;
        }

        return $this->handlerFactories[$type];
    }

    /**
     * Returns factory for processors of the given type
     *
     * @param string $type Processor type
     * @return ProcessorFactory
     */
    protected function getProcessorFactory($type)
    {
        if (!isset($this->processorFactories[$type])) {
            $class = 'Sugarcrm\\Sugarcrm\\Logger\\Processor\\Factory\\' . ucfirst($type);
            $class = SugarAutoLoader::customClass($class);
            if (!class_exists($class)) {
                throw new \InvalidArgumentException('Unsupported processor type ' . $type);
            }
            $this->processorFactories[$type] = new $class;
        }

        return $this->processorFactories[$type];
    }
}
