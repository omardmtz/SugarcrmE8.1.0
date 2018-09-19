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

/**
 * ReportsSugarFieldTeamsetCollection.php
 * This class handles rendering the team widget for the Reports module.
 * 
 */

class ReportsSugarFieldTeamsetCollection extends ViewSugarFieldTeamsetCollection {

	var $showPrimaryChecked = true;
	
    public function __construct($fill_data = false)
    {
        parent::__construct($fill_data);
		$this->form_name = "ReportsWizardForm";
		$this->action_type = 'reports'; 	 	
    }

    function init_tpl() {
        $this->ss->assign('quickSearchCode',$this->createQuickSearchCode());
        $this->createPopupCode();// this code populate $this->displayParams with popupdata.
		$this->displayParams['formName'] = $this->form_name;        
        $this->tpl_path = 'include/SugarFields/Fields/Teamset/TeamsetCollectionEditView.tpl'; 

        if(!empty($this->bean)) {      	
      	   $this->ss->assign('values',$this->bean->{$this->value_name});
           //Check if we have a primary team checked
	       $this->displayParams['primaryChecked'] = !empty($this->bean->{$this->value_name}['primary']) && $this->showPrimaryChecked;     	   
        }
        
        $this->ss->assign('displayParams',$this->displayParams);
        $this->ss->assign('vardef',$this->vardef);
        $this->ss->assign('module',$this->related_module);
                
        //do not show the hide/show toggle button
        $this->ss->assign('hideShowHideButton', true);
        $this->ss->assign('showSelectButton',$this->showSelectButton);
        $this->ss->assign('APP',$GLOBALS['app_strings']);

        $this->ss->assign('isTBAEnabled', TeamBasedACLConfigurator::isAccessibleForModule($this->module_dir));
    }        
    
    function process() {
        $this->process_reports();
        $this->process_editview();
    }    
    
    private function process_reports() {
        $sfh = new SugarFieldHandler();  
        $sf = $sfh->getSugarField('Teamset', true);  					
        $teams = $sf->getTeamsFromRequest($this->name);
        $full_form_values = array();
        if(!empty($teams)) {	    	
        	if(isset($_REQUEST["primary_{$this->name}_collection"])){
	    		$this->ss->assign('hasPrimaryTeam', true);
	    		$primary = $_REQUEST["primary_{$this->name}_collection"];
	    		$key = "id_{$this->name}_collection_{$primary}"; //Get the $_REQUEST index key
	    		$primary = $_REQUEST[$key];	    
	    		$primaryTeam = array('id' => $primary, 'name'=>$teams[$primary]);
	    		$full_form_values['primary'] = $primaryTeam;
	    		unset($teams[$primary]); //Unset the primary team
	    	} else {
	    		//Here we technically don't have a primary team chosen, but we need to allocate
	    		//a primary team to display as the first team in the widget
	    	    foreach($teams as $team_id=>$team_name) {
	    		   $full_form_values['primary'] = array('id'=>$team_id, 'name'=>$team_name);
	    		   $this->showPrimaryChecked = false;
	    		   unset($teams[$team_id]);
	    		   break;
	    	    }   		
	    	}	    	
	    	
        	foreach($teams as $team_id=>$team_name) {
	    			$full_form_values['secondaries'][] = array('id'=>$team_id, 'name'=>$team_name);
	    	}
        	
	    	$this->bean->{$this->value_name}=array_merge($this->bean->{$this->value_name}, $full_form_values);
        }                   	  	
    }    
    
}

