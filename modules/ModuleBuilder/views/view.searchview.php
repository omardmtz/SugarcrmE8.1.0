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

 // $Id: EditView.php 18703 2006-12-15 09:42:43Z majed $


require_once 'modules/ModuleBuilder/parsers/constants.php' ;

class ViewSearchView extends ViewListView
{
 	public function __construct()
 	{
 		parent::__construct();
 		if (!empty($_REQUEST['searchlayout'])) {
 			$this->editLayout = $this->request->getValidInputRequest('searchlayout', 'Assert\ComponentName');
 		}
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

 	function display(
 	    $preview = false
 	    )
 	{
 		$packageName = $this->request->getValidInputRequest('view_package', 'Assert\ComponentName');
 		$parser = ParserFactory::getParser ( $this->editLayout , $this->editModule, $packageName ) ;

 		$smarty = parent::constructSmarty ( $parser ) ;
 		$smarty->assign ( 'action', 'searchViewSave' ) ;
 		$smarty->assign ( 'view', $this->editLayout ) ;
 		$smarty->assign ( 'helpName', 'searchViewEditor' ) ;
 		$smarty->assign ( 'helpDefault', 'modify' ) ;

 		if ($preview)
 		{
 			echo $smarty->fetch ( "modules/ModuleBuilder/tpls/Preview/listView.tpl" ) ;
 		} else
 		{
 			$ajax = $this->constructAjax () ;
 			$ajax->addSection ( 'center', translate ($this->title), $smarty->fetch ( "modules/ModuleBuilder/tpls/listView.tpl" ) ) ;
 			echo $ajax->getJavascript () ;
 		}
 	}

 	function constructAjax()
 	{
 		$ajax = new AjaxCompose ( ) ;
 		switch ( $this->editLayout )
 		{
 			case MB_WIRELESSBASICSEARCH:
 			case MB_WIRELESSADVANCEDSEARCH:
 				$searchLabel = 'LBL_WIRELESSSEARCH' ;
 				break;
 			default:
                if(isModuleBWC($this->editModule)) {
                    $searchLabel = 'LBL_' . strtoupper($this->editLayout);
                } else {
                    $searchLabel = 'LBL_FILTER_SEARCH';
                }
                break;
 		}

        $layoutLabel = 'LBL_LAYOUTS' ;
        $layoutView = 'layouts' ;

        if ( in_array ( $this->editLayout , array ( MB_WIRELESSBASICSEARCH , MB_WIRELESSADVANCEDSEARCH ) ) )
        {
        	$layoutLabel = 'LBL_WIRELESSLAYOUTS' ;
        	$layoutView = 'wirelesslayouts' ;
        }

        if ($this->fromModuleBuilder) {
            $package = $this->request->getValidInputRequest('view_package', 'Assert\ComponentName');
            $ajax->addCrumb(translate('LBL_MODULEBUILDER', 'ModuleBuilder'), 'ModuleBuilder.main("mb")');
            $ajax->addCrumb(
                $package,
                'ModuleBuilder.getContent("module=ModuleBuilder&action=package&package=' . $package . '")'
            );
            $ajax->addCrumb(
                $this->editModule,
                'ModuleBuilder.getContent("module=ModuleBuilder&action=module&view_package='
                . $package . "&view_module={$this->editModule}" . '")'
            );
            $ajax->addCrumb(
                translate($layoutLabel, 'ModuleBuilder'),
                'ModuleBuilder.getContent("module=ModuleBuilder&MB=true&action=wizard&view_module='
                . $this->editModule . '&view_package=' . $package . '")'
            );
            if ($layoutLabel == 'LBL_LAYOUTS') {
                $ajax->addCrumb(
                    translate('LBL_SEARCH_FORMS', 'ModuleBuilder'),
                    'ModuleBuilder.getContent("module=ModuleBuilder&MB=true&action=wizard&view=search&view_module='
                    . $this->editModule . '&view_package=' . $package . '")'
                );
            }
            $ajax->addCrumb(translate($searchLabel, 'ModuleBuilder'), '');
        } else {
            $ajax->addCrumb(translate('LBL_STUDIO', 'ModuleBuilder'), 'ModuleBuilder.main("studio")');
            $ajax->addCrumb(
                $this->translatedEditModule,
                'ModuleBuilder.getContent("module=ModuleBuilder&action=wizard&view_module=' . $this->editModule . '")'
            );
            $ajax->addCrumb(
                translate($layoutLabel, 'ModuleBuilder'),
                'ModuleBuilder.getContent("module=ModuleBuilder&action=wizard&view=' . $layoutView
                . '&view_module=' . $this->editModule . '")'
            );
            if ($layoutLabel == 'LBL_LAYOUTS') {
                $ajax->addCrumb(
                    translate('LBL_SEARCH_FORMS', 'ModuleBuilder'),
                    'ModuleBuilder.getContent("module=ModuleBuilder&action=wizard&view=search&view_module='
                    . $this->editModule . '")'
                );
            }
            $ajax->addCrumb(translate($searchLabel, 'ModuleBuilder'), '');
        }
        $this->title = $searchLabel;

        return $ajax;
    }
}
