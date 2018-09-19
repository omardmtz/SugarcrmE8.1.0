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
 * sugar_mkdir
 * Call this function instead of mkdir to apply preconfigured permission
 * settings when creating the directory.  This method is basically
 * a wrapper to the PHP mkdir function except that it supports setting
 * the mode value by using the configuration file (if set).  The mode is
 * 0777 by default.
 *
 * @param $pathname - String value of the directory to create
 * @param $mode - The integer value of the permissions mode to set the created directory to
 * @param $recursive - boolean value indicating whether or not to create recursive directories if needed
 * @param $context
 * @return boolean - Returns true on success false on failure
 */
function sugar_mkdir($pathname, $mode=null, $recursive=false, $context='') {
	$mode = get_mode('dir_mode', $mode);

	if ( sugar_is_dir($pathname,$mode) )
	    return true;

	$result = false;
	if(empty($mode))
		$mode = 0777;
	if(empty($context)) {
		$result = @mkdir($pathname, $mode, $recursive);
	} else {
		$result = @mkdir($pathname, $mode, $recursive, $context);
	}

	if($result){
		if(!sugar_chmod($pathname, $mode)){
			return false;
		}
		if(!empty($GLOBALS['sugar_config']['default_permissions']['user'])){
			if(!sugar_chown($pathname)){
				return false;
			}
		}
		if(!empty($GLOBALS['sugar_config']['default_permissions']['group'])){
   			if(!sugar_chgrp($pathname)) {
   				return false;
   			}
		}
	}
	else {
	    $GLOBALS['log']->error("Cannot create directory $pathname cannot be touched");
	}

	return $result;
}

/**
 * sugar_fopen
 * Call this function instead of fopen to apply preconfigured permission
 * settings when creating the the file.  This method is basically
 * a wrapper to the PHP fopen function except that it supports setting
 * the mode value by using the configuration file (if set).  The mode is
 * 0777 by default.
 *
 * @param $filename - String value of the file to create
 * @param $mode - The integer value of the permissions mode to set the created file to
 * @param $$use_include_path - boolean value indicating whether or not to search the the included_path
 * @param $context
 * @return boolean - Returns a file pointer on success, false otherwise
 */
function sugar_fopen($filename, $mode, $use_include_path=false, $context=null){
	//check to see if the file exists, if not then use touch to create it.
	if(!file_exists($filename)){
		sugar_touch($filename);
	}

	if(empty($context)) {
		return fopen($filename, $mode, $use_include_path);
	} else {
		return fopen($filename, $mode, $use_include_path, $context);
	}
}

/**
 * sugar_file_put_contents
 * Call this function instead of file_put_contents to apply preconfigured permission
 * settings when creating the the file.  This method is basically
 * a wrapper to the PHP file_put_contents function except that it supports setting
 * the mode value by using the configuration file (if set).  The mode is
 * 0777 by default.
 *
 * @param $filename - String value of the file to create
 * @param $data - The data to be written to the file
 * @param $flags - int as specifed by file_put_contents parameters
 * @param $context
 * @return int - Returns the number of bytes written to the file, false otherwise.
 */
function sugar_file_put_contents($filename, $data, $flags=null, $context=null){
	//check to see if the file exists, if not then use touch to create it.
	if(!file_exists($filename)){
		sugar_touch($filename);
	}

	if ( !is_writable($filename) ) {
	    $GLOBALS['log']->error("File $filename cannot be written to");
	    return false;
	}

	if(empty($flags)) {
		$return = file_put_contents($filename, $data);
	} elseif(empty($context)) {
        $return = file_put_contents($filename, $data, $flags);
	} else{
        $return = file_put_contents($filename, $data, $flags, $context);
	}

    return $return;
}

/**
 * sugar_file_put_contents_atomic
 * This is an atomic version of sugar_file_put_contents.  It attempts to circumvent the shortcomings of file_put_contents
 * by creating a temporary unique file and then doing an atomic rename operation.
 *
 * @param $filename - String value of the file to create
 * @param $data - The data to be written to the file
 * @param string $mode String value of the parameter to specify the type of access you require to the file stream
 * @param boolean $use_include_path set to '1' or TRUE if you want to search for the file in the include_path too
 * @param context $context Context to pass into fopen operation
 * @return boolean - Returns true if $filename was created, false otherwise.
 */
function sugar_file_put_contents_atomic($filename, $data, $mode='wb', $use_include_path=false, $context=null){

    $dir = dirname($filename);
    $temp = @tempnam($dir, 'temp');

    if ($temp === false || !($f = @fopen($temp, $mode))) {
        // delete the file created by tempnam
        if ($temp) {
            @unlink($temp);
        }
        $temp =  $dir . DIRECTORY_SEPARATOR . uniqid('temp');
        if (!($f = @fopen($temp, $mode))) {
            trigger_error("sugar_file_put_contents_atomic() : error writing temporary file '$temp'", E_USER_WARNING);
            return false;
        }
    }

    fwrite($f, $data);
    fclose($f);

    if (!@rename($temp, $filename))
    {
        @unlink($filename);
        if (!@rename($temp, $filename))
        {
            // cleaning up temp file to avoid filling up temp dir
            @unlink($temp);
            trigger_error("sugar_file_put_contents_atomic() : fatal rename failure '$temp' -> '$filename'", E_USER_ERROR);
        }
    }

    if(file_exists($filename))
    {
        return sugar_chmod($filename);
    }

    return false;
}

/**
 * sugar_file_get_contents
 *
 * @param $filename - String value of the file to create
 * @param $use_include_path - boolean value indicating whether or not to search the the included_path
 * @param $context
 * @return string|boolean - Returns a file data on success, false otherwise
 */
function sugar_file_get_contents($filename, $use_include_path=false, $context=null){
    if (!file_exists($filename)) {
        $GLOBALS['log']->error("File $filename does not exist");
        return false;
	}

	if ( !is_readable($filename) ) {
	    $GLOBALS['log']->error("File $filename cannot be read");
	    return false;
	}

	if(empty($context)) {
		return file_get_contents($filename, $use_include_path);
	} else {
		return file_get_contents($filename, $use_include_path, $context);
	}
}

/**
 * Get the directory name
 *
 * This function is similar to dirname, but dirname does not handle stream path properly.
 *
 * @param string $fileName - The name of the file to check
 * @return string - directory name
 */
function sugarDirname($fileName)
{
    if (strpos($fileName, '://') === false) {
        // regular path, not stream
        return dirname($fileName);
    }

    // stream, find the last /
    $pos = strrpos($fileName, '/');
    $dirName = substr($fileName, 0, $pos + 1);

    return $dirName;
}

/**
 * sugar_touch
 * Attempts to set the access and modification times of the file named in the filename
 * parameter to the value given in time . Note that the access time is always modified,
 * regardless of the number of parameters.  If the file does not exist it will be created.
 * This method is basically a wrapper to the PHP touch method except that created files
 * may be set with the permissions specified in the configuration file (if set).
 *
 * @param $filename - The name of the file being touched.
 * @param $time - The touch time. If time  is not supplied, the current system time is used.
 * @param $atime - If present, the access time of the given filename is set to the value of atime
 * @return boolean - Returns TRUE on success or FALSE on failure.
 *
 */
function sugar_touch($filename, $time=null, $atime=null) {

    if (!empty($GLOBALS['sugar_config']['default_permissions']['dir_mode'])) {
        $dirmode = $GLOBALS['sugar_config']['default_permissions']['dir_mode'];
    } else {
        $dirmode = null;
    }

    $result = sugar_mkdir(sugarDirname($filename), $dirmode, true);

    if (!$result) {
        return $result;
    }

   if(!empty($atime) && !empty($time)) {
   	  $result = @touch($filename, $time, $atime);
   } else if(!empty($time)) {
   	  $result = @touch($filename, $time);
   } else {
   	  $result = @touch($filename);
   }

   if(!$result) {
       $GLOBALS['log']->error("File $filename cannot be touched");
       return $result;
   }
	if(!empty($GLOBALS['sugar_config']['default_permissions']['file_mode'])){
		sugar_chmod($filename, $GLOBALS['sugar_config']['default_permissions']['file_mode']);
	}
	if(!empty($GLOBALS['sugar_config']['default_permissions']['user'])){
		sugar_chown($filename);
	}
	if(!empty($GLOBALS['sugar_config']['default_permissions']['group'])){
		sugar_chgrp($filename);
	}

    return true;
}

/**
 * sugar_chmod
 * Attempts to change the permission of the specified filename to the mode value specified in the
 * default_permissions configuration; otherwise, it will use the mode value.
 *
 * @param  string    filename - Path to the file
 * @param  int $mode The integer value of the permissions mode to set the created directory to
 * @return boolean   Returns TRUE on success or FALSE on failure.
 */
function sugar_chmod($filename, $mode=null) {
	if(!is_windows()){
        if ($mode === null) {
            $mode = get_mode('file_mode', 0660);
		}
        if(isset($mode) && $mode > 0){
		   return @chmod($filename, $mode);
		}else{
	    	return false;
		}
	}
	return true;
}

/**
 * sugar_chown
 * Attempts to change the owner of the file filename to the user specified in the
 * default_permissions configuration; otherwise, it will use the user value.
 *
 * @param filename - Path to the file
 * @param user - A user name or number
 * @return boolean - Returns TRUE on success or FALSE on failure.
 */
function sugar_chown($filename, $user='') {
	if(!is_windows()){
		if(strlen($user)){
			return chown($filename, $user);
		}else{
			if(strlen($GLOBALS['sugar_config']['default_permissions']['user'])){
				$user = $GLOBALS['sugar_config']['default_permissions']['user'];
				return chown($filename, $user);
			}else{
				return false;
			}
		}
	}
	return true;
}

/**
 * sugar_chgrp
 * Attempts to change the group of the file filename to the group specified in the
 * default_permissions configuration; otherwise it will use the group value.
 *
 * @param filename - Path to the file
 * @param group - A group name or number
 * @return boolean - Returns TRUE on success or FALSE on failure.
 */
function sugar_chgrp($filename, $group='') {
	if(!is_windows()){
		if(!empty($group)){
			return chgrp($filename, $group);
		}else{
			if(!empty($GLOBALS['sugar_config']['default_permissions']['group'])){
				$group = $GLOBALS['sugar_config']['default_permissions']['group'];
				return chgrp($filename, $group);
			}else{
				return false;
			}
		}
	}
	return true;
}

/**
 * get_mode
 *
 * Will check to see if there is a default mode defined in the config file, otherwise return the
 * $mode given as input
 *
 * @param int $mode - the mode being passed by the calling function. This value will be overridden by a value
 * defined in the config file.
 * @return int - the mode either found in the config file or passed in via the input parameter
 */
function get_mode($key = 'dir_mode', $mode=null) {
	if ( !is_int($mode) )
        $mode = (int) $mode;
	if(!is_windows()){
		$conf_inst=SugarConfig::getInstance();
		$mode = $conf_inst->get('default_permissions.'.$key, $mode);
	}
	return $mode;
}

function sugar_is_dir($path, $mode='r'){
		if(defined('TEMPLATE_URL'))return is_dir($path, $mode);
		return is_dir($path);
}

function sugar_is_file($path, $mode='r'){
		if(defined('TEMPLATE_URL'))return is_file($path, $mode);
		return is_file($path);
}

/**
 * Get filename in cache directory
 * @api
 * @param string $file
 */
function sugar_cached($file)
{
    static $cdir = null;
    if(empty($cdir) && !empty($GLOBALS['sugar_config']['cache_dir'])) {
        $cdir = rtrim($GLOBALS['sugar_config']['cache_dir'], '/\\');
    }
    if(empty($cdir)) {
        $cdir = "cache";
    }
    return "$cdir/$file";
}
