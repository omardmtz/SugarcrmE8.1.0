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


class ContactsViewQuickcreate extends ViewQuickcreate
{
    public function preDisplay() 
    {
    	parent::preDisplay();
    	if($this->_isDCForm) {
    		//XXX TODO 20110329 Frank Steegmans: Hack to make quick create fields populate when used through the DC menu
    		//          NOTE HOWEVER that sqs_objects form fields are not properly populated because of some other hacks
    		//          resulting in none of the fields properly populating when selecting an account
    		if(!empty($this->bean->phone_office))$_REQUEST['phone_work'] = $this->bean->phone_office;
    		if(!empty($this->bean->billing_address_street))$_REQUEST['primary_address_street'] = $this->bean->billing_address_street;
    		if(!empty($this->bean->billing_address_city))$_REQUEST['primary_address_city'] = $this->bean->billing_address_city;
    		if(!empty($this->bean->billing_address_state))$_REQUEST['primary_address_state'] = $this->bean->billing_address_state;
    		if(!empty($this->bean->billing_address_country))$_REQUEST['primary_address_country'] = $this->bean->billing_address_country;
    		if(!empty($this->bean->billing_address_postalcode))$_REQUEST['primary_address_postalcode'] = $this->bean->billing_address_postalcode;
	   	}
    }    
}