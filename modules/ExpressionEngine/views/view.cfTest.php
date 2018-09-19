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
class ViewCfTest extends SugarView
{
    public function __construct()
    {
		$this->options['show_footer'] = true;
		$this->options['show_header'] = true;
        parent::__construct();
 	}
 	
 	function display() {
 		$th = new TemplateHandler();
 		$depScript = $th->createDependencyJavascript(array(
 			'phone_office' => array(
 				'calculated' => true, 
 				"formula" => 'add(strlen($name), $employees)',
 				"enforced" => true,
 		)),array(), "EditView");
 		$smarty = new Sugar_Smarty();
 		$smarty->assign("dependencies", $depScript);
 		$smarty->display('modules/ExpressionEngine/tpls/cfTest.tpl');
 	}
}

