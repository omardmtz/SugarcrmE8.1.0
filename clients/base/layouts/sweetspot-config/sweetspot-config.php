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
$viewdefs['base']['layout']['sweetspot-config'] = array(
    'css_class' => 'sweetspot-config',
    'components' => array(
        array(
            'view' => 'sweetspot-config-headerpane',
        ),
        array(
            'layout' => array(
                'type' => 'fluid',
                'components' => array(
                    array(
                        'layout' => array(
                            'type' => 'sweetspot-config-list',
                            'span' => 8,
                        ),
                    ),
                    array(
                        'view' => array(
                            'type' => 'sweetspot-config-theme',
                            'span' => 4,
                        ),
                    ),
                ),
            ),
        ),
    ),
);
