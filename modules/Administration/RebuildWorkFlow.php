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

require_once('include/workflow/plugin_utils.php');

global $beanFiles;
global $mod_strings;
global $db;

function remove_workflow_dir($dir) {
   if($elements = glob($dir."/*")){
       foreach($elements as $element) {
           is_dir($element)? remove_workflow_dir($element) : unlink($element);
       }
   }
}



$workflow_object = BeanFactory::newBean('WorkFlow');


	$module_array = $workflow_object->get_module_array();

	foreach($module_array as $key => $module){

		$dir = "custom/modules/".$module."/workflow";
		if(file_exists($dir)){
			
			remove_workflow_dir($dir);
			
		//end if directory does exist
		}
		
	//end foreach loop	
	}	
	



//clean workflow cache..rebuilt done.
echo $mod_strings['LBL_REBUILD_WORKFLOW_CACHE'];
$workflow_object->repair_workflow(); 

echo $mod_strings['LBL_DONE'] . '<BR>';


build_plugin_list();


echo $mod_strings['LBL_REBUILD_WORKFLOW_COMPILING'] . $mod_strings['LBL_DONE'];

?>
