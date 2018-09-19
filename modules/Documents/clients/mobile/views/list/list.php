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
$viewdefs['Documents']['mobile']['view']['list'] = array(
    'panels' => array (
        array (
            'label' => 'LBL_PANEL_DEFAULT',
            'fields' => array(
                array(
                    'name' => 'name',
                    'label' => 'LBL_DOC_NAME',
                    'default' => true,
                    'enabled' => true,
                ),
                array(
                    'name' => 'active_date',
                    'label' => 'LBL_DATE',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'category_id',
                    'label' => 'LBL_CATEGORY',
                    'enabled' => true,
                    'default' => true,
                ),
            ),
    	),
	),
);