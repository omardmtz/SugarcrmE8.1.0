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
 require_once('ModuleInstall/PackageManager/PackageManager.php');
 class PackageController{
        var $_pm;

    /**
     * Constructor: this class is called from the the ajax call and handles invoking the correct
     * functionality on the server.
     */
    public function __construct()
    {
        $this->_pm = new PackageManager();
    }

        function performBasicSearch(){
            $search_term = '';
            $node_id = '';
             if(isset($_REQUEST['search_term'])) {
                $search_term = nl2br($_REQUEST['search_term']);
            }
             if(isset($_REQUEST['node_id'])) {
                $node_id = nl2br($_REQUEST['node_id']);
            }
            $xml = PackageManager::getPackages($node_id);
            $this->sendJsonOutput(array('packages' => $xml));
        }

        /**
         * Retrieve a list of packages which belong to the corresponding category
         *
         * @param category_id   this is passed via POST and is the category id of packages
         *                      we wish to retrieve
         * @return packages     xml string consisting of the packages and releases which belong to
         *                      the category
         */
        function getPackages(){
            $category_id = '';

             if(isset($_REQUEST['category_id'])) {
                $category_id = nl2br($_REQUEST['category_id']);
            }
            $xml = PackageManager::getPackages($category_id);
            $this->sendJsonOutput(array('package_output' => $xml));
        }

        /**
         * Obtain a list of releases from the server.  This function is currently used for generating the patches/langpacks for upgrade wizard
         * as well as during installation
         */
        function getReleases(){
            $category_id = '';
       		$package_id = '';
       		$types = '';
            if(isset($_REQUEST['category_id'])) {
                $category_id = nl2br($_REQUEST['category_id']);
            }
            if(isset($_REQUEST['package_id'])) {
                $package_id = nl2br($_REQUEST['package_id']);
            }
            if(isset($_REQUEST['types'])) {
                $types = nl2br($_REQUEST['types']);
            }
            $types = explode(',', $types);

            $filter = array();
          	$count = count($types);
          	$index = 1;
          	$type_str = '';
          	foreach($types as $type){
          		$type_str .= "'".$type."'";
          		if($index < $count)
          			$type_str .= ",";
          		$index++;
          	}

          	$filter = array('type' => $type_str);
          	$filter = PackageManager::toNameValueList($filter);
            $releases = PackageManager::getReleases($category_id, $package_id, $filter);
            $nodes = array();
            $release_map = array();
            foreach($releases['packages'] as $release){
            	$release = PackageManager::fromNameValueList($release);
				$nodes[] = array('description' => $release['description'], 'version' => $release['version'], 'build_number' => $release['build_number'], 'id' => $release['id']);
        		$release_map[$release['id']] = array('package_id' => $release['package_id'], 'category_id' => $release['category_id']);
        	}
        	$_SESSION['ML_PATCHES'] = $release_map;
            $this->sendJsonOutput(array('releases' => $nodes));
        }

        /**
         * Obtain a promotion from the depot
         */
        function getPromotion(){

            $header = PackageManager::getPromotion();

            $this->sendJsonOutput(array('promotion' => $header));
        }

        /**
         * Download the given release
         *
         * @param category_id   this is passed via POST and is the category id of the release we wish to download
         * @param package_id   this is passed via POST and is the package id of the release we wish to download
         * @param release_id   this is passed via POST and is the release id of the release we wish to download
         * @return bool         true is successful in downloading, false otherwise
         */
        function download(){
            global $sugar_config;
            $package_id = '';
            $category_id  = '';
            $release_id = '';
            if(isset($_REQUEST['package_id'])) {
                $package_id = nl2br($_REQUEST['package_id']);
            }
            if(isset($_REQUEST['category_id'])) {
                $category_id = nl2br($_REQUEST['category_id']);
            }
            if(isset($_REQUEST['release_id'])) {
                $release_id = nl2br($_REQUEST['release_id']);
            }
            $GLOBALS['log']->debug("PACKAGE ID: ".$package_id);
            $GLOBALS['log']->debug("CATEGORY ID: ".$category_id);
            $GLOBALS['log']->debug("RELEASE ID: ".$release_id);
            $result = $this->_pm->download($category_id, $package_id, $release_id);
            $GLOBALS['log']->debug("RESULT: ".print_r($result,true));
            $success = 'false';
            if($result != null){
                $GLOBALS['log']->debug("Performing Setup");
                $this->_pm->performSetup($result, 'module', false);
                $GLOBALS['log']->debug("Complete Setup");
                $success = 'true';
            }
            $this->sendJsonOutput(array('success' => $success));
        }

         /**
         * Retrieve a list of categories that are subcategories to the selected category
         *
         * @param id - the id of the parent_category, -1 if this is the root
         * @return array - a list of categories/nodes which are underneath this node
         */
        function getCategories(){
            $node_id = '';
             if(isset($_REQUEST['category_id'])) {
                $node_id = nl2br($_REQUEST['category_id']);
            }
            $GLOBALS['log']->debug("NODE ID: ".$node_id);
            $nodes = PackageManager::getCategories($node_id);
            $this->sendJsonOutput(array('nodes' => $nodes));
        }

         function getNodes(){
            $category_id = '';
             if(isset($_REQUEST['category_id'])) {
                $category_id = nl2br($_REQUEST['category_id']);
            }
            $GLOBALS['log']->debug("CATEGORY ID: ".$category_id);
            $nodes = PackageManager::getModuleLoaderCategoryPackages($category_id);
            $GLOBALS['log']->debug(var_export($nodes, true));
            $this->sendJsonOutput(array('nodes' => $nodes));
        }

        /**
         * Check the SugarDepot for updates for the given type as passed in via POST
         * @param type      the type to check for
         * @return array    return an array of releases for each given installed object if an update is found
         */
        function checkForUpdates(){
            $type = '';
             if(isset($_REQUEST['type'])) {
                $type = nl2br($_REQUEST['type']);
            }
            $pm = new PackageManager();
            $updates = $pm->checkForUpdates();
            $nodes = array();
			$release_map = array();
            if(!empty($updates)){
	            foreach($updates as $update){
	            	$update = PackageManager::fromNameValueList($update);
	            	$nodes[] = array('label' => $update['name'], 'description' => $update['description'], 'version' => $update['version'], 'build_number' => $update['build_number'], 'id' => $update['id'], 'type' => $update['type']);
					$release_map[$update['id']] = array('package_id' => $update['package_id'], 'category_id' => $update['category_id'], 'type' => $update['type']);
	            }
            }
           //patches
           $filter = array(array('name' => 'type', 'value' => "'patch'"));
            $releases = $pm->getReleases('', '', $filter);
            if(!empty($releases['packages'])){
            	foreach($releases['packages'] as $update){
	            	$update = PackageManager::fromNameValueList($update);
					$nodes[] = array('label' => $update['name'], 'description' => $update['description'], 'version' => $update['version'], 'build_number' => $update['build_number'], 'id' => $update['id'], 'type' => $update['type']);
					$release_map[$update['id']] = array('package_id' => $update['package_id'], 'category_id' => $update['category_id'], 'type' => $update['type']);
	            }
            }
			$_SESSION['ML_PATCHES'] = $release_map;
            $this->sendJsonOutput(array('updates' => $nodes));
        }

        function getLicenseText(){
            $file = '';
            if(isset($_REQUEST['file'])) {
                $file = hashToFile($_REQUEST['file']);
            }
            $GLOBALS['log']->debug("FILE : ".$file);
            $this->sendJsonOutput(array('license_display' => PackageManagerDisplay::buildLicenseOutput($file)));
        }

        /**
         *  build the list of modules that are currently in the staging area waiting to be installed
         */
        function getPackagesInStaging(){
            $packages = $this->_pm->getPackagesInStaging('module');

            $this->sendJsonOutput(array('packages' => $packages));
        }

        /**
         *  build the list of modules that are currently in the staging area waiting to be installed
         */
        function performInstall(){
            $file = '';
             if(isset($_REQUEST['file'])) {
                $file = hashToFile($_REQUEST['file']);
            }
          	if(!empty($file)){
	            $this->_pm->performInstall($file);
			}

            $this->sendJsonOutput(array('result' => 'success'));
        }

        function authenticate(){
            $username = '';
            $password = '';
            $servername = '';
            $terms_checked = '';
            if(isset($_REQUEST['username'])) {
                $username = nl2br($_REQUEST['username']);
            }
            if(isset($_REQUEST['password'])) {
                $password = nl2br($_REQUEST['password']);
            }
       		 if(isset($_REQUEST['servername'])) {
                $servername = $_REQUEST['servername'];
            }
            if(isset($_REQUEST['terms_checked'])) {
                $terms_checked = $_REQUEST['terms_checked'];
                if($terms_checked == 'on')
                	$terms_checked = true;
            }

            if(!empty($username) && !empty($password)){
                $password = md5($password);
                $result = PackageManager::authenticate($username, $password, $servername, $terms_checked);
                if(!is_array($result) && $result == true)
                    $status  = 'success';
                else
                    $status  = $result['faultstring'];
            }else{
                $status  = 'failed';
            }

            $this->sendJsonOutput(array('status' => $status));
        }

        function getDocumentation(){
            $package_id = '';
            $release_id = '';

            if(isset($_REQUEST['package_id'])) {
                $package_id = nl2br($_REQUEST['package_id']);
            }
            if(isset($_REQUEST['release_id'])) {
                $release_id = nl2br($_REQUEST['release_id']);
            }

            $documents = PackageManager::getDocumentation($package_id, $release_id);
            $GLOBALS['log']->debug("DOCUMENTS: ".var_export($documents, true));
            $this->sendJsonOutput(array('documents' => $documents));
        }

        function downloadedDocumentation(){
            $document_id = '';

            if(isset($_REQUEST['document_id'])) {
                $document_id = nl2br($_REQUEST['document_id']);
            }
             $GLOBALS['log']->debug("Downloading Document: ".$document_id);
            PackageManagerComm::downloadedDocumentation($document_id);
            $this->sendJsonOutput(array('result' => 'true'));
        }

        /**
         * Remove metadata files such as foo-manifest
         * Enter description here ...
         * @param unknown_type $file
         * @param unknown_type $meta
         */
        protected function rmMetaFile($file, $meta)
        {
            $metafile = pathinfo($file, PATHINFO_DIRNAME)."/". pathinfo($file, PATHINFO_FILENAME)."-$meta.php";
            if(file_exists($metafile)) {
                unlink($metafile);
            }
        }

 		function remove(){
            $file = '';

            if(isset($_REQUEST['file'])) {
                 $file = urldecode(hashToFile($_REQUEST['file']));
            }
            $GLOBALS['log']->debug("FILE TO REMOVE: ".$file);
            if(!empty($file)){
            	unlink($file);
            	foreach(array("manifest", "icon") as $meta) {
            	    $this->rmMetaFile($file, $meta);
            	}
                $realpath = UploadFile::realpath($file);
                $md5_file = $realpath . '.md5';
            if (file_exists($md5_file)) {
                unlink($md5_file);
            }
            }
            $this->sendJsonOutput(array('result' => 'true'));
        }

    /**
    * Sends output in a JSON format
    * 
    * @param mixed $output
    */
    protected function sendJsonOutput($output)
    {
        $json = getJSONobj();
        header('Content-Type: application/json');
        echo $json->encode($output);
    }
}

