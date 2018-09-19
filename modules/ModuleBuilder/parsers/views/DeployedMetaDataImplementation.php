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
/*
 * Implementation class (following a Bridge Pattern) for handling loading and saving deployed module metadata
 * For example, listview or editview viewdefs
 */

require_once 'modules/ModuleBuilder/parsers/constants.php';

class DeployedMetaDataImplementation extends AbstractMetaDataImplementation implements MetaDataImplementationInterface
{
    /**
     * Additional metadata parameters
     *
     * @var array
     */
    protected $params = array();

	/*
	 * Constructor
	 * @param string $view
	 * @param string $moduleName
	 * @param string $client The client making the request for this implementation
     * @param array  $params Additional metadata parameters
	 * @throws Exception Thrown if the provided view doesn't exist for this module
	 */
    public function __construct($view, $moduleName, $client = '', array $params = array())
	{
        // Set the deployed state to true
        $this->_deployed = true;

		// BEGIN ASSERTIONS
		if (! isset ( $GLOBALS [ 'beanList' ] [ $moduleName ] ))
		{
			sugar_die ( get_class ( $this ) . ": Modulename $moduleName is not a Deployed Module" ) ;
		}
		// END ASSERTIONS

		$this->_view = strtolower($view);
        $this->params = $params;
        $this->setViewClient($client);
		$this->_moduleName = $moduleName ;

		$module = StudioModuleFactory::getStudioModule( $moduleName ) ;
		$this->module_dir = $module->seed->module_dir;
		$fielddefs = $module->getFields();

        //Load any custom views
        $sm = StudioModuleFactory::getStudioModule($moduleName);
        foreach($sm->sources as $file => $def)
        {
            if (!empty($def['view'])) {
                $viewVar = "viewdefs";
                if (!empty($def['type']) && !empty($this->_fileVariables[$def["type"]]))
                    $viewVar = $this->_fileVariables[$def["type"]];
                $this->_fileVariables[$def['view']] = $viewVar;
            }
        }

		$loaded = null ;
		foreach ( array ( MB_BASEMETADATALOCATION , MB_CUSTOMMETADATALOCATION , MB_WORKINGMETADATALOCATION , MB_HISTORYMETADATALOCATION ) as $type )
		{
            $this->_sourceFilename = $this->getFileName($view, $moduleName, $type, $client);
			if($view == MB_POPUPSEARCH || $view == MB_POPUPLIST){
				global $current_language;
				$mod = return_module_language($current_language , $moduleName);
				$layout = $this->_loadFromPopupFile ( $this->_sourceFilename , $mod, $view);
			}else{
				$layout = $this->_loadFromFile ( $this->_sourceFilename );
			}
			if ( null !== $layout )
			{
                if (MB_WORKINGMETADATALOCATION == $type) {
                    $this->_useWorkingFile = true;
                } elseif (MB_HISTORYMETADATALOCATION == $type && $this->_useWorkingFile) {
                    $this->_useWorkingFile = false;
                } elseif ($type === MB_BASEMETADATALOCATION) {
                    $this->baseViewdefs = $layout;
                }
				// merge in the fielddefs from this layout
				$this->_mergeFielddefs ( $fielddefs , $layout ) ;
				$loaded = $layout ;
			}
		}

		if ($loaded === null)
		{
			switch ( $view )
			{
				case MB_QUICKCREATE:
					// Special handling for QuickCreates - if we don't have a QuickCreate definition in the usual places, then use an EditView

					$loaded = $this->_loadFromFile ( $this->getFileName ( MB_EDITVIEW, $this->_moduleName, MB_BASEMETADATALOCATION ) ) ;

					if ($loaded === null)
						throw new Exception( get_class ( $this ) . ": cannot convert from EditView to QuickCreate for Module $this->_moduleName - definitions for EditView are missing" ) ;

					// Now change the array index
					$temp = $loaded [ GridLayoutMetaDataParser::$variableMap [ MB_EDITVIEW ] ] ;
					unset ( $loaded [ GridLayoutMetaDataParser::$variableMap [ MB_EDITVIEW ] ] ) ;
					$loaded [ GridLayoutMetaDataParser::$variableMap [ MB_QUICKCREATE ] ] = $temp ;
					// finally, save out our new definition so that we have a base record for the history to work from
					$this->_sourceFilename = $this->getFileName ( MB_QUICKCREATE, $this->_moduleName, MB_CUSTOMMETADATALOCATION ) ;
					$this->_saveToFile ( $this->_sourceFilename, $loaded ) ;
					$this->_mergeFielddefs ( $fielddefs , $loaded ) ;
					break;

                case MB_SIDECARLISTVIEW:
                case MB_RECORDVIEW:
                case MB_SIDECARPOPUPVIEW:
				case MB_SIDECARDUPECHECKVIEW:
                case MB_PORTALLISTVIEW:
                case MB_PORTALRECORDVIEW:
                case MB_WIRELESSEDITVIEW:
                case MB_WIRELESSDETAILVIEW:
                case MB_WIRELESSBASICSEARCH:
                case MB_WIRELESSADVANCEDSEARCH:
                case MB_WIRELESSLISTVIEW:
                    $_viewtype = 'mobile';
                if (in_array($view,
                    array(MB_RECORDVIEW, MB_SIDECARPOPUPVIEW, MB_SIDECARDUPECHECKVIEW, MB_SIDECARLISTVIEW))) {
                    $_viewtype = 'base';
                }

                    // Set a view type (ie, portal, wireless)
                    if(in_array($view, array(MB_PORTALLISTVIEW, MB_PORTALRECORDVIEW, MB_PORTALSEARCHVIEW)))
                    {
                        $_viewtype = 'portal';
                    }

					// If we're missing a wireless view, we can create it easily from a template, sourced from SugarObjects
					// First, need to identify which SugarObject template would be the best to use
					$type = $module->getType () ;
					$this->_sourceFilename = $this->getFileName ( $view, $moduleName, MB_CUSTOMMETADATALOCATION ) ;

					// Recurse through the various SugarObjects templates to get
                    // def we can use. At worst, this will end up at basic base
                    // Note: getDefsFromTemplate() returns an array, of which 'defs'
                    // is a member. If defs is empty (which it should never be)
                    // then the remainder of the array contains useful error information.
					$loaded = $this->getDefsFromTemplate($module, $_viewtype);
					if (empty($loaded['defs'])) {
                        $eMessage  = get_class($this);
                        $eMessage .= ": cannot create $_viewtype view for module $moduleName - definitions for $view are missing.\n";
                        $eMessage .= "Attempted all types and clients and failed after {$loaded['type']} for {$loaded['client']} in {$loaded['file']}";
						throw new Exception($eMessage);
                    }

					$loaded = $this->replaceVariables($loaded['defs'], $module);
					$this->_saveToFile ( $this->_sourceFilename, $loaded , false ) ; // write out without the placeholder module_name and object
					$this->_mergeFielddefs ( $fielddefs , $loaded ) ;
					break;
				case MB_DASHLETSEARCH:
        		case MB_DASHLET:
	        		$type = $module->getType () ;
	        		$this->_sourceFilename = $this->getFileName ( $view, $moduleName, MB_CUSTOMMETADATALOCATION ) ;
	        		$needSave = false;
	        		if(file_exists( "custom/modules/{$moduleName}/metadata/".basename ( $this->_sourceFilename))){
	        			$loaded = $this->_loadFromFile ( "custom/modules/{$moduleName}/metadata/".basename ( $this->_sourceFilename) )  ;
	        		}
	        		elseif(file_exists(
	        			"modules/{$moduleName}/Dashlets/My{$moduleName}Dashlet/My{$moduleName}Dashlet.data.php")){
	        			$loaded = $this->_loadFromFile ( "modules/{$moduleName}/Dashlets/My{$moduleName}Dashlet/My{$moduleName}Dashlet.data.php");
	        		}
	        		else{
	        			$loaded = $this->_loadFromFile ( "include/SugarObjects/templates/$type/metadata/".basename ( $this->_sourceFilename ) ) ;
	        			$needSave = true;
	        		}
	        		if ($loaded === null)
						throw new Exception( get_class ( $this ) . ": cannot create dashlet view for module $moduleName - definitions for $view are missing in the SugarObject template for type $type" ) ;
	        		$loaded = $this->replaceVariables($loaded, $module);
	        		$temp = $this->_moduleName;
	        		if($needSave){
		        		$this->_moduleName = $this->_moduleName.'Dashlet';
						$this->_saveToFile ( $this->_sourceFilename, $loaded,false) ; // write out without the placeholder module_name and object
						$this->_moduleName = $temp;
						unset($temp);
	        		}
					$this->_mergeFielddefs ( $fielddefs , $loaded ) ;
					break;
				case MB_POPUPLIST:
        		case MB_POPUPSEARCH:
        			$type = $module->getType () ;
					$this->_sourceFilename = $this->getFileName ( $view, $moduleName, MB_CUSTOMMETADATALOCATION ) ;

					global $current_language;
					$mod = return_module_language($current_language , $moduleName);
					$loadedForWrite = $this->_loadFromPopupFile (  "include/SugarObjects/templates/$type/metadata/".basename ( $this->_sourceFilename )  , $mod, $view, true);
        			if ($loadedForWrite === null)
						throw new Exception( get_class ( $this ) . ": cannot create popup view for module $moduleName - definitions for $view are missing in the SugarObject template for type $type" ) ;
	        		$loadedForWrite = $this->replaceVariables($loadedForWrite, $module);
					$this->_saveToFile ( $this->_sourceFilename, $loadedForWrite , false , true) ; // write out without the placeholder module_name and object
					$loaded = $this->_loadFromPopupFile (  "include/SugarObjects/templates/$type/metadata/".basename ( $this->_sourceFilename )  , $mod, $view);
					$this->_mergeFielddefs ( $fielddefs , $loaded ) ;
        			break;
				default:

			}
			if ( $loaded === null )
				throw new Exception( get_class ( $this ) . ": view definitions for View $this->_view and Module $this->_moduleName are missing" ) ;
		}

		$this->_viewdefs = $loaded;
		// Set the original Viewdefs - required to ensure we don't lose fields from the base layout
		// Check the base location first, then if nothing is there (which for example, will be the case for some QuickCreates, and some mobile layouts - see above)
		// we need to check the custom location where the derived layouts will be
		foreach ( array ( MB_BASEMETADATALOCATION , MB_CUSTOMMETADATALOCATION ) as $type )
		{
            $sourceFilename = $this->getFileName($view, $moduleName, $type);
			if($view == MB_POPUPSEARCH || $view == MB_POPUPLIST){
				global $current_language;
				$mod = return_module_language($current_language , $moduleName);
				$layout = $this->_loadFromPopupFile ( $sourceFilename , $mod, $view);
			}else{
				$layout = $this->_loadFromFile ( $sourceFilename );
			}
			if ( null !== ($layout ) )
			{
				$this->_originalViewdefs = $layout ;
				break ;
			}
		}
		//For quick create viewdefs, if there is no quickcreatedefs.php under MB_BASEMETADATALOCATION, the original defs is editview defs.
        if ($view == MB_QUICKCREATE) {
          foreach(array(MB_QUICKCREATE, MB_EDITVIEW) as $v){
            $sourceFilename = $this->getFileName($v, $moduleName, MB_BASEMETADATALOCATION ) ;
            if (file_exists($sourceFilename )) {
              $layout = $this->_loadFromFile($sourceFilename );
              if (null !== $layout && isset($layout[GridLayoutMetaDataParser::$variableMap[$v]])) {
                $layout = array(GridLayoutMetaDataParser::$variableMap[MB_QUICKCREATE] => $layout[GridLayoutMetaDataParser::$variableMap[$v]]);
                break;
              }
            }
          }

          if (null === $layout) {
            $sourceFilename = $this->getFileName($view, $moduleName, MB_CUSTOMMETADATALOCATION );
            $layout = $this->_loadFromFile($sourceFilename );
          }

          if (null !== $layout  ) {
            $this->_originalViewdefs = $layout ;
          }
        }

		$this->_fielddefs = $fielddefs;

        // Set the panel defs (the old field defs)
        $this->setPanelDefsFromViewDefs();

        // Make sure the paneldefs are proper if there are any
        if (is_array($this->_paneldefs) && !is_numeric(key($this->_paneldefs))) {
            $this->_paneldefs = array($this->_paneldefs);
        }

        $this->_history = new History($this->getFileNameNoDefault($view, $moduleName, MB_HISTORYMETADATALOCATION));
	}

    /**
     * Gets viewdefs from a SugarObjects template when the expected metadata file
     * is not found.
     *
     * @param string $module The module for this view
     * @param string $client The client for this view
     * @return array
     */
    protected function getDefsFromTemplate($module, $client = 'base')
    {
        // Set the requested client for comparison later
        $rClient = $client;

        // Create a path ending to the metadata file
        $file = basename($this->_sourceFilename, '.php') . '/' . basename($this->_sourceFilename);

        // Create a stack of types based on the module, making sure to always add
        // in basic at the end
        $types[] = $module->getType();
        if ($types[0] != 'basic') {
            $types[] = 'basic';
        }

        // Create a stack of clients, making sure to always add base at the end
        $clients[] = $client;
        if ($client != 'base') {
            $clients[] = 'base';
        }

        // Send back an array of data that is needed for calling code
        $return = array('defs' => array());
        // Now loop over types and try to load a file, then loop over clients. The
        // basics are, if we're looking for mobile person type metadata we should
        // look in person mobile then basic mobile then person base then basic base
        foreach ($clients as $client) {
            foreach ($types as $type) {
                // Always set the type and client so clients know where this stopped
                // or where it failed
                $return['type'] = $type;
                $return['client'] = $client;

                // Now try to grab the file
                $path = "include/SugarObjects/templates/$type/clients/$client/views/$file";
                $return['file'] = $path;
                $loaded = $this->_loadFromFile($path);
                if ($loaded !== null) {
                    // If the client in the defs is not what we requested, reset it
                    // This handles cases where a base viewdef was picked up for
                    // a particular client.
                    if ($client != $rClient) {
                        $key = key($loaded);
                        $loaded[$rClient] = $loaded[$key];
                        unset($loaded[$key]);
                    }

                    $return['defs'] = $loaded;
                    // Break out so we can return this array once
                    break 2;
                }
            }
        }

        // Send it back... at this point the return should contain a type, client,
        // file and defs property, even if defs is empty
        return $return;
    }

	function getLanguage ()
	{
		return $this->_moduleName ;
	}

	function getOriginalViewdefs()
	{
		return $this->_originalViewdefs;
	}


	/*
	 * Save a draft layout
	 * @param array defs    Layout definition in the same format as received by the constructor
	 */
	function save ($defs)
	{
        $this->saveHistory();
		$GLOBALS [ 'log' ]->debug ( get_class ( $this ) . "->save(): writing to " . $this->getFileName ( $this->_view, $this->_moduleName, MB_WORKINGMETADATALOCATION ) ) ;
		$this->_saveToFile ( $this->getFileName ( $this->_view, $this->_moduleName, MB_WORKINGMETADATALOCATION ), $defs ) ;
	}

	/*
	 * Deploy a layout
	 * @param array defs    Layout definition in the same format as received by the constructor
	 */
	function deploy($defs) {
        $this->saveHistory();
		// when we deploy get rid of the working file; we have the changes in the MB_CUSTOMMETADATALOCATION so no need for a redundant copy in MB_WORKINGMETADATALOCATION
		// this also simplifies manual editing of layouts. You can now switch back and forth between Studio and manual changes without having to keep these two locations in sync
        $workingFilename = $this->getFileNameNoDefault($this->_view, $this->_moduleName, MB_WORKINGMETADATALOCATION);

		if (file_exists($workingFilename)) {
            unlink($workingFilename);
        }
        $filename = $this->getFileNameNoDefault($this->_view, $this->_moduleName, MB_CUSTOMMETADATALOCATION);
		$GLOBALS['log']->debug(get_class($this) . "->deploy(): writing to " . $filename);
		$this->_saveToFile($filename, $defs);

		// now clear the cache so that the results are immediately visible
        MetaDataFiles::clearModuleClientCache($this->_moduleName, 'view');
        MetaDataFiles::clearModuleClientCache($this->_moduleName, 'layout');

		include_once ('include/TemplateHandler/TemplateHandler.php') ;
		TemplateHandler::clearCache($this->_moduleName);
	}

    /**
     * Construct a full pathname for the requested metadata. If the file which matches additional metadata parameters
     * doesn't exist, the default file name is returned
	 *
	 * @param string $view           The view type, that is, EditView, DetailView etc
     * @param string $moduleName     The name of the module that will use this layout
     * @param string $location       The location of the file (custom, history, etc)
	 * @param string $client         The client type for the file name
     *
     * @return string
     */
    public function getFileName($view, $moduleName, $location = MB_CUSTOMMETADATALOCATION, $client = '')
    {
        if ($this->params && $this->locationSupportsParameters($location)) {
            $filename = $this->getFileNameByParameters($view, $moduleName, $location, $client, $this->params);
            // if no role layout is found, revert to the default version
            if (file_exists($filename) || !$this->locationUseDefault($location)) {
                return $filename;
            }
        }

        return $this->getFileNameByParameters($view, $moduleName, $location, $client);
    }

    public function getDefaultFileName($view, $moduleName, $client = null)
    {
        $locations = array(MB_CUSTOMMETADATALOCATION, MB_BASEMETADATALOCATION);
        foreach ($locations as $location) {
            $fileName = $this->getFileNameByParameters($view, $moduleName, $location, $client);
            if (file_exists($fileName)) {
                return $fileName;
            }
        }

        return null;
    }

    /**
     * Construct a full pathname for the requested metadata and do not check if the file exists
     *
     * @param string $view The view type, that is, EditView, DetailView etc
     * @param string $moduleName The name of the module that will use this layout
     * @param string $location The location of the file (custom, history, etc)
     * @param string $client The client type for the file name
     *
     * @return string
     */
    public function getFileNameNoDefault($view, $moduleName, $location = MB_CUSTOMMETADATALOCATION, $client = null)
    {
        return $this->getFileNameByParameters($view, $moduleName, $location, $client, $this->params);
    }

    /**
     * Checks if the given metadata location supports parameteres
     *
     * @param string $location
     * @return bool
     */
    protected function locationSupportsParameters($location)
    {
        switch ($location) {
            case MB_CUSTOMMETADATALOCATION:
            case MB_WORKINGMETADATALOCATION:
            case MB_HISTORYMETADATALOCATION:
                return true;
        }

        return false;
    }

    /**
     * Checks if default metadata file should be used
     * in case if the file corresponding to the given parameters doesn't exist
     *
     * @param string $location
     * @return bool
     */
    protected function locationUseDefault($location)
    {
        switch ($location) {
            case MB_BASEMETADATALOCATION:
            case MB_CUSTOMMETADATALOCATION:
                return true;
        }

        return false;
    }

	private function replaceVariables($defs, $module) {
        return MetaDataFiles::getModuleMetaDataDefsWithReplacements($module instanceof StudioModule ? $module->seed : $module, $defs);
		/*
        $var_values = array(
			"<object_name>" => $module->seed->object_name,
			"<_object_name>" => strtolower($module->seed->object_name),
			"<OBJECT_NAME>" => strtoupper($module->seed->object_name),
			"<module_name>" => $module->seed->module_dir,
			'<_module_name>'=> strtolower ( $module->seed->module_dir )
		);
		return $this->recursiveVariableReplace($defs, $module, $var_values);
		*/
	}

	public function getModuleDir(){
		return $this->module_dir;
	}

    private function recursiveVariableReplace($arr, $module, $replacements) {
        $ret = array();
		foreach ($arr as $key => $val) {
			if (is_array($val)) {
	            $newkey = $key;
                $val = $this->recursiveVariableReplace($val, $module, $replacements);
	            foreach ($replacements as $var => $rep) {
	                $newkey = str_replace($var, $rep, $newkey);
	            }
				$ret[$newkey] = $val;
	        } else {
                $newkey = $key;
			    $newval = $val;
                if(is_string($val))
                {
                    foreach ($replacements as $var => $rep) {
                        $newkey = str_replace($var, $rep, $newkey);
                        $newval = str_replace($var, $rep, $newval);
                    }
                }
                $ret[$newkey] = $newval;
			}
        }
		return $ret;
	}

    /**
     * This is just a wrapper to the private method _saveToFile
     * @param  $file    the file name to save to
     * @param  $defs    the defs to save to the file
     * @return void
     */
    public function saveToFile($file, $defs)
    {
        $this->_saveToFile ( $file, $defs ) ;
    }

    /**
     * Returns additional metadata parameters
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Resets user specific metadata to default
     */
    public function resetToDefault()
    {
        if (count($this->params) > 0) {
            $fileName = $this->getFileNameNoDefault($this->_view, $this->_moduleName);
            if (file_exists($fileName)) {
                $this->saveHistory();
                unlink($fileName);
                MetaDataFiles::clearModuleClientCache($this->_moduleName, 'view');
                // Clear out the cache just for the platform we are on
                $client = empty($this->client) ? 'base' : $this->client;
                MetaDataManager::refreshModulesCache(
                    array($this->_moduleName),
                    array($client),
                    $this->params
                );
            }
        }
    }

    /**
     * Saves the history for the previous state.
     */
    public function saveHistory()
    {
        if ($this->_sourceFilename == $this->getFileName(
                $this->_view,
                $this->_moduleName,
                MB_HISTORYMETADATALOCATION
            )
        ) {
            foreach (array(MB_WORKINGMETADATALOCATION, MB_CUSTOMMETADATALOCATION, MB_BASEMETADATALOCATION) as $type) {
                if (file_exists($this->getFileName($this->_view, $this->_moduleName, $type))) {
                    $this->_history->append($this->getFileName($this->_view, $this->_moduleName, $type));
                    break;
                }
            }
        } else {
            $this->_history->append($this->_sourceFilename);
        }
    }
}
