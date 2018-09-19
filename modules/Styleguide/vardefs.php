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

$dictionary['Styleguide'] = array(
    'table' => 'styleguide',
    'fields' => array (
        'parent_type' => array(
            'name' => 'parent_type',
            'vname' => 'LBL_PARENT_TYPE',
            'type' => 'parent_type',
            'dbType' => 'varchar',
            'group' => 'parent_name',
            'options' => 'parent_type_display',
            'len'=> '255',
            'studio' => array('wirelesslistview'=>false),
            'comment' => 'Sugar module the Note is associated with',
            'help' => 'Sugar module the Note is associated with.',
        ),
        'parent_id' => array(
            'name' => 'parent_id',
            'vname' => 'LBL_PARENT_ID',
            'type' => 'id',
            'required' => false,
            'reportable' => true,
            'comment' => 'The ID of the Sugar item specified in parent_type',
            'help' => 'The ID of the Sugar item specified in parent_type.',
        ),
        'parent_name' => array(
            'name'=> 'parent_name',
            'parent_type' => 'record_type_display',
            'type_name' => 'parent_type',
            'id_name' => 'parent_id',
            'vname' => 'LBL_PARENT_OF',
            'type' => 'parent',
            'source' => 'non-db',
            'options' => 'record_type_display_notes',
            'studio' => true,
            'help' => 'The name of the Sugar item specified in parent_type.',
            'sortable' => false,
        ),
        'file_mime_type' => array (
            'name' => 'file_mime_type',
            'vname' => 'LBL_FILE_MIME_TYPE',
            'type' => 'varchar',
            'len' => '100',
            'comment' => 'Attachment MIME type',
            'help' => 'Attachment MIME type.',
            'importable' => false,
        ),
        'file_url' => array(
            'name' => 'file_url',
            'vname' => 'LBL_FILE_URL',
            'type' => 'varchar',
            'source' => 'non-db',
            'reportable' => false,
            'comment' => 'Path to file (can be URL)',
            'help' => 'Path to file (can be URL).',
            'importable' => false,
        ),
        'filename' => array (
            'name' => 'filename',
            'vname' => 'LBL_FILENAME',
            'type' => 'file',
            'dbType' => 'varchar',
            'len' => '255',
            'reportable' => true,
            'comment' => 'File name associated with the note (attachment)',
            'help' => 'File name associated with the note (attachment).',
            'importable' => false,
        ),
        'description' => array (
            'name' => 'description',
            'vname' => 'LBL_NOTE_STATUS',
            'type' => 'text',
            'comment' => 'Full text of the styleguide page',
            'help' => 'This is an example help for a normal textarea box with some extensive information.',
        ),
        'picture' => array (
            'name' => 'picture',
            'vname' => 'LBL_PICTURE_FILE',
            'type' => 'image',
            'dbtype' => 'varchar',
            'len' => 255,
            'comment' => 'Image to be used as an avatar',
            'help' => 'Click to edit it.',
        ),
        'do_not_call' => array (
            'name' => 'do_not_call',
            'vname' => 'LBL_BOOLEAN',
            'type' => 'bool',
            'comment' => 'Whether or not to allow calls to contact',
            'default' => 0,
            'help' => 'Don\'t check this one.',
        ),
        'currency_id' => array(
            'name' => 'currency_id',
            'dbType' => 'id',
            'vname' => 'LBL_CURRENCY_ID',
            'type' => 'currency_id',
            'function' => 'getCurrencies',
            'function_bean' => 'Currencies',
            'required' => false,
            'reportable' => false,
            'default' => '-99',
            'comment' => 'Currency of the product',
        ),
        'list_price' =>  array(
            'name' => 'list_price',
            'vname' => 'LBL_LIST_PRICE',
            'type' => 'currency',
            'len' => '26,6',
            'audited' => true,
            'comment' => 'List price of product ("List" in Quote)',
            'help' => 'List price of product ("List" in Quote).',
        ),
        'website' => array (
            'name' => 'website',
            'vname' => 'LBL_WEBSITE',
            'type' => 'url',
            'dbType' => 'varchar',
            'len' => 255,
            'link_target' => '_blank',
            'comment' => 'URL of website for the company',
            'help' => 'URL of website for the company.',
        ),
        'phone_home' => array (
            'name' => 'phone_home',
            'vname' => 'LBL_PHONE_HOME',
            'type' => 'phone',
            'dbType' => 'varchar',
            'len' => 100,
            'comment' => 'Don\'t call to this number',
            'help' => 'Don\'t call to this number.',
        ),
        'birthdate' => array(
            'name' => 'birthdate',
            'vname' => 'LBL_BIRTHDATE',
            'type' => 'date',
            'comment' => 'The birthdate of the contact',
            'help' => 'The birthdate of the contact. Also, more information can be provided.',
        ),
        'secret_password' => array(
            'name' => 'secret_password',
            'vname' => 'LBL_PASSWORD',
            'type' => 'password',
            'dbType' => 'varchar',
            'len' => 255,
            'comment' => 'Your password will be saved securely',
            'help' => 'Your password will be saved securely!',
        ),
        'user_email' => array(
            'name' => 'user_email',
            'vname' => 'LBL_EMAIL',
            'type' => 'varchar',
            'len' => 100,
            'sortable' => false,
            'comment' => 'Emails can also have a big help description to further enhance the user experience',
            'help' => 'Emails can also have a big help description to further enhance the user experience.',
        ),
        'date_start' => array (
            'name' => 'date_start',
            'vname' => 'LBL_DATE',
            'type' => 'datetimecombo',
            'dbType' => 'datetime',
            'comment' => 'Date of start of meeting',
            'help' => 'Date of start of meeting.',
        ),
        'title' => array (
            'name' => 'title',
            'vname' => 'LBL_TITLE',
            'type' => 'default',
            'dbType' => 'varchar',
            'len' => 255,
            'comment' => 'Title of the Styleguide page',
            'help' => 'This is an example help for a normal input box.',
        ),
        'radio_button_group' => array(
            'name' => 'radio_button_group',
            'vname' => 'LBL_BUTTON_GROUP',
            'type' => 'radioenum',
            'dbType' => 'varchar',
            'len' => 255,
            'help' => 'Radio button help example.',
        ),
    ),
    'indices' => array (
    ),
    'relationships' => array (
    ),
    'acls' => array(
        'SugarACLAdminOnly' => true,
    ),
    // @TODO Fix the Default and Basic SugarObject templates so that Basic
    // implements Default. This would allow the application of various
    // implementations on Basic without forcing Default to have those so that
    // situations like this - implementing taggable - doesn't have to apply to
    // EVERYTHING. Since there is no distinction between basic and default for
    // sugar objects templates yet, we need to forecefully remove the taggable
    // implementation fields. Once there is a separation of default and basic
    // templates we can safely remove these as this module will implement
    // default instead of basic.
    'ignore_templates' => array(
        'taggable',
    ),
);

if (!class_exists('VardefManager')){
}
VardefManager::createVardef('Styleguide', 'Styleguide', array('default', 'person', 'assignable'));
