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

namespace Sugarcrm\Sugarcrm\Denormalization\TeamSecurity;

use DomainException;
use Psr\Log\LoggerInterface;
use SplObjectStorage;
use SplObserver;
use SplSubject;
use SugarConfig;
use Sugarcrm\Sugarcrm\Denormalization\TeamSecurity\State\Storage;

class State implements SplObserver, SplSubject
{
    /**
     * $sugar_config to determine if use of denormalized table is enabled
     * @var string
     */
    const CONFIG_KEY = "perfProfile.TeamSecurity";

    /**#@+
     * State parameters
     */
    const STATE_UP_TO_DATE = 'up_to_date';
    const STATE_REBUILD_RUNNING = 'rebuild_running';
    const STATE_ACTIVE_TABLE = 'active_table';
    /**#@-*/

    /**#@+
     * @var string
     */
    private $table1 = 'team_sets_users_1';
    private $table2 = 'team_sets_users_2';
    /**#@-*/

    /**
     * @var SugarConfig
     */
    private $config;

    /**
     * @var bool
     */
    private $isEnabled;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var State
     */
    private $storage;

    /**
     * @var SplObjectStorage|SplObserver[]
     */
    private $observers;

    /**
     * Constructor
     *
     * @param SugarConfig $config
     * @param Storage $storage
     * @param LoggerInterface $logger
     */
    public function __construct(SugarConfig $config, Storage $storage, LoggerInterface $logger)
    {
        $this->config = $config;
        $this->storage = $storage;
        $this->logger = $logger;
        $this->observers = new SplObjectStorage();
    }

    /**
     * Returns whether the usage of denormalized data is enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        if ($this->isEnabled !== null) {
            return $this->isEnabled;
        }

        $this->isEnabled = false;
        $modules = $this->config->get(self::CONFIG_KEY, array());

        foreach ($modules as $value) {
            if (!empty($value['use_denorm'])) {
                $this->isEnabled = true;
                break;
            }
        }

        return $this->isEnabled;
    }

    /**
     * Returns whether the inline handling of admin updates is enabled by configuration
     *
     * @return bool
     */
    public function shouldHandleAdminUpdatesInline()
    {
        return $this->config->get(self::CONFIG_KEY . '.inline_update');
    }

    /**
     * Returns whether the denormalizated data is available for use
     *
     * @return boolean
     */
    public function isAvailable()
    {
        return $this->getActiveTable() !== null;
    }

    /**
     * Returns the name of the table which should be used for building queries and can be used for a full rebuild
     * or NULL if such table is unavailable
     *
     * @return string|null
     */
    public function getActiveTable()
    {
        $activeTable = $this->storage->get(self::STATE_ACTIVE_TABLE);

        if ($activeTable !== null) {
            if (!$this->isValidTable($activeTable)) {
                $activeTable = null;
            } elseif (!$this->isEnabled()) {
                $this->deactivate();
                $activeTable = null;
            }
        } elseif ($this->isEnabled()) {
            $this->logger->critical('Denormalization is enabled but the denormalized data is unavailable.');
        }

        return $activeTable;
    }

    /**
     * Returns the name of the table which is not currently used for building queries and can be used for a full rebuild
     *
     * @return string|null
     */
    public function getStandbyTable()
    {
        if ($this->getActiveTable() === $this->table1) {
            return $this->table2;
        }

        return $this->table1;
    }

    /**
     * Activates the given table. This table will be used for reads and inline updates.
     *
     * @param string $table
     */
    public function activateTable($table)
    {
        if (!$this->isValidTable($table)) {
            throw new DomainException(sprintf(
                "The table should be either '%s' or '%s', %s given",
                $this->table1,
                $this->table2,
                var_export($table, true)
            ));
        }

        $this->updateState(self::STATE_ACTIVE_TABLE, $table);
        $this->updateState(self::STATE_UP_TO_DATE, true);
    }

    /**
     * Deactivates the usage of denormalized data
     */
    private function deactivate()
    {
        $this->updateState(self::STATE_ACTIVE_TABLE, null);
    }

    /**
     * Validates table name
     *
     * @param string $table
     * @return bool
     */
    private function isValidTable($table)
    {
        return $table === $this->table1
            || $table === $this->table2;
    }

    /**
     * Returns whether the denormalized data is up to date
     *
     * @return boolean
     */
    public function isUpToDate()
    {
        return $this->isAvailable() && $this->storage->get(self::STATE_UP_TO_DATE);
    }

    /**
     * Mark the denormalized data out of date
     */
    public function markOutOfDate()
    {
        $this->updateState(self::STATE_UP_TO_DATE, false);
    }

    /**
     * Returns whether a full rebuild of the denormalized data is currently running
     *
     * @return boolean
     */
    public function isRebuildRunning()
    {
        return (bool) $this->storage->get(self::STATE_REBUILD_RUNNING);
    }

    /**
     * Marks rebuild running
     */
    public function markRebuildRunning()
    {
        $this->updateState(self::STATE_REBUILD_RUNNING, true);
    }

    /**
     * Marks rebuild not running
     */
    public function markRebuildNotRunning()
    {
        $this->updateState(self::STATE_REBUILD_RUNNING, false);
    }

    /**
     * Updates the given state parameter and notifies the observer if the parameter has changed
     *
     * @param string $var
     * @param mixed $value
     */
    private function updateState($var, $value)
    {
        $oldValue = $this->storage->get($var);

        if ($oldValue === $value) {
            $this->logger->warning(sprintf(
                "Unexpected state transition. State parameter '%s' is already %s",
                $var,
                var_export($value, true)
            ));

            return;
        }

        $this->logger->info(sprintf(
            "State parameter '%s' changed from %s to %s.",
            $var,
            var_export($oldValue, true),
            var_export($value, true)
        ));

        $this->storage->update($var, $value);
        $this->notify();
    }

    /**
     * {@inheritDoc}
     *
     * Handles configuration update
     */
    public function update(SplSubject $config)
    {
        $this->isEnabled = null;
        $this->notify();
    }

    /**
     * {@inheritDoc}
     */
    public function attach(SplObserver $observer)
    {
        $this->observers->attach($observer);
    }

    /**
     * {@inheritDoc}
     */
    public function detach(SplObserver $observer)
    {
        $this->observers->detach($observer);
    }

    /**
     * {@inheritDoc}
     */
    public function notify()
    {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }
}
