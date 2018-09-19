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
 * Class SugarUpgradeMigrateKBDocumentsReports
 * Convert old KBDocument reports to be compatible with KBContent module.
 */
class SugarUpgradeMigrateKBDocumentsReports extends UpgradeScript
{
    const OLD_MODULE_NAME = 'KBDocuments';
    const NEW_MODULE_NAME = 'KBContents';

    public $version = '7.8';
    public $type = self::UPGRADE_DB;
    public $order = 6900; // Before the custom connection is deleted by "7_Connectors.php".

    /**
     * @var array Map old KBDocuments column to KBContents.
     */
    protected $displayColumnsMap = [
        'kbdocument_name' => 'name',
        'views_number' => 'viewcount',
        'kbdoc_approver_name' => 'kbsapprover_name',
        'kbdocument_revision_number' => 'revision',
        'is_external_article' => 'is_external',
        'status_id' => 'status',
        'assigned_user_name' => 'assigned_user_name',
        'body' => 'kbdocument_body',
        'case_name' => 'kbscase_name',
    ];

    /**
     * Convert KBDocument reports to new format (KBContents).
     * Run the script for on all version before 7.8.
     */
    public function run()
    {
        if (version_compare($this->from_version, '7.8.0.0', '>=')) {
            return;
        }

        $sq = new SugarQuery();
        $sq->select(['id']);
        $sq->from(BeanFactory::newBean('Reports'));
        $sq->where()
            ->equals('module', self::OLD_MODULE_NAME);
        $resultOldReports = $sq->execute();

        foreach ($resultOldReports as $kbdReport) {
            $this->log('KBDocument Report found: ' . $kbdReport['id']);

            $savedReport = BeanFactory::getBean(
                'Reports',
                $kbdReport['id'],
                ['encode' => false, 'strict_retrieve' => true]
            );
            $savedReport->module = self::NEW_MODULE_NAME;
            $this->convertReportsContent($savedReport);
            $savedReport->save();
        }
    }

    /**
     * Convert old column name to new.
     * @param string $column
     * @return string
     */
    protected function columnsMapper($column)
    {
        return array_key_exists($column, $this->displayColumnsMap) ? $this->displayColumnsMap[$column] : null;
    }

    /**
     * Converts the content property.
     * Replaces fields and all KBDocuments entries.
     * @param SavedReport $bean
     */
    protected function convertReportsContent(SavedReport $bean)
    {
        if (!$bean->content) {
            return;
        }
        $jsonObject = getJSONobj();
        $decodedContent = $jsonObject->decode($bean->content);

        $decodedContent['module'] = self::NEW_MODULE_NAME;
        $decodedContent['full_table_list']['self'] = [
            'value' => self::NEW_MODULE_NAME,
            'module' => self::NEW_MODULE_NAME,
            'label' => self::NEW_MODULE_NAME,
        ];

        foreach ($decodedContent['display_columns'] as $key => $displayColumn) {
            $mappedColumn = $this->columnsMapper($displayColumn['name']);

            if ($mappedColumn) {
                $this->log("Reports column '{$displayColumn['name']}' changed to '{$mappedColumn}'.");

                $decodedContent['display_columns'][$key]['name'] = $mappedColumn;
                // Seems labels are not necessary.
            }
        }
        $bean->content = $jsonObject->encode($decodedContent);
    }
}
