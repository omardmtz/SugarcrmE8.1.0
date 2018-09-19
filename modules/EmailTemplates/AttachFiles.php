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
 //Request object must have these property values:
 //		Module: module name, this module should have a file called TreeData.php
 //		Function: name of the function to be called in TreeData.php, the function will be called statically.
 //		PARAM prefixed properties: array of these property/values will be passed to the function as parameter.



if (!is_dir($cachedir = sugar_cached('images/')))
    mkdir_recursive($cachedir);

// cn: bug 11012 - fixed some MIME types not getting picked up.  Also changed array iterator.
$imgType = array('image/gif', 'image/png', 'image/x-png', 'image/bmp', 'image/jpeg', 'image/jpg', 'image/pjpeg');

$ret = array();

foreach($_FILES as $k => $file) {
	if(in_array(strtolower($_FILES[$k]['type']), $imgType) && $_FILES[$k]['size'] > 0) {
	    $upload_file = new UploadFile($k);
		// check the file
		if($upload_file->confirm_upload()) {
		    $dest = $cachedir.basename($upload_file->get_stored_file_name()); // target name
		    $guid = create_guid();
		    if($upload_file->final_move($guid)) { // move to uploads
		        $path = $upload_file->get_upload_path($guid);
		        // if file is OK, copy to cache
		        if(verify_uploaded_image($path) && copy($path, $dest)) {
		            $ret[] = $dest;
		        }
		        // remove temp file
		        unlink($path);
		    }
		}
	}
}

if (!empty($ret)) {
	$json = getJSONobj();
	echo $json->encode($ret);
	//return the parameters
}
