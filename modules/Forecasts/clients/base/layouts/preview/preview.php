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

$viewdefs['Forecasts']['base']['layout']['preview'] = array(
    'components' => array(
        array(
            'view' => array(
                'type' => 'preview-header',
            ),
        ),
        array(
            'view' => array(
                'type' => 'preview',
            ),
        ),
        array(
            'layout' => array(
                'type' => 'preview-activitystream',
            ),
            'context' => array(
                'module' => 'Forecasts',
                'forceNew' => true,
            ),
        ),
    ),
);
