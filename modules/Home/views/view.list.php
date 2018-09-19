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


class HomeViewList extends ViewList{
 	function display(){
 		global $mod_strings, $export_module, $current_language, $theme, $current_user, $dashletData, $sugar_flavor;
         $this->processMaxPostErrors();
 	}

    function processMaxPostErrors() {
        if($this->checkPostMaxSizeError()){
            $this->errors[] = $GLOBALS['app_strings']['UPLOAD_ERROR_HOME_TEXT'];
            $contentLength = $_SERVER['CONTENT_LENGTH'];

            $maxPostSize = ini_get('post_max_size');
            if (stripos($maxPostSize,"k"))
                $maxPostSize = (int) $maxPostSize * pow(2, 10);
            elseif (stripos($maxPostSize,"m"))
                $maxPostSize = (int) $maxPostSize * pow(2, 20);

            $maxUploadSize = ini_get('upload_max_filesize');
            if (stripos($maxUploadSize,"k"))
                $maxUploadSize = (int) $maxUploadSize * pow(2, 10);
            elseif (stripos($maxUploadSize,"m"))
                $maxUploadSize = (int) $maxUploadSize * pow(2, 20);

            $max_size = min($maxPostSize, $maxUploadSize);
            if ($contentLength > $max_size) {
                $errMessage = string_format($GLOBALS['app_strings']['UPLOAD_MAXIMUM_EXCEEDED'],array($contentLength,  $max_size));
            } else {
                $errMessage =$GLOBALS['app_strings']['UPLOAD_REQUEST_ERROR'];
            }

            $this->errors[] = '* '.$errMessage;
            $this->displayErrors();
        }
    }

}
?>
