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

class SugarMinifyUtils
{
    /**get_exclude_files
     *
     * This method returns a predefined array.
     * The array holds the location of files/folders to be excluded
     * if a prefix is passed in, then it is prepended to the key value in the array
     * @prefix string to be prepended to key value in array
     */
    protected function get_exclude_files($prefix = ''){
        //add slash to prefix if it is not empty
        if (!empty($prefix)){
            $prefix = $prefix . '/';
        }
        //add prefix to key if it was passed in
        $compress_exempt_files = array(
            $prefix.sugar_cached('')                => true,
            $prefix.'include/javascript/nvd3/lib/d3.min.js' => true,
            $prefix.'include/javascript/nvd3/nv.d3.min.js' => true,
            $prefix.'include/javascript/d3-sugar/d3-sugar.min.js' => true,
            $prefix.'include/javascript/sucrose/sucrose.min.js' => true,
            $prefix.'include/javascript/tiny_mce'   => true,
            $prefix.'include/javascript/yui'        => true,
            $prefix.'modules/Emails'                => true,
            $prefix.'jssource'                      => true,
            $prefix.'modules/ModuleBuilder'         => true,
            $prefix.'tests/PHPUnit/PHP/CodeCoverage/Report/HTML/Template' => true,
            $prefix.'tests/jssource/minify/expect'  => true,
            $prefix.'tests/jssource/minify/test'    => true,
            $prefix.'sidecar'                       => true,
            $prefix.'styleguide'                    => true,
        );
        return $compress_exempt_files;
    }

    /**
     * Gets the js_groupings array for use in the concatenation process
     *
     * @return array
     */
    protected function getJSGroupings()
    {
        $js_groupings = array();
        if(isset($_REQUEST['root_directory'])){
            require('jssource/JSGroupings.php');
        } else {
            require('JSGroupings.php');
            require_once('jsmin.php');
        }
        return $js_groupings;
    }

    /**ConcatenateFiles($from_path)
     *
     * This method takes in a string value of the root directory to begin processing
     * it uses the predefined array of groupings to create a concatenated file for each grouping
     * and places the concatenated file in root directory
     * @from_path root directory where processing should take place
     */
    public function ConcatenateFiles($from_path){
        global $sugar_config;

        // Minifying the group files takes a long time sometimes.
        @ini_set('max_execution_time', 0);
        $js_groupings = $this->getJSGroupings();

        // Get the files that are not meant to be minified
        $excludedFiles = $this->get_exclude_files($from_path);

        // For each item in the $js_groupings array (from JSGroupings.php),
        // concatenate the source files into the target file
        foreach ($js_groupings as $fg) {
            // List of files to build into one
            $buildList = array();

            // Default the permissions to the most restrictive to start
            $currPerm = 0;

            // Process each group array. $loc is the file to read in, $trgt is
            // the concatenated file
            foreach($fg as $loc=>$trgt){
                $already_minified = preg_match('/[\.\-]min\.js$/', $loc);
                if (preg_match('/[\.\-]min\.js$/', $loc)) {
                    $already_minified = true;
                } else {
                    $minified_loc = str_replace('.js', '-min.js', $loc);
                    if (is_file($minified_loc)) {
                        $loc = $minified_loc;
                        $already_minified = true;
                    }
                }
                $relpath = $loc;
                $loc = $from_path.'/'.$loc;

                $trgt = sugar_cached($trgt);
                //check to see that source file is a file, and is readable.
                if(is_file($loc) && is_readable($loc)){
                    // Build a file perm based on the loosest file being read in
                    $tPerm = fileperms($loc);
                    if ($tPerm !== false && $tPerm > $currPerm) {
                        $currPerm = $tPerm;
                    }

                    //make sure we have handles to both source and target file
                    $content = file_get_contents($loc);
                    //Skip minifying files in exclude list and already minified files
                    if (!$already_minified && !isset($excludedFiles[$loc])) {
                        try {
                            $content = SugarMin::minify($content);
                        } catch (RuntimeException  $e) {
                            //Use unminified $buffer instead
                        }
                    }
                    $content .= "\n/* End of File $relpath */\n\n";
                    $buildList[] = $content;
                }
            }

            // Ensure target directory exists
            $targetDir = dirname($trgt);
            if (!file_exists($targetDir)) {
                mkdir_recursive($targetDir);
            }

            // Build the file now using atomic write
            $contents = implode("", $buildList);
            sugar_file_put_contents_atomic($trgt, $contents);

            // And handle permissions like the way we used to do it
            $func = function_exists('sugar_chmod') ? 'sugar_chmod' : 'chmod';

            // Get a default permission, from config if possible
            if (isset($sugar_config['default_permissions']['file_mode'])) {
                $defaultPerm = $sugar_config['default_permissions']['file_mode'];
            } else {
                $defaultPerm = 0777;
            }

            // Handle permission value here
            $newPerm = $currPerm ? $currPerm : $defaultPerm;

            // Set the perms for the new file
            @$func($trgt, $newPerm);
        }
    }

    protected function create_backup_folder($bu_path){
        $bu_path = str_replace('\\', '/', $bu_path);
        //get path after root
        $jpos = strpos($bu_path,'jssource');
        if($jpos===false){
            $process_path = $bu_path;
        }else{
            $process_path = substr($bu_path, $jpos);
            $prefix_process_path = substr($bu_path, 0, $jpos-1);
        }
        //get rest of directories into array
        $bu_dir_arr = explode('/', $process_path);

        //iterate through each directory and create if needed

        foreach($bu_dir_arr as $bu_dir){
            if(!file_exists($prefix_process_path.'/'.$bu_dir)){
                if(function_exists('sugar_mkdir')){
                    sugar_mkdir($prefix_process_path.'/'.$bu_dir);
                }else{
                    mkdir($prefix_process_path.'/'.$bu_dir);
                }
            }
            $prefix_process_path = $prefix_process_path.'/'.$bu_dir;
        }

    }

    /**CompressFiles
     * This method will call jsmin libraries to minify passed in files
     * This method takes in 2 string values of the files to process
     * Processing will back up javascript files and then minify the original javascript.
     * Back up javascript files will have an added .src extension
     * @from_path file name and path to be processed
     * @to_path file name and path to be  used to place newly compressed contents
     */
    public function CompressFiles($from_path,$to_path){
        if(!defined('JSMIN_AS_LIB')){
            define('JSMIN_AS_LIB', true);
        }
        //assumes jsmin.php is in same directory
        if(isset($_REQUEST['root_directory']) || defined('INSTANCE_PATH')){
        }else{
            require_once('jsmin.php');
        }
        $nl='
     ';

        //check to make sure from path and to path are not empty
        if(isset($from_path) && !empty($from_path)&&isset($to_path) && !empty($to_path)){
            $lic_str = '';
            $ReadNextLine = true;
            // Output a minified version of example.js.
            if(file_exists($from_path) && is_file($from_path)){
                //read in license script
                if(function_exists('sugar_fopen')){
                    $file_handle = sugar_fopen($from_path, 'r');
                }else{
                    $file_handle = fopen($from_path, 'r');
                }
                if($file_handle){
                    $beg = false;

                    //Read the file until you hit a line with code.  This is meant to retrieve
                    //the initial license string found in the beginning comments of js code.
                    while (!feof($file_handle) && $ReadNextLine) {
                        $newLine = fgets($file_handle, 4096);
                        $newLine = trim($newLine);
                        //See if line contains open or closing comments

                        //if opening comments are found, set $beg to true
                        if(strpos($newLine, '/*')!== false){
                            $beg = true;
                        }

                        //if closing comments are found, set $beg to false
                        if(strpos($newLine, '*/')!== false){
                            $beg = false;
                        }

                        //if line is not empty (has code) set the boolean to false
                        if(! empty($newLine)){$ReadNextLine = false;}
                        //If we are in a comment block, then set boolean back to true
                        if($beg){
                            $ReadNextLine = true;
                            //add new line to license string
                            $lic_str .=trim($newLine).$nl;
                        }else{
                            //if we are here it means that uncommented and non blank line has been reached
                            //Check to see that ReadNextLine is true, if so then add the last line collected
                            //make sure the last line is either the end to a comment block, or starts with '//'
                            //else do not add as it is live code.
                            if(!empty($newLine) && ((strpos($newLine, '*/')!== false) || ($newLine{0}.$newLine{1}== '//'))){
                                //add new line to license string
                                $lic_str .=$newLine;
                            }
                            //set to false because $beg is false, which means the comment block has ended
                            $ReadNextLine = false;

                        }
                    }

                }
                if($file_handle){
                    fclose($file_handle);
                }

                //place license string into array for use with jsmin file.
                //this will preserve the license in the file
                $lic_arr = array($lic_str);

                //minify javascript
                //$jMin = new JSMin($from_path,$to_path,$lic_arr);
                if(strpos($from_path, '-min.js') !== FALSE || strpos($from_path, '.min.js') !== FALSE) {
                    $min_file = $from_path;
                } else {
                    $min_file = str_replace('.js', '-min.js', $from_path);
                    if(!is_file($min_file)) {
                        $min_file = str_replace('.js', '.min.js', $from_path);
                    }
                    // Bootstrap is funky with their semicolons.
                    if(strpos($from_path, 'bootstrap') !== FALSE) {
                        $min_file = $from_path;
                    }
                }

                if(is_file($min_file)) {
                    $out = file_get_contents($min_file);
                } else {
                    try {
                        $out = $lic_str . SugarMin::minify(file_get_contents($from_path));
                    } catch (RuntimeException  $e) {
                        $out = file_get_contents($from_path);
                    }
                }

            	if(function_exists('sugar_fopen') && $fh = @sugar_fopen( $to_path, 'w' ) )
			    {
			        fputs( $fh, $out);
			        fclose( $fh );
				} else {
				    file_put_contents($to_path, $out);
				}

            }else{
                 //log failure
                 echo"<B> COULD NOT COMPRESS $from_path, it is not a file \n";
            }

        }else{
         //log failure
         echo"<B> COULD NOT COMPRESS $from_path, missing variables \n";
        }
    }

    public function reverseScripts($from_path,$to_path=''){
        $from_path = str_replace('\\', '/', $from_path);
        if(empty($to_path)){
            $to_path = $from_path;
        }
        $to_path = str_replace('\\', '/', $to_path);

        //check to see if provided paths are legit

        if (!file_exists($from_path)){
            //log error
            echo "JS Source directory at $from_path Does Not Exist<p>\n";
            return;
        }

        //get correct path for backup
        $bu_path = $to_path;
        $bu_path .= substr($from_path, strlen($to_path.'/jssource/src_files'));

        //if this is a directory, then read it and process files
        if(is_dir($from_path)){
            //grab file / directory and read it.
            $handle = opendir($from_path);
            //loop over the directory and go into each child directory
            while (false !== ($dir = readdir($handle))) {
                //make sure you go into directory tree and not out of tree
                if($dir!= '.' && $dir!= '..'){
                    //make recursive call to process this directory
                    $this->reverseScripts($from_path.'/'.$dir, $to_path );
                }
            }
        }

        //if this is not a directory, then
        //check if this is a javascript file, then process
        $path_parts = pathinfo($from_path);
        if(is_file("$from_path") && isset($path_parts['extension']) && $path_parts['extension'] =='js'){

            //create backup directory if needed
            $bu_dir = dirname($bu_path);

            if(!file_exists($bu_dir)){
                //directory does not exist, log it and return
                echo" directory $bu_dir does not exist, could not restore $bu_path";
                return;
            }

            //delete backup src file if it exists already
            if(file_exists($bu_path)){
                unlink($bu_path);
            }
            copy($from_path, $bu_path);
        }
    }

    /**BackUpAndCompressScriptFiles
     *
     * This method takes in a string value of the root directory to begin processing
     * it will process and iterate through all files and subdirectories
     * under the passed in directory, ignoring directories and files from the predefined exclude array.
     * Processing includes calling a method that will minify the javascript children files
     * @from_path root directory where processing should take place
     * @to_path root directory where processing should take place, this gets filled in dynamically
     */
    public function BackUpAndCompressScriptFiles($from_path,$to_path = '', $backup = true){
        //check to see if provided paths are legit
        if (!file_exists($from_path)){
            //log error
            echo "The from directory, $from_path Does Not Exist<p>\n";
            return;
        }else{
            $from_path = str_replace('\\', '/', $from_path);
        }

        if(empty($to_path)){
            $to_path = $from_path;
        }elseif (!file_exists($to_path))
        {
            //log error
            echo "The to directory, $to_path Does Not Exist<p>\n";
            return;
        }

        //now grab list of files to exclude from minifying
        $exclude_files = $this->get_exclude_files($to_path);

        //process only if file/directory is not in exclude list
        if(!isset($exclude_files[$from_path])){

            //get correct path for backup
            $bu_path = $to_path.'/jssource/src_files';
            $bu_path .= substr($from_path, strlen($to_path));

            //if this is a directory, then read it and process files
            if(is_dir("$from_path")){
                //grab file / directory and read it.
                $handle = opendir("$from_path");
                //loop over the directory and go into each child directory
                while (false !== ($dir = readdir($handle))) {

                    //make sure you go into directory tree and not out of tree
                    if($dir!= '.' && $dir!= '..'){
                        //make recursive call to process this directory
                        $this->BackUpAndCompressScriptFiles($from_path.'/'.$dir, $to_path,$backup);
                    }
                }
            }


            //if this is not a directory, then
            //check if this is a javascript file, then process
            // Also, check if there's a min counterpart, in which case, don't use this file.
            $path_parts = pathinfo($from_path);
            if(is_file("$from_path") && isset($path_parts['extension']) && $path_parts['extension'] =='js'){
                /*$min_file_path = $path_parts['dirname'].'/'.$path_parts['filename'].'-min.'.$path_parts['extension'];
                if(is_file($min_file_path)) {
                    $from_path = $min_file_path;
                }*/
                if($backup){
                    $bu_dir = dirname($bu_path);
                    if(!file_exists($bu_dir)){
                        $this->create_backup_folder($bu_dir);
                    }

                    //delete backup src file if it exists already
                    if(file_exists($bu_path)){
                        unlink($bu_path);
                    }
                    //copy original file into a source file
                      rename($from_path, $bu_path);
                }else{
                    //no need to backup, but remove file that is about to be copied
                    //if it exists in both backed up scripts and working directory
                    if(file_exists($from_path) && file_exists($bu_path)){unlink($from_path);}
                }

                //now make call to minify and overwrite the original file.
                $this->CompressFiles($bu_path, $from_path);
            }
        }
    }

}
