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
 * ContactsViewContactAddressPopup
 * 
 * */
 
require_once('modules/Contacts/Popup_picker.php');

class ContactsViewContactAddressPopup extends SugarView {
    /**
     * {@inheritDoc}
     *
     * @param array $params Ignored
     */
    public function process($params = array())
    {
		$this->display();
 	}

 	function display() {
 		$this->renderJavascript();
 		$popup = new Popup_Picker();
		echo $popup->process_page_for_address();
 	}	
}
