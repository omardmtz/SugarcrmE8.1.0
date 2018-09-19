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


global $sugar_config;
$supportedExtensions = array('jpg', 'png', 'jpeg');
$json = getJSONobj();
$rmdir=true;
$returnArray = array();
if (isset($_REQUEST['forQuotes']) && $json->decode(html_entity_decode($_REQUEST['forQuotes']))) {
    $returnArray['forQuotes'] = "quotes";
} else {
    $returnArray['forQuotes'] = "company";
}
$upload_ok = false;
$upload_path = 'tmp_logo_' . $returnArray['forQuotes'] . '_upload';
if(isset($_FILES['file_1'])){
    $upload = new UploadFile('file_1');
    if($upload->confirm_upload()) {
        $upload_dir  = 'upload://' . $upload_path;
        UploadStream::ensureDir($upload_dir);
        $file_name = $upload_dir."/".$upload->get_stored_file_name();
        if($upload->final_move($file_name)) {
            $upload_ok = true;
        }
    }
}
if(!$upload_ok) {
    $returnArray['data']='not_recognize';
    echo $json->encode($returnArray);
    sugar_cleanup(true);
}
if(file_exists($file_name) && is_file($file_name)) {
    $encoded_file_name = rawurlencode($upload->get_stored_file_name());
    $returnArray['path'] = $upload_path . '/' . $encoded_file_name;
    $returnArray['url']= 'cache/images/'.$encoded_file_name;
    if(!verify_uploaded_image($file_name, $returnArray['forQuotes'] == 'quotes')) {
        $returnArray['data']='other';
        $returnArray['path'] = '';
    } else {
        $img_size = getimagesize($file_name);
        $filetype = $img_size['mime'];
        $test=$img_size[0]/$img_size[1];
        if (($test>10 || $test<1) && $returnArray['forQuotes'] == 'company'){
            $rmdir=false;
            $returnArray['data']='size';
        }
        if (($test>20 || $test<3)&& $returnArray['forQuotes'] == 'quotes')
            $returnArray['data']='size';
        sugar_mkdir(sugar_cached('images'));
        copy($file_name, sugar_cached('images/'.$upload->get_stored_file_name()));
    }
    if(!empty($returnArray['data'])){
        echo $json->encode($returnArray);
    }else{
        $rmdir=false;
        $returnArray['data']='ok';
        echo $json->encode($returnArray);
    }
}else{
    $returnArray['data']='file_error';
    echo $json->encode($returnArray);
}
sugar_cleanup(true);
