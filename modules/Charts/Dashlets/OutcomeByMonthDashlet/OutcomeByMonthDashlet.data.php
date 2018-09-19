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

 // $Id: OutcomeByMonthDashlet.data.php 16280 2006-08-22 19:47:48Z awu $

$dashletData['OutcomeByMonthDashlet']['searchFields'] = array(
        'obm_date_start' => array(
                'name'  => 'obm_date_start',
                'vname' => 'LBL_DATE_START',
                'type'  => 'datepicker',
            ),
        'obm_date_end' => array(
                'name'  => 'obm_date_end',
                'vname' => 'LBL_DATE_END',
                'type'  => 'datepicker',
            ),
        'obm_ids' => array(
                'name'  => 'obm_ids',
                'vname' => 'LBL_USERS',
                'type'  => 'user_name',
            ),
        );
?>
