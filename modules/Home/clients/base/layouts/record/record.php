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

$viewdefs['Home']['base']['layout']['record'] = array(
    'components' => array(
        array(
            'layout' => array(
                'type' => 'base',
                'name' => 'main-pane',
                'css_class' => 'main-pane home-dashboard row-fluid',
                'components' => array(
                    array(
                        'layout' => array(
                            'name' => 'dashboard',
                            'type' => 'dashboard',
                            'components' => array(
                                array(
                                    'view' => 'dashboard-headerpane',
                                    'loadModule' => 'Dashboards',
                                ),
                                array(
                                    'layout' => 'dashlet-main',
                                ),
                            ),
                            'last_state' => array(
                                'id' => 'last-visit',
                            ),
                        ),
                        'loadModule' => 'Dashboards',
                    ),
                ),
            ),
        ),
    ),
    'last_state' => array(
        'id' => 'last-visit',
    ),
);
