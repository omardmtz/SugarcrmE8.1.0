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
/**
 * SugarMerge wraps around all the merge functionality of Sugar given a module name and the path to an unzipped patch
 *
 */
class SugarMerge {
	private $mergeMapping = array();
	private $new_path = '';
	private $custom_path = 'custom';
	private $original_path = '';
	private $merged = array();
	private $fp = NULL;

    public function __construct($new_path='', $original_path='', $custom_path='custom', $includeFromNew = false)
    {

		$this->new_path = empty($new_path) || preg_match('/[\/]$/', $new_path) ? $new_path : $new_path . '/';
		$this->original_path = empty($original_path) || preg_match('/[\/]$/', $original_path) ? $original_path : $original_path . '/';
		$this->custom_path = empty($custom_path) || preg_match('/[\/]$/', $custom_path) ? $custom_path : $custom_path . '/';
        $includePrepend = $includeFromNew ? $this->new_path : "";
        //Use the new Merge classes if requested
        $mergeFiles = array(
            'modules/UpgradeWizard/SugarMerge/MergeUtils.php',
            'modules/UpgradeWizard/SugarMerge/EditViewMerge.php',
            'modules/UpgradeWizard/SugarMerge/DetailViewMerge.php',
            'modules/UpgradeWizard/SugarMerge/ListViewMerge.php',
            'modules/UpgradeWizard/SugarMerge/SearchMerge.php',
            'modules/UpgradeWizard/SugarMerge/QuickCreateMerge.php'
        );
        foreach ($mergeFiles as $mergeFile) {
            if (file_exists($includePrepend . $mergeFile)) {
                require_once($includePrepend . $mergeFile);
            } else {
                require_once($mergeFile);
            }
        }
        require_once('modules/ModuleBuilder/parsers/views/History.php');

		$this->mergeMapping = array(
			'editviewdefs.php'=> new EditViewMerge(),
			'detailviewdefs.php'=>new DetailViewMerge(),
			'listviewdefs.php'=>new ListViewMerge(),
			'searchdefs.php'=>new SearchMerge(),
			'quickcreatedefs.php'=>new QuickCreateMerge(),
		);
	}

	function setLogFilePointer($fp){
		$this->fp = $fp;
	}



	/**
	 * This will run through all the modules that may need merging and determine if there is anything to merge
	 * if $merge is set to true it will perform the merge
	 * if $merge and $save are set to true it will perform the merge and save the results in the custom directory
	 *
	 * @param BOOLEAN|ARRAY $merge - do we wish to perform the merge if false it will just return a list of files that can be merged.  If an array is passed, only those modules present in the array will be merged.
	 * @param BOOLEAN $save - do we wish to save the merged files to true - $merge must be true for this to apply - otherwise it will simulate merging so you can view the log files of the merge
	 * @param BOOLEAN $logHistory - do we wish to create history entries for any of the merges
	 * @return ARRAY - an associative array of module names to files that were either merged or have the potential to be merged depeneding if $merge and $save  are set to true
	 */
	function mergeAll($merge=true, $save=true, $logHistory=true){
		$this->merged = array();
		$searchDirectory = $this->custom_path;
		if(!preg_match('/[\/]modules$/si', $searchDirectory)) {
		   $searchDirectory .= preg_match('/[\/]$/', $this->custom_path) ? 'modules' : '/modules';
		}

		if(file_exists($searchDirectory)){
			$dir = dir($searchDirectory);
			while($e = $dir->read()){
				if(substr($e , 0, 1) != '.') {
					if(is_dir("{$searchDirectory}/{$e}/metadata")){

                        //lets make sure that the directory matches the case of the module before we pass it in
                        global $moduleList;
                        //lets populate an array with the available modules, and make the key's lowercase
                        $checkModList =  array_combine ($moduleList,$moduleList);
                        $checkModList = array_change_key_case($checkModList);

  						//now lets compare with the current directory.  This accounts for cases in which the directory was created in lowercase
                        if(!empty($checkModList[strtolower($e)])){
                            //directory was lowercase, let's use the right module value
							$e = $checkModList[strtolower($e)];
                        }

					    if( is_array($merge) )
					    {
					        if ( in_array($e,$merge) )
					        	$this->merged[$e] = $this->mergeModule($e, TRUE, $save,$logHistory );
					        else
					        {
					            $this->log("SugarMerge is skipping $e module as filter array passed in but module not specified for merge.");
					            continue;
					        }
					    }
					    else
						  $this->merged[$e] = $this->mergeModule($e, $merge, $save,$logHistory );
					}
				}
			}
		}
		return $this->merged;
	}




	/**
	 * This will merge any files that need merging for a given module
	 * if $merge is set to true it will perform the merge
	 * if $merge and $save are set to true it will perform the merge and save the results in the custom directory
	 *
	 * @param STRING $module - the name of the module to merge files for
	 * @param BOOLEAN $merge - do we wish to perform the merge if false it will just return a list of files that can be merged
	 * @param BOOLEAN $save - do we wish to save the merged files to true - $merge must be true for this to apply - otherwise it will simulate merging so you can view the log files of the merge
	 * @param BOOLEAN $logHistory - do we wish to create history entries for any of the merges
	 * @return ARRAY - an associative array of files that were either merged or have the potential to be merged depeneding if $merge and $save  are set to true
	 */
	function mergeModule($module, $merge = true, $save=true,$logHistory=true){
		$merged = array();
		$path = $this->original_path . 'modules/' . $module . '/metadata/';
		$custom_path = $this->custom_path . 'modules/' . $module . '/metadata/';
		$new_path = $this->new_path . 'modules/' . $module . '/metadata/';
		foreach($this->mergeMapping as $file=>&$object){
			if(file_exists("{$custom_path}{$file}") && file_exists("{$new_path}{$file}")){
				if($merge){
					$merged[$file] = $this->mergeFile($module, $file, $save, $logHistory);
				}else{
					$merged[$file] = true;
				}
			}
		}

		return $merged;

	}

	/**
	 * This function will merge a single file for a module
	 *
	 * @param STRING $module - name of the module
	 * @param STRING $file - name of the file
	 * @param STRING $save - should the merged file be saved to the custom directory
	 * @return BOOLEAN - success or failure of the merge
	 */
	function mergeFile($module, $file, $save=true,$logHistory=true){
		$path = $this->original_path . 'modules/' . $module . '/metadata/';
		$custom_path = $this->custom_path . 'modules/' . $module . '/metadata/';
		$new_path = $this->new_path . 'modules/' . $module . '/metadata/';
		if($this->fp) $this->mergeMapping[$file]->setLogFilePointer($this->fp);
		if(isset($this->mergeMapping[$file]) && file_exists("{$path}{$file}") && file_exists("{$custom_path}{$file}") && file_exists("{$new_path}{$file}")){
		    //Create a log entry of the custom file before it is merged
		    if($logHistory && $save)
		          $this->createHistoryLog($module, "{$custom_path}{$file}",$file);
		    return $this->mergeMapping[$file]->merge($module, "{$path}{$file}", "{$new_path}{$file}", "{$custom_path}{$file}", $save);
		}
		return false;

	}

    /**
	 * Create a history copy of the custom file that will be merged so that it can be access through
	 * studio if admins wish to revert at a later date.
	 *
	 * @param STRING $module - name of the module
	 * @param STRING $file - name of the file
	 * @param STRING $customFile - Path to the custom file that will be merged
	 */
	protected function createHistoryLog($module,$customFile,$file)
	{
	    $historyPath = 'custom/' . MB_HISTORYMETADATALOCATION . "/modules/$module/metadata/$file";
	    $history = new History($historyPath);
	    $timeStamp = $history->append($customFile);
	    $this->log("Created history file after merge with new file: " . $historyPath .'_'.$timeStamp);
	}

	/**
	 * Log a message
	 * @param string $message
	 */
	protected function log($message)
	{
	    $GLOBALS['log']->debug($message);
	}

	/**
	 * Return the custom modules path
	 *
	 * @return STRING directory where custom module files are located
	 */
	function getCustomPath() {
		return $this->custom_path;
	}


	/**
	 * Return the new upgrade modules path
	 *
	 * @return STRING directory where new module files are located
	 */
	function getNewPath() {
		return $this->new_path;
	}


	/**
	 * Return the original modules path
	 *
	 * @return STRING directory where new module files are located
	 */
	function getOriginalPath() {
		return $this->original_path;
	}

}
