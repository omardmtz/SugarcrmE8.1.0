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
$viewdefs['Notes']['base']['view']['resolve-conflicts-list'] = array(
    'panels' => array(
        array(
            'name' => 'panel_header',
            'label' => 'LBL_PANEL_1',
            'fields' => array(
                array(
                    'name' => 'name',
                    'label' => 'LBL_LIST_SUBJECT',
                    'link' => true,
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'contact_name',
                    'label' => 'LBL_LIST_CONTACT',
                    'link' => true,
                    'id' => 'CONTACT_ID',
                    'module' => 'Contacts',
                    'enabled' => true,
                    'default' => true,
                    'ACLTag' => 'CONTACT',
                    'related_fields' =>
                        array(
                            'contact_id',
                        ),
                ),
                array(
                    'name' => 'parent_name',
                    'label' => 'LBL_LIST_RELATED_TO',
                    'dynamic_module' => 'PARENT_TYPE',
                    'id' => 'PARENT_ID',
                    'link' => true,
                    'enabled' => true,
                    'default' => true,
                    'sortable' => false,
                    'ACLTag' => 'PARENT',
                    'related_fields' =>
                        array(
                            'parent_id',
                            'parent_type',
                        ),
                ),
                array(
                    'name' => 'filename',
                    'label' => 'LBL_LIST_FILENAME',
                    'enabled' => true,
                    'default' => true,
                    'type' => 'file',
                    'related_fields' =>
                        array(
                            'file_url',
                            'id',
                            'file_mime_type',
                        ),
                    'displayParams' =>
                        array(
                            'module' => 'Notes',
                        ),
                ),
                array(
                    'name' => 'created_by_name',
                    'type' => 'relate',
                    'label' => 'LBL_CREATED_BY',
                    'enabled' => true,
                    'default' => false,
                    'related_fields' => array('created_by'),
                ),
            ),
        ),
    ),
);
