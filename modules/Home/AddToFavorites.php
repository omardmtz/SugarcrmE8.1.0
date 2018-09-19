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

global $current_user;

if(!empty($_REQUEST['target_module']) && !empty($_REQUEST['target_id'])) {
    $objects = $current_user->getPreference('objects', 'favorites');
    if(!is_array($objects)) $objects = array();
    if(empty($objects[$_REQUEST['target_module']])) $objects[$_REQUEST['target_module']] = array();
    $objects[$_REQUEST['target_module']][$_REQUEST['target_id']] = true;
    
    $current_user->setPreference('objects', $objects, 0, 'favorites');
    
    echo 1;
}
else {
    echo 0;
}
?>
