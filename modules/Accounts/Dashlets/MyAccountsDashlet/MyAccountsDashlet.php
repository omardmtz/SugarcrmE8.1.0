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

// $Id: MyAccountsDashlet.php 56127 2010-04-27 00:03:05Z clee $




class MyAccountsDashlet extends DashletGeneric { 
    public function __construct($id, $def = null)
    {
		global $current_user, $app_strings;
		require('modules/Accounts/Dashlets/MyAccountsDashlet/MyAccountsDashlet.data.php');

        parent::__construct($id, $def);

        if(empty($def['title'])) $this->title = translate('LBL_HOMEPAGE_TITLE', 'Accounts');

        $this->searchFields = $dashletData['MyAccountsDashlet']['searchFields'];
        $this->columns = $dashletData['MyAccountsDashlet']['columns'];

        $this->seedBean = BeanFactory::newBean('Accounts');        
    }
    
    /**
     * Overrides the generic process to include custom logic for email addresses,
     * since they are no longer stored in  a list view friendly manner.
     * (A record may have an undetermined number of email addresses).
     *
     * @param array $lvsParams
     */
     
	function process($lvsParams = array()) {
    	if (isset($this->displayColumns) && array_search('email1', $this->displayColumns) !== false) {
	    	$lvsParams['custom_select'] = ', email_address as email1';
	    	$lvsParams['custom_from'] = ' LEFT JOIN email_addr_bean_rel eabr ON eabr.deleted = 0 AND bean_module = \'Accounts\''
	    							  . ' AND eabr.bean_id = accounts.id AND primary_address = 1'
	    							  . ' LEFT JOIN email_addresses ea ON ea.deleted = 0 AND ea.id = eabr.email_address_id';
    	}
    	
        if (isset($this->displayColumns) && array_search('parent_name', $this->displayColumns) !== false) {
	    	$lvsParams['custom_select'] = empty($lvsParams['custom_select']) ? ', a1.name as parent_name ' : $lvsParams['custom_select'] . ', a1.name as parent_name ';
	    	$lvsParams['custom_from'] = empty($lvsParams['custom_from']) ? ' LEFT JOIN accounts a1 on a1.id = accounts.parent_id' : $lvsParams['custom_from'] . ' LEFT JOIN accounts a1 on a1.id = accounts.parent_id';
    	}    	

    	parent::process($lvsParams);
    }
}

