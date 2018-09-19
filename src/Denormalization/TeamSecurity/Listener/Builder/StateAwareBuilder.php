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

namespace Sugarcrm\Sugarcrm\Denormalization\TeamSecurity\Listener\Builder;

use Doctrine\DBAL\Connection;
use Sugarcrm\Sugarcrm\Denormalization\TeamSecurity\Listener\Builder;
use Sugarcrm\Sugarcrm\Denormalization\TeamSecurity\Listener\Composite;
use Sugarcrm\Sugarcrm\Denormalization\TeamSecurity\Listener\Invalidator;
use Sugarcrm\Sugarcrm\Denormalization\TeamSecurity\Listener\NullListener;
use Sugarcrm\Sugarcrm\Denormalization\TeamSecurity\Listener\Recorder;
use Sugarcrm\Sugarcrm\Denormalization\TeamSecurity\Listener\Updater;
use Sugarcrm\Sugarcrm\Denormalization\TeamSecurity\Listener\UserOnly;
use Sugarcrm\Sugarcrm\Denormalization\TeamSecurity\State;

/**
 * Creates a listener implementation according to the current state
 */
final class StateAwareBuilder implements Builder
{
    /**
     * @var Connection
     */
    private $conn;

    /**
     * @var State
     */
    private $state;

    /**
     * Constructor
     *
     * @param Connection $conn
     * @param State $state
     */
    public function __construct(Connection $conn, State $state)
    {
        $this->conn = $conn;
        $this->state = $state;
    }

    /**
     * {@inheritDoc}
     */
    public function createListener()
    {
        $components = [];

        if ($this->state->isEnabled()) {
            if ($this->state->isAvailable()) {
                $updater = new Updater(
                    $this->conn,
                    $this->state->getActiveTable()
                );

                if (!$this->state->shouldHandleAdminUpdatesInline()) {
                    if ($this->state->isUpToDate()) {
                        $adminListener = new Invalidator($this->state);
                    } else {
                        $adminListener = new NullListener();
                    }

                    $updater = new UserOnly($updater, $adminListener);
                }

                $components[] = $updater;
            }

            if ($this->state->isRebuildRunning()) {
                $components[] = new Recorder($this->conn);
            }
        } elseif ($this->state->isUpToDate()) {
            $components[] = new Invalidator($this->state);
        }

        if (count($components) === 0) {
            return new NullListener();
        }

        if (count($components) === 1) {
            return $components[0];
        }

        return new Composite(...$components);
    }
}
