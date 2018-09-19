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
$viewdefs['Styleguide']['base']['view']['dashlet-chart'] = array(
    'dashlets' => array(
        array(
            'label' => 'Chart Dashlet Example',
            'description' => 'LBL_DASHLET_OPPORTUNITY_DESCRIPTION',
            'filter' => array(
                'module' => array(
                    'Styleguide',
                ),
                'view' => 'record'
            ),
            'config' => array(),
            'preview' => array(),
        ),
    ),
);
