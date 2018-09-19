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

$viewdefs['ForecastWorksheets']['base']['view']['filter'] = array(
    'panels' => array(
        0 => array(
            'label' => 'LBL_PANEL_1',
            'fields' => array(
                array(
                    'name' => 'ranges',
                    /*
                    This is an enum field, however the 'options' string is set dynamically in the view (which is why it
                    is missing here), since the dropdown shown to the user depends on a config setting
                    */
                    'type' => 'enum',
                    'multi' => true,
                    'label' => 'LBL_FILTERS',
                    'default' => false,
                    'enabled' => true,
                ),
            ),
        ),
    ),
    'last_state' => array(
        'id' => 'filter',
    ),
);
