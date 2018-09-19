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


class ViewEditFormula extends SugarView
{
    public function __construct()
    {
		$this->options['show_footer'] = false;
		if (isset ($_REQUEST['embed']) && $_REQUEST['embed'])
		{
			$this->options['show_header'] = false;
		}
        parent::__construct();

 	}

 	function display(){
 		global $app_strings, $current_user, $mod_strings, $theme, $beanList, $beanFiles;
		$smarty = new Sugar_Smarty();
 		$json = new JSON();
		//Load the field list from the target module
        if(!empty($_REQUEST['targetModule']) && $_REQUEST['targetModule'] != 'undefined')
 		{
			$module = $_REQUEST['targetModule'];
 			if (isset($_REQUEST['package']) && $_REQUEST['package'] != 'studio' && $_REQUEST['package'] != '') {
				//Get the MB Parsers
 				$pak = new MBPackage($_REQUEST['package']);
 				$defs = $pak->modules[$module]->getVardefs();
                $fields = FormulaHelper::cleanFields(array_merge($pak->modules[$module]->getLinkFields(), $defs['fields']));
 			} else {
 			    $seed = BeanFactory::newBean($module);
	        	$fields = FormulaHelper::cleanFields($seed->field_defs);
 			}
        	$smarty->assign('Field_Array', $json->encode($fields));
		}
		else
		{
			$fields = array(array('income', 'number'), array('employed', 'boolean'), array('first_name', 'string'), array('last_name', 'string'));
			$smarty->assign('Field_Array', $json->encode($fields));
		}

        $request_target_field = $this->request->getValidInputRequest('targetField');
        $smarty->assign("target", $request_target_field);

        $request_return_type = $this->request->getValidInputRequest('returnType');
        $smarty->assign("returnType", $request_return_type);

		//Check if we need to load Ext ourselves
 		if (!isset($_REQUEST['loadExt']) || ($_REQUEST['loadExt'] && $_REQUEST['loadExt'] != "false"))
		{
			$smarty->assign('loadExt', true);
		} else
		{
			$smarty->assign('loadExt', false);
		}
        $request_formula = $this->request->getValidInputRequest('formula');
        $smarty->assign('formula', $json->decode($request_formula));

 		$smarty->assign('app_strings', $app_strings);
 		$smarty->assign('mod', $mod_strings);
 		$smarty->display('modules/ExpressionEngine/tpls/formulaBuilder.tpl');
 	}
}
