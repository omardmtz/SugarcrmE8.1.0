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

/**
 * Listener of the changes in team security related data.
 */
interface Listener
{
    /**
     * Handles deletion of a user
     *
     * @param $userId
     * @return void
     */
    public function userDeleted($userId);

    /**
     * Handles deletion of a team
     *
     * @param $teamId
     * @return void
     */
    public function teamDeleted($teamId);

    /**
     * Handles creation of a team set
     *
     * @param string $teamSetId Team set ID
     * @param string[] $teamIds IDs of theTeam ID
     *
     * @return void
     */
    public function teamSetCreated($teamSetId, array $teamIds);

    /**
     * Handles deletion of a team set
     *
     * @param string $teamSetId Team set ID
     *
     * @return void
     */
    public function teamSetDeleted($teamSetId);

    /**
     * Handles assignment of a user to a team
     *
     * @param string $userId User ID
     * @param string $teamId Team ID
     *
     * @return void
     */
    public function userAddedToTeam($userId, $teamId);

    /**
     * Handles removal of a user from a team
     *
     * @param string $userId User ID
     * @param string $teamId Team ID
     *
     * @return void
     */
    public function userRemovedFromTeam($userId, $teamId);

    /**
     * Returns textual representation of the listener's behavior for debugging/introspection purposes.
     *
     * @return string
     */
    public function __toString();
}
