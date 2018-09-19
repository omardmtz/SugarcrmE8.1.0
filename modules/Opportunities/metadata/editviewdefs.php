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

$viewdefs['Opportunities']['EditView'] = array(
    'templateMeta' => array('maxColumns' => '2',
        'widths' => array(
            array('label' => '10', 'field' => '30'),
            array('label' => '10', 'field' => '30')
        ),
        'javascript' => '{$PROBABILITY_SCRIPT}',
    ),
    'panels' =>array (
        'default' =>
        array (

            array (
                array('name'=>'name'),
                'account_name',
            ),
            array (
                'opportunity_type',
                'lead_source',
            ),
            array (
                'campaign_name',
                'next_step',
            ),
            array (
                'description',
            ),
        ),

        'LBL_PANEL_ASSIGNMENT' => array(
            array(
                'assigned_user_name',

                array('name'=>'team_name'),
            ),
        ),
    )
);
