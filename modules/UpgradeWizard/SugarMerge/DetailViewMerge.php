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
 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
/**
 * This class extends the EditViewMerge - since the meta data is relatively the same the only thing that needs to be changed is the parameter for viewdefs
 *
 */
class DetailViewMerge extends EditViewMerge{
	/**
	 * Enter the name of the parameter used in the $varName for example in editviewdefs and detailviewdefs it is 'EditView' and 'DetailView' respectively - $viewdefs['EditView']
	 *
	 * @var STRING
	 */
	protected $viewDefs = 'DetailView';
		/**
	 * Determines if getFields should analyze panels to determine if it is a MultiPanel
	 *
	 * @var BOOLEAN
	 */
	protected $scanForMultiPanel = true;	/**
	 * Parses out the fields for each files meta data and then calls on mergeFields and setPanels
	 *
	 */
	protected function mergeMetaData(){
		$this->originalFields = $this->getFields($this->originalData[$this->module][$this->viewDefs][$this->panelName]);
		$this->originalPanelIds = $this->getPanelIds($this->originalData[$this->module][$this->viewDefs][$this->panelName]);
		$this->customFields = $this->getFields($this->customData[$this->module][$this->viewDefs][$this->panelName]);

		//Special handling to rename certain variables for DetailViews
		$rename_fields = array();
		foreach($this->customFields as $field_id=>$field){
		    //Check to see if we need to rename the field for special cases
			if(!empty($this->fieldConversionMapping[$this->module][$field_id])) {
			    $new_name = $this->fieldConversionMapping[$this->module][$field['data']['name']];
			    if(!empty($this->customFields[$new_name])) {
			        // if that field is already there, do not rename
			        continue;
			    }
			    $rename_fields[$field_id] = $new_name;
			    $this->customFields[$field_id]['data']['name'] = $this->fieldConversionMapping[$this->module][$field['data']['name']];
			}
		}

		foreach($rename_fields as $original_index=>$new_index) {
			$this->customFields[$new_index] = $this->customFields[$original_index];
			unset($this->customFields[$original_index]);
		}

		$this->customPanelIds = $this->getPanelIds($this->customData[$this->module][$this->viewDefs][$this->panelName]);
		$this->newFields = $this->getFields($this->newData[$this->module][$this->viewDefs][$this->panelName]);
		$this->newPanelIds = $this->getPanelIds($this->newData[$this->module][$this->viewDefs][$this->panelName]);
		$this->mergeFields();
		$this->mergeTemplateMeta();
		$this->setPanels();
	}

}
