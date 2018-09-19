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

$viewdefs['base']['layout']['help'] = array(
    'components' => array(
        array(
            'view' => 'help-header',
        ),
        array(
            'layout' => array(
                'type' => 'base',
                'css_class' => 'helplet-list-container',
                'components' => array(
                    array(
                        'view' => 'helplet',
                    ),
                    array(
                        'view' => array(
                            'type' => 'helplet',
                            'resources' => array(
                                'sugar_university' => array(
                                    'color' => 'blue',
                                    'icon' => 'fa-book',
                                    'url' => 'http://university.sugarcrm.com/',
                                    'link' => 'LBL_LEARNING_RESOURCES_SUGAR_UNIVERSITY_LINK',
                                ),
                                'community' => array(
                                    'color' => 'green',
                                    'icon' => 'fa-comments-o',
                                    'url' => 'https://community.sugarcrm.com/community/end-user',
                                    'link' => 'LBL_LEARNING_RESOURCES_COMMUNITY_LINK',
                                ),
                                'support' => array(
                                    'color' => 'red',
                                    'icon' => 'fa-info-circle',
                                    'url' => 'http://support.sugarcrm.com/',
                                    'link' => 'LBL_LEARNING_RESOURCES_SUPPORT_LINK',
                                ),
                                'tour' => array(
                                    'id' => 'tour',
                                    'color' => 'gray',
                                    'icon' => 'fa-road',
                                    'link' => 'LBL_TOUR_LINK',
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);
