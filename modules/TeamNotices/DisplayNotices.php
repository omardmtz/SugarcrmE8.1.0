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
if (!$GLOBALS['current_user']->isAdminForModule('Users')) sugar_die($GLOBALS['app_strings']['ERR_NOT_ADMIN']);	

global $mod_strings;
global $app_list_strings;
global $app_strings;
global $current_user;

$focus = BeanFactory::newBean('TeamNotices');

$is_edit = true;
if(!empty($_REQUEST['record'])) {
    $result = $focus->retrieve($_REQUEST['record']);
 
}
$GLOBALS['log']->info("TeamNotice list view");
global $theme;

$xtpl=new XTemplate ('modules/TeamNotices/DisplayNotices.html');
$ListView = new ListView();
$ListView->initNewXTemplate( 'modules/TeamNotices/DisplayNotices.html',$mod_strings);
$today = db_convert("'".$timedate->nowDbDate()."'", 'date');

$ListView->setHeaderTitle(translate('LBL_NOTICES', 'TeamNotices'));
$ListView->setQuery($focus->table_name.".date_start <= $today and ".$focus->table_name.".date_end >= $today and ".$focus->table_name.'.status=\'Visible\'', "", "date_start", "TEAMNOTICE");
$ListView->processListView($focus, "main", "TEAMNOTICE");




?>
