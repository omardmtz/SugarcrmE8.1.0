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
if(empty($_REQUEST['record'])) {
	sugar_die($mod_strings['LBL_DELETE_ERROR']);
} else {
	
	$focus = BeanFactory::newBean('InboundEmail');

	// retrieve the focus in order to populate it with ID. otherwise this
	// instance will be marked as deleted and than replaced by another instance,
	// which will be saved and tracked (bug #47552)
	$focus->retrieve($_REQUEST['record']);
	$focus->mark_deleted($_REQUEST['record']);
	header("Location: index.php?module=".$_REQUEST['return_module']."&action=".$_REQUEST['return_action']."&record=".$_REQUEST['return_id']);
}

?>
