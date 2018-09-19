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
 *  Quotas are used to store quota information on certain users.
 */
class Quota extends SugarBean
{
    // Stored fields

    public $id;
    public $deleted;
    public $date_entered;
    public $date_modified;
    public $modified_user_id;
    public $created_by;
    public $created_by_name;
    public $modified_by_name;

    public $user_id;
    public $assigned_user_id;
    public $user_name;
    public $user_full_name;
    public $timeperiod_id;
    public $amount;
    public $amount_base_currency;
    public $currency_id;
    public $base_rate;
    public $currency_symbol;
    public $committed;

    public $table_name = "quotas";
    public $module_dir = 'Quotas';
    public $object_name = "Quota";

    //Here value of tracker_visibility is false, as this module should not be tracked.
    public $tracker_visibility = false;

    public $new_schema = true;

    // This is used to retrieve related fields from form posts.
    public $additional_column_fields = Array();


    public function __construct()
    {
        parent::__construct();
        $this->disable_row_level_security = true;
    }

    /**
     * Get summary text
     */
    public function get_summary_text()
    {
        /**
         * @var TimePeriod
         */
        $timeperiod = BeanFactory::retrieveBean("TimePeriods", $this->timeperiod_id);
        // make sure we have the full name before display happens
        if (empty($this->user_full_name) && !empty($this->user_id)) {
            $user = BeanFactory::getBean('Users', $this->user_id);
            $this->user_full_name = $user->full_name;
        }
        $mod_strings = return_module_language($GLOBALS['current_language'], $this->module_name);

        // get the quota type as a label
        $quota_type = '';
        if (!empty($this->quota_type)) {
            if ($this->quota_type == 'Direct') {
                $quota_type = $mod_strings['LBL_MODULE_NAME_SINGULAR'] . ' ';
            }
            else if ($this->quota_type == 'Rollup') {
                $quota_type = $mod_strings['LBL_MODULE_NAME_SINGULAR'] . ' (' . $mod_strings['LBL_ADJUSTED'] . ') ';
            }
        }

        return "{$timeperiod->name} {$quota_type}- $this->user_full_name";
    }

    /**
     * function create_list_query
     *
     * @param $order_by
     * @param $where
     * @param $show_deleted
     */
    public function create_new_list_query($order_by, $where, $filter = array(), $params = array(), $show_deleted = 0, $join_type = '', $return_array = false, $parentbean = null, $singleSelect = false, $retrieve_created_by = true)
    {
        global $current_user;

        $ret_array['select'] = "SELECT users.first_name as name, users.last_name, users.id users_id, quotas.* ";

        $ret_array['from'] = " FROM users, quotas ";

        $us = BeanFactory::newBean('Users');
        $us->addVisibilityFrom($ret_array['from'], array('where_condition' => true));
        $us->addVisibilityFrom($where, array('where_condition' => true));

        $where_query = ' WHERE ';

        if (trim($where) != '') {
            $where_query .= $where . " AND ";
        }

        $where_query .= " users.id = quotas.user_id";

        if ($retrieve_created_by) {
            $where_query .= " AND quotas.created_by = '" . $current_user->id . "'";
        }

        $where_query .= " AND (users.reports_to_id = '" . $current_user->id . "'";

        if ($retrieve_created_by) {
            $where_query .= " OR (quotas.quota_type = 'Direct'" .
                " AND users.id = '" . $current_user->id . "'))";
        } else {
            $where_query .= " OR (users.id = '" . $current_user->id . "'))";
        }
        $ret_array['where'] = $where_query;
        $orderby_query = '';
        if (!empty($order_by)) {
            $orderby_query = " ORDER BY $order_by";
        }

        $ret_array['order_by'] = $orderby_query;

        if ($return_array) {
            return $ret_array;
        }

        return $ret_array['select'] . $ret_array['from'] . $ret_array['where'] . $ret_array['order_by'];
    }

    public function fill_in_additional_list_fields()
    {
        $this->fill_in_additional_detail_fields();
    }


    public function fill_in_additional_detail_fields()
    {
        global $mod_strings;
        // Get the user's full name
        $this->user_full_name = $this->getUserFullName($this->user_id);

        // Get the currency symbol based on the currency id
        if ($this->currency_id != -99) {
            $this->currency_symbol = $this->getCurrencySymbol($this->currency_id);
        }
    }


    public function get_list_view_data()
    {
        $temp_array = $this->get_list_view_array();

        if ($this->currency_id == -99) {
            $temp_array['AMOUNT'] = format_number($this->amount_base_currency, 2, 2, array('convert' => true, 'currency_symbol' => true));
        } else {
            $temp_array['AMOUNT'] = format_number($this->amount_base_currency, 2, 2, array('convert' => true, 'currency_symbol' => false)) . " ( " . $this->currency_symbol . " )";
        }

        if ($this->committed == 1) {
            $temp_array['COMMITTED_FLAG'] = 'checked';
        }
        return $temp_array;
    }


    public function save($check_notify = false)
    {
        if (empty($this->currency_id)) {
            // use user preferences for currency
            $currency = SugarCurrency::getUserLocaleCurrency();
            $this->currency_id = $currency->id;
        } else {
            $currency = SugarCurrency::getCurrencyByID($this->currency_id);
        }
        $this->base_rate = $currency->conversion_rate;

        return parent::save($check_notify);
    }


    public function set_notification_body($xtpl, $quota)
    {
        global $sugar_config;

        $xtpl->assign("QUOTA_AMOUNT", format_number($quota->amount, 2, 2, array('convert' => true, 'currency_symbol' => true)));
        $xtpl->assign("QUOTA_URL", $sugar_config['site_url'] . '/index.php?module=Quotas&action=index&timeperiod_id=' . $quota->timeperiod_id);
        $xtpl->assign("QUOTA_TIMEPERIOD", $quota->getTimePeriod($quota->timeperiod_id));

        return $xtpl;
    }


    /**
     * function getQuotaRowCount. Helper function to get a row count of the given query
     * (Can be modified and/or removed if there are other utils in the code for this)
     *
     * @param $query
     * NOTE: Renamed to distinguish it from the deprecated and now removed getRowCount DBManager function
     */
    public function getQuotaRowCount($query)
    {
        $result = $this->db->query($this->create_list_count_query($query));
        $row = $this->db->fetchByAssoc($result);

        return $row['c'];
    }


    /**
     * function getUserFullName. Helper function to get the full name of the user.
     *
     * @param $user_id
     */
    public function getUserFullName($user_id)
    {
        global $locale;

        // Get the user's full name to display in the table
        $qry = "SELECT U.first_name, U.last_name " .
            "FROM users U " .
            "WHERE U.id = " . $this->db->quoted($user_id);

        $result = $this->db->query($qry, true, " Error filling in additional detail fields: ");

        $row = Array();
        $row = $this->db->fetchByAssoc($result);

        if ($row != NULL) {
            $user_full_name = $locale->formatName('Users', $row);
            return $user_full_name;
        }

        return null;
    }


    /**
     * function getTimePeriodSelectList. Helper function to deliver the options
     * for the timeperiod <SELECT> tag.
     *
     * @param $id - if it is defined, then default to the selected timeperiod,
     *            else print directions for the user
     */
    public function getTimePeriodsSelectList($id = '')
    {
        global $mod_strings;

        $qry = "SELECT id, name FROM timeperiods where deleted=0 and is_fiscal_year=0 ";

        $result = $this->db->query($qry, true, 'Error retrieving timeperiods: ');

        $options = '';
        if ($id == null) // timeperiods is not defined, print "Select Time Period..."
        {
            $options .= '<option value="?action=index&module=Quotas" SELECTED>'
                . $mod_strings['LBL_SELECT_TIME_PERIOD']
                . '</option>';
        }

        while ($row = $this->db->fetchByAssoc($result)) {

            if ($row['id'] == $id) // timeperiod is selected, default to this one
            {
                $options .= '<option value="?edit=true&action=index&module=Quotas&timeperiod_id='
                    . $row['id']
                    . '" SELECTED>'
                    . $row['name']
                    . '</option>';
            } else {
            // print all other time periods
                $options .= '<option value="?edit=true&action=index&module=Quotas&timeperiod_id='
                    . $row['id']
                    . '">'
                    . $row['name']
                    . '</option>';
            }
        }

        return $options;
    }


    public function getTimePeriod($id)
    {

        $qry = "SELECT id, name FROM timeperiods where id = '" . $id . "'";

        $result = $this->db->query($qry, true, 'Error retrieving timeperiod: ');

        $row = $this->db->fetchByAssoc($result);

        return $row['name'];
    }


    /**
     * Find a users assigned quota for a given time period and return it.  The method will default to the current user
     * if no user is passed in.
     *
     * @param string           $timeperiod_id
     * @param null|string|User $user
     *
     * @return array
     */
    public function getCurrentUserQuota($timeperiod_id, $user = null)
    {

        if (empty($user)) {
            global $current_user;
            $user = $current_user->id;
        } else {
            if ($user instanceof User) {
                $user = $user->id;
            }
        }

        $qry = "SELECT quotas.currency_id, quotas.amount, timeperiods.name as timeperiod_name " .
            "FROM quotas INNER JOIN users ON quotas.user_id = users.id, timeperiods " .
            "WHERE quotas.timeperiod_id = timeperiods.id " .
            "AND quotas.user_id = " . $this->db->quoted($user) .
            " AND ((quotas.created_by <> " . $this->db->quoted($user) . " AND quotas.quota_type = 'Direct')" .
            "OR (users.reports_to_id IS NULL AND quotas.quota_type = 'Rollup')) " . //for top-level manager
            "AND timeperiods.id = " . $this->db->quoted($timeperiod_id) . " AND quotas.committed = 1";

        $result = $this->db->query($qry, true, 'Error retrieving Current User Quota Information: ');

        $row = $this->db->fetchByAssoc($result);
        if (empty($row)) {
            $row['amount'] = 0.00;
            $row['currency_id'] = -99;
        }

        if ($row['currency_id'] == -99) // print the default currency
        {
            $row['formatted_amount'] = format_number($row['amount'], 2, 2, array('convert' => true, 'currency_symbol' => true));
        } else {
            // print the foreign currency, must retrieve currency symbol
            $row['formatted_amount'] = format_number($row['amount'], 2, 2, array('convert' => true, 'currency_symbol' => false)) . " ( " . $this->getCurrencySymbol($row['currency_id']) . " )";
        }

        return $row;
    }


    /**
     * function getGroupQuota. Function to retrieve the total quota for a manager's
     * entire group.
     *
     * @param $timeperiod_id
     * @param $formatted - boolean to test if output should be formatted
     * @param $id        - to pass in an user_id in case it is necessary
     */
    public function getGroupQuota($timeperiod_id, $formatted = true, $id = null)
    {
        global $current_user;
        if ($id == null) {
            $id = $current_user->id;
        }

        $query = "SELECT {$this->db->convert('SUM(quotas.amount_base_currency)', 'ifnull', array(0))} as group_amount" .
            " FROM users, quotas " .
            " WHERE quotas.timeperiod_id = " . $this->db->quoted($timeperiod_id) .
            " AND quotas.deleted = 0" .
            " AND users.id = quotas.user_id" .
               " AND users.deleted = 0" .
            " AND quotas.created_by = " .$this->db->quoted($id) .
            " AND (users.reports_to_id = " . $this->db->quoted($id) .
            " OR (users.id = " . $this->db->quoted($id) .
            " AND quotas.quota_type <> 'Rollup'))"; //for top-level manager

        $result = $this->db->query($query, true, 'Error retrieving Group Quota: ');
        $row = $this->db->fetchByAssoc($result);

        if ($formatted) {
            return format_number($row['group_amount'], 2, 2, array('convert' => true, 'currency_symbol' => true));
        } else {
            return $row['group_amount'];
        } // return an unformmated version (for insertion/update into DB)
    }


    /**
     * Retrieve a user's quota using the rollup value, if available.  This method is useful for
     * fetching user quota data when you're unsure about whether or not the given user is a manager.
     * If you would like to force a direct quota, pass a false value to $should_rollup.
     *
     * @param $timeperiod_id String id of the TimePeriod to retrieve quota for
     * @param $user_id String value of the user id to retrieve.  If NULL, the $current_user is used
     * @param $should_rollup boolean value indicating whether or not the quota should be a rollup calculation; false by default
     *
     * @return array [currency_id => int, amount => number, formatted_amount => String]
     */
    public function getRollupQuota($timeperiod_id, $user_id = null, $should_rollup = false)
    {
        if (is_null($user_id)) {
            global $current_user;
            $user_id = $current_user->id;
        }

        // figure out the timeperiod
        // if we didn't find a time period, set the time period to be the current time period
        if (!is_guid($timeperiod_id) && is_numeric($timeperiod_id) && $timeperiod_id != 0) {
            // we have a timestamp, find timeperiod it belongs in
            $timeperiod_id = TimePeriod::getIdFromTimestamp($timeperiod_id);
        }

        if (!is_guid($timeperiod_id)) {
            $timeperiod_id = TimePeriod::getCurrentId();
        }

        $sq = new SugarQuery();
        $sq->select(array('quotas.currency_id', 'quotas.amount'));
        $sq->from(BeanFactory::newBean('Quotas'));
        $sq->where()
            ->equals('user_id', $user_id)
            ->equals('quota_type', ($should_rollup) ? 'Rollup' : 'Direct')
            ->equals('timeperiod_id', $timeperiod_id);
        $sq->orderBy('date_modified', 'DESC');
        $sq->limit(1);

        // since there is only ever one row, just shift the value off the results
        $results = $sq->execute();
        $row = array_shift($results);

        if (empty($row)) {
            // This is to prevent return value of false when a given timeperiod has no quota.
            $row = array(
                'currency_id' => -99,
                'amount' => 0,
            );
        }
        $row['formatted_amount'] = SugarCurrency::formatAmountUserLocale($row['amount'], $row['currency_id']);

        return $row;
    }


    public function resetGroupQuota($timeperiod_id)
    {
        global $current_user;

        $query = "UPDATE quotas SET quotas.amount=0, quotas.amount_base_currency=0" .
            " WHERE quotas.timeperiod_id = '" . $timeperiod_id . "'" .
            " AND quotas.quota_type = 'Rollup'" .
            " AND quotas.user_id = '" . $current_user->id . "'";

        $this->db->query($query, true, 'Error Updating Group Quota: ');
    }


    /**
     * function getTopLevelRecord. For the current user, get the record
     * id of the rollup quota that has been assigned. This is used in
     * the Save.php file when a top level manager needs his/her own
     * rollup quota value updated.
     *
     * @param $timeperiod_id
     */
    public function getTopLevelRecord($timeperiod_id)
    {
        global $current_user;

        $qry = "SELECT id " .
            "FROM quotas " .
            "WHERE quotas.user_id = '" . $current_user->id . "' " .
            "AND quotas.timeperiod_id = '" . $timeperiod_id . "' " .
            "AND quotas.quota_type = 'Rollup'";

        $result = $this->db->query($qry, true, 'Error retrieving top level manager information for quotas: ');
        $row = $this->db->fetchByAssoc($result);

        return $row['id'];
    }


    /**
     * function getConversionRate. Return the conversion rate of the currency
     * against the base currency.
     *
     * @param $currency_id - the currency in which to switch to and find a
     *                     conversion rate for.
     */
    public function getConversionRate($currency_id)
    {
        $qry = "SELECT conversion_rate " .
            "FROM currencies " .
            "WHERE id = '" . $currency_id . "'";

        $result = $this->db->query($qry, true, 'Error retrieving conversion rate for quotas: ');
        $row = $this->db->fetchByAssoc($result);

        return $row['conversion_rate'];
    }


    /**
     * function getCurrencySymbol. Return the symbol for the currency in which
     * we are converting to.
     *
     * @param $currency_id - the currency in which to switch to and find a
     *                     conversion rate for.
     */
    public function getCurrencySymbol($currency_id)
    {
        $qry = "SELECT symbol " .
            "FROM currencies " .
            "WHERE id = '" . $currency_id . "'";

        $result = $this->db->query($qry, true, 'Error retrieving currency rate for quotas: ');
        $row = $this->db->fetchByAssoc($result);

        return $row['symbol'];
    }


    /**
     * function getUserManagedSelectList. Function to populate <SELECT> tag
     * for the manager's direct reports.
     *
     * @param $timeperiod_id
     * @param $id - if the id is given, use this idea as the selected choice
    */
    public function getUserManagedSelectList($timeperiod_id, $id = '')
    {
    global $mod_strings;
    global $locale;

    $data = $this->getUserManagedSelectData($timeperiod_id);
    $options = '';

    if ($id == null) {
     $options .= '<option value="?action=index&module=Quotas" SELECTED>'
              . $mod_strings['LBL_SELECT_USER']
              . '</option>' ;
    }

    foreach($data as $row) {
     if ($row['user_id'] == $id) {
         $options .= '<option value="?edit=true&action=index&module=Quotas&record='
                  . $row['quota_id']
                  . '&user_id=' . $row['user_id']
                  . '&timeperiod_id=' . $timeperiod_id
                  . '" SELECTED>'
                  . $locale->formatName('Users', $row)
                  . '</option>';
     } else {
         if ($row['quota_id'] == null){
             $options .= '<option value="?edit=true&action=index&module=Quotas&record=new'
                  . '&user_id=' . $row['user_id']
                  . '&timeperiod_id=' . $timeperiod_id
                  . '">'
                  . $locale->formatName('Users', $row)
                  . '</option>' ;
         } else {
             $options .= '<option value="?edit=true&action=index&module=Quotas&record='
                  . $row['quota_id']
                  . '&user_id=' . $row['user_id']
                  . '&timeperiod_id=' . $timeperiod_id
                  . '">'
                  . $locale->formatName('Users', $row)
                  . '</option>' ;
         }
     }
    }

    return $options;
    }


    /**
    * Return data for building options in Quota::getUserManagedSelectList
    * @param string $timeperiod_id
    * @return array
    */
    protected function getUserManagedSelectData($timeperiod_id)
    {
        $result = array();
        global $current_user;
        $query = "SELECT U.id as user_id, Q.id as quota_id, Q.timeperiod_id as timeperiod_id, user_name, first_name, last_name " .
               "FROM users U " .
               "LEFT OUTER JOIN (SELECT quotas.id, quotas.user_id, quotas.timeperiod_id, quotas.quota_type " .
               "FROM quotas, users " .
               "WHERE quotas.timeperiod_id = {$this->db->quoted($timeperiod_id)}" .
               "AND quotas.user_id = users.id " .
               "AND quotas.created_by = {$this->db->quoted($current_user->id)}" .
               "AND (users.reports_to_id = {$this->db->quoted($current_user->id)}" .
               "OR (quotas.quota_type = 'Direct' AND users.id = {$this->db->quoted($current_user->id)}) ) ) Q " .
               "ON Q.user_id = U.id  " .
               "WHERE (U.reports_to_id = {$this->db->quoted($current_user->id)}" .
               " OR U.id = {$this->db->quoted($current_user->id)}) AND U.deleted = 0  " .
               "ORDER BY first_name";
        $resource = $this->db->query($query, true, 'Error retrieving quotas for managed users for current user: ');
        while($row = $this->db->fetchByAssoc($resource)){
           array_push($result, $row);
        }
        return $result;
    }


    /**
     * function isManager. The purpose of this function is to determine whether
     * the given user is a manager
     *
     * @param $id - id of the user in question
     */

    public function isManager($id)
    {
        global $current_user;

        $qry = "SELECT * " .
            "FROM users " .
            "WHERE reports_to_id = '" . $id . "'";

        $result = $this->db->query($this->create_list_count_query($qry), true, 'Error retrieving row count from quotas: ');
        $row = $this->db->fetchByAssoc($result);

        if ($row['c'] > 0) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * function isTopLevelManager. Function to determine whether the current
     * logged in user is a top level manager (ie, a manager in which he/she
     * does not report to anyone)
     */
    public function isTopLevelManager()
    {
        global $current_user;

        $qry = "SELECT * FROM users WHERE reports_to_id IS NULL AND id = '" . $current_user->id . "'";

        $result = $this->db->query($qry, true, 'Error retrieving top level manager information for quotas: ');
        $row = $this->db->fetchByAssoc($result);

        if (!empty($row)) {
            return true;
        } else {
            return false;
        }
    }
}

/**
 * Function to allow for Quotas module reporting to display TimePeriods as a filter for timeperiod_id value
 *
 * @see modules/Quotas/vardefs.php
 * @return array Array of TimePeriod names with TimePeriod instance id as key
 */
function getTimePeriodsDropDownForQuotas()
{
	return TimePeriod::get_timeperiods_dom();
}

