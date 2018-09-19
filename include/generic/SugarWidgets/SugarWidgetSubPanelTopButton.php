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

// $Id: SugarWidgetSubPanelTopButton.php 51841 2009-10-26 20:33:15Z jmertic $



class SugarWidgetSubPanelTopButton extends SugarWidget
{
    public $module;
    public $title;
    public $access_key;
    public $form_value;
    public $additional_form_fields;
    public $acl;
    public $enableActionMenu;
    
    /**
     * Flag that determines if the requested subpanel should open a sidecar quick
     * create form or a BWC one. This is set in {@see _get_form_sidecar()}.
     * @var boolean
     */
    public $sidecar;

    /**
     * Widget id suffix
     *
     * @var string
     */
    protected $buttonSuffix = '_button';

// TODO rename defines to layout defs and
// make it a member variable instead of passing it multiple layers with extra copying.

	/** Take the keys for the strings and look them up.  Module is literal, the rest are label keys
	*/
    public function __construct($module = '', $title = '', $access_key = '', $form_value = '')
	{
		global $app_strings;

		if(is_array($module))
		{
			// it is really the class details from the mapping
			$class_details = $module;

			// If keys were passed into the constructor, translate them from keys to values.
			if(!empty($class_details['module']))
				$this->module = $class_details['module'];
			if(!empty($class_details['title']))
				$this->title = $app_strings[$class_details['title']];
			if(!empty($class_details['access_key']))
				$this->access_key = $app_strings[$class_details['access_key']];
			if(!empty($class_details['form_value']))
				$this->form_value = translate($class_details['form_value'], $this->module);
			if(!empty($class_details['additional_form_fields']))
				$this->additional_form_fields = $class_details['additional_form_fields'];
			if(!empty($class_details['ACL'])){
				$this->acl = $class_details['ACL'];
			}
		}
		else
		{
			$this->module = $module;

			// If keys were passed into the constructor, translate them from keys to values.
			if(!empty($title))
				$this->title = $app_strings[$title];
			if(!empty($access_key))
				$this->access_key = $app_strings[$access_key];
			if(!empty($form_value))
				$this->form_value = translate($form_value, $module);
		}

        if (isset($GLOBALS['sugar_config']['enable_action_menu']) && $GLOBALS['sugar_config']['enable_action_menu']===false) {
            $this->enableActionMenu = false;
        } else {
            $this->enableActionMenu = true;
        }
	}

    public function getWidgetId()
    {
        $isUTF8 = mb_detect_encoding($this->form_value) == 'UTF-8';
        $formValue = $isUTF8 ? mb_strtolower($this->form_value, 'UTF-8') : strtolower($this->form_value);
    	$widgetID = parent::getWidgetId() . '_'.preg_replace('[ ]', '', $formValue);
        if ($this->buttonSuffix) {
            $widgetID .= $this->buttonSuffix;
    	}
        return $widgetID;
    }

    /**
     * This is a shim for while we're still in bwc when the current module is a
     * sidecar (not bwc) module. See SP-1630: Clicking Create from BWC subpanels
     * for sidecar should open sidecar create view.
     * @param $defines
     * @return a sidecar compatable button or falsy
     */
    function _get_form_sidecar($defines) {
        global $app_strings;
        global $subpanel_item_count;
        global $current_language;

        $sidecarReadySubPanelCreates = array(
            "SubPanelTopCreateButton",
            "SubPanelTopButtonQuickCreate",
            "SubPanelTopCreateAccountNameButton",
            "SubPanelTopCreateLeadNameButton",
            "SubPanelTopCreateNoteButton",
            "SubPanelTopScheduleMeetingButton",
            "SubPanelTopScheduleCallButton",
            "SubPanelTopCreateTaskButton"
        );

        $module = $defines['module'];
        $label = $app_strings['LBL_CREATE_BUTTON_LABEL'];

        //Sometimes module is 'History' but the child module is Notes. For the
        //purposes of determining whether to redirect to sidecar create or not,
        //treat History (a bwc) as a Note (a sidecar).
        if ($defines['widget_class'] == 'SubPanelTopCreateNoteButton' && $defines['child_module_name'] == 'Notes') {
            $module = 'Notes';
            $modStringsNotes = return_module_language($current_language, 'Notes');
            $label = $modStringsNotes['LNK_NEW_NOTE'];
        }

        //Not bwc and in our white-listed subpanel create button widgets
        if (!isModuleBWC($module) && in_array($defines['widget_class'], $sidecarReadySubPanelCreates)) {
            $wid = $this->getWidgetId();
            $id = $wid."_create_".$subpanel_item_count;//bug 51512
            $parentId = $defines['focus']->id;
            $relationship_name = $this->get_subpanel_relationship_name($defines);
            $form = 'form' . $relationship_name;
            $panelDefs = $defines['subpanel_definition'];
            $link = '';

            //Normalize Activities which should result in child module creates
            if ($module == "Activities" &&  $defines['child_module_name'] == 'Tasks') {
                $module = "Tasks";
                $label = $app_strings['LBL_CREATE_TASK'];
            } elseif ($module == "Activities" &&  $defines['child_module_name'] == 'Meetings') {
                $module = "Meetings";
                $label = $app_strings['LBL_SCHEDULE_MEETING_BUTTON_LABEL'];
            } elseif ($module == "Activities" &&  $defines['child_module_name'] == 'Calls') {
                $module = "Calls";
                $label = $app_strings['LBL_SCHEDULE_CALL'];
            }

            if ($panelDefs->isCollection()) {
                foreach ($panelDefs->sub_subpanels as $panel) {
                    if ($panel->get_module_name() == $module) {
                        $link = $panel->get_data_source_name();
                        break;
                    }
                }
            } else {
                $link = $panelDefs->get_data_source_name();
            }

            if ($this->enableActionMenu) {
                $button = '<form data-legacy-subpanel-create="1" action="index.php" method="post" name="form" id="' . $form . "\">\n".
                    "<a href='#' onClick=\"javascript:subp_nav_sidecar(
                        '" . $module . "','" . $parentId . "','c', '" . $link . "'
                    );\"".
                    " class='create_from_bwc_to_sidecar' id=\"$id\">". $label .'</a>';
            } else {
                $button = '<form data-legacy-subpanel-create="1" action="index.php" method="post" name="form" id="' . $form . "\">\n".
                    "<input type='button' onClick=\"javascript:subp_nav_sidecar(
                        '" . $module . "','" . $parentId . "','c', '" . $link . "'
                    );\"".
                    " class='create_from_bwc_to_sidecar' id=\"$id\" value=\"$label\">";
            }

            // Set the sidecar flag so that calling codes knows what to do
            $this->sidecar = true;
            return $button;
        }
        return false;
    }

    /**
     * @param array $defines
     * @param array $additionalFormFields
     */
    public function &_get_form($defines, $additionalFormFields = null)
    {
        global $app_strings;
        global $currentModule;

        // Create the additional form fields with real values if they were not passed in
        if(empty($additionalFormFields) && $this->additional_form_fields)
        {
            foreach($this->additional_form_fields as $key=>$value)
            {
                if(!empty($defines['focus']->$value))
                {
                    $additionalFormFields[$key] = $defines['focus']->$value;
                }
                else
                {
                    $additionalFormFields[$key] = '';
                }
            }
        }


		if(!empty($this->module))
        {
            $defines['child_module_name'] = $this->module;
        }
        else
        {
            $defines['child_module_name'] = $defines['module'];
        }

        $defines['parent_bean_name'] = get_class( $defines['focus']);
		$relationship_name = $this->get_subpanel_relationship_name($defines);


        $formValues = array();

        //module_button is used to override the value of module name
        $formValues['module'] = $defines['child_module_name'];
        $formValues[strtolower($defines['parent_bean_name'])."_id"] = $defines['focus']->id;

        if(isset($defines['focus']->name))
        {
            $formValues[strtolower($defines['parent_bean_name'])."_name"] = $defines['focus']->name;
            // #26451,add these fields for custom one-to-many relate field.
            if(!empty($defines['child_module_name'])){
                $formValues[$relationship_name."_name"] = $defines['focus']->name;
            	$childFocusName = !empty($GLOBALS['beanList'][$defines['child_module_name']]) ? $GLOBALS['beanList'][$defines['child_module_name']] : "";
            	if(!empty($GLOBALS['dictionary'][ $childFocusName ]["fields"][$relationship_name .'_name']['id_name'])){
            		$formValues[$GLOBALS['dictionary'][ $childFocusName ]["fields"][$relationship_name .'_name']['id_name']] = $defines['focus']->id;
            	}
            }
        }

        $formValues['return_module'] = $currentModule;

        if($currentModule == 'Campaigns'){
            $formValues['return_action'] = "DetailView";
        }else{
            $formValues['return_action'] = $defines['action'];
            if ( $formValues['return_action'] == 'SubPanelViewer' ) {
                $formValues['return_action'] = 'DetailView';
            }
        }

        $formValues['return_id'] = $defines['focus']->id;
        $formValues['return_relationship'] = $relationship_name;
        switch ( strtolower( $currentModule ) )
        {
            case 'prospects' :
                $name = $defines['focus']->account_name ;
                break ;
            case 'documents' :
                $name = $defines['focus']->document_name ;
                break ;
            case 'leads' :
            case 'contacts' :
                $name = $defines['focus']->first_name . " " .$defines['focus']->last_name ;
                break ;
            default :
               $name = (isset($defines['focus']->name)) ? $defines['focus']->name : "";
        }
        $formValues['return_name'] = $name;

        // TODO: move this out and get $additionalFormFields working properly
        if(empty($additionalFormFields['parent_type']))
        {
            if($defines['focus']->object_name=='Contact') {
                $additionalFormFields['parent_type'] = 'Accounts';
            }
            else {
                $additionalFormFields['parent_type'] = $defines['focus']->module_dir;
            }
        }
        if(empty($additionalFormFields['parent_name']))
        {
            if($defines['focus']->object_name=='Contact') {
                $additionalFormFields['parent_name'] = $defines['focus']->account_name;
                $additionalFormFields['account_name'] = $defines['focus']->account_name;
            }
            else {
                $additionalFormFields['parent_name'] = $defines['focus']->name;
            }
        }
        if(empty($additionalFormFields['parent_id']))
        {
            if($defines['focus']->object_name=='Contact') {
                $additionalFormFields['parent_id'] = $defines['focus']->account_id;
                $additionalFormFields['account_id'] = $defines['focus']->account_id;
            } else if($defines['focus']->object_name=='Contract') {
            	$additionalFormFields['contract_id'] = $defines['focus']->id;
            } else {
                $additionalFormFields['parent_id'] = $defines['focus']->id;
            }
        }

        if ($defines['focus']->object_name=='Opportunity') {
            $additionalFormFields['account_id'] = $defines['focus']->account_id;
            $additionalFormFields['account_name'] = $defines['focus']->account_name;
        }

        if (!empty($defines['child_module_name']) and $defines['child_module_name']=='Contacts' and !empty($defines['parent_bean_name']) and $defines['parent_bean_name']=='contact' ) {
            if (!empty($defines['focus']->id ) and !empty($defines['focus']->name)) {
                $formValues['reports_to_id'] = $defines['focus']->id;
                $formValues['reports_to_name'] = $defines['focus']->name;
            }
        }
        $formValues['action'] = "EditView";

        if (!empty($this->asUrl)) {
            $returnLink = '';
            foreach($formValues as $key => $value ) {
                $returnLink .= $key.'='.$value.'&';
            }
            foreach($additionalFormFields as $key => $value ) {
                $returnLink .= $key.'='.$value.'&';
            }
            $returnLink = rtrim($returnLink,'&');

            return $returnLink;
        } else {
            //SP-1630: Clicking Create from BWC subpanels for sidecar should open sidecar create view
            $sidecarButton = $this->_get_form_sidecar($defines);
            if ($sidecarButton) {
                return $sidecarButton;
            }

            $form = 'form' . $relationship_name;
            $button = '<form action="index.php" method="post" name="form" id="' . $form . "\">\n";

            foreach($formValues as $key => $value) {
                $button .= "<input type='hidden' name='" . $key . "' value='" . $value . "' />\n";
            }

            // fill in additional form fields for all but action
            foreach($additionalFormFields as $key => $value) {
                if($key != 'action') {
                    $button .= "<input type='hidden' name='" . $key . "' value='" . $value . "' />\n";
                }
            }

            return $button;
        }
    }

    /**
     * {@inheritDoc}
     *
     * @param array $defines              Layout definitions
     * @param array $additionalFormFields Additional form fields
     */
    public function display(array $defines, $additionalFormFields = array())
	{
		$temp='';
		$inputID = $this->getWidgetId();

		if(!empty($this->acl) && ACLController::moduleSupportsACL($defines['module'])  &&  !ACLController::checkAccess($defines['module'], $this->acl, true)){
			return $temp;
		}

        /**
         * if module is hidden or subpanel for the module is hidden - doesn't show quick create button
         */
        if ( SugarWidget::isModuleHidden( !empty($this->module) ? $this->module : $defines['module'] ) )
        {
            return '';
        }

		global $app_strings;

        if ( isset($_REQUEST['layout_def_key']) && $_REQUEST['layout_def_key'] == 'UserEAPM' ) {
            // Subpanels generally don't go on the editview, so we have to handle this special
            $this->asUrl = true;
            $megaLink = $this->_get_form($defines, $additionalFormFields);
            $button = "<input title='$this->title' accesskey='$this->access_key' class='button' type='submit' name='$inputID' id='$inputID' value='$this->form_value' onclick='javascript:document.location=\"index.php?".$megaLink."\"; return false;'/>";
        } else {
            $button = $this->_get_form($defines, $additionalFormFields);

            // $this->sidecar is set in _get_form_sidecar()
            if ($this->sidecar !== true) {
                $button .= "<input title='$this->title' accesskey='$this->access_key' class='button' type='submit' name='$inputID' id='$inputID' value='$this->form_value' />\n";
            }
            $button .= "</form>";
        }
        return $button;
	}

	/**
	 * Returns a string that is the JSON encoded version of the popup request.
	 * Perhaps this function should be moved to a more globally accessible location?
	 */
	function _create_json_encoded_popup_request($popup_request_data)
	{
	    return json_encode($popup_request_data);
	}

	/**
	 * get_subpanel_relationship_name
	 * Get the relationship name based on the subapnel definition
	 * @param mixed $defines The subpanel definition
	 */
	function get_subpanel_relationship_name($defines) {
		 $relationship_name = '';
		 if(!empty($defines)) {
		 	$relationship_name = isset($defines['module']) ? $defines['module'] : '';
	     	$dataSource = $defines['subpanel_definition']->get_data_source_name(true);
         	if (!empty($dataSource)) {
				$relationship_name = $dataSource;
				//Try to set the relationship name to the real relationship, not the link.
				if (!empty($defines['subpanel_definition']->parent_bean->field_defs[$dataSource])
				 && !empty($defines['subpanel_definition']->parent_bean->field_defs[$dataSource]['relationship']))
				{
					$relationship_name = $defines['subpanel_definition']->parent_bean->field_defs[$dataSource]['relationship'];
				}
			}
		 }
		 return $relationship_name;
	}

}

