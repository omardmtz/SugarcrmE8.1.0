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
 * Class SugarUpgradeFixReportTeams
 */
class SugarUpgradeFixReportTeams extends UpgradeScript
{
    public $order = 7650;
    public $type = self::UPGRADE_ALL;

    public function run() {
        if (!version_compare($this->from_version, '7.0', '<')) {
            // only need to run this upgrading from pre 7.0 versions
            return;
        }

        $this->convertTeamsForReports();
    }

    /**
     * Convert team_sets to team_link
     */
    public function convertTeamsForReports() {
        $patterns = array('/\"team_sets\"/', '/\:team_sets\"/', '/Team Set/', '/\"relationship_name\":\"(\w+)_team_sets\"/');
        $replacements = array('"team_link"', ':team_link"', 'Teams', '"relationship_name":"${1}_team_link"');
        $query = 'SELECT id, content FROM saved_reports WHERE deleted = 0';
        $db = DBManagerFactory::getInstance();
        $result = $db->query($query);
        $reportsNeedUpdate = array();
        while ($row = $db->fetchByAssoc($result, false)) {
            $id = $row['id'];
            $content = $row['content'];
            $content = preg_replace($patterns, $replacements, $content, -1, $count);
            if ($count > 0) {
                $reportsNeedUpdate[$id] = $content;
            }
        }

        foreach ($reportsNeedUpdate as $id => $content) {
            $query = "UPDATE saved_reports SET content = '{$content}' WHERE id = '{$id}'";
            $db->query($query);
       }
    }
}
