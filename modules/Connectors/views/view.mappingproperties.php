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


class ViewMappingProperties extends ViewList
{   
 	/**
	 * @see SugarView::process()
	 */
    public function process($params = array())
	{
 		$this->options['show_all'] = false;
 		$this->options['show_javascript'] = true;
 		$this->options['show_footer'] = false;
 		$this->options['show_header'] = false;
        parent::process($params);
 	}
 	
    /**
	 * @see SugarView::display()
	 */
	public function display() 
	{
        require_once('include/connectors/utils/ConnectorUtils.php');
		$connector_strings = ConnectorUtils::getConnectorStrings($_REQUEST['source_id']);
        $sources = ConnectorUtils::getConnectors();
        $source_id = $this->request->getValidInputRequest('source_id', 'Assert\ComponentName');

        $source = SourceFactory::getSource($source_id);
        $is_enabled = ConnectorUtils::isSourceEnabled($source_id);
		$script = '';
		$display_data = array();
		if($is_enabled) {
	        $mapping = $source->getMapping();
	        $source_defs = $source->getFieldDefs();
	    
	    	//Create the Javascript code to dynamically add the tables
	    	$json = getJSONobj();
	    	foreach($mapping['beans'] as $module=>$field_mapping) {
	            
	    		$mod_strings = return_module_language($GLOBALS['current_language'], $module);
	    		$bean = BeanFactory::newBean($module);
	    		if ( !is_object($bean) )
	    		    continue;
	    		$field_defs = $bean->getFieldDefinitions();
	    		$available_fields = array();
	
	    		$labels = array();
			    $duplicate_labels = array();
	    		foreach($field_defs as $id=>$def) {
	    			
	    			//We are filtering out some fields here
	    			if($def['type'] == 'relate' || $def['type'] == 'link' || (isset($def['dbType']) && $def['dbType'] == 'id')) {
	    			   continue;
	    			}
	    			   

	    			if($def['name'] == 'team_set_id') {
	    			   continue;
	    			}
	    			
	    			if(isset($def['vname'])) {
	    			   $available_fields[$id] = !empty($mod_strings[$def['vname']]) ? $mod_strings[$def['vname']] : $id;
	    			} else {
	    			   $available_fields[$id] = $id;
	    			}
	    			
	    		    //Remove the ':' character in some labels
	    			if(preg_match('/\:$/', $available_fields[$id])) {
	    		       $available_fields[$id] = substr($available_fields[$id], 0, strlen($available_fields[$id])-1);
	    		    }
	    		    
	    		    if(isset($labels[$available_fields[$id]])) {
	    		      $duplicate_labels[$labels[$available_fields[$id]]] = $labels[$available_fields[$id]];
	    		      $duplicate_labels[$id] = $id;
	    		    } else {
	    		      $labels[$available_fields[$id]] = $id;
	    		    }    		    
	    		}
	
	    		foreach($duplicate_labels as $id) {
			   	    $available_fields[$id] = $available_fields[$id] . " ({$id})";
			    }
			    
			    asort($available_fields);
			    
	            $field_keys = array();
	    		$field_values = array();
	    		
	    		$source_fields = array();
	    		foreach($field_mapping as $id=>$field) {
	    			    if(!empty($source_defs[$id])) {
	    			       $source_fields[$id] = $source_defs[$id];
	    			    }
	    		}
	    		$source_fields = array_merge($source_fields, $source_defs);
	    		
	    		foreach($source_fields as $id=>$def) {
	    			if(empty($def['hidden'])){
	    				$field_keys[strtolower($id)] = !empty($connector_strings[$source_fields[$id]['vname']]) ? $connector_strings[$source_fields[$id]['vname']] : $id;
	    				$field_values[] = !empty($field_mapping[strtolower($id)]) ? $field_mapping[strtolower($id)] : '';
	    			}
	    		}

	    		$display_data[$module] = array('field_keys' => $field_keys,
	    		                               'field_values' => $field_values,
	    		                               'available_fields' => $available_fields,
	    		                               'field_mapping' => $field_mapping,
                                               'module_name' => isset($GLOBALS['app_list_strings']['moduleList'][$module]) ? $GLOBALS['app_list_strings']['moduleList'][$module] : $module
                                                );
	    	}
		}

		$this->ss->assign('display_data', $display_data);
    	$this->ss->assign('empty_mapping', empty($display_data) ? true : false);
    	$this->ss->assign('dynamic_script', $script);  	
    	$this->ss->assign('sources', $sources);
    	$this->ss->assign('mod', $GLOBALS['mod_strings']);
    	$this->ss->assign('APP', $GLOBALS['app_strings']);
    	$this->ss->assign('source_id', $source_id);
    	$this->ss->assign('source_name', $sources[$source_id]['name']);
    	$this->ss->assign('theme', $GLOBALS['theme']);
		
    	echo $this->ss->fetch($this->getCustomFilePathIfExists('modules/Connectors/tpls/mapping_properties.tpl'));
    }
}
