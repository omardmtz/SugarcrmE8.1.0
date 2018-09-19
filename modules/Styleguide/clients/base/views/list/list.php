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
$viewdefs['Styleguide']['base']['view']['list'] = array(
    'panels' => array(
        array(
            'label' => 'LBL_PANEL_1',
            'fields' => array(
                array(
                    'name' => 'name',
                    'fields' => array(
                        'salutation',
                        'first_name',
                        'last_name',
                    ),
                    'link' => true,
                    'label' => 'fullname',
                    'enabled' => true,
                    'default' => true,
                    'sortable' => false,
                ),
                array(
                    'name' => 'title',
                    'label' => 'text',
                    'sortable' => false,
                ),
                array(
                    'name' => 'do_not_call',
                    'label' => 'bool',
                    'sortable' => false,
                ),
                array(
                    'name' => 'email',
                    'label' => 'email',
                    'sortable' => false,
                ),
                array (
                    'name' => 'assigned_user_name',
                    'label' => 'relate',
                    'id' => 'ASSIGNED_USER_ID',
                    'default' => true,
                    'sortable' => false,
                ),
                array(
                    'name' => 'list_price',
                    'label' => 'currency',
                ),
                array(
                    'name' => 'birthdate',
                    'label' => 'date',
                    'sortable' => false,
                ),
                array(
                    'name' => 'date_end',
                    'label' => 'datetimecombo',
                    'sortable' => false,
                ),
            ),
        ),
    ),
);

