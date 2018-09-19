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

$dictionary['emails_email_addr_rel'] = array(
    'table' => 'emails_email_addr_rel',
    'comment' => 'Normalization of address fields FROM, TO, CC, and BCC',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'vname' => 'LBL_ID',
            'type' => 'id',
            'required' => true,
            'reportable' => true,
            'duplicate_on_record_copy' => 'no',
            'mandatory_fetch' => true,
        ),
        'email_id' => array(
            'name' => 'email_id',
            'vname' => 'LBL_EMAIL_ID',
            'type' => 'id',
        ),
        'from' => array(
            'name' => 'from',
            'vname' => 'LBL_EMAILS_FROM',
            'type' => 'link',
            'relationship' => 'emails_from',
            'source' => 'non-db',
            'reportable' => false,
        ),
        'to' => array(
            'name' => 'to',
            'vname' => 'LBL_EMAILS_TO',
            'type' => 'link',
            'relationship' => 'emails_to',
            'source' => 'non-db',
            'reportable' => false,
        ),
        'cc' => array(
            'name' => 'cc',
            'vname' => 'LBL_EMAILS_CC',
            'type' => 'link',
            'relationship' => 'emails_cc',
            'source' => 'non-db',
            'reportable' => false,
        ),
        'bcc' => array(
            'name' => 'bcc',
            'vname' => 'LBL_EMAILS_BCC',
            'type' => 'link',
            'relationship' => 'emails_bcc',
            'source' => 'non-db',
            'reportable' => false,
        ),
        'address_type' => array(
            'name' => 'address_type',
            'vname' => 'LBL_ADDRESS_TYPE',
            'type' => 'varchar',
            'len' => 4,
            'required' => true,
            'comment' => 'The role (from, to, cc, bcc) that the entry plays in the email',
        ),
        'email_address_id' => array(
            'name' => 'email_address_id',
            'vname' => 'LBL_EMAIL_ADDRESS_ID',
            'type' => 'id',
            // Only required at send-time.
            'required' => false,
        ),
        'email_addresses' => array(
            'name' => 'email_addresses',
            'vname' => 'LBL_EMAIL_ADDRESS',
            'type' => 'link',
            'relationship' => 'emailaddresses_emailparticipants',
            'source' => 'non-db',
        ),
        'email_address' => array(
            'name' => 'email_address',
            'vname' => 'LBL_EMAIL_ADDRESS',
            'type' => 'relate',
            'rname' => 'email_address',
            'source' => 'non-db',
            'id_name' => 'email_address_id',
            'link' => 'email_addresses',
            'module' => 'EmailAddresses',
        ),
        'invalid_email' => array(
            'name' => 'invalid_email',
            'vname' => 'LBL_INVALID_EMAIL',
            'type' => 'relate',
            'rname' => 'invalid_email',
            'source' => 'non-db',
            'id_name' => 'email_address_id',
            'link' => 'email_addresses',
        ),
        'opt_out' => array(
            'name' => 'opt_out',
            'vname' => 'LBL_OPT_OUT',
            'type' => 'relate',
            'rname' => 'opt_out',
            'source' => 'non-db',
            'id_name' => 'email_address_id',
            'link' => 'email_addresses',
        ),
        'parent_type' => array(
            'name' => 'parent_type',
            'vname' => 'LBL_PARENT_NAME',
            'type' => 'parent_type',
            'dbType' => 'varchar',
            'options' => 'record_type_display_emailparticipants',
            'required' => false,
        ),
        'parent_id' => array(
            'name' => 'parent_id',
            'vname' => 'LBL_PARENT_ID',
            'type' => 'id',
            'comment' => "The bean's ID",
        ),
        'parent_name' => array(
            'name' => 'parent_name',
            'vname' => 'LBL_LIST_RELATED_TO',
            'type' => 'parent',
            'type_name' => 'parent_type',
            'id_name' => 'parent_id',
            'source' => 'non-db',
            'parent_type' => 'record_type_display_emailparticipants',
            'options' => 'record_type_display_emailparticipants',
        ),
        'deleted' => array(
            'name' => 'deleted',
            'type' => 'bool',
            'default' => 0,
        ),
        'date_entered' => array(
            'name' => 'date_entered',
            'vname' => 'LBL_DATE_ENTERED',
            'type' => 'datetime',
        ),
        'date_modified' => array(
            'name' => 'date_modified',
            'vname' => 'LBL_DATE_MODIFIED',
            'type' => 'datetime',
        ),
    ),
    'indices' => array(
        array(
            'name' => 'emails_email_addr_relpk',
            'type' => 'primary',
            'fields' => array(
                'id',
            ),
        ),
        array(
            'name' => 'idx_eearl_email_id',
            'type' => 'index',
            'fields' => array(
                'email_id',
                'address_type',
            ),
        ),
        array(
            'name' => 'idx_eearl_email_address_deleted',
            'type' => 'index',
            'fields' => array(
                'email_address_id',
                'deleted',
            ),
        ),
        array(
            'name' => 'idx_eearl_email_address_role',
            'type' => 'index',
            'fields' => array(
                'email_address_id',
                'address_type',
                'deleted',
            ),
        ),
        array(
            'name' => 'idx_eearl_parent',
            'type' => 'index',
            'fields' => array(
                'parent_type',
                'parent_id',
                'deleted',
            ),
        ),
        array(
            'name' => 'idx_eearl_parent_role',
            'type' => 'index',
            'fields' => array(
                'parent_type',
                'parent_id',
                'address_type',
                'deleted',
            ),
        ),
    ),
    'relationships' => array(
        'emailaddresses_emailparticipants' => array(
            'lhs_module' => 'EmailAddresses',
            'lhs_table' => 'email_addresses',
            'lhs_key' => 'id',
            'rhs_module' => 'EmailParticipants',
            'rhs_table' => 'emails_email_addr_rel',
            'rhs_key' => 'email_address_id',
            'relationship_type' => 'one-to-many',
        ),
    ),
);

$dictionary['email_addr_bean_rel'] = array(
    'table' => 'email_addr_bean_rel',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id',
            'required' => true,
        ),
        'email_address_id' => array(
            'name' => 'email_address_id',
            'type' => 'id',
            'required' => true,
        ),
        'bean_id' => array(
            'name' => 'bean_id',
            'type' => 'id',
            'required' => true,
        ),
        'bean_module' => array(
            'name' => 'bean_module',
            'type' => 'varchar',
            'len' => 100,
            'required' => true,
        ),
        'primary_address' => array(
            'name' => 'primary_address',
            'type' => 'bool',
            'default' => '0',
        ),
        'reply_to_address' => array(
            'name' => 'reply_to_address',
            'type' => 'bool',
            'default' => '0',
        ),
        'date_created' => array(
            'name' => 'date_created',
            'type' => 'datetime',
        ),
        'date_modified' => array(
            'name' => 'date_modified',
            'type' => 'datetime',
        ),
        'deleted' => array(
            'name' => 'deleted',
            'type' => 'bool',
            'default' => 0,
        ),
    ),
    'indices' => array(
        array(
            'name' => 'email_addresses_relpk',
            'type' => 'primary',
            'fields' => array(
                'id',
            ),
        ),
        array(
            'name' => 'idx_email_address_id',
            'type' => 'index',
            'fields' => array(
                'email_address_id',
            ),
        ),
        array(
            'name' => 'idx_bean_id',
            'type' => 'index',
            'fields' => array(
                'bean_id',
                'bean_module',
            ),
        ),
    ),
    'relationships' => array(
    ),
);
