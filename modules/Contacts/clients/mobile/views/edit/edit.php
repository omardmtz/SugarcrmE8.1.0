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
/*********************************************************************************
 * $Id$
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

$viewdefs['Contacts']['mobile']['view']['edit'] = array(
    'templateMeta' => array(
        'maxColumns' => '1',
        'widths' => array(
            array('label' => '10', 'field' => '30'),
        ),
    ),
    'panels' => array(
        array(
            'label' => 'LBL_PANEL_DEFAULT',
            'fields' => array(
                array(
                    'name' => 'first_name',
                    'customCode' => '{html_options name="salutation" options=$fields.salutation.options selected=$fields.salutation.value}&nbsp;<input name="first_name" size="15" maxlength="25" type="text" value="{$fields.first_name.value}">',
                    'displayParams' => array(
                        'wireless_edit_only' => true,
                    ),
                ),
                array(
                    'name' => 'last_name',
                    'displayParams' => array(
                        'required' => true,
                        'wireless_edit_only' => true,
                    ),
                ),
                'title',
                'phone_work',
                'phone_mobile',
                'phone_home',
                'email',
                'primary_address_street',
                'primary_address_city',
                'primary_address_state',
                'primary_address_postalcode',
                'primary_address_country',
                'account_name',
                'assigned_user_name',

                'team_name',
            ),
        )
    )
);
?>
