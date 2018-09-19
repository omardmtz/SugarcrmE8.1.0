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

use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;


if (!is_admin($current_user)) sugar_die("Unauthorized access to administration.");
if (isset($GLOBALS['sugar_config']['hide_admin_diagnostics']) && $GLOBALS['sugar_config']['hide_admin_diagnostics'])
{
    sugar_die("Unauthorized access to diagnostic tool.");
}

$request = InputValidation::getService();
$timeRequest = $request->getValidInputRequest('time');
$guidRequest = $request->getValidInputRequest('guid', 'Assert\Guid');

if ($guidRequest === null || $timeRequest === null) {
	die('Did not receive a filename to download');
}

ini_set('zlib.output_compression','Off');

$time = str_replace(array('.', '/', '\\'), '', $timeRequest);
$guid = str_replace(array('.', '/', '\\'), '', $guidRequest);
$path = sugar_cached("diagnostic/{$guid}/diagnostic{$time}.zip");
$filesize = filesize($path);
ob_clean();
header('Content-Description: File Transfer');
header('Content-type: application/octet-stream');
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Disposition: attachment; filename=$guid.zip");
header("Content-Transfer-Encoding: binary");
header("Content-Length: $filesize");
readfile($path);

