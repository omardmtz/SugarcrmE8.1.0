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

use Sugarcrm\Sugarcrm\Denormalization\TeamSecurity\Listener;

/**
 * Routes user initiated events to one of the listeners and the rest to the other
 */
final class UserOnly implements Listener
{
    /**
     * @var Listener
     */
    private $matchingListener;

    /**
     * @var Listener
     */
    private $nonMatchingListener;

    /**
     * Constructor
     *
     * @param Listener $matchingListener
     * @param Listener $nonMatchingListener
     */
    public function __construct(Listener $matchingListener, Listener $nonMatchingListener)
    {
        $this->matchingListener = $matchingListener;
        $this->nonMatchingListener = $nonMatchingListener;
    }

    /**
     * {@inheritDoc}
     */
    public function userDeleted($userId)
    {
        $this->nonMatchingListener->userDeleted($userId);
    }

    /**
     * {@inheritDoc}
     */
    public function teamDeleted($teamId)
    {
        $this->nonMatchingListener->teamDeleted($teamId);
    }

    /**
     * {@inheritDoc}
     *
     * A team set is created upon assignment of a new unique set of teams to a record (a user-initiated event).
     */
    public function teamSetCreated($teamSetId, array $teamIds)
    {
        $this->matchingListener->teamSetCreated($teamSetId, $teamIds);
    }

    /**
     * {@inheritDoc}
     */
    public function teamSetDeleted($teamSetId)
    {
        $this->nonMatchingListener->teamSetDeleted($teamSetId);
    }

    /**
     * {@inheritDoc}
     */
    public function userAddedToTeam($userId, $teamId)
    {
        $this->nonMatchingListener->userAddedToTeam($userId, $teamId);
    }

    /**
     * {@inheritDoc}
     */
    public function userRemovedFromTeam($userId, $teamId)
    {
        $this->nonMatchingListener->userRemovedFromTeam($userId, $teamId);
    }

    /**
     * {@inheritDoc}
     */
    public function __toString()
    {
        return sprintf('UserOnly(%s, %s)', $this->matchingListener, $this->nonMatchingListener);
    }
}
