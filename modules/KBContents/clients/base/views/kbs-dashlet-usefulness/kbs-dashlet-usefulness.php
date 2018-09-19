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

$viewdefs['KBContents']['base']['view']['kbs-dashlet-usefulness'] = array(
    'dashlets' => array(
        array(
            'label' => 'LBL_DASHLET_USEFULNESS_NAME',
            'description' => 'LBL_DASHLET_USEFULNESS_DESC',
            'config' => array(
                'module' => 'KBContents'
            ),
            'preview' => array(),
            'filter' => array(
                'module' => array(
                    'KBContents',
                ),
                'view' => 'record',
            ),
        ),
    ),
    'chart' => array(
        'name' => 'chart',
        'label' => 'Chart',
        'type' => 'chart',
        'view' => 'detail'
    ),
);
