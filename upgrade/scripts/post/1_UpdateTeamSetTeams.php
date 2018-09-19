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

/**
 * Apply changes to the `team_sets_teams` table
 */
class SugarUpgradeUpdateTeamSetTeams extends UpgradeScript
{
    public $order = 1100;
    public $type = self::UPGRADE_DB;

    public function run()
    {
        if (version_compare($this->from_version, '7.11.0.0', '>=')) {
            return;
        }

        // remove empty records and duplicates
        $this->db->query(<<<SQL
DELETE FROM team_sets_teams
WHERE id IN (
  SELECT *
  FROM (
         SELECT t1.id
         FROM team_sets_teams t1
         WHERE deleted = 1
               OR team_set_id IS NULL
               OR team_id IS NULL
               OR EXISTS(
                   SELECT NULL
                   FROM team_sets_teams t2
                   WHERE t2.team_set_id = t1.team_set_id
                         AND t2.team_id = t1.team_id
                         AND t2.id < t1.id
                         AND t2.deleted = 0
               )
       ) t
);
SQL
        );

        // enforce NOT NULL on the key columns which cannot be done via DBManager
        foreach (array('team_id', 'team_set_id') as $field) {
            $this->db->query(sprintf(
                'ALTER TABLE team_sets_teams MODIFY %s %s NOT NULL',
                $field,
                $this->db->getColumnType('id')
            ));
        }
    }
}
