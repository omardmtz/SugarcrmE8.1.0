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
class ViewIndex extends SugarView
{
    public function __construct()
    {
		$this->options['show_footer'] = false;
		$this->options['show_header'] = true;
        parent::__construct();
 	}
 	
 	function display() {
 		$smarty = new Sugar_Smarty();
 		$smarty->display('modules/ExpressionEngine/tpls/index.tpl');
 	}
}

