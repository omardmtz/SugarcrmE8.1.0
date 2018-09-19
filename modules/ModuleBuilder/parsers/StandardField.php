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


class StandardField extends DynamicField
{
	var $custom_def = array();
	var $base_def = array();
	var $baseField;
	

    public function __construct($module = '') {
        parent::__construct($module);
    }
    
    protected function loadCustomDef($field){
    	global $beanList;
    	if (!empty($beanList[$this->module]) && is_file("custom/Extension/modules/{$this->module}/Ext/Vardefs/sugarfield_$field.php"))
    	{
            $bean_name = get_valid_bean_name($this->module);
            $dictionary = array($bean_name => array("fields" => array($field => array())));
            include FileLoader::validateFilePath("$this->base_path/sugarfield_$field.php");
            if (!empty($dictionary[$bean_name]) && isset($dictionary[$bean_name]["fields"][$field]))
                $this->custom_def = $dictionary[$bean_name]["fields"][$field];
    	}
    }

    protected function loadBaseDef($field){
        global $beanList;
        if (!empty($beanList[$this->module]) && is_file("modules/{$this->module}/vardefs.php"))
        {
            $dictionary = array();
            include FileLoader::validateFilePath("modules/{$this->module}/vardefs.php");
            if (!empty($dictionary[$beanList[$this->module]]) && isset($dictionary[$beanList[$this->module]]["fields"][$field]))
                $this->base_def = $dictionary[$beanList[$this->module]]["fields"][$field];
        }
    }
    
    /**
     * Adds a custom field using a field object
     *
     * @param Field Object $field
     * @return boolean
     */
    function addFieldObject(&$field){
        global $dictionary, $beanList;
        
        
        if (empty($beanList[$this->module]))
            return false;

        $bean_name = get_valid_bean_name($this->module);

        if (empty($dictionary[$bean_name]) || empty($dictionary[$bean_name]["fields"][$field->name]))
            return false;

        $currdef = $dictionary[$bean_name]["fields"][$field->name];

        // set $field->unified_search=true if field supports unified search
        // regarding #51427
        if($field->supports_unified_search)
        {
            if(isset($dictionary[$bean_name]['unified_search_default_enabled']) && isset($dictionary[$bean_name]['unified_search'])
            && $dictionary[$bean_name]['unified_search_default_enabled'] && $dictionary[$bean_name]['unified_search'])
            {
                $currdef['unified_search'] = $field->unified_search = isset($currdef['unified_search'])
                 ? $currdef['unified_search']
                 : true;
            }
        }
        // end #51427

        $this->loadCustomDef($field->name);
        $this->loadBaseDef($field->name);
        $newDef = $field->get_field_def();
        
        require_once ('modules/DynamicFields/FieldCases.php') ;
        $this->baseField = get_widget ( $field->type) ;
        foreach ($field->vardef_map as $property => $fmd_col){
           
        	if ($property == "action" || $property == "label_value" || $property == "label"
            	|| ((substr($property, 0,3) == 'ext' && strlen($property) == 4))
            ) 
            	continue;

            // Bug 37043 - Avoid writing out vardef defintions that are the default value.
            if (isset($newDef[$property]) &&
            	((!isset($currdef[$property]) && !$this->isDefaultValue($property,$newDef[$property], $this->baseField))
            		|| (isset($currdef[$property]) && $currdef[$property] !== $newDef[$property])
            	)
            ){
            	$this->custom_def[$property] =
                    is_string($newDef[$property]) ? htmlspecialchars_decode($newDef[$property], ENT_QUOTES) : $newDef[$property];
            }
            
            //Remove any orphaned entries
            if (isset($this->custom_def[$property]) && !isset($newDef[$property]))
            	unset($this->custom_def[$property]);

            //Handle overrides of out of the box definitions with empty
            if (!empty($this->base_def[$property]) && !isset($newDef[$property]))
            {
                //Switch on type of the property to find what the correct 'empty' is.
                if(is_string($this->base_def[$property]))
                    $this->custom_def[$property] = "";
                else if(is_array($this->base_def[$property]))
                    $this->custom_def[$property] = array();
                else if(is_bool($this->base_def[$property]))
                    $this->custom_def[$property] = false;
                else
                    $this->custom_def[$property] = null;
            }
        }
        
        if (isset($this->custom_def["duplicate_merge_dom_value"]) && !isset($this->custom_def["duplicate_merge"]))
        	unset($this->custom_def["duplicate_merge_dom_value"]);
        
        $this->writeVardefExtension($bean_name, $field, $this->custom_def);
    }
    
    
}
