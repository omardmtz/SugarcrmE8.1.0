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
$viewdefs['base']['view']['sweetspot-config-theme'] = array(
    'action' => 'edit',
    'fields' => array(
        array(
            'name' => 'theme',
            'type' => 'enum',
            'label' => 'LBL_SWEETSPOT_THEME_SELECT',
            'enum_width' => 'resolve',
            'options' => 'sweetspot_theme_options',
        ),
    ),
);

