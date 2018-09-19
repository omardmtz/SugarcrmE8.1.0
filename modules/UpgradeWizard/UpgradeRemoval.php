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
 * UpgradeRemoval.php
 * 
 * This is the base class to support removing files during an upgrade process.
 * To support custom removal of files during an upgrade process take the following steps:
 * 
 * 1) Extend this class and save the PHP file name to be the same as the class name
 * 2) Override the getFilesToRemove method to return an Array of files/directories to remove
 * 3) Place this PHP file in custom/scripts/files_to_remove directory of your SugarCRM install
 * 
 * The UpgradeRemoval instance will be invoked from the unlinkUpgradeFiles method of uw_utils.php
 */
class UpgradeRemoval
{

    /**
     * @var string minimal version for removal
     */
    public $version = '';

/**
 * getFilesToRemove
 * Return array of files/directories to remove.  Default implementation returns empty array.
 * 
 * @param int $version integer value of original version to be upgraded
 * @return mixed $files Array of files/directories to remove
 */
public function getFilesToRemove($version)
{
	return array();
}

/**
 * processFilesToRemove
 * This method handles removing the array of files/directories specified.
 * 
 * @param mixed $files 
 */
public function processFilesToRemove($files=array())
{
	if(empty($files) || !is_array($files))
	{
		return;
	}	
	
	require_once('include/dir_inc.php');
	
	if(!file_exists('custom/backup'))
	{
	   mkdir_recursive('custom/backup');
	}
	
	foreach($files as $file)
	{		
		if(file_exists($file))
		{
			$this->backup($file);   
			if(is_dir($file))
			{
			  rmdir_recursive($file);	
			} else {
			  unlink($file);
			}
	    }
	}
}


/**
 * backup
 * Private method to handle backing up the file to custom/backup directory
 * 
 * @param $file File or directory to backup to custom/backup directory
 */
protected function backup($file)
{
	$basename = basename($file);
	$basepath = str_replace($basename, '', $file);

	if(!empty($basepath) && !file_exists('custom/backup/' . $basepath))
	{
	   mkdir_recursive('custom/backup/' . $basepath);
	}
	
	if(is_dir($file))
	{
    	copy_recursive($file, 'custom/backup/' . $file);	
	} else {
		copy($file, 'custom/backup/' . $file);
	}
}

}
