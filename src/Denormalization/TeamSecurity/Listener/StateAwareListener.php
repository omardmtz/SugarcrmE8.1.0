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
use SplObserver;
use SplSubject;
use Sugarcrm\Sugarcrm\Denormalization\TeamSecurity\Listener;

/**
 * Proxies calls to the underlying listener and rebuilds it when requested
 */
final class StateAwareListener implements Listener, SplObserver
{
    /**
     * @var Builder
     */
    private $builder;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Listener
     */
    private $listener;

    /**
     * Constructor
     *
     * @param Builder $builder
     * @param LoggerInterface $logger
     */
    public function __construct(Builder $builder, LoggerInterface $logger)
    {
        $this->builder = $builder;
        $this->logger = $logger;
    }

    /**
     * {@inheritDoc}
     */
    public function userDeleted($userId)
    {
        $this->getListener()->userDeleted($userId);
    }

    /**
     * {@inheritDoc}
     */
    public function teamDeleted($teamId)
    {
        $this->getListener()->teamDeleted($teamId);
    }

    /**
     * {@inheritDoc}
     */
    public function teamSetCreated($teamSetId, array $teamIds)
    {
        $this->getListener()->teamSetCreated($teamSetId, $teamIds);
    }

    /**
     * {@inheritDoc}
     */
    public function teamSetDeleted($teamSetId)
    {
        $this->getListener()->teamSetDeleted($teamSetId);
    }

    /**
     * {@inheritDoc}
     */
    public function userAddedToTeam($userId, $teamId)
    {
        $this->getListener()->userAddedToTeam($userId, $teamId);
    }

    /**
     * {@inheritDoc}
     */
    public function userRemovedFromTeam($userId, $teamId)
    {
        $this->getListener()->userRemovedFromTeam($userId, $teamId);
    }

    /**
     * @return Listener
     */
    private function getListener()
    {
        if (!$this->listener) {
            $this->listener = $this->builder->createListener();
            $this->logger->info(sprintf('Using %s listener', $this));
        }

        return $this->listener;
    }

    /**
     * {@inheritDoc}
     */
    public function update(SplSubject $state)
    {
        $this->listener = null;
    }

    /**
     * {@inheritDoc}
     */
    public function __toString()
    {
        return (string) $this->getListener();
    }
}
