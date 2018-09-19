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
 
function getMLARoles()
{
    return array(
        'Sales Administrator' => array(
            'Accounts' => array('admin' => 100, 'access' => 89),
            'Contacts' => array('admin' => 100, 'access' => 89),
            'Forecasts' => array('admin' => 100, 'access' => 89),
            'Leads' => array('admin' => 100, 'access' => 89),
            'Quotes' => array('admin' => 100, 'access' => 89),
            'Opportunities' => array('admin' => 100, 'access' => 89),
         ),
        'Marketing Administrator' => array(
            'Accounts' => array('admin' => 100, 'access' => 89),
            'Contacts' => array('admin' => 100, 'access' => 89),
            'Campaigns' => array('admin' => 100, 'access' => 89),
            'ProspectLists' => array('admin' => 100, 'access' => 89),
            'Leads' => array('admin' => 100, 'access' => 89),
            'Prospects' => array('admin' => 100, 'access' => 89),
        ),
        'Customer Support Administrator' => array(
            'Accounts' => array('admin' => 100, 'access' => 89),
            'Contacts' => array('admin' => 100, 'access' => 89),
            'Bugs' => array('admin' => 100, 'access' => 89),
            'Cases' => array('admin' => 100, 'access' => 89),
            'KBContents' => array('admin' => 100, 'access' => 89),
        ),
        'Data Privacy Manager' => array(
            'DataPrivacy' => array('admin' => 99, 'access' => 89),
            'Accounts' => array('admin' => 99, 'access' => 89),
            'Contacts' => array('admin' => 99, 'access' => 89),
            'Leads' => array('admin' => 99, 'access' => 89),
            'Prospects' => array('admin' => 99, 'access' => 89),
        ),
    );
}

function create_default_roles() {
    // Adding MLA Roles
    global $db;
    addDefaultRoles(getMLARoles());
}
?>
