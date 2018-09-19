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

 // $Id: EditDropDownWizard.php 18703 2006-12-15 09:42:43Z majed $

class EditDropDownWizard extends StudioWizard {
	var $wizard = 'EditDropDownWizard';
    function welcome(){
		return 'You can rename the global dropdown list here.';
	}
	function back(){
	    
	    ob_clean();
	     if(!empty($_SESSION['studio']['module'])){
	        header('Location: index.php?action=wizard&module=Studio&wizard=SelectModuleAction');
	        sugar_cleanup(true);
	     }
	     header('Location: index.php?action=wizard&module=Studio&wizard=StudioWizard');
	    sugar_cleanup(true);
	     
	    
	   
	}
	function options(){
//		return array('EditDropdown'=>$GLOBALS['mod_strings']['LBL_SW_EDIT_DROPDOWNS'], 'CreateDropdown'=>$GLOBALS['mod_strings']['LBL_ED_CREATE_DROPDOWN'] );
	}
	
	function process($option){
		switch($option){
		    case 'EditDropdown':
		        parent::process($option);
		        require_once('modules/Studio/DropDowns/EditView.php');
		        break;
		    case 'SaveDropDown':
		        DropDownHelper::saveDropDown($_REQUEST);
		        require_once('modules/Studio/DropDowns/EditView.php');
		        break;
		    default:
		         parent::process($option);
		}
	}

    /**
     * {@inheritDoc}
     *
     * @param string $error Ignored
     */
    public function display($error = '')
	{
	    // override the parent display - don't display any wizard stuff
	}
	
}
