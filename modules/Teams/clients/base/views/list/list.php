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
$viewdefs['Teams']['base']['view']['list'] = array(
    'panels' => array(
        array(
            'name' => 'panel_header',
            'label' => 'LBL_PANEL_1',
            'fields' => array(
                array(
                    'name' => 'name',
                    'label' => 'LBL_PRIMARY_TEAM_NAME',
                    'enabled' => true,
                    'default' => true,
                    'sortable' => true,
                ),
                array(
                    'name' => 'description',
                    'label' => 'LBL_DESCRIPTION',
                    'sortable' => false,
                ),
                array(
                    'name' => 'private',
                    'label' => 'LBL_PRIVATE',
                    'enabled' => true,
                    'default' => true,
                    'sortable' => true,
                ),
            ),

        ),
    ),
);
