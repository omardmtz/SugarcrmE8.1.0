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
class ConfiguratorViewAddFontResult extends SugarView {
   var $log="";
    /**
     * display the form
     */
    public function display(){
        global $mod_strings, $app_list_strings, $app_strings, $current_user;
        if(!is_admin($current_user)){
           sugar_die($GLOBALS['app_strings']['ERR_NOT_ADMIN']);
        }
        $error = $this->addFont();

        $this->ss->assign("MODULE_TITLE",
            getClassicModuleTitle(
                $mod_strings['LBL_MODULE_ID'],
                array($mod_strings['LBL_ADDFONTRESULT_TITLE']),
                false
                )
            );
        if($error){
            $this->ss->assign("error", $this->log);
        }else{
            $this->ss->assign("info", $this->log);
        }
        $this->ss->assign("MOD", $mod_strings);
        $this->ss->assign("APP", $app_strings);
//display
        $this->ss->display('modules/Configurator/tpls/addFontResult.tpl');
    }

    /**
     * This method prepares the received data and call the addFont method of the fontManager
     * @return boolean true on success
     */
    private function addFont(){
        $this->log="";
        $error=false;
        $files = array("pdf_metric_file","pdf_font_file");
        foreach($files as $k){
            // handle uploaded file
            $uploadFile = new UploadFile($k);
            if (isset($_FILES[$k]) && $uploadFile->confirm_upload()){
                $uploadFile->final_move(basename($_FILES[$k]['name']));
                $uploadFileNames[$k] = $uploadFile->get_upload_path(basename($_FILES[$k]['name']));
            }else{
                $this->log = translate('ERR_PDF_NO_UPLOAD', "Configurator");
                $error=true;
            }
        }
        if(!$error){
            $encoding_table = $this->request->getValidInputRequest(
                'pdf_encoding_table',
                array(
                    'Assert\Regex' => array(
                        'pattern' => '/^[a-z0-9_-]+$/i'
                    )
                )
            );
            $embedded = (bool) $this->request->getValidInputRequest('pdf_embedded');
            $fontManager = new FontManager();
            $error = $fontManager->addFont(
                $uploadFileNames["pdf_font_file"],
                $uploadFileNames["pdf_metric_file"],
                $embedded,
                $encoding_table,
                array(),
                htmlspecialchars_decode($_REQUEST['pdf_cidinfo'], ENT_QUOTES),
                $_REQUEST['pdf_style_list']
            );
            $this->log .= $fontManager->log;
            if($error){
                $this->log .= implode("\n",$fontManager->errors);
            }
        }
        return $error;
    }
}
