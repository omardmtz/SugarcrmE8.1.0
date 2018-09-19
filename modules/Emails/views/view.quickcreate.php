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

class EmailsViewQuickcreate extends ViewQuickcreate 
{
    /**
     * @see ViewQuickcreate::display()
     */
    public function display()
    {
        $userPref = $GLOBALS['current_user']->getPreference('email_link_type');
		$defaultPref = $GLOBALS['sugar_config']['email_default_client'];
		if($userPref != '')
			$client = $userPref;
		else
			$client = $defaultPref;
		
        if ( $client == 'sugar' ) {
            $eUi = new EmailUI();
            if(!empty($this->bean->id) && !in_array($this->bean->object_name,array('EmailMan')) ) {
                $fullComposeUrl = "index.php?module=Emails&action=Compose&parent_id={$this->bean->id}&parent_type={$this->bean->module_dir}";
                $composeData = array('parent_id'=>$this->bean->id, 'parent_type' => $this->bean->module_dir);
            } else {
                $fullComposeUrl = "index.php?module=Emails&action=Compose";
                $composeData = array('parent_id'=>'', 'parent_type' => '');
            }
            
            $j_quickComposeOptions = $eUi->generateComposePackageForQuickCreate($composeData, $fullComposeUrl); 
            $json_obj = getJSONobj();
            $opts = $json_obj->decode($j_quickComposeOptions);
            $opts['menu_id'] = 'dccontent';
             
            $ss = new Sugar_Smarty();
            $ss->assign('json_output', $json_obj->encode($opts));
            $ss->display('modules/Emails/templates/dceMenuQuickCreate.tpl');
        }
        else {
            $emailAddress = '';
            if(!empty($this->bean->id) && !in_array($this->bean->object_name,array('EmailMan')) ) {
                $emailAddress = $this->bean->emailAddress->getPrimaryAddress($this->bean);
            }
            echo "<script>document.location.href='mailto:$emailAddress';lastLoadedMenu=undefined;DCMenu.closeOverlay();</script>";
            die();
        }
    } 
}
