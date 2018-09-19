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

$viewdefs['Currencies']['base']['view']['list'] = array(
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
                    'width' => 'xlarge',
                ),
                array(
                    'name' => 'iso4217',
                    'label' => 'LBL_LIST_ISO4217',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'symbol',
                    'label' => 'LBL_LIST_SYMBOL',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'conversion_rate',
                    'label' => 'LBL_LIST_RATE',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'status',
                    'label' => 'LBL_STATUS',
                    'enabled' => true,
                    'default' => true,
                    'type' => 'enum',
                    'options' => 'currency_status_dom',
                ),
            ),
        ),
    ),
);
