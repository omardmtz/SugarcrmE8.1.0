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


class TeamsViewList extends ViewList
{
    public function preDisplay()
    {
        //bug #46690: Developer Access to Users/Teams/Roles
        if (!$GLOBALS['current_user']->isAdminForModule('Users') && !$GLOBALS['current_user']->isDeveloperForModule('Users'))
            sugar_die("Unauthorized access to administration.");

        parent::preDisplay();
    }

    public function display()
    {
        $sugarSmarty = new Sugar_Smarty();
        echo $sugarSmarty->fetch(SugarAutoLoader::existingCustomOne('modules/Teams/tpls/Errors.tpl'));
        parent::display();
    }
}
