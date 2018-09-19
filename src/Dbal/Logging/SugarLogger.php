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

namespace Sugarcrm\Sugarcrm\Dbal\Logging;

/**
 * Logs queries into sugarcrm log
 */
class SugarLogger implements \Doctrine\DBAL\Logging\SQLLogger
{
    /**
     * Logging level
     * @var string
     */
    const LEVEL = 'info';
    /**
     * Maximum length of the parameter value to dump
     * @var int
     */
    const MAX_PARAM_LENGTH = 100;

    /**
     * Sugar log
     *
     * @var \LoggerManager
     */
    protected $logger;

    /**
     * @param \LoggerManager $logger Sugar log, usually $GLOBALS['log']
     */
    public function __construct(\LoggerManager $logger)
    {
        $this->logger = $logger;
    }

    public function startQuery($sql, array $params = null, array $types = null)
    {
        if ($this->logger->wouldLog(self::LEVEL)) {
            $message = 'Query: ' . $sql;
            if (count($params)) {
                $message .= PHP_EOL . 'Params: ' . $this->stringify($params);
            }
            if (count($types)) {
                $message .= PHP_EOL . 'Types: ' . $this->stringify($types);
            }
            $this->log($message);
        }
    }

    public function stopQuery()
    {
    }

    /**
     * @param array $message Array to log
     *
     * @return string
     */
    protected function stringify(array $message)
    {
        return json_encode(
            array_map(
                function ($str) {
                    if (is_string($str) && (strlen($str) > self::MAX_PARAM_LENGTH)) {
                        $str = substr($str, 0, self::MAX_PARAM_LENGTH) . '...';
                    }
                    return $str;
                },
                $message
            )
        );
    }

    /**
     * @param string $message
     */
    protected function log($message)
    {
        call_user_func(array($this->logger, self::LEVEL), $message);
    }
}
