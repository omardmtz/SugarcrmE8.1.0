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


class ViewHistory extends SugarView
{
    var $pageSize = 10 ;

    /**
     * @var History
     */
    protected $history;

    /**
     * @var AbstractMetaDataParser
     */
    protected $parser;

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

	function display ()
    {
        $this->layout = strtolower ( $_REQUEST [ 'view' ] ) ;
        
        $subpanelName = null ;
        if ((strtolower ( $this->layout ) == 'listview') && (!empty ( $_REQUEST [ 'subpanel' ] )))
        {
            $subpanelName = $_REQUEST [ 'subpanel' ] ;
            
        }
        
        $packageName = (isset ( $_REQUEST [ 'view_package' ] ) && (strtolower ( $_REQUEST [ 'view_package' ] ) != 'studio')) ? $_REQUEST [ 'view_package' ] : null ;
        $this->module = $this->request->getValidInputRequest('view_module', 'Assert\ComponentName');

        $params = array();
        if (!empty($_REQUEST['role'])) {
            $params['role'] = $_REQUEST['role'];
        }
        $this->parser = ParserFactory::getParser(
            $this->layout,
            $this->module,
            $packageName,
            $subpanelName,
            null,
            $params
        );
        $this->history = $this->parser->getHistory () ;
        $action = ! empty ( $_REQUEST [ 'histAction' ] ) ? $_REQUEST [ 'histAction' ] : 'browse' ;
        $GLOBALS['log']->debug( get_class($this)."->display(): performing History action {$action}" ) ;
        $this->$action () ;
    }

    function browse ()
    {
        global $timedate ;

        $smarty = new Sugar_Smarty ( ) ;
        global $mod_strings ;
        $smarty->assign ( 'mod_strings', $mod_strings ) ;
        $smarty->assign ( 'view_module', $this->module ) ;
        $smarty->assign ( 'view', $this->layout ) ;

        $subpanel = $this->request->getValidInputRequest('subpanel');
        $smarty->assign('subpanel', $subpanel);

        $page = $this->request->getValidInputRequest('page', array(
            'Assert\Type' => array('type' => 'numeric'),
            'Assert\Range' => array('min' => 0),
        ), 0);

        $count = $this->history->getCount();
        $ts = $this->history->getNth ( $page * $this->pageSize ) ;
        $snapshots = array ( ) ;
        for ( $i = 0 ; $i <= $this->pageSize && $ts > 0 ; $i ++ )
        {
            if ($page * $this->pageSize + $i + 1 == $count) {
                $label = translate('LBL_MB_DEFAULT_LAYOUT');
                $isDefault = true;
            } else {
                $dbDate = $timedate->fromTimestamp($ts)->asDb();
                $label = $timedate->to_display_date_time($dbDate);
                $isDefault = false;
            }
            $snapshots[$ts] = array(
                'label' => $label,
                'isDefault' => $isDefault,
            );
            $ts = $this->history->getNext () ;
        }

        // If we're viewing history for the Opportunities module, we remove the
        // first revision to remove the result of the Opps/RLI mode-switch.
        if ($this->module == 'Opportunities') {
            array_pop($snapshots);
        }

        if (count ( $snapshots ) > $this->pageSize)
        {
            $smarty->assign ( 'nextPage', true ) ;
        }
        $snapshots = array_slice ( $snapshots, 0, $this->pageSize, true ) ;
        $smarty->assign ( 'currentPage', $page ) ;
        $smarty->assign ( 'snapshots', $snapshots ) ;
        
        $html = $smarty->fetch ( 'modules/ModuleBuilder/tpls/history.tpl' ) ;
        echo $html ;
    }

    function preview ()
    {
        global $mod_strings ;
        if (! isset ( $_REQUEST [ 'sid' ] ))
        {
            die ( 'SID Required' ) ;
        }
        $sid = $_REQUEST [ 'sid' ] ;
        $subpanel = '';
        if (! empty ( $_REQUEST [ 'subpanel' ] ))
        {
            $subpanel = $_REQUEST['subpanel'];
        }

        $isDefault = $sid == $this->history->getLast();
        echo "<input type='button' value='" . translate('LBL_BTN_CLOSE') . "' " .
                "class='button' onclick='ModuleBuilder.tabPanel.removeTab(ModuleBuilder.tabPanel.get(\"activeTab\"));' style='margin:5px;'>" . 
             "<input type='button' value='" . translate('LBL_MB_RESTORE') . "' " .
                "class='button' onclick='ModuleBuilder.history.revert("
            . htmlspecialchars(json_encode($this->module))
            . ', ' . htmlspecialchars(json_encode($this->layout))
            . ', ' . htmlspecialchars(json_encode($sid))
            . ', ' . htmlspecialchars(json_encode($subpanel))
            . ', ' . htmlspecialchars(json_encode($isDefault))
            . ");' style='margin:5px;'>";
        $this->history->restoreByTimestamp ( $sid ) ;

        if (($this->layout == 'listview') || ($this->layout == 'wirelesslistview')) {
            $view = new ViewListView ( ) ;
        } else if ($this->layout == 'basic_search' || $this->layout == 'advanced_search')
        {
            $view = new ViewSearchView ( ) ;
        } else if ($this->layout == 'dashlet' || $this->layout == 'dashletsearch')
        {
        	$view = new ViewDashlet ( ) ;
        }  else if ($this->layout == 'popuplist' || $this->layout == 'popupsearch' || $this->layout == 'selection-list')
        {
        	$view = new ViewPopupview ( ) ;
        } else
        {
            $view = new ViewLayoutView ( ) ;
        }
        
        $view->display ( true ) ;
        $this->history->undoRestore () ;
    }

    function restore ()
    {
        if (! isset ( $_REQUEST [ 'sid' ] ))
        {
            die ( 'SID Required' ) ;
        }
        $sid = $_REQUEST [ 'sid' ] ;
        $this->history->restoreByTimestamp ( $sid ) ;
    }

    protected function resetToDefault()
    {
        $implementation = $this->parser->getImplementation();
        $fileName = $implementation->getDefaultFileName($this->layout, $this->module);
        $this->history->savePreview($fileName);
    }

	/**
 	 * Restores a layout to its current customized state. 
 	 * Called when leaving a restored layout without saving.
 	 */
    function unrestore() 
    {
    	$this->history->undoRestore () ;
    }
}
