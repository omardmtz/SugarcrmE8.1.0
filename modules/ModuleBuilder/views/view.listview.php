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

class ViewListView extends SugarView
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

    /*
     * Pseudo-constructor to enable subclasses to call a parent's constructor without knowing the parent in PHP4
     */
    function __construct($bean = null, $view_object_map = array(), $request = null)
    {
        parent::__construct($bean, $view_object_map, $request);
        $this->editModule = $this->request->getValidInputRequest('view_module', 'Assert\ComponentName');
        $this->editLayout = $this->request->getValidInputRequest('view', 'Assert\ComponentName');
        $this->subpanel = $this->request->getValidInputRequest('subpanel');
        $this->subpanelLabel = $this->request->getValidInputRequest('subpanelLabel', null, false);
        $this->package = $this->request->getValidInputRequest('view_package', 'Assert\ComponentName');
        $this->fromModuleBuilder = !empty($this->package);

        if (!$this->fromModuleBuilder) {
            $moduleNames = array_change_key_case ( $GLOBALS['app_list_strings'] [ 'moduleList' ] ) ;
            $this->translatedEditModule = $moduleNames [ strtolower ( $this->editModule ) ] ;
        }
    }

    // DO NOT REMOVE - overrides parent ViewEdit preDisplay() which attempts to load a bean for a non-existent module
    function preDisplay ()
    {
    }

    function display ($preview = false)
    {
        $subpanelName = (!empty($this->subpanel)) ? $this->subpanel : null;
        $parser = ParserFactory::getParser($this->editLayout, $this->editModule, $this->package, $subpanelName);
        $smarty = $this->constructSmarty ( $parser ) ;

        if ($preview)
        {
            echo $smarty->fetch ( "modules/ModuleBuilder/tpls/Preview/listView.tpl" ) ;
        } else
        {
            $ajax = $this->constructAjax () ;
            $ajax->addSection ( 'center', $this->translatedViewType, $smarty->fetch ( "modules/ModuleBuilder/tpls/listView.tpl" ) ) ;

            echo $ajax->getJavascript () ;
        }
    }

    function constructAjax ()
    {
        $ajax = new AjaxCompose ( ) ;

        $layoutLabel = 'LBL_LAYOUTS' ;
        $layoutView = 'layouts' ;

        if ( $this->editLayout == MB_WIRELESSLISTVIEW )
        {
        	$layoutLabel = 'LBL_WIRELESSLAYOUTS' ;
        	$layoutView = 'wirelesslayouts' ;
        }

        $labels = array (
        			MB_LISTVIEW => 'LBL_LISTVIEW' ,
        			MB_WIRELESSLISTVIEW => 'LBL_WIRELESSLISTVIEW' ,
        			) ;
        $translatedViewType = '' ;
		if ( isset ( $labels [ strtolower ( $this->editLayout ) ] ) )
			$translatedViewType = translate ( $labels [ strtolower( $this->editLayout ) ] , 'ModuleBuilder' ) ;
		$this->translatedViewType = $translatedViewType;

        $subpanelTitle = $this->request->getValidInputRequest('subpanel_title');

        if ($this->fromModuleBuilder)
        {
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
            if ($this->subpanel != "") {
                $ajax->addCrumb ( translate ( 'LBL_AVAILABLE_SUBPANELS', 'ModuleBuilder' ), '' ) ;
                if ($this->subpanelLabel) {
                    $subpanelLabel = $this->subpanelLabel;
                    // If the subpanel title has changed, use that for the label instead
                    if (!empty($subpanelTitle) && $this->subpanelLabel != $subpanelTitle) {
                        $subpanelLabel = $subpanelTitle;
                    }

                    $ajax->addCrumb( $subpanelLabel, '' );
                    $this->translatedViewType = $subpanelLabel . "&nbsp;" . translate("LBL_SUBPANEL", "ModuleBuilder");
                } else
                {
                    $ajax->addCrumb ( $this->subpanel, '' ) ;
                    $this->translatedViewType = translate("LBL_SUBPANEL", "ModuleBuilder");
                }
            } else
            {
                $ajax->addCrumb ( translate ( $layoutLabel, 'ModuleBuilder' ), 'ModuleBuilder.getContent("module=ModuleBuilder&MB=true&action=wizard&view_module=' . $this->editModule . '&view_package=' . $this->package . '")' ) ;
                $ajax->addCrumb ( $translatedViewType, '' ) ;
            }
        } else
        {
            $ajax->addCrumb ( translate ( 'LBL_STUDIO', 'ModuleBuilder' ), 'ModuleBuilder.getContent("module=ModuleBuilder&action=wizard")' ) ;
            $ajax->addCrumb ( $this->translatedEditModule, 'ModuleBuilder.getContent("module=ModuleBuilder&action=wizard&view_module=' . $this->editModule . '")' ) ;

            if ($this->subpanel)
            {
                $ajax->addCrumb ( translate ( 'LBL_SUBPANELS', 'ModuleBuilder' ), 'ModuleBuilder.getContent("module=ModuleBuilder&action=wizard&view=subpanels&view_module=' . $this->editModule . '")' ) ;
                if ($this->subpanelLabel)
                {
                    $subpanelLabel = $this->subpanelLabel;
                    // If the subpanel title has changed, use that for the label instead
                    if(!empty($subpanelTitle) && $this->subpanelLabel != $subpanelTitle)
                        $subpanelLabel = $subpanelTitle;

                    $ajax->addCrumb( $subpanelLabel, '' );
                    $this->translatedViewType = $subpanelLabel . "&nbsp;" . translate("LBL_SUBPANEL", "ModuleBuilder");
                } else
                {
                    $ajax->addCrumb ( $this->subpanel, '' ) ;
                    $this->translatedViewType = translate("LBL_SUBPANEL", "ModuleBuilder");
                }
            } else
            {
                $ajax->addCrumb ( translate ( $layoutLabel, 'ModuleBuilder' ), 'ModuleBuilder.getContent("module=ModuleBuilder&action=wizard&view='.$layoutView.'&view_module=' . $this->editModule . '")' ) ;
                $ajax->addCrumb ( $translatedViewType, '' ) ;
            }
        }
        return $ajax ;
    }

    function constructSmarty ($parser)
    {
        global $mod_strings;
        $isModuleBWC = isModuleBWC($this->editModule) ;

        $smarty = new Sugar_Smarty ( ) ;
        $smarty->assign ( 'translate', true ) ;
        $smarty->assign ( 'language', $parser->getLanguage () ) ;

        $smarty->assign ( 'view', $this->editLayout ) ;
        $smarty->assign ( 'module', "ModuleBuilder" ) ;
        $smarty->assign ( 'field_defs', $parser->getFieldDefs () ) ;
        $smarty->assign ( 'action', 'listViewSave' ) ;
        $smarty->assign ( 'view_module', $this->editModule ) ;

        if (!empty ( $this->subpanel ) )
        {
            $smarty->assign ( 'subpanel', $this->subpanel ) ;
            $smarty->assign ( 'subpanelLabel', $this->subpanelLabel ) ;
            if (!$this->fromModuleBuilder) {
                $subList =  SubPanel::getModuleSubpanels ( $this->editModule);
                $subRef = $subList[strtolower($this->subpanel)];
                $subTitleKey = !empty($subRef) ? $subRef : "LBL_" . strtoupper($this->subpanel) . "_SUBPANEL_TITLE";
                $subTitle    = !empty($subRef) ? translate($subTitleKey, $this->editModule) : UCfirst($this->subpanel);
            	$smarty->assign ( 'subpanel_label', $subTitleKey ) ;
            	$smarty->assign ( 'subpanel_title', $subTitle ) ;
            }
        }
        $helpName = $this->subpanel ? 'subPanelEditor' : 'listViewEditor';
        $smarty->assign ( 'helpName', $helpName ) ;
        $smarty->assign ( 'helpDefault', 'modify' ) ;

        $smarty->assign ( 'title', $this->_constructTitle () ) ;
        $groups = array ( ) ;
        foreach ( $parser->columns as $column => $function )
        {
            // update this so that each field has a properties set
            // properties are name, value, title (optional)
            $groups [ $GLOBALS [ 'mod_strings' ] [ $column ] ] = $parser->$function () ; // call the parser functions to populate the list view columns, by default 'default', 'available' and 'hidden'
        }
        foreach ($groups as $groupKey => $group) {
            foreach ($group as $fieldKey => $field) {
                if (isset($field['width'])) {
                    if ($isModuleBWC) {
                        $width = intval($field['width']);
                        $unit = '%';
                    } else {
                        $isPercentage = strrpos($field['width'], '%') !== false;
                        if ($isPercentage) {
                            // We won't be bringing over the % definitions from metadata
                            $width = '';
                            $unit = '';
                        } else {
                            $width = intval($field['width']);
                            if ($width > 0) {
                                $unit = 'px';
                            } else {
                                // check if it is a valid string
                                $width = in_array($field['width'], SidecarListLayoutMetaDataParser::getDefaultWidths()) ?
                                    $field['width'] : '';
                                $unit = '';
                            }
                        }
                    }
                    $groups[$groupKey][$fieldKey]['width'] = $width;
                    $groups[$groupKey][$fieldKey]['units'] = $unit;
                }
            }
        }

        $smarty->assign('groups', $groups);
        $smarty->assign('from_mb', $this->fromModuleBuilder);

        global $image_path;
        $imageSave = SugarThemeRegistry::current()->getImage('studio_save','',null,null,'.gif',$mod_strings['LBL_BTN_SAVE']) ;

//        $imageHelp = SugarThemeRegistry::current()->getImage('help') ;


        $history = $parser->getHistory () ;

        $histaction = "ModuleBuilder.history.browse(\"{$this->editModule}\", \"{$this->editLayout}\")" ;
        if ($this->subpanel)
            $histaction = "ModuleBuilder.history.browse(\"{$this->editModule}\", \"{$this->editLayout}\", \"{$this->subpanel}\")" ;

        $restoreAction = "onclick='ModuleBuilder.history.revert(\"{$this->editModule}\", \"{$this->editLayout}\", \"{$history->getLast()}\", \"\")'";
        if ($this->subpanel)
            $restoreAction = "onclick='ModuleBuilder.history.revert(\"{$this->editModule}\", \"{$this->editLayout}\", \"{$history->getLast()}\", \"{$this->subpanel}\")'";

        $smarty->assign(
            'onsubmit',
            'studiotabs.generateGroupForm("edittabs"); if (countListFields()==0)' .
            '{ModuleBuilder.layoutValidation.popup();}else{ModuleBuilder.handleSave("edittabs");} return false;'
        );
        $buttons = array ( ) ;
        $buttons [] = array ( 'id' =>'savebtn', 'name' => 'savebtn' , 'type' => 'submit', 'image' => $imageSave , 'text' => (! $this->fromModuleBuilder)?$GLOBALS [ 'mod_strings' ] [ 'LBL_BTN_SAVEPUBLISH' ]: $GLOBALS [ 'mod_strings' ] [ 'LBL_BTN_SAVE' ]);
        $buttons [] = array ( 'id' => 'spacer' , 'width' => '50px' ) ;
        $buttons [] = array ( 'id' =>'historyBtn',       'name' => 'historyBtn' , 'text' => translate ( 'LBL_HISTORY' ) , 'actionScript' => "onclick='$histaction'" ) ;
        $buttons [] = array ( 'id' => 'historyRestoreDefaultLayout' , 'name' => 'historyRestoreDefaultLayout',  'text' => translate ( 'LBL_RESTORE_DEFAULT_LAYOUT' ) , 'actionScript' => $restoreAction ) ;

        $smarty->assign ( 'buttons', $this->_buildImageButtons ( $buttons ) ) ;

        $editImage = SugarThemeRegistry::current()->getImage('edit_inline','',null,null,'.gif',$mod_strings['LBL_EDIT']) ;

        $smarty->assign ( 'editImage', $editImage ) ;
        $deleteImage = SugarThemeRegistry::current()->getImage('delete_inline','',null,null,'.gif',$mod_strings['LBL_MB_DELETE']) ;

        $smarty->assign ( 'deleteImage', $deleteImage ) ;
        $smarty->assign ( 'MOD', $GLOBALS [ 'mod_strings' ] ) ;
        $local = $this->request->getValidInputRequest('local');

        if ($this->fromModuleBuilder)
        {
            $smarty->assign ( 'MB', true ) ;
            $smarty->assign ( 'view_package', $this->package ) ;
            $mb = new ModuleBuilder ( ) ;
            $module = & $mb->getPackageModule ( $this->package, $this->editModule ) ;
            $smarty->assign('current_mod_strings', $module->getModStrings());
            if ($this->subpanel)
            {
                if ($local !== null) {
                    $smarty->assign ( 'local', '1' ) ;
                }
                $smarty->assign ( "subpanel", $this->subpanel ) ;
            } else
            {
                $smarty->assign ( 'description', $GLOBALS [ 'mod_strings' ] [ 'LBL_LISTVIEW_DESCRIPTION' ] ) ;

            }

        } else
        {
            if ($this->subpanel)
            {
                $smarty->assign ( "subpanel", "$this->subpanel" ) ;
            } else
            {
                $smarty->assign ( 'description', $GLOBALS [ 'mod_strings' ] [ 'LBL_LISTVIEW_DESCRIPTION' ] ) ;
            }
        }

        return $smarty ;
    }

    function _constructTitle ()

    {

        global $app_list_strings ;

        if ($this->fromModuleBuilder)
        {
            $title = $this->editModule ;
            if ($this->subpanel != "")
            {
                $title .= ":$this->subpanel" ;
            }
        } else
        {
            $title = ($this->subpanel) ? ':' . $this->subpanel : $app_list_strings [ 'moduleList' ] [ $this->editModule ] ;
        }
        return $GLOBALS [ 'mod_strings' ] [ 'LBL_LISTVIEW_EDIT' ] . ':&nbsp;' . $title ;

    }

    function _buildImageButtons ($buttons , $horizontal = true)
    {
    	$text = '<table cellspacing=2><tr>' ;
        foreach ( $buttons as $button )
        {
            if (empty($button['id'])) {
            	$button['id'] = $button['name'];
            }
            if (! $horizontal)
            {
                $text .= '</tr><tr>' ;
            }
            if ($button['id'] == "spacer") {
                $text .= "<td style='width:{$button['width']}'> </td>";
                continue;
            }

            $type = isset($button['type']) ? $button['type'] : 'button';
            if (! empty ( $button [ 'plain' ] ))
            {
                $text .= <<<EOQ
                 <td><input name={$button['name']} id={$button['id']} class="button" type="{$type}" valign='center'
EOQ;

            } else
            {
                $text .= <<<EOQ
                <td><input name={$button['name']} id={$button['id']} class="button" type="{$type}" valign='center' style='cursor:default'
EOQ;
            }

            if (isset($button['actionScript'])) {
                $text .= ' ' . $button['actionScript'];
            }

            $text .= "value=\"{$button['text']}\"/></td>" ;
        }
        $text .= '</tr></table>' ;
        return $text ;
    }
}
