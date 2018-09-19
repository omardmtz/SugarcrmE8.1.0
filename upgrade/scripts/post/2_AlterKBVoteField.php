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
 * Class SugarUpgradeAlterKBVoteField.
 * Changes length to vardefs value of vote field in kbusefulness table.
 */
class SugarUpgradeAlterKBVoteField extends UpgradeScript
{
    public $order = 2001;
    public $type = self::UPGRADE_DB;

    /**
     * @inheritdoc
     */
    public function run()
    {
        global $dictionary;

        if (version_compare($this->from_version, '7.7', '<') ||
            !$this->db instanceof OracleManager ||
            !$this->db->tableExists('kbusefulness') ||
            !isset($dictionary['kbusefulness']['fields'])
        ) {
            return;
        }

        $tableColumns = $this->db->get_columns('kbusefulness');
        if (!isset($tableColumns['vote']['type']) || $tableColumns['vote']['type'] != 'number') {
            return;
        }
        $currentDbType = $tableColumns['vote']['type'] . '(' . $tableColumns['vote']['len'] . ')';

        $fields = $dictionary['kbusefulness']['fields'];
        if (!isset($fields['vote']['type']) || $fields['vote']['type'] != 'smallint') {
            return;
        }

        $currentSugarType = $this->db->getColumnType($fields['vote']['type']);

        if ($currentDbType != $currentSugarType) {
            $this->log("Changing kbusefulness.vote column type from $currentDbType to $currentSugarType");

            $this->db->query('ALTER TABLE KBUSEFULNESS ADD tmpVote NUMBER(*)');
            $this->db->query('UPDATE KBUSEFULNESS SET tmpVote = VOTE');
            $this->db->query('UPDATE KBUSEFULNESS SET VOTE = NULL');
            $this->db->query("ALTER TABLE KBUSEFULNESS MODIFY VOTE $currentSugarType");
            $this->db->query('UPDATE KBUSEFULNESS SET VOTE = tmpVote');
            $this->db->query('ALTER TABLE KBUSEFULNESS DROP COLUMN tmpVote');
        }
    }
}
