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

// $Id: SugarWidgetSubPanelConcat.php 13782 2006-06-06 17:58:55Z majed $



class SugarWidgetSubPanelConcat extends SugarWidgetField
{
    public function displayList($layout_def)
	{
		$value='';
		if (isset($layout_def['source']) and is_array($layout_def['source']) and isset($layout_def['fields']) and is_array($layout_def['fields'])) {
			
			foreach ($layout_def['source'] as $field) {
			
				if (isset($layout_def['fields'][strtoupper($field)])) {
					$value.=$layout_def['fields'][strtoupper($field)];
				} else {
					$value.=$field;
				}	
			}
		}
		return $value;
	}
}
