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
 * Update DB2 sequence definition to not use preallocated sequence ids to
 * avoid gaps in auto increment values.
 */
class SugarUpgradeRepairDb2Sequences extends UpgradeScript
{
    public $version = '7.5.0';
    public $order = 2000;
    public $type = self::UPGRADE_DB;

    public function run()
    {
        if (!$this->db instanceof IBMDB2Manager) {
            return;
        }

        // get all defined sequences and update using NO CACHE
        $sql = "SELECT seqname FROM SYSCAT.SEQUENCES WHERE seqname LIKE '%_SEQ' AND ownertype = 'U'";
        $res = $this->db->query($sql);
        while ($row = $this->db->fetchByAssoc($res)) {
            $this->log("Altering sequence {$row['seqname']} using NO CACHE");
            $alter = "ALTER SEQUENCE {$row['seqname']} NO CACHE";
            $this->db->query($alter, false, "Error altering sequence {$row['seqname']} using NO CACHE");
        }
    }
}
