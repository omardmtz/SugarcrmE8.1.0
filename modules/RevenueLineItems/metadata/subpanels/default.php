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

$subpanel_layout = array(
    'top_buttons' => array(
        array('widget_class' => 'SubPanelTopCreateButton'),
        array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Accounts'),
    ),
    'where' => '',
    'fill_in_additional_fields' => true,
    'list_fields' => array(
        'name' => array(
            'vname' => 'LBL_LIST_NAME',
            'widget_class' => 'SubPanelDetailViewLink',
            'width' => '10%',
            'default' => true,
        ),
        'sales_stage' => array(
            'type' => 'enum',
            'vname' => 'LBL_SALES_STAGE',
            'width' => '10%',
            'default' => true,
        ),
        'probability' => array(
            'type' => 'int',
            'vname' => 'LBL_PROBABILITY',
            'width' => '10%',
            'default' => true,
        ),
        'date_closed' => array(
            'type' => 'date',
            'related_fields' => array(
                0 => 'date_closed_timestamp',
            ),
            'vname' => 'LBL_DATE_CLOSED',
            'width' => '10%',
            'default' => true,
        ),
        'commit_stage' => array(
            'type' => 'enum',
            'default' => true,
            'vname' => 'LBL_COMMIT_STAGE_FORECAST',
            'width' => '10%',
        ),
        'quantity' => array(
            'vname' => 'LBL_QUANTITY',
            'width' => '10%',
            'default' => true,
        ),
        'discount_usdollar' => array(
            'usage' => 'query_only',
        ),
        'remove_button' => array(
            'vname' => 'LBL_REMOVE',
            'widget_class' => 'SubPanelRemoveButton',
            'width' => '4%',
        ),
    ),
);
