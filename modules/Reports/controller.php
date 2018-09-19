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
/*********************************************************************************
 * $Id$
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

class ReportsController extends SugarController
{	
	/** 
	 * @see SugarController::setup($module = '')
	 */
	
	public function setup($module = '')
	{
		$result = parent::setup($module);
		
		// bug 41860 fix
		if(!empty($_REQUEST['id']))
			$this->record = $_REQUEST['id'];
		// end bugfix
		
		return $result;
	}
	
    /**
     * @see SugarController::loadBean()
     */
	public function loadBean()
	{			
		if(!empty($_REQUEST['record']) && $_REQUEST['action'] == 'ReportsWizard'){
			$_REQUEST['id'] = $this->record;
			$_REQUEST['page'] = 'report';
			$this->view_object_map['action'] =  'ReportsWizard';
		}
		else if(empty($this->record) && !empty($_REQUEST['id'])){
			$this->record = $_REQUEST['id'];
			$GLOBALS['action'] = 'detailview';
			$this->view_object_map['action'] =  'ReportCriteriaResults';
		}
		elseif(!empty($this->record)){
			if ($_REQUEST['action'] == 'DetailView') {
				$_REQUEST['id'] = $this->record;
				unset($_REQUEST['record']);
			}else{
				$GLOBALS['action'] = 'detailview'; //bug 41860 
			}
			$_REQUEST['page'] = 'report';
			$this->view_object_map['action'] =  'ReportCriteriaResults';
		}
		
		parent::loadBean();
	}
	
	public function action_buildreportmoduletree() 
	{
	    $this->view = 'buildreportmoduletree';
	}
	
	public function action_add_schedule() 
	{
	    $this->view = 'schedule';
	}
	
	public function action_detailview()
	{
		$this->view = 'classic';
	}

	public function action_get_teamset_field() 
	{
		$view = new ReportsSugarFieldTeamsetCollection(true);
		$view->setup();
		$view->process();
		$view->init_tpl();
		echo $view->display();
	}
	public function action_get_quicksearch_defaults() 
	{
		global $global_json;
		$global_json = getJSONobj();
		$qsd = QuickSearchDefaults::getQuickSearchDefaults();
		if (!empty($_REQUEST['parent_form']))
			$qsd->form_name = $_REQUEST['parent_form'];
		$quicksearch_js = array();
		if (isset($_REQUEST['parent_module']) && isset($_REQUEST['parent_field'])) {
			$sqs_objects = array($_REQUEST['parent_field'] => $qsd->getQSParent($_REQUEST['parent_module'])); 
    		foreach($sqs_objects as $sqsfield => $sqsfieldArray) {
                $quicksearch_js[$sqsfield] = $global_json->encode($sqsfieldArray);
            }
		}

        echo json_encode($quicksearch_js);
	}

    protected function action_massupdate(){
        //bug: 44857 - Reports calls MasUpdate passing back the 'module' parameter, but that is also a parameter in the database
        //so when we call MassUpdate with $addAllBeanFields then it will use this in the query.
		$query = $this->request->getValidInputRequest(
            'current_query_by_page',
            array('Assert\PhpSerialized' => array('base64Encoded' => true))
        );

        if(!empty($query['module'])) {
            unset($query['module']);
            $_REQUEST['current_query_by_page'] = base64_encode(serialize($query));
        }
        parent::action_massupdate();
    }
}
