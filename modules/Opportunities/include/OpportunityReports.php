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

class OpportunityReports
{
    /**
     * The RevenueLineItem Table Name for Reports
     *
     * @var string
     */
    protected $rli_table_name = 'Opportunities:revenuelineitems';

    /**
     * The RevenueLineItem Table Def for Reports
     *
     * @var array
     */
    protected $rli_table_def = array(
        'name' => 'Opportunities  >  Revenue Line Items',
        'parent' => 'self',
        'link_def' => array(
            'name' => 'revenuelineitems',
            'relationship_name' => 'opportunities_revenuelineitems',
            'bean_is_lhs' => true,
            'link_type' => 'many',
            'label' => 'Revenue Line Items',
            'module' => 'RevenueLineItems',
            'table_key' => 'Opportunities:revenuelineitems',
        ),
        'module' => 'RevenueLineItems',
        'label' => 'Revenue Line Items',
    );

    /**
     * @var DBManager
     */
    protected $db;

    public function __construct()
    {
        $this->db = DBManagerFactory::getInstance();
    }

    public function migrateToRevenueLineItems()
    {
        $reports = $this->getReports();

        foreach ($reports as $id => $report) {
            // reset the name, just in case.
            $this->rli_table_name = 'Opportunities:revenuelineitems';

            // if links_defs is there, we should set that as well
            if (isset($report['links_def'])) {
                $report['links_def'][] = 'revenuelineitems';
                // if we are setting the links_defs, the rli_table_name needs to be changed
                $this->rli_table_name = 'revenuelineitems';
            } elseif (isset($report['full_table_list'])) {
                if (isset($report['full_table_list']['self']['children']) &&
                        is_array($report['full_table_list']['self']['children'])) {
                    $this->rli_table_name = 'self_link_' . count($report['full_table_list']);
                    $report['full_table_list']['self']['children'][$this->rli_table_name] = $this->rli_table_name;
                }
                $report['full_table_list'][$this->rli_table_name] = $this->rli_table_def;
            } else {
                // if we don't have a links_def or the full_table_list, we should just bail out now.
                continue;
            }

            // lets loop though all the display_columns and find anyone that is sales_stage
            foreach (array('group_defs', 'display_columns', 'summary_columns') as $type) {
                foreach ($report[$type] as $key => $column) {
                    if ($column['name'] == 'sales_stage' && $column['table_key'] == 'self') {
                        $report[$type][$key]['table_key'] = $this->rli_table_name;
                    }
                }
            }

            // now lets fix all the filters.
            foreach ($report['filters_def'] as $name => $filter) {

                $returnSingleFilter = false;
                if (isset($filter['name']) && isset($filter['table_key'])) {
                    $returnSingleFilter = true;
                    $filter = array($filter);
                }

                $filter = $this->fixFilters($filter, $this->rli_table_name);
                if ($returnSingleFilter) {
                    $filter = array_shift($filter);
                }

                $report['filters_def'][$name] = $filter;
            }

            $this->saveReport($id, $report);

            $this->cleanUp();
        }
    }

    public function migrateToOpportunities()
    {
        $reports = $this->getReports();

        foreach ($reports as $id => $report) {
            // reset the name, just in case.
            $this->rli_table_name = 'Opportunities:revenuelineitems';

            // if links_defs is there, we need to unset it from there
            if (isset($report['links_def'])) {
                if ($loc = array_search('revenuelineitems', $report['links_def'])) {
                    unset($report['links_def'][$loc]);
                }
                // if we are setting the links_defs, the rli_table_name needs to be changed
                $this->rli_table_name = 'revenuelineitems';
            } elseif (isset($report['full_table_list'])) {
                if (isset($report['full_table_list']['self']['children']) &&
                        is_array($report['full_table_list']['self']['children'])) {

                    // find the RLI module
                    foreach($report['full_table_list']['self']['children'] as $child) {
                        if (isset($report['full_table_list'][$child]['module']) &&
                            $report['full_table_list'][$child]['module'] === 'RevenueLineItems') {
                            $this->rli_table_name = $child;
                            break;
                        }
                    }
                    unset($report['full_table_list']['self']['children'][$this->rli_table_name]);
                }
                // if it's in the full_table_list, it should be removed from there.
                if (isset($report['full_table_list'][$this->rli_table_name])) {
                    unset($report['full_table_list'][$this->rli_table_name]);
                }
            } else {
                // if we don't have a links_def or the full_table_list, we should just bail out now.
                continue;
            }

            // lets loop though all the display_columns and find anyone that is sales_stage
            foreach (array('group_defs', 'display_columns', 'summary_columns') as $type) {
                foreach ($report[$type] as $key => $column) {
                    if ($column['name'] == 'sales_stage' && $column['table_key'] == $this->rli_table_name) {
                        $report[$type][$key]['table_key'] = 'self';
                    }
                }
            }

            // now lets fix all the filters.
            foreach ($report['filters_def'] as $name => $filter) {

                $returnSingleFilter = false;
                if (isset($filter['name']) && isset($filter['table_key'])) {
                    $returnSingleFilter = true;
                    $filter = array($filter);
                }

                $filter = $this->fixFilters($filter, 'self');
                if ($returnSingleFilter) {
                    $filter = array_shift($filter);
                }

                $report['filters_def'][$name] = $filter;
            }

            $this->saveReport($id, $report);

            $this->cleanUp();
        }
    }

    protected function getReports()
    {
        $query = 'SELECT id, content FROM saved_reports WHERE module = ? AND content LIKE ? AND deleted = 0';
        $conn = $this->db->getConnection();
        $stmt = $conn->executeQuery($query, array('Opportunities', '%"name":"sales_stage"%'));

        $reports = array();
        while ($row = $stmt->fetch()) {
            $reports[$row['id']] = json_decode($row['content'], true);
        }

        return $reports;
    }

    protected function saveReport($id, array $report)
    {
        $conn = $this->db->getConnection();
        $conn->update('saved_reports', array(
            'content' => json_encode($report, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT),
        ), array(
            'id' => $id,
        ));
    }

    /**
     * Soft Delete all the Revenue Line Item Reports, this should only be called when switching from Opps w/ RLIs to
     * Opps w/o RLIs
     */
    public function deleteAllRevenueLineItemReports()
    {
        $sql = "UPDATE saved_reports set deleted = 1 WHERE module = 'RevenueLineItems' AND deleted = 0";
        $this->db->query($sql);
    }

    /**
     * Clean up the Cache
     */
    protected function cleanUp()
    {
        // clear out any js cache, as the reports will screw up if they are not cleared
        $rac = new RepairAndClear();
        $rac->clearJsFiles();
    }

    /**
     * Utility method to loop down all the defined filters
     *
     * @param string $filter the filter we are looking at
     * @param string $table_name The table name we want to set the value to
     * @return mixed
     */
    protected function fixFilters($filter, $table_name)
    {
        foreach ($filter as $name => $f) {
            if ($name === 'operator') {
                continue;
            }

            // if the operator is set, then we have a group by, and we need to process all those queries
            if (isset($f['operator'])) {
                $filter[$name] = $this->fixFilters($f, $table_name);
            } elseif ($f['name'] === 'sales_stage' && $f['table_key'] !== $table_name) {
                $filter[$name]['table_key'] = $table_name;
            }
        }
        return $filter;
    }
}
