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
 * Class to fix old style report defs during upgrade
 */
class SugarUpgradeUpdateReportDef extends UpgradeScript
{
    public $order = 9100;
    public $type = self::UPGRADE_CUSTOM;

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        if (version_compare($this->from_version, '7.10.0.0', '<')) {
            $this->updateReports();
        }
    }

    /**
     *
     * Get all the reports, update the report def and save
     */
    public function updateReports()
    {
        $q = new SugarQuery();
        $q->select(array('id'));
        $q->from(BeanFactory::newBean('Reports'));
        $rows = $q->execute();
        foreach ($rows as $row) {
            $saved_report = BeanFactory::getBean('Reports', $row['id'], array('encode' => false));
            // Running through the Report constructor sanitizes the report def
            $oldContent = $saved_report->content;
            $report = new Report($oldContent);

            // No need to save if there aren't any changes
            if ($oldContent === $report->report_def_str) {
                continue;
            }

            $saved_report->content = $report->report_def_str;
            // Don't update date modfied and modified by
            $saved_report->update_date_modified = false;
            $saved_report->update_modified_by = false;
            $saved_report->save();
            $this->log('Updated report definition for Report: ' . $saved_report->name);
        }
    }
}
