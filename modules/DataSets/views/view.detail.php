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
 * $Id: view.detail.php 
 * Description: This file is used to override the default Meta-data EditView behavior
 * to provide customization specific to the DataSets module.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

class DataSetsViewDetail extends ViewDetail {
 	/**
 	 * display
     *
     */
 	function display() {
 		parent::display();
 		
		global $current_user, $app_strings, $mod_strings;
		
		if(isset($this->bean->query_id) && !empty($this->bean->query_id)){
			//CHECK FOR SUB-QUERIES
			$this->bean->check_interlock();
			//OUTPUT THE DATASET
			$data_set = BeanFactory::getBean('CustomQueries', $this->bean->query_id);
			$QueryView = new ReportListView();
			$QueryView->initNewXTemplate( 'modules/CustomQueries/QueryView.html',$mod_strings);
			$QueryView->setHeaderTitle($this->bean->name);
		
			//below: make sure to aquire the custom layout headers if available
			$QueryView->export_type = "Ent";
			
			$QueryView->xTemplateAssign('EDIT_INLINE', SugarThemeRegistry::current()->getImage('edit_inline','align="absmiddle" border="0"',null,null,'.gif',$app_strings['LNK_EDIT']));

			$QueryView->xTemplateAssign('LEFTARROW_INLINE', SugarThemeRegistry::current()->getImage('calendar_previous','align="absmiddle" border="0"', null,null,'.gif',$mod_strings['LBL_LEFT']));

			$QueryView->xTemplateAssign('RIGHTARROW_INLINE', SugarThemeRegistry::current()->getImage('calendar_next','align="absmiddle" border="0"', null,null,'.gif',$mod_strings['LBL_RIGHT']));

			$QueryView->setup($data_set, $this->bean, "main", "CUSTOMQUERY");
			$query_results = $QueryView->processDataSet();
		
			if($query_results['result']=="Error"){
			
				if (is_admin($current_user)){	
					echo "<font color=\"red\"><b>".$query_results['result_msg']."".$app_strings['ERROR_EXAMINE_MSG']."</font><BR>".$query_results['msg']."</b>";	
				} else {
					echo "<font color=\"red\"><b>".$query_results['result_msg']."</font></b><BR>";	
				}	
		
				
			}
			
			//end if there is even a query for the data set
			} else {
				echo "<font color=\"red\"><b>".$app_strings['NO_QUERY_SELECTED']."</font></b><BR>";	
			}	
 	} //display
}

