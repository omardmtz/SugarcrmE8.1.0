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
/*********************************************************************************
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
$viewdefs['Products']['mobile']['view']['edit'] = array(
    'templateMeta' => array(
        'maxColumns' => '1',
        'widths' => array(
            array('label' => '10', 'field' => '30'),
        ),
    ),
    'panels' => array(
        array(
            'fields' => array(
                array(
                    'name' => 'name',
                    'required' => true,
                ),
                'product_template_name',
                'status',
                'account_name',
                'quote_name',
                'quantity',
                array(
                    'name' => 'discount_price',
                ),
                array(
                    'name' => 'cost_price',
                    'readonly' => true,
                ),
                array(
                    'name' => 'list_price',
                    'readonly' => true,
                ),
                array(
                    'name' => 'mft_part_num',
                    'readonly' => true,
                ),
                'assigned_user_name',
                'team_name',
            ),
        ),
    ),
);
