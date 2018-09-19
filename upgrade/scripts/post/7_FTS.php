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
 * Set up FTS when upgrading CE->PRO
 */
class SugarUpgradeFTS extends UpgradeScript
{
    public $order = 7000;
    public $type = self::UPGRADE_DB;

    public function run()
    {
        if ($this->from_flavor == 'ce' && $this->toFlavor('pro')
            && $this->db->supports('fulltext') && $this->db->full_text_indexing_installed()
        ) {
            $this->db->full_text_indexing_setup();
        }

        //Check if the fts_queue_tmp table exists and clone the data back into the fts_queue table.
        if (version_compare($this->from_version, '7.2.2', '>=') || !$this->db->tableExists("fts_queue_tmp")) {
            return;
        }
        $queries = array(
            "Clone Data from Temp" => "INSERT INTO fts_queue (bean_id, bean_module, date_modified, processed, id, date_created) "
                . "(SELECT bean_id, bean_module, date_modified, processed, "
                . $this->db->getGuidSQL() . ", " . $this->db->now() . " FROM fts_queue_tmp)",
            "Remove Temp Table" => $this->db->dropTableNameSQL("fts_queue_tmp"),
        );

        $this->log('Clearing the fts_queue so we can add a primary key');

        foreach ($queries as $description => $q) {
            $this->db->commit();
            if (!$this->db->query($q)) {
                return $this->error('UpgradeFTS failed on step ' . $description);
            }
        }
        $this->db->commit();
    }
}
