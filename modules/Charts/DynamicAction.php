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

if(isset($_GET['DynamicAction']) && $_GET['DynamicAction'] == "saveImage") {
	$filename = pathinfo($_POST['filename'], PATHINFO_BASENAME);
	$ext = pathinfo($filename, PATHINFO_EXTENSION);
	if(!in_array(strtolower($ext), array('jpg', 'png', 'jpeg'))) {
	    return false;
	}
	$image = str_replace(" ", "+", $_POST["imageStr"]);
	$data = substr($image, strpos($image, ","));
    if(sugar_mkdir(sugar_cached("images"), 0777, true))
    {
        $filepath = sugar_cached("images/$filename");
        file_put_contents($filepath, base64_decode($data));
        if(!verify_uploaded_image($filepath)) {
            unlink($filepath);
            return false;
        }
    }
    else
    {
        return false;
    }
}
