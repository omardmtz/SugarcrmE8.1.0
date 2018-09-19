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

class SugarForecasting_Committed extends SugarForecasting_AbstractForecast implements SugarForecasting_ForecastSaveInterface
{
    /**
     * No longer used but the class parent implements SugarForecasting_ForecastProcessInterface
     *
     * @return array|string
     */
    public function process()
    {
        return array_values($this->dataArray);
    }

    /**
     * Save any committed values
     *
     * @return array|mixed
     */
    public function save()
    {
        global $current_user;

        $args = $this->getArgs();
        $db = DBManagerFactory::getInstance();

        if (!isset($args['timeperiod_id']) || empty($args['timeperiod_id'])) {
            $args['timeperiod_id'] = TimePeriod::getCurrentId();
        }

        $commit_type = strtolower($this->getArg('commit_type'));

        /* @var $mgr_worksheet ForecastManagerWorksheet */
        $mgr_worksheet = BeanFactory::newBean('ForecastManagerWorksheets');
        /* @var $worksheet ForecastWorksheet */
        $worksheet = BeanFactory::newBean('ForecastWorksheets');

        $field_ext = '_case';

        if ($commit_type == "manager") {
            $worksheet_totals = $mgr_worksheet->worksheetTotals($current_user->id, $args['timeperiod_id']);
            // we don't need the *_case values so lets make them the same as the *_adjusted values
            $field_ext = '_adjusted';
        } else {
            $worksheet_totals = $worksheet->worksheetTotals($args['timeperiod_id'], $current_user->id);
            // set likely
            $worksheet_totals['likely_case'] = SugarMath::init($worksheet_totals['amount'], 6)
                    ->add($worksheet_totals['includedClosedAmount'])->result();
            $worksheet_totals['best_case'] = SugarMath::init($worksheet_totals['best_case'], 6)
                    ->add($worksheet_totals['includedClosedBest'])->result();
            $worksheet_totals['worst_case'] = SugarMath::init($worksheet_totals['worst_case'], 6)
                    ->add($worksheet_totals['includedClosedWorst'])->result();
        }
        
        /* @var $forecast Forecast */
        $forecast = BeanFactory::newBean('Forecasts');
        $forecast->user_id = $current_user->id;
        $forecast->timeperiod_id = $args['timeperiod_id'];
        $forecast->best_case = $worksheet_totals['best' . $field_ext];
        $forecast->likely_case = $worksheet_totals['likely' . $field_ext];
        $forecast->worst_case = $worksheet_totals['worst' . $field_ext];
        $forecast->forecast_type = $args['forecast_type'];
        $forecast->opp_count = $worksheet_totals['included_opp_count'];
        $forecast->currency_id = '-99';
        $forecast->base_rate = '1';
        
        //If we are committing a rep forecast, calculate things.  Otherwise, for a manager, just use what is passed in.
        if ($args['commit_type'] == 'sales_rep') {
            $forecast->calculatePipelineData(
                $worksheet_totals['includedClosedAmount'],
                $worksheet_totals['includedClosedCount']
            );
            //push the pipeline numbers back into the args
            $args['pipeline_opp_count'] = $forecast->pipeline_opp_count;
            $args['pipeline_amount'] = $forecast->pipeline_amount;
            $worksheet_totals['closed_amount'] = $forecast->closed_amount;
        } else {
            $forecast->pipeline_opp_count = $worksheet_totals['pipeline_opp_count'];
            $forecast->pipeline_amount = $worksheet_totals['pipeline_amount'];
            $forecast->closed_amount = $worksheet_totals['closed_amount'];
        }
       
        if ($worksheet_totals['likely_case'] != 0 && $worksheet_totals['included_opp_count'] != 0) {
            $forecast->opp_weigh_value = $worksheet_totals['likely_case'] / $worksheet_totals['included_opp_count'];
        }
        $forecast->save();

        // roll up the committed forecast to that person manager view
        // copy the object so we can set some needed values
        $mgr_rollup_data = $worksheet_totals;
        $mgr_rollup_data['forecast_type'] = $args['forecast_type'];
        // pass same timeperiod as the other data to the manager's rollup
        $mgr_rollup_data['timeperiod_id'] = $args['timeperiod_id'];

        $mgr_worksheet->reporteeForecastRollUp($current_user, $mgr_rollup_data);

        if ($this->getArg('commit_type') == "sales_rep") {
            $worksheet->commitWorksheet($current_user->id, $args['timeperiod_id']);
        } elseif ($this->getArg('commit_type') == "manager") {
            $mgr_worksheet->commitManagerForecast($current_user, $args['timeperiod_id']);
        }

        //TODO-sfa remove this once the ability to map buckets when they get changed is implemented (SFA-215).
        $admin = BeanFactory::newBean('Administration');
        $settings = $admin->getConfigForModule('Forecasts');
        if (!isset($settings['has_commits']) || !$settings['has_commits']) {
            $admin->saveSetting('Forecasts', 'has_commits', true, 'base');
            MetaDataManager::refreshModulesCache(array('Forecasts'));
        }

        $forecast->date_entered = $this->convertDateTimeToISO($db->fromConvert($forecast->date_entered, 'datetime'));
        $forecast->date_modified = $this->convertDateTimeToISO($db->fromConvert($forecast->date_modified, 'datetime'));

        return $worksheet_totals;
    }
}
