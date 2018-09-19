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
 * Implements the annual representation of a time period
 * @api
 */
class AnnualTimePeriod extends TimePeriod implements TimePeriodInterface {

    public function __construct() {
        $this->module_name = 'AnnualTimePeriods';

        parent::__construct();

        //The time period type
        $this->type = TimePeriod::ANNUAL_TYPE;

        //The leaf period type
        $this->leaf_period_type = TimePeriod::QUARTER_TYPE;

        //The number of leaf periods
        $this->leaf_periods = 4;

        $this->periods_in_year = 1;

        //Fiscal is 52-week based, chronological is year based
        $this->is_fiscal = false;

        $this->is_fiscal_year = true;

        //The next period modifier
        $this->next_date_modifier = $this->is_fiscal ? '52 week' : '1 year';

        //The previous period modifier
        $this->previous_date_modifier = $this->is_fiscal ? '-52 week' : '-1 year';

        global $app_strings;
        //The name template
        $this->name_template = $app_strings['LBL_ANNUAL_TIMEPERIOD_FORMAT'];

        //The leaf name template
        $this->leaf_name_template = $app_strings['LBL_QUARTER_TIMEPERIOD_FORMAT'];
    }


    /**
     * getTimePeriodName
     *
     * Returns the timeperiod name.  The TimePeriod base implementation simply returns the $count argument passed
     * in from the code
     *
     * @param $count The timeperiod series count
     * @return string The formatted name of the timeperiod
     */
    public function getTimePeriodName($count, $timeperiod = null)
    {
        $timedate = TimeDate::getInstance();
        $year = $timedate->fromDbDate($this->start_date);

        if(isset($this->currentSettings['timeperiod_fiscal_year']) &&
            $this->currentSettings['timeperiod_fiscal_year'] == 'next_year') {
            $year->modify('+1 year');
        }

        return string_format($this->name_template, array($year->format('Y')));
    }


    /**
     * Returns the formatted chart label data for the timeperiod
     *
     * @param $chartData Array of chart data values
     * @return formatted Array of chart data values where the labels are broken down by the timeperiod's increments
     */
    public function getChartLabels($chartData) {
        $months = array();

        $start = strtotime($this->start_date);
        $end = strtotime($this->end_date);

        while ($start < $end) {
            $val = $chartData;
            $val['label'] = date('Y', $start);
            $months[date('Y', $start)] = $val;
            $start = strtotime('+1 year', $start);
        }

        return $months;
    }


    /**
     * Returns the key for the chart label data for the date closed value
     *
     * @param String The date_closed value in db date format
     * @return String value of the key to use to map to the chart labels
     */
    public function getChartLabelsKey($dateClosed) {
        return date('Y', strtotime($dateClosed));
    }

}
