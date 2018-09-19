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

class SugarForecasting_Filter_TimePeriodFilter extends SugarForecasting_AbstractForecast
{

    /**
     * Process to get an array of Timeperiods based on system configurations.  It will return the n number
     * of backward timeperiods + current set of timeperiod + n number of future timeperiods.
     *
     * @return array id/name of TimePeriods
     */
    public function process()
    {
        $admin = BeanFactory::newBean('Administration');
        $settings = $admin->getConfigForModule('Forecasts', 'base');
        $forward = $settings['timeperiod_shown_forward'];
        $backward = $settings['timeperiod_shown_backward'];
        $type = $settings['timeperiod_interval'];
        $leafType = $settings['timeperiod_leaf_interval'];
        $timedate = TimeDate::getInstance();

        $timePeriods = array();

        $current = TimePeriod::getCurrentTimePeriod($type);

        //If the current TimePeriod cannot be found for the type, just create one using the current date as a reference point
        if(empty($current)) {
            $current = TimePeriod::getByType($type);
            $current->setStartDate($timedate->getNow()->asDbDate());
        }

        $startDate = $timedate->fromDbDate($current->start_date);

        //Move back for the number of backward TimePeriod(s)
        while($backward-- > 0) {
            $startDate->modify($current->previous_date_modifier);
        }

        $endDate = $timedate->fromDbDate($current->end_date);

        //Increment for the number of forward TimePeriod(s)
        while($forward-- > 0) {
            $endDate->modify($current->next_date_modifier);
        }

        $db = DBManagerFactory::getInstance();
        $sq = new SugarQuery();
        $sq->from(BeanFactory::newBean('TimePeriods'));
        $sq->select(array('id', 'name'));
        $sq->where()
            ->notNull('parent_id')
            ->gte('start_date', $startDate->asDbDate())
            ->lte('start_date', $endDate->asDbDate())
            ->addRaw("coalesce({$db->convert('type', 'length')},0) > 0");
        $sq->orderBy('start_date', 'ASC');

        $beans = $sq->execute();

        foreach ($beans as $row) {
            $timePeriods[$row['id']] = $row['name'];
        }

        return $timePeriods;

    }

}