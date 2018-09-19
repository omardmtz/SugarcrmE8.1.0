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
require_once ('modules/ModuleBuilder/MB/ModuleBuilder.php') ;

class ViewRelationships extends SugarView
{
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

    function display()
    {
        $moduleName = $this->request->getValidInputRequest(
            'view_module',
            'Assert\ComponentName',
            $this->request->getValidInputRequest('edit_module', 'Assert\ComponentName')
        );
        $packageName = $this->request->getValidInputRequest('view_package', 'Assert\ComponentName');
        // set the mod_strings as we can be called after doing a Repair and the mod_strings are set to Administration
        $GLOBALS['mod_strings'] = return_module_language($GLOBALS['current_language'], 'ModuleBuilder');
        $this->_initSmarty();
        $this->ss->assign('mod_strings', $GLOBALS [ 'mod_strings' ]);
        $this->ss->assign('view_module', $moduleName);

        $ajax = new AjaxCompose ( ) ;
        $json = getJSONobj () ;
        $this->fromModuleBuilder = !empty($_REQUEST['MB']) || (!empty($packageName) && ($packageName != 'studio'));
        $this->ss->assign('fromModuleBuilder', $this->fromModuleBuilder);
        if (!$this->fromModuleBuilder)
        {
            $this->ss->assign('view_package', '');

            $relationships = new DeployedRelationships ( $moduleName ) ;
            $ajaxRelationships = $this->getAjaxRelationships( $relationships ) ;
            $this->ss->assign('relationships', $json->encode($ajaxRelationships));
            $this->ss->assign('empty', (sizeof($ajaxRelationships) == 0));
            $this->ss->assign('studio', true);

            //crumb
            global $app_list_strings ;
            $moduleNames = array_change_key_case ( $app_list_strings [ 'moduleList' ] ) ;
            $translatedModule = $moduleNames [ strtolower ( $moduleName ) ] ;
            $ajax->addCrumb ( translate('LBL_STUDIO'), 'ModuleBuilder.main("studio")' ) ;
            $ajax->addCrumb ( $translatedModule, 'ModuleBuilder.getContent("module=ModuleBuilder&action=wizard&view_module=' . $moduleName . '")' ) ;
            $ajax->addCrumb ( translate('LBL_RELATIONSHIPS'), '' ) ;
            $ajax->addSection(
                'center',
                $moduleName . ' ' . translate('LBL_RELATIONSHIPS'),
                $this->fetchTemplate('modules/ModuleBuilder/tpls/studioRelationships.tpl')
            );

        } else
        {
            $this->ss->assign('view_package', $packageName);

            $mb = new ModuleBuilder ( ) ;
            $module = &$mb->getPackageModule($packageName, $moduleName);
            $package = $mb->packages[$packageName];
			$package->loadModuleTitles();
            $relationships = new UndeployedRelationships ( $module->getModuleDir () ) ;
            $ajaxRelationships = $this->getAjaxRelationships( $relationships ) ;
            $this->ss->assign('relationships', $json->encode($ajaxRelationships));
            $this->ss->assign('empty', (sizeof($ajaxRelationships) == 0));

            $module->help['default'] = (empty($moduleName)) ? 'create' : 'modify';
            $module->help['group'] = 'module';

            $ajax->addCrumb ( translate('LBL_MODULEBUILDER'), 'ModuleBuilder.main("mb")' ) ;
            $ajax->addCrumb ( $package->name, 'ModuleBuilder.getContent("module=ModuleBuilder&action=package&package=' . $package->name . '")' ) ;
            $ajax->addCrumb ( $moduleName, 'ModuleBuilder.getContent("module=ModuleBuilder&action=module&view_package=' . $package->name . '&view_module=' . $moduleName . '")' ) ;
            $ajax->addCrumb ( translate('LBL_RELATIONSHIPS'), '' ) ;
            $ajax->addSection(
                'center',
                $moduleName . ' ' . translate('LBL_RELATIONSHIPS'),
                $this->fetchTemplate('modules/ModuleBuilder/tpls/studioRelationships.tpl')
            );
        }
        echo $ajax->getJavascript () ;
    }

    /*
     * Encode the relationships for this module for display in the Ext grid layout
     */
    function getAjaxRelationships ( $relationships )
    {
        $ajaxrels = array ( ) ;
        foreach ( $relationships->getRelationshipList () as $relationshipName )
        {
            $rel = $relationships->get ( $relationshipName )->getDefinition () ;
            if (!empty($rel['lhs_vname'])) {
                $rel['lhs_module'] = translate($rel['lhs_vname']);
            } else {
                $rel['lhs_module'] = translate($rel['lhs_module']);
            }
            if (!empty($rel['rhs_vname'])) {
                $rel['rhs_module'] = translate($rel['rhs_vname']);
            } else {
                $rel['rhs_module'] = translate($rel['rhs_module']);
            }
            
            //#28668  , translate the relationship type before render it .
            switch($rel['relationship_type']){
            	case 'one-to-one':
            	$rel['relationship_type']  = translate ( 'LBL_ONETOONE' );
            	break;
            	case 'one-to-many':
            	$rel['relationship_type']  = translate ( 'LBL_ONETOMANY' );
            	break;
            	case 'many-to-one':
            	$rel['relationship_type']  = translate ( 'LBL_MANYTOONE' );
            	break;
            	case 'many-to-many':
            	$rel['relationship_type']  = translate ( 'LBL_MANYTOMANY' );
            	break;
            	default: $rel['relationship_type']  = '';
            }
            $rel [ 'name' ] = $relationshipName ;
            if ($rel [ 'is_custom' ] && isset($rel [ 'from_studio' ]) && $rel [ 'from_studio' ]) {
            	$rel [ 'name' ] = $relationshipName . "*";
            }
            $ajaxrels [] = $rel ;
        }
        return $ajaxrels ;
    }

    /**
     * fetchTemplate
     * This function overrides fetchTemplate from SugarView.
     *
     * @param string $template the file to fetch
     * @return string contents from calling the fetch method on the Sugar_Smarty instance
     */
    protected function fetchTemplate($template)
    {
        if (func_num_args() > 1) { // for BC, @todo remove it in future
            $GLOBALS['log']->deprecated('Invalid call to ' . __METHOD__ . ', only $template argument is expected');
            if (!is_string($template)) {
                $template = func_get_arg(1);
            }
        }
        return $this->ss->fetch($this->getCustomFilePathIfExists($template));
    }
}
