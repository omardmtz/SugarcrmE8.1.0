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
$viewdefs['ProductTemplates']['base']['view']['resolve-conflicts-list'] = array(
    'favorites' => false,
    'panels' => array(
        array(
            'name' => 'panel_header',
            'fields' => array(
                array(
                    'name' => 'name',
                    'link' => true,
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'type_name',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'category_name',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'status',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'qty_in_stock',
                    'enabled' => true,
                    'default' => false,
                ),
                array(
                    'name' => 'cost_price',
                    'type' => 'currency',
                    'related_fields' => array(
                        'cost_usdollar',
                        'currency_id',
                        'base_rate',
                    ),
                    'currency_field' => 'currency_id',
                    'base_rate_field' => 'base_rate',
                    'enabled' => true,
                    'default' => false,
                ),
                array(
                    'name' => 'list_price',
                    'type' => 'currency',
                    'related_fields' => array(
                        'list_usdollar',
                        'currency_id',
                        'base_rate',
                    ),
                    'currency_field' => 'currency_id',
                    'base_rate_field' => 'base_rate',
                    'enabled' => true,
                    'default' => false,
                ),
                array(
                    'name' => 'discount_price',
                    'type' => 'currency',
                    'related_fields' => array(
                        'discount_usdollar',
                        'currency_id',
                        'base_rate',
                    ),
                    'currency_field' => 'currency_id',
                    'base_rate_field' => 'base_rate',
                    'enabled' => true,
                    'default' => false,
                ),
            ),
        ),
    ),
);
