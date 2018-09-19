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

class ConnectorsController extends SugarController {

	protected $action_remap = array('index'=>'ConnectorSettings');

	var $admin_actions = array('ConnectorSettings', 'DisplayProperties', 'MappingProperties', 'ModifyMapping', 'ModifyDisplay', 'ModifyProperties',
	                           'ModifySearch', 'SearchProperties', 'SourceProperties',
	                           'SavedModifyDisplay', 'SaveModifyProperties', 'SaveModifySearch', 'RunTest');


	function process() {
		if(!is_admin($GLOBALS['current_user']) && in_array($this->action, $this->admin_actions)) {
			$this->hasAccess = false;
		}
		parent::process();
	}


	/**
	 * When the user clicks the Search button, the form is posted back here and this action sets the
	 * search parameters in the session.  Once this call returns, the tabs will then call RetrieveSource to load
	 * the data that was saved in the session.
	 *
	 */
	function action_SetSearch(){
		if(empty($_REQUEST)) {
		   return;
		}

		$this->view = 'ajax';
		require_once('include/connectors/utils/ConnectorUtils.php');
        $searchdefs = ConnectorUtils::getSearchDefs();
		$merge_module = $_REQUEST['merge_module'];
		$record_id = $_REQUEST['record'];
		$searchDefs = isset($searchdefs) ? $searchdefs : array();
		unset($_SESSION['searchDefs'][$merge_module][$record_id]);
		$sMap = array();

		$search_source = $this->request->getValidInputRequest('source_id', 'Assert\ComponentName');
		$source_instance = ConnectorFactory::getInstance($search_source);
		$source_map = $source_instance->getModuleMapping($merge_module);
		$module_fields = array();
		foreach($_REQUEST as $search_term => $val){
			if(!empty($source_map[$search_term])){
				$module_fields[$source_map[$search_term]] = $val;
			}
		}

		foreach($module_fields as $search_term => $val){
			foreach($searchDefs as $source => $modules){
				if(empty($sMap[$source])){
					$instance = ConnectorFactory::getInstance($source);
					$sMap[$source] = array_flip($instance->getModuleMapping($merge_module));
				}

				if(!empty($sMap[$source][$search_term])){
					$source_key = $sMap[$source][$search_term];
					$_SESSION['searchDefs'][$merge_module][$record_id][$source][$source_key] = $val;
	        	}
	        }
		}
	}

	/**
	 * This action it meant to handle the hover action on the listview.
	 *
	 */
	function action_RetrieveSourceDetails() {
		$this->view = 'ajax';
		$source_id = $this->request->getValidInputRequest('source_id', 'Assert\ComponentName');
        $record_id = $this->request->getValidInputRequest('record_id', 'Assert\Guid');

        if(empty($source_id) || empty($record_id)) {
           //display error here
           return;
        }
    	$source = ConnectorFactory::getInstance($source_id);
    	$module = $_SESSION['merge_module'];

		$result = $source->fillBean(array('id' => $record_id), $module);
		require_once('include/connectors/utils/ConnectorUtils.php');
        $connector_strings = ConnectorUtils::getConnectorStrings($source_id);

        $fields = $source->getModuleMapping($module);
		$fieldDefs = $source->getFieldDefs();
		$str = '';

		foreach($fields as $key=>$field){

			$label = $field;
			if(isset($fieldDefs[$key])) {
			   $label = isset($connector_strings[$fieldDefs[$key]['vname']]) ? $connector_strings[$fieldDefs[$key]['vname']] : $label;
			}

			$val = $result->$field;
			if(!empty($val)){
			   if(strlen($val) > 50) {
			   	  $val = substr($val, 0, 47) . '...';
			   }
			   $str .= $label . ': ' .  $val.'<br/>';
			}
		}
		global $theme;
		$json = getJSONobj();
		$retArray = array();

		$retArray['body'] = !empty($str) ? str_replace(array("\rn", "\r", "\n"), array('','','<br />'), $str) : $GLOBALS['mod_strings']['ERROR_NO_ADDITIONAL_DETAIL'];
		$retArray['caption'] = "<div style='float:left'>{$GLOBALS['app_strings']['LBL_ADDITIONAL_DETAILS']}</div>";
	    $retArray['width'] = (empty($results['width']) ? '300' : $results['width']);
	    $retArray['theme'] = $theme;
	    header("Content-Type: application/json");
	    echo $json->encode($retArray);
	}


	function action_GetSearchForm(){
        $this->view = 'ajax';
		if(!empty($_REQUEST['source_id'])){
			//get the search fields and return the search form

			$ss = new Sugar_Smarty();
		    require_once('include/connectors/utils/ConnectorUtils.php');
            $searchdefs = ConnectorUtils::getSearchDefs();
			$merge_module = $this->request->getValidInputRequest('merge_module', 'Assert\Mvc\ModuleName', '');
			$seed = BeanFactory::newBean($merge_module);
			$_searchDefs = isset($searchdefs) ? $searchdefs : array();
			$_trueFields = array();
			$source = $this->request->getValidInputRequest('source_id', 'Assert\ComponentName');

			$searchLabels = ConnectorUtils::getConnectorStrings($source);
			$record =  $this->request->getValidInputRequest('record', 'Assert\Guid');
			$sourceObj = SourceFactory::getSource($source);
			$field_defs = $sourceObj->getFieldDefs();

	 	    if(!empty($_searchDefs[$source][$merge_module])) {
				foreach($_searchDefs[$source][$merge_module] as $key) {
					if(!empty($_SESSION['searchDefs'][$merge_module][$record][$source][$key])){
						$_trueFields[$key]['value'] = $_SESSION['searchDefs'][$merge_module][$record][$source][$key];
					}else{
						$_trueFields[$key]['value'] = '';
					}
					if(!empty($field_defs[$key]) && isset($searchLabels[$field_defs[$key]['vname']])){
						$_trueFields[$key]['label'] = $searchLabels[$field_defs[$key]['vname']];
					}else{
						$_trueFields[$key]['label'] = $key;
					}
				  }//foreach
			}//fi

			$ss->assign('mod', $GLOBALS['mod_strings']);
			$ss->assign('search_fields', $_trueFields);
			$ss->assign('source_id', $source);
			$ss->assign('fields', $seed->field_defs);
			$ss->assign('module', $merge_module);
			$ss->assign('RECORD', $record);
			$ss->assign('APP', $GLOBALS['app_strings']);
			$ss->assign('MOD', $GLOBALS['mod_strings']);
			echo $ss->fetch('modules/Connectors/tpls/search_form.tpl');
		}
	}


	function pre_save(){}
	function post_save(){}


	/**
	 * Once the user has completed the merge, save the data that has been merged onto the bean.
	 *
	 */
	function action_save(){
		$ds_record = new ConnectorRecord();
		$ds_record->load_merge_bean($_REQUEST['merge_module'], false, $_REQUEST['record']);
		foreach($ds_record->merge_bean->column_fields as $field){
			if(isset($_POST[$field]))
			{
				$value = $_POST[$field];

				$ds_record->merge_bean->$field = $value;
            } elseif (isset($ds_record->merge_bean->field_defs[$field]['type'])
                && $ds_record->merge_bean->field_defs[$field]['type'] == 'bool') {
				$ds_record->merge_bean->$field = 0;
			}
		}
		foreach($ds_record->merge_bean->additional_column_fields as $field){
			if(isset($_POST[$field]))
			{
				$value = $_POST[$field];
				$ds_record->merge_bean->$field = $value;
			}
		}
		global $check_notify;
		$ds_record->merge_bean->save($check_notify);
		$return_id = $ds_record->merge_bean->id;
		$return_module = $ds_record->merge_module;
		$return_action = 'EditView';
		header("Location: index.php?action=$return_action&module=$return_module&record=$return_id");
	}

    function action_CallConnectorFunc() {
        $this->view = 'ajax';
        $json = getJSONobj();

        if(!empty($_REQUEST['source_id']))
        {
            $source_id = $this->request->getValidInputRequest('source_id', 'Assert\ComponentName');
            $source = SourceFactory::getSource($source_id);

            $method = 'ext_'.$_REQUEST['source_func'];
            if ( method_exists($source,$method) ) {
                echo $json->encode($source->$method($_REQUEST));
            } else {
                echo $json->encode(array('error'=>true,'errorMessage'=>'Could Not Find Function: '.$method.' in class: '.get_class($source)));
            }
        }
        else
        {
            echo $json->encode(array('error'=>true,'errorMessage'=>'Source Id is not specified.'));
        }
    }

	function action_CallRest() {
		$this->view = 'ajax';
        echo '';
	}

	function action_CallSoap() {
	    $this->view = 'ajax';
	    $source_id = $this->request->getValidInputRequest('source_id', 'Assert\ComponentName');
	    $module = $this->request->getValidInputRequest('module_id', 'Assert\Mvc\ModuleName');
	    $return_params = explode(',', $_REQUEST['fields']);
	    $component = ConnectorFactory::getInstance($source_id);
	    $beans = $component->fillBeans($_REQUEST, $module);
		if(!empty($beans) && !empty($return_params)) {
		    $results = array();
			$count = 0;
			foreach($beans as $bean) {
				foreach($return_params as $field) {
					$results[$count][$field] = $bean->$field;
				}
				$count++;
			}
		    $json = getJSONobj();
		    echo $json->encode($results);
	    } else {
	        echo '';
	    }
	}


	function action_DefaultSoapPopup() {
		$this->view = 'ajax';
	    $source_id = $this->request->getValidInputRequest('source_id', 'Assert\ComponentName'); 
	    $module = $this->request->getValidInputRequest('module_id', 'Assert\Mvc\ModuleName');
	    $id = $this->request->getValidInputRequest('record_id', 'Assert\Guid'); 
	    $mapping = $_REQUEST['mapping'];

	    $mapping = explode(',', $mapping);
	    //Error checking

	    //Load bean
	    $bean = BeanFactory::getBean($module, $id);

	    $component = ConnectorFactory::getInstance($source_id);
	    //Create arguments
	    $args = array();
	    $field_defs = $bean->getFieldDefinitions();
	    foreach($field_defs as $id=>$field) {
	    	    if(!empty($bean->$id)) {
	    	       $args[$id] = $bean->$id;
	    	    }
	    }

	    $beans = $component->fillBeans($args, $module);
		if(!empty($beans) && !empty($mapping)) {
		    $results = array();
			$count = 0;
			foreach($beans as $bean) {
				foreach($mapping as $field) {
					$results[$count][$field] = $bean->$field;
				}
				$count++;
			}
		    $json = getJSONobj();
		    echo $json->encode($results);
	    } else {
	    	$GLOBALS['log']->error($GLOBALS['app_strings']['ERR_MISSING_MAPPING_ENTRY_FORM_MODULE']);
	        echo '';
	    }
	}

	function action_SaveModifyProperties() {
		$sources = array();
		$properties = array();
		foreach($_REQUEST as $name=>$value) {
		        if(preg_match("/^source[0-9]+$/", $name, $matches)) {
	                $source_id = $value;
	                $properties = array();
			        foreach($_REQUEST as $arg=>$val) {
				        if(preg_match("/^{$source_id}_(.*?)$/", $arg, $matches2)) {
				           $properties[$matches2[1]] = $val;
				    	}
					}

					if (!empty($properties)) {
					    $source = SourceFactory::getSource($source_id);
					    $source->setProperties($properties);
					    $source->saveConfig();
					}
		    	}
		}

		require_once('include/connectors/utils/ConnectorUtils.php');
		ConnectorUtils::updateMetaDataFiles();

        // refresh connector
        $cm = new ConnectorManager();
        $connectors = $cm->buildConnectorsMeta();


	    // BEGIN SUGAR INT
	    if(empty($_REQUEST['from_unit_test'])) {
	    // END SUGAR INT
   	    header("Location: index.php?action=ConnectorSettings&module=Connectors");
	    // BEGIN SUGAR INT
	    }
	    // END SUGAR INT
	}

	function action_SaveModifyDisplay() {
			if(empty($_REQUEST['display_sources'])) {
			   return;
			}

			require_once('include/connectors/utils/ConnectorUtils.php');

			$connectors = ConnectorUtils::getConnectors();
			$connector_keys = array_keys($connectors);

			$modules_sources = ConnectorUtils::getDisplayConfig();
			if ( !is_array($modules_sources) ) {
			    $modules_sources = (array) $modules_sources;
			}

			$sources = array();
			$values = array();
			$new_modules_sources = array();

			if(!empty($_REQUEST['display_values'])) {
				$display_values = explode(',', $_REQUEST['display_values']);
			    foreach($display_values as $value) {
			    	    $entry = explode(':', $value);
                                    $mod = get_module_from_singular($entry[1]); // get the internal module name
                                    $new_modules_sources[$mod][$entry[0]] = $entry[0];
			    }
			}

			//These are the sources that were modified.
			//We only update entries for these sources that have been changed
		    $display_sources = explode(',', $_REQUEST['display_sources']);
		    foreach($display_sources as $source) {
		    	    $sources[$source] = $source;
		    } //foreach

		    $removedModules = array();

            //Unset entries that have all sources removed
	    	foreach($modules_sources as $module=>$source_entries) {
    	 	     foreach($source_entries as $source_id) {
    	 	     	     if(!empty($sources[$source_id]) && empty($new_modules_sources[$module][$source_id])) {
    	 	     	     	unset($modules_sources[$module][$source_id]);
    	 	     	     	$removedModules[$module] = true;
    	 	     	     }
    	 	     }
    	 	}
    	 	$removedModules = array_keys($removedModules);
    	 	foreach($removedModules as $key){
    	 		if(empty($new_modules_sources[$key])){
    	 			ConnectorUtils::cleanMetaDataFile($key);
    	 		}
    	 	}

		    //Update based on new_modules_sources
		    foreach($new_modules_sources as $module=>$enabled_sources) {
		    	 //If the module is not in $modules_sources add it there
		    	 if(empty($modules_sources[$module])) {
		    	 	$modules_sources[$module] = $enabled_sources;
		    	 } else {
		    	 	foreach($enabled_sources as $source_id) {
		    	 		    if(empty($modules_sources[$module][$source_id])) {
		    	 		       $modules_sources[$module][$source_id] = $source_id;
		    	 		    }
		    	 	} //foreach
		    	 }
		    } //foreach

			//Should we just remove entries where all sources are disabled?
		    $unset_modules = array();
		    foreach($modules_sources as $module=>$mapping) {
		    	if(empty($mapping)) {
		    	   $unset_modules[] = $module;
		    	}
		    }

		    foreach($unset_modules as $mod) {
		    	unset($modules_sources[$mod]);
		    }

			if(!write_array_to_file('modules_sources', $modules_sources, CONNECTOR_DISPLAY_CONFIG_FILE)) {
	           //Log error and return empty array
	     	   $GLOBALS['log']->fatal("Cannot write \$modules_sources to " . CONNECTOR_DISPLAY_CONFIG_FILE);
	   	    }

	   	    $sources_modules = array();
	   	    foreach($modules_sources as $module=>$source_entries) {
		    	foreach($source_entries as $id) {
		    		    $sources_modules[$id][$module] = $module;
		    	}
	   	    }


            //Now update the searchdefs and field mapping entries accordingly
            require('modules/Connectors/metadata/searchdefs.php');
            $originalSearchDefs = $searchdefs;
			$connectorSearchDefs = ConnectorUtils::getSearchDefs();

			$searchdefs = array();
            foreach($sources_modules as $source_id=>$modules) {
           	    foreach($modules as $module) {
					$searchdefs[$source_id][$module] = !empty($connectorSearchDefs[$source_id][$module]) ? $connectorSearchDefs[$source_id][$module] : (!empty($originalSearchDefs[$source_id][$module]) ? $originalSearchDefs[$source_id][$module] : array());
           	    }
            }

			//Write the new searchdefs out
		    if(!write_array_to_file('searchdefs', $searchdefs, 'custom/modules/Connectors/metadata/searchdefs.php')) {
		       $GLOBALS['log']->fatal("Cannot write file custom/modules/Connectors/metadata/searchdefs.php");
		    }

		    //Unset the $_SESSION['searchDefs'] variable
		    if (isset($_SESSION['searchDefs'])) {
			    unset($_SESSION['searchDefs']);
		    }



		    //Clear mapping file if needed (this happens when all modules are removed from a source
			foreach($sources as $id) {
		    	    if(empty($sources_modules[$source]) &&  isset($connectors[$id])) {
		    	        //Now write the new mapping entry to the custom folder
					    $dir = $connectors[$id]['directory'];
						if(!preg_match('/^custom\//', $dir)) {
						   $dir = 'custom/' . $dir;
						}

					    if(!file_exists($dir)) {
			       		   mkdir_recursive($dir);
			    		}

                        $fakeMapping = array('beans'=>array());
					    if(!write_array_to_file('mapping', $fakeMapping, "{$dir}/mapping.php")) {
					       $GLOBALS['log']->fatal("Cannot write file {$dir}/mapping.php");
					    }
                        $s = SourceFactory::getSource($id);
                        if (isset($s)) {
                            $s->saveMappingHook($fakeMapping);
                        }
		    	    } //if
		    } //foreach

		    //Now update the field mapping entries
		    foreach($sources_modules as $id=>$modules) {
				    $source = SourceFactory::getSource($id);
				    $mapping = $source->getMapping();
				    $mapped_modules = array_keys($mapping['beans']);

		            foreach($mapped_modules as $module) {
                    	   if(empty($sources_modules[$id][$module])) {
                    	   	  unset($mapping['beans'][$module]);
                    	   }
                    }

                    //Remove modules from the mapping entries
                    foreach($modules as $module) {
							if(empty($mapping['beans'][$module])) {
								$originalMapping = $source->getOriginalMapping();
								if(empty($originalMapping['beans'][$module])) {
								    $defs = $source->getFieldDefs();
								    $keys = array_keys($defs);
								    $new_mapping_entry = array();
								    foreach($keys as $key) {
								    	    $new_mapping_entry[$key] = '';
								    }
								    $mapping['beans'][$module] = $new_mapping_entry;
								} else {
 									$mapping['beans'][$module] = $originalMapping['beans'][$module];
								}
							} //if

                    } //foreach

                    if (!isset( $connectors[$id]['directory'])) continue;
				    //Now write the new mapping entry to the custom folder
				    $dir = $connectors[$id]['directory'];
					if(!preg_match('/^custom\//', $dir)) {
					   $dir = 'custom/' . $dir;
					}

				    if(!file_exists($dir)) {
		       		   mkdir_recursive($dir);
		    		}

				    if(!write_array_to_file('mapping', $mapping, "{$dir}/mapping.php")) {
				       $GLOBALS['log']->fatal("Cannot write file {$dir}/mapping.php");
				    }
                    $source->saveMappingHook($mapping);

		    } //foreach

		    // save eapm configs
		    $this->handleEAPMSettings($connectors, $sources, $_REQUEST);

		    ConnectorUtils::updateMetaDataFiles();


            // refresh connector cache
            $cm = new ConnectorManager();
            $connectors = $cm->buildConnectorsMeta();
		    // BEGIN SUGAR INT
		    if(empty($_REQUEST['from_unit_test'])) {
		    // END SUGAR INT
	   	    header("Location: index.php?action=ConnectorSettings&module=Connectors");
		    // BEGIN SUGAR INT
		    }
		    // END SUGAR INT
	}

    public function handleEAPMSettings($connectors, $sources, $request)
    {
        foreach($connectors as $connector_name => $data) {
            // if we touched it AND it has EAPM data
            if(isset($sources[$connector_name]) && !empty($data["eapm"])) {
                // Grab the old value if it is set
                $oldValue = isset($connectors[$connector_name]["eapm"]["enabled"]) ?
                            $connectors[$connector_name]["eapm"]["enabled"] :
                            null;

                // Set from the request
                $connectors[$connector_name]["eapm"]["enabled"] = !empty($request[$connector_name."_external"]);

                // If there is a difference, save the config. This will
                // trigger a connectors save as well and update all relevent
                // metadata.
                if ($connectors[$connector_name]["eapm"]["enabled"] !== $oldValue) {
                    // Get the source object
                    $s = SourceFactory::getSource($sources[$connector_name]);

                    // Get the existing config
                    $sConfig = $s->getConfig();
                    if (!isset($sConfig['eapm'])) {
                        $sConfig['eapm'] = array();
                    }

                    // Merge what we have
                    $sConfig['eapm'] = array_merge($sConfig['eapm'], $connectors[$connector_name]['eapm']);

                    // Set and save... this will trigger a connector save call
                    $s->setConfig($sConfig);
                    $s->saveConfig();
                }
            }
        }

        return $connectors;
    }

	function action_SaveModifySearch() {
		$search_sources = !empty($_REQUEST['search_sources']) ? explode(',', $_REQUEST['search_sources']) : array();
		$search_values = !empty($_REQUEST['search_values']) ? explode(',', $_REQUEST['search_values']) : array();

		//Build the source->module->fields mapping
		$source_modules_fields = array();
		foreach($search_values as $id) {
			    $parts = explode(':', $id);
			    $source_modules_fields[$parts[0]][$parts[1]][] = $parts[2];
		}

		require_once('include/connectors/utils/ConnectorUtils.php');
		$searchdefs = ConnectorUtils::getSearchDefs();

		//Now update for each source
		foreach($search_sources as $source) {
				$existing_modules = !empty($searchdefs[$source]) ? array_keys($searchdefs[$source]) : array();
			    unset($searchdefs[$source]);

				foreach($existing_modules as $module) {
		       	  if(empty($source_modules_fields[$source][$module])) {
		       	  	 $searchdefs[$source][$module] = array();
		       	  }
		        }

			    if(!empty($source_modules_fields[$source])) {
			       foreach($source_modules_fields[$source] as $module=>$def) {
			       	  $searchdefs[$source][$module] = $def;
			       }
			    }
		}


	    if(!file_exists('custom/modules/Connectors/metadata')) {
	       mkdir_recursive('custom/modules/Connectors/metadata');
	    }

	    if(!write_array_to_file('searchdefs', $searchdefs, 'custom/modules/Connectors/metadata/searchdefs.php')) {
	       $GLOBALS['log']->fatal("Cannot write file custom/modules/Connectors/metadata/searchdefs.php");
	       return array();
	    }

	    if (isset($_SESSION['searchDefs'])) {
		    unset($_SESSION['searchDefs']);
	    }

        // refresh connector cache
        $cm = new ConnectorManager();
        $connectors = $cm->buildConnectorsMeta();

	    // BEGIN SUGAR INT
		if(empty($_REQUEST['from_unit_test'])) {
		// END SUGAR INT
        header("Location: index.php?action=ConnectorSettings&module=Connectors");
	    // BEGIN SUGAR INT
	    }
	    // END SUGAR INT
	}


	/**
	 * action_SaveModifyMapping
	 */
	function action_SaveModifyMapping() {
		$mapping_sources = !empty($_REQUEST['mapping_sources']) ? explode(',', $_REQUEST['mapping_sources']) : array();
		$mapping_values = !empty($_REQUEST['mapping_values']) ? explode(',', $_REQUEST['mapping_values']) : array();

		//Build the source->module->fields mapping
		$source_modules_fields = array();
		foreach($mapping_values as $id) {
			    $parts = explode(':', $id);
			    $key_vals = explode('=', $parts[2]);
			    //Note the strtolwer call... we are lowercasing the key values
			    $source_modules_fields[$parts[0]][$parts[1]][strtolower($key_vals[0])] = $key_vals[1];
		} //foreach

		foreach($mapping_sources as $source_id) {
			    if(empty($source_modules_fields[$source_id])) {
				   $source = SourceFactory::getSource($source_id);
				   $mapping = $source->getMapping();
				   foreach($mapping['beans'] as $module=>$entry) {
			          $source_modules_fields[$source_id][$module] = array();
				   }
			    }
		} //foreach



		if ( isset($_SESSION['searchDefs']) )
		    unset($_SESSION['searchDefs']);


		require_once('include/connectors/utils/ConnectorUtils.php');
		$source_entries = ConnectorUtils::getConnectors();

		foreach($source_modules_fields as $id=>$mapping_entry) {
			    //Insert the id mapping
			    foreach($mapping_entry as $module=>$entry) {
			    	$mapping_entry[$module]['id'] = 'id';
			    }

			    $source = SourceFactory::getSource($id);
			    $mapping = $source->getMapping();
			    $mapping['beans'] = $mapping_entry;

			    //Now write the new mapping entry to the custom folder
			    $dir = $source_entries[$id]['directory'];
				if(!preg_match('/^custom\//', $dir)) {
				   $dir = 'custom/' . $dir;
				}

			    if(!file_exists($dir)) {
	       		   mkdir_recursive($dir);
	    		}

			    if(!write_array_to_file('mapping', $mapping, "{$dir}/mapping.php")) {
			       $GLOBALS['log']->fatal("Cannot write file {$dir}/mapping.php");
			    }
                $source->saveMappingHook($mapping);
		}

		//Rewrite the metadata files
		ConnectorUtils::updateMetaDataFiles();

        // refresh connectors
        $cm = new ConnectorManager();
        $connectors = $cm->buildConnectorsMeta();

	    // BEGIN SUGAR INT
		if(empty($_REQUEST['from_unit_test'])) {
		// END SUGAR INT
        header("Location: index.php?action=ConnectorSettings&module=Connectors");
	    // BEGIN SUGAR INT
		}
		// END SUGAR INT
	}


	function action_RunTest() {
        global $mod_strings;
	    $this->view = 'ajax';
	    $source_id = $this->request->getValidInputRequest('source_id', 'Assert\ComponentName');

        // Get the source object and init it all at once
        $source = SourceFactory::getSource($source_id, true);

        // Build a properties array
        $properties = array();
        foreach($_REQUEST as $name=>$value) {
            if (preg_match("/^{$source_id}_(.*?)$/", $name, $matches)) {
               $properties[$matches[1]] = $value;
            }
        }

        // If there are properties, set them into the source for testing
        if ($properties) {
            $source->setProperties($properties);
        }

        try {
            if ($source->isRequiredConfigFieldsForButtonSet() && $source->test()) {
                echo $mod_strings['LBL_TEST_SOURCE_SUCCESS'];
            } else {
              echo $mod_strings['LBL_TEST_SOURCE_FAILED'];
            }
        } catch (Exception $ex) {
            $GLOBALS['log']->fatal($ex->getMessage());
            echo $ex->getMessage();
        }
    }


	/**
	 * action_RetrieveSources
	 * Returns a JSON encoded format of the Connectors that are configured for the system
	 *
	 */
	function action_RetrieveSources() {
		require_once('include/connectors/utils/ConnectorUtils.php');
		$this->view = 'ajax';
		$sources = ConnectorUtils::getConnectors();
		$results = array();
		foreach($sources as $id=>$entry) {
			    $results[$id] = !empty($entry['name']) ? $entry['name'] : $id;
		}
	    $json = getJSONobj();
	    echo $json->encode($results);
	}

}
?>
