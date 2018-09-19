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

 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

	
if(!empty($_REQUEST['saved_search_action'])) {

	$ss = BeanFactory::newBean('SavedSearch');
	
	switch($_REQUEST['saved_search_action']) {
        case 'update': // save here
        	$savedSearchBean = BeanFactory::newBean($_REQUEST['search_module']);
            $ss->handleSave('', true, false, $_REQUEST['saved_search_select'], $savedSearchBean);
            break;
		case 'save': // save here
			$savedSearchBean = BeanFactory::newBean($_REQUEST['search_module']);
			$ss->handleSave('', true, false, null, $savedSearchBean);
			break;
		case 'delete': // delete here
			$ss->handleDelete($_REQUEST['saved_search_select']);
			break;			
	}
}
elseif(!empty($_REQUEST['saved_search_select'])) { // requesting a search here.
    if(!empty($_REQUEST['searchFormTab'])) // where is the request from  
        $searchFormTab = $_REQUEST['searchFormTab'];
    else 
        $searchFormTab = 'saved_views';

	if($_REQUEST['saved_search_select'] == '_none') { // none selected
		$_SESSION['LastSavedView'][$_REQUEST['search_module']] = '';
        $current_user->setPreference('ListViewDisplayColumns', array(), 0, $_REQUEST['search_module']);
        $ajaxLoad = empty($_REQUEST['ajax_load']) ? "" : "&ajax_load=" . $_REQUEST['ajax_load'];
        header("Location: index.php?action=index&module={$_REQUEST['search_module']}&searchFormTab={$searchFormTab}&query=true&clear_query=true$ajaxLoad");
		die();
	}
	else {
		
		$ss = BeanFactory::newBean('SavedSearch');
        $show='no';
        if(isset($_REQUEST['showSSDIV'])){$show = $_REQUEST['showSSDIV'];}
		$ss->returnSavedSearch($_REQUEST['saved_search_select'], $searchFormTab, $show);
	}
}
else {
	include('modules/SavedSearch/ListView.php');
}

?>