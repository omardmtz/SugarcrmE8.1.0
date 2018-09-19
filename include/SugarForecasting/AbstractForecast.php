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

abstract class SugarForecasting_AbstractForecast extends SugarForecasting_AbstractForecastArgs implements SugarForecasting_ForecastProcessInterface
{
    /**
     * Where we store the data we want to use
     *
     * @var array
     */
    protected $dataArray = array();

    /**
     * Return the data array
     *
     * @return array
     */
    public function getDataArray()
    {
        return $this->dataArray;
    }

    /**
     * Get the months for the current time period
     *
     * @param $timeperiod_id
     * @return array
     */
    protected function getTimePeriodMonths($timeperiod_id)
    {
        /* @var $timeperiod TimePeriod */
        $timeperiod = BeanFactory::getBean('TimePeriods', $timeperiod_id);

        $months = array();

        $start = strtotime($timeperiod->start_date);
        $end = strtotime($timeperiod->end_date);
        while ($start < $end) {
            $months[] = date('F Y', $start);
            $start = strtotime("+1 month", $start);
        }

        return $months;
    }

    /**
     * Get the direct reportees for a user.
     *
     * @param $user_id
     * @return array
     */
    protected function getUserReportees($user_id)
    {
        $db = DBManagerFactory::getInstance();
        $sql = sprintf("SELECT id, user_name, first_name, last_name, title, reports_to_id FROM users WHERE (reports_to_id = '%s' OR id = '%s') AND " . User::getLicensedUsersWhere(), $user_id, $user_id);

        $result = $db->query($sql);
        $reportees = array();

        while ($row = $db->fetchByAssoc($result)) {
            $reportees[$row['id']] = $row['user_name'];

            //If the row matches the manager user reverse the order of the array so that the manager is first
            if ($row['id'] == $user_id) {
                $reportees = array_reverse($reportees, true);
            }
        }

        return $reportees;
    }

    /**
     * Get the passes in users reportee's who have a forecast for the passed in time period
     *
     * @param string $user_id           A User Id
     * @param string $timeperiod_id     The Time period you want to check for
     * @return array
     */
    public function getUserReporteesWithForecasts($user_id, $timeperiod_id)
    {

        $db = DBManagerFactory::getInstance();
        $return = array();
        $query = "SELECT distinct users.user_name FROM users, forecasts
                WHERE forecasts.timeperiod_id = '" . $timeperiod_id . "' AND forecasts.deleted = 0
                AND users.id = forecasts.user_id AND users.deleted = 0 AND (users.reports_to_id = '" . $user_id . "')";

        $result = $db->query($query, true, " Error fetching user's reporting hierarchy: ");
        while (($row = $db->fetchByAssoc($result)) != null) {
            $return[] = $row['user_name'];
        }

        return $return;
    }

    /**
     * Utility method to convert a date time string into an ISO data time string for Sidecar usage.
     *
     * @param string $dt_string     Date Time value to from the db to convert into ISO format for Sidecar to consume
     * @return string               The ISO version of the string
     */
    protected function convertDateTimeToISO($dt_string)
    {
        $timedate = TimeDate::getInstance();
        if ($timedate->check_matching_format($dt_string, TimeDate::DB_DATETIME_FORMAT) === false) {
            $dt_string = $timedate->to_db($dt_string);
        }
        global $current_user;
        return $timedate->asIso($timedate->fromDb($dt_string), $current_user);
    }
}
