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

class HolidaysViewEdit extends ViewEdit 
{
    /**
	 * @see SugarView::display()
	 */
	public function display() 
	{
		global $beanFiles, $mod_strings;
        $db = DBManagerFactory::getInstance();
		// the user admin (MLA) cannot edit any administrator holidays
		global $current_user;
		if(isset($_REQUEST['record'])){
            $query = "SELECT is_admin FROM users WHERE id=(SELECT person_id FROM holidays WHERE id=".
                $db->quoted($_REQUEST['record']) . ")";
            $result = $db->query($query);
			$row = $GLOBALS['db']->fetchByAssoc($result);
			if(!is_admin($current_user)&& $current_user->isAdminForModule('Users')&& $row['is_admin']==1){
				sugar_die('Unauthorized access');
			}
		}
		
		$this->ev->process();

		if ($_REQUEST['return_module'] == 'Project'){
			
        	$projectBean = BeanFactory::getBean('Project', $_REQUEST['return_id']);
        	
        	$userBean = BeanFactory::newBean('Users');
        	$contactBean = BeanFactory::newBean('Contacts');
        	
        	$projectBean->load_relationship("user_resources");
        	$userResources = $projectBean->user_resources->getBeans($userBean);
        	$projectBean->load_relationship("contact_resources");
        	$contactResources = $projectBean->contact_resources->getBeans($contactBean);
        	       	
			ksort($userResources);
			ksort($contactResources);	
						
			$this->ss->assign("PROJECT", true);
			$this->ss->assign("USER_RESOURCES", $userResources);
			$this->ss->assign("CONTACT_RESOURCES", $contactResources);
			
			$this->ss->assign("MOD", $mod_strings);
			
			$holiday_js = "<script type='text/javascript'>\n";
			$holiday_js .= $projectBean->resourceSelectJS();
			$holiday_js .= "\n</script>";

			echo $holiday_js;
        }
		
 		echo $this->ev->display();

        //echo the javascript that will validate the form
        $javascript = new javascript();
        $javascript->setFormName("EditView");
        $javascript->addFieldGeneric('holiday_date', '', 'LBL_HOLIDAY_DATE' ,'true');
        $javascript->addFieldGeneric('person_type', '', 'LBL_PERSON_TYPE' ,'true');
        //note that the person type and person id labels are use the resource name label on purpose for a clearer UI
        $javascript->addToValidateBinaryDependency('person_id', 'alpha', 'LBL_RESOURCE_NAME', 'true', '', 'person_type');
        echo $javascript->getScript();
 	}
}
