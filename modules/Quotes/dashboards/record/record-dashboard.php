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
return array(
    'metadata' => array(
        'components' => array(
            array(
                'rows' => array(
                    array(
                        array(
                            'view' => array(
                                'type' => 'product-catalog-dashlet',
                                'label' => 'LBL_PRODUCT_CATALOG_DASHLET_NAME',
                            ),
                            'width' => 12,
                        ),
                    ),
                    array(
                        array(
                            'view' => array(
                                'type' => 'planned-activities',
                                'label' => 'LBL_PLANNED_ACTIVITIES_DASHLET',
                            ),
                            'width' => 12,
                        ),
                    ),
                    array(
                        array(
                            'view' => array(
                                'name' => 'active-tasks',
                                'label' => 'LBL_ACTIVE_TASKS_DASHLET',
                            ),
                            'width' => 12,
                        ),
                    ),
                    array(
                        array(
                            'view' => array(
                                'type' => 'history',
                                'label' => 'LBL_HISTORY_DASHLET',
                            ),
                            'width' => 12,
                        ),
                    ),
                    array(
                        array(
                            'view' => array(
                                'type' => 'attachments',
                                'label' => 'LBL_DASHLET_ATTACHMENTS_NAME',
                                'limit' => '5',
                                'auto_refresh' => '0',
                            ),
                            'context' => array(
                                'module' => 'Notes',
                                'link' => 'notes',
                            ),
                            'width' => 12,
                        ),
                    ),
                ),
                'width' => 12,
            ),
        ),
    ),
    'name' => 'LBL_QUOTES_RECORD_DASHBOARD',
);
