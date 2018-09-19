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

$dictionary['tag_bean_rel'] = array(
    'table' => 'tag_bean_rel',
    'relationships' => array(
    ),
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id',
            'required' => true,
        ),
        'tag_id' => array(
            'name' => 'tag_id',
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
        ),
        'date_modified' => array(
            'name' => 'date_modified',
            'type' => 'datetime',
        ),
        'deleted' => array(
            'name' => 'deleted',
            'type' => 'bool',
            'default' => '0',
        ),
    ),
    'indices' => array(
        array(
            'name' => 'tags_bean_relpk',
            'type' => 'primary',
            'fields' => array(
                'id',
            ),
        ),
        array(
            'name' => 'idx_tagsrel_tagid_beanid',
            'type' => 'index',
            'fields' => array(
                'tag_id',
                'bean_id',
            ),
        ),
        array(
            'name' => 'idx_tag_bean_rel_del_bean_module_beanid',
            'type' => 'index',
            'fields' => array(
                'deleted',
                'bean_module',
                'bean_id',
            ),
        ),
    ),
);
