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

require_once('include/EditView/EditView.php');

/**
 * QuickCreate - minimal object creation form
 * @api
 */
class QuickCreate extends EditView {
    /**
     * True if the create being populated via an AJAX call?
     */
    var $viaAJAX = false;

    function process() {
        global $current_user, $timedate;

        parent::process();

        $this->ss->assign('ASSIGNED_USER_ID', $current_user->id);
        $this->ss->assign('TEAM_ID', $current_user->default_team);

        $this->ss->assign('REQUEST', array_merge($_GET, $_POST));
        $this->ss->assign('CALENDAR_LANG', "en");

        $date_format = $timedate->get_cal_date_format();
        $this->ss->assign('USER_DATEFORMAT', '('. $timedate->get_user_date_format().')');
        $this->ss->assign('CALENDAR_DATEFORMAT', $date_format);

		$time_format = $timedate->get_user_time_format();
        $time_separator = ":";
        if(preg_match('/\d+([^\d])\d+([^\d]*)/s', $time_format, $match)) {
           $time_separator = $match[1];
        }
        $t23 = strpos($time_format, '23') !== false ? '%H' : '%I';
        if(!isset($match[2]) || $match[2] == '') {
          $this->ss->assign('CALENDAR_FORMAT', $date_format . ' ' . $t23 . $time_separator . "%M");
        } else {
          $pm = $match[2] == "pm" ? "%P" : "%p";
          $this->ss->assign('CALENDAR_FORMAT', $date_format . ' ' . $t23 . $time_separator . "%M" . $pm);
        }

        $this->ss->assign('CALENDAR_FDOW', $current_user->get_first_day_of_week());
    }
}
?>
