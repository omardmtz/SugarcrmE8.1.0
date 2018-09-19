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

class AccountsViewDetail extends ViewDetail {
 	/**
 	 * display
 	 * Override the display method to support customization for the buttons that display
 	 * a popup and allow you to copy the account's address into the selected contacts.
 	 * The custom_code_billing and custom_code_shipping Smarty variables are found in
 	 * include/SugarFields/Fields/Address/DetailView.tpl (default).  If it's a English U.S.
 	 * locale then it'll use file include/SugarFields/Fields/Address/en_us.DetailView.tpl.
 	 */
 	function display(){
				
		if(empty($this->bean->id)){
			global $app_strings;
			sugar_die($app_strings['ERROR_NO_RECORD']);
		}				
		
		$this->dv->process();
		global $mod_strings;
		if(ACLController::checkAccess('Contacts', 'edit', true)) {
			$push_billing = '<span class="id-ff"><button class="button btn_copy" title="' . $mod_strings['LBL_PUSH_CONTACTS_BUTTON_LABEL'] . 
								 '" type="button" onclick=\'open_contact_popup("Contacts", 600, 600, "&account_name=' .
								 urlencode($this->bean->name) . '&html=change_address' .
								 '&primary_address_street=' . str_replace(array("\rn", "\r", "\n"), array('','','<br>'), urlencode($this->bean->billing_address_street)) . 
								 '&primary_address_city=' . $this->bean->billing_address_city . 
								 '&primary_address_state=' . $this->bean->billing_address_state . 
								 '&primary_address_postalcode=' . $this->bean->billing_address_postalcode . 
								 '&primary_address_country=' . $this->bean->billing_address_country .
								 '", true, false);\' value="' . $mod_strings['LBL_PUSH_CONTACTS_BUTTON_TITLE']. '">'.
								 SugarThemeRegistry::current()->getImage("id-ff-copy","",null,null,'.png',$mod_strings["LBL_COPY"]).
								 '</button></span>';
								 
			$push_shipping = '<span class="id-ff"><button class="button btn_copy" title="' . $mod_strings['LBL_PUSH_CONTACTS_BUTTON_LABEL'] . 
								 '" type="button" onclick=\'open_contact_popup("Contacts", 600, 600, "&account_name=' .
								 urlencode($this->bean->name) . '&html=change_address' .
								 '&primary_address_street=' . str_replace(array("\rn", "\r", "\n"), array('','','<br>'), urlencode($this->bean->shipping_address_street)) .
								 '&primary_address_city=' . $this->bean->shipping_address_city .
								 '&primary_address_state=' . $this->bean->shipping_address_state .
								 '&primary_address_postalcode=' . $this->bean->shipping_address_postalcode .
								 '&primary_address_country=' . $this->bean->shipping_address_country .
								 '", true, false);\' value="' . $mod_strings['LBL_PUSH_CONTACTS_BUTTON_TITLE'] . '">'.
								  SugarThemeRegistry::current()->getImage("id-ff-copy",'',null,null,'.png',$mod_strings['LBL_COPY']).
								 '</button></span>';
		} else {
			$push_billing = '';
			$push_shipping = '';
		}

		$this->ss->assign("custom_code_billing", $push_billing);
		$this->ss->assign("custom_code_shipping", $push_shipping);
        
        if(empty($this->bean->id)){
			global $app_strings;
			sugar_die($app_strings['ERROR_NO_RECORD']);
		}				
		echo $this->dv->display();
 	} 	
}

