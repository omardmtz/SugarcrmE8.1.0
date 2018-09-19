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
 



class HolidaysQuickCreate extends QuickCreate {
    
    var $javascript;
    
    function process() {
        global $current_user, $timedate, $app_list_strings, $current_language, $mod_strings;
        $mod_strings = return_module_language($current_language, 'Holidays');
        
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
        }
        
        parent::process();
        
        $this->ss->assign("CALENDAR_DATEFORMAT", $timedate->get_cal_date_format());

        if($this->viaAJAX) { // override for ajax call
            $this->ss->assign('saveOnclick', "onclick='if(check_form(\"holidaysQuickCreate\")) return SUGAR.subpanelUtils.inlineSave(this.form.id, \"holidays\"); else return false;'");
            $this->ss->assign('cancelOnclick', "onclick='return SUGAR.subpanelUtils.cancelCreate(\"subpanel_holidays\")';");
        }
        
        $this->ss->assign('viaAJAX', $this->viaAJAX);

        $this->javascript = new javascript();
        $this->javascript->setFormName('holidayQuickCreate');
        
        $focus = BeanFactory::newBean('Holidays');
        $this->javascript->setSugarBean($focus);
        $this->javascript->addAllFields('');

        $this->ss->assign('additionalScripts', $this->javascript->getScript(false));
    }   
}
?>