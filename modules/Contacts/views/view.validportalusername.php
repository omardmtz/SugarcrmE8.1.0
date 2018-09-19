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
 * ContactsViewValidPortalUsername.php
 * 
 * This class overrides SugarView and provides an implementation for the ValidPortalUsername
 * method used for checking whether or not an existing portal user_name has already been assigned.
 * We take advantage of the MVC framework to provide this action which is invoked from
 * a javascript AJAX request.
 * 
 * @author Collin Lee
 * */
 

class ContactsViewValidPortalUsername extends SugarView 
{
    /**
     * {@inheritDoc}
     *
     * @param array $params Ignored
     */
    public function process($params = array())
 	{
		$this->display();
 	}

 	/**
     * @see SugarView::display()
     */
    public function display()
    {
        if (!empty($_REQUEST['portal_name'])) {
            $portalUsername = $this->bean->db->quote($_REQUEST['portal_name']);
            $result = $this->bean->db->query("Select count(id) as total from contacts where portal_name = '$portalUsername' and deleted='0'");
            $total = 0;
            while($row = $this->bean->db->fetchByAssoc($result))
                $total = $row['total'];
            echo $total;
        }
        else
           echo '0';
 	}	
}
