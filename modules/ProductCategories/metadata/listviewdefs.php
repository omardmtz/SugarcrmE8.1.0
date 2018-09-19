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

$listViewDefs['ProductCategories'] = array(
    'name' => array(
        'label' => 'LBL_LIST_NAME',
        'width' => '25',
        'link' => true,
        'default' => true,
    ),
    'parent_name' => array(
        'label' => 'LBL_PARENT_CATEGORY',
        'width' => '25',
        'link' => true,
        'default' => true,
    ),
    'description' => array(
        'label' => 'LBL_DESCRIPTION',
        'width' => '50',
        'default' => true,
    ),
    'list_order' => array(
        'label' => 'LBL_LIST_ORDER',
        'width' => '4',
        'default' => true,
    ),
);
