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






global $mod_strings;
global $app_list_strings;
global $app_strings;
global $current_language;
global $sugar_config;
global $sugar_flavor;
global $sugar_version;

$send_version = isset($sugar_version) ? $sugar_version : "";
$send_edition = isset($sugar_flavor) ? $sugar_flavor : "";
$send_lang = isset($current_language) ? $current_language : "";
$send_key = isset($sugar_config['unique_key']) ? $sugar_config['unique_key'] : "";


$sugar_smarty = new Sugar_Smarty();

$iframe_url = add_http("www.sugarcrm.com/network/redirect.php?to=training&tmpl=network&version={$send_version}&edition={$send_edition}&language={$send_lang}&key={$send_key}");
$sugar_smarty->assign('iframeURL', $iframe_url);

echo $sugar_smarty->fetch('modules/Home/TrainingPortal.tpl');

?>
