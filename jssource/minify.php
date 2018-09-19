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

use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;

if(!defined('sugarEntry'))define('sugarEntry', true);

if (!defined('SUGAR_BASE_DIR')) {
    define('SUGAR_BASE_DIR', str_replace('\\', '/', dirname(__DIR__)));
}

set_include_path(
    dirname(__FILE__) . PATH_SEPARATOR
    . SUGAR_BASE_DIR . PATH_SEPARATOR
    . get_include_path()
);

require_once 'include/SugarObjects/SugarConfig.php';
require_once 'include/utils/array_utils.php';

$minifyUtils = null;

//assumes jsmin.php is in same directory
if (isset($_REQUEST['root_directory'])) {
    require_once 'jssource/minify_utils.php';
} else {
    require_once 'minify_utils.php';
}

//if we are coming from browser

if(isset($_REQUEST['root_directory'])){

    require_once('include/utils/sugar_file_utils.php');

    // get the root directory to process
    $inputValidation = InputValidation::getService();
    $from = $inputValidation->getValidInputRequest('root_directory', 'Assert\File');
    $forceReb = !empty($_REQUEST['force_rebuild']);
    // make sure that the rebuild option has been chosen
    if (isset($_REQUEST['js_rebuild_concat'])){
        if (!$forceReb && $_REQUEST['js_rebuild_concat'] == 'rebuild') {
            //rebuild if files have changed
            $js_groupings = array();
            if (isset($_REQUEST['root_directory'])) {
                require 'jssource/JSGroupings.php';
            } else {
                require 'JSGroupings.php';
            }

            //iterate through array of grouped files
            $grp_array = $js_groupings;//from JSGroupings.php;

            //for each item in array, concatenate the source files
            foreach($grp_array as $grp){
                // check if we have to do a rebuild by comparing JS file timestamps
                foreach($grp as $original =>$concat){

                    // if the original file doesn't exist, skip it (so does the build util)
                    if (!is_file($original)) {
                        continue;
                    }

                    $concat = sugar_cached($concat);

                    // make sure concatenated file is still valid
                    if (is_file($concat)) {
                        // if individual file has been modified date later than modified date of
                        // concatenated file, then force a rebuild
                        if(filemtime($original) > filemtime($concat)){
                            $forceReb = true;
                            //no need to continue, we will rebuild
                            break 2;
                        }
                    }else{
                        //if files are not valid, rebuild as one file could have been deleted
                        $forceReb = true;
                        //no need to continue, we will rebuild
                        break 2;
                    }
                }
            }

        }
        //if boolean has been set, concatenate files
        if ($forceReb) {
            $minifyUtils = new SugarMinifyUtils();
            $minifyUtils->ConcatenateFiles("$from");
        }

    }else{
        //We are only allowing rebuilding of concat files from browser.
    }
    return;
}else{
    //run via command line
    //print_r($argv);
    $from="";

    if(isset($argv[1]) && !empty($argv[1])){
         $from = $argv[1];
    }else{
        //Root Directory was not specified
        echo 'Root Directory Input was not provided';
        return;
    }

    if ($argv[1] != '-?') {
        chdir($from);
        require_once('include/utils.php');
        require_once('include/utils/file_utils.php');
        require_once('include/utils/autoloader.php');
        require_once('include/utils/sugar_file_utils.php');
        require_once('include/dir_inc.php');
    }
    if(!function_exists('sugar_cached')) {
        if ($argv[1] != '-?') {
            require_once($from.'/./include/utils.php');
            require_once($from.'/./include/utils/file_utils.php');
            require_once($from.'/./include/utils/sugar_file_utils.php');
            require_once($from.'/./include/dir_inc.php');
        }
        if(!function_exists('sugar_cached')) {
            function sugar_cached($dir) { return "cache/$dir"; }
        }
    }

    if($argv[1] == '-?'){
        $argv[2] = '-?';
    }
    $minifyUtils = new SugarMinifyUtils();

    //if second argument is set, then process commands
    if(!empty($argv[2])){
        if($argv[2] == '-r'){
            //replace the compressed scripts with the backed up version
            $minifyUtils->reverseScripts("$from/jssource/src_files",$from);
        }elseif($argv[2] == '-m'){
            //replace the scripts, and then minify the scripts again
            $minifyUtils->reverseScripts("$from/jssource/src_files",$from);
            $minifyUtils->BackUpAndCompressScriptFiles($from,"",false,true);
        }elseif($argv[2] == '-c'){
            //replace the scripts, concatenate the files, and then minify the scripts again
            $minifyUtils->reverseScripts("$from/jssource/src_files",$from);
            $minifyUtils->BackUpAndCompressScriptFiles($from,"",false,true);
            $minifyUtils->ConcatenateFiles($from,true);
        }elseif($argv[2] == '-mo'){
            //do not replace the scriptsjust minify the existing scripts again
            $minifyUtils->BackUpAndCompressScriptFiles($from,"",false,true);
        }elseif($argv[2] == '-co'){
            //concatenate the files only
            $minifyUtils->ConcatenateFiles($from,true);
        }elseif($argv[2] == '-?'){
            die("
    Usage : minify <root path> [[-r]|[-m]|[-c]]

    <root path> = path of directory to process.  Should be root of sugar instance.
     -r  = replace javascript of root with scripts from backed up jssource/src_files directory
     -m  = same as r, only the script is minified and then copied
     -c  = same as m, only the concatenated files are processed again.
     -co = concatenates only the js files that are to be concatenated.  Main use is for development when files that make up a concatenated file have been modified.
     -mo = minifies only the existing js files.  Will not use source files and will not back up scripts.  Main use is for development, when changes have been made to working javascript and you wish to recompress your scripts.

    *** note that options are mutually exclusive.  You would use -r OR -m OR -c

    examples: say your patch is located in 'c:/sugar'
    You wish to have files from root directory concatenated according to file grouping array, as well as all js files compressed and backed up:
        minify 'c:/sugar'

    You wish to have backed up jssource files replace your current javascript files:
        minify 'c:/sugar' -r

    You wish to have backed up jssource files minified, and replace your current javascript files:
        minify 'c:/sugar' -m

    You wish to have backed up jssource files concatenated, minified, and replace your current javascript files:
        minify 'c:/sugar' -c
                                        ");

        }
    }else{
        //default is to concatenate the files, then back up and compress them
        if(empty($from)){
            echo("directory root to process was not specified");
        }
        $minifyUtils->BackUpAndCompressScriptFiles($from, '', true, true);
        $minifyUtils->ConcatenateFiles($from,true);
    }
}

