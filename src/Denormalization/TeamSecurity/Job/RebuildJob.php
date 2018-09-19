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

namespace Sugarcrm\Sugarcrm\Denormalization\TeamSecurity\Job;

use SchedulersJob;

/**
 * Handle the rebuild of Team Security denormalized data.
 */
class RebuildJob implements \RunnableSchedulerJob
{
    /**
     * @var SchedulersJob
     */
    protected $job;

    /**
     * @var callable
     */
    private $command;

    /**
     * Constructor
     *
     * @param callable $command
     */
    public function __construct(callable $command)
    {
        $this->command = $command;
    }

    /**
     * {@inheritdoc}
     */
    public function setJob(SchedulersJob $job)
    {
        $this->job = $job;
    }

    /**
     * {@inheritdoc}
     */
    public function run($data)
    {
        $start = time();
        $command = $this->command;
        list($status, $message) = $command();
        $duration = time() - $start;

        $message .= sprintf(' (%s second(s) taken)', $duration);

        if ($status) {
            return $this->job->succeedJob($message);
        }

        return $this->job->failJob($message);
    }
}
