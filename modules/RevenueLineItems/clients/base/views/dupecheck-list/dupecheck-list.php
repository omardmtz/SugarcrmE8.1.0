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
$viewdefs['RevenueLineItems']['base']['view']['dupecheck-list'] = array(
    'panels' => array(
        array(
            'name' => 'panel_header',
            'label' => 'LBL_PANEL_1',
            'fields' => array(
                array(
                    'name' => 'name',
                    'link' => true,
                    'label' => 'LBL_LIST_NAME',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'opportunity_name',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'account_name',
                    'readonly' => true,
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'sales_stage',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'probability',
                    'enabled' => true,
                    'default' => false,
                ),
                array(
                    'name' => 'date_closed',
                    'enabled' => true,
                    'default' => false,
                ),
                array(
                    'name' => 'commit_stage',
                    'enabled' => true,
                    'default' => false,
                ),
                array(
                    'name' => 'product_template_name',
                    'enabled' => true,
                    'default' => false,
                ),
                array(
                    'name' => 'category_name',
                    'enabled' => true,
                    'default' => false,
                ),
                array(
                    'name' => 'quantity',
                    'enabled' => true,
                    'default' => false,
                ),
                array(
                    'name' => 'likely_case',
                    'required' => true,
                    'type' => 'currency',
                    'related_fields' => array(
                        'currency_id',
                        'base_rate',
                    ),
                    'convertToBase' => true,
                    'currency_field' => 'currency_id',
                    'base_rate_field' => 'base_rate',
                    'enabled' => true,
                    'default' => false,
                ),
                array(
                    'name' => 'best_case',
                    'required' => true,
                    'type' => 'currency',
                    'related_fields' => array(
                        'currency_id',
                        'base_rate',
                    ),
                    'convertToBase' => true,
                    'currency_field' => 'currency_id',
                    'base_rate_field' => 'base_rate',
                    'enabled' => true,
                    'default' => false,
                ),
                array(
                    'name' => 'worst_case',
                    'required' => true,
                    'type' => 'currency',
                    'related_fields' => array(
                        'currency_id',
                        'base_rate',
                    ),
                    'convertToBase' => true,
                    'currency_field' => 'currency_id',
                    'base_rate_field' => 'base_rate',
                    'enabled' => true,
                    'default' => false,
                ),
                array(
                    'name' => 'quote_name',
                    'label' => 'LBL_ASSOCIATED_QUOTE',
                    'related_fields' => array('quote_id'),
                    'readonly' => true,
                    'enabled' => true,
                    'default' => false,
                ),
                array(
                    'name' => 'assigned_user_name',
                    'enabled' => true,
                    'default' => false,
                ),
            ),
        ),
    ),
);
