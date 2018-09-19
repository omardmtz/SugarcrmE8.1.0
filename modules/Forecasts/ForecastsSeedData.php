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
 * Handles populating seed data for Forecasts module
 */
class ForecastsSeedData
{

    /**
     * @static
     *
     * @param Array $timeperiods Array of $timeperiod instances to build forecast data for
     */
    public static function populateSeedData($timeperiods)
    {

        global $timedate, $current_user, $app_list_strings;

        $user = BeanFactory::newBean('Users');
        $comm = new Common();
        $commit_order = $comm->get_forecast_commit_order();

        // get what we are forecasting on
        /* @var $admin Administration */
        $admin = BeanFactory::newBean('Administration');
        $settings = $admin->getConfigForModule('Forecasts');

        $forecast_by = $settings['forecast_by'];

        foreach ($timeperiods as $timeperiod_id => $timeperiod) {

            foreach ($commit_order as $commit_type_array) {
                //direct entry per user, and some user will have a Rollup entry too.

                $ratio = array('.8', '1', '1.2', '1.4');
                $key = array_rand($ratio);

                if ($commit_type_array[1] === 'Direct') {
                    $commitUserId = $commit_type_array[0];
                    // get the worksheet total for a given user
                    /* @var $worksheet ForecastWorksheet */
                    $worksheet = BeanFactory::newBean('ForecastWorksheets');
                    $totals = $worksheet->worksheetTotals($timeperiod_id, $commitUserId, $forecast_by, true);

                    if ($totals['total_opp_count'] == 0) {
                        continue;
                    }

                    // output to the screen to keep the connection active
                    echo '.';

                    /* @var $quota Quota */
                    $quota = BeanFactory::newBean('Quotas');
                    $quota->timeperiod_id = $timeperiod_id;
                    $quota->user_id = $commitUserId;
                    $quota->quota_type = 'Direct';
                    $quota->currency_id = -99;

                    $quota->amount = SugarMath::init()->exp('?*?', array($totals['amount'], $ratio[$key]))->result();
                    $quota->amount_base_currency = $quota->amount;
                    $quota->committed = 1;
                    $quota->set_created_by = false;
                    if ($commitUserId === 'seed_sarah_id' ||
                        $commitUserId === 'seed_will_id' ||
                        $commitUserId === 'seed_jim_id'
                    ) {
                        $quota->created_by = 'seed_jim_id';
                    } else {
                        if ($commitUserId === 'seed_sally_id' || $commitUserId === 'seed_max_id') {
                            $quota->created_by = 'seed_sarah_id';
                        } else {
                            if ($commitUserId === 'seed_chris_id') {
                                $quota->created_by = 'seed_will_id';
                            } else {
                                $quota->created_by = $current_user->id;
                            }
                        }
                    }

                    $quota->save();

                    if (!$user->isManager($commitUserId)) {
                        /* @var $quotaRollup Quota */
                        $quotaRollup = BeanFactory::newBean('Quotas');
                        $quotaRollup->timeperiod_id = $timeperiod_id;
                        $quotaRollup->user_id = $commitUserId;
                        $quotaRollup->quota_type = 'Rollup';
                        $quota->currency_id = -99;
                        $quotaRollup->amount = $quota->amount;
                        $quotaRollup->amount_base_currency = $quotaRollup->amount;
                        $quotaRollup->committed = 1;
                        $quotaRollup->set_created_by = false;
                        if ($commitUserId === 'seed_sarah_id' ||
                            $commitUserId === 'seed_will_id' ||
                            $commitUserId === 'seed_jim_id'
                        ) {
                            $quotaRollup->created_by = 'seed_jim_id';
                        } else {
                            if ($commitUserId === 'seed_sally_id' || $commitUserId === 'seed_max_id') {
                                $quotaRollup->created_by = 'seed_sarah_id';
                            } else {
                                if ($commitUserId === 'seed_chris_id') {
                                    $quotaRollup->created_by = 'seed_will_id';
                                } else {
                                    $quotaRollup->created_by = $current_user->id;
                                }
                            }
                        }

                        $quotaRollup->save();
                    }

                    // create a previous forecast to simulate a change
                    /* @var $forecast Forecast */
                    $forecast = BeanFactory::newBean('Forecasts');
                    $forecast->timeperiod_id = $timeperiod_id;
                    $forecast->user_id = $commitUserId;
                    $forecast->opp_count = $totals['included_opp_count'];
                    if ($totals['included_opp_count'] > 0) {
                        $forecast->opp_weigh_value = SugarMath::init()->setScale(0)->exp(
                            '(?/?)/?',
                            array($totals['amount'], $ratio[$key], $totals['included_opp_count'])
                        )->result();
                    } else {
                        $forecast->opp_weigh_value = '0';
                    }
                    $forecast->best_case = SugarMath::init()->exp('(?+?)/?', array($totals['best_case'], $totals['won_best'], $ratio[$key]))->result();
                    $forecast->worst_case = SugarMath::init()->exp('(?+?)/?', array($totals['worst_case'], $totals['won_worst'], $ratio[$key]))->result();
                    $forecast->likely_case = SugarMath::init()->exp('(?+?)/?', array($totals['amount'], $totals['won_amount'], $ratio[$key]))->result();
                    $forecast->forecast_type = 'Direct';
                    $forecast->date_committed = $timedate->asDb($timedate->getNow()->modify("-1 day"));
                    $forecast->date_entered = $timedate->asDb($timedate->getNow()->modify("-1 day"));
                    $forecast->date_modified = $timedate->asDb($timedate->getNow()->modify("-1 day"));
                    $forecast->calculatePipelineData(
                        SugarMath::init()->exp('?/?', array($totals['includedClosedAmount'], $ratio[$key]))->result(),
                        $totals['includedClosedCount']
                    );
                    $forecast->save();

                    self::createManagerWorksheet($commitUserId, $forecast->toArray());

                    // create the current forecast
                    /* @var $forecast2 Forecast */
                    $forecast2 = BeanFactory::newBean('Forecasts');
                    $forecast2->timeperiod_id = $timeperiod_id;
                    $forecast2->user_id = $commitUserId;
                    $forecast2->opp_count = $totals['included_opp_count'];
                    if ($totals['included_opp_count'] > 0) {
                        $forecast2->opp_weigh_value = SugarMath::init()->setScale(0)->exp(
                            '?/?',
                            array($totals['amount'], $totals['included_opp_count'])
                        )->result();
                    } else {
                        $forecast2->opp_weigh_value = '0';
                    }
                    $forecast2->best_case = SugarMath::init($totals['best_case'])->add($totals['won_best'])->result();
                    $forecast2->worst_case = SugarMath::init($totals['worst_case'])->add($totals['won_worst'])->result();
                    $forecast2->likely_case = SugarMath::init($totals['amount'])->add($totals['won_amount'])->result();
                    $forecast2->forecast_type = 'Direct';
                    $forecast2->date_committed = $timedate->asDb($timedate->getNow());
                    $forecast2->calculatePipelineData(
                        $totals['includedClosedAmount'],
                        $totals['includedClosedCount']
                    );
                    $forecast2->save();

                    self::createManagerWorksheet($commitUserId, $forecast2->toArray());

                } else {

                    /* @var $mgr_worksheet ForecastManagerWorksheet */
                    $mgr_worksheet = BeanFactory::newBean('ForecastManagerWorksheets');
                    $totals = $mgr_worksheet->worksheetTotals($commit_type_array[0], $timeperiod_id, true);

                    if ($totals['included_opp_count'] == 0) {
                        continue;
                    }

                    // output to the screen to keep the connection active
                    echo '.';

                    /* @var $quota Quota */
                    $quota = BeanFactory::newBean('Quotas');
                    $quota->timeperiod_id = $timeperiod_id;
                    $quota->user_id = $commit_type_array[0];
                    $quota->quota_type = 'Rollup';
                    $quota->currency_id = -99;
                    $quota->amount = SugarMath::init($totals['quota'], 6)->mul($ratio[$key])->result();
                    $quota->amount_base_currency = $quota->amount;
                    $quota->committed = 1;
                    $quota->save();

                    /* @var $forecast Forecast */
                    $forecast = BeanFactory::newBean('Forecasts');
                    $forecast->timeperiod_id = $timeperiod_id;
                    $forecast->user_id = $commit_type_array[0];
                    $forecast->opp_count = $totals['included_opp_count'];
                    $forecast->opp_weigh_value = SugarMath::init()->setScale(0)->exp(
                        '?/?',
                        array($totals['likely_adjusted'], $totals['included_opp_count'])
                    )->result();
                    $forecast->likely_case = $totals['likely_adjusted'];
                    $forecast->best_case = $totals['best_adjusted'];
                    $forecast->worst_case = $totals['worst_adjusted'];
                    $forecast->forecast_type = 'Rollup';
                    $forecast->pipeline_opp_count = $totals['pipeline_opp_count'];
                    $forecast->pipeline_amount = $totals['pipeline_amount'];
                    $forecast->closed_amount = $totals['closed_amount'];
                    $forecast->date_entered = $timedate->asDb($timedate->getNow());
                    
                    $forecast->save();
                    self::createManagerWorksheet($commit_type_array[0], $forecast->toArray());

                }

                self::commitRepItems($commit_type_array[0], $timeperiod_id, $forecast_by);
            }

            // loop though all the managers and commit their forecast
            $managers = array(
                'seed_sarah_id',
                'seed_will_id',
                'seed_jim_id' // we do jim last since sarah and will will feed up into jim
            );

            $cid = $current_user->id;
            foreach ($managers as $manager) {
                /* @var $user User */
                $user = BeanFactory::getBean('Users', $manager);
                /* @var $worksheet ForecastManagerWorksheet */
                $worksheet = BeanFactory::newBean('ForecastManagerWorksheets');
                // set the current_user->id to the manager
                $current_user->id = $manager;
                $worksheet->commitManagerForecast($user, $timeperiod_id);
            }
            $current_user->id = $cid;
        }


        $admin = BeanFactory::newBean('Administration');
        $admin->saveSetting('Forecasts', 'is_setup', 1, 'base');

        // TODO-sfa - remove this once the ability to map buckets when they get changed is implemented (SFA-215).
        // this locks the forecasts ranges configs if the apps is installed with demo data and already has commits
        $admin->saveSetting('Forecasts', 'has_commits', 1, 'base');
    }

    protected static function createManagerWorksheet($user_id, $data)
    {
        /* @var $user User */
        $user = BeanFactory::getBean('Users', $user_id);
        /* @var $worksheet ForecastManagerWorksheet */
        $worksheet = BeanFactory::newBean('ForecastManagerWorksheets');
        if ($data["forecast_type"] == "Rollup") {
            $data["likely_adjusted"] = $data["likely_case"];
            $data["best_adjusted"] = $data["best_case"];
            $data["worst_adjusted"] = $data["worst_case"];
        }
        $worksheet->manager_saved = true;
        $worksheet->reporteeForecastRollUp($user, $data);
    }

    protected static function commitRepItems($user_id, $timeperiod, $forecast_by)
    {
        /* @var $tp TimePeriod */
        $tp = BeanFactory::getBean('TimePeriods', $timeperiod);

        $bean = BeanFactory::newBean($forecast_by);
        $sq = new SugarQuery();
        $sq->select(array('forecast_by.*'));
        $sq->from($bean, array('alias' => 'forecast_by'))->where()
            ->equals('forecast_by.assigned_user_id', $user_id)
            ->queryAnd()
            ->gte('forecast_by.date_closed_timestamp', $tp->start_date_timestamp)
            ->lte('forecast_by.date_closed_timestamp', $tp->end_date_timestamp);

        $link_name = ($forecast_by == 'RevenueLineItems') ? 'account_link' : 'accounts';
        $bean->load_relationship($link_name);
        $bean->$link_name->buildJoinSugarQuery($sq, array('joinTableAlias' => 'account'));
        $sq->select(array('account.id', 'account_id'));

        $beans = $sq->execute();

        unset($bean);

        foreach ($beans as $bean) {
            /* @var $obj Opportunity|Product */
            $obj = BeanFactory::newBean($forecast_by);
            $obj->loadFromRow($bean);

            /* @var $opp_wkst ForecastWorksheet */
            $opp_wkst = BeanFactory::newBean('ForecastWorksheets');
            if ($forecast_by == 'Opportunities') {
                $opp_wkst->saveRelatedOpportunity($obj, true);
            } else {
                $opp_wkst->saveRelatedProduct($obj, true);
            }
        }
    }
}
