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
$mod_strings   = return_module_language($current_language, $_REQUEST['target_module']);
$target_module = $_REQUEST['target_module']; // target class

if(SugarAutoLoader::existing('modules/'. $_REQUEST['target_module'] . '/EditView.php')) {
    $tpl = $_REQUEST['tpl'];
	if(SugarAutoLoader::requireWithCustom('modules/' . $target_module . '/' . $target_module . 'QuickCreate.php')) { // if there is a quickcreate override
	    $editviewClass     = SugarAutoLoader::customClass($target_module . 'QuickCreate'); // eg. OpportunitiesQuickCreate
	    $editview          = new $editviewClass($target_module, 'modules/' . $target_module . '/tpls/' . $tpl);
	    $editview->viaAJAX = true;
	}
	else { // else use base class
	    require_once('include/EditView/EditViewQuickCreate.php');
	    $editview = new EditViewQuickCreate($target_module, 'modules/' . $target_module . '/tpls/' . $tpl);
	}
	$editview->process();
	echo $editview->display();
} else{

	$subpanelView = 'modules/'. $target_module . '/views/view.subpanelquickcreate.php';
	$view = (!empty($_REQUEST['target_view'])) ? $_REQUEST['target_view'] : 'QuickCreate';
	//Check if there is a custom override, then check for module override, finally use default (SubpanelQuickCreate)

	if(SugarAutoLoader::requireWithCustom($subpanelView)) {
    	$subpanelClass = SugarAutoLoader::customClass($target_module . 'SubpanelQuickCreate');
        $sqc  = new $subpanelClass($target_module, $view);
	} else {
		$sqc  = new SubpanelQuickCreate($target_module, $view);
	}
}

