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

use Sugarcrm\Sugarcrm\Security\InputValidation\Request;


class ListViewSmarty extends ListViewDisplay{

	var $data;
	var $ss; // the smarty object
	var $displayColumns;
	var $searchColumns; // set by view.list.php
	var $tpl;
	var $moduleString;
	var $export = true;
    var $delete = true;
    var $select = true;
    var $mailMerge = true;
    var $email = true;
    var $targetList = false;
	var $multiSelect = true;
	var $quickViewLinks = true;
	var $lvd;
	var $mergeduplicates = true;
    var $contextMenus = true;
    var $showMassupdateFields = true;
    var $menu_location = 'top';

    /**
     * Constructor, Smarty object immediately available after
     *
     * @param Request $request
     */
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
		$this->ss = new Sugar_Smarty();
	}

    /**
     * Processes the request. Calls ListViewData process. Also assigns all lang strings, export links,
     * This is called from ListViewDisplay
     *
     * @param file file Template file to use
     * @param data array from ListViewData
     * @param html_var string the corresponding html var in xtpl per row
     *
     */
	function process($file, $data, $htmlVar) {
		if(!$this->should_process)return;
		global $odd_bg, $even_bg, $hilite_bg, $click_bg, $app_strings;
		parent::process($file, $data, $htmlVar);

		$this->tpl = $file;
		$this->data = $data;

        $totalWidth = 0;
        foreach($this->displayColumns as $name => $params) {
            $totalWidth += $params['width'];
        }
        $adjustment = $totalWidth / 100;

        $contextMenuObjectsTypes = array();
        foreach($this->displayColumns as $name => $params) {
            $this->displayColumns[$name]['width'] = floor($this->displayColumns[$name]['width'] / $adjustment);
            // figure out which contextMenu objectsTypes are required
            if(!empty($params['contextMenu']['objectType']))
                $contextMenuObjectsTypes[$params['contextMenu']['objectType']] = true;
        }
		$this->ss->assign('displayColumns', $this->displayColumns);
		$this->ss->assign('APP',$app_strings);

		$this->ss->assign('bgHilite', $hilite_bg);
		$this->ss->assign('colCount', count($this->displayColumns) + 10);
		$this->ss->assign('htmlVar', strtoupper($htmlVar));
		$this->ss->assign('moduleString', $this->moduleString);
        $this->ss->assign('editLinkString', $app_strings['LBL_EDIT_BUTTON']);
        $this->ss->assign('viewLinkString', $app_strings['LBL_VIEW_BUTTON']);
        $this->ss->assign('allLinkString',$app_strings['LBL_LINK_ALL']);
        $this->ss->assign('noneLinkString',$app_strings['LBL_LINK_NONE']);
        $this->ss->assign('recordsLinkString',$app_strings['LBL_LINK_RECORDS']);
        $this->ss->assign('selectLinkString',$app_strings['LBL_LINK_SELECT']);
        $this->ss->assign('favorites',$this->seed->isFavoritesEnabled());

        // Bug 24677 - Correct the page total amount on the last page of listviews
        $pageTotal = $this->data['pageData']['offsets']['next']-$this->data['pageData']['offsets']['current'];
        if ( $this->data['pageData']['offsets']['next'] < 0 ) {
            $pageTotal = $this->data['pageData']['offsets']['total'] - $this->data['pageData']['offsets']['current'];
        }

		if($this->select)$this->ss->assign('selectLinkTop', $this->buildSelectLink('select_link', $this->data['pageData']['offsets']['total'], $pageTotal));
        if($this->select)$this->ss->assign('selectLinkBottom', $this->buildSelectLink('select_link', $this->data['pageData']['offsets']['total'], $pageTotal, "bottom"));

        if($this->show_action_dropdown)
		{
            $action_menu = $this->buildActionsLink();
			$this->ss->assign('actionsLinkTop', $action_menu);
            if(count($action_menu['buttons']) > 0) {
                $firstButton = reset($action_menu['buttons']);
                $this->ss->assign('actionDisabledLink', preg_replace("/id\\s*\\=(\"\\w+\"|w+)/i", "", $firstButton));
            }
            $menu_location = 'bottom';
            $this->ss->assign('actionsLinkBottom', $this->buildActionsLink('actions_link' ,$menu_location));
		}
		
		$this->ss->assign('quickViewLinks', $this->quickViewLinks);

		// handle save checks and stuff
		if($this->multiSelect)
        {
			$this->ss->assign('selectedObjectsSpan', $this->buildSelectedObjectsSpan(true, (isset($_POST['mass'])) ? count($_POST['mass']): 0));
		    $this->ss->assign('multiSelectData', $this->getMultiSelectData());
		} else {
            $this->ss->assign('multiSelectData', '<textarea style="display: none" name="uid"></textarea>');
        }
		// include button for Adding to Target List if in one of four applicable modules
		if ( isset ( $_REQUEST['module']) && in_array ( $_REQUEST['module'] , array ( 'Contacts','Prospects','Leads','Accounts' ))
		&& ACLController::checkAccess('ProspectLists','edit',true)) {
			$this->ss->assign( 'targetLink', $this->buildTargetList() ) ;
		}
		$this->processArrows($data['pageData']['ordering']);
		$this->ss->assign('prerow', $this->multiSelect);
		$this->ss->assign('clearAll', $app_strings['LBL_CLEARALL']);
		$this->ss->assign('rowColor', array('oddListRow', 'evenListRow'));
		$this->ss->assign('bgColor', array($odd_bg, $even_bg));
        $this->ss->assign('contextMenus', $this->contextMenus);
        $this->ss->assign('is_admin_for_user', $GLOBALS['current_user']->isAdminForModule('Users'));
        $this->ss->assign('is_admin', $GLOBALS['current_user']->isAdmin());


        if($this->contextMenus && !empty($contextMenuObjectsTypes)) {
            $script = '';
            $cm = new contextMenu();
            foreach($contextMenuObjectsTypes as $type => $value) {
                $cm->loadFromFile($type);
                $script .= $cm->getScript();
                $cm->menuItems = array(); // clear menuItems out
            }
            $this->ss->assign('contextMenuScript', $script);
        }
	}

    /**
     * Assigns the sort arrows in the tpl
     *
     * @param ordering array data that contains the ordering info
     *
     */
	function processArrows($ordering)
    {
		$pathParts = pathinfo(SugarThemeRegistry::current()->getImageURL('arrow.gif',false));

        list($width,$height) = getimagesize($pathParts['dirname'].'/'.$pathParts['basename']);

		$this->ss->assign('arrowExt', $pathParts['extension']);
		$this->ss->assign('arrowWidth', $width);
		$this->ss->assign('arrowHeight', $height);
		$this->ss->assign('arrowAlt', translate('LBL_SORT'));
	}



    /**
     * Displays the xtpl, either echo or returning the contents
     *
     * @param end bool display the ending of the listview data (ie MassUpdate)
     *
     */
	function display($end = true) {

		if(!$this->should_process) return $GLOBALS['app_strings']['LBL_SEARCH_POPULATE_ONLY'];
        global $app_strings, $sugar_version, $sugar_flavor, $server_unique_key, $currentModule, $app_list_strings, $sugar_config;
        $this->ss->assign('moduleListSingular', $app_list_strings["moduleListSingular"]);
        $this->ss->assign('moduleList', $app_list_strings['moduleList']);
        $this->ss->assign('data', $this->data['data']);
        $this->ss->assign('query', $this->data['query']);
        $this->ss->assign('sugar_info', array("sugar_version" => $sugar_version, 
											  "sugar_flavor" => $sugar_flavor));
		$this->data['pageData']['offsets']['lastOffsetOnPage'] = $this->data['pageData']['offsets']['current'] + count($this->data['data']);
		$this->ss->assign('pageData', $this->data['pageData']);

        $navStrings = array('next' => $app_strings['LNK_LIST_NEXT'],
                            'previous' => $app_strings['LNK_LIST_PREVIOUS'],
                            'end' => $app_strings['LNK_LIST_END'],
                            'start' => $app_strings['LNK_LIST_START'],
                            'of' => $app_strings['LBL_LIST_OF']);
        $this->ss->assign('navStrings', $navStrings);

        // Default is true
        $displayEmptyDataMessages = !isset($sugar_config['display_empty_data_messages']) || !empty($sugar_config['display_empty_data_messages']);
        //TODO: Cleanup, better logic for which modules are exempt from the new messaging. 
        $modulesExemptFromEmptyDataMessages = array('WorkFlow','ContractTypes', 'OAuthKeys', 'TimePeriods');
        if( (isset($GLOBALS['moduleTabMap'][$currentModule]) && $GLOBALS['moduleTabMap'][$currentModule] == 'Administration')
            || isset($GLOBALS['adminOnlyList'][$currentModule]) || in_array($currentModule, $modulesExemptFromEmptyDataMessages) )
        {
            $displayEmptyDataMessages = FALSE;
        }
        $this->ss->assign('displayEmptyDataMesssages', $displayEmptyDataMessages);

        $displaySubMessage = true;
        $modulesExemptFromSubMessage = array('Reports');
        if(in_array($currentModule, $modulesExemptFromSubMessage) )
        {
            $displaySubMessage = false;
        }
        $this->ss->assign('displaySubMessage', $displaySubMessage);

		$str = parent::display();
		$strend = $this->displayEnd();

		return $str . $this->ss->fetch($this->tpl) . (($end) ? $strend : '');
	}
    function displayEnd() {
        $str = '';
        if($this->show_mass_update_form) {
            if($this->showMassupdateFields){
                $str .= $this->mass->getMassUpdateForm(true);
            }
            $str .= $this->mass->endMassUpdateForm();
        }

        return $str;
    }
}

