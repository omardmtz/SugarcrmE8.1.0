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

 * Description:
 ********************************************************************************/


global $mod_strings;

if (!is_admin($current_user)) {
    sugar_die($app_strings['LBL_UNAUTH_ADMIN']);
}

if(!isset($_REQUEST['record']))
	sugar_die($mod_strings['ERR_DELETE_RECORD']);

$focus = BeanFactory::deleteBean('CustomQueries', $_REQUEST['record']);

if(!$focus)
	sugar_die($mod_strings['ERR_DELETE_RECORD']);

//Remove the query_id from any data_sets that are currently using it.

$focus->mark_relationships_deleted($_REQUEST['record']);

header("Location: index.php?module=".$_REQUEST['return_module']."&action=".$_REQUEST['return_action']."&record=".$_REQUEST['return_id']);
?>
