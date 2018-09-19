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

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Psr\Log\LoggerTrait;

/**
 * Adapter of PSR-3 logger interface for existing components which use $GLOBAL['log'] for logging
 *
 * Should be used for components which use deprecated logging levels, or their custom versions may do so.
 */
class BackwardCompatibleAdapter implements LoggerInterface
{
    use LoggerTrait;

    /**
     * Map of the log levels defined in LoggerManager to the levels in defined in RFC 5424
     *
     * @var array
     */
    protected static $levelMap = array(
        'fatal' => LogLevel::ALERT,
        'security' => LogLevel::CRITICAL,
        'warn' => LogLevel::WARNING,
        'deprecated' => LogLevel::NOTICE,
    );

    /**
     * Underlying pure PSR-3 logger
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Constructor
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Handles deprecated method call by translating the level into a PSR-3 compatible and prepending the
     * original method to the message which then will be handled by legacy formatter
     *
     * @param string $method Method name
     * @param array $arguments Method arguments
     */
    public function __call($method, array $arguments)
    {
        $message = array_shift($arguments);

        if (isset(self::$levelMap[$method])) {
            // use mapping from Sugar log levels to PSR-3 ones
            $level = self::$levelMap[$method];
            // prepend the original level for backward compatible formatting
            $message = '[LEVEL:' . $method . '] ' . $message;
        } else {
            $level = LogLevel::ERROR;
        }

        $this->log($level, $message);
    }

    /** {@inheritDoc} */
    public function log($level, $message, array $context = array())
    {
        $this->logger->log($level, $message, $context);
    }
}
