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

/**
 * EmailSugarFieldTeamset.php
 * This class handles special rendering
 *
 */

require_once 'modules/Teams/TeamSetManager.php';

class EmailSugarFieldTeamsetCollection extends ViewSugarFieldTeamsetCollection {

	var $user_id;

    public function __construct($bean, $field_defs, $customMethod = '', $form_name = 'EditView')
    {
        parent::__construct(false);

    	$this->tpl_path = SugarAutoLoader::existingCustomOne('include/SugarFields/Fields/Teamset/TeamsetCollectionEmailView.tpl');
    	//$this->module_dir = $module;
        $this->bean_id = $bean->id;
        $this->form_name = $form_name;
        $this->customMethod = $customMethod;

    	$this->bean = $bean;

    	if(empty($this->bean)) {
    	   echo "Unable to load module {$module}";
    	   return;
    	}

    	//Initialize displayParams
		$this->displayParams['formName'] = $this->form_name;
		$this->displayParams['primaryChecked'] = true;

    	$this->vardef = $field_defs['team_name'];
    	$this->name = $this->vardef['name'];
		$this->related_module = 'Teams';
        $this->value_name = 'team_set_id_values';
        $this->numFields = 1;
        $this->ss = new Sugar_Smarty();
        $this->extra_var = array();
        $this->field_to_name_array = array();
    }

    /*
     * setup
     *
     * Retrieve the related module and load the bean and the relationship
     * call retrieve values()
     */
    function setup()
    {
        $this->related_module = 'Teams';
        $this->value_name = 'team_set_id_values';
        $this->vardef['name'] = $this->name;
        $secondaries = array();
        $primary = false;
        $this->bean->{$this->value_name} = array('role_field' => 'team_name');
        if (!empty($this->bean->team_set_id)) {
            $selectedTeamIds = array();
            if (!empty($this->bean->acl_team_set_id)) {
                $selectedTeamIds = array_map(function ($el) {
                    return $el['id'];
                }, TeamSetManager::getTeamsFromSet($this->bean->acl_team_set_id));
            }
            $teams = TeamSetManager::getTeamsFromSet($this->bean->team_set_id);
            foreach ($teams as $row) {
                if (empty($primary) && $this->bean->team_id == $row['id']) {
                    $this->bean->{$this->value_name} = array_merge(
                        $this->bean->{$this->value_name},
                        array(
                            'primary' => array(
                                'id' => $row['id'],
                                'name' => $row['display_name'],
                                'selected' => in_array($row['id'], $selectedTeamIds),
                            )
                        )
                    );
                    $primary = true;
                } else {
                    $secondaries['secondaries'][] = array(
                        'id' => $row['id'],
                        'name' => $row['display_name'],
                        'selected' => in_array($row['id'], $selectedTeamIds),
                    );
                }
            } //foreach
        }
        $this->bean->{$this->value_name} = array_merge($this->bean->{$this->value_name}, $secondaries);
        $this->skipModuleQuickSearch = true;
        $this->showSelectButton = false;
    }


    /**
     * display
     * Display the widget
     */
    function get_code($includeMassUpdateField = FALSE) {
		$this->setup();
        $this->ss->assign('displayParams', $this->displayParams);
        $this->ss->assign('vardef',$this->vardef);
        $this->ss->assign('module',$this->related_module);

        if(!empty($this->bean)){
      	   $this->ss->assign('values', $this->bean->{$this->value_name});
        }
        $this->ss->assign('includeMassUpdateField',$includeMassUpdateField);
        $this->ss->assign('hideShowHideButton',$this->hideShowHideButton);
        $this->ss->assign('showSelectButton', $this->showSelectButton);
        $this->ss->assign('APP', $GLOBALS['app_strings']);
        $this->ss->assign('isTBAEnabled', TeamBasedACLConfigurator::isAccessibleForModule($this->bean->module_dir));
        $this->ss->assign('CUSTOM_METHOD', $this->customMethod);
        $this->ss->assign("USER_ID", (!empty($this->user_id) ? $this->user_id : ''));
        $this->ss->assign('quickSearchCode', $this->createQuickSearchCode());
		$this->process();
		return $this->display();
    }

	function getClassicViewQS() {
		return $this->createQuickSearchCode();
	}


    /*
     * Create the quickSearch code for the collection field.
     * return the javascript code which define sqs_objects.
     */
    function createQuickSearchCode($returnAsJavascript=true){
        $sqs_objects = array();
        $qsd = QuickSearchDefaults::getQuickSearchDefaults();
        $qsd->setFormName($this->form_name);
        for($i=0; $i<$this->numFields; $i++) {
            $name1 = "{$this->form_name}_{$this->name}_collection_{$i}";
            $sqs_objects[$name1] = $qsd->getQSParent($this->related_module);
            $sqs_objects[$name1]['populate_list'] = array("{$this->vardef['name']}_collection_{$i}", "id_{$this->vardef['name']}_collection_{$i}");
            $sqs_objects[$name1]['field_list'] = array('name', 'id');

            if(!empty($this->user_id)) {
 				$sqs_objects[$name1]['conditions'][] = array('name'=>'user_id', 'value'=>$this->user_id);
            }

            if(!empty($this->customMethod)) {
                $sqs_objects[$name1]['method'] = $this->customMethod;
        	}
        }

        $id = "{$this->form_name}_{$this->name}_collection_0";

        if(!empty($sqs_objects) && count($sqs_objects) > 0) {
            foreach($sqs_objects[$id]['field_list'] as $k=>$v){
                $this->field_to_name_array[$v] = $sqs_objects[$id]['populate_list'][$k];
            }
            if($returnAsJavascript){
	            $quicksearch_js = '<script language="javascript">';
	            $quicksearch_js.= "if(typeof sqs_objects == 'undefined'){var sqs_objects = new Array;}";

	            foreach($sqs_objects as $sqsfield=>$sqsfieldArray){
	               $quicksearch_js .= "sqs_objects['$sqsfield']={$this->json->encode($sqsfieldArray)};";
	            }

	            return $quicksearch_js .= '</script>';
            }else{
            	return $sqs_objects;
            }
       }
       return '';
    }


    function process() {
        $this->process_editview();
    }

}

