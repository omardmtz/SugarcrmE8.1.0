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

use Sugarcrm\Sugarcrm\Util\Files\FileLoader;

class MBVardefs{
	var $templates = array();
	var $iTemplates = array();
	var $vardefs = array();
	var $vardef = array();
	var $path = '';
	var $name = '';
	var $errors = array();

    public function __construct($name, $path, $key_name)
    {
		$this->path = $path;
		$this->name = $name;
		$this->key_name = $key_name;
		$this->load();
	}

	function loadTemplate($by_group, $template, $file){
		$module = $this->name;
		$table_name = $this->name;
		$object_name = $this->key_name;
		$_object_name = strtolower($this->key_name);

		// required by the vardef template for team security in SugarObjects
		$table_name = strtolower($module);

		if(file_exists($file)){
			include($file);
            if (isset($vardefs))
            {
                if($by_group){
                    $this->vardefs['fields'] [$template]= $vardefs['fields'];
                }else{
                    $this->vardefs['fields']= array_merge($this->vardefs['fields'], $vardefs['fields']);
                    if(!empty($vardefs['relationships'])){
                        $this->vardefs['relationships']= array_merge($this->vardefs['relationships'], $vardefs['relationships']);
                    }
                }

                // Handle vardefs that use other implementation vardefs
                if (isset($vardefs['uses'])) {
                    // This *should* be the case all the time
                    if (is_array($vardefs['uses'])) {
                        foreach ($vardefs['uses'] as $use) {
                            $useFile = MB_IMPLEMENTS . '/' . $use . '/vardefs.php';
                            $this->loadTemplate($by_group, $use, $useFile);
                        }
                    } else {
                        // Uses should never really be a string, but you never know
                        $useFile = MB_IMPLEMENTS . '/' . $vardefs['uses'] . '/vardefs.php';
                        $this->loadTemplate($by_group, $vardefs['uses'], $useFile);
                    }
                }
            }
		}
        //Bug40450 - Extra 'Name' field in a File type module in module builder
        if(array_key_exists('file', $this->templates))
        {
            unset($this->vardefs['fields']['name']);
            unset($this->vardefs['fields']['file']['name']);
        }

	}

    /**
     * Merges various vardefs from implementation and template types
     * 
     * @param boolean $by_group Whether to group the defs
     */
    function mergeVardefs($by_group = false) {
        $this->vardefs = array(
            'fields' => array(),
            'relationships' => array(),
        );

        $module_name = $this->name;

        // Handle implementations (assignable, team_security, etc)
        foreach ($this->iTemplates as $template => $val) {
            $file = MB_IMPLEMENTS . '/' . $template . '/vardefs.php';
            $this->loadTemplate($by_group,$template, $file);
        }

        // Always make sure that basic is added in, even if it's not, and that it's
        // the first type in the list
        $templates = $this->templates;
        if (!isset($templates['basic'])) {
            array_unshift($templates, array('basic' => 1));
        }

        // Handle the template types
        $objType = 'basic';
        foreach ($templates as $template => $val) {
            $file = MB_TEMPLATES . '/' . $template . '/vardefs.php';
            $this->loadTemplate($by_group,$template, $file);

            // Keep track of the template type so we have it for later
            $objType = $template;
        }

        if ($by_group) {
            // If the name of the module is the same as the object type, this wipes out its fields
            if ($this->name != $objType) {
                $this->vardefs['fields'][$this->name] = $this->vardef['fields'];
            } else {
                // If the module name IS the same as the type, and vardef is not empty, merge it
                if (!empty($this->vardef['fields'])) {
                    $this->vardefs['fields'][$this->name] = array_merge($this->vardefs['fields'][$this->name], $this->vardef['fields']);
                }
            }
        } else {
           $this->vardefs['fields'] = array_merge($this->vardefs['fields'], $this->vardef['fields']);
        }
    }

	function updateVardefs($by_group=false){
		$this->mergeVardefs($by_group);
	}


	function getVardefs(){
		return $this->vardefs;
	}

	function getVardef(){
		return $this->vardef;
	}

	/**
	 * Ensure the vardef name is OK for database
	 * @param string $name
	 * @return string
	 */
	protected function validateVardefName($name)
	{
	    $name = $GLOBALS['db']->getValidDBName($name, true, 'column');
	    if($GLOBALS['db']->isReservedWord($name)) {
	        $name = $name."_field";
	    }
	    return $GLOBALS['db']->getValidDBName($name, true, 'column');
	}

    function addFieldVardef($vardef)
    {
        if (!isset($vardef['default'])) {
            unset($vardef['default']);
        }
        if (empty($this->vardef['fields'][$vardef['name']]) && empty($this->vardefs['fields'][$vardef['name']])) {
            // clean up names for new fields
            $vardef['name'] = $this->validateVardefName($vardef['name']);
        }
        $this->vardef['fields'][$vardef['name']] = $vardef;
    }

	function deleteField($field){
		unset($this->vardef['fields'][$field->name]);
	}

	function save(){
		$header = file_get_contents('modules/ModuleBuilder/MB/header.php');
		write_array_to_file('vardefs', $this->vardef, $this->path . '/vardefs.php','w', $header);
	}

	function build($path){
		$header = file_get_contents('modules/ModuleBuilder/MB/header.php');
		write_array_to_file('dictionary["' . $this->name . '"]', $this->getVardefs(), $path . '/vardefs.php', 'w', $header);
	}

    public function load()
    {
        $this->vardef = array('fields'=>array(), 'relationships'=>array());
        $vardefFile = $this->path . '/vardefs.php';
        if (file_exists($vardefFile)) {
            $this->vardef = FileLoader::varFromInclude($vardefFile, 'vardefs');
        }
    }
}
