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

 // $Id: StudioWizard.php 20582 2007-03-02 03:54:00Z majed $


class StudioWizard{
    var $tplfile = 'modules/Studio/wizards/tpls/wizard.tpl';
    var $wizard = 'StudioWizard';
    var $status = '';
    var $assign = array();
    
    function welcome(){
        return $GLOBALS['mod_strings']['LBL_SW_WELCOME'];
    }

    function options(){
    	$options = array('SelectModuleWizard'=>$GLOBALS['mod_strings']['LBL_SW_EDIT_MODULE'], 
    	                 'EditDropDownWizard'=>$GLOBALS['mod_strings']['LBL_SW_EDIT_DROPDOWNS'],
    	                 'RenameTabs'=>$GLOBALS['mod_strings']['LBL_SW_RENAME_TABS'],
    	                 'ConfigureTabs'=>$GLOBALS['mod_strings']['LBL_SW_EDIT_TABS'],
    	                 'Portal'=>$GLOBALS['mod_strings']['LBL_SW_EDIT_PORTAL'],
				         'Workflow'=>$GLOBALS['mod_strings']['LBL_SW_EDIT_WORKFLOW'],
				         'RepairCustomFields'=>$GLOBALS['mod_strings']['LBL_SW_REPAIR_CUSTOMFIELDS'],
				         'MigrateCustomFields'=>$GLOBALS['mod_strings']['LBL_SW_MIGRATE_CUSTOMFIELDS'],

        
        );
    	if(!empty($GLOBALS['license']->settings['license_num_portal_users'])){
        	$options['SugarPortal']=$GLOBALS['mod_strings']['LBL_SW_SUGARPORTAL'];
        }
        return $options;
        
        
    }
    function back(){}
    function process($option){
        switch($option)
        {
            case 'SelectModuleWizard':
                require_once('modules/Studio/wizards/'. $option . '.php');
                $newWiz = new $option();
                $newWiz->display();
                break;
            case 'EditDropDownWizard':
                require_once('modules/Studio/wizards/'. $option . '.php');
                $newWiz = new $option();
                $newWiz->display();
                break;
            case 'RenameTabs':
                $newWiz = new RenameModules();
                $newWiz->process();
                break; 
            case 'ConfigureTabs':
                header('Location: index.php?module=Administration&action=ConfigureTabs');
                sugar_cleanup(true);
            case 'Workflow':
                header('Location: index.php?module=WorkFlow&action=ListView');
                sugar_cleanup(true);
            case 'Portal':
                header('Location: index.php?module=iFrames&action=index');
                sugar_cleanup(true);
            case 'RepairCustomFields':
            	header('Location: index.php?module=Administration&action=UpgradeFields');
            	sugar_cleanup(true);
            case 'MigrateCustomFields':
            	header('LOCATION: index.php?module=Administration&action=Development');
            	sugar_cleanup(true);
            case 'SugarPortal':
            	header('LOCATION: index.php?module=Studio&action=Portal');
            	sugar_cleanup(true);
            case 'Classic':
                header('Location: index.php?module=DynamicLayout&action=index');
                sugar_cleanup(true);
            default:
                $this->display();
        }
    }
    function display($error = ''){
       echo $this->fetch($error );
    }
    
    function fetch($error = ''){
    	 global $mod_strings;
        echo getClassicModuleTitle('StudioWizard', array($mod_strings['LBL_MODULE_TITLE']), false);
        $sugar_smarty = new Sugar_Smarty();
        $sugar_smarty->assign('welcome', $this->welcome());
        $sugar_smarty->assign('options', $this->options());
        $sugar_smarty->assign('MOD', $GLOBALS['mod_strings']);
        $sugar_smarty->assign('option', (!empty($_REQUEST['option'])?$_REQUEST['option']:''));
        $sugar_smarty->assign('wizard',$this->wizard);
         $sugar_smarty->assign('error',$error);
        $sugar_smarty->assign('status', $this->status);
        $sugar_smarty->assign('mod', $mod_strings);
        foreach($this->assign as $name=>$value){
            $sugar_smarty->assign($name, $value);
        }
       return  $sugar_smarty->fetch($this->tplfile);
    }

}
?>
