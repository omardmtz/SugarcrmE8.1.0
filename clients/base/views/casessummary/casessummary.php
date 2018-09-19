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
$viewdefs['base']['view']['casessummary'] = array(
    'dashlets' => array(
        array(
            'label' => 'LBL_CASE_SUMMARY_CHART',
            'description' => 'LBL_CASE_SUMMARY_CHART_DESC',
            'config' => array(),
            'preview' => array(),
            'filter' => array(
                'module' => array(
                    'Accounts',
                ),
                'view' => 'record',
            ),
            'fields' => array(
                'name',
                'account_id',
                'id',
                'status',
                'my_favorite',
            ),
        ),
    ),
);
