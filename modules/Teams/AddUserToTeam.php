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

use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;
use Sugarcrm\Sugarcrm\Security\InputValidation\Request;

global $current_user;

if (!$current_user->isAdminForModule('Users')) {
    sugar_die("Unauthorized access to administration.");
}

$request = InputValidation::getService();

$record = $request->getValidInputRequest('record', 'Assert\Guid');
$user_id = $request->getValidInputRequest('user_id', 'Assert\Guid');
$records = $request->getValidInputRequest(
    'records',
    array('Assert\All' => array('constraints' => 'Assert\Guid'))
);

if ((empty($record) && empty($records)) || empty($user_id)) {
    global $mod_strings;

    sugar_die($mod_strings['ERR_ADD_RECORD']);
} else {
    $focus = BeanFactory::newBean('Teams');

    if (!is_array($records)) {
        $records = array();
    }

    if (!empty($record)) {
        $records[] = $record;
    }

    foreach ($records as $id) {
        $focus->retrieve($id);
        $focus->add_user_to_team($user_id);
    }
}

header("Location: index.php?module={$_REQUEST['return_module']}&action={$_REQUEST['return_action']}&record={$_REQUEST['return_id']}");
