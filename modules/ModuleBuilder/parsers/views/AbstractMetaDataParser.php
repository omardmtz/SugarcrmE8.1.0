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

abstract class AbstractMetaDataParser
{
    /**
     * The client making this request for the parser. Default is empty. NOT ALL
     * PARSERS SET THIS.
     *
     * @var string
     */
    public $client;

    //Make these properties public for now until we can create some usefull accessors
    public $_fielddefs;
    public $_viewdefs;
    public $_paneldefs = array();
	protected $_moduleName;

    /**
     * object to handle the reading and writing of files and field data
     *
     * @var DeployedMetaDataImplementation|UndeployedMetaDataImplementation
     */
    protected $implementation;

    /**
     * Returns an array of modules affected by this object. In almost all cases
     * this will be a single array. For subpanels, it will be more than one.
     * 
     * @return array List of modules changed within this object
     */
    public function getAffectedModules()
    {
        return $this->implementation->getAffectedModules();
    }

    function getLayoutAsArray ()
    {
        $viewdefs = $this->_panels ;
    }

    function getLanguage ()
    {
        return $this->implementation->getLanguage () ;
    }

    function getHistory ()
    {
        return $this->implementation->getHistory () ;
    }

    public function getFieldDefs()
    {
        return $this->_fielddefs;
    }

    public function getPanelDefs() {
        return $this->_paneldefs;
    }

    public function getModuleName() {
        return $this->_moduleName;
    }

    function removeField ($fieldName)
    {
    	return false;
    }

    public function useWorkingFile() {
        return $this->implementation->useWorkingFile();
    }

    /**
     * Is this field something we wish to show in Studio/ModuleBuilder layout editors?
     *
     * @param array $def     Field definition in the standard SugarBean field definition format - name, vname, type and so on
     * @param string $view   The name of the view
     * @param string $client The client for this request
     * @return boolean       True if ok to show, false otherwise
     */
    public static function validField(array $def, $view = "", $client = '')
    {
        //Studio invisible fields should always be hidden
        if (isset($def['studio'])) {
            if (is_array($def['studio'])) {
                // Handle client specific studio setting for a field
                $clientRules = self::getClientStudioValidation($def['studio'], $view, $client);
                if ($clientRules !== null) {
                    return $clientRules;
                }
                
                if (!empty($view) && isset($def['studio'][$view])) {
                    return $def [ 'studio' ][$view] !== false && $def [ 'studio' ][$view] !== 'false' && $def [ 'studio' ][$view] !== 'hidden';
                }
                
                if (isset($def['studio']['visible'])) {
                    return $def['studio']['visible'];
                }
            } else {
                return $def['studio'] !== false && $def['studio'] !== 'false' && $def['studio'] !== 'hidden';
			}
        }

        // JSON fields are by design internal and are not supposed to be displayed as is on the client side
        // or modified in Studio
        if (isset($def['type']) && $def['type'] === 'json') {
            return false;
        }

        // bug 19656: this test changed after 5.0.0b - we now remove all ID type fields - whether set as type, or dbtype, from the fielddefs
        return
		(
		  (
		    (empty ( $def [ 'source' ] ) || $def [ 'source' ] == 'db' || $def [ 'source' ] == 'custom_fields')
			&& isset($def [ 'type' ]) && $def [ 'type' ] != 'id' && $def [ 'type' ] != 'parent_type'
			&& (empty ( $def [ 'dbType' ] ) || $def [ 'dbType' ] != 'id')
			&& ( isset ( $def [ 'name' ] ) && strcmp ( $def [ 'name' ] , 'deleted' ) != 0 )
		  ) // db and custom fields that aren't ID fields
          ||
          // exclude fields named *_name (just convention) and email1 regardless of their type
          (isset($def['name']) && ($def['name'] === 'email1' || substr($def['name'], -5) === '_name'))
            || (isset($def['type']) && ($def['type'] == 'file'))
        );
    }

    protected function _standardizeFieldLabels(array &$fielddefs)
	{
		foreach ( $fielddefs as $key => $def )
		{
			if ( !isset ($def [ 'label' ] ) )
			{
				$fielddefs [ $key ] [ 'label'] = ( isset ( $def [ 'vname' ] ) ) ? $def [ 'vname' ] : $key ;
			}
		}
	}

    public static function _trimFieldDefs(array $def)
    {
        throw new BadMethodCallException(__METHOD__ . ' is not implemented');
    }

	public function getRequiredFields(){
	    $fieldDefs = $this->implementation->getFielddefs();
	    $newAry = array();
	    foreach($fieldDefs as $field){
	        if(isset($field['required']) && $field['required'] && isset($field['name']) && empty( $field['readonly'])) {
	            array_push($newAry , '"'.$field['name'].'"');
            }
        }
        return $newAry;
	}

    /**
     * Used to determine if a field property is true or false given that it could be
     * the boolean value or a string value such use 'false' or '0'
     * @static
     * @param $val
     * @return bool
     */
    protected static function isTrue($val)
    {
        if (is_string($val))
        {
            $str = strtolower($val);
            return ($str != '0' && $str != 'false' && $str != "");
        }
        //For non-string types, juse use PHP's normal boolean conversion
        else{
            return ($val == true);
        }
    }

    /**
     * Public accessor for the isTrue method, to allow handlers outside of the
     * parsers to test truthiness of a value in metadata
     *
     * @static
     * @param mixed $val
     * @return bool
     */
    public static function isTruthy($val) {
        return self::isTrue($val);
    }

    /**
     * Cache killer, to be defined in child classes as needed.
     */
    protected function _clearCaches()
    {
        if ($this->implementation->isDeployed()) {
            SugarCache::cleanOpcodes();
        }
    }

    /**
     * Gets client specific vardef rules for a field for studio
     * 
     * @param Array $studio The value of $defs['studio']
     * @param string $view A view name, which could be empty
     * @param string $client The client for this request
     * @return bool|null Boolean if there is a setting for a client, null otherwise
     */
    public static function getClientStudioValidation(array $studio, $view, $client)
    {
        // Handle client specific studio setting for a field
        if ($client && isset($studio[$client])) {
            // Posibilities are:
            // studio[client] = true|false
            // studio[client] = array(view => true|false)
            if (is_bool($studio[$client])) {
                return $studio[$client];
            }
            
            // Check for a client -> specific studio setting
            if (!empty($view) && is_array($studio[$client]) && isset($studio[$client][$view])) {
                return $studio[$client][$view] !== false;
            }
        }
        
        return null;
    }

    /**
     * Resets user specific metadata to default
     */
    public function resetToDefault()
    {
        if ($this->implementation->isDeployed()) {
            $this->implementation->resetToDefault();
            $this->_clearCaches();
        }
    }
}
