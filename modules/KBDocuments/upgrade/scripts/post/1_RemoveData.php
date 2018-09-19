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
 * Remove KBDocuments tables for clear install.
 */
class SugarUpgradeRemoveData extends UpgradeScript
{
    public $order = 1001;
    public $type = self::UPGRADE_DB;
    public $version = '7.5';

    public function run()
    {
        if (version_compare($this->from_version, '7.7.0', '<')) {
            // tables to delete
            $tables = array(
                'kbcontents',
                'kbcontents_cstm',
                'kbcontents_audit',
                'kbdocument_revisions',
                'kbdocuments',
                'kbdocuments_cstm',
                'kbdocuments_kbtags',
                'kbdocuments_views_ratings',
                'kbtags',
                'kbtags_cstm',
            );

            foreach ($tables as $table) {
                if ($this->db->tableExists($table)) {
                    foreach ($this->db->get_indices($table) as $index) {
                        if ($index['type'] == 'fulltext') {
                            $this->db->dropIndexes($table, array($index), true);
                        }
                    }
                    $this->db->dropTableName($table);
                }
            }
            // Need to delete cache of unified search modules.
            if (file_exists('custom/modules/unified_search_modules_display.php')) {
                UnifiedSearchAdvanced::clearCache();
            }
        }
    }
}
