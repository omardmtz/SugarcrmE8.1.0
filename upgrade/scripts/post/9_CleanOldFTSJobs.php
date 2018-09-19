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

use Sugarcrm\Sugarcrm\Elasticsearch\Queue\QueueManager;

/**
 * Upgrade script to cleanup old FTS jobs in the database.
 */
class SugarUpgradeCleanOldFTSJobs extends UpgradeScript
{
    public $order = 9600;
    public $type = self::UPGRADE_CUSTOM;

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        if (version_compare($this->from_version, '7.7', '<')) {
            $this->cleanupOldIndexJobs();
        }
    }

    /**
     * Cleanup any scheduled jobs left behind from deprecated SugarSearchEngine
     * code base.
     */
    public function cleanupOldIndexJobs()
    {
        $db = DBManagerFactory::getInstance();
        $sql = sprintf(
            'UPDATE %s SET status = %s, resolution = %s, message = %s WHERE target = %s',
            QueueManager::JOB_QUEUE,
            $db->quoted(\SchedulersJob::JOB_STATUS_DONE),
            $db->quoted(\SchedulersJob::JOB_FAILURE),
            $db->quoted('Invalidated by new Elasticsearch QueueManager'),
            $db->quoted('class::SugarSearchEngineFullIndexer')
        );
        $db->query($sql);
    }
}
