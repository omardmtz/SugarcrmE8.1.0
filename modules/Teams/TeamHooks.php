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

class TeamHooks
{
    /**
     * Used for exit point in recursive removing
     * @var array
     */
    protected static $removedLinks = [];

    /**
     * Adds user-team relationship with additional business logic
     * @param SugarBean $team
     * @param string $event
     * @param array $args
     */
    public static function addManagerToTeam(SugarBean $team, $event, $args)
    {
        if ($team instanceof Team) {
            if (isset($args['relationship']) && $args['relationship'] == 'team_memberships') {
                $team->add_user_to_team($args['related_id']);
            }
        }
    }

    /**
     * Removes user-team relationship with additional business logic
     * @param SugarBean $team
     * @param string $event
     * @param array $args
     */
    public static function removeManagerFromTeam(SugarBean $team, $event, $args)
    {
        if ($team instanceof Team) {
            if (isset($args['relationship']) && $args['relationship'] == 'team_memberships') {
                $membership = BeanFactory::newBean('TeamMemberships'); /**@var TeamMembership $membership*/
                $membership->retrieve_by_user_and_team($args['related_id'], $team->id);
                if ($membership->id && !isset(self::$removedLinks[$membership->id])) {
                    self::$removedLinks[$membership->id] = 1;
                    $team->remove_user_from_team($args['related_id']);
                    unset(self::$removedLinks[$membership->id]);
                }
            }
        }
    }
}
