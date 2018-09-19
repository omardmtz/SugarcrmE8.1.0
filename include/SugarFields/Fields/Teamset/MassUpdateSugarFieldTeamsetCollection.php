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
 * MaassUpdateSugarFieldTeamsetCollection.php
 * This class handles rendering the team widget for the MassUpdate form.
 * 
 */


class MassUpdateSugarFieldTeamsetCollection extends ViewSugarFieldTeamsetCollection {

    public function __construct($fill_data = false)
    {
        parent::__construct($fill_data);
		$this->form_name = 'MassUpdate'; 
        $this->action_type = 'massupdate';		 	
    }

    function init_tpl() {   
        $this->tpl_path = 'include/SugarFields/Fields/Teamset/TeamsetCollectionMassupdateView.tpl';
        $this->ss->assign('quickSearchCode',$this->createQuickSearchCode());
        $this->createPopupCode();// this code populate $this->displayParams with popupdata.
        $this->ss->assign('displayParams',$this->displayParams);
        $this->ss->assign('vardef',$this->vardef);
        $this->ss->assign('module',$this->related_module);
        $default = array('primary'=>array('id'=>1, 'name'=>'admin'));
        if(!empty($this->bean)){
      	   $this->ss->assign('values',$this->bean->{$this->value_name});
        }
        $this->ss->assign('showSelectButton',$this->showSelectButton);
        $this->ss->assign('APP',$GLOBALS['app_strings']);
        $this->ss->assign('isTBAEnabled', TeamBasedACLConfigurator::isAccessibleForModule($this->module_dir));
    }        
    
    function process() {
        $this->process_editview();	
    }    
    
}

