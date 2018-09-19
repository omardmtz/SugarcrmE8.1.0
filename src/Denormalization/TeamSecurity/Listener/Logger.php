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

namespace Sugarcrm\Sugarcrm\Denormalization\TeamSecurity\Listener;

use Psr\Log\LoggerInterface;
use Sugarcrm\Sugarcrm\Denormalization\TeamSecurity\Listener;

/**
 * Logs events to a PSR-3 logger
 */
final class Logger implements Listener
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Constructor
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritDoc}
     */
    public function userDeleted($userId)
    {
        $this->log("User '%s' deleted", $userId);
    }

    /**
     * {@inheritDoc}
     */
    public function teamDeleted($teamId)
    {
        $this->log("Team '%s' deleted", $teamId);
    }

    /**
     * {@inheritDoc}
     */
    public function teamSetCreated($teamSetId, array $teamIds)
    {
        $this->log("Team set '%s' created from team(s) %s", $teamSetId, implode(', ', array_map(function ($id) {
            return sprintf("'%s'", $id);
        }, $teamIds)));
    }

    /**
     * {@inheritDoc}
     */
    public function teamSetDeleted($teamSetId)
    {
        $this->log("Team set '%s' deleted", $teamSetId);
    }

    /**
     * {@inheritDoc}
     */
    public function userAddedToTeam($userId, $teamId)
    {
        $this->log("User '%s' added to team '%s'", $userId, $teamId);
    }

    /**
     * {@inheritDoc}
     */
    public function userRemovedFromTeam($userId, $teamId)
    {
        $this->log("User '%s' removed from team '%s'", $userId, $teamId);
    }

    /**
     * {@inheritDoc}
     */
    public function __toString()
    {
        return sprintf('Logger');
    }

    /**
     * {@inheritDoc}
     */
    private function log($format, ...$arguments)
    {
        $this->logger->info(vsprintf($format, $arguments));
    }
}
