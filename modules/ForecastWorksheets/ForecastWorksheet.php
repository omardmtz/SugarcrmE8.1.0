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

class ForecastWorksheet extends SugarBean
{

    public $id;
    public $worksheet_id;
    public $currency_id;
    public $base_rate;
    public $args;
    public $name;
    public $commit_stage;
    public $probability;
    public $best_case;
    public $likely_case;
    public $worst_case;
    public $sales_stage;
    public $product_id;
    public $assigned_user_id;
    public $timeperiod_id;
    public $date_closed;
    public $date_closed_timestamp;
    public $draft = 0; // default to 0, it will be set to 1 by the args that get passed in;
    public $parent_type;
    public $parent_id;
    public $account_name;
    public $account_id;
    public $object_name = 'ForecastWorksheet';
    public $module_name = 'ForecastWorksheets';
    public $module_dir = 'ForecastWorksheets';
    public $table_name = 'forecast_worksheets';
    public $disable_custom_fields = true;
    public $opportunity_id;
    public $opportunity_name;
    public $product_template_id;
    public $product_template_name;
    public $next_step;
    public $lead_source;
    public $description;
    public $category_id;
    public $category_name;
    public $list_price;
    public $cost_price;
    public $discount_price;
    public $discount_amount;
    public $quantity;
    public $total_amount;

    /**
     * The fields we should copy from the Products/RLI Bean
     *
     * @var array
     */
    public $productFieldMap = array(
        'name',
        'account_id',
        'account_name',
        'likely_case',
        'best_case',
        'base_rate',
        'worst_case',
        'currency_id',
        'date_closed',
        'date_closed_timestamp',
        'probability',
        'commit_stage',
        'sales_stage',
        'assigned_user_id',
        'created_by',
        'date_entered',
        'deleted',
        'team_id',
        'team_set_id',
        'opportunity_id',
        'opportunity_name',
        'description',
        'next_step',
        'lead_source',
        'product_type',
        'campaign_id',
        'campaign_name',
        'product_template_id',
        'product_template_name',
        'category_id',
        'category_name',
        'list_price',
        'cost_price',
        'discount_price',
        'discount_amount',
        'quantity',
        'total_amount'
    );

    /**
     * The field map for when coping from an Opportunity Bean
     *
     * @var array
     */
    public $opportunityFieldMap = array(
        'name',
        'account_id',
        'account_name',
        array('likely_case' => 'amount'),
        'best_case',
        'base_rate',
        'worst_case',
        'currency_id',
        'date_closed',
        'date_closed_timestamp',
        'sales_stage',
        'probability',
        'commit_stage',
        'assigned_user_id',
        'created_by',
        'date_entered',
        'deleted',
        'team_id',
        'team_set_id',
        'sales_status',
        'description',
        'next_step',
        'lead_source',
        array('product_type' => 'opportunity_type'),
        'campaign_id',
        'campaign_name'
    );

    /**
     * Update the real table with the values when a save happens on the front end
     *
     * @param bool $check_notify        Should we send the notifications
     */
    public function saveWorksheet($check_notify = false)
    {
        //Update the Opportunities bean -- should update the revenue line item as well through SaveOverload.php
        /* @var $bean Opportunity|Product */
        $bean = $this->getBean($this->parent_type, $this->parent_id);
        $bean->probability = $this->probability;
        if ($bean instanceof RevenueLineItem) {
            $bean->likely_case = $this->likely_case;
        } else {
            $bean->amount = $this->likely_case;
        }

        if (!empty($this->date_closed)) {
            $bean->date_closed = $this->date_closed;
        }

        $bean->sales_stage = $this->sales_stage;
        $bean->commit_stage = $this->commit_stage;
        if ($this->ACLFieldAccess('best_case', 'write')) {
            $bean->best_case = $this->best_case;
        }
        if ($this->ACLFieldAccess('worst_case', 'write')) {
            $bean->worst_case = $this->worst_case;
        }
        $bean->save($check_notify);
    }

    /**
     * Sets Worksheet args so that we save the supporting tables.
     *
     * @param array $args Arguments passed to save method through PUT
     */
    public function setWorksheetArgs($args)
    {
        // save the args variable
        $this->args = $args;

        // loop though the args and assign them to the corresponding key on the object
        foreach ($args as $arg_key => $arg) {
            $this->$arg_key = $arg;
        }
    }


    /**
     * Save an Opportunity as a worksheet
     *
     * @param Opportunity $opp      The Opportunity that we want to save a snapshot of
     * @param bool $isCommit        Is the Opportunity being committed
     */
    public function saveRelatedOpportunity(Opportunity $opp, $isCommit = false)
    {
        $this->retrieve_by_string_fields(
            array(
                'parent_type' => 'Opportunities',
                'parent_id' => $opp->id,
                'draft' => ($isCommit === false) ? 1 : 0,
                'deleted' => 0
            ),
            true,
            false
        );

        $this->deleted = $opp->deleted;

        // load the account
        if (empty($opp->account_name) && !empty($opp->account_id)) {
            $opp->account_name = $this->getRelatedName('Accounts', $opp->account_id);
        }

        $this->copyValues($this->opportunityFieldMap, $opp);

        // set the parent types
        $this->parent_type = 'Opportunities';
        $this->parent_id = $opp->id;
        $this->draft = ($isCommit === false) ? 1 : 0;

        $this->save(false);

        //if this migrated, we need to delete the committed row
        $this->removeMigratedRow($opp);
    }

    /**
     * Commit All Related Products from an Opportunity
     * @deprecated As of 7.8 this is no longer needed
     * @param Opportunity $opp
     * @param $isCommit
     */
    public function saveOpportunityProducts(Opportunity $opp, $isCommit = false)
    {
        $GLOBALS['log']->deprecated('Opportunity::saveOpportunityProducts() has been deprecated in 7.8');

        // remove the relationship if it exists as it could cause errors with the cached beans in the BeanFactory
        if (isset($opp->revenuelineitems)) {
            unset($opp->revenuelineitems);
        }
        // now save all related products to the opportunity
        // commit every product associated with the Opportunity
        $revenuelineitems = $opp->get_linked_beans('revenuelineitems', 'RevenueLineItems');
        /* @var $product Product */
        foreach ($revenuelineitems as $revenuelineitem) {
            /* @var $product_wkst ForecastWorksheet */
            $product_wkst = BeanFactory::newBean('ForecastWorksheets');
            $product_wkst->saveRelatedProduct($revenuelineitem, $isCommit);
            unset($product_wkst); // clear the cache
        }
    }

    /**
     * Removes committed row for a bean if it has moved timeperiods.
     *
     * @param SugarBean $bean
     * @return boolean true if something is removed, false if not
     */
    public function removeMigratedRow(SugarBean $bean)
    {
        $return = false;
        if (isset($this->fetched_row['date_closed']) && isset($bean->fetched_row['date_closed']) &&
            $this->timeperiodHasMigrated($this->fetched_row["date_closed"], $bean->fetched_row["date_closed"])
        ) {
            $worksheet = $this->getBean("ForecastWorksheets");
            $worksheet->retrieve_by_string_fields(
                array(
                    "parent_type" => $bean->module_name,
                    "parent_id" => $bean->id,
                    "draft" => 0,
                    "deleted" => 0,
                ),
                true,
                false
            );
            $worksheet->deleted = 1;
            $worksheet->save();
            $return = true;
        }
        return $return;
    }

    /**
     * Save a snapshot of a Revenue Line Item (Product) to the ForecastWorksheet Table
     *
     * @param RevenueLineItem $rli          The RLI to commit
     * @param bool $isCommit                    Are we committing a product for the forecast
     */
    public function saveRelatedProduct(RevenueLineItem $rli, $isCommit = false)
    {

        $this->retrieve_by_string_fields(
            array(
                'parent_type' => 'RevenueLineItems',
                'parent_id' => $rli->id,
                'draft' => ($isCommit === false) ? 1 : 0,
                'deleted' => 0
            ),
            true,
            false
        );

        $this->deleted = $rli->deleted;

        // load the account
        if (empty($rli->account_name) && !empty($rli->account_id)) {
            $rli->account_name = $this->getRelatedName('Accounts', $rli->account_id);
        }

        // load the opportunity
        if (empty($rli->opportunity_name) && !empty($rli->opportunity_id)) {
            $rli->opportunity_name = $this->getRelatedName('Opportunities', $rli->opportunity_id);
        }

        // Product Template
        if (empty($rli->product_template_name) && !empty($rli->product_template_id)) {
            $rli->product_template_name = $this->getRelatedName('ProductTemplates', $rli->product_template_id);
        }

        // Product Category
        if (empty($rli->category_name) && !empty($rli->category_id)) {
            $rli->category_name = $this->getRelatedName('ProductCategories', $rli->category_id);
        }

        $this->copyValues($this->productFieldMap, $rli);

        // set the parent types
        $this->parent_type = 'RevenueLineItems';
        $this->parent_id = $rli->id;
        $this->draft = ($isCommit === false) ? 1 : 0;

        //if this migrated, we need to delete the committed row
        $this->removeMigratedRow($rli);

        $this->save(false);
    }

    /**
     * Look up an Item name
     *
     * @param string $module            The module we need to look in.
     * @param string $id                The Item Id that we need to find the name for
     * @return string                   The name for the account, or empty if one is not found
     */
    protected function getRelatedName($module, $id)
    {
        $returnValue = '';
        $sugar_query = new SugarQuery();
        $sugar_query->select('name');
        $sugar_query->from(BeanFactory::newBean($module))->where()->equals('id', $id);
        $item = $sugar_query->execute();

        if (!empty($item)) {
            $returnValue = $item[0]['name'];
        }

        // if one is not found, just return empty
        return $returnValue;
    }

    /**
     * Checks to see if a worksheet item being saved has jumped timeperiods.
     *
     * @param date $worksheetDate
     * @param date $objDate
     *
     * @return bool
     */
    protected function timeperiodHasMigrated($worksheetDate, $objDate)
    {
        $return = false;

        //if the close dates are different, we need to see if the obj is in a new timeperiod
        if ($worksheetDate != $objDate) {
            $tp1 = TimePeriod::retrieveFromDate($worksheetDate);
            $tp2 = TimePeriod::retrieveFromDate($objDate);

            if (!empty($tp1) && !empty($tp2) && ($tp1->id != $tp2->id)) {
                $return = true;
            }
        }

        return $return;
    }

    /**
     * Copy the fields from the $seed bean to the worksheet object
     *
     * @param array $fields
     * @param SugarBean|array $seed
     */
    public function copyValues($fields, $seed)
    {
        if ($seed instanceof SugarBean) {
            $seed = $seed->toArray();
        }

        foreach ($fields as $field) {
            $key = $field;
            if (is_array($field)) {
                // if we have an array it should be a key value pair, where the key is the destination value and the value,
                // is the seed value
                list($key, $field) = each($field);
            }
            // make sure the field is set, as not to cause a notice since a field might get unset() from the $seed class
            if (isset($seed[$field])) {
                $this->$key = $seed[$field];
            }
        }
    }

    /**
     * Start the Commit Process for a Sales Rep
     *
     * @param string $user_id
     * @param string $timeperiod
     * @param int $chunk_size            How big to make the chunks of data
     * @return bool
     */
    public function commitWorksheet($user_id, $timeperiod, $chunk_size = 50)
    {
        $settings = Forecast::getSettings();

        if ($settings['is_setup'] == false) {
            $GLOBALS['log']->fatal("Forecast Module is not setup. " . __CLASS__ . " should not be running");
            return false;
        }
        /* @var $tp TimePeriod */
        $tp = $this->getBean('TimePeriods', $timeperiod);

        if (empty($tp->id)) {
            $GLOBALS['log']->fatal("Unable to load TimePeriod for id: " . $timeperiod);
            return false;
        }

        $type = $settings['forecast_by'];

        $sq = $this->getSugarQuery();
        // we want the deleted records
        /* @var $bean_obj SugarBean */
        $bean_obj = $this->getBean($type);
        $sq->select(array($bean_obj->getTableName().'.*'));
        $sq->from($bean_obj, array('add_deleted' => false))->where()
            ->equals('assigned_user_id', $user_id)
            ->queryAnd()
            ->gte('date_closed_timestamp', $tp->start_date_timestamp)
            ->lte('date_closed_timestamp', $tp->end_date_timestamp);
        $sq->orderBy('date_modified', 'DESC');

        $link_name = ($type == 'RevenueLineItems') ? 'account_link' : 'accounts';
        $bean_obj->load_relationship($link_name);
        $bean_obj->$link_name->buildJoinSugarQuery($sq, array('joinTableAlias' => 'account'));
        $sq->select(array(array('account.id', 'account_id')));

        $beans = $sq->execute();

        $this->removeReassignedItems($user_id, $timeperiod, $chunk_size);

        if (empty($beans)) {
            return false;
        }

        $bean_chunks = array_chunk($beans, $chunk_size);

        // process the first chunk
        $this->processWorksheetDataChunk($type, $bean_chunks[0]);

        // process any remaining in the background
        for ($x = 1; $x < count($bean_chunks); $x++) {
            $this->createUpdateForecastWorksheetJob($type, $bean_chunks[$x], $user_id);
        }

        return true;
    }

    /**
     * Process Data Chunks of worksheet data
     *
     * @param string $forecast_by
     * @param array $data
     */
    public function processWorksheetDataChunk($forecast_by, array $data)
    {
        foreach ($data as $bean) {
            /* @var $obj Opportunity|RevenueLineItem */
            $obj = $this->getBean($forecast_by);
            $obj->loadFromRow($bean);

            /* @var $worksheet ForecastWorksheet */
            $worksheet = $this->getBean('ForecastWorksheets');
            if ($forecast_by == 'Opportunities') {
                $worksheet->saveRelatedOpportunity($obj, true);
            } elseif ($forecast_by == 'RevenueLineItems') {
                $worksheet->saveRelatedProduct($obj, true);
            }
            unset($worksheet, $obj);
        }
    }

    /**
     * Create a job for the backend to process records on.
     *
     * @param string $forecast_by
     * @param array $data
     * @param string $user_id
     */
    protected function createUpdateForecastWorksheetJob($forecast_by, array $data, $user_id)
    {
        /* @var $job SchedulersJob */
        $job = $this->getBean('SchedulersJobs');
        $job->name = "Update ForecastWorksheets";
        $job->target = "class::SugarJobUpdateForecastWorksheets";
        $job->data = json_encode(array('forecast_by' => $forecast_by, 'data' => $data));
        $job->retry_count = 0;
        $job->assigned_user_id = $user_id;

        $jq = $this->getJobQueue();
        $jq->submitJob($job);
    }

    /**
     * Removes leftover committed items after something is reassigned.  When the user commits again,
     * things that were reassigned are removed.
     *
     * @param string $user_id
     * @param String $timeperiod
     * @param int $chunk_size
     * @return bool success
     */
    public function removeReassignedItems($user_id, $timeperiod, $chunk_size = 50)
    {
        $settings = $this->getForecastSettings();
        $returnVal = true;

        if ($settings['is_setup'] == false) {
            $GLOBALS['log']->fatal("Forecast Module is not setup. " . __CLASS__ . " should not be running");
            $returnVal = false;
        }

        $tp = $this->getBean('TimePeriods', $timeperiod);

        if ($returnVal && empty($tp->id)) {
            $GLOBALS['log']->fatal("Unable to load TimePeriod for id: " . $timeperiod);
            $returnVal = false;
        }

        if ($returnVal) {
            $sq = $this->getSugarQuery();
            $sq->select(array('id'));
            $sq->from($this, array('alias'=>'fw','team_security'=>false))
                ->joinTable('forecast_worksheets', array('alias' => 'fw2'))
                ->on()->equalsField('fw2.parent_id', 'fw.parent_id')
                ->notEqualsField('fw2.assigned_user_id', 'fw.assigned_user_id');
            $sq->where()
                ->equals("draft", 0)
                ->queryAnd()
                ->equals("assigned_user_id", $user_id);

            $beans = $sq->execute();

            if (empty($beans)) {
                $returnVal = false;
            } else {
                $bean_chunks = array_chunk($beans, $chunk_size);

                //process the first chunk
                $this->processRemoveChunk($bean_chunks[0]);

                // process any remaining in the background
                for ($x = 1; $x < count($bean_chunks); $x++) {
                    $this->createRemoveReassignedJob($bean_chunks[$x], $user_id);
                }

            }
        }

        return $returnVal;
    }

    /**
     * Processes the first set of beans to remove
     *
     * @param array $beans Forecastworksheet beans
     */
    public function processRemoveChunk($beans)
    {
        foreach ($beans as $bean) {
            $this->mark_deleted($bean["id"]);
        }
    }

    /**
     * Creates the job to remove reassigned items
     *
     * @param array $data Array of bean IDs
     * @param string $user_id
     */
    public function createRemoveReassignedJob($data, $user_id)
    {
        $job = $this->getBean('SchedulersJobs', null);
        $job->name = 'Remove Reassigned Items';
        $job->target = 'class::SugarJobRemoveReassignedItems';
        $job->data = json_encode($data);
        $job->retry_count = 0;
        $job->assigned_user_id = $user_id;

        $jq = $this->getJobQueue();
        $jq->submitJob($job);
    }

    /**
     * Utility function to get Forecast Settings (to aid in test writing)
     *
     * @param bool $reload
     * @return array
     */
    public function getForecastSettings($reload = false)
    {
        return Forecast::getSettings($reload);
    }

    /**
     * Utility function to get beans (to aid in test writing)
     *
     * @param string $beanName
     * @param string|null $id
     * @return null|SugarBean
     */
    public function getBean($beanName, $id = null)
    {
        return BeanFactory::getBean($beanName, $id);
    }

    /**
     * Utility function to get the job queue (to aid in test writing)
     * @return SugarJobQueue
     */
    public function getJobQueue()
    {
        SugarAutoLoader::load('include/SugarQueue/SugarJobQueue.php');
        return new SugarJobQueue();
    }

    /**
     * Utility function to get a new SugarQuery instance (to aid in test writing)
     * @return SugarQuery
     */
    public function getSugarQuery()
    {
        return new SugarQuery();
    }

    public static function reassignForecast($fromUserId, $toUserId)
    {
        global $current_user;

        $db = DBManagerFactory::getInstance();

        // reassign Opportunities
        $_object = BeanFactory::newBean('Opportunities');
        $_query = "update {$_object->table_name} set " .
            "assigned_user_id = '{$toUserId}', " .
            "date_modified = '" . TimeDate::getInstance()->nowDb() . "', " .
            "modified_user_id = '{$current_user->id}' " .
            "where {$_object->table_name}.deleted = 0 and {$_object->table_name}.assigned_user_id = '{$fromUserId}'";
        $res = $db->query($_query, true);
        $affected_rows = $db->getAffectedRowCount($res);

        // Products
        // reassign only products that have related opportunity - products created from opportunity::save()
        // other products will be reassigned if module Product is selected by user
        $_object = BeanFactory::newBean('RevenueLineItems');
        $_query = "update {$_object->table_name} set " .
            "assigned_user_id = '{$toUserId}', " .
            "date_modified = '" . TimeDate::getInstance()->nowDb() . "', " .
            "modified_user_id = '{$current_user->id}' " .
            "where {$_object->table_name}.deleted = 0 and {$_object->table_name}.assigned_user_id = '{$fromUserId}' and {$_object->table_name}.opportunity_id IS NOT NULL ";
        $res = $db->query($_query, true);
        $affected_rows += $db->getAffectedRowCount($res);

        // delete Forecasts
        $_object = BeanFactory::newBean('Forecasts');
        $_query = "update {$_object->table_name} set " .
            "deleted = 1, " .
            "date_modified = '" . TimeDate::getInstance()->nowDb() . "' " .
            "where {$_object->table_name}.deleted = 0 and {$_object->table_name}.user_id = '{$fromUserId}'";
        $res = $db->query($_query, true);
        $affected_rows += $db->getAffectedRowCount($res);

        // delete Quotas
        $_object = BeanFactory::newBean('Quotas');
        $_query = "update {$_object->table_name} set " .
            "deleted = 1, " .
            "date_modified = '" . TimeDate::getInstance()->nowDb() . "' " .
            "where {$_object->table_name}.deleted = 0 and {$_object->table_name}.user_id = '{$fromUserId}'";
        $res = $db->query($_query, true);
        $affected_rows += $db->getAffectedRowCount($res);

        // clear reports_to for inactive users
        $objFromUser = BeanFactory::getBean('Users', $fromUserId, array('deleted' => false));
        $fromUserReportsTo = !empty($objFromUser->reports_to_id) ? $objFromUser->reports_to_id : '';
        $objFromUser->reports_to_id = '';
        $objFromUser->save();

        if (User::isManager($fromUserId)) {
            // setup report_to for user
            $objToUserId = BeanFactory::newBean('Users');
            $objToUserId->retrieve($toUserId);
            $objToUserId->reports_to_id = $fromUserReportsTo;
            $objToUserId->save();

            // reassign users (reportees)
            $_object = BeanFactory::newBean('Users');
            $_query = "update {$_object->table_name} set " .
                "reports_to_id = '{$toUserId}', " .
                "date_modified = '" . TimeDate::getInstance()->nowDb() . "', " .
                "modified_user_id = '{$current_user->id}' " .
                "where {$_object->table_name}.deleted = 0 and {$_object->table_name}.reports_to_id = '{$fromUserId}' " .
                "and {$_object->table_name}.id != '{$toUserId}'";
            $db->query($_query, true);
        }

        // ForecastWorksheets
        // reassign entries in forecast_worksheets for the draft rows
        $_object = BeanFactory::newBean('ForecastWorksheets');
        $_query = "update {$_object->table_name} set " .
            "assigned_user_id = '{$toUserId}', " .
            "date_modified = '" . TimeDate::getInstance()->nowDb() . "', " .
            "modified_user_id = '{$current_user->id}' " .
            "where {$_object->table_name}.deleted = 0 and {$_object->table_name}.draft = 1
            and {$_object->table_name}.assigned_user_id = '{$fromUserId}'";
        $res = $db->query($_query, true);
        $affected_rows += $db->getAffectedRowCount($res);

        // delete all the committed rows as they are no longer needed
        $_query = "update {$_object->table_name} set " .
            "deleted = 1, " .
            "date_modified = '" . TimeDate::getInstance()->nowDb() . "', " .
            "modified_user_id = '{$current_user->id}' " .
            "where {$_object->table_name}.deleted = 0 and {$_object->table_name}.draft = 0
            and {$_object->table_name}.assigned_user_id = '{$fromUserId}'";
        $res = $db->query($_query, true);
        $affected_rows += $db->getAffectedRowCount($res);

        // ForecastManagerWorksheets

        // reassign entries in forecast_manager_worksheets
        $_object = BeanFactory::newBean('ForecastManagerWorksheets');

        // delete all manager worksheets for the user we are migrating away from
        $_query = "update {$_object->table_name} set " .
            "deleted = 1, " .
            "date_modified = '" . TimeDate::getInstance()->nowDb() . "', " .
            "modified_user_id = '{$current_user->id}' " .
            "where {$_object->table_name}.deleted = 0
            and {$_object->table_name}.user_id = '{$fromUserId}'";
        $res = $db->query($_query, true);
        $affected_rows += $db->getAffectedRowCount($res);

        // Remove any committed rows that are assigned to the user we are migration away from, since we don't
        // want to migration committed records
        $_query = "update {$_object->table_name} set " .
            "deleted = 1, " .
            "date_modified = '" . TimeDate::getInstance()->nowDb() . "', " .
            "modified_user_id = '{$current_user->id}' " .
            "where {$_object->table_name}.deleted = 0 and {$_object->table_name}.draft = 0
            and {$_object->table_name}.assigned_user_id = '{$fromUserId}'";
        $res = $db->query($_query, true);
        $affected_rows += $db->getAffectedRowCount($res);

        // move all draft records left over that have not been deleted to the new user.
        $_query = "update {$_object->table_name} set " .
            "assigned_user_id = '{$toUserId}', " .
            "user_id = '{$toUserId}', " .
            "date_modified = '" . TimeDate::getInstance()->nowDb() . "', " .
            "modified_user_id = '{$current_user->id}' " .
            "where {$_object->table_name}.deleted = 0 and {$_object->table_name}.assigned_user_id = '{$fromUserId}' ";
        $res = $db->query($_query, true);
        $affected_rows += $db->getAffectedRowCount($res);

        return $affected_rows;
    }

    /**
     * This method emulates the Forecast Rep Worksheet calculateTotals method.
     *
     * @param string $timeperiod_id
     * @param string $user_id
     * @param string|null $forecast_by
     * @param boolean $useDraftRecords
     * @return array|bool
     */
    public function worksheetTotals($timeperiod_id, $user_id, $forecast_by = null, $useDraftRecords = false)
    {
        /* @var $tp TimePeriod */
        $tp = $this->getBean('TimePeriods', $timeperiod_id);
        if (empty($tp->id)) {
            // timeperiod not found
            return false;
        }

        $settings = Forecast::getSettings();
        if (is_null($forecast_by)) {
            $forecast_by = $settings['forecast_by'];
        }

        // setup the return array
        $return = array(
            'amount' => '0',
            'best_case' => '0',
            'worst_case' => '0',
            'overall_amount' => '0',
            'overall_best' => '0',
            'overall_worst' => '0',
            'timeperiod_id' => $tp->id,
            'lost_count' => '0',
            'lost_amount' => '0',
            'lost_best' => '0',
            'lost_worst' => '0',
            'won_count' => '0',
            'won_amount' => '0',
            'won_best' => '0',
            'won_worst' => '0',
            'included_opp_count' => 0,
            'total_opp_count' => 0,
            'includedClosedCount' => 0,
            'includedClosedAmount' => '0',
            'includedClosedBest' => '0',
            'includedClosedWorst' => '0',
            'pipeline_amount' => '0',
            'pipeline_opp_count' => 0,
            'closed_amount' => '0',
            'includedIdsInLikelyTotal' => array()
        );

        global $current_user;

        $sq = $this->getSugarQuery();
        $bean_obj = $this->getBean($this->module_name);
        $sq->select(array($bean_obj->getTableName().'.*'));
        $sq->from($bean_obj)->where()
            ->equals('assigned_user_id', $user_id)
            ->equals('parent_type', $forecast_by)
            ->equals('deleted', 0)
            ->equals('draft', ($current_user->id == $user_id || $useDraftRecords === true) ? 1 : 0)
            ->queryAnd()
            ->gte('date_closed_timestamp', $tp->start_date_timestamp)
            ->lte('date_closed_timestamp', $tp->end_date_timestamp);
        $results = $sq->execute();

        foreach ($results as $row) {
            // if customers have made likely_case, best_case, or worst_case not required,
            // it saves to the DB as NULL, make sure we set it to 0 for the math ahead
            if (empty($row['likely_case'])) {
                $row['likely_case'] = 0;
            }
            if (empty($row['best_case'])) {
                $row['best_case'] = 0;
            }
            if (empty($row['worst_case'])) {
                $row['worst_case'] = 0;
            }
            
            $worst_base = SugarCurrency::convertWithRate($row['worst_case'], $row['base_rate']);
            $amount_base = SugarCurrency::convertWithRate($row['likely_case'], $row['base_rate']);
            $best_base = SugarCurrency::convertWithRate($row['best_case'], $row['base_rate']);

            $closed = false;
            if (in_array($row['sales_stage'], $settings['sales_stage_won']) && in_array($row['commit_stage'], $settings['commit_stages_included'])) {
                $return['won_amount'] = SugarMath::init($return['won_amount'], 6)->add($amount_base)->result();
                $return['won_best'] = SugarMath::init($return['won_best'], 6)->add($best_base)->result();
                $return['won_worst'] = SugarMath::init($return['won_worst'], 6)->add($worst_base)->result();
                $return['won_count']++;
                $return['includedClosedCount']++;
                $return['includedClosedAmount'] = SugarMath::init($return['includedClosedAmount'], 6)
                    ->add($amount_base)->result();
                $closed = true;
            } elseif (in_array($row['sales_stage'], $settings['sales_stage_lost'])) {
                $return['lost_amount'] = SugarMath::init($return['lost_amount'], 6)->add($amount_base)->result();
                $return['lost_best'] = SugarMath::init($return['lost_best'], 6)->add($best_base)->result();
                $return['lost_worst'] = SugarMath::init($return['lost_worst'], 6)->add($worst_base)->result();
                $return['lost_count']++;
                $closed = true;
            }

            if (in_array($row['commit_stage'], $settings['commit_stages_included'])) {
                if (!$closed) {
                    $return['amount'] = SugarMath::init($return['amount'], 6)->add($amount_base)->result();
                    $return['best_case'] = SugarMath::init($return['best_case'], 6)->add($best_base)->result();
                    $return['worst_case'] = SugarMath::init($return['worst_case'], 6)->add($worst_base)->result();

                    // add RLI/Opp id to includedIds array
                    array_push($return['includedIdsInLikelyTotal'], $row['parent_id']);
                }
                $return['included_opp_count']++;
                if ($closed) {
                    $return['includedClosedBest'] = SugarMath::init($return['includedClosedBest'], 6)
                        ->add($best_base)->result();
                    $return['includedClosedWorst'] = SugarMath::init($return['includedClosedWorst'], 6)
                        ->add($worst_base)->result();
                }
            }

            $return['total_opp_count']++;
            $return['overall_amount'] = SugarMath::init($return['overall_amount'], 6)->add($amount_base)->result();
            $return['overall_best'] = SugarMath::init($return['overall_best'], 6)->add($best_base)->result();
            $return['overall_worst'] = SugarMath::init($return['overall_worst'], 6)->add($worst_base)->result();
        }

        // send back the totals
        return $return;

    }
}
