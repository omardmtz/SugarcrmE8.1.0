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
if(is_admin($current_user))
{
    if(!isset($_REQUEST['process']))
    {
        global $mod_strings;
        echo '<br>'.$mod_strings['LBL_REPAIR_JS_FILES_PROCESSING'];
        echo'<div id="msgDiv"></div>';
        $ss = new Sugar_Smarty();
        $ss->display('modules/Administration/templates/RebuildSprites.tpl');
    }
}

?>