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

use Sugarcrm\Sugarcrm\Logger\Factory;
use Sugarcrm\Sugarcrm\Logger\BackwardCompatibleAdapter;

require_once 'include/SugarLogger/LoggerTemplate.php';

/**
 * SugarCRM adapter for PSR-3 compatible logger
 */
class SugarPsrLogger implements LoggerTemplate
{
    /**
     * Backward compatible PSR-3 logger
     *
     * @var BackwardCompatibleAdapter
     */
    protected $psrLogger;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->psrLogger = Factory::getInstance()->createLoggerForLoggerManager('default');
    }

    /**
     * {@inheritDoc}
     */
    public function log($method, $message)
    {
        // for compatibility with LoggerManager::__call() and SugarLogger::log()
        if (is_array($message)) {
            if (count($message) == 1) {
                $message = array_shift($message);
            } else {
                $message = print_r($message, true);
            }
        }

        $this->psrLogger->$method($message);
    }
}
