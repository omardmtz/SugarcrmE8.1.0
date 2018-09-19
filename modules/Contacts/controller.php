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

class ContactsController extends SugarController
{
	function action_Popup(){
		if(!empty($_REQUEST['html']) && $_REQUEST['html'] == 'mail_merge'){
			$this->view = 'mailmergepopup';
		}else{
			$this->view = 'popup';
		}
	}
	
    function action_ValidPortalUsername()
    {
		$this->view = 'validportalusername';
    }

    function action_RetrieveEmail()
    {
        $this->view = 'retrieveemail';	
    }

    function action_ContactAddressPopup()
    {
		$this->view = 'contactaddresspopup';
    }
  
    function action_CloseContactAddressPopup()
    {
    	$this->view = 'closecontactaddresspopup';
    }    
}
?>