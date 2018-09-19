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

use Sugarcrm\Sugarcrm\Security\Crypto\Blowfish;

/**
 * source is the parent class of any source object.
 * @api
 */
abstract class source{
	/**
	 * The name of an wrapper to use if the class wants to provide an override
	 */
	public $wrapperName;
	protected $_config;
	protected $_mapping;
	protected $_field_defs;

    /**
     * @var bool enable_in_wizard Boolean value marking whether or not the connector may appear in the wizard (Get Data) views
     */
    protected $_enable_in_wizard = true;

    /**
     * @var bool enable_in_hover Boolean value marking whether or not a hover link could be applied to the connector
     */
	protected $_enable_in_hover = false;

    /**
     * @var bool enable_in_admin_mapping Boolean value marking whether or not this connector should be shown in the Modify Mapping view
     */
    protected $_enable_in_admin_mapping = true;

    /**
     * @var bool enable_in_admin_properties Boolean value marking whether or not this connector should appear in the Set Connector Properties view
     */
    protected $_enable_in_admin_properties = true;

    /**
     * @var bool enable_in_admin_display Boolean value marking whether or not this connector should appear in the Enable Connectors view
     */
    protected $_enable_in_admin_display = true;

    /**
     * @var bool enable_in_admin_search Boolean value marking whether or not this connector should appear in the Manage Connector Search view
     */
    protected $_enable_in_admin_search = true;

    /**
     * @var bool has_testing_enabled Boolean value marking whether or not the connector should display the test button in administration view
     */
	protected $_has_testing_enabled = false;

	protected $_required_config_fields = array();
	protected $_required_config_fields_for_button = array();
	protected $config_decrypted = false;

    /**
     * The ExternalAPI Base that instantiated this connector.
     * @var _eapm
     */
    protected $_eapm = null;

	public function __construct(){
		$this->loadConfig();
		$this->loadMapping();
		$this->loadVardefs();
 	}

 	public function init(){}

 	//////// CALLED FROM component.php ///////
	public function loadMapping() {
 		$mapping = array();
 		$dir = str_replace('_','/',get_class($this));
 		$map = SugarAutoLoader::existingCustomOne("modules/Connectors/connectors/sources/{$dir}/mapping.php");
 		if(!empty($map)) {
 		    require $map;
 		}
	    $this->_mapping = $mapping;
 	}

    public function saveMappingHook($mapping) {
        // Most classes don't care that the mapping has changed, but this is here if they do.
        return;
    }

    /**
     * Load source's vardef file
     */
 	public function loadVardefs() {
		$dir = str_replace('_','/',$class = get_class($this));
		$defs = SugarAutoLoader::existingCustomOne("modules/Connectors/connectors/sources/{$dir}/vardefs.php");
		if(!empty($defs)) {
			require $defs;
		}

		$this->_field_defs = !empty($dictionary[$class]['fields']) ? $dictionary[$class]['fields'] : array();
 	}

 	/**
 	 * Given a parameter in a vardef field, return the list of fields that match the param and value
 	 *
 	 * @param string $param_name
 	 * @param string $param_value
 	 * @return array
 	 */
	public function getFieldsWithParams($param_name, $param_value)
	{
		if(empty($this->_field_defs)){
			$this->loadVardefs();
		}
		$fields_with_param = array();
		foreach($this->_field_defs as $key => $def){
			if(!empty($def[$param_name]) && ($def[$param_name] == $param_value)){
				$fields_with_param[$key] = $def;
			}
		}
		return $fields_with_param;
 	}

    /**
     * Save source's config to custom directory
     */
    public function saveConfig()
    {
        // Allow for hooks to be run in each child source as needed
        $this->preSaveConfig();

        // Handle the actual saving
        $config_str = "<?php\n/***CONNECTOR SOURCE***/\n";

        // Handle encryption
        if(!empty($this->_config['encrypt_properties']) && is_array($this->_config['encrypt_properties']) && !empty($this->_config['properties'])){
            foreach($this->_config['encrypt_properties'] as $name) {
                if(!empty($this->_config['properties'][$name])) {
                    $this->_config['properties'][$name] = Blowfish::encode(
                        Blowfish::getKey('encrypt_field'),
                        $this->_config['properties'][$name]
                    );
                }
            }
        }

        foreach($this->_config as $key => $val) {
            if(!empty($val)){
                $config_str .= $this->getConfigString($key, $val);
            }
        }
        $dir = str_replace('_', '/', get_class($this));

        if(!file_exists("custom/modules/Connectors/connectors/sources/{$dir}")) {
            mkdir_recursive("custom/modules/Connectors/connectors/sources/{$dir}");
        }
        file_put_contents("custom/modules/Connectors/connectors/sources/{$dir}/config.php", $config_str);

        // Rebuild the connector cache now
        ConnectorUtils::getConnectors(true);

        // Handle after save tasks
        $this->postSaveConfig();

        // Force a metadata refresh if needed
        MetaDataManager::refreshSectionCache(MetaDataManager::MM_CONFIG);
    }

    /**
     * Get the config values encoded to string format to place into the custom modules directory
     * @param $key array key
     * @param $val array value
     */
    public function getConfigString($key, $val) {
        return override_value_to_string_recursive2('config', $key, $val, true);
    }

 	/**
 	 * Initialize config - decrypt encrypted fields
 	 */
 	public function initConfig()
 	{
        if($this->config_decrypted) return;
        // Handle decryption
        if(!empty($this->_config['encrypt_properties']) && is_array($this->_config['encrypt_properties']) && !empty($this->_config['properties'])){
            foreach($this->_config['encrypt_properties'] as $name) {
                if(!empty($this->_config['properties'][$name])) {
                    $this->_config['properties'][$name] = Blowfish::decode(
                        Blowfish::getKey('encrypt_field'),
                        $this->_config['properties'][$name]
                    );
                }
            }
        }
        $this->config_decrypted = true;
 	}

 	/**
 	 * Load config.php for this source
 	 */
	public function loadConfig()
	{
		$config = array();
		$dir = str_replace('_','/',get_class($this));
		foreach(SugarAutoLoader::existingCustom("modules/Connectors/connectors/sources/{$dir}/config.php") as $file) {
		    require $file;
		}
		$this->_config = $config;

		//If there are no required config fields specified, we will default them to all be required
		if(empty($this->_required_config_fields)) {
		   foreach($this->_config['properties'] as $id=>$value) {
		   	  $this->_required_config_fields[] = $id;
		   }
		}
 	}

    // Helper function for the settings panels
    /**
     * Filter which modules are allowed to connect
     * @param array $moduleList
     * @return array Allowed modules
     */
    public function filterAllowedModules( $moduleList )
    {
        // Most modules can connect to everything, no further filtering necessary
        return $moduleList;
    }

 	////////////// GETTERS and SETTERS ////////////////////
	public function getMapping()
	{
 		return $this->_mapping;
 	}

	public function getOriginalMapping() {
 		$mapping = array();
 		$dir = str_replace('_','/',get_class($this));
        foreach(SugarAutoLoader::existing("modules/Connectors/connectors/sources/{$dir}/mapping.php") as $file) {
            require $file;
        }
		return $mapping;
 	}

 	public function setMapping($mapping)
 	{
 		$this->_mapping = $mapping;
 	}

 	public function getFieldDefs()
 	{
 		return $this->_field_defs;
 	}

 	public function getConfig()
 	{
 	    if(!$this->config_decrypted) $this->initConfig();
 		return $this->_config;
 	}

 	public function setConfig($config)
 	{
 		$this->_config = $config;
 		$this->config_decrypted = true; // Don't decrypt external configs
 	}

    public function setEAPM(ExternalAPIBase $eapm)
    {
        $this->_eapm = $eapm;
    }

    public function getEAPM()
    {
        return $this->_eapm;
    }

    public function setProperties($properties=array())
    {
 	 	if(!empty($this->_config) && isset($this->_config['properties'])) {
 		   $this->_config['properties'] = $properties;
 		   $this->config_decrypted = true; // Don't decrypt external configs
 		}
 	}

 	public function getProperties()
 	{
 	 	if(!empty($this->_config) && isset($this->_config['properties'])) {
 	 	   if(!$this->config_decrypted) $this->initConfig();
 		   return $this->_config['properties'];
 		}
 		return array();
 	}

 	/**
 	 * Check if certain property contains non-empty value
 	 * @param string $name
 	 * @return bool
 	 */
 	public function propertyExists($name)
 	{
 	    return !empty($this->_config['properties'][$name]);
 	}

 	public function getProperty($name)
 	{
 	    if(!empty($this->_config) && isset($this->_config['properties'][$name])) {
 	        // check if we're asking for encrypted property and we didn't decrypt yet
 	        if(!$this->config_decrypted && !empty($this->_config['encrypt_properties']) && in_array($name, $this->_config['encrypt_properties']) && !empty($this->_config['properties'][$name])) {
 	            $this->initConfig();
 	        }
 	        return $this->_config['properties'][$name];
 		} else {
 			return '';
 		}
 	}

 	/**
 	 * hasTestingEnabled
 	 * This method is used to indicate whether or not a data source has testing enabled so that
 	 * the administration interface may call the test method on the data source instance
 	 *
 	 * @return enabled boolean value indicating whether or not testing is enabled
 	 */
 	public function hasTestingEnabled() {
 		return $this->_has_testing_enabled;
 	}

    /**
     * test
     * This method is called from the administration interface to run a test of the service
     * It is up to subclasses to implement a test and set _has_testing_enabled to true so that
     * a test button is rendered in the administration interface
     *
     * @return result boolean result of the test function
     */
    public function test() {
        return true;
    }


    /**
     * isEnabledInWizard
     * This method indicates whether or not the connector should be enabled in the wizard
     * Connectors that do not support the getList/getItem methods via API calls should
     * set the protected class variable _enable_in_wizard to false.
     *
     * @return $enabled boolean variable indicating whether or not the connector is enabled for the wizard
     */
    public function isEnabledInWizard() {
    	return $this->_enable_in_wizard;
    }


    /**
     * isEnabledInHover
     * This method indicates whether or not the connector should be enabled for the hover links
     * Connectors that do not provide a formatter implementation should not
     * set the protected class variable _enable_in_hover to true.
     *
     * @return $enabled boolean variable indicating whether or not the connector is enabled for the hover links
     *
     */
    public function isEnabledInHover() {
    	return $this->_enable_in_hover;
    }

    /**
     * isEnabledInAdminProperties
     * This method indicates whether or not the connector should be shown in the Set Connector Properties view.
     * The Admin views call each source's isEnabledInAdminProperties method to verify whether or not the connector should be
     * displayed.  Connectors that do not have any administrative properties should set the protected class variable
     * _enable_in_admin_properties to false.
     *
     * @return boolean value indicating whether or not the connector is enabled for admin views
     */
    public function isEnabledInAdminProperties()
    {
        return $this->_enable_in_admin_properties;
    }

    /**
     * isEnabledInAdminMapping
     * This method indicates whether or not the connector should be shown in the Map Connector Fields view.
     * The Admin views call each source's isEnabledInAdminMapping method to verify whether or not the connector should be
     * displayed.  Connectors that do not have any administrative mapping properties should set the protected class variable
     * _enable_in_admin_mapping to false.
     *
     * @return boolean value indicating whether or not the connector is enabled for admin views
     */
    public function isEnabledInAdminMapping()
    {
        return $this->_enable_in_admin_mapping;
    }

    /**
     * isEnabledInAdminDisplay
     * This method indicates whether or not the connector should be shown in the Enable Connectors view.
     * The Admin views call each source's isEnabledInAdminDisplay method to verify whether or not the connector should be
     * displayed.  Connectors that do not have any administrative display settings should set the protected class variable
     * _enable_in_admin_display to false.
     *
     * @return boolean value indicating whether or not the connector is enabled for admin views
     */
    public function isEnabledInAdminDisplay()
    {
        return $this->_enable_in_admin_display;
    }

    /**
     * isEnabledInAdminSearch
     * This method indicates whether or not the connector should be shown in the Manage Connectors Search view.
     * The Admin views call each source's isEnabledInAdminSearch method to verify whether or not the connector should be
     * displayed.  Connectors that do not have any administrative search settings should set the protected class variable
     * _enable_in_admin_search to false.
     *
     * @return boolean value indicating whether or not the connector is enabled for admin views
     */
    public function isEnabledInAdminSearch()
    {
        return $this->_enable_in_admin_search;
    }

    /**
     * getRequiredConfigFields
     * This method returns an Array of the configuration keys that are required for the Connector.
     * Subclasses should set the class variable _required_config_fields to
     * return an Array of keys as specified in the Connector's config.php that are required.
     *
     * @return $fields Array of Connector config fields that are required
     */
    public function getRequiredConfigFields() {
    	return $this->_required_config_fields;
    }


    /**
     * isRequiredConfigFieldsSet
     * This method checks the configuration parameters against the required config fields
     * to see if they are set
     *
     * @return $set boolean value indicating whether or not the required config fields are set
     */
    public function isRequiredConfigFieldsSet() {
        //Check if required fields are set
   		foreach($this->_required_config_fields as $field) {
	    	if(empty($this->_config['properties'][$field])) {
	    	   return false;
	    	}
   		}
    	return true;
    }


    /**
     * getRequiredConfigFieldsForButton
     * This method returns an Array of the configuration keys that are required before the
     * "Get Data" button will include the Connector.  We use it as a subset of the
     * $this->_required_config_fields Array.
     *
     * @return $fields Array of Connector config fields that are required to be set for the "Get Data" button to appear
     */
    public function getRequiredConfigFieldsForButton() {
    	return $this->_required_config_fields_for_button;
    }


    /**
     * isRequiredConfigFieldsForButtonSet
     * This method checks the configuration parameters against the required config fields
     * for the "Get Button" to see if they are set
     *
     * @return $set boolean value indicating whether or not the required config fields are set
     */
    public function isRequiredConfigFieldsForButtonSet() {
        //Check if required fields for button are set
        foreach ($this->_required_config_fields_for_button as $field) {
            if (empty($this->_config['properties'][$field])) {
               return false;
            }
        }

        return true;
    }


    /**
     * Allow data sources to log information
     *
     * @param string $log_data
     */
    protected function log($log_data){
    	$name = get_class($this);
    	$property_name = $this->getProperty('name');
    	if(!empty($property_name)){
    		$name = $property_name;
    	}
    	$GLOBALS['log']->info($name. ': '.$log_data);
    }

 	/**
 	 * getItem
 	 * Returns an array containing a key/value pair(s) of a connector record. To be overridden by the implementation
 	 * source.
 	 *
 	 * @param $args Array of arguments to search/filter by
 	 * @param $module String optional value of the module that the connector framework is attempting to map to
 	 * @return Array of key/value pair(s) of connector record; empty Array if no results are found
 	 */
	public abstract function getItem($args=array(), $module=null);


 	/**
 	 * getList
 	 * Returns a nested array containing a key/value pair(s) of a connector record. To be overridden by the
 	 * implementation source.
 	 *
 	 * @param $args Array of arguments to search/filter by
 	 * @param $module String optional value of the module that the connector framework is attempting to map to
 	 * @return Array of key/value pair(s) of connector record; empty Array if no results are found
 	 */
	public abstract function getList($args=array(), $module=null);

    /**
	 * Default destructor
	 *
	 */
 	public function __destruct(){
         // Bug # 47233 - This desctructor was originally removed by bug # 44533.
         // We have to add this destructor back in
         // because there are customers who upgrade from 61x to 623
         // who have the Jigsaw connector enabled, and the jigsaw connector
         // makes a call to this destructor.

     }

     /**
      * Handles actions at the beginning of saveConfig(). This should be defined
      * in child classes if there are actions to carry out.
      */
     public function preSaveConfig()
     {}

     /**
      * Handles actions and the end of saveConfig(). This should be defined
      * in child classes if there are actions to carry out.
      */
     public function postSaveConfig()
     {}
}
