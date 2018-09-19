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

class SugarWidgetSubPanelTopSelectAccountButton extends SugarWidgetSubPanelTopSelectButton {
    public function display(array $widget_data, $additionalFormFields = array())
	{
		/*
		* i.dymovsky
		* Because when user role can't edit Accounts, it also can't edit Membership Organizations. Select button leads to change MO list
		* See bug 25633
		* Bug25633 code change start
		*/
		if (!ACLController::checkAccess($widget_data["module"], "edit", true)) {
			return ;
		}
		/*
		* Bug25633 code change end
		*/
		
        return parent::display($widget_data, $additionalFormFields);
	}
}
