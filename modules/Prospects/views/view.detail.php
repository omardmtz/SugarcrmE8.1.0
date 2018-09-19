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

class ProspectsViewDetail extends ViewDetail {
 	function display() {
		if(isset($this->bean->lead_id) && !empty($this->bean->lead_id)){
			
			//get lead name
			$lead = BeanFactory::getBean('Leads', $this->bean->lead_id);
			$this->ss->assign('lead', $lead);
		}
 		parent::display();
 	}
}
