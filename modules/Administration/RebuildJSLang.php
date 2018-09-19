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
if(is_admin($current_user)){

    global $mod_strings, $sugar_config;
    echo $mod_strings['LBL_REBUILD_JAVASCRIPT_LANG_DESC'];

    //remove the js language files
    LanguageManager::removeJSLanguageFiles();

    //remove language cache files
    LanguageManager::clearLanguageCache();
}
else{
	sugar_die($GLOBALS['app_strings']['ERR_NOT_ADMIN']);
}
?>
