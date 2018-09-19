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
 * TimePeriodInterface.php
 *
 * interface definition for TimePeriod subclasses used by the forecasting components
 */
interface TimePeriodInterface
{
    public function getLengthInDays();

    public function getNextTimePeriod();

    public function getPreviousTimePeriod();

    public function setStartDate($start_date=null);

    /**
     * Returns the formatted chart labels for the chart data supplied
     *
     * @see include/SugarForecasting/Chart/Individual.php
     * @param $chartData Array of chart data based on the incoming parameters sent
     * @return mixed Array of formatted chart data with the corresponding time intervals
     */
    public function getChartLabels($chartData);

    /**
     * Returns the chart label key for the data set given the closed date of a record
     *
     * @see include/SugarForecasting/Chart/Individual.php
     * @param $dateClosed Database date format (2012-01-01) of date closed
     * @return String of the key used for the data set
     */
    public function getChartLabelsKey($dateClosed);
}
?>