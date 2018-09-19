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

namespace Sugarcrm\Sugarcrm\Performance\Dbal;

use Doctrine\DBAL\Logging\SQLLogger;

/**
 * Logs queries for SugarXhprof class
 *
 * @see \SugarXHprof
 */
class XhprofLogger implements SQLLogger
{
    /**
     * Maximum length of the parameter value to dump
     * @var int
     */
    const MAX_PARAM_LENGTH = 100;

    /**
     * @var array
     */
    public $currentQuery;

    /**
     * @var float|null
     */
    protected $start = null;

    /**
     * @var SQLLogger
     */
    protected $logger;

    /**
     * @var \SugarXHprof
     */
    protected $xhprof;

    /**
     * @param \SugarXhprof $xhprof instance of SugarXhprof class
     * @param SQLLogger $logger instance of SQLLogger class
     */
    public function __construct(\SugarXHprof $xhprof, SQLLogger $logger)
    {
        $this->xhprof = $xhprof;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function startQuery($sql, array $params = null, array $types = null)
    {
        $this->logger->startQuery($sql, $params, $types);

        $this->start = microtime(true);
        $this->currentQuery = array('sql' => $sql, 'params' => $params, 'types' => $types);
    }

    /**
     * {@inheritdoc}
     */
    public function stopQuery()
    {
        $duration = microtime(true) - $this->start;

        $sql = $this->currentQuery['sql'] . ($this->currentQuery['params']
                ? (' with ' . $this->stringify($this->currentQuery['params']))
                : '');

        $this->xhprof->trackSQL($sql, $duration);

        $this->start = 0;
        $this->currentQuery = null;

        $this->logger->stopQuery();
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
}
