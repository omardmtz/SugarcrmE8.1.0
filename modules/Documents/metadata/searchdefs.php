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


$searchdefs['Documents'] = array(
    'templateMeta' => array('maxColumns' => '3', 'maxColumnsBasic' => '4',
        'widths' => array('label' => '10', 'field' => '30'),
    ),
    'layout' => array(
        'basic_search' => array(
            'document_name',
            array ('name' => 'favorites_only','label' => 'LBL_FAVORITES_FILTER','type' => 'bool',),
            'filename' => array (
                'type' => 'varchar',
                'label' => 'LBL_FILENAME',
                'width' => '10%',
                'default' => false,
                'enabled' => false,
                'name' => 'filename',
            ),
        ),
        'advanced_search' => array(
            'document_name',
            'category_id',
            'subcategory_id',
            'active_date',
            'exp_date',
            'assigned_user_id' =>
                array (
                    'name' => 'assigned_user_id',
                    'type' => 'enum',
                    'label' => 'LBL_ASSIGNED_TO',
                    'function' =>
                        array (
                            'name' => 'get_user_array',
                            'params' =>
                                array (
                                    0 => false,
                                ),
                        ),
                    'default' => true,
                    'width' => '10%',
                ),
            array ('name' => 'favorites_only','label' => 'LBL_FAVORITES_FILTER','type' => 'bool',),
            'filename' => array (
                'type' => 'varchar',
                'label' => 'LBL_FILENAME',
                'width' => '10%',
                'default' => false,
                'enabled' => false,
                'name' => 'filename',
            ),
        ),
    ),
);
?>
