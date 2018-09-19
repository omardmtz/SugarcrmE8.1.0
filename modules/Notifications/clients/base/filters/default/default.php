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

$viewdefs['Notifications']['base']['filter']['default'] = array(
    'default_filter' => 'all_records',

    'filters' => array(
        array(
            'id' => 'read',
            'name' => 'LBL_READ',
            'filter_definition' => array(
                array(
                    'is_read' => array('$equals' => 1),
                ),
            ),
            'editable' => false,
        ),
        array(
            'id' => 'unread',
            'name' => 'LBL_UNREAD',
            'filter_definition' => array(
                array(
                    'is_read' => array('$equals' => 0),
                ),
            ),
            'editable' => false,
        ),
    ),
);
