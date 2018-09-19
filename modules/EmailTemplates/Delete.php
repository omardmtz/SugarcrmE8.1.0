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
 * $Header$
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/





$focus = BeanFactory::newBean('EmailTemplates');

if(!isset($_REQUEST['record']))
	sugar_die("A record number must be specified to delete the template.");
$focus->retrieve($_REQUEST['record']);
if(!$focus->ACLAccess('Delete')) {
	ACLController::displayNoAccess(true);
	sugar_cleanup(true);
}
sugar_cache_clear('select_array:'.$focus->object_name.'namebase_module=\''.$focus->base_module.'\'name');
$focus->mark_deleted($_REQUEST['record']);

header("Location: index.php?module=".$_REQUEST['return_module']."&action=".$_REQUEST['return_action']."&record=".$_REQUEST['return_id']);
?>
