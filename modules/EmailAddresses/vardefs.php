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

$dictionary['EmailAddress'] = array(
    'table' => 'email_addresses',
    'audited' => true,
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id',
            'vname' => 'LBL_EMAIL_ADDRESS_ID',
            'required' => true,
        ),
        'email_address' => array(
            'name' => 'email_address',
            'type' => 'varchar',
            'vname' => 'LBL_EMAIL_ADDRESS',
            'length' => 100,
            'required' => true,
            'audited' => true,
            'pii' => true,
        ),
        'email_address_caps' => array(
            'name' => 'email_address_caps',
            'type' => 'varchar',
            'vname' => 'LBL_EMAIL_ADDRESS_CAPS',
            'length' => 100,
            'required' => true,
            'reportable' => false,
            'audited' => true,
            'pii' => true,
        ),
        'invalid_email' => array(
            'name' => 'invalid_email',
            'type' => 'bool',
            'default' => 0,
            'vname' => 'LBL_INVALID_EMAIL',
            'audited' => true,
        ),
        'opt_out' => array(
            'name' => 'opt_out',
            'type' => 'bool',
            'default' => 0,
            'vname' => 'LBL_OPT_OUT',
            'audited' => true,
        ),
        'date_created' => array(
            'name' => 'date_created',
            'type' => 'datetime',
            'vname' => 'LBL_DATE_CREATE',
        ),
        'date_modified' => array(
            'name' => 'date_modified',
            'type' => 'datetime',
            'vname' => 'LBL_DATE_MODIFIED',
        ),
        'deleted' => array(
            'name' => 'deleted',
            'type' => 'bool',
            'default' => 0,
            'vname' => 'LBL_DELETED',
        ),
        'email_participants' => array(
            'name' => 'email_participants',
            'vname' => 'LBL_EMAIL_PARTICIPANTS',
            'type' => 'link',
            'relationship' => 'emailaddresses_emailparticipants',
            'source' => 'non-db',
            'reportable' => false,
        ),
    ),
    'indices' => array(
        array(
            'name' => 'email_addressespk',
            'type' => 'primary',
            'fields' => array(
                'id',
            ),
        ),
        array(
            'name' => 'idx_ea_caps_opt_out_invalid',
            'type' => 'index',
            'fields' => array(
                'email_address_caps',
                'opt_out',
                'invalid_email',
            ),
        ),
        array(
            'name' => 'idx_ea_opt_out_invalid',
            'type' => 'index',
            'fields' => array(
                'email_address',
                'opt_out',
                'invalid_email',
            ),
        ),
    ),
);

VardefManager::createVardef('EmailAddresses', 'EmailAddress', []);
