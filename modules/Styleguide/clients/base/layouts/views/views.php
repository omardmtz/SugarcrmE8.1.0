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
$viewdefs['Styleguide']['base']['layout']['views'] = array(
    'css_class' => 'styleguide',
    'components' => array(
        array(
            'layout' => array(
                'type' => 'base',
                'name' => 'sidebar',
                'css_class' => 'row-fluid',
                'components' => array(
                    array(
                        'layout' => array(
                            'type' => 'base',
                            'name' => 'main-pane',
                            'css_class' => 'main-pane span12',
                            'components' => array(
                                array(
                                    'view' => 'sg-headerpane',
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);
