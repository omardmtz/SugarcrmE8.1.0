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

if (isset($_SESSION['authenticated_user_id']))
{
    ob_clean();
    header('Location: ' . $GLOBALS['app']->getLoginRedirect());
    sugar_cleanup(true);
    return;
}

// display the logged out screen
$smarty = new Sugar_Smarty();
$smarty->assign(array(
    'LOGIN_URL'  => 'index.php?action=Login&module=Users',
    'STYLESHEET' => getJSPath('modules/Users/login.css'),
));
$smarty->display('modules/Users/LoggedOut.tpl');
