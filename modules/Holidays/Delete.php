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
 * $Id: Delete.php 13782 2006-06-06 17:58:55 +0000 (Tue, 06 Jun 2006) majed $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/



$focus = BeanFactory::newBean('Holidays');

if(!isset($_REQUEST['record']))
	sugar_die("A record number must be specified to delete this holiday.");

$focus->mark_deleted($_REQUEST['record']);

// Bug 11485: Redirect to "My Account" page, do not expose Holiday listview
global $current_user;
header("Location: index.php?module=Users&action=DetailView&record=".$current_user->id);
?>
