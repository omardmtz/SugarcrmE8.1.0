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
$silent = isset($_REQUEST['silent']) ? true : false;
if(is_admin($current_user)){
    global $mod_strings;
	if (!$silent) { echo $mod_strings['LBL_REBUILD_DASHLETS_DESC']; }
    if(is_file($cachedfile = sugar_cached('dashlets/dashlets.php'))) {
        unlink($cachedfile);
    }

    $dc = new DashletCacheBuilder();
    $dc->buildCache();
   if( !$silent ) echo '<br><br><br><br>' . $mod_strings['LBL_REBUILD_DASHLETS_DESC_SUCCESS'];
}
else{
	sugar_die($GLOBALS['app_strings']['ERR_NOT_ADMIN']);
}
?>