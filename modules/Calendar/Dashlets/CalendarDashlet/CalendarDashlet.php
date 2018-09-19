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



class CalendarDashlet extends Dashlet {
    var $view = 'week';

    public function __construct($id, $def)
    {
        $this->loadLanguage('CalendarDashlet','modules/Calendar/Dashlets/');

        parent::__construct($id); 
         
		$this->isConfigurable = true; 
		$this->hasScript = true;  
                
		if(empty($def['title'])) 
			$this->title = $this->dashletStrings['LBL_TITLE'];
		else 
			$this->title = $def['title'];  
			
		if(!empty($def['view']))
			$this->view = $def['view'];			
             
    }

    function display(){
		ob_start();
		
		if(isset($GLOBALS['cal_strings']))
			return parent::display() . "Only one Calendar dashlet is allowed.";
			
		
		global $cal_strings, $current_language;
		$cal_strings = return_module_language($current_language, 'Calendar');
		
		if(!ACLController::checkAccess('Calendar', 'list', true))
			ACLController::displayNoAccess(true);
						
		$cal = new Calendar($this->view);
		$cal->dashlet = true;
		$cal->add_activities($GLOBALS['current_user']);
		$cal->load_activities();
		
		$display = new CalendarDisplay($cal,$this->id);
		$display->display_calendar_header(false);		
		$display->display();
			
		$str = ob_get_contents();	
		ob_end_clean();
		
		return parent::display() . $str;
    }
    

    function displayOptions() {
        global $app_strings,$mod_strings;        
        $ss = new Sugar_Smarty();
        $ss->assign('MOD', $this->dashletStrings);        
        $ss->assign('title', $this->title);
        $ss->assign('view', $this->view);
        $ss->assign('id', $this->id);

        return parent::displayOptions() . $ss->fetch('modules/Calendar/Dashlets/CalendarDashlet/CalendarDashletOptions.tpl');
    }  

    function saveOptions($req) {
        global $sugar_config, $timedate, $current_user, $theme;
        $options = array();
        $options['title'] = $_REQUEST['title']; 
        $options['view'] = $_REQUEST['view'];       
         
        return $options;
    }

    function displayScript(){
	return "";
    }


}

