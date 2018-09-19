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

namespace Sugarcrm\Sugarcrm\Denormalization\TeamSecurity\Command;

use Psr\Log\LoggerInterface;
use Sugarcrm\Sugarcrm\Denormalization\TeamSecurity\State;

/**
 * Performs full denormalized data rebuild if required in the current state and updates the state accordingly
 */
final class StateAwareRebuild
{
    /**
     * @var State
     */
    private $state;

    /**
     * @var callable
     */
    private $command;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Constructor
     *
     * @param State $state
     * @param callable $command
     * @param LoggerInterface $logger
     */
    public function __construct(State $state, callable $command, LoggerInterface $logger)
    {
        $this->state = $state;
        $this->command = $command;
        $this->logger = $logger;
    }

    /**
     * Rebuilds denormalized data
     *
     * @return array
     */
    public function __invoke($ignoreUpToDate = false)
    {
        if (!$this->state->isEnabled()) {
            return array(
                true,
                'The use of denormalized table is not enabled. No need to run the job.',
            );
        }

        if ($this->state->isRebuildRunning()) {
            return array(
                true,
                'Denormalized table rebuild is already running.',
            );
        }

        if (!$ignoreUpToDate && $this->state->isUpToDate()) {
            return array(
                true,
                'Denormalized data is up to date.',
            );
        }

        try {
            $table = $this->state->getStandbyTable();
            $this->state->markRebuildRunning();
            $command = $this->command;
            $command($table);
            $this->state->activateTable($table);
        } catch (\Exception $e) {
            $this->logger->critical($e);

            return array(
                false,
                sprintf(
                    'Denormalized table rebuild failed with error: %s',
                    $e->getMessage()
                ),
            );
        } finally {
            $this->state->markRebuildNotRunning();
        }

        return array(
            true,
            'Denormalized table rebuild completed',
        );
    }
}
