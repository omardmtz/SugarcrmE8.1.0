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

require_once('modules/EAPM/EAPM.php');
class MeetingsViewListbytype extends ViewList {
    var $options = array('show_header' => false, 'show_title' => false, 'show_subpanels' => false, 'show_search' => true, 'show_footer' => false, 'show_javascript' => false, 'view_print' => false,);

 	function listViewProcess(){
        if (!$eapmBean = EAPM::getLoginInfo('IBMSmartCloud', true) ) {
            $smarty = new Sugar_Smarty();
            echo $smarty->fetch('include/externalAPI/IBMSmartCloud/IBMSmartCloudSignup.'.$GLOBALS['current_language'].'.tpl');
            return;
        }

        $apiName = 'IBMSmartCloud';
        $api = ExternalAPIFactory::loadAPI($apiName,true);
        $api->loadEAPM($eapmBean);

        $quickCheck = $api->quickCheckLogin();
        if ( ! $quickCheck['success'] ) {
            $errorMessage = string_format(translate('LBL_ERR_FAILED_QUICKCHECK','EAPM'), array('IBM SmartCloud'));
            $errorMessage .= '<form method="POST" target="_EAPM_CHECK" action="index.php">';
            $errorMessage .= '<input type="hidden" name="module" value="EAPM">';
            $errorMessage .= '<input type="hidden" name="action" value="Save">';
            $errorMessage .= '<input type="hidden" name="record" value="'.$eapmBean->id.'">';
            $errorMessage .= '<input type="hidden" name="active" value="1">';
            $errorMessage .= '<input type="hidden" name="closeWhenDone" value="1">';
            $errorMessage .= '<input type="hidden" name="refreshParentWindow" value="1">';

            $errorMessage .= '<br><input type="submit" value="'.$GLOBALS['app_strings']['LBL_EMAIL_OK'].'">&nbsp;';
            $errorMessage .= '<input type="button" onclick="lastLoadedMenu=undefined;DCMenu.closeOverlay();return false;" value="'.$GLOBALS['app_strings']['LBL_CANCEL_BUTTON_LABEL'].'">';
            $errorMessage .= '</form>';
            echo $errorMessage;
            return;
        }

		$this->processSearchForm();
        $this->params['orderBy'] = 'meetings.date_start';
        $this->params['overrideOrder'] = true;
		$this->lv->searchColumns = $this->searchForm->searchColumns;
		$this->lv->show_action_dropdown = false;
   		$this->lv->multiSelect = false;   		
   		
   		unset($this->searchForm->searchdefs['layout']['advanced_search']);
   		
		if(!$this->headers) {
			return;
        }

		if(empty($_REQUEST['search_form_only']) || $_REQUEST['search_form_only'] == false){
			$this->lv->ss->assign("SEARCH",false);
            $name_basic = $this->request->getValidInputRequest('name_basic');
            $this->lv->ss->assign('DCSEARCH', $name_basic);
			$this->lv->setup($this->seed, 'include/ListView/ListViewDCMenu.tpl', $this->where, $this->params);
			echo $this->lv->display();
		}
 	}
 	
    function listViewPrepare() {
        $oldRequest = $_REQUEST;
        parent::listViewPrepare();
        $_REQUEST = $oldRequest;
    }

    function processSearchForm(){
   		// $type = 'LotusLiveDirect';
   		$type = 'IBMSmartCloud';
          global $timedate;

         $two_hours_ago = $GLOBALS['db']->convert($GLOBALS['db']->quoted($timedate->asDb($timedate->getNow()->get("-2 hours"))), 'datetime');

   		$where =  " meetings.type = '$type' AND meetings.status != 'Held' AND meetings.status != 'Not Held' AND meetings.date_start > {$two_hours_ago} AND ( meetings.assigned_user_id = '".$GLOBALS['db']->quote($GLOBALS['current_user']->id)."' OR exists ( SELECT id FROM meetings_users WHERE meeting_id = meetings.id AND user_id = '".$GLOBALS['db']->quote($GLOBALS['current_user']->id)."' AND deleted = 0 ) ) ";

          if ( isset($_REQUEST['name_basic']) ) {
              $name_search = trim($_REQUEST['name_basic']);
              if ( ! empty($name_search) ) {
                  $where .= " AND meetings.name LIKE '".$GLOBALS['db']->quote($name_search)."%' ";
              }
          }

          $this->where = $where;
   	}

}
