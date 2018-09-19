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

use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;


/**
 *
 * PSR-0 adapter for SugarLogger until Monolog is integrated.
 *
 */
class LoggerTransition extends AbstractLogger
{
    /**
     * @var \LoggerManager
     */
    protected $logger;

    /**
     * @var array Mapping from PSR0 to Sugar log levels
     */
    protected $psrSugarMap = array();

    /**
     * Constructor.
     * @param \SugarLogger $logger
     */
    public function __construct(\LoggerManager $logger)
    {
        $this->logger = $logger;
        $this->initMap();
    }

    /**
     * Initialize the mapping between PSR level and sugar level.
     */
    public function initMap()
    {
        if (empty($this->psrSugarMap)) {
            $this->psrSugarMap = array(
                LogLevel::EMERGENCY => 'fatal',
                LogLevel::ALERT => 'fatal',
                LogLevel::CRITICAL => 'fatal',
                LogLevel::ERROR => 'error',
                LogLevel::WARNING => 'warn',
                LogLevel::NOTICE => 'info',
                LogLevel::INFO => 'info',
                LogLevel::DEBUG => 'debug',
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function log($level, $message, array $context = array())
    {
        $callBack = array($this->logger, $this->getSugarLevel($level));

        // LoggerManager doesn't support context so lets skip it for now
        return call_user_func($callBack, $message);
    }

    /**
     * @return LoggerManager
     */
    public function getSugarLogger()
    {
        return $this->logger;
    }

    /**
     * Get the corresponding PSR-0 level, given a sugar level.
     * @param string $level
     * @return string
     */
    protected function getSugarLevel($level)
    {
        return $this->psrSugarMap[$level];
    }

}
