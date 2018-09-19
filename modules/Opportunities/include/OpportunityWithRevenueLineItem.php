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
 * Class OpportunityWithRevenueLineItem
 */
class OpportunityWithRevenueLineItem extends OpportunitySetup
{
    /**
     * How should the fields act when using RevenueLineItems
     *
     * @var array
     */
    protected $field_vardef_setup = array(
        'amount' => array(
            'required' => false,
            'audited' => false,
            'calculated' => true,
            'enforced' => true,
            'formula' => 'rollupConditionalSum($revenuelineitems, "likely_case", "sales_stage", forecastSalesStages(true, false))',
            'readonly' => true,
            'massupdate' => false,
            'importable' => true,
        ),
        'best_case' => array(
            'calculated' => true,
            'enforced' => true,
            'formula' => 'rollupConditionalSum($revenuelineitems, "best_case", "sales_stage", forecastSalesStages(true, false))',
            'audited' => false,
            'readonly' => true,
            'massupdate' => false,
        ),
        'worst_case' => array(
            'calculated' => true,
            'enforced' => true,
            'formula' => 'rollupConditionalSum($revenuelineitems, "worst_case", "sales_stage", forecastSalesStages(true, false))',
            'audited' => false,
            'readonly' => true,
            'massupdate' => false,
        ),
        'date_closed' => array(
            'calculated' => true,
            'enforced' => true,
            'formula' => 'maxRelatedDate($revenuelineitems, "date_closed")',
            'audited' => false,
            'importable' => true,
            'required' => false,
            'massupdate' => false,
        ),
        'commit_stage' => array(
            'massupdate' => false,
            'studio' => false,
            'reportable' => false,
            'workflow' => false,
        ),
        'sales_stage' => array(
            'audited' => false,
            'required' => false,
            'studio' => false,
            'massupdate' => false,
            'reportable' => false,
            'workflow' => false,
            'importable' => false,
        ),
        'probability' => array(
            'audited' => false,
            'studio' => false,
            'massupdate' => false,
            'reportable' => false,
            'importable' => false,
        ),
        'sales_status' => array(
            'studio' => true,
            'reportable' => true,
            'audited' => true,
            'massupdate' => true,
            'importable' => true,
        ),
        'date_closed_timestamp' => array(
            'formula' => 'rollupMax($revenuelineitems, "date_closed_timestamp")'
        ),
        'total_revenue_line_items' => array(
            'reportable' => true,
            'workflow' => true
        ),
        'closed_revenue_line_items' => array(
            'reportable' => true,
            'workflow' => true
        )
    );

    /**
     * Which reports should be shown and hidden.
     *
     * @var array
     */
    protected $reportchange = array(
        'show' => array(),
        'hide' => array('Current Quarter Forecast', 'Detailed Forecast'),
        'redefine' => array(
            // @codingStandardsIgnoreStart
            'Opportunities Won By Lead Source' => '{"display_columns":[],"module":"Opportunities","group_defs":[{"name":"lead_source","label":"Lead Source","table_key":"self","type":"enum"}],"summary_columns":[{"name":"lead_source","label":"Opportunities: Lead Source","table_key":"self"},{"name":"likely_case","label":"SUM: Likely","field_type":"currency","group_function":"sum","table_key":"Opportunities:revenuelineitems"},{"name":"count","label":"Count","field_type":"","group_function":"count","table_key":"self"}],"report_name":"Opportunities Won By Lead Source","chart_type":"hBarF","do_round":1,"chart_description":"","numerical_chart_column":"Opportunities:revenuelineitems:likely_case:sum","numerical_chart_column_type":"currency","assigned_user_id":"1","report_type":"summary","full_table_list":{"self":{"value":"Opportunities","module":"Opportunities","label":"Opportunities","dependents":[]},"Opportunities:revenuelineitems":{"name":"Opportunities  \u003E  Revenue Line Items","parent":"self","link_def":{"name":"revenuelineitems","relationship_name":"opportunities_revenuelineitems","bean_is_lhs":true,"link_type":"many","label":"Revenue Line Items","module":"RevenueLineItems","table_key":"Opportunities:revenuelineitems"},"module":"RevenueLineItems","label":"Revenue Line Items","dependents":["Filter.1_table_filter_row_1","display_summaries_row_2"]}},"filters_def":{"Filter_1":{"operator":"AND","0":{"name":"sales_stage","table_key":"Opportunities:revenuelineitems","qualifier_name":"is","input_name0":["Closed Won"]}}}}',
            'Pipeline By Type By Team' => '{"display_columns":[],"module":"Opportunities","group_defs":[{"name":"name","label":"Primary Team Name","table_key":"Opportunities:team_link","type":"name"},{"name":"opportunity_type","label":"Type","table_key":"self","type":"enum"}],"summary_columns":[{"name":"name","label":"Team: Team Name","table_key":"Opportunities:team_link"},{"name":"opportunity_type","label":"Opportunities: Type","table_key":"self"},{"name":"likely_case","label":"SUM: Likely","field_type":"currency","group_function":"sum","table_key":"Opportunities:revenuelineitems"}],"report_name":"Pipeline By Type By Team","chart_type":"hBarF","do_round":1,"chart_description":"","numerical_chart_column":"Opportunities:revenuelineitems:likely_case:sum","numerical_chart_column_type":"currency","assigned_user_id":"1","report_type":"summary","full_table_list":{"self":{"value":"Opportunities","module":"Opportunities","label":"Opportunities","dependents":[]},"Opportunities:team_link":{"parent":"self","label":"Team","link_def":{"name":"team_link","relationship_name":"opportunities_team","bean_is_lhs":"","link_type":"one","label":"Team","table_key":"Opportunities:team_link"},"module":"Teams","name":"Opportunities  \u003E  Teams","dependents":["group_by_row_1","display_summaries_row_group_by_row_1"]},"Opportunities:revenuelineitems":{"name":"Opportunities  \u003E  Revenue Line Items","parent":"self","link_def":{"name":"revenuelineitems","relationship_name":"opportunities_revenuelineitems","bean_is_lhs":true,"link_type":"many","label":"Revenue Line Items","module":"RevenueLineItems","table_key":"Opportunities:revenuelineitems"},"module":"RevenueLineItems","label":"Revenue Line Items","dependents":["Filter.1_table_filter_row_1","display_summaries_row_3"]}},"filters_def":{"Filter_1":{"operator":"AND","0":{"name":"sales_stage","table_key":"Opportunities:revenuelineitems","qualifier_name":"one_of","input_name0":["Prospecting","Qualification","Needs Analysis","Value Proposition","Id. Decision Makers","Perception Analysis","Proposal\/Price Quote","Negotiation\/Review"]}}}}',
            'Pipeline By Team By User' => '{"display_columns":[],"module":"Opportunities","group_defs":[{"name":"name","label":"Primary Team Name","table_key":"Opportunities:team_link","type":"name"},{"name":"user_name","label":"User Name","table_key":"Opportunities:assigned_user_link","type":"username"}],"summary_columns":[{"name":"name","label":"Team: Team Name","table_key":"Opportunities:team_link"},{"name":"user_name","label":"Assigned to User: User Name","table_key":"Opportunities:assigned_user_link"},{"name":"likely_case","label":"SUM: Likely","field_type":"currency","group_function":"sum","table_key":"Opportunities:revenuelineitems"}],"report_name":"Pipeline By Team By User","chart_type":"hBarF","do_round":1,"chart_description":"","numerical_chart_column":"Opportunities:revenuelineitems:likely_case:sum","numerical_chart_column_type":"currency","assigned_user_id":"1","report_type":"summary","full_table_list":{"self":{"value":"Opportunities","module":"Opportunities","label":"Opportunities","dependents":[]},"Opportunities:team_link":{"parent":"self","label":"Team","link_def":{"name":"team_link","relationship_name":"opportunities_team","bean_is_lhs":"","link_type":"one","label":"Team","table_key":"Opportunities:team_link"},"module":"Teams","name":"Opportunities  \u003E  Teams","dependents":["group_by_row_1","display_summaries_row_group_by_row_1"]},"Opportunities:assigned_user_link":{"parent":"self","label":"Assigned to User","link_def":{"name":"assigned_user_link","relationship_name":"opportunities_assigned_user","bean_is_lhs":"","link_type":"one","label":"Assigned to User","table_key":"Opportunities:assigned_user_link"},"module":"Users","name":"Opportunities  \u003E  Assigned to User","dependents":["group_by_row_2","display_summaries_row_group_by_row_2"]},"Opportunities:revenuelineitems":{"name":"Opportunities  \u003E  Revenue Line Items","parent":"self","link_def":{"name":"revenuelineitems","relationship_name":"opportunities_revenuelineitems","bean_is_lhs":true,"link_type":"many","label":"Revenue Line Items","module":"RevenueLineItems","table_key":"Opportunities:revenuelineitems"},"module":"RevenueLineItems","label":"Revenue Line Items","dependents":["Filter.1_table_filter_row_1","display_summaries_row_3"]}},"filters_def":{"Filter_1":{"operator":"AND","0":{"name":"sales_stage","table_key":"Opportunities:revenuelineitems","qualifier_name":"one_of","input_name0":["Prospecting","Qualification","Needs Analysis","Value Proposition","Id. Decision Makers","Perception Analysis","Proposal\/Price Quote","Negotiation\/Review"]}}}}',
            // @codingStandardsIgnoreEnd
        )
    );

    /**
     * Handle The Metadata Conversion
     *
     * @return mixed|void
     */
    public function doMetadataConvert()
    {
        // always runt he parent first, since we need to fix the vardefs before doing the viewdefs
        parent::doMetadataConvert();

        // fix the view defs now
        $this->fixRecordView(
            array(
                'commit_stage' => false,
                'sales_status' => true,
                'sales_stage' => false,
                'probability' => false
            )
        );

        $this->fixListViews(
            array(
                'sales_stage' => 'sales_status',
                'probability' => false,
                'commit_stage' => false,
            )
        );

        $this->fixFilter(
            array(
                'sales_stage' => false,
                'sales_status' => true,
                'probability' => false,
            )
        );
    }

    /**
     * Metadata Fixes for the Opportunity Module
     *
     * - Sets the dupe check to use `sales_status` instead of `sales_stage`
     * - Add a dependency extension that turns off the default oob dependencies
     */
    protected function fixOpportunityModule()
    {
        // lets make sure the dir is there
        SugarAutoLoader::ensureDir($this->moduleExtFolder . '/Vardefs');

        $file_contents = <<<EOL
<?php
\$dictionary['Opportunity']['fields']['revenuelineitems']['workflow'] = true;
\$dictionary['Opportunity']['duplicate_check']['FilterDuplicateCheck']['filter_template'][0]['\$and'][1] = array('sales_status' => array('\$not_equals' => 'Closed Lost'));
\$dictionary['Opportunity']['duplicate_check']['FilterDuplicateCheck']['filter_template'][0]['\$and'][2] = array('sales_status' => array('\$not_equals' => 'Closed Won'));
EOL;

        sugar_file_put_contents($this->moduleExtFolder . '/Vardefs/' . $this->dupeCheckExtFile, $file_contents);

        SugarAutoLoader::ensureDir($this->moduleExtFolder . '/Dependencies');


        // below is a ugly hack to update the `set_base_rate` dependency
        $file_contents = <<<EOL
<?php
if (isset(\$dependencies['Opportunities'])) {
    foreach(
        array('commit_stage_readonly_set_value','best_worst_sales_stage_read_only','likely_case_copy_when_closed')
        as \$dep
    ) {
        if (isset(\$dependencies['Opportunities'][\$dep])) {
            unset(\$dependencies['Opportunities'][\$dep]);
        }
    }

    // the `set_base_rate` dependency needs to use 'sales_status' here
    if (isset(\$dependencies['Opportunities']['set_base_rate'])) {
        \$dependencies['Opportunities']['set_base_rate']['triggerFields'] = array('sales_status');
        \$dependencies['Opportunities']['set_base_rate']['actions'][0]['params']['value'] =
            'ifElse(isForecastClosed(\$sales_status), \$base_rate, currencyRate(\$currency_id))';
    }
}
EOL;

        sugar_file_put_contents(
            $this->moduleExtFolder . '/Dependencies/' . $this->oppModuleDependencyFile,
            $file_contents
        );
    }

    /**
     * Enable and Show the RevenueLine Item Module
     *
     * - Add the file that shows the RLI Module
     * - Adds the `studio.php` file
     * - Puts  RLI module from the menu bar
     * - Adds the ACL Actions
     */
    protected function fixRevenueLineItemModule()
    {
        // lets make sure the dir is there
        SugarAutoLoader::ensureDir($this->rliModuleExtFolder . '/Vardefs');

        $file_contents = <<<EOL
<?php
\$dictionary['RevenueLineItem']['importable'] = true;
\$dictionary['RevenueLineItem']['unified_search'] = true;
EOL;

        sugar_file_put_contents($this->rliModuleExtFolder . '/Vardefs/' . $this->rliModuleExtVardefFile, $file_contents);

        // set the current loaded instance up
        if (isset($GLOBALS['dictionary']['RevenueLineItem'])) {
            $GLOBALS['dictionary']['RevenueLineItem']['importable'] = true;
            $GLOBALS['dictionary']['RevenueLineItem']['unified_search'] = true;
        }

        $this->cleanupUnifiedSearchCache();

        SugarAutoLoader::ensureDir($this->appExtFolder . '/Include');

        // we need to run the code we are putting in the custom file
        $GLOBALS['moduleList'][] = 'RevenueLineItems';
        if (isset($GLOBALS['modInvisList']) && is_array($GLOBALS['modInvisList'])) {
            foreach ($GLOBALS['modInvisList'] as $key => $mod) {
                if ($mod === 'RevenueLineItems') {
                    unset($GLOBALS['modInvisList'][$key]);
                }
            }
        }

        $file_contents = <<<EOL
<?php
\$moduleList[] = 'RevenueLineItems';
if (isset(\$modInvisList) && is_array(\$modInvisList)) {
    foreach (\$modInvisList as \$key => \$mod) {
        if (\$mod === 'RevenueLineItems') {
            unset(\$modInvisList[\$key]);
        }
    }
}
EOL;

        sugar_file_put_contents($this->appExtFolder . '/Include/' . $this->rliModuleExtFile, $file_contents);

        // enable the ACLs on RevenueLineItems
        ACLAction::addActions('RevenueLineItems');

        // show the rli module in WorkFlows
        $affected_modules = $this->toggleRevenueLineItemsLinkInWorkFlows(true);

        // show the rli module in the mega menu
        $this->setRevenueLineItemModuleTab(true);

        // handle the parent_type_field
        $this->setRevenueLineItemInParentRelateDropDown(true);

        // enable the item in the quick create
        $this->toggleRevenueLineItemQuickCreate(true);

        // place the studio file
        sugar_touch($this->rliStudioFile);

        $affected_modules[] = 'RevenueLineItems';

        return $affected_modules;
    }


    /**
     * Call this method to convert the data as well, this should be called after `doMetadataConvert`
     */
    public function doDataConvert()
    {
        $this->resetForecastData('RevenueLineItems');

        // fix the reports
        SugarAutoLoader::load('modules/Opportunities/include/OpportunityReports.php');
        $reports = new OpportunityReports();
        $reports->migrateToRevenueLineItems();

        $this->createRevenueLineItems();
    }

    /**
     * Create a notification for the current user informing them of mode switch completion.
     */
    protected function sendNotification()
    {
        $notification = BeanFactory::newBean('Notifications');
        $notification->assigned_user_id = $GLOBALS['current_user']->id;
        $notification->name = $GLOBALS['app_strings']['LBL_JOB_NOTIFICATION_OPPS_WITH_RLIS_SUBJECT'];
        $notification->description = $GLOBALS['app_strings']['LBL_JOB_NOTIFICATION_OPPS_WITH_RLIS_SUBJECT'];
        $notification->severity = 'success';
        $notification->save();
    }

    /**
     * Find all the Opportunities and Create RLI's for them, this will process the last 100 modified Opportunities
     * right away, and schedule the rest in chunks of 100 for the Scheduler to Take care of
     *
     * @throws SugarQueryException
     */
    protected function createRevenueLineItems()
    {
        // get the list of opps that are not deleted
        $sq = new SugarQuery();
        $sq->select(array('id'));
        $sq->from($this->bean);
        $sq->orderBy('date_modified', 'DESC');

        $opps = $sq->execute();

        if (empty($opps)) {
            $this->sendNotification();
            return false;
        }

        $bean_chunks = array_chunk($opps, 100);
        // process the first chunk
        $this->processOpportunityIds($bean_chunks[0]);

        $job_group = md5(microtime());

        if(count($bean_chunks) > 1) {
            // process any remaining in the background
            for ($x = 1; $x < count($bean_chunks); $x++) {
                $this->createRevenueLineItemJob($bean_chunks[$x], $job_group);
            }
        } else {
            $this->sendNotification();
        }
    }

    /**
     * Create a job for the Scheduler to create the RLI's for.
     *
     * @param array $data
     * @param string $job_group
     */
    protected function createRevenueLineItemJob(array $data, $job_group)
    {
        /* @var $job SchedulersJob */
        $job = BeanFactory::newBean('SchedulersJobs');
        $job->name = "Create RevenueLineItems for Opportunities";
        $job->target = "class::SugarJobCreateRevenueLineItems";
        $job->data = json_encode(array('data' => $data));
        $job->retry_count = 0;
        $job->assigned_user_id = $GLOBALS['current_user']->id;
        $job->job_group = $job_group;

        $jq = new SugarJobQueue();
        $jq->submitJob($job);
    }

    /**
     * Process the chunks of opportunities to create related Revenue Line Items
     *
     * - This always sets commit_stage to empty since forecasts is not setup any more.
     *
     * @param array $data
     */
    public static function processOpportunityIds(array $data)
    {
        Activity::disable();
        // disable the fts index as well
        $ftsSearch = \Sugarcrm\Sugarcrm\SearchEngine\SearchEngine::getInstance();
        $ftsSearch->setForceAsyncIndex(true);

        foreach ($data as $db_opp) {
            /* @var $opp Opportunity */
            $opp = BeanFactory::getBean('Opportunities', $db_opp['id']);
            if ($opp->id === $db_opp['id']) {
                /* @var $rli RevenueLineItem */
                $rli = BeanFactory::newBean('RevenueLineItems');
                $rli->update_modified_by = false;
                $rli->set_created_by = false;
                $rli->name = $opp->name;
                $rli->best_case = $opp->best_case;
                $rli->likely_case = $opp->amount;
                $rli->worst_case = $opp->worst_case;
                $rli->cost_price = $opp->amount;
                $rli->quantity = 1;
                $rli->currency_id = $opp->currency_id;
                $rli->base_rate = $opp->base_rate;
                $rli->probability = $opp->probability;
                $rli->date_closed = $opp->date_closed;
                $rli->date_closed_timestamp = $opp->date_closed_timestamp;
                $rli->assigned_user_id = $opp->assigned_user_id;
                $rli->modified_user_id = $opp->modified_user_id;
                $rli->modified_by_name = $opp->modified_by_name;
                $rli->created_by = $opp->created_by;
                $rli->created_by_name = $opp->created_by_name;
                $rli->account_id = $opp->account_id;
                $rli->commit_stage = '';
                $rli->sales_stage = $opp->sales_stage;
                $rli->deleted = $opp->deleted;
                $rli->team_id = $opp->team_id;
                $rli->team_set_id = $opp->team_set_id;
                $rli->acl_team_set_id = $opp->acl_team_set_id;
                $rli->save();

                // set the relationship up correctly
                $rli->load_relationship('opportunities');
                $rli->opportunities->add($opp->id);
            }
        }
        // set it back to the default value from the config.
        $ftsSearch->setForceAsyncIndex(
            SugarConfig::getInstance()->get('search_engine.force_async_index', false)
        );
        Activity::restoreToPreviousState();
    }

    /**
     * Update the field in the Products Module
     */
    protected function fixProductsModule()
    {
        $this->fixProductsModuleField('revenuelineitem_name', 'massupdate', true);
    }
}
