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

 // $Id: PipelineBySalesStageDashlet.data.php 16280 2006-08-22 19:47:48Z awu $

$dashletData['PipelineBySalesStageDashlet']['searchFields'] = array(
        'pbss_date_start' => array(
                'name'  => 'pbss_date_start',
                'vname' => 'LBL_CLOSE_DATE_START',
                'type'  => 'datepicker',
            ),

        'pbss_chart_type' => array(
                'name'  => 'pbss_chart_type',
                'vname' => 'LBL_CHART_TYPE',
                'type'  => 'singleenum',
            ),
        'pbss_date_end' => array(
                'name'  => 'pbss_date_end',
                'vname' => 'LBL_CLOSE_DATE_END',
                'type'  => 'datepicker',
            ),
        'pbss_sales_stages' => array(
                'name'  => 'pbss_sales_stages',
                'vname' => 'LBL_SALES_STAGES',
                'type'  => 'enum',
            ),
        );
?>
