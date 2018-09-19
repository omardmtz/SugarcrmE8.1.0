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
  * A Facade to ListView and ListViewSmarty
  */
 class ListViewFacade{

 	var $focus = null;
 	var $module = '';
 	var $type = 0;

 	var $lv;

 	//ListView fields
 	var $template;
 	var $title;
 	var $where = '';
 	var $params = array();
 	var $offset = 0;
 	var $limit = -1;
 	var $filter_fields = array();
 	var $id_field = 'id';
 	var $prefix = '';
 	var $mod_strings = array();

 	/**
 	 * Constructor
 	 * @param $focus - the bean
 	 * @param $module - the module name
 	 * @param - 0 = decide for me, 1 = ListView.html, 2 = ListViewSmarty
 	 */
    public function __construct($focus, $module, $type = 0)
    {
 		$this->focus = $focus;
 		$this->module = $module;
 		$this->type = $type;
 		$this->build();
 	}

 	function build(){
 		//we will assume that if the ListView.html file exists we will want to use that one
        if (file_exists('modules/'.$this->module.'/ListView.html')) {
 			$this->type = 1;
 			$this->lv = new ListView();
 			$this->template = 'modules/'.$this->module.'/ListView.html';
 		} else {
 		    $metadataFile = SugarAutoLoader::loadWithMetafiles($this->module, 'listviewdefs');
            if($metadataFile) {
 		        require $metadataFile;
            }

			SugarACL::listFilter($this->module, $listViewDefs[ $this->module], array("owner_override" => true));

			$this->lv = new ListViewSmarty();
			$displayColumns = array();
			if(!empty($_REQUEST['displayColumns'])) {
			    foreach(explode('|', $_REQUEST['displayColumns']) as $num => $col) {
			        if(!empty($listViewDefs[$this->module][$col]))
			            $displayColumns[$col] = $listViewDefs[$this->module][$col];
			    }
			}
			else {
                if (isset($listViewDefs[$this->module])) {
                    foreach ($listViewDefs[$this->module] as $col => $params) {
                        if (!empty($params['default']) && $params['default']) {
                            $displayColumns[$col] = $params;
                        }
                    }
                }
			}
			$this->lv->displayColumns = $displayColumns;
			$this->type = 2;
			$this->template = 'include/ListView/ListViewGeneric.tpl';
 		}
 	}

 	function setup($template = '', $where = '', $params = array(), $mod_strings = array(), $offset = 0, $limit = -1, $orderBy = '', $prefix = '', $filter_fields = array(), $id_field = 'id'){
 		if(!empty($template))
 			$this->template = $template;

 		$this->mod_strings = $mod_strings;

 		if($this->type == 1){
 			$this->lv->initNewXTemplate($this->template,$this->mod_strings);
 			$this->prefix = $prefix;
 			$this->lv->setQuery($where, $limit, $orderBy, $prefix);
 			$this->lv->show_select_menu = false;
			$this->lv->show_export_button = false;
			$this->lv->show_delete_button = false;
			$this->lv->show_mass_update = false;
			$this->lv->show_mass_update_form = false;
 		}else{
 			$this->lv->export = false;
			$this->lv->delete = false;
			$this->lv->select = false;
			$this->lv->mailMerge = false;
			$this->lv->multiSelect = false;
 			$this->lv->setup($this->focus, $this->template, $where, $params, $offset, $limit,  $filter_fields, $id_field);

 		}
 	}

 	function display($title = '', $section = 'main', $return = FALSE){
 		if($this->type == 1){
            ob_start();
 			$this->lv->setHeaderTitle($title);
 			$this->lv->processListView($this->focus, $section, $this->prefix);
            $output = ob_get_contents();
            ob_end_clean();
 		}else{
             $output = get_form_header($title, '', false) . $this->lv->display();
 		}
        if($return)
            return $output;
        else
            echo $output;
 	}

	function setTitle($title = ''){
		$this->title = $title;
	}
 }

