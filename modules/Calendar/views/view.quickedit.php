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
require_once('include/EditView/EditView2.php');


class CalendarViewQuickEdit extends SugarView
{
	public $ev;
	protected $editable;
	
	public function preDisplay()
	{
		$this->bean = $this->view_object_map['currentBean'];

		if ($this->bean->ACLAccess('Save')) {
			$this->editable = 1;
		} else {
			$this->editable = 0;
		}
	}

	public function display()
	{

		$module = $this->view_object_map['currentModule'];

		$_REQUEST['module'] = $module;

		$base = 'modules/' . $module . '/metadata/';
		$source = SugarAutoLoader::existingCustomOne($base . 'editviewdefs.php', $base.'quickcreatedefs.php');

		$GLOBALS['mod_strings'] = return_module_language($GLOBALS['current_language'], $module);
        $tpl = SugarAutoLoader::existingCustomOne('include/EditView/EditView.tpl');

		$this->ev = new EditView();
		$this->ev->view = "QuickCreate";
		$this->ev->ss = new Sugar_Smarty();
		$this->ev->formName = "CalendarEditView";
		$this->ev->setup($module,$this->bean,$source,$tpl);
		$this->ev->defs['templateMeta']['form']['headerTpl'] = "modules/Calendar/tpls/editHeader.tpl";
		$this->ev->defs['templateMeta']['form']['footerTpl'] = "modules/Calendar/tpls/empty.tpl";
		$this->ev->process(false, "CalendarEditView");
		
		if (!empty($this->bean->id)) {
		    require_once('include/json_config.php');
		    $jsonConfig = new json_config();
		    $grJavascript = $jsonConfig->getFocusData($module, $this->bean->id);
        } else {
            $grJavascript = "";
        }	
	
		$jsonArr = array(
				'access' => 'yes',
				'module_name' => $this->bean->module_dir,
				'record' => $this->bean->id,
				'edit' => $this->editable,
				'html'=> $this->ev->display(false, true),
				'gr' => $grJavascript,
                'acl' => array(
                    'delete' => $this->bean->aclAccess('delete'),
                ),
		);
		
		if (!empty($this->view_object_map['repeatData'])) {
			$jsonArr = array_merge($jsonArr, array("repeat" => $this->view_object_map['repeatData']));
		}
			
		ob_clean();
		echo json_encode($jsonArr);
	}
}

?>
