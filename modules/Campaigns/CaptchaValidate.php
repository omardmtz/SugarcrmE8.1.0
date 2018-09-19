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
 * $Id$
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

 /**Check captcha validation here.
 *
 */
require_once('vendor/reCaptcha/recaptchalib.php');

$admin = Administration::getSettings('captcha');
if($admin->settings['captcha_on']=='1' && !empty($admin->settings['captcha_private_key'])){
	$privatekey = $admin->settings['captcha_private_key'];
}else
	die("Captcha settings not found");
$response = recaptcha_check_answer($privatekey,
									$_SERVER["REMOTE_ADDR"],
									$_REQUEST["recaptcha_challenge_field"],
									$_REQUEST["recaptcha_response_field"]);
if(!$response->is_valid){
	die("Invalid captcha entry, go back and fix. ". $response->error. " ");
}
else echo("Success");

?>
