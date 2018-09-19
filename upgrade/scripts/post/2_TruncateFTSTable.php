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
 * 'Clearing the fts_queue so we can add a primary key
 */
class SugarUpgradeTruncateFTSTable extends UpgradeScript
{
    public $order = 2010;
    public $type = self::UPGRADE_DB;

    public function run()
    {
        if (!$this->db->tableExists("fts_queue") || version_compare($this->from_version, '7.2.2', '>=')) {
            return;
        }

        //Remove any existing temp table
        if ($this->db->tableExists("fts_queue_tmp")) {
            $this->db->query($this->db->dropTableNameSQL("fts_queue_tmp"));
        }

        $columns = $this->db->get_columns('fts_queue');
        $indices = $this->db->get_indices('fts_queue');
        $cols = implode(',', array_keys($columns));
        $queries = array(
            "Create Temp Table" => $this->db->createTableSQLParams('fts_queue_tmp', $columns, $indices),
            "Copy Data to Temp" => "INSERT INTO fts_queue_tmp ({$cols}) (SELECT {$cols} FROM fts_queue WHERE processed = 0)",
            "Truncate Table" => $this->db->truncateTableSQL("fts_queue"),
        );

        $this->log('Clearing the fts_queue so we can add a primary key');

        foreach ($queries as $description => $q) {
            $this->db->commit();
            if (!$this->db->query($q)) {
                return $this->error('TruncateFTSTable failed on step ' . $description);
            }
        }
        $this->db->commit();
    }
}
