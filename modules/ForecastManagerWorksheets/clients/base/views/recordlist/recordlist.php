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
$viewdefs['ForecastManagerWorksheets']['base']['view']['recordlist'] = array(
    'css_class' => 'forecast-manager-worksheet',
    'favorite' => false,
    'selection' => array(),
    'rowactions' => array(
        'actions' => array(
            /*array(
                'type' => 'rowaction',
                'css_class' => 'btn disabled',
                'tooltip' => 'LBL_PREVIEW',
                'event' => 'list:preview:fire',
                'icon' => 'fa-eye',
                'acl_action' => 'view',
            ),*/
            array(
                'type' => 'rowaction',
                'css_class' => 'btn',
                'tooltip' => 'LBL_HISTORY_LOG',
                'event' => 'list:history_log:fire',
                'icon' => 'fa-exclamation-circle',
                'acl_action' => 'view',
            ),
        ),
    ),
);
