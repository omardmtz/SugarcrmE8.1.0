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

class ProjectViewTemplatesEdit extends ViewEdit 
{
 	/**
	 * @see SugarView::_getModuleTitleParams()
	 */
	protected function _getModuleTitleParams($browserTitle = false)
	{
	    global $mod_strings;
	    
	    $crumbs = array();
	    $crumbs[] = $this->_getModuleTitleListParam($browserTitle);
	    if(!empty($this->bean->id)){
	    	$crumbs[] =  "<a href='index.php?module=Project&action=EditView&record={$this->bean->id}'>{$this->bean->name}</a>";
	    }
	    $crumbs[] = $mod_strings['LBL_PROJECT_TEMPLATE'];
    	return $crumbs;
    }
    
	function display() 
	{
        $this->bean->is_template = 1;
        $this->ev->ss->assign("is_template", 1);

 		parent::display();
 	}
}
