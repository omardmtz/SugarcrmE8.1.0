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

$viewdefs['Bugs']['portal']['view']['list-bottom'] = array(
    'listNav' =>
    array(
        0 =>
        array(
            'name' => 'create_new',
            'type' => 'navelement',
            'icon' => 'fa-plus',
            'label' => ' ',
            'route' =>
            array(
                'action' => 'create',
                'module' => 'Cases',
            ),
        ),
        1 =>
        array(
            'name' => 'show_more_button_back',
            'type' => 'navelement',
            'icon' => 'fa-chevron-left',
            'label' => ' '
        ),
        2 =>
        array(
            'name' => 'show_more_button_forward',
            'type' => 'navelement',
            'icon' => 'fa-chevron-right',
            'label' => ' '
        ),
    ),
);
