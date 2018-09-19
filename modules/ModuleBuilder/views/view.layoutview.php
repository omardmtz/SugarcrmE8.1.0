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

require_once 'modules/ModuleBuilder/parsers/constants.php' ;

class ViewLayoutView extends SugarView
{
    /** @var GridLayoutMetaDataParser */
    protected $parser;

    public function __construct($bean = null, $view_object_map = array(), $request = null)
    {
        parent::__construct($bean, $view_object_map, $request);
        $GLOBALS ['log']->debug('in ViewLayoutView');
        $this->editModule = $this->request->getValidInputRequest('view_module', 'Assert\ComponentName');
        $this->editLayout = $this->request->getValidInputRequest('view','Assert\ComponentName');
        $this->package = $this->request->getValidInputRequest('view_package', 'Assert\ComponentName');
        $mb = $this->request->getValidInputRequest('MB');
        $this->fromModuleBuilder = !is_null($mb) || !empty($this->package);
        if ($this->fromModuleBuilder) {
            $this->type = $this->editLayout;
        } else {
            global $app_list_strings;
            $moduleNames = array_change_key_case($app_list_strings ['moduleList']);
            $this->translatedEditModule = $moduleNames [strtolower($this->editModule)];
            $this->sm = StudioModuleFactory::getStudioModule($this->editModule);
            $this->type = $this->sm->getViewType($this->editLayout);
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
    function preDisplay ()
    {
    }

    function display ($preview = false)
    {
        global $mod_strings ;
        $params = array();
        $role = $this->request->getValidInputRequest('role', 'Assert\Guid');
        if (!empty($role)) {
            $params['role'] = $role;
        }
        $this->parser = $parser = ParserFactory::getParser(
            $this->editLayout,
            $this->editModule,
            $this->package,
            null,
            null,
            $params
        );
        $history = $parser->getHistory () ;
        $smarty = $this->getSmarty();
        //Add in the module we are viewing to our current mod strings
		if (! $this->fromModuleBuilder) {
			global $current_language;
			$editModStrings = return_module_language($current_language, $this->editModule);
			$mod_strings = sugarArrayMerge($editModStrings, $mod_strings);
		}
        $smarty->assign('mod', $mod_strings);
		$smarty->assign('MOD', $mod_strings);
        // assign buttons
        $images = array ( 'icon_save' => 'studio_save' , 'icon_publish' => 'studio_publish' , 'icon_address' => 'icon_Address' , 'icon_emailaddress' => 'icon_EmailAddress' , 'icon_phone' => 'icon_Phone' ) ;
        foreach ( $images as $image => $file )
        {
            $smarty->assign ( $image, SugarThemeRegistry::current()->getImage($file,'',null,null,'.gif',$file) ) ;
        }

        $requiredFields = implode($parser->getRequiredFields () , ',');
        $slashedRequiredFields = addslashes($requiredFields);
        $buttons = array ( ) ;
        $disableLayout = false;

        if ($preview)
        {
            $smarty->assign ( 'layouttitle', translate ( 'LBL_LAYOUT_PREVIEW', 'ModuleBuilder' ) ) ;
        } else
        {
            $smarty->assign ( 'layouttitle', translate ( 'LBL_CURRENT_LAYOUT', 'ModuleBuilder' ) ) ;

            //Check if we need to synch edit view to other layouts
            if($this->editLayout == MB_DETAILVIEW || $this->editLayout == MB_QUICKCREATE){
		        $parser2 = ParserFactory::getParser(MB_EDITVIEW,$this->editModule,$this->package);
                if($this->editLayout == MB_DETAILVIEW){
		            $disableLayout = $parser2->getSyncDetailEditViews();
                }

                $copyFromEditView = $this->request->getValidInputRequest('copyFromEditView');
                if(!empty($copyFromEditView)){
                    $editViewPanels = $parser2->convertFromCanonicalForm($parser2->_viewdefs['panels']);
                    $parser->_viewdefs [ 'panels' ] = $editViewPanels;
                    $parser->_fielddefs = $parser2->_fielddefs;
                    $parser->setUseTabs($parser2->getUseTabs());
                    $parser->setTabDefs($parser2->getTabDefs());
                }
		    }

            $buttons = $this->getButtons($history, $disableLayout, $params);

            $implementation = $parser->getImplementation();
            $roles = $this->getRoleList($implementation);
            $copyFromOptions = $this->getRoleListWithMetadata($roles, $role);
            $smarty->assign('copy_from_options', $copyFromOptions);
        }

        $available_fields = $parser->getAvailableFields();
        $field_defs = $parser->getFieldDefs();

        foreach($available_fields as $key => $value) {
            if(isset($field_defs[$value['name']]['studio']) && $field_defs[$value['name']]['studio'] === false) {
                unset($available_fields[$key]);
            }
        }

        $smarty->assign('buttons', $this->getButtonHTML($buttons));

        // assign fields and layout
        $smarty->assign ( 'available_fields', $available_fields ) ;
        
        $smarty->assign ( 'disable_layout', $disableLayout) ;
        $smarty->assign ( 'required_fields', $requiredFields) ;
        $smarty->assign ( 'layout', $parser->getLayout () ) ;
        $smarty->assign ( 'field_defs', $field_defs ) ;
        $smarty->assign ( 'view_module', $this->editModule ) ;
        $smarty->assign ( 'view', $this->editLayout ) ;
        $smarty->assign('selected_role', $role);
        $smarty->assign ( 'maxColumns', $parser->getMaxColumns() ) ;
        $smarty->assign ( 'nextPanelId', $parser->getFirstNewPanelId() ) ;
        $smarty->assign ( 'displayAsTabs', $parser->getUseTabs() ) ;
        $smarty->assign ( 'tabDefs', $parser->getTabDefs() ) ;
        $smarty->assign ( 'syncDetailEditViews', $parser->getSyncDetailEditViews() ) ;
        $smarty->assign('fieldwidth', 300 / $parser->getMaxColumns());
        // Bug 57260 - LBL_PANEL_DEFAULT not translated for undeployed modules in layout editor
        $smarty->assign ( 'translate', true ) ;

        if ($this->fromModuleBuilder)
        {
            $smarty->assign ( 'fromModuleBuilder', $this->fromModuleBuilder ) ;
            $smarty->assign ( 'view_package', $this->package ) ;
        }

        // Layout labels for the breadcrumb
        $labels = array (
            MB_EDITVIEW => 'LBL_EDITVIEW' ,
            MB_DETAILVIEW => 'LBL_DETAILVIEW' ,
            MB_QUICKCREATE => 'LBL_QUICKCREATE',
            MB_RECORDVIEW => 'LBL_RECORDVIEW',
            MB_WIRELESSEDITVIEW => 'LBL_WIRELESSEDITVIEW' ,
            MB_WIRELESSDETAILVIEW => 'LBL_WIRELESSDETAILVIEW' ,
        );

        $layoutLabel = 'LBL_LAYOUTS' ;
        $layoutView = 'layouts' ;

        if ( in_array ( $this->editLayout , array ( MB_WIRELESSEDITVIEW , MB_WIRELESSDETAILVIEW ) ) )
        {
        	$layoutLabel = 'LBL_WIRELESSLAYOUTS' ;
        	$layoutView = 'wirelesslayouts' ;
        	$smarty->assign('wireless', true);
        }

        $ajax = new AjaxCompose ( ) ;

        $translatedViewType = '' ;
		if ( isset ( $labels [ strtolower ( $this->editLayout ) ] ) )
			$translatedViewType = translate ( $labels [ strtolower( $this->editLayout ) ] , 'ModuleBuilder' ) ;
        else if (isset($this->sm))
        {
            foreach($this->sm->sources as $file => $def)
            {
                if (!empty($def['view']) && $def['view'] == $this->editLayout && !empty($def['name']))
                {
                    $translatedViewType = $def['name'];
                }
            }
            if(empty($translatedViewType))
            {
                $label = "LBL_" . strtoupper($this->editLayout);
                $translated = translate($label, $this->editModule);
                if ($translated != $label)
                    $translatedViewType =  $translated;
            }
        }

        if ($this->fromModuleBuilder) {
            $ajax->addCrumb(translate('LBL_MODULEBUILDER', 'ModuleBuilder'), 'ModuleBuilder.main("mb")');
            $ajax->addCrumb(
                $this->package,
                'ModuleBuilder.getContent("module=ModuleBuilder&action=package&package=' . $this->package . '")'
            );
            $ajax->addCrumb(
                $this->editModule,
                'ModuleBuilder.getContent("module=ModuleBuilder&action=module&view_package='
                . $this->package . '&view_module=' . $this->editModule . '")'
            );
            $ajax->addCrumb(
                translate($layoutLabel, 'ModuleBuilder'),
                'ModuleBuilder.getContent("module=ModuleBuilder&MB=true&action=wizard&view='
                . $layoutView . '&view_module=' . $this->editModule . '&view_package=' . $this->package . '")'
            );
            $ajax->addCrumb($translatedViewType, '');
        } else {
            $ajax->addCrumb(translate('LBL_STUDIO', 'ModuleBuilder'), 'ModuleBuilder.main("studio")');
            $ajax->addCrumb(
                $this->translatedEditModule,
                'ModuleBuilder.getContent("module=ModuleBuilder&action=wizard&view_module=' . $this->editModule . '")'
            );
            $ajax->addCrumb(
                translate($layoutLabel, 'ModuleBuilder'),
                'ModuleBuilder.getContent("module=ModuleBuilder&action=wizard&view='
                . $layoutView . '&view_module=' . $this->editModule . '")'
            );
            $ajax->addCrumb($translatedViewType, '');
        }

        // set up language files
		$smarty->assign ( 'language', $parser->getLanguage() ) ; // for sugar_translate in the smarty template
        $smarty->assign('from_mb',$this->fromModuleBuilder);
        $smarty->assign('calc_field_list', json_encode($parser->getCalculatedFields()));
		if ($this->fromModuleBuilder) {
			$mb = new ModuleBuilder ( ) ;
            $module = & $mb->getPackageModule ( $this->package, $this->editModule ) ;
		    $smarty->assign('current_mod_strings', $module->getModStrings());
		}

        $ajax->addSection(
            'center',
            $translatedViewType,
            $smarty->fetch(SugarAutoLoader::existingCustomOne('modules/ModuleBuilder/tpls/layoutView.tpl'))
        );
        if ($preview) {
        	echo $smarty->fetch ( 'modules/ModuleBuilder/tpls/Preview/layoutView.tpl' );
		} else {
			echo $ajax->getJavascript () ;
    	}
    }

    /**
     * @return Sugar_Smarty
     */
    protected function getSmarty()
    {
        if (is_null($this->ss)) {
            $this->ss = new Sugar_Smarty();
        }
        return $this->ss;
    }

    protected function getButtons($history, $disableLayout, $params)
    {
        $buttons = array();
        if (!$this->fromModuleBuilder) {
            $buttons [] = array(
                'id' => 'saveBtn',
                'text' => translate('LBL_BTN_SAVE'),
                'actionScript' => "onclick='if(Studio2.checkGridLayout(\"{$this->editLayout}\")) Studio2.handleSave();'",
                'disabled' => $disableLayout,
            );
            $buttons [] = array(
                'id' => 'publishBtn',
                'text' => translate('LBL_BTN_SAVEPUBLISH'),
                'actionScript' => "onclick='if(Studio2.checkGridLayout(\"{$this->editLayout}\")) Studio2.handlePublish();'",
                'disabled' => $disableLayout,
            );
        } else {
            $buttons [] = array(
                'id' => 'saveBtn',
                'text' => $GLOBALS ['mod_strings'] ['LBL_BTN_SAVE'],
                'actionScript' => "onclick='if(Studio2.checkGridLayout(\"{$this->editLayout}\")) Studio2.handlePublish();'",
                'disabled' => $disableLayout,
            );
        }
        $buttons [] = array('id' => 'spacer', 'width' => '33px');
        $buttons [] = array(
            'id' => 'historyBtn',
            'text' => translate('LBL_HISTORY'),
            'actionScript' => "onclick='ModuleBuilder.history.browse(\"{$this->editModule}\", \"{$this->editLayout}\")'",
            'disabled' => $disableLayout,
        );

        if (!$params) {
            $action = 'ModuleBuilder.history.revert('
                . '"' . $this->editModule . '",'
                . '"' . $this->editLayout . '",'
                . '"' . $history->getLast() . '",'
                . '""'
                . ')';
        } else {
            $action = 'ModuleBuilder.history.resetToDefault('
                . '"' . $this->editModule . '",'
                . '"' . $this->editLayout . '"'
                . ')';
        }

        $restoreDefaultDisabled = $disableLayout;

        // Handle Opps+RLI mode switch creating one history item on install.
        if ($this->editModule == 'Opportunities') {
            if ($history->getCount() == 1) {
                $restoreDefaultDisabled = true;
            } else if ($history->getCount() > 1) {
                $historyList = $history->getList();
                $historyItem = $historyList[1];

                $action = 'ModuleBuilder.history.revert('
                    . '"' . $this->editModule . '",'
                    . '"' . $this->editLayout . '",'
                    . '"' . $historyItem . '",'
                    . '""'
                    . ')';
            }
        }

        $buttons [] = array(
            'id' => 'historyDefault',
            'text' => translate('LBL_RESTORE_DEFAULT_LAYOUT'),
            'actionScript' => "onclick='$action'",
            'disabled' => $restoreDefaultDisabled,
        );
        $implementation = $this->parser->getImplementation();
        if ($this->editLayout == MB_DETAILVIEW || $this->editLayout == MB_QUICKCREATE) {
            $buttons [] = array(
                'id' => 'copyFromEditView',
                'text' => translate('LBL_COPY_FROM_EDITVIEW'),
                'actionScript' => "onclick='ModuleBuilder.copyFromView(\"{$this->editModule}\", \"{$this->editLayout}\")'",
                'disabled' => $disableLayout,
            );
        } elseif (!empty($GLOBALS['sugar_config']['roleBasedViews'])
            && !isModuleBWC($this->editModule)
            && ($this->editLayout == MB_RECORDVIEW
                || $this->editLayout == MB_WIRELESSEDITVIEW
                || $this->editLayout == MB_WIRELESSDETAILVIEW)
            && $implementation->isDeployed()) {
            $availableRoles = $this->getRoleList($implementation);
            $buttons [] = array('type' => 'spacer', 'width' => '33px');
            $buttons [] = array('type' => 'label', "text" => translate('LBL_ROLE') . ":");
            $buttons [] = array(
                'id' => 'roleList',
                'type' => 'enum',
                'actionScript' => 'style="max-width:150px" onchange="ModuleBuilder.switchLayoutRole(this)"',
                "options" => $this->getAvailableRoleList($implementation),
                "selected" => empty($params['role']) ? "" :  $params['role'],
            );

            if (!empty($params['role'])) {
                $rolesWithMetadata = $this->getRoleListWithMetadata($availableRoles, $params['role']);
                $buttons [] = array(
                    'id' => 'copyBtn',
                    'text' => translate('LBL_BTN_COPY_FROM'),
                    'actionScript' => "onclick='ModuleBuilder.copyLayoutFromRole();'",
                    'disabled' => !count($rolesWithMetadata),
                );
            }
        }
        return $buttons;
    }

    protected function getButtonHTML(array $buttons)
    {
        $html = "";
        foreach ($buttons as $button) {
            if ((isset($button['id']) && $button['id'] == "spacer") ||
                (isset($button['type']) && $button['type'] == "spacer")
            ) {
                $html .= "<td style='width:{$button['width']}'> </td>";
            } elseif (isset($button['type']) && $button['type'] == "enum") {
                $button['actionScript'] = empty($button['actionScript']) ? "" : $button['actionScript'];
                $html .= "<td><select id={$button['id']} {$button['actionScript']}>"
                    . get_select_options_with_id(
                        $button['options'],
                        $button['selected']
                    ) . "</select></td>";
            } elseif (isset($button['type']) && $button['type'] == "label") {
                $html .= "<td><span class='label'>{$button['text']}</span></td>";
            } else {
                $html .= "<td><input id='{$button['id']}' type='button' valign='center' class='button' style='cursor:pointer' "
                    . "onmousedown='this.className=\"buttonOn\";return false;' onmouseup='this.className=\"button\"' "
                    . "onmouseout='this.className=\"button\"' {$button['actionScript']} value = '{$button['text']}'";
                if (!empty($button['disabled'])) {
                    $html .= " disabled";
                }
                $html .= "></td>";
            }
        }
        return $html;
    }

    /**
     * Returns object storage containing available roles as keys
     * and flags indicating if there is role specific metadata as value
     *
     * @param MetaDataImplementationInterface $implementation
     * @return SplObjectStorage
     */
    protected function getRoleList(MetaDataImplementationInterface $implementation)
    {
        return MBHelper::getRoles($this->getHasMetaCallback($implementation));
    }

    /**
     * Returns list of roles with marker indicating whether role specific metadata exists
     *
     * @param MetaDataImplementationInterface $implementation
     * @return array
     */
    protected function getAvailableRoleList(MetaDataImplementationInterface $implementation)
    {
        return MBHelper::getAvailableRoleList($this->getHasMetaCallback($implementation));
    }

    /**
     * Returns list of roles which have role specific metadata
     *
     * @param SplObjectStorage $roles
     * @param $currentRole
     * @return array
     */
    protected function getRoleListWithMetadata(SplObjectStorage $roles, $currentRole)
    {
        $result = array();
        foreach ($roles as $role) {
            $hasMetadata = $roles->offsetGet($role);
            if ($hasMetadata && $role->id != $currentRole) {
                $result[$role->id] = $role->name;
            }
        }

        return $result;
    }

    /**
     * @param MetaDataImplementationInterface $implementation
     *
     * @return callable
     */
    protected function getHasMetaCallback(MetaDataImplementationInterface $implementation) {
        $editLayout = $this->editLayout;
        $editModule = $this->editModule;

        return function($params) use ($implementation, $editLayout, $editModule) {
            //Remove roles that should not be used on normal users.
            return $implementation->fileExists(
                $editLayout,
                $editModule,
                MB_CUSTOMMETADATALOCATION,
                array(
                    'role' => $params['role'],
                )
            );
        };
    }
}
