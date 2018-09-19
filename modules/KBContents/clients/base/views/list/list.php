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
$viewdefs['KBContents']['base']['view']['list'] = array(
    'panels' => array(
        array(
            'label' => 'LBL_PANEL_1',
            'fields' => array(
                array(
                    'name' => 'name',
                    'label' => 'LBL_NAME',
                    'default' => true,
                    'enabled' => true,
                    'link' => true,
                    'related_fields' => array(
                        'kbdocument_id',
                        'kbarticle_id',
                    ),
                ),
                array(
                    'name' => 'language',
                    'label' => 'LBL_LANG',
                    'default' => true,
                    'enabled' => true,
                    'link' => true,
                    'type' => 'enum-config',
                    'key' => 'languages',
                ),
                array(
                    'name' => 'revision',
                    'label' => 'LBL_REVISION',
                    'enabled' => true,
                    'default' => false,
                    'readonly' => true,
                ),
                array(
                    'name' => 'status',
                    'label' => 'LBL_STATUS',
                    'enabled' => true,
                    'default' => true,
                    'type' => 'status',
                    'related_fields' => array(
                        'active_date',
                        'exp_date',
                    ),
                ),
                array(
                    'name' => 'category_name',
                    'label' => 'LBL_CATEGORY_NAME',
                    'enabled' => true,
                    'default' => true,
                    'css_class' => 'overflow-visible',
                    'initial_filter' => 'by_category',
                    'initial_filter_label' => 'LBL_FILTER_CREATE_NEW',
                    'filter_relate' => array(
                        'category_id' => 'category_id',
                    ),
                ),
                array(
                    'name' => 'viewcount',
                    'label' => 'LBL_VIEWED_COUNT',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'date_entered',
                    'label' => 'LBL_DATE_ENTERED',
                    'enabled' => true,
                    'default' => true,
                    'readonly' => true,
                ),
                array(
                    'name' => 'kbsapprover_name',
                    'label' => 'LBL_KBSAPPROVER',
                    'default' => false,
                    'enabled' => true,
                ),
                array(
                    'name' => 'assigned_user_name',
                    'label' => 'LBL_ASSIGNED_TO',
                    'default' => false,
                    'enabled' => true,
                ),
            ),
        ),
    ),
    'orderBy' => array(
        'field' => 'date_modified',
        'direction' => 'desc',
    ),
);
