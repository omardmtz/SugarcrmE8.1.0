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

$focus = BeanFactory::newBean('WorkFlowTriggerShells');

if (empty($_REQUEST['record']))
{
    sugar_die($mod_strings['ERR_DELETE_RECORD']);
}

$focus = BeanFactory::retrieveBean('WorkFlowTriggerShells', $_REQUEST['record']);
if (empty($focus)) {
    sugar_die($mod_strings['ERR_DELETE_EMPTY']);
}
$focus->mark_deleted($_REQUEST['record']);

header("Location: index.php?module=".$_REQUEST['return_module']."&action=".$_REQUEST['return_action']."&record=".$_REQUEST['return_id']);
