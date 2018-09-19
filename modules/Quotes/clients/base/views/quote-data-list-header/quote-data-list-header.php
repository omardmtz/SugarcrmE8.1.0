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
$viewdefs['Quotes']['base']['view']['quote-data-list-header'] = array(
    'selection' => array(
        'type' => 'multi',
        'actions' => array(
            array(
                'name' => 'group_button',
                'type' => 'rowaction',
                'label' => 'LBL_CREATE_GROUP_SELECTED_BUTTON_LABEL',
                'tooltip' => 'LBL_CREATE_GROUP_SELECTED_BUTTON_TOOLTIP',
                'acl_action' => 'edit',
            ),
            array(
                'name' => 'massdelete_button',
                'type' => 'rowaction',
                'label' => 'LBL_DELETE_SELECTED_LABEL',
                'tooltip' => 'LBL_DELETE_SELECTED_TOOLTIP',
                'acl_action' => 'delete',
            ),
        ),
    ),
);
