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

class SugarUpgradeFixReportChartDashletMeta extends UpgradeScript
{
    public $order = 7650;
    public $type = self::UPGRADE_DB;

    private $savedReportCache;

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        if (version_compare($this->from_version, '8.0', '>=')) {
            return;
        }

        $genericDashboardBean = BeanFactory::newBean('Dashboards');
        $dashboardQuery = new SugarQuery();
        $dashboardQuery->from($genericDashboardBean);

        $allDashboards = $genericDashboardBean->fetchFromQuery($dashboardQuery);

        $genericReportsBean = BeanFactory::newBean('Reports');
        $reportQuery = new SugarQuery();
        $reportQuery->from($genericReportsBean);

        $this->savedReportCache = $genericReportsBean->fetchFromQuery(
            $reportQuery,
            array('id', 'name')
        );

        foreach ($allDashboards as $beanId => $dashboardBean) {
            $this->updateReportsDashlet($dashboardBean);
        }
    }

    /**
     * Adds the report name to the saved reports chart dashlet metadata
     *
     * @param SugarBean dashboardBean The dashboard bean to fix
     */
    public function updateReportsDashlet($dashboardBean)
    {
        $metadata = json_decode(html_entity_decode($dashboardBean->metadata));

        // Only run if the metadata is defined and has components
        if (empty($metadata) || !property_exists($metadata, 'components')) {
            return false;
        }

        $changed = false;

        // Parse through the dashboard metadata and update the metadata for
        // the Saved Reports Chart Dashlet.
        foreach ($metadata->components as $component) {
            if (!property_exists($component, 'rows')) {
                continue;
            }

            foreach ($component->rows as $rowsKey => $rows) {
                foreach ($rows as $dashletKey => $dashlet) {
                    if (!property_exists($dashlet, 'view')) {
                        continue;
                    }

                    $view = $dashlet->view;

                    $shouldUpdate = property_exists($view, 'type') &&
                        $view->type === 'saved-reports-chart' &&
                        !property_exists($view, 'saved_report');

                    if ($shouldUpdate) {
                        $savedReportId = $view->saved_report_id;
                        $view->saved_report = $this->savedReportCache[$savedReportId]->name;
                        $changed = true;
                    }
                }
            }
        }

        if ($changed) {
            $dashboardBean->metadata = json_encode($metadata);
            $dashboardBean->save();
        }
    }
}
