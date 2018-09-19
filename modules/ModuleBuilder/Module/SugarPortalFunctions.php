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


class SugarPortalFunctions{

	function getNodes()
	{
	    global $mod_strings;
		$nodes = array();
        $nodes[] = array( 'name'=>$mod_strings['LBL_PORTAL_CONFIGURE'], 'action'=>'module=ModuleBuilder&action=portalconfig','imageTitle' => 'SPSync', );
        $nodes[] = array( 'name'=>$mod_strings['LBL_PORTAL_THEME'], 'action'=>'javascript: window.parent.App.router.navigate("Styleguide/layout/themeroller",{trigger: true});','imageTitle' => 'SPUploadCSS', );
		return $nodes;
	}
	
	
	
	
	
	
}
?>