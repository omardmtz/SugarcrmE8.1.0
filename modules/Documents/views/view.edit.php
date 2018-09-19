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

class DocumentsViewEdit extends ViewEdit
{
     public function __construct()
     {
         parent::__construct();
         $this->useForSubpanel = true;
     }

 	/**
	 * @see SugarView::display()
	 */
	public function display()
 	{
		global $app_list_strings, $mod_strings;

		$loadSignedId = $this->request->getValidInputRequest('load_signed_id');
		$record = $this->request->getValidInputRequest('record', 'Assert\Guid');
        $action = $this->request->getValidInputRequest('action');
		$selectedRevisionId = $this->request->getValidInputRequest('selected_revision_id', 'Assert\Guid');

		$load_signed=false;
		if (!empty($loadSignedId)) {

			$load_signed=true;
			if ($record !== null) {
				$this->bean->related_doc_id = $record;
			}
			if ($selectedRevisionId !== null) {
				$this->bean->related_doc_rev_id = $selectedRevisionId;
			}

			$this->bean->id=null;
			$this->bean->document_name=null;
			$this->bean->filename=null;
			$this->bean->is_template=0;
		} //if

		if (!empty($this->bean->id) ||
            (empty($this->bean->id) && $record !== null && $action !== null && strtolower($action)=='quickedit')
        ) {
			$this->ss->assign("FILE_OR_HIDDEN", "hidden");
			if (!$this->ev->isDuplicate) {
				$this->ss->assign("DISABLED", "disabled");
			}
		} else {
			$this->bean->revision = 1;
		    $this->ss->assign("FILE_OR_HIDDEN", "file");
		}

		$popup_request_data = array(
			'call_back_function' => 'document_set_return',
			'form_name' => 'EditView',
			'field_to_name_array' => array(
				'id' => 'related_doc_id',
				'document_name' => 'related_document_name',
				),
			);
		$json = getJSONobj();
		$this->ss->assign('encoded_document_popup_request_data', $json->encode($popup_request_data));


		//get related document name.
		if (!empty($this->bean->related_doc_id)) {
			$this->ss->assign("RELATED_DOCUMENT_NAME",Document::get_document_name($this->bean->related_doc_id));
			$this->ss->assign("RELATED_DOCUMENT_ID",$this->bean->related_doc_id);
			if (!empty($this->bean->related_doc_rev_id)) {
				$this->ss->assign("RELATED_DOCUMENT_REVISION_OPTIONS", get_select_options_with_id(DocumentRevision::get_document_revisions($this->bean->related_doc_id), $this->bean->related_doc_rev_id));
			} else {
				$this->ss->assign("RELATED_DOCUMENT_REVISION_OPTIONS", get_select_options_with_id(DocumentRevision::get_document_revisions($this->bean->related_doc_id), ''));
			}
		} else {
			$this->ss->assign("RELATED_DOCUMENT_REVISION_DISABLED", "disabled");
		}

		$parentId = $this->request->getValidInputRequest('parent_id', 'Assert\Guid');
		$parentName = $this->request->getValidInputRequest('parent_name');
		$parentType = $this->request->getValidInputRequest('parent_type', 'Assert\Mvc\ModuleName');
		//set parent information in the form.
		if ($parentId !== null) {
			$this->ss->assign("PARENT_ID",$parentId);
		} //if

		if ($parentName !== null) {
			$this->ss->assign("PARENT_NAME", $parentName);

			if (!empty($parentType)) {
				switch (strtolower($parentType)) {

					case "contracts" :
						$this->ss->assign("LBL_PARENT_NAME",$mod_strings['LBL_CONTRACT_NAME']);
						break;

					//todo remove leads case.
					case "leads" :
						$this->ss->assign("LBL_PARENT_NAME",$mod_strings['LBL_CONTRACT_NAME']);
						break;
				} //switch
			} //if
		} //if

		if ($parentType !== null) {
			$this->ss->assign("PARENT_TYPE", $parentType);
		}

		if ($load_signed) {
			$this->ss->assign("RELATED_DOCUMENT_REVISION_DISABLED", "disabled");
			$this->ss->assign("RELATED_DOCUMENT_BUTTON_AVAILABILITY", "hidden");
			$this->ss->assign("LOAD_SIGNED_ID",$_REQUEST['load_signed_id']);
		} else {
			$this->ss->assign("RELATED_DOCUMENT_BUTTON_AVAILABILITY", "button");
		} //if-else

 		parent::display();
 	}

	/**
	 * @see SugarView::_getModuleTitleParams()
	 */
	protected function _getModuleTitleParams($browserTitle = false)
	{
    	$params = array();
    	$params[] = $this->_getModuleTitleListParam($browserTitle);
    	if(!empty($this->bean->id)){
			$params[] = "<a href='index.php?module={$this->module}&action=DetailView&record={$this->bean->id}'>".$this->bean->document_name."</a>";
			$params[] = $GLOBALS['app_strings']['LBL_EDIT_BUTTON_LABEL'];
		}else{
			$params[] = $GLOBALS['app_strings']['LBL_CREATE_BUTTON_LABEL'];
		}

		return $params;
    }

}
