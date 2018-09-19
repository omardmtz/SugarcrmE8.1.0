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
 * Update metadata of dashable lists with kbdocuments.
 */
class SugarUpgradeMigrateDashableListsWithKBDocuments extends UpgradeScript
{
    const OLD_MODULE_NAME = 'KBDocuments';
    const NEW_MODULE_NAME = 'KBContents';

    public $order = 8100;
    public $type = self::UPGRADE_DB;
    public $version = '7.7';

    private $displayColumnsMap = array(
        'kbdocument_name' => 'name',
        'views_number' => 'viewcount',
        'kbdoc_approver_name' => 'kbsapprover_name',
        'kbdocument_revision_number' => 'revision',
        'is_external_article' => 'is_external',
        'status_id' => 'status',
        'assigned_user_name' => 'assigned_user_name',
        'body' => 'kbdocument_body',
        'case_name' => 'kbscase_name',
    );

    /**
     * @inheritdoc
     */
    public function run()
    {
        if (version_compare($this->from_version, '7.7.0', '<')) {
            $this->migrateDashableLists();
        }
    }

    /**
     * Performs dashable lists migration.
     */
    public function migrateDashableLists()
    {
        $result = $this->db->query('SELECT id, metadata FROM dashboards WHERE deleted=0');

        while ($dashboard = $this->db->fetchByAssoc($result, false)) {
            $metadata = json_decode($dashboard['metadata']);

            $this->updateMetadata($metadata);

            $metadata = json_encode($metadata);
            if ($metadata === $dashboard['metadata']) {
                continue;
            }

            $this->db->query(
                "UPDATE dashboards SET metadata={$this->db->quoted($metadata)} WHERE id="
                . $this->db->quoted($dashboard['id'])
            );
        }

    }

    /**
     * Retrieve dashable lists views based on supplied dashboard metadata.
     *
     * @param object $metadata Dashboard metadata.
     */
    private function updateMetadata($metadata)
    {
        if (empty($metadata->components)) {
            return;
        }

        foreach ($metadata->components as $component) {

            if (empty($component->rows)) {
                continue;
            }

            foreach ($component->rows as $rows) {
                foreach ($rows as $row) {
                    if (!$this->checkOnKbDocument($row)) {
                        continue;
                    }

                    $view = $row->view;
                    $hasTypeDashableList = !empty($view->type) && $view->type == 'dashablelist';
                    $hasNameDashableList = !empty($view->name) && $view->name == 'dashablelist';

                    if ($hasNameDashableList) {
                        unset($view->name);
                        $view->type = 'dashablelist';
                        $hasTypeDashableList = true;
                    }
                    if (!$hasTypeDashableList) {
                        continue;
                    }

                    $row->context->module = self::NEW_MODULE_NAME;
                    $this->updateView($view);
                }
            }
        }
    }

    /**
     * Check if context of record is KBDocuments.
     *
     * @param object $metadataRow ($metadata->components[?]->rows[?]->rows[?])
     * @return bool
     */
    private function checkOnKbDocument($metadataRow)
    {
        return !empty($metadataRow->context->module) &&
        $metadataRow->context->module == self::OLD_MODULE_NAME &&
        !empty($metadataRow->view->module) &&
        $metadataRow->view->module == self::OLD_MODULE_NAME;
    }

    /**
     * Update dashable list view.
     *
     * @param object $view Dashable list view.
     */
    private function updateView($view)
    {
        $view->module = self::NEW_MODULE_NAME;
        if (!empty($view->display_columns)) {
            $view->display_columns = array_map(array($this, 'columnsMapper'), $view->display_columns);
        }
    }

    /**
     * Convert old column name to new.
     *
     * @param string $column
     * @return string
     */
    private function columnsMapper($column)
    {
        return array_key_exists($column, $this->displayColumnsMap) ? $this->displayColumnsMap[$column] : $column;
    }
}
