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

$viewdefs['Home']['base']['layout']['search-dashboard'] = array(
    'metadata' => array(
        'components' => array(
            array(
                'rows' => array(
                    array(
                        array(
                            'view' =>
                                array(
                                    'type' => 'search-facet',
                                    'facet_id' => 'assigned_user_id',
                                    'custom_toolbar' => 'no',
                                    'label' => 'LBL_FACET_ASSIGNED_TO_ME',
                                    'ui_type' => 'single',
                                ),
                            'width' => 12,
                        ),
                        array(
                            'view' =>
                                array(
                                    'type' => 'search-facet',
                                    'facet_id' => 'favorite_link',
                                    'custom_toolbar' => 'no',
                                    'label' => 'LBL_FACET_MY_FAVORITES',
                                    'ui_type' => 'single',
                                ),
                            'width' => 12,
                        ),
                        array(
                            'view' =>
                                array(
                                    'type' => 'search-facet',
                                    'facet_id' => 'created_by',
                                    'custom_toolbar' => 'no',
                                    'label' => 'LBL_FACET_CREATED_BY_ME',
                                    'ui_type' => 'single',
                                ),
                            'width' => 12,
                        ),
                        array(
                            'view' =>
                                array(
                                    'type' => 'search-facet',
                                    'facet_id' => 'modified_user_id',
                                    'custom_toolbar' => 'no',
                                    'label' => 'LBL_FACET_MODIFIED_BY_ME',
                                    'ui_type' => 'single',
                                ),
                            'width' => 12,
                        ),
                        array(
                            'view' =>
                                array(
                                    'type' => 'search-facet',
                                    'label' => 'LBL_FACET_MODULES',
                                    'facet_id' => 'modules',
                                    'ui_type' => 'multi',
                                    'custom_toolbar' => array(
                                        "buttons" => array(
                                            array(
                                                "type" => "dashletaction",
                                                "css_class" => "dashlet-toggle btn btn-invisible minify",
                                                "icon" => "fa-chevron-up",
                                                "action" => "toggleMinify",
                                                "tooltip" => "LBL_DASHLET_TOGGLE",
                                            ),
                                        ),
                                    ),
                                ),
                            'width' => 12,
                        ),
                    ),
                ),
                'width' => 12,
            ),
        ),
    ),
    'name' => 'LBL_FACETS_DASHBOARD_TITLE',
    'css_class' => 'facets-dashboard',
    'drag_and_drop' => false,
);
