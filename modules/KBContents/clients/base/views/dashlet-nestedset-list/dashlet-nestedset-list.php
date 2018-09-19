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
$viewdefs['KBContents']['base']['view']['dashlet-nestedset-list'] = array(
    'dashlets' => array(
        array(
            'label' => 'LBL_DASHLET_CATEGORIES_NAME',
            'description' => 'LBL_DASHLET_CATEGORIES_DESCRIPTION',
            'config' => array(
                'last_state' => array(
                    'id' => 'dashlet-nestedset-list-kbcontents',
                ),
                'data_provider' => 'Categories',
                'config_provider' => 'KBContents',
                'root_name' => 'category_root',
                'extra_provider' => array(
                    'module' => 'KBContents',
                    'field' => 'category_id',
                ),
            ),
            'preview' => array(
                'data_provider' => 'Categories',
                'config_provider' => 'KBContents',
                'root_name' => 'category_root',
            ),
            'filter' => array(
                'module' => array(
                    'KBContents',
                    'KBContentTemplates',
                ),
                'view' => array(
                    'record',
                    'records',
                ),
            ),
        ),
    ),
    'config' => array (
    ),
);
