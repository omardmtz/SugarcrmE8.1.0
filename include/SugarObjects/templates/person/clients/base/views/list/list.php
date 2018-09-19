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
$module_name = '<module_name>';
$viewdefs[$module_name]['base']['view']['list'] = array(
    'panels' => array(
        array(
            'label' => 'LBL_PANEL_DEFAULT',
            'fields' => array(
                array (
                    'name' => 'name',
                    'type' => 'fullname',
                    'fields' => array(
                        'salutation',
                        'first_name',
                        'last_name',
                    ),
                    'link' => true,
                    'label' => 'LBL_LIST_NAME',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'title',
                    'label' => 'LBL_TITLE',
                    'default' => true,
                    'enabled' => true,
                ),
                array(
                    'name' => 'phone_work',
                    'label' => 'LBL_OFFICE_PHONE',
                    'default' => true,
                    'enabled' => true,
                ),
                array(
                    'name' => 'email',
                    'label' => 'LBL_EMAIL_ADDRESS',
                    'link' => true,
                    'default' => true,
                    'enabled' => true,
                ),
                array(
                    'name' => 'do_not_call',
                    'label' => 'LBL_DO_NOT_CALL',
                    'default' => false,
                    'enabled' => true,
                ),
                array(
                    'name' => 'phone_home',
                    'label' => 'LBL_HOME_PHONE',
                    'default' => false,
                    'enabled' => true,
                ),
                array(
                    'name' => 'phone_mobile',
                    'label' => 'LBL_MOBILE_PHONE',
                    'default' => false,
                    'enabled' => true,
                ),
                array(
                    'name' => 'phone_other',
                    'label' => 'LBL_WORK_PHONE',
                    'default' => false,
                    'enabled' => true,
                ),
                array(
                    'name' => 'phone_fax',
                    'label' => 'LBL_FAX_PHONE',
                    'default' => false,
                    'enabled' => true,
                ),
                array(
                    'name' => 'primary_address_street',
                    'label' => 'LBL_PRIMARY_ADDRESS_STREET',
                    'default' => false,
                    'enabled' => true,
                ),
                array(
                    'name' => 'primary_address_city',
                    'label' => 'LBL_PRIMARY_ADDRESS_CITY',
                    'default' => false,
                    'enabled' => true,
                ),
                array(
                    'name' => 'primary_address_state',
                    'label' => 'LBL_PRIMARY_ADDRESS_STATE',
                    'default' => false,
                    'enabled' => true,
                ),
                array(
                    'name' => 'primary_address_postalcode',
                    'label' => 'LBL_PRIMARY_ADDRESS_POSTALCODE',
                    'default' => false,
                    'enabled' => true,
                ),
                array(
                    'name' => 'date_modified',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'date_entered',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'created_by_name',
                    'label' => 'LBL_CREATED',
                    'default' => false,
                    'enabled' => true,
                    'readonly' => true,
                ),
                array(
                    'name' => 'team_name',
                    'label' => 'LBL_TEAM',
                    'default' => false,
                    'enabled' => true,
                ),
            ),
        ),
    ),
);
