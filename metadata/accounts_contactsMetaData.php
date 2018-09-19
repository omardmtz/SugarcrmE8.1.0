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
$dictionary['accounts_contacts'] = array (
    'table' => 'accounts_contacts',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id',
        ),
        'contact_id' => array(
            'name' => 'contact_id',
            'type' => 'id',
        ),
        'account_id' => array(
            'name' => 'account_id',
            'type' => 'id',
        ),
        'date_modified' => array (
            'name' => 'date_modified',
            'type' => 'datetime',
        ),
        'primary_account' => array(
            'name' => 'primary_account',
            'type' => 'bool',
            'default' => '0',
        ),
        'deleted' => array(
            'name' => 'deleted',
            'type' => 'bool',
            'len' => '1',
            'required' => false,
            'default' => '0',
        ),
    ),
    'indices' => array (
        array(
            'name' =>'accounts_contactspk',
            'type' =>'primary',
            'fields'=>array('id'),
        ),
        array(
            'name' => 'idx_account_contact',
            'type'=>'alternate_key',
            'fields'=>array('account_id','contact_id'),
        ),
        array(
            'name' => 'idx_contid_del_accid',
            'type' => 'index',
            'fields'=> array('contact_id', 'deleted', 'account_id'),
        ),
    ),
    'relationships' => array (
        'accounts_contacts' => array(
            'lhs_module' => 'Accounts',
            'lhs_table' => 'accounts',
            'lhs_key' => 'id',
            'rhs_module' => 'Contacts',
            'rhs_table' => 'contacts',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'accounts_contacts',
            'join_key_lhs' => 'account_id',
            'join_key_rhs' => 'contact_id',
            'primary_flag_column' => 'primary_account',
            'primary_flag_side' => 'rhs',
            'primary_flag_default' => true,
        ),
    ),
);
