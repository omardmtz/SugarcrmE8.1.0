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
 * ContactsViewRetrieveEmailUsername.php
 * 
 * This class overrides SugarView and provides an implementation for the RetrieveEmailUsername
 * method used for returning the information about an email address
 * 
 * @author Collin Lee
 * */

class ContactsViewRetrieveEmail extends SugarView {
    /**
     * {@inheritDoc}
     *
     * @param array $params Ignored
     */
    public function process($params = array())
    {
		$this->display();
 	}

 	function display(){
	    $data = array();
	    $data['target'] = $this->request->getValidInputRequest('target', 'Assert\ComponentName');
        if(!empty($_REQUEST['email'])) {
			$emailAddr = $this->request->getValidInputRequest('email', 'Assert\Email');
	        $db = DBManagerFactory::getInstance();
	        $email = $GLOBALS['db']->quoted(strtoupper(trim($emailAddr)));
	        $result = $db->query("SELECT * FROM email_addresses WHERE email_address_caps = $email AND deleted = 0");
			if($row = $db->fetchByAssoc($result)) {
		        $data['email'] = $row;
			} else {
				$data['email'] = '';
			}
        }
		$json = new JSON();
		echo $json->encode($data); 
 	}	
}
