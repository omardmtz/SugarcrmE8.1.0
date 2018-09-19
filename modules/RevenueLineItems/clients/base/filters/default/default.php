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
$viewdefs['RevenueLineItems']['base']['filter']['default'] = array(
    'default_filter' => 'all_records',
    'fields' => array(
        'name' => array(),
        'opportunity_name' => array(),
        'account_name' => array(),
        'sales_stage' => array(),
        'probability' => array(),
        'date_closed' => array(),
        'commit_stage' => array(),
        'product_template_name' => array(),
        'category_name' => array(),
        'worst_case' => array(),
        'likely_case' => array(),
        'best_case' => array(),
        'date_entered' => array(),
        'date_modified' => array(),
        'tag' => array(),
        '$owner' => array(
            'predefined_filter' => true,
            'vname' => 'LBL_CURRENT_USER_FILTER',
        ),
        'assigned_user_name' => array(),
        '$favorite' => array(
            'predefined_filter' => true,
            'vname' => 'LBL_FAVORITES_FILTER',
        ),

    ),
);
