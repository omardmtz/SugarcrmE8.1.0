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


// An API to let the user in to the metadata
class MetadataApi extends SugarApi
{
    /**
     * @return array
     */
    public function registerApiRest()
    {
        return array(
            'getAllMetadata' => array(
                'reqType' => 'GET',
                'path' => array('metadata'),
                'pathVars' => array(''),
                'method' => 'getAllMetadata',
                'shortHelp' => 'This method will return all metadata for the system',
                'longHelp' => 'include/api/html/metadata_all_help.html',
                'noEtag' => true,
                'ignoreMetaHash' => true,
                'ignoreSystemStatusError' => true,
            ),
            'getAllMetadataPost' => array(
                'reqType' => 'POST',
                'path' => array('metadata'),
                'pathVars' => array(''),
                'method' => 'getAllMetadata',
                'shortHelp' => 'This method will return all metadata for the system, filtered by the array of hashes sent to the server',
                'longHelp' => 'include/api/html/metadata_all_help.html',
                'noEtag' => true,
                'ignoreMetaHash' => true,
                'ignoreSystemStatusError' => true,
            ),
            'getAllMetadataHashes' => array(
                'reqType' => 'GET',
                'path' => array('metadata','_hash'),
                'pathVars' => array(''),
                'method' => 'getAllMetadataHash',
                'shortHelp' => 'This method will return the hash of all metadata for the system',
                'longHelp' => 'include/api/html/metadata_all_help.html',
                'ignoreMetaHash' => true,
                'ignoreSystemStatusError' => true,
            ),
            'getPublicMetadata' =>  array(
                'reqType' => 'GET',
                'path' => array('metadata','public'),
                'pathVars'=> array(''),
                'method' => 'getPublicMetadata',
                'shortHelp' => 'This method will return the metadata needed when not logged in',
                'longHelp' => 'include/api/html/metadata_all_help.html',
                'noLoginRequired' => true,
                'noEtag' => true,
                'ignoreMetaHash' => true,
                'ignoreSystemStatusError' => true,
            ),
            'getLanguage' => array(
                'reqType' => 'GET',
                'path' => array('lang', '?'),
                'pathVars' => array('', 'lang'),
                'method' => 'getLanguage',
                'shortHelp' => 'Returns the labels for the application',
                'longHelp' => 'include/api/html/metadata_all_help.html',
                'rawReply' => true,
                'noEtag' => true,
                'ignoreMetaHash' => true,
                'ignoreSystemStatusError' => true,
            ),
            'getPublicLanguage' => array(
                'reqType' => 'GET',
                'path' => array('lang', 'public', '?'),
                'pathVars' => array('', '', 'lang'),
                'method' => 'getPublicLanguage',
                'shortHelp' => 'Returns the public labels for the application',
                'longHelp' => 'include/api/html/metadata_all_help.html',
                'noLoginRequired' => true,
                'rawReply' => true,
                'noEtag' => true,
                'ignoreMetaHash' => true,
                'ignoreSystemStatusError' => true,
            ),
        );
    }

    /**
     * Gets a MetaDataManager object
     * @param string $platform The platform to get the manager for
     * @param boolean $public Flag to describe visibility for metadata
     * @return MetaDataManager
     */
    protected function getMetaDataManager($platform = '', $public = false)
    {
        return MetaDataManager::getManager($platform, $public);
    }

    /**
     * Gets the type filter for this request
     * 
     * @param array $args
     * @param array $default
     * @return array
     */
    protected function getTypeFilter(array $args, $default)
    {
        $typeFilter = $default;
        if (!empty($args['type_filter'])) {
            // Explode is fine here, we control the list of types
            $types = explode(",", $args['type_filter']);
            if ($types != false) {
                $typeFilter = $types;
            }
        }

        return $typeFilter;
    }

    /**
     * Gets the module filter for this request
     * 
     * @param array $args
     * @param array $default
     * @return array
     */
    protected function getModuleFilter(array $args, $default)
    {
        $moduleFilter = $default;
        if (!empty($args['module_filter'])) {
            if (function_exists('str_getcsv')) {
                // Use str_getcsv here so that commas can be escaped, I pity the fool that has commas in his module names.
                $modules = str_getcsv($args['module_filter'],',','');
            } else {
                $modules = explode(",", $args['module_filter']);
            }
            
            if ( $modules != false ) {
                $moduleFilter = $modules;
            }
        }

        return $moduleFilter;
    }

    /**
     * Determines whether the request is a hash only metadata request
     * 
     * @param array $args
     * @return bool
     */
    protected function isOnlyHash(array $args)
    {
        return !empty($args['only_hash']) && ($args['only_hash'] == 'true' || $args['only_hash'] == '1');
    }


    /**
     * To massage metadata for backward compatibility.
     *
     * @param ServiceBase $api
     * @param array $args
     * @param array $data
     * @return array
     */
    protected function massageMetaData(ServiceBase $api, array $args, array $data)
    {
        if (empty($args['module_dependencies']) && $api->getVersion() < 11) {
            foreach ($data['modules'] as $module => &$modMeta) {
                // move module level dependencies $modMeta['dependencies'] to each view
                if (!empty($modMeta['dependencies']) && !empty($modMeta['views'])) {
                    foreach ($modMeta['views'] as $view => &$viewMeta) {
                        if (is_array($viewMeta) && !empty($viewMeta['meta'])) {
                            if (!isset($viewMeta['meta']['dependencies']) ||
                                !is_array($viewMeta['meta']['dependencies'])) {
                                $viewMeta['meta']['dependencies'] = array();
                            }
                            foreach ($modMeta['dependencies'] as $dep) {
                                $viewMeta['meta']['dependencies'][] = $dep;
                            }
                        }
                    }
                    unset($modMeta['dependencies']);
                }
            }
        }
        return $data;
    }

    /**
     * Authenticated metadata request endpoint
     * 
     * @param ServiceBase $api
     * @param array $args
     * @return array
     */
    public function getAllMetadata(ServiceBase $api, array $args)
    {
        // Get the metadata manager we need first
        $mm = $this->getMetaDataManager($api->platform);

        // Get the metadata hash if there is one for eTag checking
        $hash = $mm->getMetadataHash(true);

        // ETag that bad boy
        if ($hash && $api->generateETagHeader($hash)) {
            return;
        }

        // Get our metadata now
        $data = $mm->getMetadata($args);

        // handle dependency backward compatiblity
        $data = $this->massageMetaData($api, $args, $data);

        // These are the base metadata sections in private metadata
        $sections = $mm->getSections();

        // Default the type filter to everything, but filter them if requested
        $typeFilter = $this->getTypeFilter($args, $sections);

        // Same with module filtering
        $moduleFilter = $this->getModuleFilter($args, array());

        // Is this a hash only request?
        $onlyHash = $this->isOnlyHash($args);

        // Handle chunking
        $key = array_search('modules', $sections);
        if ($key !== false) {
            unset($sections[$key]);
        }
        $baseChunks = $sections;
        $perModuleChunks = array('modules');

        return $this->filterResults($args, $data, $typeFilter, $onlyHash, $baseChunks, $perModuleChunks, $moduleFilter);
    }

    /**
     * Public metadata request endpoint
     * 
     * @param ServiceBase $api
     * @param array $args
     * @return array
     */
    public function getPublicMetadata(ServiceBase $api, array $args)
    {
        // Get the metadata manager we need for this request
        $mm = $this->getMetaDataManager($api->platform, true);

        // Get the metadata hash if there is one for eTag checking
        $hash = $mm->getMetadataHash(true);

        // ETag that bad boy
        if ($hash && $api->generateETagHeader($hash)) {
            return;
        }

        // Get the metadata now since we'll be needing it
        $data = $mm->getMetadata($args);

        // Public metadata sections, no module info at this time
        $baseChunks = $mm->getSections();
        
        // Set the type filter from the sections
        $typeFilter = $this->getTypeFilter($args, $baseChunks);
        
        // See if this is a hash only request
        $onlyHash = $this->isOnlyHash($args);

        return $this->filterResults($args, $data, $typeFilter, $onlyHash, $baseChunks);
    }

    /*
     * Filters the results for Public and Private Metadata
     * @param array $args the Arguments from the Rest Request
     * @param array $data the data to be filtered
     * @param array $typeFilter the specific sections of metadata we want
     * @param bool  $onlyHash check to return only hashes
     * @param array $baseChunks the chunks we want filtered
     * @param array $perModuleChunks the module chunks we want filtered
     * @param array $moduleFilter the specific modules we want
     */
    protected function filterResults(array $args, $data, $typeFilter, $onlyHash = false, $baseChunks = array(), $perModuleChunks = array(), $moduleFilter = array())
    {
        if ($onlyHash) {
            // The client only wants hashes
            $hashesOnly = array();
            $hashesOnly['_hash'] = $data['_hash'];
            foreach ($baseChunks as $chunk) {
                if (in_array($chunk, $typeFilter) ) {
                    $hashesOnly[$chunk]['_hash'] = $data['_hash'];
                }
            }

            foreach ($perModuleChunks as $chunk) {
                if (in_array($chunk, $typeFilter)) {
                    // We want modules, let's filter by the requested modules and by which hashes match.
                    foreach ($data[$chunk] as $modName => &$modData) {
                        if (empty($moduleFilter) || in_array($modName,$moduleFilter)) {
                            $hashesOnly[$chunk][$modName]['_hash'] = $data[$chunk][$modName]['_hash'];
                        }
                    }
                }
            }

            $data = $hashesOnly;

        } else {
            // The client is being bossy and wants some data as well.
            foreach ($baseChunks as $chunk) {
                if (!in_array($chunk,$typeFilter)
                    || (isset($args[$chunk]) && $args[$chunk] == $data[$chunk]['_hash'])) {
                    unset($data[$chunk]);
                }
            }

            // Relationships are special, they are a baseChunk but also need to pay attention to modules
            if (!empty($moduleFilter) && isset($data['relationships']) ) {
                // We only want some modules, but we want the relationships
                foreach ($data['relationships'] as $relName => $relData) {
                    if ($relName == '_hash') {
                        continue;
                    }
                    if (!in_array($relData['rhs_module'],$moduleFilter)
                        && !in_array($relData['lhs_module'],$moduleFilter)) {
                        unset($data['relationships'][$relName]);
                    } else { 
                        $data['relationships'][$relName]['checked'] = 1;
                    }
                }
            }

            foreach ($perModuleChunks as $chunk) {
                if (!in_array($chunk, $typeFilter)) {
                    unset($data[$chunk]);
                } else {
                    // We want modules, let's filter by the requested modules and by which hashes match.
                    foreach ($data[$chunk] as $modName => &$modData) {
                        if ((!empty($moduleFilter) && !in_array($modName,$moduleFilter))
                            || (isset($args[$chunk][$modName]) && $args[$chunk][$modName] == $modData['_hash'])) {
                            unset($data[$chunk][$modName]);
                            continue;
                        }
                    }
                }
            }
        }

        return $data;
    }

    /**
     * Given a platform and language, returns the language JSON contents
     *
     * @param ServiceBase $api
     * @param array $args
     */
    public function getLanguage(ServiceBase $api, array $args, $public = false)
    {
        $return = '';

        //Since this is a raw response we need to set the content type ourselves.
        $api->getResponse()->setHeader("Content-Type", "application/json");

        // Get the metadata manager we need first
        $mm = $this->getMetaDataManager($api->platform, $public);
        $lang = $mm->getLanguage($args);

        //generate hash
        $hash = md5($lang);

        if (!$api->generateETagHeader($hash, 0)) {
            $return = $lang;
        }

        return $return;
    }

    /**
     * Given a platform and language, returns the public language JSON contents
     * 
     * @param ServiceBase $api
     * @param array $args
     */
    public function getPublicLanguage(ServiceBase $api, array $args)
    {
        return $this->getLanguage($api, $args, true);
    }
}
