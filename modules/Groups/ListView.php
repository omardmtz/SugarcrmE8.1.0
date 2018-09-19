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
global $current_language;

$focus = BeanFactory::newBean('Groups');
$where = ' users.users.is_group = 1 ';

$current_module_strings = return_module_language($current_language, 'Users');

$ListView = new ListView();
$ListView->initNewXTemplate('modules/Groups/ListView.html',$current_module_strings);
$ListView->setHeaderTitle($mod_strings['LBL_LIST_TITLE']);
$ListView->setQuery($where, "", "last_name, first_name", "USER");
$ListView->show_mass_update=false;
$ListView->processListView($focus, "main", "USER");
?>