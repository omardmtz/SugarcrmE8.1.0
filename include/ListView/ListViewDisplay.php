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
use Sugarcrm\Sugarcrm\Security\InputValidation\Request;


class ListViewDisplay {
    static $listViewCounter = 0;

	var $show_mass_update_form = false;
	var $show_action_dropdown = true;
	var $rowCount;
	var $mass = null;
	var $seed;
	var $multi_select_popup;
	var $lvd;
	var $moduleString;
	var $export = true;
	var $multiSelect = true;
	var $mailMerge = true;
	var $should_process = true;
	var $show_plus = false;
	/*
	 * Used in view.popup.php. Sometimes there are fields on the search form that are not referenced in the listviewdefs. If this
	 * is the case, then the filterFields will be set and the related fields will not be referenced when calling create_new_list_query.
	 */
	var $mergeDisplayColumns = false;
    public $actionsMenuExtraItems = array();

    /**
     * @var Request
     */
    protected $request;

	/**
	 * Constructor
     *
     * @param Request $request
	 */
    public function __construct(Request $request = null)
    {
        $this->request = $request ?: InputValidation::getService();
        $this->lvd = new ListViewData($this->request);
		$this->searchColumns = array () ;
	}

	function shouldProcess($moduleDir){
		$searching = false;
		$sessionSearchQuery = "{$moduleDir}2_QUERY_QUERY";
		if (!empty($_SESSION[$sessionSearchQuery])) {
			$searching = true;
		}
		if(!empty($GLOBALS['sugar_config']['save_query']) && $GLOBALS['sugar_config']['save_query'] == 'populate_only'){
		    if(empty($GLOBALS['displayListView']) 
		            && (!empty($_REQUEST['clear_query']) 
		                || $_REQUEST['module'] == $moduleDir 
		                    && ((empty($_REQUEST['query']) || $_REQUEST['query'] == 'MSI' )
		                        && (!$searching)))) {

				$_SESSION['last_search_mod'] = $this->request->getValidInputRequest('module', 'Assert\Mvc\ModuleName');
				$this->should_process = false;
				return false;
			}
		}
		$this->should_process = true;
		return true;
	}

	/**
	 * Setup the class
	 * @param seed SugarBean Seed SugarBean to use
	 * @param file File Template file to use
	 * @param string $where
	 * @param offset:0 int offset to start at
	 * @param int:-1 $limit
	 * @param string[]:array() $filter_fields
	 * @param array:array() $params
	 * 	Potential $params are
		$params['distinct'] = use distinct key word
		$params['include_custom_fields'] = (on by default)
		$params['massupdate'] = true by default;
        $params['handleMassupdate'] = true by default, have massupdate.php handle massupdates?
	 * @param string:'id' $id_field
	 */
    public function setup(
        $seed,
        $file = '',
        $where = '',
        $params = array(),
        $offset = 0,
        $limit = -1,
        $filter_fields = array(),
        $id_field = 'id'
    ) {
        $this->should_process = true;
        if(isset($seed->module_dir) && !$this->shouldProcess($seed->module_dir)){
        		return false;
        }
        if(isset($params['export'])) {
          $this->export = $params['export'];
        }
        if(!empty($params['multiSelectPopup'])) {
		  $this->multi_select_popup = $params['multiSelectPopup'];
        }
		if(!empty($params['massupdate']) && $params['massupdate'] != false) {
			$this->show_mass_update_form = true;
			$this->mass = $this->getMassUpdate();
			$this->mass->setSugarBean($seed);
			if(!empty($params['handleMassupdate']) || !isset($params['handleMassupdate'])) {
                $this->mass->handleMassUpdate();
            }
		}
		$this->seed = $seed;

        $filter_fields = $this->setupFilterFields($filter_fields);

        $data = $this->lvd->getListViewData($seed, $where, $offset, $limit, $filter_fields, $params, $id_field);

        $this->fillDisplayColumnsWithVardefs();

        $data = $this->setupHTMLFields($data);

		$this->process($file, $data, $seed->object_name);
		return true;
	}

	function setupFilterFields($filter_fields = array())
	{
		// create filter fields based off of display columns
        if(empty($filter_fields) || $this->mergeDisplayColumns) {
            foreach($this->displayColumns as $columnName => $def) {

               $filter_fields[strtolower($columnName)] = true;

                if(isset($this->seed->field_defs[strtolower($columnName)]['type']) &&
                    strtolower($this->seed->field_defs[strtolower($columnName)]['type']) == 'currency') {
                    if (isset($this->seed->field_defs['currency_id'])) {
                        $filter_fields['currency_id'] = true;
                    }
                    if (isset($this->seed->field_defs['base_rate'])) {
                        $filter_fields['base_rate'] = true;
                    }
                }

               if(!empty($def['related_fields'])) {
                    foreach($def['related_fields'] as $field) {
                        //id column is added by query construction function. This addition creates duplicates
                        //and causes issues in oracle. #10165
                        if ($field != 'id') {
                            $filter_fields[$field] = true;
                        }
                    }
                }
                if (!empty($this->seed->field_defs[strtolower($columnName)]['db_concat_fields'])) {
                    foreach($this->seed->field_defs[strtolower($columnName)]['db_concat_fields'] as $index=>$field){
                        if(!isset($filter_fields[strtolower($field)]) || !$filter_fields[strtolower($field)])
                        {
                            $filter_fields[strtolower($field)] = true;
                        }
                    }
                }
            }
            foreach ($this->searchColumns as $columnName => $def )
            {
                $filter_fields[strtolower($columnName)] = true;
            }
        }


        //check for team_set_count
        if(!empty($filter_fields['team_name']) && empty($filter_fields['team_count'])){
            $filter_fields['team_count'] = true;

            //Add the team_id entry so that we can retrieve the team_id to display primary team
            $filter_fields['team_id'] = true;
        }

        return $filter_fields;
	}


	/**
	 * Any additional processing
	 * @param file File template file to use
	 * @param data array row data
	 * @param html_var string html string to be passed back and forth
	 */
	function process($file, $data, $htmlVar) {
		$this->rowCount = count($data['data']);
		$this->moduleString = $data['pageData']['bean']['moduleDir'] . '2_' . strtoupper($htmlVar) . '_offset';
	}

	/**
	 * Display the listview
	 * @return string ListView contents
	 */
	public function display() 
	{
		if (!$this->should_process) {
		    return '';
		}
		
		$str = '';
		if ($this->show_mass_update_form) {
			$str = $this->mass->getDisplayMassUpdateForm(true, $this->multi_select_popup).$this->mass->getMassUpdateFormHeader($this->multi_select_popup);
		}
        
		return $str;
	}
	/**
	 * Display the select link
     * @return string select link html
	 * @param echo Bool set true if you want it echo'd, set false to have contents returned
	 */
	function buildSelectLink($id = 'select_link', $total=0, $pageTotal=0, $location="top") {
		global $app_strings;
		if ($pageTotal < 0)
			$pageTotal = $total;


        $total_label = "";
        if (!empty($GLOBALS['sugar_config']['disable_count_query']) && $GLOBALS['sugar_config']['disable_count_query'] === true && $total > $pageTotal) {
            $this->show_plus = true;
            $total_label =  $pageTotal.'+';
            $total = $pageTotal;
        } else {
            $total_label = $total;
        }

		$close_inline_img = SugarThemeRegistry::current()->getImage('close_inline', 'border=0', null, null, ".gif", $app_strings['LBL_CLOSEINLINE']);
		$menuItems = array(
            "<input title=\"".$app_strings['LBL_SELECT_ALL_TITLE']."\" type='checkbox' class='checkbox massall' name='massall' id='massall_".$location."' value='' onclick='sListView.check_all(document.MassUpdate, \"mass[]\", this.checked);' /><a id='$id'  href='javascript: void(0);'></a>",
            "<a  name='thispage' id='button_select_this_page_".$location."' class='menuItem' onmouseover='hiliteItem(this,\"yes\");' onmouseout='unhiliteItem(this);' onclick='if (document.MassUpdate.select_entire_list.value==1){document.MassUpdate.select_entire_list.value=0;sListView.check_all(document.MassUpdate, \"mass[]\", true, $pageTotal)}else {sListView.check_all(document.MassUpdate, \"mass[]\", true)};' href='#'>{$app_strings['LBL_LISTVIEW_OPTION_CURRENT']}&nbsp;&#x28;{$pageTotal}&#x29;&#x200E;</a>",
            "<a  name='selectall' id='button_select_all_".$location."' class='menuItem' onmouseover='hiliteItem(this,\"yes\");' onmouseout='unhiliteItem(this);' onclick='sListView.check_entire_list(document.MassUpdate, \"mass[]\",true,{$total});' href='#'>{$app_strings['LBL_LISTVIEW_OPTION_ENTIRE']}&nbsp;&#x28;{$total_label}&#x29;&#x200E;</a>",
            "<a name='deselect' id='button_deselect_".$location."' class='menuItem' onmouseover='hiliteItem(this,\"yes\");' onmouseout='unhiliteItem(this);' onclick='sListView.clear_all(document.MassUpdate, \"mass[]\", false);' href='#'>{$app_strings['LBL_LISTVIEW_NONE']}</a>",
        );

        $link = array(
            'class' => 'clickMenu selectmenu',
            'id' => 'selectLink',
            'buttons' => $menuItems,
            'flat' => false,
        );
        return $link;
	}

	/**
	 * Display the actions link
	 *
	 * @param  string $id link id attribute, defaults to 'actions_link'
	 * @return string HTML source
	 */
	protected function buildActionsLink($id = 'actions_link', $location = 'top')
	{
	    global $app_strings;
		$closeText = SugarThemeRegistry::current()->getImage('close_inline', 'border=0', null, null, ".gif", $app_strings['LBL_CLOSEINLINE']);
		$moreDetailImage = SugarThemeRegistry::current()->getImageURL('MoreDetail.png');
		$menuItems = array();

		// delete
		if ( ACLController::checkAccess($this->seed->module_dir,'delete',true) && $this->delete )
			$menuItems[] = $this->buildDeleteLink($location);
		// compose email
        if ( $this->email )
			$menuItems[] = $this->buildComposeEmailLink($this->data['pageData']['offsets']['total'], $location);
		// mass update
		$mass = $this->getMassUpdate();
		$mass->setSugarBean($this->seed);
		if ( ( ACLController::checkAccess($this->seed->module_dir,'edit',true) && ACLController::checkAccess($this->seed->module_dir,'massupdate',true) ) && $this->showMassupdateFields && $mass->doMassUpdateFieldsExistForFocus() )
            $menuItems[] = $this->buildMassUpdateLink($location);
		// merge
		if ( $this->mailMerge )
		    $menuItems[] = $this->buildMergeLink(null, $location);
		if ( $this->mergeduplicates )
		    $menuItems[] = $this->buildMergeDuplicatesLink($location);
		// add to target list
		if ( $this->targetList && ACLController::checkAccess('ProspectLists','edit',true) )
		    $menuItems[] = $this->buildTargetList($location);
		// export
		if ( ACLController::checkAccess($this->seed->module_dir,'export',true) && $this->export )
			$menuItems[] = $this->buildExportLink($location);

		foreach ( $this->actionsMenuExtraItems as $item )
		    $menuItems[] = $item;

        $menuItems = array_filter($menuItems); // delete possible empty values - they are needless
        $link = array(
            'class' => 'clickMenu selectActions fancymenu',
            'id' => 'selectActions',
            'name' => 'selectActions',
            'buttons' => $menuItems,
            'flat' => false,
        );
        return $link;

}
	/**
	 * Builds the export link
	 *
	 * @return string HTML
	 */
	protected function buildExportLink($loc = 'top')
	{
		global $app_strings;
        return "<a href='javascript:void(0)' id=\"export_listview_". $loc ."\" onclick=\"return sListView.send_form("
            . "true, '{$this->seed->module_dir}', 'index.php?entryPoint=export', "
            . "SUGAR.language.get('app_strings', 'LBL_LISTVIEW_NO_SELECTED')"
            . ")\">{$app_strings['LBL_EXPORT']}</a>";
	}

	/**
	 * Builds the massupdate link
	 *
	 * @return string HTML
	 */
	protected function buildMassUpdateLink($loc = 'top')
	{
		global $app_strings;

        $onClick = "document.getElementById('massupdate_form').style.display = ''; var yLoc = YAHOO.util.Dom.getY('massupdate_form'); scroll(0,yLoc);";
		return "<a href='javascript:void(0)' id=\"massupdate_listview_". $loc ."\" onclick=\"$onClick\">{$app_strings['LBL_MASS_UPDATE']}</a>";

	}

	/**
	 * Builds the compose email link
	 *
	 * @return string HTML
	 */
	protected function buildComposeEmailLink($totalCount, $loc = 'top')
	{
		global $app_strings,$dictionary;

        if (!is_array($this->seed->field_defs)) {
            return '';
        }
        $foundEmailField = false;
        // Search for fields that look like an email address
        foreach ($this->seed->field_defs as $field) {
            if(isset($field['type'])&&$field['type']=='link'
               &&isset($field['relationship'])&&isset($dictionary[$this->seed->object_name]['relationships'][$field['relationship']])
               &&$dictionary[$this->seed->object_name]['relationships'][$field['relationship']]['rhs_module']=='EmailAddresses') {
                $foundEmailField = true;
                break;
            }
        }
        if (!$foundEmailField) {
            return '';
        }


		$userPref = $GLOBALS['current_user']->getPreference('email_link_type');
		$defaultPref = $GLOBALS['sugar_config']['email_default_client'];
		if($userPref != '')
			$client = $userPref;
		else
			$client = $defaultPref;

		if($client == 'sugar')
			$script = "<a href='javascript:void(0)' " .
                    "id=\"composeemail_listview_". $loc ."\"".
					'onclick="return sListView.send_form_for_emails(true, \''."Emails".'\', \'index.php?module=Emails&action=Compose&ListView=true\',\''.$app_strings['LBL_LISTVIEW_NO_SELECTED'].'\', \''.$this->seed->module_dir.'\', \''.$totalCount.'\', \''.$app_strings['LBL_LISTVIEW_LESS_THAN_TEN_SELECT'].'\')">' .
					$app_strings['LBL_EMAIL_COMPOSE'] . '</a>';
		else
			$script = "<a href='javascript:void(0)' " .
                    "id=\"composeemail_listview_". $loc ."\"".
					"onclick=\"return sListView.use_external_mail_client('{$app_strings['LBL_LISTVIEW_NO_SELECTED']}', '{$_REQUEST['module']}');\">" .
					$app_strings['LBL_EMAIL_COMPOSE'] . '</a>';

        return $script;
	} // fn
	/**
	 * Builds the delete link
	 *
	 * @return string HTML
	 */
	protected function buildDeleteLink($loc = 'top')
	{
		global $app_strings;
        return "<a href='javascript:void(0)' id=\"delete_listview_". $loc ."\" onclick=\""
            . "return sListView.send_mass_update('selected', "
            . "SUGAR.language.get('app_strings', 'LBL_LISTVIEW_NO_SELECTED')"
            . ", 1)\">{$app_strings['LBL_DELETE_BUTTON_LABEL']}</a>";
 	}
	/**
	 * Display the selected object span object
	 *
     * @return string select object span
	 */
	function buildSelectedObjectsSpan($echo = true, $total=0) {
		global $app_strings;

        $displayStyle = $total > 0 ? "" : "display: none;";
		$selectedObjectSpan = "<span style='$displayStyle' id='selectedRecordsTop'>{$app_strings['LBL_LISTVIEW_SELECTED_OBJECTS']}<input  style='border: 0px; background: transparent; font-size: inherit; color: inherit' type='text' id='selectCountTop' readonly name='selectCount[]' value='{$total}' /></span>";

        return $selectedObjectSpan;
	}
    /**
	 * Builds the mail merge link
	 * The link can be disabled by setting module level duplicate_merge property to false
	 * in the moudle's vardef file.
	 *
	 * @return string HTML
	 */
	protected function buildMergeDuplicatesLink($loc = 'top')
	{
        global $app_strings, $dictionary;

        //need delete and edit access.
		if (!(ACLController::checkAccess($this->seed->module_dir, 'edit', true)) or !(ACLController::checkAccess($this->seed->module_dir, 'delete', true))) {
			return "";
		}

        if (isset($dictionary[$this->seed->object_name]['duplicate_merge']) && $dictionary[$this->seed->object_name]['duplicate_merge']==true ) {
            $params = array(
                'return_module' => $this->request->getValidInputRequest('module', 'Assert\Mvc\ModuleName'),
                'return_action' => $this->request->getValidInputRequest('action'),
                'return_id' => $this->request->getValidInputRequest('record', 'Assert\Guid'),
            );
            $params = array_filter($params);

            if (count($params) > 0) {
                $return_string = '&' . http_build_query($params);
            } else {
                $return_string = '';
            }

            $onclick = 'if (sugarListView.get_checks_count() > 1) {'
                . 'sListView.send_form(true, "MergeRecords", "index.php", "' . $app_strings['LBL_LISTVIEW_NO_SELECTED'] . '", "' . $this->seed->module_dir . '", ' . json_encode($return_string) . ');'
            . '} else {'
                . 'alert("' . $app_strings['LBL_LISTVIEW_TWO_REQUIRED'] . '");'
                . 'return false;'
            . '}';

            return "<a href='javascript:void(0)' ".
                            "id='mergeduplicates_listview_". $loc ."'".
                            'onclick="' . htmlspecialchars($onclick, ENT_QUOTES, 'UTF-8') . '">'.
                            $app_strings['LBL_MERGE_DUPLICATES'].'</a>';
        }

        return "";
     }
    /**
	 * Builds the mail merge link
	 *
	 * @return string HTML
	 */
	protected function buildMergeLink(array $modules_array = null, $loc = 'top')
	{
        if ( empty($modules_array) ) {
            require('modules/MailMerge/modules_array.php');
        }
        global $current_user, $app_strings;

        $admin = Administration::getSettings('system');
        $user_merge = $current_user->getPreference('mailmerge_on');
        $module_dir = (!empty($this->seed->module_dir) ? $this->seed->module_dir : '');
        $str = '';
        
        if ($user_merge == 'on' && isset($admin->settings['system_mailmerge_on']) && $admin->settings['system_mailmerge_on'] && !empty($modules_array[$module_dir])) {
            return "<a href='javascript:void(0)'  " .
                    "id='merge_listview_". $loc ."'"  .
					'onclick="if (document.MassUpdate.select_entire_list.value==1){document.location.href=\'index.php?action=index&module=MailMerge&entire=true\'} else {return sListView.send_form(true, \'MailMerge\',\'index.php\',\''.$app_strings['LBL_LISTVIEW_NO_SELECTED'].'\');}">' .
					$app_strings['LBL_MAILMERGE'].'</a>';
        }
        return $str;
	}

	/**
	 * Builds the add to target list link
	 *
     * @return string HTML
	 */
	protected function buildTargetList($loc = 'top')
	{
        global $app_strings;
		unset($_REQUEST[session_name()]);
		unset($_REQUEST['PHPSESSID']);
        $current_query_by_page = base64_encode(serialize($_REQUEST));

		$js = <<<EOF
            if(sugarListView.get_checks_count() < 1) {
                alert('{$app_strings['LBL_LISTVIEW_NO_SELECTED']}');
                return false;
            }
			if ( document.forms['targetlist_form'] ) {
				var form = document.forms['targetlist_form'];
				form.reset;
			} else
				var form = document.createElement ( 'form' ) ;
			form.setAttribute ( 'name' , 'targetlist_form' );
			form.setAttribute ( 'method' , 'post' ) ;
			form.setAttribute ( 'action' , 'index.php' );
			document.body.appendChild ( form ) ;
			if ( !form.module ) {
			    var input = document.createElement('input');
			    input.setAttribute ( 'name' , 'module' );
			    input.setAttribute ( 'value' , '{$this->seed->module_dir}' );
			    input.setAttribute ( 'type' , 'hidden' );
			    form.appendChild ( input ) ;
			    var input = document.createElement('input');
			    input.setAttribute ( 'name' , 'action' );
			    input.setAttribute ( 'value' , 'TargetListUpdate' );
			    input.setAttribute ( 'type' , 'hidden' );
			    form.appendChild ( input ) ;
			}
			if ( !form.uids ) {
			    var input = document.createElement('input');
			    input.setAttribute ( 'name' , 'uids' );
			    input.setAttribute ( 'type' , 'hidden' );
			    form.appendChild ( input ) ;
			}
			if ( !form.prospect_list ) {
			    var input = document.createElement('input');
			    input.setAttribute ( 'name' , 'prospect_list' );
			    input.setAttribute ( 'type' , 'hidden' );
			    form.appendChild ( input ) ;
			}
			if ( !form.return_module ) {
			    var input = document.createElement('input');
			    input.setAttribute ( 'name' , 'return_module' );
			    input.setAttribute ( 'type' , 'hidden' );
			    form.appendChild ( input ) ;
			}
			if ( !form.return_action ) {
			    var input = document.createElement('input');
			    input.setAttribute ( 'name' , 'return_action' );
			    input.setAttribute ( 'type' , 'hidden' );
			    form.appendChild ( input ) ;
			}
			if ( !form.select_entire_list ) {
			    var input = document.createElement('input');
			    input.setAttribute ( 'name' , 'select_entire_list' );
			    input.setAttribute ( 'value', document.MassUpdate.select_entire_list.value);
			    input.setAttribute ( 'type' , 'hidden' );
			    form.appendChild ( input ) ;
			}
			if ( !form.current_query_by_page ) {
			    var input = document.createElement('input');
			    input.setAttribute ( 'name' , 'current_query_by_page' );
			    input.setAttribute ( 'value', '{$current_query_by_page}' );
			    input.setAttribute ( 'type' , 'hidden' );
			    form.appendChild ( input ) ;
			}
			open_popup('ProspectLists','600','400','',true,false,{ 'call_back_function':'set_return_and_save_targetlist','form_name':'targetlist_form','field_to_name_array':{'id':'prospect_list'} } );
EOF;
        $js = str_replace(array("\r","\n"),'',$js);
        return "<a href='javascript:void(0)' id=\"targetlist_listview_". $loc ."\" onclick=\"$js\">{$app_strings['LBL_ADD_TO_PROSPECT_LIST_BUTTON_LABEL']}</a>";
	}
	/**
	 * Display the bottom of the ListView (ie MassUpdate
	 * @return string contents
	 */
	public function displayEnd() 
	{
		$str = '';
		if($this->show_mass_update_form) {
			$str .= $this->mass->getMassUpdateForm(true);
			$str .= $this->mass->endMassUpdateForm();
		}

		return $str;
	}

    /**
     * Display the multi select data box etc.
     * @return string contents
     */
	public function getMultiSelectData() 
	{
		$str = "<script>YAHOO.util.Event.addListener(window, \"load\", sListView.check_boxes);</script>\n";

		$massUpdateRun = isset($_REQUEST['massupdate']) && $_REQUEST['massupdate'] == 'true';
		$uids = empty($_REQUEST['uid']) || $massUpdateRun ? '' : $_REQUEST['uid'];
        $select_entire_list = ($massUpdateRun) ? 0 : (isset($_POST['select_entire_list']) ? $_POST['select_entire_list'] : (isset($_REQUEST['select_entire_list']) ? $_REQUEST['select_entire_list'] : 0));

		$str .= "<textarea style='display: none' name='uid'>{$uids}</textarea>\n" .
				"<input type='hidden' name='select_entire_list' value='{$select_entire_list}'>\n".
				"<input type='hidden' name='{$this->moduleString}' value='0'>\n".
		        "<input type='hidden' name='show_plus' value='{$this->show_plus}'>\n";
		return $str;
	}

     /**
     * @return MassUpdate instance
     */
    protected function getMassUpdate()
    {
        return new MassUpdate($this->request);
    }

    /**
     * Fill displayColumns with additional field values from vardefs of the current bean seed.
     * We need vardefs to be in displayColumns for a further processing (e.g. in SugarField)
     * Similar vardef field values do not override field values from displayColumns, only necessary and missing ones are added
     */
    protected function fillDisplayColumnsWithVardefs()
    {
        foreach ($this->displayColumns as $columnName => $def) {
            $seedName =  strtolower($columnName);
            if (!empty($this->lvd->seed->field_defs[$seedName])) {
                $seedDef = $this->lvd->seed->field_defs[$seedName];
            }

            if (empty($this->displayColumns[$columnName]['type'])) {
                if (!empty($seedDef['type'])) {
                    $this->displayColumns[$columnName]['type'] = (!empty($seedDef['custom_type']))?$seedDef['custom_type']:$seedDef['type'];
                } else {
                    $this->displayColumns[$columnName]['type'] = '';
                }
            }//fi empty(...)

            if (!empty($seedDef['options'])) {
                $this->displayColumns[$columnName]['options'] = $seedDef['options'];
            }

            //Bug 40511, make sure relate fields have the correct module defined
            if ($this->displayColumns[$columnName]['type'] == "relate" && !empty($seedDef['link']) && empty( $this->displayColumns[$columnName]['module'])) {
                $link = $seedDef['link'];
                if (!empty($this->lvd->seed->field_defs[$link]) && !empty($this->lvd->seed->field_defs[$seedDef['link']]['module'])) {
                    $this->displayColumns[$columnName]['module'] = $this->lvd->seed->field_defs[$seedDef['link']]['module'];
                }
            }

            if (!empty($seedDef['sort_on']) && !is_array($seedDef['sort_on'])) {
                $this->displayColumns[$columnName]['orderBy'] = $seedDef['sort_on'];
            }

            // bug50645 Blank value for URL custom field in DetailView and subpanel
            // we need to replace the "default" attribute value with the value set in field definition
            if (!empty($this->displayColumns[$columnName]['default']) && isset($seedDef['default'])) {
                $this->displayColumns[$columnName]['default'] = $seedDef['default'];
            }

            if (isset($seedDef)) {
                // Merge the two arrays together, making sure the seedDef doesn't override anything explicitly set in the displayColumns array.
                $this->displayColumns[$columnName] = $this->displayColumns[$columnName] + $seedDef;
            }

            //C.L. Bug 38388 - ensure that ['id'] is set for related fields
            if (!isset($this->displayColumns[$columnName]['id']) && isset($this->displayColumns[$columnName]['id_name'])) {
                $this->displayColumns[$columnName]['id'] = strtoupper($this->displayColumns[$columnName]['id_name']);
            }
        }
    }

    /**
     * Fill in the HTML fields, since the values come from the vardefs
     *
     * @param $data - ListView Data
     */
    protected function setupHTMLFields($data)
    {
        foreach ($this->displayColumns as $columnName => $def) {
            $columnLower = strtolower($columnName);
            if ($this->displayColumns[$columnName]['type'] == 'html') {
                if (isset($this->seed->custom_fields)) {
                    $customField = $this->seed->custom_fields;
                    if (isset($customField->bean) && isset($customField->bean->$columnLower)) {
                        $htmlDisplay = html_entity_decode($customField->bean->$columnLower);
                        for ($count = 0; $count < count($data['data']); $count++) {
                            $data['data'][$count][$columnName] = $htmlDisplay;
                        }
                    }
                }
            }
        }

        return $data;
    }
}
