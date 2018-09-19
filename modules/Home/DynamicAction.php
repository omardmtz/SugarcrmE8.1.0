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

// $Id$


$mySugar = new MySugar($_REQUEST['module']);
if (!isset($_REQUEST['DynamicAction'])) {
	$_REQUEST['DynamicAction'] = 'displayDashlet';
}
// commit session before returning output so we can serialize AJAX requests
// and not get session into a wrong state
$res = $mySugar->{$_REQUEST['DynamicAction']}();
if(isset($_REQUEST['commit_session'])) {
    session_commit();
}
echo $res;
