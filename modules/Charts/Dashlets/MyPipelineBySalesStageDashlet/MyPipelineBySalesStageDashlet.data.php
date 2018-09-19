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

 // $Id: MyPipelineBySalesStageDashlet.data.php 16280 2006-08-22 19:47:48Z awu $

$dashletData['MyPipelineBySalesStageDashlet']['searchFields'] = array(
        'mypbss_date_start' => array(
                'name'  => 'mypbss_date_start',
                'vname' => 'LBL_CLOSE_DATE_START',
                'type'  => 'datepicker',
            ),

        'mypbss_chart_type' => array(
                'name'  => 'mypbss_chart_type',
                'vname' => 'LBL_CHART_TYPE',
                'type'  => 'singleenum',
            ),
        'mypbss_date_end' => array(
                'name'  => 'mypbss_date_end',
                'vname' => 'LBL_CLOSE_DATE_END',
                'type'  => 'datepicker',
            ),
        'mypbss_sales_stages' => array(
                'name'  => 'mypbss_sales_stages',
                'vname' => 'LBL_SALES_STAGES',
                'type'  => 'enum',
            ),
        
        );
?>
