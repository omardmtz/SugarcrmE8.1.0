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

$dictionary['Subscription'] = array(
    'table' => 'subscriptions',
    'fields' => array(
        // Set unnecessary fields from Basic to non-required/non-db.
        'name' => array (
            'name' => 'name',
            'type' => 'varchar',
            'required' => false,
            'source' => 'non-db',
        ),

        'description' => array (
            'name' => 'description',
            'type' => 'varchar',
            'required' => false,
            'source' => 'non-db',
        ),

        // Add table columns.
        'parent_type' => array(
            'name'     => 'parent_type',
            'type'     => 'varchar',
            'len'      => 100,
            'required' => true,
        ),

        'parent_id' => array(
            'name'     => 'parent_id',
            'type'     => 'id',
            'len'      => 36,
            'required' => true,
        ),
    ),
    'indices' => array(
        array(
            'name' => 'subscription_parent',
            'type' => 'index',
            'fields' => array('parent_id'),
        ),
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

VardefManager::createVardef('ActivityStream/Subscriptions', 'Subscription', array('basic'));
