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

include_once 'include/utils/array_utils.php';

/**
 * Convert all \ to / in path, remove multiple '/'s and '/./'
 * @param string $path
 * @return string
 */
function clean_path( $path )
{
    // clean directory/file path with a functional equivalent
    $appendpath = '';
    if ( is_windows() && strlen($path) >= 2 && $path[0].$path[1] == "\\\\" ) {
        $path = substr($path,2);
        $appendpath = "\\\\";
    }
    $path = str_replace( "\\", "/", $path );
    $path = str_replace( "//", "/", $path );
    $path = str_replace( "/./", "/", $path );
    return( $appendpath.$path );
}

/**
 * Checks if the given path is a valid destination for package files
 *
 * @param string $path
 * @return bool
 */
function isValidCopyPath($path)
{
    $path = str_replace('\\', '/', $path);

    // check if path is absolute
    if ($path === '' || $path[0] === '/') {
        return false;
    }

    // additionally check if path starts with a drive letter for Windows
    if (is_windows() && preg_match('/^[a-z]:/i', $path)) {
        return false;
    }

    // check if path contains reference to parent directory
    if (preg_match('/(^|\/)\.\.(\/|$)/', $path)) {
        return false;
    }

    return true;
}

function create_cache_directory($file)
{
    $paths = explode('/',$file);
    $dir = rtrim(sugar_cached(""), '/');
    if(!file_exists($dir))
    {
        sugar_mkdir($dir, 0775);
    }
    for($i = 0; $i < sizeof($paths) - 1; $i++)
    {
        $dir .= '/' . $paths[$i];
        if(!file_exists($dir))
        {
            sugar_mkdir($dir, 0775);
        }
    }
    return $dir . '/'. $paths[sizeof($paths) - 1];
}

/**
 * @deprecated
 * Use ModuleInstaller::getModuleDirs
 * @return string
 */
function get_module_dir_list()
{
	$modules = array();
	$path = 'modules';
	$d = dir($path);
	while($entry = $d->read())
	{
		if($entry != '..' && $entry != '.')
		{
			if(is_dir($path. '/'. $entry))
			{
				$modules[$entry] = $entry;
			}
		}
	}
	return $modules;
}

function mk_temp_dir( $base_dir, $prefix="" )
{
    $temp_dir = tempnam( $base_dir, $prefix );
    if( !$temp_dir || !unlink( $temp_dir ) )
    {
        return( false );
    }

    if( sugar_mkdir( $temp_dir ) ){
        return( $temp_dir );
    }

    return( false );
}

function remove_file_extension( $filename )
{
    return( substr( $filename, 0, strrpos($filename, ".") ) );
}

function write_array_to_file( $the_name, $the_array, $the_file, $mode="w", $header='' )
{
    if(!empty($header) && ($mode != 'a' || !file_exists($the_file))){
		$the_string = $header;
	}else{
    	$the_string =   "<?php\n" .
                    '// created: ' . date('Y-m-d H:i:s') . "\n";
	}
    $the_string .=  "\$$the_name = " .
                    var_export_helper( $the_array ) .
                    ";";

    return sugar_file_put_contents_atomic($the_file, $the_string);
}

/**
 * write an array to a file with format as $the_name[$key] = $value,
 * @param $the_name
 * @param $the_array
 * @param $the_file
 * @param string $mode
 * @param string $header
 * @return bool
 */
function write_array_to_file_as_key_value_pair($the_name, $the_array, $the_file, $mode = "w", $header = '')
{
    if (!empty($header) && ($mode != 'a' || !file_exists($the_file))) {
        $the_string = $header;
    } else {
        $the_string =   "<?php\n" .
            '// created: ' . date('Y-m-d H:i:s') . "\n";
    }

    $arrayName = "\$$the_name";
    foreach ($the_array as $key => $value) {
        $the_string .= $arrayName . "['$key'] = " . var_export_helper($value) . ";" . "\n";
    }

    return sugar_file_put_contents_atomic($the_file, $the_string);
}

function write_encoded_file( $soap_result, $write_to_dir, $write_to_file="" )
{
    // this function dies when encountering an error -- use with caution!
    // the path/file is returned upon success



    if( $write_to_file == "" )
    {
        $write_to_file = $write_to_dir . "/" . $soap_result['filename'];
    }

    $file = $soap_result['data'];
    $write_to_file = str_replace( "\\", "/", $write_to_file );

    $dir_to_make = dirname( $write_to_file );
    if( !is_dir( $dir_to_make ) )
    {
        mkdir_recursive( $dir_to_make );
    }
    $fh = sugar_fopen( $write_to_file, "wb" );
    fwrite( $fh, base64_decode( $file ) );
    fclose( $fh );

    if( md5_file( $write_to_file ) != $soap_result['md5'] )
    {
        die( "MD5 error after writing file $write_to_file" );
    }
    return( $write_to_file );
}

function create_custom_directory($file)
{
    $paths = explode('/',$file);
    $dir = 'custom';
    if(!file_exists($dir))
    {
        sugar_mkdir($dir, 0755);
    }
    for($i = 0; $i < sizeof($paths) - 1; $i++)
    {
        $dir .= '/' . $paths[$i];
        if(!file_exists($dir))
        {
            sugar_mkdir($dir, 0755);
        }
    }
    return $dir . '/'. $paths[sizeof($paths) - 1];
}

/**
 * This function will recursively generates md5s of files and returns an array of all md5s.
 *
 * @param	$path The path of the root directory to scan - must end with '/'
 * @param	$ignore_dirs array of filenames/directory names to ignore running md5 on - default 'cache' and 'upload'
 * @result	$md5_array an array containing path as key and md5 as value
 */
function generateMD5array($path, $ignore_dirs = array('cache', 'upload'))
{
	$dh  = opendir($path);
	while (false !== ($filename = readdir($dh)))
	{
		$current_dir_content[] = $filename;
	}

	// removes the ignored directories
	$current_dir_content = array_diff($current_dir_content, $ignore_dirs);

	sort($current_dir_content);
	$md5_array = array();

	foreach($current_dir_content as $file)
	{
		// make sure that it's not dir '.' or '..'
		if(strcmp($file, ".") && strcmp($file, ".."))
		{
			if(is_dir($path.$file))
			{
				// For testing purposes - uncomment to see all files and md5s
				//echo "<BR>Dir:  ".$path.$file."<br>";
				//generateMD5array($path.$file."/");

				$md5_array += generateMD5array($path.$file."/", $ignore_dirs);
			}
			else
			{
				// For testing purposes - uncomment to see all files and md5s
				//echo "   File: ".$path.$file."<br>";
				//echo md5_file($path.$file)."<BR>";

				$md5_array[$path.$file] = md5_file($path.$file);
			}
		}
	}

	return $md5_array;

}

/**
 * Function to compare two directory structures and return the items in path_a that didn't match in path_b
 *
 * @param	$path_a The path of the first root directory to scan - must end with '/'
 * @param	$path_b The path of the second root directory to scan - must end with '/'
 * @param	$ignore_dirs array of filenames/directory names to ignore running md5 on - default 'cache' and 'upload'
 * @result	array containing all the md5s of everything in $path_a that didn't have a match in $path_b
 */
function md5DirCompare($path_a, $path_b, $ignore_dirs = array('cache', 'upload'))
{
	$md5array_a = generateMD5array($path_a, $ignore_dirs);
	$md5array_b = generateMD5array($path_b, $ignore_dirs);

	$result = array_diff($md5array_a, $md5array_b);

	return $result;
}

/**
 * Function to retrieve all file names of matching pattern in a directory (and it's subdirectories)
 * example: getFiles($arr, './modules', '.+/EditView.php/'); // grabs all EditView.phps
 * @param array $arr return array to populate matches
 * @param string $dir directory to look in [ USE ./ in front of the $dir! ]
 * @param regex $pattern optional pattern to match against
 */
function getFiles(&$arr, $dir, $pattern = null) {
	if(!is_dir($dir))return;
 	$d = dir($dir);
 	while($e =$d->read()){
 		if(substr($e, 0, 1) == '.')continue;
 		$file = $dir . '/' . $e;
 		if(is_dir($file)){
 			getFiles($arr, $file, $pattern);
 		}else{
 			if(empty($pattern)) $arr[] = $file;
                else if(preg_match($pattern, $file))
                $arr[] = $file;
 		}
 	}
}

/**
 * Function to split up large files for download
 * used in download.php
 * @param string $filename
 * @param int $retbytes
 */
function readfile_chunked($filename,$retbytes=true)
{
   	$chunksize = 1*(1024*1024); // how many bytes per chunk
	$buffer = '';
	$cnt = 0;
	$handle = sugar_fopen($filename, 'rb');
	if ($handle === false)
	{
	    return false;
	}
	while (!feof($handle))
	{
	    $buffer = fread($handle, $chunksize);
	    echo $buffer;
	    flush();
	    if ($retbytes)
	    {
	        $cnt += strlen($buffer);
	    }
	}
	    $status = fclose($handle);
	if ($retbytes && $status)
	{
	    return $cnt; // return num. bytes delivered like readfile() does.
	}
	return $status;
}
/**
 * Renames a file. If $new_file already exists, it will first unlink it and then rename it.
 * used in SugarLogger.php
 * @param string $old_filename
 * @param string $new_filename
 */
function sugar_rename( $old_filename, $new_filename){
	if (empty($old_filename) || empty($new_filename)) return false;
	$success = false;
    if (file_exists($new_filename)) {
        unlink($new_filename);
    	$success = rename($old_filename, $new_filename);
	}
	else {
		$success = rename($old_filename, $new_filename);
	}

	return $success;
}

function fileToHash($file){
		$hash = md5($file);
		$_SESSION['file2Hash'][$hash] = $file;
		return $hash;
	}

function hashToFile($hash){
		if(!empty($_SESSION['file2Hash'][$hash])){
			return $_SESSION['file2Hash'][$hash];
		}
		return false;
}



/**
 * get_file_extension
 * This function returns the file extension portion of a given filename
 *
 * @param $filename String of filename to return extension
 * @param $string_to_lower boolean value indicating whether or not to return value as lowercase, true by default
 *
 * @return extension String value, blank if no extension found
 */
function get_file_extension($filename, $string_to_lower=true)
{
    if(strpos($filename, '.') !== false)
    {
        $filenameParts = explode('.', $filename);
        $extension = array_pop($filenameParts);
        return $string_to_lower ? strtolower($extension) : $extension;
    }

    return '';
}


/**
 * get_mime_content_type_from_filename
 * This function is similar to mime_content_type, but does not require a real
 * file or path location.  Instead, the function merely checks the filename
 * extension and returns a best guess mime content type.
 *
 * @param $filename String of filename to return mime content type
 * @return mime content type as String value (defaults to 'application/octet-stream' for filenames with extension, empty otherwise)
 *
 */
function get_mime_content_type_from_filename($filename)
{
	if(strpos($filename, '.') !== false)
	{
        $mime_types = array(
            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        $ext = get_file_extension($filename, true);
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        }

        return 'application/octet-stream';
	}

    return '';
}

function cleanFileName($name)
{
    return preg_replace('/[^\w-._]+/i', '', $name);
}

/**
 * Filter dir name to not contain path components - no slashes, no .., etc.
 * @param string $name
 * @return string
 */
function cleanDirName($name)
{
    return str_replace(array("\\", "/", "."), "", $name);
}

/**
 * Attempts to use PHPs mime type getters, where available, to get the content
 * type of a file. Checks existence of the file
 *
 * @param string $file The filepath to get the mime type for
 * @param string $default An optional mimetype string to return if one cannot be found
 * @return string The string content type of the file or false if the file does
 *                not exist
 */
function get_file_mime_type($file, $default = false)
{
    // If the file is readable then use it
    if (is_readable($file)) {
        $file = UploadFile::realpath($file);
        // Default the return
        $mime = '';

        // Check first for the finfo functions needed to use built in functions
        // Suppressing warnings since some versions of PHP are choking on
        // getting the mime type by reading the file contents even though the
        // file is readable
        if (mime_is_detectable_by_finfo()) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            if ($finfo) {
                $mime = @finfo_file($finfo, $file);
                finfo_close($finfo);
            }
        } else  {
            // Fall back to our regular way of doing it
            if (function_exists('mime_content_type')) {
                $mime = @mime_content_type($file);
            } elseif (function_exists('ext2mime')) {
                $mime = @ext2mime($file);
            }
        }

        // If mime is empty, set it manually... this can happen from either not
        // being able to detect the mime type using core PHP functions or in the
        // case of a failure of one of the core PHP functions for some reason
        if (empty($mime)) {
            $mime = 'application/octet-stream';
        }

        return $mime;
    }

    return $default;
}

/**
 * Helper function to determine whether the functions needed to fetch a mime type
 * natively with PHP exist.
 *
 * @return bool
 */
function mime_is_detectable()
{
    $fallback = function_exists('mime_content_type') || function_exists('ext2mime');
    return mime_is_detectable_by_finfo() || $fallback;
}

/**
 * Helper function to determine whether the native PHP FileInfo functions are
 * available. FileInfo is now included with PHP 5.3+. Prior to that it was a
 * PECL extension.
 * http://us2.php.net/manual/en/fileinfo.installation.php
 *
 * @return bool
 */
function mime_is_detectable_by_finfo()
{
    return function_exists('finfo_open') && function_exists('finfo_file') && function_exists('finfo_close');
}
