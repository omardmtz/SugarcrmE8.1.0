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

class SugarForecasting_Progress_Manager extends SugarForecasting_Manager
{
    /**
     * @var Opportunity
     */
    protected $opportunity;

    /**
     * @var pipelineCount
     */
    protected $pipelineCount;

    /**
     * @var pipelineRevenue
     */
    protected $pipelineRevenue;

    /**
     * @var closedAmount
     */
    protected $closedAmount;

    /**
     * Class Constructor
     * @param array $args       Service Arguments
     */
    public function __construct($args)
    {
        parent::__construct($args);

        $this->loadConfigArgs();
    }

    /**
     * Get Settings from the Config Table.
     */
    public function loadConfigArgs() {
        /* @var $admin Administration */
        $admin = BeanFactory::newBean('Administration');
        $settings = $admin->getConfigForModule('Forecasts');

        // decode and json decode the settings from the administration to set the sales stages for closed won and closed lost
        $this->setArg('sales_stage_won', $settings["sales_stage_won"]);
        $this->setArg('sales_stage_lost', $settings["sales_stage_lost"]);
    }

    /**
     * Process the code to return the values that we need
     *
     * @return array
     */
    public function process()
    {
        return $this->getManagerProgress();
    }

    /**
     * Get the Numbers for the Manager View
     *
     * @return array
     */
    public function getManagerProgress()
    {
        $user_id = $this->getArg('user_id');
        $timeperiod_id = $this->getArg('timeperiod_id');
        $getTargetQuota = (bool)$this->getArg('target_quota');

        /* @var $mgr_worksheet ForecastManagerWorksheet */
        $mgr_worksheet = BeanFactory::newBean('ForecastManagerWorksheets');
        $totals = $mgr_worksheet->worksheetTotals($user_id, $timeperiod_id);

        // pull the quota from the worksheet data since we need the draft records if they exist
        // to show what could be in draft for the user, if they are the current user.
        $totals['quota_amount'] = $totals['quota'];

        // add the target quota to the return data if passed target_quota=true as a param
        if($getTargetQuota) {
            /* @var $quotaBean Quota */
            $quotaBean = BeanFactory::newBean('Quotas');
            $quota = $quotaBean->getRollupQuota($timeperiod_id, $user_id, true);
            $totals['target_quota_amount'] = $quota['amount'];
        }

        // we should send back the adjusted totals with out the closed_amount included.
        foreach (array('worst_adjusted', 'likely_adjusted', 'best_adjusted') as $field) {
            $totals[$field] = SugarMath::init($totals[$field])->sub($totals['closed_amount'])->result();
        }

        $totals['user_id'] = $user_id;
        $totals['timeperiod_id'] = $timeperiod_id;
        // unset some vars that come from the worksheet to avoid confusion with correct data
        // coming from this endpoint for progress
        unset($totals['pipeline_opp_count'], $totals['quota'],
            $totals['included_opp_count'], $totals['pipeline_amount']);

        return $totals;
    }

    /**
     * utilizes some of the functions from the base manager class to load data and sum the quota figures
     * @return float
     */
    public function getQuotaTotalFromData()
    {
        //getting quotas from quotas table
        /* @var $db DBManager */
        $db = DBManagerFactory::getInstance();
        $quota_query = "SELECT sum(q.amount/q.base_rate) quota
                        FROM quotas q
                        INNER JOIN users u
                        ON q.user_id = u.id
                        WHERE u.deleted = 0 AND u.status = 'Active'
                            AND q.timeperiod_id = '{$this->getArg('timeperiod_id')}'
                            AND ((u.id = '{$this->getArg('user_id')}' and q.quota_type = 'Direct')
                            OR (u.reports_to_id = '{$this->getArg('user_id')}' and q.quota_type = 'Rollup'))
                            AND q.deleted = 0";

        $row = $db->fetchOne($quota_query);
        return $row['quota'];
    }    

    /**
     * retrieves the amount of opportunities with count less the closed won/lost stages
     *
     */
    public function getPipelineRevenue()
    {

        $db = DBManagerFactory::getInstance();
        $amountSum = 0;
        $query = "";

        $user_id = $this->getArg('user_id');
        $timeperiod_id = $this->getArg('timeperiod_id');
        $excluded_sales_stages_won = $this->getArg('sales_stage_won');
        $excluded_sales_stages_lost = $this->getArg('sales_stage_lost');
        $repIds = User::getReporteeReps($user_id);
        $mgrIds = User::getReporteeManagers($user_id);
        $arrayLen = 0;
        $admin = BeanFactory::newBean('Administration');
        $settings = $admin->getConfigForModule('Forecasts');
        
        $tableName = strtolower($settings['forecast_by']);
        $tableName = $tableName == 'revenuelineitems' ? 'revenue_line_items' : $tableName;
        $amountColumn = $tableName == 'revenue_line_items' ? 'likely_case' : 'amount';

        //Note: this will all change in sugar7 to the filter API
        //set up outer part of the query
        $query = "select sum(amount) as amount, sum(recordcount) as recordcount, sum(closed) as closed from(";
        
        $queryMgrOpps = "";
        //only commiteed manager
        $subQuery = "(select (pipeline_amount / base_rate) as amount, " .
                                 "pipeline_opp_count as recordcount, " .
                                 "(closed_amount / base_rate) as closed from forecasts " .
                         "where timeperiod_id = {$db->quoted($timeperiod_id)} " .
                            "and user_id = {$db->quoted($user_id)} " .
                            "and forecast_type = 'Direct' " .
                         "order by date_entered desc ";
        $queryMgrOpps .= $db->limitQuery($subQuery, 0, 1, false, "", false);
        $queryMgrOpps .= ") ";
        
        //only committed direct reportee (manager) opps
        $queryRepOpps = "";
        $arrayLen = count($mgrIds);
        for($index = 0; $index < $arrayLen; $index++) {
            $subQuery = "(select (pipeline_amount / base_rate) as amount, " .
                                 "pipeline_opp_count as recordcount, " .
                                 "(closed_amount / base_rate) as closed from forecasts " .
                         "where timeperiod_id = {$db->quoted($timeperiod_id)} " .
                            "and user_id = {$db->quoted($mgrIds[$index])} " .
                            "and forecast_type = 'Rollup' " .
                         "order by date_entered desc ";
            $queryRepOpps .= $db->limitQuery($subQuery, 0, 1, false, "", false);
            $queryRepOpps .= ") ";
            if ($index+1 != $arrayLen) {
                $queryRepOpps .= "union all ";
            }
        }
        
        $arrayLen = count($repIds);
        
        //if we've started adding queries, we need a union to pick up the rest if we have more to add
        if ($queryRepOpps != "" && $arrayLen > 0) {
            $queryRepOpps .= " union all ";
        }
        //only committed direct reportee (manager) opps
        for($index = 0; $index < $arrayLen; $index++) {
            $subQuery = "(select (pipeline_amount / base_rate) as amount, " .
                                 "pipeline_opp_count as recordcount, " .
                                 "(closed_amount / base_rate) as closed from forecasts " .
                         "where timeperiod_id = {$db->quoted($timeperiod_id)} " .
                            "and user_id = {$db->quoted($repIds[$index])} " .
                            "and forecast_type = 'Direct' " .
                         "order by date_entered desc ";
            $queryRepOpps .= $db->limitQuery($subQuery, 0, 1, false, "", false);
            $queryRepOpps .= ") ";
            if ($index+1 != $arrayLen) {
                $queryRepOpps .= "union all ";
            }
        }
         
        //Union the two together if we have two separate queries
        $query .= $queryMgrOpps;
        if ($queryRepOpps != "") {
            $query .= " union all " . $queryRepOpps;
        }
        
        //finally, finish up the outer query
        $query .= ") sums";
        
        $result = $db->query($query);
        $row = $db->fetchByAssoc($result);
        $this->closedAmount = is_numeric($row["closed"]) ? $row["closed"] : 0;
    }
}
