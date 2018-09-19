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

if(!empty($_REQUEST['layout']) && !empty($_REQUEST['layoutModule'])) {
//    sleep (2);
//  _ppd($_REQUEST['layout']); 
    $subpanels = explode(',', $_REQUEST['layout']);
    
    $layoutParam = $_REQUEST['layoutModule'];
    
    if(!empty($_REQUEST['layoutGroup']) && $_REQUEST['layoutGroup']!= translate('LBL_MODULE_ALL')) {
    	$layoutParam .= ':'.$_REQUEST['layoutGroup'];
    }
    
    $current_user->setPreference('subpanelLayout', $subpanels, 0, $layoutParam);
}
else {
    echo 'oops';
}

?>