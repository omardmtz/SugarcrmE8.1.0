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
/**
 * This dependency sets the status field to read only if the lead has been converted.
 */
$dependencies['Leads']['status_read_only'] = array(
    'hooks' => array("edit"),
    //Trigger formula for the dependency. Defaults to 'true'.
    'trigger' => 'true',
    'triggerFields' => array('converted'),
    'onload' => true,
    //Actions is a list of actions to fire when the trigger is true
    'actions' => array(
        array(
            'name' => 'ReadOnly', //Action type
            //The parameters passed in depend on the action type
            'params' => array(
                'target' => 'status',
                'label' => 'status_label', //normally <field>_label
                'value' => 'equal($converted,"true")',
            ),
        ),
    ),
);
