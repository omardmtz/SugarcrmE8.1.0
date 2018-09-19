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




class SugarWidgetSubPanelRemoveButtonAccount extends SugarWidgetSubPanelRemoveButton {
	/**
	 * 
	 * @see SugarWidgetSubPanelRemoveButton::displayList()
	 */
    public function displayList($layout_def)
    {
		if (!$layout_def['EditView']) {
			return false;
		}
		return parent::displayList($layout_def);
	}
}