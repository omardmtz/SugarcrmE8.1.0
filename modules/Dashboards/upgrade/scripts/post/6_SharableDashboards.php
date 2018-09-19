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
 * Class SugarUpgradeSharableDashboards
 *
 * This upgrader runs for each user, and then each user's dashboard. It contains
 * the upgrade requirements for sharable dashboards, including:
 *  Favorite assignment
 *  Team assignment
 */
class SugarUpgradeSharableDashboards extends UpgradeScript
{
    public $order = 6900;
    public $type = self::UPGRADE_DB;
    public function run()
    {
        if (version_compare($this->from_version, '7.10', '>=')) {
            return;
        }

        $this->updateTeams();
        $this->addToFavorites();
    }

    private function updateTeams()
    {
        $conn = $this->db->getConnection();

        $select = $conn->executeQuery('
            SELECT DISTINCT d.assigned_user_id AS used_id, t.id AS team_id
            FROM dashboards d
            INNER JOIN teams t
            ON t.associated_user_id = d.assigned_user_id
            AND t.deleted = 0
            WHERE d.deleted = 0
        ');

        $update = $conn->prepare('
            UPDATE dashboards
            SET team_id = ?,
            team_set_id = ?
            WHERE assigned_user_id = ?
        ');

        while (($row = $select->fetch())) {
            $teamId = $row['team_id'];
            $userId = $row['used_id'];

            // Handle team assignment
            $teamSetId = (new TeamSet())->addTeams([$teamId]);
            $update->execute([$teamId, $teamSetId, $userId]);
        }
    }

    private function addToFavorites()
    {
        $stmt = $this->db->getConnection()->executeQuery('
            SELECT dashboards.id, dashboards.assigned_user_id
            FROM dashboards
            WHERE dashboards.deleted = 0
        ');

        while (($row = $stmt->fetch())) {
            $userId = $row['assigned_user_id'];
            $dashboardId = $row['id'];

            // Add the dashboard to the user's favorites
            $favId = SugarFavorites::generateGUID('Dashboards', $dashboardId, $userId);

            $fav = BeanFactory::newBean('SugarFavorites');
            $fav->id = $favId;
            $fav->module = 'Dashboards';
            $fav->record_id = $dashboardId;
            $fav->created_by = $userId;
            $fav->assigned_user_id = $userId;
            $fav->modified_user_id = $userId;
            $fav->new_with_id = true;
            $fav->update_modified_by = false;
            $fav->set_created_by = false;
            $fav->save();
        }
    }
}
