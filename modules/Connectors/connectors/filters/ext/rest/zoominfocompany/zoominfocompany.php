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


class ext_rest_zoominfocompany_filter extends default_filter {	
	
public function getList($args, $module) {
	
	if(empty($args)) {
	   return null;	
	}

	$list = $this->_component->getSource()->getList($args, $module);

	if(empty($list) && isset($args['companyName'])) {
	   if(preg_match('/^(.*?)([\,|\s]+.*?)$/', $args['companyName'], $matches)) {
	   	 $GLOBALS['log']->info("ext_rest_zoominfocompany_filter, change companyName search term");
	   	 $args['companyName'] = $matches[1];
	     $list = $this->_component->getSource()->getList($args, $module);
	   }
	} 			
	
	//If count was 0 and state and/or country value was used, we try to improve searching
	if(empty($list) && isset($args['companyName']) && isset($args['ZipCode'])) {
	   $GLOBALS['log']->info("ext_rest_zoominfocompany_filter, unset ZipCode search term");
	   unset($args['ZipCode']);	
	   $list = $this->_component->getSource()->getList($args, $module);
	}	
	
	if(empty($list) && isset($args['companyName']) && isset($args['Country'])) {
	   $GLOBALS['log']->info("ext_rest_zoominfocompany_filter, unset Country search term");
	   unset($args['Country']);	
	   $list = $this->_component->getSource()->getList($args, $module);
	} 

	return $list;
}
	
}

?>
