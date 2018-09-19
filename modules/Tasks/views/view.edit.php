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

class TasksViewEdit extends ViewEdit
{
    /**
 	 * @see SugarView::preDisplay()
 	 */
 	public function preDisplay()
 	{
 		if($_REQUEST['module'] != 'Tasks' && isset($_REQUEST['status']) && empty($_REQUEST['status'])) {
	       $this->bean->status = '';
 		} //if
 		if(!empty($_REQUEST['status']) && ($_REQUEST['status'] == 'Completed')) {
	       $this->bean->status = 'Completed';
 		}
 		parent::preDisplay();
 	}

 	/**
 	 * @see SugarView::display()
 	 */
 	public function display()
 	{
 		if($this->ev->isDuplicate){
	       $this->bean->status = $this->bean->getDefaultStatus();
 		} //if
 		parent::display();
 	}
}
