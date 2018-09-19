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
 * Update dashlets when upgrading CE->PRO
 * TODO: irrelevant for 7?
 */
class SugarUpgradeUpgradeDashlets extends UpgradeScript
{
    public $order = 4000;
    public $type = self::UPGRADE_DB;

    public function run()
    {
        if (!($this->from_flavor == 'ce' && $this->toFlavor('pro')))
            return;
        $dashletsFiles = array();
        if (file_exists($cachedfile = sugar_cached('dashlets/dashlets.php'))) {
            require $cachedfile;
        }

        if (file_exists('modules/Home/dashlets.php')) {
            require 'modules/Home/dashlets.php';
        }


        $prefstomove = array('mypbss_date_start' => 'MyPipelineBySalesStageDashlet',
            'mypbss_date_end' => 'MyPipelineBySalesStageDashlet',
            'mypbss_sales_stages' => 'MyPipelineBySalesStageDashlet',
            'mypbss_chart_type' => 'MyPipelineBySalesStageDashlet',
            'lsbo_lead_sources' => 'OpportunitiesByLeadSourceByOutcomeDashlet',
            'lsbo_ids' => 'OpportunitiesByLeadSourceByOutcomeDashlet',
            'pbls_lead_sources' => 'OpportunitiesByLeadSourceDashlet',
            'pbls_ids' => 'OpportunitiesByLeadSourceDashlet',
            'pbss_date_start' => 'PipelineBySalesStageDashlet',
            'pbss_date_end' => 'PipelineBySalesStageDashlet',
            'pbss_sales_stages' => 'PipelineBySalesStageDashlet',
            'pbss_chart_type' => 'PipelineBySalesStageDashlet',
            'obm_date_start' => 'OutcomeByMonthDashlet', 'obm_date_end' => 'OutcomeByMonthDashlet',
            'obm_ids' => 'OutcomeByMonthDashlet');

        $result = $this->db->query("SELECT id FROM users where deleted = '0' AND status != 'Reserved' AND portal_only = 0");

        while ($row = $this->db->fetchByAssoc($result)) {
            $current_user = BeanFactory::getBean('Users', $row['id']);

            // Set the user theme to be 'Sugar' theme since this is run for CE
            // flavor conversions
            $current_user->setPreference('user_theme', 'Sugar', 0, 'global');

            $pages = $current_user->getPreference('pages', 'Home');

            if (empty($pages)) {
                continue;
            }

            $empty_dashlets = array();
            $dashlets = $current_user->getPreference('dashlets', 'Home');
            $dashlets = !empty($dashlets) ? $dashlets : $empty_dashlets;
            $existingDashlets = array();
            foreach ($dashlets as $id => $dashlet) {
                if (!empty($dashlet['className']) && !is_array($dashlet['className'])) {
                    $existingDashlets[$dashlet['className']] = $dashlet['className'];
                }
            } // foreach

            // BEGIN 'Sales Page'
            $salesDashlets = array();
            foreach ($defaultSalesDashlets as $salesDashletName => $module) {
                // clint - fixes bug #20398
                // only display dashlets that are from visibile modules and that
                // the user has permission to list
                $myDashlet = new MySugar($module);
                $displayDashlet = $myDashlet->checkDashletDisplay();
                if (isset($dashletsFiles[$salesDashletName]) && $displayDashlet) {
                    $options = array();
                    $prefsforthisdashlet = array_keys($prefstomove, $salesDashletName);
                    foreach ($prefsforthisdashlet as $pref) {
                        $options[$pref] = $current_user->getPreference($pref);
                    }

                    $salesDashlets[create_guid()] = array('className' => $salesDashletName,
                        'module' => $module,
                        'fileLocation' => $dashletsFiles[$salesDashletName]['file'],
                        'options' => $options);
                }
            }

            foreach ($defaultSalesChartDashlets as $salesChartDashlet => $module) {
                $savedReport = new SavedReport();
                $reportId = $savedReport->retrieveReportIdByName($salesChartDashlet);
                // clint - fixes bug #20398
                // only display dashlets that are from visibile modules and that
                // the user has permission to list
                $myDashlet = new MySugar($module);
                $displayDashlet = $myDashlet->checkDashletDisplay();

                if (isset($reportId) && $displayDashlet) {
                    $salesDashlets[create_guid()] = array('className' => 'ChartsDashlet',
                        'module' => $module,
                        'fileLocation' => $dashletsFiles['ChartsDashlet']['file'],
                        'reportId' => $reportId);
                }
            }

            $count = 0;
            $salesColumns = array();
            $salesColumns[0] = array();
            $salesColumns[0]['width'] = '60%';
            $salesColumns[0]['dashlets'] = array();
            $salesColumns[1] = array();
            $salesColumns[1]['width'] = '40%';
            $salesColumns[1]['dashlets'] = array();

            foreach ($salesDashlets as $guid => $dashlet) {
                if ($count % 2 == 0)
                    array_push($salesColumns[0]['dashlets'], $guid);
                else
                    array_push($salesColumns[1]['dashlets'], $guid);
                $count++;
            }
            // END 'Sales Page'

            // BEGIN 'Marketing Page'
            $marketingDashlets = array();
            foreach ($defaultMarketingChartDashlets as $marketingChartDashlet => $module) {
                $savedReport = new SavedReport();
                $reportId = $savedReport->retrieveReportIdByName($marketingChartDashlet);
                // clint - fixes bug #20398
                // only display dashlets that are from visibile modules and that
                // the user has permission to list
                $myDashlet = new MySugar($module);
                $displayDashlet = $myDashlet->checkDashletDisplay();

                if (isset($reportId) && $displayDashlet) {
                    $marketingDashlets[create_guid()] = array('className' => 'ChartsDashlet',
                        'module' => $module,
                        'fileLocation' => $dashletsFiles['ChartsDashlet']['file'],
                        'reportId' => $reportId);
                }
            }

            foreach ($defaultMarketingDashlets as $marketingDashletName => $module) {
                // clint - fixes bug #20398
                // only display dashlets that are from visibile modules and that
                // the user has permission to list
                $myDashlet = new MySugar($module);
                $displayDashlet = $myDashlet->checkDashletDisplay();

                if (isset($dashletsFiles[$marketingDashletName]) && $displayDashlet) {
                    $options = array();
                    $prefsforthisdashlet = array_keys($prefstomove, $marketingDashletName);
                    foreach ($prefsforthisdashlet as $pref) {
                        $options[$pref] = $current_user->getPreference($pref);
                    } // foreach
                    $marketingDashlets[create_guid()] = array('className' => $marketingDashletName,
                        'module' => $module,
                        'fileLocation' => $dashletsFiles[$marketingDashletName]['file'],
                        'options' => $options);
                }
            }

            $count = 0;
            $marketingColumns = array();
            $marketingColumns[0] = array();
            $marketingColumns[0]['width'] = '30%';
            $marketingColumns[0]['dashlets'] = array();
            $marketingColumns[1] = array();
            $marketingColumns[1]['width'] = '30%';
            $marketingColumns[1]['dashlets'] = array();
            $marketingColumns[2] = array();
            $marketingColumns[2]['width'] = '40%';
            $marketingColumns[2]['dashlets'] = array();

            foreach ($marketingDashlets as $guid => $dashlet) {
                if ($count % 3 == 0)
                    array_push($marketingColumns[0]['dashlets'], $guid);
                else
                    if ($count % 3 == 1)
                        array_push($marketingColumns[1]['dashlets'], $guid);
                    else
                        array_push($marketingColumns[2]['dashlets'], $guid);
                $count++;
            }
            // END 'Marketing Page'

            // BEGIN 'Support Page'- bug46195
            $supportDashlets = array();
            foreach ($defaultSupportChartDashlets as $supportChartDashlet => $module) {
                $savedReport = new SavedReport();
                $reportId = $savedReport->retrieveReportIdByName($supportChartDashlet);
                $myDashlet = new MySugar($module);
                $displayDashlet = $myDashlet->checkDashletDisplay();

                if (isset($reportId) && $displayDashlet) {
                    $supportDashlets[create_guid()] = array('className' => 'ChartsDashlet',
                        'module' => $module,
                        'fileLocation' => $dashletsFiles['ChartsDashlet']['file'],
                        'reportId' => $reportId);
                }
            }

            foreach ($defaultSupportDashlets as $supportDashletName => $module) {

                $myDashlet = new MySugar($module);
                $displayDashlet = $myDashlet->checkDashletDisplay();

                if (isset($dashletsFiles[$supportDashletName]) && $displayDashlet) {
                    $options = array();
                    $prefsforthisdashlet = array_keys($prefstomove, $supportDashletName);
                    foreach ($prefsforthisdashlet as $pref) {
                        $options[$pref] = $current_user->getPreference($pref);
                    } // foreach
                    $supportDashlets[create_guid()] = array('className' => $supportDashletName,
                        'module' => $module,
                        'fileLocation' => $dashletsFiles[$supportDashletName]['file'],
                        'options' => $options);
                }
            }

            $count = 0;
            $supportColumns = array();
            $supportColumns[0] = array();
            $supportColumns[0]['width'] = '30%';
            $supportColumns[0]['dashlets'] = array();
            $supportColumns[1] = array();
            $supportColumns[1]['width'] = '30%';
            $supportColumns[1]['dashlets'] = array();
            $supportColumns[2] = array();
            $supportColumns[2]['width'] = '40%';
            $supportColumns[2]['dashlets'] = array();

            foreach ($supportDashlets as $guid => $dashlet) {
                if ($count % 3 == 0)
                    array_push($supportColumns[0]['dashlets'], $guid);
                else
                    if ($count % 3 == 1)
                        array_push($supportColumns[1]['dashlets'], $guid);
                    else
                        array_push($supportColumns[2]['dashlets'], $guid);
                $count++;
            }
            // END ' Support Page' - bug 46195

            // Set the dashlets pages to user preferences table
            $pageIndex = count($pages);
            $pages[$pageIndex]['columns'] = $salesColumns;
            $pages[$pageIndex]['numColumns'] = '2';
            $pages[$pageIndex]['pageTitle'] = $this->mod_strings['LBL_HOME_PAGE_2_NAME']; // "Sales Page"
            $pageIndex++;

            $pages[$pageIndex]['columns'] = $marketingColumns;
            $pages[$pageIndex]['numColumns'] = '3';
            $pages[$pageIndex]['pageTitle'] = $this->mod_strings['LBL_HOME_PAGE_6_NAME']; // "Marketing Page"
            $pageIndex++;

            $pages[$pageIndex]['columns'] = $supportColumns;
            $pages[$pageIndex]['numColumns'] = '4';
            $pages[$pageIndex]['pageTitle'] = $this->mod_strings['LBL_HOME_PAGE_3_NAME']; // "Support Page" - bug 46195

            $dashlets = array_merge($dashlets, $salesDashlets, $marketingDashlets, $supportDashlets);
            $current_user->setPreference('dashlets', $dashlets, 0, 'Home');
            $current_user->setPreference('pages', $pages, 0, 'Home');
        } // while
    }
}
