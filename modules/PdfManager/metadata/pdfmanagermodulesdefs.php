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


// For $bannedPdfManagerFieldsAndLinks, list of banned Fields and Links by module for PdfManager
/*
        $bannedPdfManagerFieldsAndLinks['moduleName'] = array(
            'fields' => array(
                'fieldName1',
                'fieldName2',
            ),
            'relationships' => array(
                'relationshipName1',
                'relationshipName2',
            ),
        );
*/

$bannedPdfManagerFieldsAndLinks['Accounts'] = array (
    'fields' => array(
        'billing_address_street_2',
        'billing_address_street_3',
        'billing_address_street_4',
        'shipping_address_street_2',
        'shipping_address_street_3',
        'shipping_address_street_4',
    ),
);
$bannedPdfManagerFieldsAndLinks['Contacts'] = array (
    'fields' => array(
        'accept_status_id',
        'accept_status_name',
        'alt_address_street_2',
        'alt_address_street_3',
        'email_and_name1',
        'primary_address_street_2',
        'primary_address_street_3',
    ),
);
$bannedPdfManagerFieldsAndLinks['Employees'] = array (
    'relationships' => array(
        'holidays',
        'oauth_tokens',
    ),
);
$bannedPdfManagerFieldsAndLinks['Leads'] = array (
    'fields' => array(
        'accept_status_id',
        'accept_status_name',
        'alt_address_street_2',
        'alt_address_street_3',
        'primary_address_street_2',
        'primary_address_street_3',
    ),
);
$bannedPdfManagerFieldsAndLinks['Opportunities'] = array (
    'relationships' => array(
        'campaigns',
    ),
);
$bannedPdfManagerFieldsAndLinks['Prospects'] = array (
    'fields' => array(
        'accept_status_id',
        'accept_status_name',
        'alt_address_street_2',
        'alt_address_street_3',
        'primary_address_street_2',
        'primary_address_street_3',
    ),
);
$bannedPdfManagerFieldsAndLinks['Users'] = array (
    'fields' => array(
        'address_city',
        'address_country',
        'address_postalcode',
        'address_state',
        'address_street',
        'date_entered',
        'date_modified',
        'department',
        'description',
        'email1',
        'employee_status',
        'phone_fax',
        'phone_home',
        'messenger_id',
        'messenger_type',
        'phone_mobile',
        'phone_other',
        'status',
        'title',
        'phone_work',
    ),
);

// For $bannedPdfManagerModules, list of banned modules for PdfManager
$bannedPdfManagerModules[] = 'Users';
$bannedPdfManagerModules[] = 'Employees';
