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


class ReportsViewClassic extends ViewClassic
{
 	/**
	 * @see SugarView::preDisplay()
	 */
	public function preDisplay()
 	{
 		if(!empty($this->view_object_map['action']))
 			$this->action = $this->view_object_map['action'];
 	}
 	
 	/**
	 * @see SugarView::display()
	 */
	public function display()
 	{
 		parent::display();
 		$this->action = $GLOBALS['action'];	
 	}	
}
