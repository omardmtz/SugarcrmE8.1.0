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

$inputValidation = InputValidation::getService();
$samlRequest = $inputValidation->getValidInputRequest('SAMLRequest');
$logout = $inputValidation->getValidInputRequest('logout');
$relayState = $inputValidation->getValidInputRequest('RelayState');
if ($samlRequest) {
    if (!$logout) {
        $smarty = new Sugar_Smarty();
        $redirectUrl = 'index.php?module=Users&action=Logout&logout=1&SAMLRequest=' . urlencode($samlRequest);
        if ($relayState) {
            $redirectUrl .= '&RelayState=' . urlencode($relayState);
        }
        $smarty->assign(array(
                'REDIRECT_URL'  => $redirectUrl,
        ));
        $smarty->display('modules/Users/tpls/Logout.tpl');
    } else {
        /** @var AuthenticationController $authController */
        $authController->authController->logout();
    }
} else {
    // record the last theme the user used
    $current_user->setPreference('lastTheme', $theme);
    $GLOBALS['current_user']->call_custom_logic('before_logout');

    // submitted by Tim Scott from SugarCRM forums
    foreach ($_SESSION as $key => $val) {
        $_SESSION[$key] = ''; // cannot just overwrite session data, causes segfaults in some versions of PHP
    }
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time()-42000, '/');
    }

    SugarApplication::endSession();

    LogicHook::initialize();
    $GLOBALS['logic_hook']->call_custom_logic('Users', 'after_logout');

    /** @var AuthenticationController $authController */
    $authController->authController->logout();
}
