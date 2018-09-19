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
$viewdefs['Releases']['base']['filter']['basic'] = array(
    'create' => false,
    'filters' => array(
        array(
            'id' => 'all_records', // need 'all_records' to make filter irremovable
            'name' => 'LBL_ACTIVE_RELEASES',
            'filter_definition' => array(
                'status' => array(
                    '$in' => array('Active'),
                ),
            ),
            'editable' => false,
        ),
    ),
);
