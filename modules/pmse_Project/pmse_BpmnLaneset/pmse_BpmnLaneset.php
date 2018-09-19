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
 * THIS CLASS IS FOR DEVELOPERS TO MAKE CUSTOMIZATIONS IN
 */


class pmse_BpmnLaneset extends pmse_BpmnLaneset_sugar {

	public function __construct(){
		parent::__construct();
	}

	/**
	 * @inheritDoc
	 */
	public function ACLAccess($view, $context = null)
	{
		switch ($view) {
			case 'list':
				if (is_array($context)
					&& isset($context['source'])
					&& $context['source'] === 'filter_api') {
					return false;
				}
				break;
			case 'edit':
			case 'view':
				if (is_array($context)
					&& isset($context['source'])
					&& $context['source'] === 'module_api') {
					return false;
				}
				break;
		}
		return parent::ACLAccess($view, $context);
	}

}
?>
