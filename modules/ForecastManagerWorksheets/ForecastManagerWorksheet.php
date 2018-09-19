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


class ForecastManagerWorksheet extends SugarBean
{
    public $args;
    public $user_id;
    public $version;
    public $id;
    public $assigned_user_id;
    public $currency_id;
    public $base_rate;
    public $name;
    public $best_case;
    public $likely_case;
    public $worst_case;
    public $timeperiod_id;
    public $quota_id;
    public $commit_stage;
    public $quota;
    public $best_case_adjusted;
    public $likely_case_adjusted;
    public $worst_case_adjusted;
    public $show_history_log = 0;
    public $draft = 0;
    public $date_modified;
    public $manager_saved = false;
    public $object_name = 'ForecastManagerWorksheet';
    public $module_name = 'ForecastManagerWorksheets';
    public $module_dir = 'ForecastManagerWorksheets';
    public $table_name = 'forecast_manager_worksheets';
    public $disable_custom_fields = true;
    public $is_manager = false;
    public $draft_save_type = '';

    /**
     * Commit a manager forecast from the draft records
     *
     * @param User $manager
     * @param string $timeperiod
     * @return bool
     */
    public function commitManagerForecast(User $manager, $timeperiod)
    {
        global $current_user;

        // make sure that the User passed in is actually a manager
        if (!$this->isUserManager($manager->id)) {
            return false;
        }

        /* @var $db DBManager */
        $db = DBManagerFactory::getInstance();

        $sql = 'SELECT name, assigned_user_id, team_id, team_set_id, quota, best_case, best_case_adjusted,
                likely_case, likely_case_adjusted, worst_case, worst_case_adjusted, currency_id, base_rate,
                timeperiod_id, user_id, opp_count, pipeline_opp_count, pipeline_amount, closed_amount ' .
            'FROM ' . $this->table_name . ' ' .
            'WHERE assigned_user_id = ' . $db->quoted($manager->id) . ' AND user_id != ' . $db->quoted($manager->id) . ' ' .
            'AND timeperiod_id = ' . $db->quoted($timeperiod) . ' AND draft = 1 AND deleted = 0 ' .
            'UNION ALL ' .
            'SELECT name, assigned_user_id, team_id, team_set_id, quota, best_case, best_case_adjusted,
                    likely_case, likely_case_adjusted, worst_case, worst_case_adjusted, currency_id, base_rate,
                    timeperiod_id, user_id, opp_count, pipeline_opp_count, pipeline_amount, closed_amount ' .
            'FROM ' . $this->table_name . ' ' .
            'WHERE assigned_user_id = ' . $db->quoted($manager->id) . ' AND user_id = ' . $db->quoted($manager->id) . ' ' .
            'AND timeperiod_id = ' . $db->quoted($timeperiod) . ' AND draft = 1 AND deleted = 0';


        $results = $db->query($sql);

        $this->fixTopLevelManagerQuotaRollup($manager->id, $timeperiod);

        while ($row = $db->fetchByAssoc($results)) {
            /* @var $worksheet ForecastManagerWorksheet */
            $worksheet = BeanFactory::newBean('ForecastManagerWorksheets');

            $worksheet->retrieve_by_string_fields(
                array(
                    'user_id' => $row['user_id'],
                    // user id comes from the user model
                    'assigned_user_id' => $row['assigned_user_id'],
                    // the assigned user of the row is who the user reports to
                    'timeperiod_id' => $row['timeperiod_id'],
                    // the current timeperiod
                    'draft' => 0,
                    // we want to update the committed row
                    'deleted' => 0,
                )
            );
            foreach ($row as $key => $value) {
                $worksheet->$key = $value;
            }
            $worksheet->draft = 0; // make sure this is always 0!
            $worksheet->save();

            $type = ($worksheet->user_id == $current_user->id) ? 'Direct' : 'Rollup';
            $disableAS = ($worksheet->user_id == $current_user->id);

            $this->_assignQuota($worksheet->quota, $type, $worksheet->user_id, $timeperiod, $disableAS);
        }

        return true;
    }

    /**
     * Commit a quota to the quotas table
     *
     * @param string $quota_amount
     * @param string $user_id
     * @param string $timeperiod_id
     * @return Quota The Quota Bean
     */
    protected function commitQuota($quota_amount, $user_id, $timeperiod_id, $quota_type)
    {
        $quota = $this->getQuota($user_id, $timeperiod_id, $quota_type);

        // set all the values just to make sure
        $quota->timeperiod_id = $timeperiod_id;
        $quota->user_id = $user_id;
        $quota->committed = 1;
        $quota->quota_type = $quota_type;
        $quota->amount = $quota_amount;
        $quota->save();

        return $quota;
    }

    /**
     * Fetch the Quota from the DB.
     *
     * @param string $user_id
     * @param string $timeperiod_id
     * @param string $quota_type
     * @return Quota
     */
    protected function getQuota($user_id, $timeperiod_id, $quota_type) {
        /* @var $quota Quota */
        $quota = $this->getBean('Quotas');
        $quota->retrieve_by_string_fields(
            array(
                'timeperiod_id' => $timeperiod_id,
                'user_id' => $user_id,
                'committed' => 1,
                'quota_type' => $quota_type,
                'deleted' => 0
            )
        );

        return $quota;
    }


    /**
     * Roll up the data from the rep-worksheets to the manager worksheets
     *
     * @param User $reportee
     * @param $data
     * @return boolean
     */
    public function reporteeForecastRollUp(User $reportee, $data)
    {

        if (!isset($data['timeperiod_id']) || !is_guid($data['timeperiod_id'])) {
            $data['timeperiod_id'] = TimePeriod::getCurrentId();
        }

        // handle top level managers
        $reports_to = $reportee->reports_to_id;
        if (empty($reports_to)) {
            $reports_to = $reportee->id;
        }

        if (isset($data['forecast_type'])) {
            // check forecast type to see if the assigned_user_id should be equal to the $reportee as it's their own
            // rep worksheet
            if ($data['forecast_type'] == "Direct" && $this->isUserManager($reportee->id)) {
                // this is the manager committing their own data, the $reports_to should be them
                // and not their actual manager
                $reports_to = $reportee->id;
            } else {
                if ($data['forecast_type'] == "Rollup" && $reports_to == $reportee->id) {
                    // if type is rollup and reports_to is equal to the $reportee->id (aka no top level manager),
                    // we don't want to update their draft record so just ignore this,
                    return false;
                }
            }
        }

        if (isset($data['draft']) && $data['draft'] == '1' && $GLOBALS['current_user']->id == $reportee->id) {
            // this data is for the current user, but is not a commit so we need to update their own draft record
            $reports_to = $reportee->id;
        }

        $this->retrieve_by_string_fields(
            array(
                'user_id' => $reportee->id, // user id comes from the user model
                'assigned_user_id' => $reports_to, // the assigned user of the row is who the user reports to
                'timeperiod_id' => $data['timeperiod_id'], // the current timeperiod
                'draft' => 1, // we only ever update the draft row
                'deleted' => 0,
            )
        );

        $copyMap = array(
            'currency_id',
            'base_rate',
            'timeperiod_id',
            'opp_count',
            'pipeline_opp_count',
            'pipeline_amount',
            'closed_amount'
        );

        if ($data["forecast_type"] == "Direct") {
            $copyMap[] = "likely_case";
            $copyMap[] = "best_case";
            $copyMap[] = "worst_case";
        } else if ($data["forecast_type"] == "Rollup") {
            $copyMap[] = array("likely_case" => "likely_adjusted");
            $copyMap[] = array("best_case" => "best_adjusted");
            $copyMap[] = array("worst_case" => "worst_adjusted");
        }

        if (empty($this->id) || $this->manager_saved == false) {
            if ($data["forecast_type"] == "Rollup") {
                $copyMap[] = array('likely_case_adjusted' => 'likely_adjusted');
                $copyMap[] = array('best_case_adjusted' => 'best_adjusted');
                $copyMap[] = array('worst_case_adjusted' => 'worst_adjusted');
            } elseif ($data["forecast_type"] == "Direct") {
                $copyMap[] = array('likely_case_adjusted' => 'likely_case');
                $copyMap[] = array('best_case_adjusted' => 'best_case');
                $copyMap[] = array('worst_case_adjusted' => 'worst_case');
            }
        }
        if (empty($this->id)) {
            if (!isset($data['quota']) || empty($data['quota'])) {
                // we need to get a fresh bean to store the quota if one exists
                $quotaSeed = BeanFactory::newBean('Quotas');

                // check if we need to get the roll up amount
                $getRollupQuota = ($this->isUserManager($reportee->id)
                    && isset($data['forecast_type'])
                    && $data['forecast_type'] == 'Rollup');

                $quota = $quotaSeed->getRollupQuota($data['timeperiod_id'], $reportee->id, $getRollupQuota);
                $data['quota'] = $quota['amount'];
            }
            $copyMap[] = "quota";
        }

        $this->copyValues($copyMap, $data);

        // set the team to the default ones from the passed in user
        $this->team_set_id = $reportee->team_set_id;
        $this->team_id = $reportee->team_id;

        $this->name = $reportee->full_name;
        $this->user_id = $reportee->id;
        $this->assigned_user_id = $reports_to;
        $this->draft = 1;

        $this->save();

        // roll up the draft value for best/likely/worst case values to the committed record if one exists
        $this->rollupDraftToCommittedWorksheet($this);

        return true;
    }

    /**
     * @param ForecastManagerWorksheet $worksheet   The Draft Worksheet
     * @param array $copyMap                        What we want to copy, if left empty it will default to
     *                                              worst, likely and best case fields
     * @return bool
     */
    protected function rollupDraftToCommittedWorksheet(ForecastManagerWorksheet $worksheet, $copyMap = array())
    {
        /* @var $committed_worksheet ForecastManagerWorksheet */
        $committed_worksheet = $this->getBean($this->module_name);
        $committed_worksheet->retrieve_by_string_fields(
            array(
                'user_id' => $worksheet->user_id, // user id comes from the user model
                'assigned_user_id' => $worksheet->assigned_user_id,
                'timeperiod_id' => $worksheet->timeperiod_id, // the current timeperiod
                'draft' => 0,
                'deleted' => 0,
            )
        );

        if (empty($committed_worksheet->id)) {
            return false;
        }

        // if we don't pass in a copy map, just use the default
        if (empty($copyMap)) {
            $copyMap = array(
                'likely_case',
                'best_case',
                'worst_case',
            );
        }

        $this->copyValues($copyMap, $worksheet->toArray(), $committed_worksheet);

        $committed_worksheet->update_date_modified = false;

        $committed_worksheet->save();

        return true;
    }

    /**
     * Copy the fields from the $seed bean to the worksheet object
     *
     * @param array $fields
     * @param array $seed
     * @param ForecastManagerWorksheet $bean        Optional, If not set, it will be set to the current object
     */
    protected function copyValues($fields, array $seed, ForecastManagerWorksheet &$bean = null)
    {
        if (is_null($bean)) {
            $bean = $this;
        }

        foreach ($fields as $field) {
            $key = $field;
            if (is_array($field)) {
                // if we have an array it should be a key value pair, where the key is the destination
                // value and the value, is the seed value
                list($key, $field) = each($field);
            }
            // make sure the field is set, as not to cause a notice since a field might get unset() from the $seed class
            if (isset($seed[$field])) {
                $bean->$key = $seed[$field];
            }
        }
    }

    /**
     * Assign the Quota out to the reporting users for the given user if they are a manager
     *
     * @param string $user_id           The User for which we want to look for reportee's for
     * @param string $timeperiod_id     Which timeperiod
     * @return bool
     */
    public function assignQuota($user_id, $timeperiod_id)
    {
        if ($this->isUserManager($user_id) === false) {
            return false;
        }

        // get everyone but the current user
        $sq = $this->getSugarQuery();
        $sq->select(array('id', 'quota', 'user_id'));
        $sq->from($this);
        $sq->where()
            ->equals('assigned_user_id', $user_id)
            ->notEquals('user_id', $user_id)
            ->equals('timeperiod_id', $timeperiod_id)
            ->equals('draft', 1);

        // now just get the current user
        $sq2 = $this->getSugarQuery();
        $sq2->select(array('id', 'quota', 'user_id'));
        $sq2->from($this);
        $sq2->where()
            ->equals('assigned_user_id', $user_id)
            ->Equals('user_id', $user_id)
            ->equals('timeperiod_id', $timeperiod_id)
            ->equals('draft', 1);

        // create the union query now
        $sqU =$this->getSugarQuery();
        $sqU->union($sq)->addQuery($sq2);

        // run the query
        $worksheets = $sqU->execute();

        $this->fixTopLevelManagerQuotaRollup($user_id, $timeperiod_id);

        foreach ($worksheets as $worksheet) {
            $type = ($worksheet['user_id'] == $user_id) ? 'Direct' : 'Rollup';
            $disableAS = ($worksheet['user_id'] == $user_id);

            // now lets actually assign the quota
            $this->_assignQuota($worksheet['quota'], $type, $worksheet['user_id'], $timeperiod_id, $disableAS);

            /* @var $worksheet_obj ForecastManagerWorksheet */
            $worksheet_obj = $this->getBean($this->module_name, $worksheet['id']);
            $this->rollupDraftToCommittedWorksheet($worksheet_obj, array('quota'));
        }

        return true;
    }

    /**
     * Actually do the work of assigning a quota from the worksheets
     *
     * @param string $quota The amount of the quota
     * @param string $type What type is the quota
     * @param string $user_id Who is the quota for
     * @param string $timeperiod_id What timeperiod is the quota for
     * @param bool $disableActivityStream Should we disable activity streams and create our own entry
     */
    protected function _assignQuota($quota, $type, $user_id, $timeperiod_id, $disableActivityStream = false)
    {
        if ($disableActivityStream) {
            Activity::disable();
            $current_quota = $this->getQuota($user_id, $timeperiod_id, $type);
        }

        // get the updated quota back, this is needed because current_quota might be empty
        // as it could very well not exist yet.
        $quota = $this->commitQuota(
            $quota,
            $user_id,
            $timeperiod_id,
            $type
        );

        $new_quota = $this->recalcQuotas($user_id, $timeperiod_id, true);

        if ($disableActivityStream) {
            Activity::restoreToPreviousState();

            if ($new_quota !== $current_quota->amount) {
                $args = array(
                    'isUpdate' => !empty($current_quota->amount),
                    'dataChanges' => array(
                        'amount' => array(
                            'field_name' => 'amount',
                            'field_type' => 'currency',
                            'before' => $current_quota->amount,
                            'after' => $new_quota
                        )
                    )
                );

                // Manually Create the Activity Stream Entry!
                $aqm = $this->getActivityQueueManager();
                $aqm->eventDispatcher($quota, 'after_save', $args);
            }
        }
    }

    /**
     * If the user is the top level manager, set their rollup quota value correctly
     *
     * @param string $user_id
     * @param string $timeperiod_id
     * @throws SugarQueryException
     */
    protected function fixTopLevelManagerQuotaRollup($user_id, $timeperiod_id)
    {
        if ($this->isTopLevelManager($user_id)) {

            $sq = $this->getSugarQuery();
            $sq->select(array())
                ->fieldRaw('sum(quota * base_rate)', 'quota');
            $sq->from($this);
            $sq->where()
                ->equals('assigned_user_id', $user_id)
                ->equals('timeperiod_id', $timeperiod_id)
                ->equals('draft', 1);

            $quota = $sq->getOne();

            $this->commitQuota(
                $quota,
                $user_id,
                $timeperiod_id,
                'Rollup'
            );
        }
    }

    /**
     * Does the user_id belong to a top level manager
     *
     * @param string $user_id
     * @return bool
     */
    protected function isTopLevelManager($user_id)
    {
        return User::isTopLevelManager($user_id);
    }

    /**
     * Save Worksheet
     *
     * @deprecated
     *
     * This is no longer used in 7.8, so it can safely be deprecated
     *
     * @param bool $check_notify
     */
    public function saveWorksheet($check_notify = false)
    {
        $GLOBALS['log']->deprecated('Opportunity::saveWorksheet() has been deprecated in 7.8');
        $this->is_manager = $this->isUserManager($this->user_id);

        // save to the manager worksheet table (new table)
        // get the user object
        /* @var $userObj User */
        $userObj = $this->getBean('Users', $this->user_id);
        /* @var $mgr_worksheet ForecastManagerWorksheet */
        $mgr_worksheet = $this->getBean('ForecastManagerWorksheets');
        $mgr_worksheet->reporteeForecastRollUp($userObj, $this->args);


    }

    /**
     * Override save so we always set the currency_id and base_rate to the default system currency
     *
     * @override
     * @param bool $check_notify
     * @return String
     */
    public function save($check_notify = false)
    {
        // make sure the currency and base_rate are always set to the base currency for the manager worksheets
        $currency = SugarCurrency::getBaseCurrency();
        $this->currency_id = $currency->id;
        $this->base_rate = $currency->conversion_rate;

        return parent::save($check_notify);
    }

    /**
     * @throws SugarQueryException
     */
    public function fill_in_additional_detail_fields()
    {
        parent::fill_in_additional_detail_fields();
        // see if the value should show the commitLog Button
        $sq = new SugarQuery();
        $sq->select('date_modified');
        $sq->from(BeanFactory::newBean($this->module_name))->where()
            ->equals('assigned_user_id', $this->assigned_user_id)
            ->equals('user_id', $this->user_id)
            ->equals('draft', 0)
            ->equals('timeperiod_id', $this->timeperiod_id);
        $beans = $sq->execute();

        if (empty($beans) && empty($this->date_modified)) {
            $this->show_history_log = 0;
        } else {
            if (empty($beans) && !empty($this->date_modified)) {
                // When reportee has committed but manager has not
                $this->show_history_log = 1;
            } else {
                $bean = $beans[0];
                $committed_date = $this->db->fromConvert($bean["date_modified"], "datetime");

                if (strtotime($committed_date) < strtotime($this->date_modified)) {

                    // find the differences via the audit table
                    // we use a direct query since SugarQuery can't do the audit tables...
                    $sql = sprintf(
                        "SELECT field_name, before_value_string, after_value_string FROM %s
                        WHERE parent_id = %s AND date_created >= " . $this->db->convert('%s', 'datetime'),
                        $this->get_audit_table_name(),
                        $this->db->quoted($this->id),
                        $this->db->quoted($this->fetched_row['date_modified'])
                    );

                    $results = $this->db->query($sql);

                    // get the setting for which fields to compare on
                    /* @var $admin Administration */
                    $admin = BeanFactory::newBean('Administration');
                    $settings = $admin->getConfigForModule('Forecasts', 'base');

                    while ($row = $this->db->fetchByAssoc($results)) {
                        $field = substr($row['field_name'], 0, strpos($row['field_name'], '_'));
                        if ($settings['show_worksheet_' . $field] == "1") {
                            // calculate the difference to make sure it actually changed at 2 digits vs changed at 6 digits
                            $diff = SugarMath::init($row['after_value_string'], 6)->sub(
                                $row['before_value_string']
                            )->result();
                            // due to decimal rounding on the front end, we only want to know about differences greater
                            // of two decimal places.
                            // todo-sfa: This hardcoded 0.01 value needs to be changed to a value determined by userprefs
                            if (abs($diff) >= 0.01) {
                                $this->show_history_log = 1;
                                break;
                            }
                        }
                    }
                }
            }
        }
        if (!empty($this->user_id)) {
            $this->is_manager = User::isManager($this->user_id);
        }
    }

    /**
     * Sets Worksheet args so that we save the supporting tables.
     *
     * @param array $args Arguments passed to save method through PUT
     */
    public function setWorksheetArgs($args)
    {
        // save the args
        $this->args = $args;

        // loop though the args and assign them to the corresponding key on the object
        foreach ($args as $arg_key => $arg) {
            $this->$arg_key = $arg;
        }
    }

    /**
     * Gets a sum of the passed in user's reportees quotas for a specific timeperiod
     *
     * @param string $userId The userID for which you want a reportee quota sum.
     * @param string $timeperiodId      the timeperiod to use
     * @return int Sum of quota amounts.
     */
    protected function getQuotaSum($userId, $timeperiodId)
    {
        $sql = "SELECT sum(q.amount) amount " .
            "FROM quotas q " .
            "INNER JOIN users u ON u.reports_to_id = " . $this->db->quoted($userId) . " " .
            "AND q.user_id = u.id " .
            "AND q.timeperiod_id = " . $this->db->quoted($timeperiodId) . " " .
            "AND q.quota_type = 'Rollup'";
        $amount = 0;

        $result = $this->db->query($sql);
        while ($row = $this->db->fetchByAssoc($result)) {
            $amount = $row['amount'];
        }

        return (empty($amount)) ? 0 : $amount;
    }

    /**
     * Gets the passed in user's committed quota value and direct quota ID
     *
     * @param string userId User id to query for
     * @param string $timeperiodId      the timeperiod to use
     * @return array id, Quota value
     */
    protected function getManagerQuota($userId, $timeperiodId)
    {
        /*
         * This info is in two rows, and either of them might not exist.  The union
         * is here to make sure data is returned if one or the other exists.  This statement
         * lets us grab both bits with one call to the db rather than two separate smaller
         * calls.
         *
         * We are looking for the ID of the quota where quota_type = Direct
         * and the AMOUNT of the quota where quota_type = Rollup
         */
        $sql = "SELECT q1.amount, q2.id FROM quotas q1 " .
            "left outer join quotas q2 " .
            "on q1.user_id = q2.user_id " .
            "and q1.timeperiod_id = q2.timeperiod_id " .
            "and q2.quota_type = 'Direct' " .
            "where q1.user_id = " . $this->db->quoted($userId) . " " .
            "and q1.timeperiod_id = " . $this->db->quoted($timeperiodId) . " " .
            "and q1.quota_type = 'Rollup' " .
            "union all " .
            "SELECT q2.amount, q1.id FROM quotas q1 " .
            "left outer join quotas q2 " .
            "on q1.user_id = q2.user_id " .
            "and q1.timeperiod_id = q2.timeperiod_id " .
            "and q2.quota_type = 'Rollup' " .
            "where q1.user_id = " . $this->db->quoted($userId) . " " .
            "and q1.timeperiod_id = " . $this->db->quoted($timeperiodId) . " " .
            "and q1.quota_type = 'Direct'";

        $quota = array();

        $result = $this->db->query($sql);
        while ($row = $this->db->fetchByAssoc($result)) {
            $quota['amount'] = $row['amount'];
            $quota['id'] = $row['id'];
        }

        return $quota;
    }

    /**
     * Recalculates quotas based on committed values and reportees' quota values
     *
     * @param string $user_id
     * @param string $timeperiodId
     * @param bool $fromCommit
     * @return string The new calculated quota for the users
     */
    protected function recalcQuotas($user_id, $timeperiodId, $fromCommit = false)
    {
        global $current_user;

        //Calculate Manager direct
        $mgr_quota = $this->recalcUserQuota($current_user->id, $timeperiodId);

        // update the quota for the managers if the reportee's have changed.
        $this->updateManagerWorksheetQuota($current_user->id, $timeperiodId, $mgr_quota, true);
        if ($fromCommit == true) {
            // when it's from a commit, we need to update the committed record as well.
            $this->updateManagerWorksheetQuota($current_user->id, $timeperiodId, $mgr_quota, false);
        }
        //Calculate reportee direct
        $rep_quota = $this->recalcUserQuota($user_id, $timeperiodId);

        // update the quota for the managers if the reportee's have changed.
        $this->updateManagerWorksheetQuota($user_id, $timeperiodId, $rep_quota, true);
        if ($fromCommit == true) {
            // when it's from a commit, we need to update the committed record as well.
            $this->updateManagerWorksheetQuota($user_id, $timeperiodId, $rep_quota, false);
        }

        return $rep_quota;
    }

    /**
     * Update the manager draft record with the recalculated quota
     *
     * @param string $manager_id
     * @param string $timeperiod
     * @param number $quota
     * @param boolean $isDraft
     * @return bool
     */
    protected function updateManagerWorksheetQuota($manager_id, $timeperiod, $quota, $isDraft = true)
    {
        // safe guard to make sure user is actually a manager
        if (!$this->isUserManager($manager_id)) {
            return false;
        }

        /* @var $worksheet ForecastManagerWorksheet */
        $worksheet = $this->getBean('ForecastManagerWorksheets');

        $return = $worksheet->retrieve_by_string_fields(
            array(
                'user_id' => $manager_id, // user id comes from the user model
                'assigned_user_id' => $manager_id, // the assigned user of the row is who the user reports to
                'timeperiod_id' => $timeperiod, // the current timeperiod
                'draft' => intval($isDraft),
                'deleted' => 0,
            )
        );

        if (is_null($return) && $isDraft === true) {
            $user = $this->getBean('Users', $manager_id);
            // no draft record found, create it based on the above params
            $worksheet->user_id = $manager_id;
            $worksheet->assigned_user_id = $manager_id;
            $worksheet->timeperiod_id = $timeperiod;
            $worksheet->draft = intval($isDraft);
            $worksheet->best_case = 0;
            $worksheet->best_case_adjusted = 0;
            $worksheet->likely_case = 0;
            $worksheet->likely_case_adjusted = 0;
            $worksheet->worst_case = 0;
            $worksheet->worst_case_adjusted = 0;
            $worksheet->opp_count = 0;
            $worksheet->pipeline_opp_count = 0;
            $worksheet->pipeline_amount = 0;
            $worksheet->closed_amount = 0;
            $worksheet->quota = 0;
            $worksheet->name = $user->full_name;

        } else {
            if (!is_null($return) && $isDraft === false) {
                // only update the date_modified if it's a draft version
                $worksheet->update_date_modified = false;
            } else {
                if (is_null($return) && $isDraft === false) {
                    // we didn't find a committed row... we need to bail
                    return false;
                }
            }
        }

        if ($quota != $worksheet->quota) {
            $worksheet->quota = $quota;
            $worksheet->save();

            return true;
        }

        return false;
    }

    /**
     * Recalculates a specific user's direct quota
     *
     * @param string $userId    User Id of quota that needs recalculated.
     * @param string $timeperiodId      the timeperiod to use
     * @return number           The New total for the passed in user
     */
    protected function recalcUserQuota($userId, $timeperiodId)
    {
        global $current_user;

        $isCurrentUser = ($current_user->id === $userId);
        $reporteeTotal = $this->getQuotaSum($userId, $timeperiodId);
        $managerQuota = $this->getManagerQuota($userId, $timeperiodId);
        $managerAmount = (isset($managerQuota['amount']) && !empty($managerQuota['amount']))
                            ? $managerQuota['amount'] : '0';
        $newTotal = SugarMath::init($managerAmount, 6)->sub($reporteeTotal)->result();
        $quota = BeanFactory::getBean('Quotas', isset($managerQuota['id']) ? $managerQuota['id'] : null);
        $quotaAmount = isset($quota->amount) ? $quota->amount : '0';
        /**
         * if this is the current user, we need to use the manager assigned amount to figure out if we need to adjust
         * their quota to make sure that the reporteeTotal + the current assigned Quota is > the manager assigned
         * quota, if it's not we need to adjust the mid level managers quota to make up the gap.
         */
        if ($isCurrentUser) {
            $quotaAmount = $managerAmount;
        }

        /**
         * If the reporteeTotal + the current assigned direct amount is greater than quotaAmount (see above),
         * then we should just return the current assigned direct quota amount,
         *
         * this is also true for top level managers
         */
        if (SugarMath::init($reporteeTotal, 6)->add($quota->amount)->result() > $quotaAmount ||
            ($isCurrentUser && empty($current_user->reports_to_id))) {
            return $quota->amount;
        }

        // if a user is not a manager, then just take the value that the manager assigned to them as the rollup and use
        // it for their direct amount
        if ($this->isUserManager($userId) === false) {
            $newTotal = $managerAmount;
        }

        //save Manager quota
        if ($newTotal != $quota->amount) {
            $quota->user_id = $userId;
            $quota->timeperiod_id = $timeperiodId;
            $quota->quota_type = 'Direct';
            $quota->amount = $newTotal;
            $quota->committed = 1;
            $quota->save();
        }

        return $newTotal;
    }

    /**
     * This method emulates the Forecast Manager Worksheet calculateTotals method.
     *
     * @param string $userId
     * @param string $timeperiodId
     * @param boolean $useDraftRecords
     * @return array|bool            Return calculated totals or boolean false if timeperiod is not valid
     */
    public function worksheetTotals($userId, $timeperiodId, $useDraftRecords = false)
    {
        /* @var $tp TimePeriod */
        $tp = $this->getBean('TimePeriods', $timeperiodId);
        if (empty($tp->id)) {
            // timeperiod not found
            return false;
        }

        $return = array(
            'quota' => '0',
            'best_case' => '0',
            'best_adjusted' => '0',
            'likely_case' => '0',
            'likely_adjusted' => '0',
            'worst_case' => '0',
            'worst_adjusted' => '0',
            'included_opp_count' => 0,
            'pipeline_opp_count' => 0,
            'pipeline_amount' => '0',
            'closed_amount' => '0'
        );

        global $current_user;

        $sq = $this->getSugarQuery();
        $bean_obj = $this->getBean($this->module_name);
        $sq->select(array($bean_obj->getTableName().'.*'));
        $sq->from($bean_obj)->where()
            ->equals('timeperiod_id', $tp->id)
            ->equals('assigned_user_id', $userId)
            ->equals('draft', ($current_user->id == $userId || $useDraftRecords === true) ? 1 : 0)
            ->equals('deleted', 0);
        $results = $sq->execute();

        foreach ($results as $row) {
            $return['quota'] = SugarMath::init($return['quota'], 6)->add(
                SugarCurrency::convertWithRate($row['quota'], $row['base_rate'])
            )->result();
            $return['best_case'] = SugarMath::init($return['best_case'], 6)->add(
                SugarCurrency::convertWithRate($row['best_case'], $row['base_rate'])
            )->result();
            $return['best_adjusted'] = SugarMath::init($return['best_adjusted'], 6)->add(
                SugarCurrency::convertWithRate($row['best_case_adjusted'], $row['base_rate'])
            )->result();
            $return['likely_case'] = SugarMath::init($return['likely_case'], 6)->add(
                SugarCurrency::convertWithRate($row['likely_case'], $row['base_rate'])
            )->result();
            $return['likely_adjusted'] = SugarMath::init($return['likely_adjusted'], 6)->add(
                SugarCurrency::convertWithRate($row['likely_case_adjusted'], $row['base_rate'])
            )->result();
            $return['worst_case'] = SugarMath::init($return['worst_case'], 6)->add(
                SugarCurrency::convertWithRate($row['worst_case'], $row['base_rate'])
            )->result();
            $return['worst_adjusted'] = SugarMath::init($return['worst_adjusted'], 6)->add(
                SugarCurrency::convertWithRate($row['worst_case_adjusted'], $row['base_rate'])
            )->result();
            $return['closed_amount'] = SugarMath::init($return['closed_amount'], 6)->add(
                SugarCurrency::convertWithRate($row['closed_amount'], $row['base_rate'])
            )->result();

            $return['included_opp_count'] += $row['opp_count'];
            $return['pipeline_opp_count'] += $row['pipeline_opp_count'];
            $return['pipeline_amount'] = SugarMath::init($return['pipeline_amount'], 6)
                ->add($row['pipeline_amount'])->result();
        }

        return $return;
    }

    /**
     * Utility method to check if a user is a manager
     *
     * @param string $user_id
     * @return bool
     */
    protected function isUserManager($user_id)
    {
        return User::isManager($user_id);
    }

    /**
     * Utility Method to get a bean
     *
     * @param string $module The module we want the bean for
     * @param null $id The id, if one is needed
     * @return null|SugarBean
     */
    protected function getBean($module, $id = null)
    {
        return BeanFactory::getBean($module, $id);
    }

    /**
     * Return a new SugarQuery object
     *
     * @return SugarQuery
     */
    protected function getSugarQuery()
    {
        return new SugarQuery();
    }

    /**
     * Utility Method to get the Activity Queue Manager
     *
     * @return ActivityQueueManager
     */
    protected function getActivityQueueManager()
    {
        SugarAutoLoader::load('modules/ActivityStream/Activities/ActivityQueueManager.php');
        return new ActivityQueueManager();
    }
}
