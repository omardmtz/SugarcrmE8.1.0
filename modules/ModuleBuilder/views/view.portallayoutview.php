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

use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;


class ViewPortalLayoutView extends ViewLayoutView
{
    public function __construct($bean = null, $view_object_map = array(), Request $request = null)
	{
        parent::__construct($bean, $view_object_map, $request);
		$GLOBALS['log']->debug('in ViewPortalLayoutView');
		$this->editModule = $this->request->getValidInputRequest('view_module', 'Assert\Mvc\ModuleName');
		$this->editLayout = $this->request->getValidInputRequest('view', 'Assert\ComponentName');
	}

	/**
	 * @see SugarView::_getModuleTitleParams()
	 */
	protected function _getModuleTitleParams($browserTitle = false)
	{
	    global $mod_strings;
	    
    	return array(
    	   translate('LBL_MODULE_NAME','Administration'),
    	   ModuleBuilderController::getModuleTitle(),
    	   );
    }

	// DO NOT REMOVE - overrides parent ViewEdit preDisplay() which attempts to load a bean for a non-existent module
	function preDisplay() 
	{
	}

    /**
     * {@inheritDoc}
     *
     * @param bool $preview Ignored, for compatibility with parent method
     */
    public function display($preview = false)
	{
	    $this->parser = ParserFactory::getParser(MB_PORTAL . strtolower($this->editLayout),$this->editModule,null,null,MB_PORTAL);
		$smarty = new Sugar_Smarty();
		
		//Add in the module we are viewing to our current mod strings
		global $mod_strings, $current_language;
		$editModStrings = return_module_language($current_language, $this->editModule);
		$mod_strings = sugarArrayMerge($editModStrings, $mod_strings);
		$smarty->assign('mod', $mod_strings);
		$smarty->assign('MOD', $mod_strings);
		
		// assign buttons
		$images = array('icon_save' => 'studio_save', 'icon_publish' => 'studio_publish', 'icon_address' => 'icon_Address', 'icon_emailaddress' => 'icon_EmailAddress', 'icon_phone' => 'icon_Phone');
		foreach($images as $image=>$file) {
			$smarty->assign($image,SugarThemeRegistry::current()->getImage($file, ''
,null,null,'.gif',$file));
		}
		$smarty->assign('icon_delete',SugarThemeRegistry::current()->getImage('icon_Delete','',48,48,'.gif',$mod_strings['LBL_MB_DELETE'] ));

		$buttons = array();
		$buttons[] = array(
		  'id'=>'saveBtn',
		  'image'=>SugarThemeRegistry::current()->getImage($images['icon_save'],'',null,null,'.gif',$mod_strings['LBL_BTN_SAVE']),
		  'text'=>$GLOBALS['mod_strings']['LBL_BTN_SAVE'],
		  'actionScript'=>"onclick='if(Studio2.checkCalcFields(" . JSON::encode($this->editLayout)
				  . ", \"ERROR_CALCULATED_PORTAL_FIELDS\"))Studio2.handleSave();'"
		);
		$buttons[] = array(
		  'id'=>'publishBtn',
		  'image'=>SugarThemeRegistry::current()->getImage($images['icon_publish'],'',null,null,'.gif',$mod_strings['LBL_BTN_PUBLISH']),
		  'text'=>$GLOBALS['mod_strings']['LBL_BTN_SAVEPUBLISH'],
		  'actionScript'=>"onclick='if(Studio2.checkCalcFields(" . JSON::encode($this->editLayout)
				  . ", \"ERROR_CALCULATED_PORTAL_FIELDS\"))Studio2.handlePublish();'"
		);

		$html = "";
		foreach($buttons as $button){
		    if ($button['id'] == "spacer") {
                $html .= "<td style='width:{$button['width']}'> </td>";
            } else {
                $html .= "<td><input id='{$button['id']}' type='button' valign='center' class='button' style='cursor:pointer' "
                   . "onmousedown='this.className=\"buttonOn\";return false;' onmouseup='this.className=\"button\"' "
                   . "onmouseout='this.className=\"button\"' {$button['actionScript']} value = '{$button['text']}' ></td>" ;
            }
		}

		$smarty->assign('buttons', $html);

		// assign fields and layout
		$smarty->assign('available_fields', $this->parser->getAvailableFields());
        $smarty->assign ( 'field_defs', $this->parser->getFieldDefs () ) ;
		$smarty->assign('layout', $this->parser->getLayout());
		$smarty->assign('view_module', $this->editModule);
		$smarty->assign('calc_field_list', json_encode($this->parser->getCalculatedFields()));
		$smarty->assign('view', $this->editLayout);
        $smarty->assign('maxColumns', $this->parser->getMaxColumns());
		$smarty->assign('fieldwidth', '150px');
		$smarty->assign('translate',true);
		$smarty->assign('fromPortal',true); // flag for form submittal - when the layout is submitted the actions are the same for layouts and portal layouts, but the parsers must be different...

		if (!empty($this->parser->usingWorkingFile)) {
			$smarty->assign('layouttitle',$GLOBALS['mod_strings']['LBL_LAYOUT_PREVIEW']);
		} else {
			$smarty->assign('layouttitle',$GLOBALS['mod_strings']['LBL_CURRENT_LAYOUT']);
		}

		$ajax = new AjaxCompose();

		$ajax->addCrumb(translate('LBL_SUGARPORTAL', 'ModuleBuilder'), 'ModuleBuilder.main("sugarportal")');
        $ajax->addCrumb(translate('LBL_LAYOUTS', 'ModuleBuilder'), 'ModuleBuilder.getContent("module=ModuleBuilder&action=wizard&portal=1&layout=1")');
        $ajax->addCrumb(ucwords(translate('LBL_MODULE_NAME',$this->editModule)), 'ModuleBuilder.getContent("module=ModuleBuilder&action=wizard&portal=1&view_module='.$this->editModule.'")');
        $ajax->addCrumb(ucwords($this->editLayout), '');
		// set up language files
		$smarty->assign('language',$this->parser->getLanguage());	// for sugar_translate in the smarty template

		//navjeet- assistant logic has changed
		//include('modules/ModuleBuilder/language/en_us.lang.php');
		//$smarty->assign('assistantBody', $mod_strings['assistantHelp']['module']['editView'] );
		$ajax->addSection('center', $GLOBALS['mod_strings']['LBL_EDIT_LAYOUT'],$smarty->fetch('modules/ModuleBuilder/tpls/layoutView.tpl'));
		echo $ajax->getJavascript();
	}
}
