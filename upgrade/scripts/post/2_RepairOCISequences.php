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
 * Update Oracle sequence definition to not use preallocated sequence ids to
 * avoid gaps in auto increment values.
 * Required for 12c installs and doesn't negatively affect 11g installs
 */
class SugarUpgradeRepairOCISequences extends UpgradeScript
{
    public $version = '7.6.0';
    public $order = 2000;
    public $type = self::UPGRADE_DB;

    public function run()
    {
        // this only applies for OCI below 7.6.0
        if (!$this->db instanceof OracleManager || !version_compare($this->from_version, '7.6.0', '<')) {
            return;
        }

        // get all defined sequences and update using NO CACHE
        $sql = "SELECT sequence_name FROM USER_SEQUENCES WHERE sequence_name LIKE '%_SEQ'";
        $res = $this->db->query($sql);
        while ($row = $this->db->fetchByAssoc($res)) {
            $this->log("Altering sequence {$row['sequence_name']} using NOCACHE");
            $alter = "ALTER SEQUENCE {$row['sequence_name']} NOCACHE";
            $this->db->query($alter, false, "Error altering sequence {$row['sequence_name']} using NOCACHE");
        }
    }
}
