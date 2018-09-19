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

/**
 * Convert Lead Metadata Definition
 * This file defines which modules are included in the lead conversion process.
 * Within each module we define the following properties:
 *  * module (string): module name (plural)
 *  * required (boolean): is the user required to create or associate an existing record for this module before converting
 *  * duplicateCheck (boolean): should duplicate check be performed for this module?
 *  * contactRelateField (string): field on the contact that links to this module (if set, relationship will be created to contact)
 *  * dependentModules (array): array of module names that this module is dependent on
 *                              if set, this module will be disabled until dependent modules are completed
 *  * fieldMapping (array): how should lead fields be mapped to this module left side is the module and right side is the lead
 */

$viewdefs['Leads']['base']['layout']['convert-main'] = array(
    'modules' =>
    array(
        array(
            'module' => 'Contacts',
            'required' => true,
            'copyData' => true,
            'duplicateCheckOnStart' => true,
            'fieldMapping' =>
            array(
            ),
            'hiddenFields' =>
            array(
                'account_name' => 'Accounts',
            ),
        ),
        array(
            'module' => 'Accounts',
            'required' => true,
            'copyData' => true,
            'duplicateCheckOnStart' => true,
            'duplicateCheckRequiredFields' =>
            array(
                'name',
            ),
            'contactRelateField' => 'account_name',
            'fieldMapping' =>
            array(
                'name' => 'account_name',
                'billing_address_street' => 'primary_address_street',
                'billing_address_city' => 'primary_address_city',
                'billing_address_state' => 'primary_address_state',
                'billing_address_postalcode' => 'primary_address_postalcode',
                'billing_address_country' => 'primary_address_country',
                'shipping_address_street' => 'primary_address_street',
                'shipping_address_city' => 'primary_address_city',
                'shipping_address_state' => 'primary_address_state',
                'shipping_address_postalcode' => 'primary_address_postalcode',
                'shipping_address_country' => 'primary_address_country',
                'phone_office' => 'phone_work',
            ),
        ),
        array(
            'module' => 'Opportunities',
            'required' => false,
            'copyData' => true,
            'duplicateCheckOnStart' => false,
            'duplicateCheckRequiredFields' =>
            array(
                'account_id',
            ),
            'fieldMapping' =>
            array(
                'name' => 'opportunity_name',
                'phone_work' => 'phone_office',
            ),
            'dependentModules' =>
            array(
                'Accounts' =>
                array(
                    'fieldMapping' =>
                    array(
                        'account_id' => 'id',
                    ),
                ),
            ),
            'hiddenFields' =>
            array(
                'account_name' => 'Accounts',
            ),
        ),
    ),
);

