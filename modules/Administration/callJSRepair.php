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

/*
**this is the ajax call that is called from RebuildJSLang.php.  It processes
**the Request object in order to call correct methods for repairing/rebuilding JSfiles
*Note that minify.php has already been included as part of index.php, so no need to include again.
*/

//set default root directory
$from = getcwd();
$request = InputValidation::getService();
$rootDirectory = $request->getValidInputRequest('root_directory', array('Assert\Type' => (array('type' => 'string'))));
$jsAdminRepair = $request->getValidInputRequest('js_admin_repair', array('Assert\Type' => (array('type' => 'string'))));

if (!empty($rootDirectory)) {
    $from = $rootDirectory;
}

//this script can take a while, change max execution time to 10 mins
$tmp_time = ini_get('max_execution_time');
ini_set('max_execution_time','600');

    //figure out which commands to call.
    if($jsAdminRepair == 'concat' ){
        //concatenate mode, call the files that will concatenate javascript group files
        $_REQUEST['js_rebuild_concat'] = 'rebuild';
        require_once('jssource/minify.php');
    }else{
        $_REQUEST['root_directory'] = getcwd();
        require_once('jssource/minify.php');
        $minifyUtils = new SugarMinifyUtils();
        if($jsAdminRepair == 'replace'){
            //should replace compressed JS with source js
            $minifyUtils->reverseScripts("$from/jssource/src_files","$from");

        }elseif($jsAdminRepair == 'mini'){
            //should replace compressed JS with minified version of source js
            $minifyUtils->reverseScripts("$from/jssource/src_files","$from");
            $minifyUtils->BackUpAndCompressScriptFiles("$from","",false);
            $minifyUtils->ConcatenateFiles("$from");

        }elseif($jsAdminRepair == 'repair'){
         //should compress existing javascript (including changes done) without overwriting original source files
            $minifyUtils->BackUpAndCompressScriptFiles("$from","",false);
            $minifyUtils->ConcatenateFiles("$from");
        }
    }
//set execution time back to what it was
ini_set('max_execution_time',$tmp_time);
