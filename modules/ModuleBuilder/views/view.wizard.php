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

class ModuleBuilderViewWizard extends SugarView
{
    private $view = null ; // the wizard view to display
    private $actions ;
    private $buttons ;
    private $question ;
    private $title ;
    private $help ;

    private $editModule ;

    public function __construct()
    {
        parent::__construct();
        $this->view = $this->request->getValidInputRequest('view', 'Assert\ComponentName');
        $this->editModule = $this->request->getValidInputRequest('view_module', 'Assert\ComponentName');
        $this->buttons = array(); // initialize so that modules without subpanels for example don't result in this being unset and causing problems in the smarty->assign
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

    public function display()
    {
        $this->ajax = new AjaxCompose ( ) ;
        $smarty = new Sugar_Smarty ( ) ;

        if (isset ( $_REQUEST [ 'MB' ] )) {
            $this->processMB ( $this->ajax ) ;
        } else {
            if ( isset ( $_REQUEST [ 'portal' ] ) ) {
                $this->processSugarPortal ( $this->ajax ) ;
            } else {

                $this->processStudio ( $this->ajax ) ;

            }
        }

        $smarty->assign ( 'buttons', $this->buttons ) ;
        $smarty->assign ( 'image_path', $GLOBALS [ 'image_path' ] ) ;
        $smarty->assign ( "title", $this->title ) ;
        $smarty->assign ( "question", $this->question ) ;
        $smarty->assign ( "defaultHelp", $this->help ) ;
        $smarty->assign ( "actions", $this->actions ) ;

        $this->ajax->addSection ( 'center', $this->title, $smarty->fetch ( 'modules/ModuleBuilder/tpls/wizard.tpl' ) ) ;
        echo $this->ajax->getJavascript () ;
    }

    public function processStudio(
        $ajax
        )
    {

        $this->ajax->addCrumb ( translate( 'LBL_STUDIO' ), 'ModuleBuilder.main("studio")' ) ;

        if (! isset ( $this->editModule )) {
            //Studio Select Module Page
            $this->generateStudioModuleButtons () ;
            $this->question = translate('LBL_QUESTION_EDIT') ;
            $this->title = translate( 'LBL_STUDIO' );
            global $current_user;
            if (is_admin($current_user))
                $this->actions = "<input class=\"button\" type=\"button\" id=\"exportBtn\" name=\"exportBtn\" onclick=\"ModuleBuilder.getContent('module=ModuleBuilder&action=exportcustomizations');\" value=\"" . translate ( 'LBL_BTN_EXPORT' ) . '">' ;

            $this->help = 'studioHelp' ;
        } else {
            $module = StudioModuleFactory::getStudioModule( $this->editModule ) ;
            $this->ajax->addCrumb ( $module->name, !empty($this->view) ? 'ModuleBuilder.getContent("module=ModuleBuilder&action=wizard&view_module=' . $this->editModule . '")' : '' ) ;
            switch ($this->view) {
                case 'layouts':
                    //Studio Select Layout page
                    $this->buttons = $module->getLayouts() ;
                    $this->title = $module->name . " " . translate('LBL_LAYOUTS') ;
                    $this->question = translate( 'LBL_QUESTION_LAYOUT' ) ;
                    $this->help = 'layoutsHelp' ;
                    $this->ajax->addCrumb ( translate( 'LBL_LAYOUTS' ), '' ) ;
                    break;

                case 'wirelesslayouts':
                    //Studio Select WirelessLayout page
                    $this->buttons = $module->getWirelessLayouts() ;
                    $this->title = $module->name . " " . translate('LBL_WIRELESSLAYOUTS') ;
                    $this->question = translate( 'LBL_QUESTION_LAYOUT' ) ;
                    $this->help = 'layoutsHelp' ;
                    $this->ajax->addCrumb ( translate( 'LBL_WIRELESSLAYOUTS' ), '' ) ;
                    break;

                case 'subpanels':
                    //Studio Select Subpanel page.
                    $this->buttons = $module->getSubpanels() ;
                    $this->title = $module->name . " " . translate( 'LBL_SUBPANELS' ) ;
                    $this->question = translate( 'LBL_QUESTION_SUBPANEL' ) ;
                    $this->ajax->addCrumb ( translate( 'LBL_SUBPANELS' ), '' ) ;
                    $this->help = 'subpanelHelp' ;
                    break;

                case 'search':
                    //Studio Select Search Layout page.
                    $this->buttons = $module->getSearch() ;
                    $this->title = $module->name . " " . translate('LBL_SEARCH');
                    $this->question = translate( 'LBL_QUESTION_SEARCH' ) ;
                    $this->ajax->addCrumb ( translate( 'LBL_LAYOUTS' ), 'ModuleBuilder.getContent("module=ModuleBuilder&action=wizard&view=layouts&view_module=' . $this->editModule . '")' ) ;
                    $this->ajax->addCrumb ( translate( 'LBL_SEARCH' ), '' ) ;
                    $this->help = 'searchHelp' ;
                    break;

                case 'dashlet':
                    $this->generateStudioDashletButtons();
                    $this->title = $this->editModule ." " .translate('LBL_DASHLET');
                    $this->question = translate( 'LBL_QUESTION_DASHLET' ) ;
                    $this->ajax->addCrumb ( translate( 'LBL_LAYOUTS' ), 'ModuleBuilder.getContent("module=ModuleBuilder&action=wizard&view=layouts&view_module=' . $this->editModule . '")' ) ;
                    $this->ajax->addCrumb ( translate( 'LBL_DASHLET' ), '' ) ;
                    $this->help = 'dashletHelp' ;
                    break;

                case 'popup':
                    $this->generateStudioPopupButtons();
                    $this->title = $this->editModule ." " .translate('LBL_POPUP');
                    $this->question = translate( 'LBL_QUESTION_POPUP' ) ;
                    $this->ajax->addCrumb ( translate( 'LBL_LAYOUTS' ), 'ModuleBuilder.getContent("module=ModuleBuilder&action=wizard&view=layouts&view_module=' . $this->editModule . '")' ) ;
                    $this->ajax->addCrumb ( translate( 'LBL_POPUP' ), '' ) ;
                    $this->help = 'popupHelp' ;
                    break;
                default:
                    //Studio Edit Module Page
                    $this->buttons = $module->getModule () ;
                    $this->question = translate( 'LBL_QUESTION_MODULE' ) ;
                    $this->title = translate( 'LBL_EDIT' ) . " " . $module->name ;
                    $this->help = 'moduleHelp' ;
                    global $current_user;
                    if (is_admin($current_user))
                        $this->actions = "<input class=\"button\" type=\"button\" id=\"exportBtn\" name=\"exportBtn\" "
                        . "onclick=\"ModuleBuilder.getContent('module=ModuleBuilder&action=resetmodule&view_module=$this->editModule');\" value=\""
                        . translate( 'LBL_RESET_MODULE' ) . '">' ;
            }
        }
    }

    public function processMB (
        $ajax
        )
    {
        if (! isset ( $_REQUEST [ 'view_package' ] )) {
            sugar_die ( "no ModuleBuilder package set" ) ;
        }

        $this->editModule = $this->request->getValidInputRequest('view_module', 'Assert\ComponentName');
        $this->package = $this->request->getValidInputRequest('view_package', 'Assert\ComponentName');

        $ajax->addCrumb ( translate ( 'LBL_MODULEBUILDER', 'ModuleBuilder' ), 'ModuleBuilder.main("mb")' ) ;
        $ajax->addCrumb ( $this->package, 'ModuleBuilder.getContent("module=ModuleBuilder&action=package&view_package=' . $this->package . '")' ) ;
        $ajax->addCrumb ( $this->editModule, 'ModuleBuilder.getContent("module=ModuleBuilder&action=module&view_module=' . $this->editModule . '&view_package=' . $this->package . '")') ;

        switch ($this->view) {
            case 'subpanel':
                //ModuleBuilder Select Subpanel
                $ajax->addCrumb ( $this->editModule, 'ModuleBuilder.getContent("module=ModuleBuilder&action=module&view_module=' . $this->editModule . '&view_package=' . $this->package . '")' ) ;
                $ajax->addCrumb ( translate( 'LBL_SUBPANELS' ), '' ) ;
                $this->question = translate( 'LBL_QUESTION_SUBPANEL' ) ;
                $this->help = 'subpanelHelp' ;
                break;

            case 'wirelesslayouts':
                $ajax->addCrumb ( translate( 'LBL_WIRELESSLAYOUTS' ), 'ModuleBuilder.getContent("module=ModuleBuilder&MB=true&action=wizard&view=wirelesslayouts&view_module=' . $this->editModule . '&view_package=' . $this->package . '")' ) ;
                $mb = new ModuleBuilder ( ) ;
                $module = $mb->getPackageModule( $this->package , $this->editModule ) ;
                $this->buttons = $module->getWirelessLayouts () ;
                $this->title = $this->editModule . " " . translate( 'LBL_WIRELESSLAYOUTS' ) ;
                $this->question = translate( 'LBL_QUESTION_LAYOUT' ) ;
                $this->help = "layoutsHelp" ;
                break ;

            case 'wirelesssearch':
                $mb = new ModuleBuilder ( ) ;
                $module = $mb->getPackageModule( $this->package , $this->editModule ) ;
                $this->buttons = $module->getWirelessSearch () ;
                $this->title = $this->editModule . " " . translate( 'LBL_WIRELESSSEARCH' ) ;
                $this->question = translate( 'LBL_QUESTION_SEARCH' ) ;
                $ajax->addCrumb ( translate( 'LBL_WIRELESSLAYOUTS' ), 'ModuleBuilder.getContent("module=ModuleBuilder&MB=true&action=wizard&view_module=' . $this->editModule . '&view_package=' . $this->package . '")' ) ;
                $ajax->addCrumb ( translate( 'LBL_WIRELESSSEARCH' ), '' ) ;
                $this->help = "searchHelp" ;
                break;

            case 'popup':
                $this->generateMBPopupButtons();
                $this->title = $this->editModule ." " .translate('LBL_POPUP');
                $this->question = translate( 'LBL_QUESTION_POPUP' ) ;
                $this->ajax->addCrumb ( translate( 'LBL_LAYOUTS' ), 'ModuleBuilder.getContent("module=ModuleBuilder&MB=true&action=wizard&view=layouts&MB=1&view_package='.$this->package.'&view_module=' . $this->editModule . '")' ) ;
                $this->ajax->addCrumb ( translate( 'LBL_POPUP' ), '' ) ;
                $this->help = 'popupHelp' ;
                break;
            default:
                $ajax->addCrumb ( translate( 'LBL_LAYOUTS' ), '' ) ;
                $this->generateMBViewButtons () ;
                $this->title = $this->editModule . " " . translate( 'LBL_LAYOUTS' ) ;
                $this->question = translate( 'LBL_QUESTION_LAYOUT' ) ;
                $this->help = "layoutsHelp" ;
        }
    }

    public function processSugarPortal(
        $ajax
        )
    {
        $this->ajax->addCrumb ( translate( 'LBL_SUGARPORTAL' ), 'ModuleBuilder.main("sugarportal")' ) ;

        if (isset ( $this->editModule )) {
            $module = StudioModuleFactory::getStudioModule( $this->editModule ) ;
            $this->generateSugarPortalViewButtons () ;
            $this->title = $module->name ;
            $this->question = translate( 'LBL_QUESTION_SUGAR_PORTAL' ) ;
            $this->ajax->addCrumb ( translate( 'LBL_LAYOUTS' ), 'ModuleBuilder.getContent("module=ModuleBuilder&action=wizard&portal=1&view=layout")' ) ;
            $this->ajax->addCrumb ( $module->name, 'ModuleBuilder.getContent("module=ModuleBuilder&action=wizard&portal=1&view_module=' . $this->editModule . '")' ) ;
            $this->help = 'layoutsHelp' ;
        } elseif (isset ( $_REQUEST [ 'layout' ] )) {
            // SugarPortal Layouts Page
            $this->generateSugarPortalModuleButtons () ;
            $this->question = translate( 'LBL_QUESTION_MODULE1' ) ;
            $this->title = translate( 'LBL_SUGARPORTAL' ) ;
            $this->help = 'portalLayoutHelp' ;
        } else {
            //Main SugarPortal Page
            $this->generateSugarPortalMainButtons () ;
            $this->question = translate( 'LBL_QUESTION_FUNCTION' ) ;
            $this->title = translate( 'LBL_SUGARPORTAL' ) ;
            $this->help = 'portalHelp' ;
        }
    }

    public function generateStudioModuleButtons()
    {
        $sb = new StudioBrowser ( ) ;
        $sb->loadModules () ;
        $nodes = $sb->getNodes () ;
        $this->buttons = array ( ) ;
        //$GLOBALS['log']->debug(print_r($nodes,true));
        foreach ($nodes as $module) {
            $this->buttons [$module['module']] = array(
                'action' => $module['action'],
                'imageTitle' => ucfirst ( $module [ 'module' ] . "_32" ) ,
                'size' => '32',
                'linkId' => 'studiolink_'.$module [ 'module' ],
                'bwc' => !empty($module['bwc']),
                'label' => $module['name']
            );
        }
    }

    public function generateSugarPortalViewButtons()
    {
        $module = new SugarPortalModule ( $this->editModule ) ;
        if (!empty($module->views) && is_array($module->views)) {
            foreach ($module->views as $file => $view) {
                $viewType = ($view [ 'type' ] == 'list') ? "ListView" : ucfirst ( $view [ 'type' ] ) ;
                $this->buttons [ $view [ 'name' ] ] = array ( 'action' => "module=ModuleBuilder&action=editPortal&view_module={$this->editModule}&view=$viewType" , 'imageTitle' => $viewType , 'help' => "viewBtn{$viewType}" , 'size' => '48' ) ;
            }
        }
    }

    public function generateSugarPortalModuleButtons()
    {
        $sb = new SugarPortalBrowser ( ) ;
        $nodes = $sb->getNodes () ;
        $this->buttons = array ( ) ;
        foreach ($nodes as $modules) {
            if ($modules [ 'name' ] == translate('LBL_LAYOUTS')) {
                foreach ($modules [ 'children' ] as $module) {
                    $this->buttons [ $module [ 'name' ] ] = array ( 'action' => $module [ 'action' ] , 'imageTitle' => ucfirst ( $module [ 'module' ]. "_32" ) , 'size' => '32' ) ;
                }
                ksort($this->buttons);
                break ;
            }
        }
    }
    public function generateSugarPortalMainButtons()
    {
        $sb = new SugarPortalBrowser ( ) ;
        $nodes = $sb->getNodes () ;
        $GLOBALS [ 'log' ]->debug ( print_r ( $nodes, true ) ) ;
        $this->buttons = array ( ) ;

        foreach ($nodes as $module) {
            $title = (isset ( $module [ 'imageTitle' ] ) ? $module [ 'imageTitle' ] : $module [ 'module' ]) ;
            $this->buttons [ $module [ 'name' ] ] = array ( 'action' => $module [ 'action' ] , 'imageTitle' => $title , 'help' => $title , 'size' => '48' ) ;
        }
    }

    public function generateMBViewButtons()
    {
        $this->buttons [ $GLOBALS [ 'mod_strings' ] [ 'LBL_RECORDVIEW' ] ] =
          array (
              'action' => "module=ModuleBuilder&MB=true&action=editLayout&view=".MB_RECORDVIEW."&view_module={$this->editModule}&view_package={$this->package}" ,
              'imageTitle' => 'RecordView',
              'help'=>'viewBtnRecordView'
          ) ;
        $this->buttons [ $GLOBALS [ 'mod_strings' ] [ 'LBL_LISTVIEW' ] ] =
          array (
              'action' => "module=ModuleBuilder&MB=true&action=editLayout&view=".MB_LISTVIEW."&view_module={$this->editModule}&view_package={$this->package}" ,
              'imageTitle' => 'ListView',
              'help'=>'viewBtnListView'
          ) ;
        $this->buttons [ $GLOBALS [ 'mod_strings' ] ['LBL_POPUP'] ] =
        array (
            'imageTitle' => 'Popup',
            'action' => "module=ModuleBuilder&MB=true&action=wizard&view=popup&view_module={$this->editModule}&view_package={$this->package}",
            'help'=>'PopupListViewBtn'
        );
    }

    public function generateMBDashletButtons()
    {
        $this->buttons [ $GLOBALS [ 'mod_strings' ][ 'LBL_DASHLETLISTVIEW' ] ] = array('action'=> "module=ModuleBuilder&MB=true&action=editLayout&view=dashlet&view_module={$this->editModule}&view_package={$this->package}", 'imageTitle'=> $GLOBALS ['mod_strings']['LBL_DASHLETLISTVIEW'], 'imageName'=>'ListView', 'help'=>'DashletListViewBtn');
        $this->buttons [ $GLOBALS [ 'mod_strings' ][ 'LBL_DASHLETSEARCHVIEW' ] ] = array('action'=> "module=ModuleBuilder&MB=true&action=editLayout&view=dashletsearch&view_module={$this->editModule}&view_package={$this->package}", 'imageTitle'=> $GLOBALS ['mod_strings']['LBL_DASHLETSEARCHVIEW'], 'imageName'=>'BasicSearch','help'=> 'DashletSearchViewBtn');
    }

    public function generateMBPopupButtons()
    {
        $this->buttons [ $GLOBALS [ 'mod_strings' ][ 'LBL_POPUPLISTVIEW' ] ] = array('action'=> "module=ModuleBuilder&action=editLayout&view=selection-list&view_module={$this->editModule}&view_package={$this->package}", 'imageTitle'=> $GLOBALS ['mod_strings']['LBL_POPUPLISTVIEW'], 'imageName'=>'ListView', 'help'=>'PopupListViewBtn');
        $this->buttons [ $GLOBALS [ 'mod_strings' ][ 'LBL_POPUPSEARCH' ] ] = array('action'=> "module=ModuleBuilder&action=editLayout&view=popupsearch&view_module={$this->editModule}&view_package={$this->package}", 'imageTitle'=> $GLOBALS ['mod_strings']['LBL_POPUPSEARCH'], 'imageName'=>'BasicSearch','help'=> 'PopupSearchViewBtn');
    }

    public function generateStudioPopupButtons()
    {
        $this->buttons [ $GLOBALS [ 'mod_strings' ][ 'LBL_POPUPLISTVIEW' ] ] = array('action'=> "module=ModuleBuilder&action=editLayout&view=selection-list&view_module={$this->editModule}", 'imageTitle'=> $GLOBALS ['mod_strings']['LBL_POPUPLISTVIEW'], 'imageName'=>'ListView', 'help'=>'PopupListViewBtn');
        $this->buttons [ $GLOBALS [ 'mod_strings' ][ 'LBL_POPUPSEARCH' ] ] = array('action'=> "module=ModuleBuilder&action=editLayout&view=popupsearch&view_module={$this->editModule}", 'imageTitle'=> $GLOBALS ['mod_strings']['LBL_POPUPSEARCH'], 'imageName'=>'BasicSearch','help'=> 'PopupSearchViewBtn');
    }
}
