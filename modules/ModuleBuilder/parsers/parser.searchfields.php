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

class ParserSearchFields extends ModuleBuilderParser
{

	var $searchFields;
	var $packageKey; 
	
    public function __construct($moduleName, $packageName = '')
    {
        $this->moduleName = $moduleName;
        if (!empty($packageName))
        {
            $this->packageName = $packageName;
            $mbPackage = new MBPackage($this->packageName);
            $this->packageKey = $mbPackage->key;
        }
        
        $this->searchFields = $this->getSearchFields();
    }
    
    function addSearchField($name, $searchField)
    {
    	if(empty($name) || empty($searchField) || !is_array($searchField))
    	{
    		return;
    	}
    	
    	$key = isset($this->packageKey) ? $this->packageKey . '_' . $this->moduleName : $this->moduleName;
        $this->searchFields[$key][$name] = $searchField;
    }
    
    function removeSearchField($name) 
    {

    	$key = isset($this->packageKey) ? $this->packageKey . '_' . $this->moduleName : $this->moduleName;

    	if(isset($this->searchFields[$key][$name]))
    	{
    		unset($this->searchFields[$key][$name]);
    	}
    }
    
    function getSearchFields()
    {
    	$searchFields = array();
        if (!empty($this->packageName) && file_exists("custom/modulebuilder/packages/{$this->packageName}/modules/{$this->moduleName}/metadata/SearchFields.php")) //we are in Module builder
        {
			include("custom/modulebuilder/packages/{$this->packageName}/modules/{$this->moduleName}/metadata/SearchFields.php");      	        	
        } else if(file_exists("custom/modules/{$this->moduleName}/metadata/SearchFields.php")) {
			include("custom/modules/{$this->moduleName}/metadata/SearchFields.php");      	        	
        } else if(file_exists("modules/{$this->moduleName}/metadata/SearchFields.php")) {
			include("modules/{$this->moduleName}/metadata/SearchFields.php");      	        	        	
        }
        
        return $searchFields;
    }
    
    function saveSearchFields ($searchFields)
    {
        if (!empty($this->packageName)) //we are in Module builder
        {
			$header = file_get_contents('modules/ModuleBuilder/MB/header.php');
            if(!file_exists("custom/modulebuilder/packages/{$this->packageName}/modules/{$this->moduleName}/metadata/SearchFields.php"))
            {
               mkdir_recursive("custom/modulebuilder/packages/{$this->packageName}/modules/{$this->moduleName}/metadata");
            }
			write_array_to_file("searchFields['{$this->packageKey}_{$this->moduleName}']", $searchFields["{$this->packageKey}_{$this->moduleName}"], "custom/modulebuilder/packages/{$this->packageName}/modules/{$this->moduleName}/metadata/SearchFields.php", 'w', $header);                	        	
        } else {
			$header = file_get_contents('modules/ModuleBuilder/MB/header.php');
            if(!file_exists("custom/modules/{$this->moduleName}/metadata/SearchFields.php"))
            {
               mkdir_recursive("custom/modules/{$this->moduleName}/metadata");
            }			
			write_array_to_file("searchFields['{$this->moduleName}']", $searchFields[$this->moduleName], "custom/modules/{$this->moduleName}/metadata/SearchFields.php", 'w', $header);                	        	
        }
        $this->searchFields = $searchFields;
    }
    


}

