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
global $current_user;
$data = array();
$sc = Phaser::singleton();
 $dbgoal = $sc->getInstanceProperty('annual_revenue_goal');
if (!is_null($dbgoal) && is_int(intval($dbgoal))) {
    $goal=intval($dbgoal);
}else{
    $goal = 1000000;
}
$dayOfTheYear=date('z',strtotime('today'));
$goalsper = array(
        'Week'=>$goal/52,
        'Month'=>$goal/12,
        'Year'=>$goal,
        'YTD'=>$goal*($dayOfTheYear/365)
    );
$today = getdate(time());

$firstDayThisMonth=date('Y-m-d',strtotime('first day of this month'));
$lastDayThisMonth=date('Y-m-d',strtotime('last day of this month'));


$firstDayThisWeek=date('Y-m-d',strtotime('last monday'));
$lastDayThisWeek=date('Y-m-d',strtotime('next sunday'));

$data = array();
$queries = array(
        'Week'=>'SELECT sum(amount) val FROM opportunities where sales_stage = \'Closed Won\' and deleted=0 and date_closed >=\''.$firstDayThisWeek.'\' and date_closed <=\''.$lastDayThisWeek.'\'',
        'Month'=>'SELECT sum(amount) val FROM opportunities where sales_stage = \'Closed Won\' and deleted=0 and date_closed >=\''.$firstDayThisMonth.'\' and date_closed <=\''.$lastDayThisMonth.'\'',
        'Year'=>'SELECT sum(amount) val FROM opportunities where sales_stage = \'Closed Won\' and deleted=0 and date_closed >=\''.$today['year'].'-01-01\' and date_closed <=\''.$today['year'].'-12-31\'',
        'YTD'=>'SELECT sum(amount) val FROM opportunities where sales_stage = \'Closed Won\' and deleted=0 and date_closed >=\''.$today['year'].'-01-01\' and date_closed <=\''.$today['year'].'-12-31\'',
);

$styles = array(
);

$results = array();
    foreach ($queries as $queryName => $query) {
        $result = $GLOBALS['db']->query($query);
        var_dump($query);
        while($row = $GLOBALS['db']->fetchByAssoc($result)){
            if(isset($row['val'])) {
                var_dump($row);
                $results[$queryName]['committed'] = floatval($row['val']);
                $results[$queryName]['goal'] = $goalsper[$queryName];
            } else {
                $results[$queryName]['committed'] = floatval(0);
                $results[$queryName]['goal'] = $goalsper[$queryName];
            }
        }
    }
$data = $results;
