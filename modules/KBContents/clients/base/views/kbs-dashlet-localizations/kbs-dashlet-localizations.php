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
$viewdefs['KBContents']['base']['view']['kbs-dashlet-localizations'] = array(
    'dashlets' => array(
        array(
            'label' => 'LBL_DASHLET_LOCALIZATIONS_NAME',
            'description' => 'LBL_DASHLET_LOCALIZATIONS_DESCRIPTION',
            'config' => array(
                'module' => 'KBContents',
            ),
            'preview' => array(),
            'filter' => array(
                'module' => array(
                    'KBContents'
                ),
                'view' => 'record',
            ),
        ),
    ),
    'panels' => array(
        array(
            'name' => 'panel_body',
            'columns' => 2,
            'labelsOnTop' => true,
            'placeholders' => true,
            'fields' => array(
                array(
                    'name' => 'limit',
                    'label' => 'LBL_DASHLET_CONFIGURE_DISPLAY_ROWS',
                    'type' => 'enum',
                    'options' => 'dashlet_limit_options',
                ),
            ),
        ),
    ),
);
