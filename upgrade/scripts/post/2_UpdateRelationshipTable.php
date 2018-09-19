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
 * We need to repair the relationship table before we repair everything else
 * Otherwise the relationship rebuild happens before the table is fixed and
 * the upgrade fails.
 */
class SugarUpgradeUpdateRelationshipTable extends UpgradeScript
{
    public $order = 2050;
    public $type = self::UPGRADE_DB;

    public function run()
    {
        $this->log('Start rebuilding relationship database table');

        // this is dirty, but otherwise SugarBean caches old defs :(
        $GLOBALS['reload_vardefs'] = true;

        unset($GLOBALS['dictionary']['Relationship']);

        $focus = BeanFactory::newBean('Relationships');

        $sql = $this->db->repairTable($focus, true);
        if(trim($sql) != '') {
            $this->log('Ran sql: ' . $sql);
        } else {
            $this->log('Relationship database table up to date.');
        }

        unset($GLOBALS['reload_vardefs']);
        $this->log('Relationship Database table repaired');

    }
}
