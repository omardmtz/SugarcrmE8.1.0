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


function copy_recursive( $source, $dest ){


    if( is_file( $source ) ){
        return( copy( $source, $dest ) );
    }
    if( !sugar_is_dir($dest, 'instance') ){
        sugar_mkdir( $dest );
    }

    $status = true;

    $d = dir( $source );
    if($d === false) {
    	return false;
    }
    while(false !== ($f = $d->read())) {
        if( $f == "." || $f == ".." ) {
            continue;
        }
        $status &= copy_recursive( "$source/$f", "$dest/$f" );
    }
    $d->close();
    return $status;
}

function mkdir_recursive($path, $check_is_parent_dir = false)
{
	if(sugar_is_dir($path, 'instance')) {
		return(true);
	}
	if(sugar_is_file($path, 'instance')) {
	    if(!empty($GLOBALS['log'])) {
		    $GLOBALS['log']->fatal("ERROR: mkdir_recursive(): argument $path is already a file.");
	    }
		return false;
	}

    //make variables with file paths
	$pathcmp = $path = rtrim(clean_path($path), '/');
	$basecmp =$base = rtrim(clean_path(getcwd()), '/');
	if(is_windows()) {
        //make path variable lower case for comparison in windows
	    $pathcmp = strtolower($path);
	    $basecmp = strtolower($base);
	}

    if($basecmp == $pathcmp) {
        return true;
    }
    $base .= "/";
	if(strncmp($pathcmp, $basecmp, strlen($basecmp)) == 0) {
        /* strip current path prefix */
	    $path = substr($path, strlen($base));
	}
	$thePath = '';
	$dirStructure = explode("/", $path);
	if($dirStructure[0] == '') {
	    // absolute path
        $base = '/';
        array_shift($dirStructure);
	}
	if(is_windows()) {
	    if(strlen($dirStructure[0]) == 2 && $dirStructure[0][1] == ':') {
	        /* C: prefix */
	        $base = array_shift($dirStructure)."\\";
	    } elseif ($dirStructure[0][0].$dirStructure[0][1] == "\\\\") {
	        /* UNC absolute path */
	        $base = array_shift($dirStructure)."\\".array_shift($dirStructure)."\\"; // we won't try to mkdir UNC share name
	    }
	}
	foreach($dirStructure as $dirPath) {
		$thePath .= $dirPath."/";
		$mkPath = $base.$thePath;

		if(!is_dir($mkPath )) {
			if(!sugar_mkdir($mkPath)) return false;
		}
	}
	return true;
}

function rmdir_recursive( $path ){
    if( is_file( $path ) ){
        return( unlink( $path ) );
    }
    if( !is_dir( $path ) ){
       	if(!empty($GLOBALS['log'])) {
            $GLOBALS['log']->fatal( "ERROR: rmdir_recursive(): argument $path is not a file or a dir." );
       	}
        return false;
    }

    $status = true;

    $d = dir( $path );
    
    while(($f = $d->read()) !== false){
        if( $f == "." || $f == ".." ){
            continue;
        }
        $status &= rmdir_recursive( "$path/$f" );
    }
    $d->close();
    $rmOk = @rmdir($path);
    if($rmOk === FALSE){
        $GLOBALS['log']->error("ERROR: Unable to remove directory $path");
    }
    return( $status );
}

/**
 * Recursively scans a directory for text files (HTML and text only) and returns 
 * the result as an array.
 * 
 * DEPRECATED. WILL BE REMOVED IN 6.7 -rgonzalez
 * 
 * @deprecated
 * @param $the_dir
 * @param $the_array
 * @return mixed
 */
function findTextFiles( $the_dir, $the_array ){
    if(!is_dir($the_dir)) {
		return $the_array;
	}
	$d = dir($the_dir);
    while (false !== ($f = $d->read())) {
        if( $f == "." || $f == ".." ){
            continue;
        }
        if( is_dir( "$the_dir/$f" ) ){
            // i think depth first is ok, given our cvs repo structure -Bob.
            $the_array = findTextFiles( "$the_dir/$f", $the_array );
        }
        else {
            $mime = get_file_mime_type("$the_dir/$f");
            switch($mime){
                // we take action on these cases
                case "text/html":
                case "text/plain":
                    array_push( $the_array, "$the_dir/$f" );
                    break;
                // we consciously skip these types
                case "application/pdf":
                case "application/x-zip":
                case "image/gif":
                case "image/jpeg":
                case "image/png":
                case "text/rtf":
                    break;
                default:
                    $GLOBALS['log']->info( "no type handler for $the_dir/$f with get_file_mime_type: $mime\n" );
            }
        }
    }
    return( $the_array );
}

function findAllFiles( $the_dir, $the_array, $include_dirs=false, $ext='', $exclude_dir=''){
	// jchi  #24296
	if(!empty($exclude_dir)){
		$exclude_dir = is_array($exclude_dir)?$exclude_dir:array($exclude_dir);
		foreach($exclude_dir as $ex_dir){
			if($the_dir == $ex_dir){
				  return $the_array;
			}
		}
	}
	$the_dir = rtrim($the_dir, "/\\");
	//end
    if(!is_dir($the_dir)) {
		return $the_array;
	}
	$d = dir($the_dir);
    while (false !== ($f = $d->read())) {
        if( $f == "." || $f == ".." ){
            continue;
        }

        if( is_dir( "$the_dir/$f" ) ) {
			// jchi  #24296
			if(!empty($exclude_dir)){
				//loop through array to compare directories..
				foreach($exclude_dir as $ex_dir){
					if("$the_dir/$f" == $ex_dir){
						  continue 2;
					}
				}
			}
			//end

			if($include_dirs) {
				$the_array[] = clean_path("$the_dir/$f");
			}
            $the_array = findAllFiles( "$the_dir/$f", $the_array, $include_dirs, $ext);
        } else {
			if(empty($ext) || preg_match('/'.$ext.'$/i', $f)){
				$the_array[] = "$the_dir/$f";
			}
        }


    }
    rsort($the_array);
    return $the_array;
}

function findAllFilesRelative( $the_dir, $the_array ){
    if(!is_dir($the_dir)) {
		return $the_array;
	}
    $original_dir   = getCwd();
    if(is_dir($the_dir)){
    	chdir( $the_dir );
    	$the_array = findAllFiles( ".", $the_array );
    	if(is_dir($original_dir)){
    		chdir( $original_dir );
    	}
    }
    return( $the_array );
}

/*
 * Obtain an array of files that have been modified after the $date_modified
 *
 * @param the_dir			the directory to begin the search
 * @param the_array			array to hold the results
 * @param date_modified		the date to use when searching for files that have
 * 							been modified
 * @param filter			optional regular expression filter
 *
 * return					an array containing all of the files that have been
 * 							modified since date_modified
 */
function findAllTouchedFiles( $the_dir, $the_array, $date_modified, $filter=''){
    if(!is_dir($the_dir)) {
		return $the_array;
	}
	$d = dir($the_dir);
    while (false !== ($f = $d->read())) {
        if( $f == "." || $f == ".." ){
            continue;
        }
        if( is_dir( "$the_dir/$f" ) ){
            // i think depth first is ok, given our cvs repo structure -Bob.
            $the_array = findAllTouchedFiles( "$the_dir/$f", $the_array, $date_modified, $filter);
        }
        else {
        	$file_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s', filemtime("$the_dir/$f"))) - date('Z'));

        	if(strtotime($file_date) > strtotime($date_modified) && (empty($filter) || preg_match($filter, $f))){
        		 array_push( $the_array, "$the_dir/$f");
        	}
        }
    }
    return $the_array;
}
