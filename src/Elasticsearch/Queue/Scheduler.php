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

namespace Sugarcrm\Sugarcrm\Elasticsearch\Queue;

use Sugarcrm\Sugarcrm\SearchEngine\SearchEngine;


/**
 *
 * Persistent scheduler which is responsible to create subsequent jobs based
 * on what needs to be consumed from the database queue.
 *
 */
class Scheduler implements \RunnableSchedulerJob
{
    /**
     * @var \SchedulersJob
     */
    protected $job;

    /**
     * @var \Sugarcrm\Sugarcrm\SearchEngine\Engine\Elastic
     */
    protected $engine;

    /**
     * Ctor
     */
    public function __construct(SearchEngine $engine = null)
    {
        $this->engine = $engine ?: SearchEngine::getInstance();
    }

    /**
     * {@inheritdoc}
     */
    public function setJob(\SchedulersJob $job)
    {
        $this->job = $job;
    }

    /**
     * {@inheritdoc}
     */
    public function run($data)
    {
        // We can only run for Elasticsearch engine
        if (!$this->isElasticSearchEngine()) {
            return $this->job->failJob("The current configured SearchEngine is not Elasticsearch");
        }

        // Force connectivity check
        if (!$this->isAvailable()) {
            $msg = 'Elasticsearch not available, postponing consumer job creation';
            return $this->job->failJob($msg);
        }

        // Create consumer jobs
        $list = array();
        foreach ($this->getQueuedModules() as $module) {
            $this->engine->getContainer()->queueManager->createConsumer($module);
            $list[] = $module;
        }

        if (!empty($list)) {
            $message = 'Created consumers for: '.implode(', ', $list);
        } else {
            $message = 'No records currently in queue - nothing to do';
            $this->reportIndexingDone();
        }

        return $this->job->succeedJob($message);
    }

    /**
     * Check if current active engine is for elasticsearch
     * @return boolean
     */
    protected function isElasticSearchEngine()
    {
        return ($this->engine->getEngine() instanceof \Sugarcrm\Sugarcrm\SearchEngine\Engine\Elastic);
    }

    /**
     * Wrapper to verify Elastichsearch availability
     * @return boolean
     */
    protected function isAvailable()
    {
        return $this->engine->isAvailable(true);
    }

    /**
     * Wrapper to get list of modules for which queued records exist
     * @return array
     */
    protected function getQueuedModules()
    {
        return $this->engine->getContainer()->queueManager->getQueuedModules();
    }

    /**
     * Wrapper to report that we are done processing our queue
     */
    protected function reportIndexingDone()
    {
        return $this->engine->getContainer()->queueManager->reportIndexingDone();
    }
}
