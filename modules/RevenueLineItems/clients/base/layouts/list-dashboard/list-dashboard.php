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

$viewdefs['RevenueLineItems']['base']['layout']['list-dashboard'] = array(
    'metadata' =>
        array(
            'components' =>
                array(
                    array(
                        'rows' =>
                            array(
                                array(
                                    array(
                                        'view' =>
                                            array(
                                                'type' => 'bubblechart',
                                                'label' => 'LBL_DASHLET_TOP10_SALES_OPPORTUNITIES_NAME',
                                                'filter_duration' => 0,
                                                'visibility' => 'user',
                                            ),
                                        'width' => 12,
                                    ),
                                ),
                            ),
                        'width' => 12,
                    ),
                ),
        ),
    'name' => 'LBL_REVENUE_LINE_ITEMS_LIST_DASHBOARD',
);
