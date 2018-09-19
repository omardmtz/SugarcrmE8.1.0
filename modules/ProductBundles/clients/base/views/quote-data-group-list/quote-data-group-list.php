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
$viewdefs['ProductBundles']['base']['view']['quote-data-group-list'] = array(
    'selection' => array(
        'type' => 'multi',
        'actions' => array(
            array(
                'type' => 'rowaction',
                'name' => 'edit_row_button',
                'label' => 'LBL_EDIT_BUTTON',
                'tooltip' => 'LBL_EDIT_BUTTON',
                'acl_action' => 'edit',
            ),
            array(
                'type' => 'rowaction',
                'name' => 'delete_row_button',
                'label' => 'LBL_DELETE_BUTTON',
                'tooltip' => 'LBL_DELETE_BUTTON',
                'acl_action' => 'delete',
            ),
        ),
    ),
);
