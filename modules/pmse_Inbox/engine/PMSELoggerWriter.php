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


class PMSELoggerWriter extends SugarLogger
{

    public function __construct()
    {
        $config = SugarConfig::getInstance();
        $this->ext = '.log';
        $this->logfile = 'PMSE';
        $this->dateFormat = $config->get('logger.file.dateFormat', $this->dateFormat);
        $this->logSize = $config->get('logger.file.maxSize', $this->logSize);
        $this->maxLogs = $config->get('logger.file.maxLogs', $this->maxLogs);
        $this->filesuffix = $config->get('logger.file.suffix', $this->filesuffix);
        $log_dir = $config->get('log_dir', $this->log_dir);
        $this->log_dir = $log_dir . (empty($log_dir) ? '' : '/');
        unset($config);
        $this->_doInitialization();
    }

    /**
     *
     * @param type $dateFormat
     * @codeCoverageIgnore
     */
    public function setDateFormat($dateFormat)
    {
        $this->dateFormat = $dateFormat;
    }
}

