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
 * Class SugarUpgradeFixPMSEPALockedVariables
 */
class SugarUpgradeFixPMSEPALockedVariables extends UpgradeScript
{
    public $order = 9000;
    public $type = self::UPGRADE_DB;

    protected $table = 'pmse_bpm_process_definition';
    protected $field = 'pro_locked_variables';

    /**
     * To get pro_locked_variables from process definition table
     * @return Array
     */
    protected function getLockedVariables()
    {
        $notEmpty = $this->db->getNotEmptyFieldSQL($this->field);
        $sql = "SELECT id, {$this->field} FROM {$this->table} WHERE deleted = 0 AND $notEmpty";
        $result = $this->db->query($sql);

        $records = array();
        while ($row = $this->db->fetchByAssoc($result, false)) {
            $records[] = $row;
        }
        return $records;
    }

    /**
     * entry point of the script
     */
    public function run()
    {
        // Fix only when upgrading from pre 7.8.0.0
        if (version_compare($this->from_version, '7.8.0.0', '<')) {
            $records = $this->getLockedVariables();

            foreach ($records as $record) {
                if (!empty($record['pro_locked_variables'])) {
                    $variable = html_entity_decode($record['pro_locked_variables'], ENT_QUOTES);
                    if (!empty($variable)) {
                        $id = $this->db->quoted($record['id']);
                        $variable = $this->db->quoted($variable);
                        $sql = "UPDATE {$this->table} SET {$this->field} = $variable where id = $id";
                        $this->db->query($sql);
                    }
                }
            }
        }
    }
}
