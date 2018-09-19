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
/*********************************************************************************

 * Description:  TODO To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

global $mod_strings, $app_strings;
global $current_user;
$module_menu = array();
if ( isTouchScreen() ) {
    $module_menu[] = Array('index.php?module=Home&action=index', $mod_strings['LBL_MODULE_NAME'], 'Home', 'Home');
}
$module_menu[] = Array('index.php?module=Home&action=index&activeTab=AddTab', $app_strings['LBL_ADD_PAGE'], 'AddTab', 'Home');
?>