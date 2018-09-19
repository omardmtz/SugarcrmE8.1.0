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

class ForecastsDefaults
{
    /**
     * Sets up the default forecasts config settings
     *
     * @param bool $isUpgrade if this is being called in an upgrade setting
     * @param string $currentVersion if isUpgrade == true, the current version the user has
     * @param string $targetVersion if isUpgrade == true, the version the user is upgrading to
     * @return array
     */
    public static function setupForecastSettings($isUpgrade = false, $currentVersion = "670", $targetVersion = "670")
    {
        $isSetup = false;
        $admin = BeanFactory::newBean('Administration');

        $forecastConfig = self::getDefaults();
        // set is_upgrade
        $forecastConfig['is_upgrade'] = $isUpgrade ? 1 : 0;

        // Any version-specific changes to the defaults can be added here
        // and determined by $currentVersion & $targetVersion
        if ($isUpgrade) {
            // get current settings
            $adminConfig = $admin->getConfigForModule('Forecasts');
            // if admin has already been set up
            if (!empty($adminConfig['is_setup'])) {
                foreach ($adminConfig as $key => $val) {
                    $forecastConfig[$key] = $val;
                }
            }
        }

        foreach ($forecastConfig as $name => $value) {
            $admin->saveSetting('Forecasts', $name, $value, 'base');
        }

        return $forecastConfig;
    }

    /**
     * Returns the default values for Forecasts to use
     *
     * @param int $isSetup pass in if you want is_setup to be 1 or 0, 0 by default
     * @return array default config settings for Forecasts to use
     */
    public static function getDefaults($isSetup = 0)
    {
        // If isSetup happens to get passed as a boolean false, change to 0 for the db
        if ($isSetup === false) {
            $isSetup = 0;
        }

        // default forecast config setup
        return array(
            // this is used to indicate whether the admin wizard should be shown on first run (for admin only, otherwise a message telling a non-admin to tell their admin to set it up)
            'is_setup' => $isSetup,
            // this is used to indicate whether we are coming from an upgraded instance
            'is_upgrade' => 0,
            //TODO-sfa remove this once the ability to map buckets when they get changed is implemented (SFA-215).
            // this is used to indicate whether any user has made commits since the forecasts module has been set up.
            'has_commits' => 0,
            // indicates how data is displayed in the worksheet
            'forecast_by' => 'Opportunities', // options: 'RevenueLineItems' or 'Opportunities'
            // sets whether forecasting timeperiods will be set up based on fiscal or calendar periods, options come from forecasts_timeperiod_types_dom
            'timeperiod_type' => 'chronological', //options:  'chronological' or 'fiscal'
            // the timeperiod intervals users can forecasts over, options come from forecasts_timeperiod_options_dom
            'timeperiod_interval' => TimePeriod::ANNUAL_TYPE,
            // the leaf interval that gets the extra week if main period is fiscal + quaterly, options come from forecasts_timeperiod_leaf_quarterly_options_dom, (first, middle, last)
            'timeperiod_leaf_interval' => TimePeriod::QUARTER_TYPE,
            // the starting point for each fiscal year, this is also used as the starting point to dynamically build the timeperiods
            'timeperiod_start_date' => date("Y") . '-01-01',
            // if timeperiod_start_date is anything other than 01/01/year, the "Fiscal Year" will technically
            // span two years e.g. startDate: 06/01/2013 will span 2013 and 2014. This setting saves if we're calling
            // the Fiscal Year by the Current year (2013) or by the Next year (2014) for labeling purposes
            // options: null, 'current_year', 'next_year'
            'timeperiod_fiscal_year' => null,
            // number of timeperiods forward from the current that are displayed
            'timeperiod_shown_forward' => '2',
            // number of timeperiods in the past from the current that are displayed
            'timeperiod_shown_backward' => '2',
            // used to indicate the available option for grouping opportunities
            'forecast_ranges' => 'show_binary', // options:  'show_binary', 'show_buckets', 'show_custom_buckets'
            // used to reference the app_list_string entry to indicate the commit stage list to use
            'buckets_dom' => 'commit_stage_binary_dom', // options:  commit_stage_binary_dom, commit_stage_dom, commit_stage_extended_dom
            // the defined binary ranges the different buckets opportunities will fall in by default based on their probability
            'show_binary_ranges' => array(
                'include' => array('min' => 70, 'max' => 100),
                'exclude' => array('min' => 0, 'max' => 69)
            ),
            // the defined bucket ranges the different buckets opportunities will fall in by default based on their probability
            'show_buckets_ranges' => array(
                'include' => array('min' => 85, 'max' => 100),
                'upside' => array('min' => 70, 'max' => 84),
                'exclude' => array('min' => 0, 'max' => 69)
            ),
            // the defined custom ranges the different buckets opportunities will fall in by default based on their probability
            'show_custom_buckets_ranges' => array(
                'include' => array('min' => 85, 'max' => 100),
                'upside' => array('min' => 70, 'max' => 84),
                'exclude' => array('min' => 0, 'max' => 69)
            ),
            // contains a comma-separated list of commit_stages that should be included in likely/best/worst totals
            'commit_stages_included' => array('include'),
            //sales_stage_won are all sales_stage opportunity values indicating the opportunity is won
            'sales_stage_won' => array('Closed Won'),
            //sales_stage_lost are all sales_stage opportunity values indicating the opportunity is lost
            'sales_stage_lost' => array('Closed Lost'),
            // whether or not to show the likely column in the forecasts worksheets
            'show_worksheet_likely' => 1,
            // whether or not to show the best column in the forecasts worksheets
            'show_worksheet_best' => 1,
            // whether or not to show the worst column in the forecasts worksheets
            'show_worksheet_worst' => 0,
            // whether or not to show the likely total in the forecasts projected view
            'show_projected_likely' => 1,
            // whether or not to show the best total in the forecasts projected view
            'show_projected_best' => 1,
            // whether or not to show the worst total in the forecasts projected view
            'show_projected_worst' => 0,
            // whether or not to show the commit warnings
            'show_forecasts_commit_warnings' => 1,
            // default enabled worksheet columns
            'worksheet_columns' => self::getWorksheetColumns('pro'),
        );
    }

    /**
     * Given a flavor, returns the proper worksheet columns in an array
     *
     * @param string $flav ent/ult/pro/corp
     * @return array of fields and column names for the worksheet to use
     */
    public static function getWorksheetColumns($flav)
    {
        $cols = array();
        switch($flav) {
            case 'ent':
            case 'ult':
                $cols = array(
                    'commit_stage',
                    'parent_name',  // parent_name is the name of the RLI and the link back to it
                    'opportunity_name',
                    'account_name',
                    'date_closed',
                    'product_template_name',
                    'sales_stage',
                    'probability',
                    'likely_case',
                    'best_case',
                );
                break;

            case 'pro':
            case 'corp':
                $cols = array(
                    'commit_stage',
                    'parent_name', // parent_name is the name of the Opportunity and the link back to it
                    'account_name',
                    'date_closed',
                    'sales_stage',
                    'probability',
                    'likely_case',
                    'best_case',
                );
                break;
        }

        return $cols;
    }

    /**
     * Returns a Forecasts config default given the key for the default
     * @param $key
     * @return mixed
     */
    public static function getConfigDefaultByKey($key)
    {
        $forecastsDefault = self::getDefaults();
        return $forecastsDefault[$key];
    }

    /**
     * Runs SQL to upgrade columns specific for Forecasts modules.  This is a helper function called from silentUpgrade_step2.php and end.php
     * for upgrade script code that runs SQL to update tables.
     *
     * @static
     */
    public static function upgradeColumns()
    {
        $db = DBManagerFactory::getInstance();

        $nonDefaultRates = array();
        $result = $db->query("SELECT id, conversion_rate FROM currencies WHERE deleted = 0 AND id <> '-99'");
        while ($row = $db->fetchByAssoc($result)) {
            $nonDefaultRates[$row['id']] = $row['conversion_rate'];
        }

        //Update the currency_id and base_rate columns for existing records so that we have currency_id and base_rate values set up correctly
        $tables = array('opportunities', 'products', 'forecasts', 'quotes', 'quotas');
        foreach ($tables as $table) {
            $isUsDollar = true; // set false if table has no usdollar fields
            switch ($table) {
                case 'opportunities':
                    $amount = 'amount';
                    $amount_usdollar = 'amount_usdollar';
                    break;
                case 'products':
                    $amount = 'discount_amount';
                    $amount_usdollar = 'discount_amount_usdollar';
                    break;
                case 'forecasts':
                    $isUsDollar = false;
                    break;
                case 'quotes':
                    $amount = 'subtotal';
                    $amount_usdollar = 'subtotal_usdollar';
                    break;
                case 'quotas':
                    $amount = 'amount';
                    $amount_usdollar = 'amount_base_currency';
                    break;
            }
            if ($isUsDollar) {
                //update base_rate where usdollar fields exist, reverse calculate the rate
                $db->query(
                    "UPDATE {$table} SET base_rate = {$amount_usdollar} / {$amount} WHERE base_rate IS NULL AND {$amount_usdollar} IS NOT NULL and {$amount} IS NOT NULL and {$amount} <> 0"
                );
            }
            // Update currency_id to base (-99) with NULL values
            $db->query("UPDATE {$table} SET currency_id='-99' WHERE currency_id IS NULL");

            // Update base_rate for from currency table for NULL values
            foreach ($nonDefaultRates as $id => $rate) {
                $db->query(
                    sprintf(
                        "UPDATE {$table} SET base_rate = %d WHERE base_rate IS NULL AND currency_id IS NOT NULL AND currency_id <> '-99' AND currency_id = '%s'",
                        $rate,
                        $id
                    )
                );
            }

            // Update remaining base_rate for records with NULL values
            $db->query("UPDATE {$table} SET base_rate = 1 WHERE base_rate IS NULL");
        }
    }
}
