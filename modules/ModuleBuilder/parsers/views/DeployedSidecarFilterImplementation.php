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

require_once 'modules/ModuleBuilder/parsers/constants.php';

use Sugarcrm\Sugarcrm\Util\Files\FileLoader;

class DeployedSidecarFilterImplementation extends AbstractMetaDataImplementation implements MetaDataImplementationInterface
{
    /**
     * The file that the metadata for this implementation was loaded from
     * 
     * @var string
     */
    protected $loadedMetadataFile;

    /**
     * The metadata file that the original metadata was loaded from
     * 
     * @var string
     */
    protected $originalMetadataFile;

    /**
     * Used when loading metadata to find the right file to use
     * 
     * @var array
     */
    protected $currentStateFiles = array(
        MB_HISTORYMETADATALOCATION,
        MB_WORKINGMETADATALOCATION,
        MB_CUSTOMMETADATALOCATION,
        MB_BASEMETADATALOCATION,
    );

    /**
     * The constructor
     * @param string $linkName
     * @param string $loadedModule - Accounts
     * @param string $client - base
     */
    public function __construct($loadedModule, $client = 'base')
    {
        $this->bean = BeanFactory::newBean($loadedModule);
        if (empty($this->bean)) {
            throw new Exception("Bean was not provided");
        }

        // Set some preliminaries so that the rest of the class can do its thing
        $this->_moduleName = $loadedModule;
        $this->setViewClient($client);

        // Get the viewdefs
        $viewdefs = $this->getCurrentMetadata();
        $this->_viewdefs = empty($viewdefs) ? $this->getEmptyDefs() : $this->getNewViewDefs($viewdefs);

        // Set the history file
        $historyFile = $this->getMetadataFilename(MB_HISTORYMETADATALOCATION);
        $this->_history = new History($historyFile);

        // Set the field defs
        $this->_fielddefs = $this->bean->field_defs;

        // Some fields are defined in the original filter metadata file but not in _fielddefs.
        // We want to add them to _fielddefs as well, so when they are moved from the default
        // column to the hidden column in Studio's search layout, they won't disappear.
        $types = array(MB_BASEMETADATALOCATION);
        $marker = 'originalMetadataFile';
        $originalMeta = $this->getMetadataFromFiles($types, $marker);
        if (empty($originalMeta)) {
            $originalMeta = $this->getFallbackMetadata($marker);
        }
        if ($originalMeta && !empty($originalMeta[$this->_moduleName]['base']['filter']['default']['fields']) && is_array($originalMeta[$this->_moduleName]['base']['filter']['default']['fields'])) {
            foreach ($originalMeta[$this->_moduleName]['base']['filter']['default']['fields'] as $key => $val) {
                // FIXME This is a temporary fix, will have a more generic solution in TY-228
                if ((!isset($this->_fielddefs[$key]) && isset($val['dbFields'])) || $key === '$favorite') {
                    // if this field is not already in _fielddefs, add it
                    $this->_comboFieldDefs[$key] = $val;
                }
            }
        }

        // Make sure the paneldefs are proper if there are any
        $this->_paneldefs = isset($this->_viewdefs) ? $this->_viewdefs : array();

        // Lastly, but not leastly, grab the original metadata
        $original = $this->getOriginalMetadata();
        $this->_originalViewdefs = $this->getDefsFromOriginal($original);
    }

    /**
     * Gets an array containing just a fields element
     * 
     * @return array
     */
    public function getEmptyDefs()
    {
        return array('fields' => array());
    }

    /**
     * Gets correct view definitions from an array of definitions 
     * @param array $defs Complete viewdef to get defs from
     * @param string $client The client to search the defs for
     * @return array
     */
    public function getDefsFromArray($defs, $client)
    {
        if (isset($defs[$this->_moduleName][$client]['filter']['default'])) {
            return $defs[$this->_moduleName][$client]['filter']['default'];
        }
        
        return $this->getEmptyDefs();
    }

    /**
     * Get the correct viewdefs from the array in the file
     * @param array $viewDefs
     * @return array
     */
    public function getNewViewDefs(array $viewDefs)
    {
        return $this->getDefsFromArray($viewDefs, $this->loadedViewClient);
    }

    /**
     * Getter for the fielddefs
     * @return array
     */
    public function getFieldDefs()
    {
        unset($this->_fielddefs['my_favorite']);
        return $this->_fielddefs;
    }

    /**
     * Getter for the language
     * @return string
     */
    public function getLanguage()
    {
        return $this->_moduleName;
    }

    /*
     * Save a definition that will be used to display a filter for $this->_moduleName
     * @param array defs Layout definition in the same format as received by the constructor
     */

    public function deploy($defs, $clearCache = true)
    {
        // We are saving to the custom file
        $savefile = $this->getMetadataFilename(MB_CUSTOMMETADATALOCATION);

        // Simple validation and sanity checking
        if (!is_dir(dirname($savefile))) {
            if (!sugar_mkdir(dirname($savefile), null, true)) {
                throw new Exception("Cannot create directory for $savefile");
            }
        }

        // Handle history
        if ($this->loadedMetadataFile === MB_HISTORYMETADATALOCATION) {
            $types = array(MB_WORKINGMETADATALOCATION, MB_CUSTOMMETADATALOCATION, MB_BASEMETADATALOCATION);
            foreach ($types as $type) {
                $file = $this->getMetadataFilename($type);
                if (file_exists($file)) {
                    $this->_history->append($file);
                    break;
                }
            }
        } else {
            $this->_history->append($this->loadedMetadataFile);
        }

        $this->_viewdefs = $defs;

        // Now save the actual data
        $ret = write_array_to_file(
            "viewdefs['{$this->_moduleName}']['{$this->_viewClient}']['filter']['default']",
            $this->_viewdefs,
            $savefile
        );

        // Delete the working file if exists as we do in DeployedMetaDataImplementation
        $workingFilename = $this->getMetadataFilename(MB_WORKINGMETADATALOCATION);

        if (file_exists($workingFilename)) {
            unlink($workingFilename);
        }

        if ($clearCache) {
            // clear the cache for this module
            MetaDataManager::refreshModulesCache(array($this->_moduleName));
        }
        return $ret;
    }

    /*
     * Construct a full pathname for the requested metadata
     *
     * @param string $type The type of the file (custom, history, etc)
     * @param string $template The sugar object template type
     * @param string $client The client for this file name
     */
    protected function getMetadataFilename($type = MB_BASEMETADATALOCATION, $template = null, $client = null)
    {
        if ($client === null) {
            $client = $this->_viewClient;
        }

        // If a template was provided, use the sugarobject template type path
        if ($template) {
            $path = "include/SugarObjects/templates/$template";
        } else {
            // Else use the regular file path
            $path = MetaDataFiles::getPath($type) . "modules/{$this->_moduleName}";
        }

        // Finish building the path before sending it back
        $path .= "/clients/{$client}/filters/default/default.php";
        return $path;
    }

    /**
     * Gets metadata from a set of file types.
     * 
     * @param array $types List of file types to get metadata from when found
     * @param string $marker The property to set when metadata is found
     * @return array
     */
    public function getMetadataFromFiles($types, $marker = 'loadedMetadataFile')
    {
        // Prepare the return
        $viewdefs = array();

        foreach ($types as $type) {
            $file = $this->getMetadataFilename($type);
            if (file_exists($file)) {
                require FileLoader::validateFilePath($file);
                if (!empty($viewdefs)) {
                    $this->$marker = $file;
                    break;
                }
            }
        }

        return $viewdefs;
    }

    /**
     * Gets the original view defs from either the custom metadata, base metadata
     * or the sugar objects template
     * 
     * @return array
     */
    public function getOriginalMetadata()
    {
        $types = array(MB_CUSTOMMETADATALOCATION, MB_BASEMETADATALOCATION);
        $marker = 'originalMetadataFile';

        $viewdefs = $this->getMetadataFromFiles($types, $marker);

        // If, at this point, our viewdefs are empty, we need to try a little
        // harder and a little deeper
        if (empty($viewdefs)) {
            $viewdefs = $this->getFallbackMetadata($marker);
        }

        return $viewdefs;
    }

    /**
     * Loads the current metadata for this implementation. Checks in working, 
     * history, custom and base before getting fallback metadata.
     * 
     * @return array
     */
    public function getCurrentMetadata()
    {
        // Began the metadata load process. There should be two types of loads
        // here... one for the current metadata to show in studio, which loads
        // working, history, custom, base in that order, and one for the original
        // view defs that are brought in which should be base, custom. In both
        // cases, if there are no defs found, we should look to sugar object 
        // templates for type then basic, in that order.
        $viewdefs = $this->getMetadataFromFiles($this->currentStateFiles);

        // If, at this point, our viewdefs are empty, we need to try a little
        // harder and a little deeper
        if (empty($viewdefs)) {
            $viewdefs = $this->getFallbackMetadata();

            // Set to base for downstream processes
            $this->loadedViewClient = 'base';
        }

        return $viewdefs;
    }

    /**
     * Gets the metadata from various non module locations as a fallback. If 
     * no metadata file is found, will log an error and return an empty set of 
     * viewdefs.
     * 
     * @param string $marker The property to set when metadata is found
     * @return array
     */
    public function getFallbackMetadata($marker = 'loadedMetadataFile')
    {
        // Prepare the return
        $viewdefs = array();

        // Get our module type
        $sm = new StudioModule($this->_moduleName);
        $template = $sm->getType();

        // Build an array of files to search defs for
        $files = array();
        if (!$this->_viewClient !== 'base') {
            // This is the OOTB file for this module in the base client
            $files[] = $this->getMetadataFilename(MB_BASEMETADATALOCATION, null, 'base');
        }

        // This is the metadata file for this module type
        $files[] = $this->getMetadataFilename('', $template, 'base');

        if ($template !== 'basic') {
            // This is the metadata file for basic modules
            $files[] = $this->getMetadataFilename('', 'basic', 'base');
        }

        // Used for logging
        $found = false;

        // Loop and set
        foreach ($files as $file) {
            if (file_exists($file)) {
                require FileLoader::validateFilePath($file);
                if (!empty($viewdefs)) {
                    // This needs to be done in the event we have a SugarObject template file in use
                    $viewdefs = MetaDataFiles::getModuleMetaDataDefsWithReplacements($this->bean, $viewdefs);
                    if (isset($viewdefs[$this->_moduleName])) {
                        $this->$marker = $file;
                        $found = true;
                        break;
                    }
                }
            }
        }

        // If we found nothing, log it
        if (!$found) {
            $GLOBALS['log']->error("Could not find a filter file for {$this->_moduleName}");
        }

        return $viewdefs;
    }

    /**
     * Sets the client
     *
     * @param string $client
     */
    public function setViewClient($client)
    {
        parent::setViewClient($client);
        $this->loadedViewClient = $client;
    }
    
    /**
     * Attempts to get defs from an array with unknown client type, like from a
     * fallback fetch
     * 
     * @param array $defs The definitions to search
     * @return array
     */
    public function getDefsFromOriginal($defs)
    {
        if (isset($defs[$this->_moduleName])) {
            $client = key($defs[$this->_moduleName]);
            $newdefs = $this->getDefsFromArray($defs, $client);
            if (!empty($newdefs['fields'])) {
                return $newdefs;
            }
        }

        return $this->getEmptyDefs();
    }
}
