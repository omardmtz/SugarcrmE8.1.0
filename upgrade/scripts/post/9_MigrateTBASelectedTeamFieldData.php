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
 * Clone data from team_set_selected_id to acl_team_set_id nd drop old column.
 */
class SugarUpgradeMigrateTBASelectedTeamFieldData extends UpgradeScript
{
    public $order = 9999;
    public $type = self::UPGRADE_DB;

    public function run()
    {
        // this upgrade script is from 7.8RC3 to next RC or 7.8
        $nextMinorReleases = version_compare($this->to_version, '7.8.0.0RC4', '>=')
            || version_compare($this->to_version, '7.8.0.0', '==');
        if (version_compare($this->from_version, '7.8.0.0RC3', '==') && $nextMinorReleases) {
            $tables = array();
            foreach ($GLOBALS['beanList'] as $beanName) {
                $bean = BeanFactory::newBeanByName($beanName);
                if ($bean
                    && $bean instanceof SugarBean
                    && TeamBasedACLConfigurator::implementsTBA($bean->getModuleName())
                ) {
                    $tables[] = $bean->getTableName();
                }
            }
            $tables = array_unique($tables);

            foreach ($tables as $tableName) {
                $this->db->query("UPDATE $tableName SET acl_team_set_id = team_set_selected_id");
                $this->db->query("ALTER TABLE $tableName DROP COLUMN team_set_selected_id");
            }
        }
        return;
    }
}
