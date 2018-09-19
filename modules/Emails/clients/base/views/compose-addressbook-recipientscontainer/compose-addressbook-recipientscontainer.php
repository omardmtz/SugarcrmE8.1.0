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

$viewdefs['Emails']['base']['view']['compose-addressbook-recipientscontainer'] = array(
    'template' => 'record',
    'panels' => array(
        array(
            'name' => 'selected_recipients',
            'columns' => 1,
            'labels' => true,
            'labelsOnTop' => true,
            'placeholders' => true,
            'fields' => array(
                array(
                    'name' => 'to_collection',
                    'type' => 'email-recipients',
                    'label' => 'LBL_SELECTED_RECIPIENTS',
                    'readonly' => true,
                    'span' => 12,
                ),
            ),
        ),
    ),
);

