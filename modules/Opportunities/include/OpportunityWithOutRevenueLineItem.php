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
 * Class OpportunityWithOutRevenueLineItem
 *
 * This is used for when we want to convert from RLI back to just Opps
 */
class OpportunityWithOutRevenueLineItem extends OpportunitySetup
{
    protected $dateClosedMigration = 'max';

    /**
     * Mapping for the values of the vardefs
     *
     * @var array
     */
    protected $field_vardef_setup = array(
        'amount' => array(
            'required' => true,
            'audited' => true,
            'calculated' => false,
            'enforced' => false,
            'formula' => '',
            'readonly' => false,
            'massupdate' => true,
            'importable' => 'required',
        ),
        'best_case' => array(
            'calculated' => false,
            'enforced' => false,
            'formula' => '',
            'audited' => true,
            'readonly' => false,
            'massupdate' => true,
        ),
        'worst_case' => array(
            'calculated' => false,
            'enforced' => false,
            'formula' => '',
            'audited' => true,
            'readonly' => false,
            'massupdate' => true,
        ),
        'date_closed' => array(
            'calculated' => false,
            'enforced' => false,
            'formula' => '',
            'audited' => true,
            'importable' => 'required',
            'required' => true,
            'massupdate' => true,
        ),
        'commit_stage' => array(
            'massupdate' => true,
            'studio' => true,
            'reportable' => true,
            'workflow' => true
        ),
        'sales_stage' => array(
            'audited' => true,
            'required' => true,
            'studio' => true,
            'massupdate' => true,
            'reportable' => true,
            'workflow' => true,
            'importable' => 'required',
        ),
        'probability' => array(
            'audited' => true,
            'studio' => true,
            'massupdate' => true,
            'reportable' => true,
            'importable' => 'required',
        ),
        'sales_status' => array(
            'studio' => false,
            'reportable' => false,
            'audited' => false,
            'massupdate' => false,
            'importable' => false,
        ),
        'date_closed_timestamp' => array(
            'formula' => 'timestamp($date_closed)'
        ),
        'total_revenue_line_items' => array(
            'reportable' => false,
            'workflow' => false
        ),
        'closed_revenue_line_items' => array(
            'reportable' => false,
            'workflow' => false
        )
    );

    /**
     * Which reports should be shown and hidden.
     *
     * @var array
     */
    protected $reportchange = array(
        'show' => array('Current Quarter Forecast', 'Detailed Forecast'),
        'hide' => array(),
        'redefine' => array(
            'Opportunities Won By Lead Source' => '',
            'Pipeline By Type By Team' => '',
            'Pipeline By Team By User' => '',
        )
    );

    /**
     * Put any custom Convert Logic Here
     *
     * @return mixed|void
     */
    public function doMetadataConvert()
    {
        // always runt he parent first, since we need to fix the vardefs before doing the viewdefs
        parent::doMetadataConvert();

        // fix the record view first
        // only add the commit_stage field if forecasts is setup
        $this->fixRecordView(
            array(
                'commit_stage' => $this->isForecastSetup(),
                'sales_status' => false,
                'sales_stage' => true,
                'probability' => true
            )
        );

        // fix the various list views
        $this->fixListViews(
            array(
                'commit_stage' => $this->isForecastSetup(),
                'sales_status' => 'sales_stage',
                'probability' => true,
            )
        );

        $this->fixFilter(
            array(
                'sales_stage' => true,
                'sales_status' => false,
                'probability' => true,
            )
        );
    }

    /**
     * Metadata Fixes for the Opportunity Module
     *
     * - Removes the duplicate check change
     * - Removes the dependency extension that turns off the default oob dependencies
     */
    protected function fixOpportunityModule()
    {
        if (file_exists($this->moduleExtFolder . '/Vardefs/' . $this->dupeCheckExtFile)) {
            unlink($this->moduleExtFolder . '/Vardefs/' . $this->dupeCheckExtFile);
        }

        if (file_exists($this->moduleExtFolder . '/Dependencies/' . $this->oppModuleDependencyFile)) {
            unlink($this->moduleExtFolder . '/Dependencies/' . $this->oppModuleDependencyFile);
        }
    }

    /**
     * Metadata fixes for the RLI Module
     *
     * - Removes the file that shows the RLI Module
     * - Removes the Studio File
     * - Hides the RLI module from the menu bar
     * - Removes the ACL Actions
     */
    protected function fixRevenueLineItemModule()
    {
        // hide the RLI module from the quick create, this needs to be done first, so it's properly removed
        $this->toggleRevenueLineItemQuickCreate(false);

        // cleanup on the current request
        $GLOBALS['modInvisList'][] = 'RevenueLineItems';
        if (isset($GLOBALS['moduleList']) && is_array($GLOBALS['moduleList'])) {
            foreach ($GLOBALS['moduleList'] as $key => $mod) {
                if ($mod === 'RevenueLineItems') {
                    unset($GLOBALS['moduleList'][$key]);
                }
            }
        }

        // set the current loaded instance up
        if (isset($GLOBALS['dictionary']['RevenueLineItem'])) {
            $GLOBALS['dictionary']['RevenueLineItem']['importable'] = false;
            $GLOBALS['dictionary']['RevenueLineItem']['unified_search'] = false;
        }

        if (file_exists($this->appExtFolder . '/Include/' . $this->rliModuleExtFile)) {
            unlink($this->appExtFolder . '/Include/' . $this->rliModuleExtFile);
        }

        if (file_exists($this->rliStudioFile)) {
            unlink($this->rliStudioFile);
        }

        if (file_exists($this->rliModuleExtFolder . '/Vardefs/' . $this->rliModuleExtVardefFile)) {
            unlink($this->rliModuleExtFolder . '/Vardefs/' . $this->rliModuleExtVardefFile);
        }

        $this->cleanupUnifiedSearchCache();

        // hide the RLI module in workflows
        $affected_modules = $this->toggleRevenueLineItemsLinkInWorkFlows(false);

        // hide the mega menu tab
        $this->setRevenueLineItemModuleTab(false);

        // handle the parent type field
        $this->setRevenueLineItemInParentRelateDropDown(false);

        // disable the ACLs on RevenueLineItems
        ACLAction::removeActions('RevenueLineItems');

        // add the RLI module
        $affected_modules[] = 'RevenueLineItems';

        return $affected_modules;
    }

    /**
     * Call this method to convert the data as well, this should be called after `doMetadataConvert`
     */
    public function doDataConvert()
    {
        $this->resetForecastData('Opportunities');
        
        // fix the reports
        SugarAutoLoader::load('modules/Opportunities/include/OpportunityReports.php');
        $reports = new OpportunityReports();
        $reports->migrateToOpportunities();
        // soft delete all the RLI Reports
        $reports->deleteAllRevenueLineItemReports();

        $this->queueRevenueLineItemsForNotesOnOpportunities();
        $this->setOpportunityDataFromRevenueLineItems();
        $this->deleteRevenueLineItems();

        if ($this->isForecastSetup()) {
            SugarAutoLoader::load('include/SugarQueue/jobs/SugarJobUpdateOpportunities.php');
            SugarJobUpdateOpportunities::updateOpportunitiesForForecasting();
        }
    }

    /**
     * Delete all the RLI data, since it not needed any more
     */
    protected function deleteRevenueLineItems()
    {
        $rli = BeanFactory::newBean('RevenueLineItems');
        /* @var $db DBManager */
        $db = DBManagerFactory::getInstance();
        $db->commit();
        $db->query($db->truncateTableSQL($rli->getTableName()));
        $db->commit();
        $cstm_table = $rli->getTableName() . '_cstm';

        if ($db->tableExists($cstm_table)) {
            $db->commit();
            $db->query($db->truncateTableSQL($cstm_table));
            $db->commit();
        }
    }

    protected function queueRevenueLineItemsForNotesOnOpportunities()
    {
        /* @var $rli RevenueLineItem */
        $rli = BeanFactory::newBean('RevenueLineItems');

        $labels = array();

        $fields = array(
            'name',
            'sales_stage',
            'probability',
            'date_closed',
            'currency_id',
            'worst_case',
            'likely_case',
            'best_case',
            'opportunity_id',
            'next_step',
        );

        // for now use the default config
        $default_lang = $GLOBALS['sugar_config']['default_language'];
        $mod_strings = return_module_language($default_lang, $rli->module_name);
        $app_strings = return_application_language($default_lang);
        foreach ($fields as $field) {
            if ($field === 'currency_id') {
                $vname = 'LBL_CURRENCY';
            } else {
                $def = $rli->getFieldDefinition($field);
                $vname = $def['vname'];
            }
            if (isset($mod_strings[$vname])) {
                $labels[$field] = str_replace(':', '', $mod_strings[$vname]);
            } elseif (isset($app_strings[$vname])) {
                $labels[$field] = str_replace(':', '', $app_strings[$vname]);
            } else {
                $labels[$field] = $vname;
            }
        }

        // get all the rows
        $sq = new SugarQuery();
        $sq->select($fields);
        $sq->from($rli)
            ->orderBy('opportunity_id')
            ->orderBy('date_closed');

        $results = $sq->execute();

        $chunk = array();
        $max_chunk_size = 10;

        $job_group = md5(microtime());

        foreach ($results as $row) {
            if (!isset($chunk[$row['opportunity_id']])) {
                if (count($chunk) === $max_chunk_size) {
                    // schedule job here
                    $this->scheduleOpportunityRevenueLineItemNoteCreate($labels, $chunk, $job_group);
                    $chunk = array();
                }
                $chunk[$row['opportunity_id']] = array();
            }
            // remove the fields added by the sorting in SugarQuery
            unset($row['revenue_line_items__opportunity_id']);
            unset($row['revenue_line_items__date_closed']);
            $chunk[$row['opportunity_id']][] = $row;
        }

        // schedule the last job here.
        $this->scheduleOpportunityRevenueLineItemNoteCreate($labels, $chunk, $job_group);
    }

    private function scheduleOpportunityRevenueLineItemNoteCreate(array $labels, array $chunk, $job_group)
    {
        /* @var $job SchedulersJob */
        $job = BeanFactory::newBean('SchedulersJobs');
        $job->name = "Create Revenue Line Items Note On Opportunities";
        $job->target = "class::SugarJobCreateRevenueLineItemNotes";
        $job->data = json_encode(array('chunk' => $chunk, 'labels' => $labels));
        $job->retry_count = 0;
        $job->assigned_user_id = $GLOBALS['current_user']->id;
        $job->job_group = $job_group;

        $jq = new SugarJobQueue();
        $jq->submitJob($job);
    }

    /**
     * Fix the Opportunity Data to have the correct data once we go back from having RLI's to only have Opps
     *
     * - Takes the lowest sales_stage from all the RLIs
     * - Takes the lowest date_closed from all the RLIs
     * - Sets commit_stage to empty
     * - Sets sales_status to empty
     *
     * This is all done via a Query since we delete all the RLI's and we didn't want to keep any of them around.
     *
     * @throws SugarQueryException
     */
    protected function setOpportunityDataFromRevenueLineItems()
    {
        // need to figure out the best way to roll this up before truncating the table.
        $app_list_strings = return_app_list_strings_language($GLOBALS['current_language']);
        // get the sales_stage from the RLI module
        /* @var $rli RevenueLineItem */
        $rli = BeanFactory::newBean('RevenueLineItems');
        $def = $rli->getFieldDefinition('sales_stage');

        $db = DBManagerFactory::getInstance();
        $list_value = array();

        // get the `options` param so we make sure if they customized it to use their custom version
        $sqlCase = '';
        $list = $def['options'];
        if (!empty($list) && isset($app_list_strings[$list])) {
            $i = 0;
            $order_by_arr = array();
            foreach ($app_list_strings[$list] as $key => $value) {
                $list_value[$i] = $key;
                if ($key == '') {
                    $order_by_arr[] = "WHEN (sales_stage='' OR sales_stage IS NULL) THEN " . $i++;
                } else {
                    $order_by_arr[] = "WHEN sales_stage=" . $db->quoted($key) . " THEN " . $i++;
                }
            }
            $sqlCase = "min(CASE " . implode("\n", $order_by_arr) . " ELSE $i END)";
        }

        $fcsettings = Forecast::getSettings();

        $stage_cases = array();
        $closed_stages = array_merge($fcsettings['sales_stage_won'], $fcsettings['sales_stage_lost']);

        foreach($closed_stages as $stage) {
            $stage_cases[] = $db->quoted($stage);
        }

        $stage_cases = implode(',', $stage_cases);

        $lost_stages = array();
        foreach($fcsettings['sales_stage_lost'] as $row) {
            $lost_stages[] = $db->quoted($row);
        }

        $lost_stages = implode(',', $lost_stages);

        $sq = new SugarQuery();
        $sq->select(array('opportunity_id'))
            ->fieldRaw('COUNT(opportunity_id)', 'rli_count')
            ->fieldRaw($sqlCase, 'sales_stage')
            ->fieldRaw($this->dateClosedMigration . '(CASE when sales_stage IN (' . $stage_cases . ') THEN date_closed END)', 'dc_closed')
            ->fieldRaw($this->dateClosedMigration . '(CASE when sales_stage NOT IN (' . $stage_cases . ') THEN date_closed END)', 'dc_open')
            ->fieldRaw($this->dateClosedMigration . '(CASE when sales_stage IN (' . $stage_cases . ') THEN date_closed_timestamp END)', 'dct_closed')
            ->fieldRaw($this->dateClosedMigration . '(CASE when sales_stage NOT IN (' . $stage_cases . ') THEN date_closed_timestamp END)', 'dct_open');
        $sq->from($rli);
        $sq->groupBy('opportunity_id');

        $results = $sq->execute();

        $opportunity_ids = array();
        foreach ($results as $result) {
            $opportunity_ids[] = $db->quoted($result['opportunity_id']);
        }

        // If we have no Opportunities to work with, we're done processing.
        if (empty($opportunity_ids)) {
            return true;
        }

        $opportunity_ids = implode(',', $opportunity_ids);

        $closed_rli_sql = 'SELECT opportunity_id, COUNT(id) AS rli_count, SUM(best_case) AS best, SUM(likely_case) AS likely, SUM(worst_case) AS worst FROM revenue_line_items WHERE opportunity_id IN (' . $opportunity_ids . ') AND sales_stage IN (' . $lost_stages . ') GROUP BY opportunity_id';
        $closed_rli_result = $db->query($closed_rli_sql);

        $closed_rlis = array();
        while ($row = $db->fetchByAssoc($closed_rli_result)) {
            $closed_rlis[$row['opportunity_id']] = $row;
        }

        foreach ($results as $result) {
            $sql = 'UPDATE opportunities SET date_closed = ' . $db->quoted((!empty($result['dc_open']) ? $result['dc_open'] : $result['dc_closed'])) . ',
                date_closed_timestamp = ' . $db->quoted((!empty($result['dct_open']) ? $result['dct_open'] : $result['dct_closed'])) . ',
                sales_stage = ' . $db->quoted($list_value[$result['sales_stage']]) . ',
                included_revenue_line_items = 0, total_revenue_line_items = 0, closed_revenue_line_items = 0,
                probability = ' . $db->quoted($app_list_strings['sales_probability_dom'][$list_value[$result['sales_stage']]]) . ',
                sales_status = ' . $db->quoted('') . ', commit_stage = ' . $db->quoted('');

            if (isset($closed_rlis[$result['opportunity_id']]) && $result['rli_count'] == $closed_rlis[$result['opportunity_id']]['rli_count']) {
                $sql .= ', amount = ' . $db->quoted($closed_rlis[$result['opportunity_id']]['likely']) . ',
                    best_case = ' . $db->quoted($closed_rlis[$result['opportunity_id']]['best']) . ',
                    worst_case = ' . $db->quoted($closed_rlis[$result['opportunity_id']]['worst']);
            }

            $sql .= ' WHERE id = ' . $db->quoted($result['opportunity_id']);

            $db->query($sql);
        }
    }

    public function setDateClosedMigrationParam($type)
    {
        $type = strtolower($type);
        if ($type === 'earliest') {
            $this->dateClosedMigration = 'min';
        } else {
            $this->dateClosedMigration = 'max';
        }
    }

    /**
     * Lets make sure the WorkFlows are cleaned up
     */
    protected function processWorkFlows()
    {
        $this->deleteRevenueLineItemsWorkFlows();
        $this->deleteRevenueLineItemsRelatedActions();
        $this->deleteRevenueLineItemsRelatedTriggers();
        $this->deleteRevenueLineItemWorkFlowEmailTemplates();

        // Advanced Workflow RLI Definitions need to be disabled
        $this->disableRevenueLineItemsProcessDefinitions();

        parent::processWorkFlows();
    }

    /**
     * Lets delete all the RevenueLineItem WorkFlows
     *
     * @throws SugarQueryException
     */
    private function deleteRevenueLineItemsWorkFlows()
    {
        /* @var $workFlow WorkFlow */
        $workFlow = BeanFactory::newBean('WorkFlow');

        $sq = new SugarQuery();
        $sq->select(array('id'));
        $sq->from($workFlow);
        $sq->where()
            ->equals('base_module', 'RevenueLineItems');

        $rows = $sq->execute();

        // now delete all the workflows that were found
        // this will also delete all the related items
        foreach ($rows as $row) {
            $workFlow->mark_deleted($row['id']);
        }

    }

    /**
     * Delete all the Actions that do something on the RevenueLineItems Module
     *
     * @throws SugarQueryException
     */
    private function deleteRevenueLineItemsRelatedActions()
    {
        // get the action shells
        $actionShells = BeanFactory::newBean('WorkFlowActionShells');

        $sq = new SugarQuery();
        $sq->select(array('id', 'parent_id'));
        $sq->from($actionShells);
        $sq->where()
            ->queryOr()
                ->equals('rel_module', 'revenuelineitems')
                ->equals('action_module', 'revenuelineitems');

        $rows = $sq->execute();

        foreach ($rows as $row) {
            $actionShells->retrieve($row['id']);
            $actionShells->check_for_child_bridge(true);

            mark_delete_components($actionShells->get_linked_beans('actions', 'WorkFlowAction'));
            mark_delete_components($actionShells->get_linked_beans('rel1_action_fil', 'Expression'));
            $actionShells->mark_deleted($row['id']);
            $actionShells->get_workflow_object()->write_workflow();
        }
    }

    /**
     * Delete all the Triggers that are triggered by the RLI Module
     *
     * @throws SugarQueryException
     */
    private function deleteRevenueLineItemsRelatedTriggers()
    {
        // get the action shells
        $triggerShells = BeanFactory::newBean('WorkFlowTriggerShells');

        $sq = new SugarQuery();
        $sq->select(array('id', 'parent_id'));
        $sq->from($triggerShells);
        $sq->where()
            ->equals('rel_module', 'revenuelineitems');

        $rows = $sq->execute();

        foreach ($rows as $row) {
            $triggerShells->mark_deleted($row['id']);
        }
    }

    /**
     * Delete all the Email Templates for the Revenue Line Items Module
     */
    private function deleteRevenueLineItemWorkFlowEmailTemplates()
    {
        $db = DBManagerFactory::getInstance();
        $sql = 'UPDATE email_templates SET deleted = 1 WHERE base_module = ' . $db->quoted('RevenueLineItems');

        $db->query($sql);
    }

    /**
     * Disable all Process Definitions with target module as Revenue Line Items.
     * Use a job queue since process can take a while.
     * @throws SugarQueryException
     */
    private function disableRevenueLineItemsProcessDefinitions()
    {
        $projectBean = BeanFactory::newBean('pmse_Project');
        $q = new SugarQuery();
        $q->select(array('id'));
        $q->from($projectBean);
        $q->where()->equals('prj_module', 'RevenueLineItems');

        $results = $q->execute();
        $ids = array_map(function ($obj) {
            return $obj['id'];
        }, $results);

        /* @var $job SchedulersJob */
        $job = BeanFactory::newBean('SchedulersJobs');
        $job->name = "Mass Enable/Disable Process Definitions";
        $job->target = "class::SugarJobUpdatePdStatus";
        $job->data = json_encode(array('ids' => $ids, 'status' => 'INACTIVE'));

        $jq = new SugarJobQueue();
        $jq->submitJob($job);
    }

    /**
     * Update the field in the Products Module
     */
    protected function fixProductsModule()
    {
        $this->fixProductsModuleField('revenuelineitem_name', 'massupdate', false);
    }
}
