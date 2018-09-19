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

class NoteSoap
{
    var $upload_file;

    public function __construct()
    {
    	$this->upload_file = new UploadFile('uploadfile');
    }

    function saveFile($note, $portal = false)
    {
        global $sugar_config;

        $focus = BeanFactory::newBean('Notes');

                if($portal){
                        $focus->disable_row_level_security = true;
                }


        if(!empty($note['id'])){
                $focus->retrieve($note['id']);
                if(empty($focus->id)) {
                    return '-1';
                }
        }else{
                return '-1';
        }

        if(!empty($note['file'])){
                $decodedFile = base64_decode($note['file']);
                $this->upload_file->set_for_soap($note['filename'], $decodedFile);

                $ext_pos = strrpos($this->upload_file->stored_file_name, ".");
                $this->upload_file->file_ext = substr($this->upload_file->stored_file_name, $ext_pos + 1);
                if (in_array($this->upload_file->file_ext, $sugar_config['upload_badext'])) {
                        $this->upload_file->stored_file_name .= ".txt";
                        $this->upload_file->file_ext = "txt";
                }

                $focus->filename = $this->upload_file->get_stored_file_name();
                $focus->file_mime_type = $this->upload_file->getMimeSoap($focus->filename);
               	$focus->id = $note['id'];
                $return_id = $focus->save();
                $this->upload_file->final_move($focus->id);
        }else{
                return '-1';
        }

        return $return_id;
    }

    function newSaveFile($note, $portal = false){
        global $sugar_config;

        $focus = BeanFactory::newBean('Notes');

        if($portal){
        	$focus->disable_row_level_security = true;
        }

        if(!empty($note['id'])){
        	$focus->retrieve($note['id']);
            if(empty($focus->id)) {
                return '-1';
            }
        } else {
           	return '-1';
        }

        if(!empty($note['file'])){
            $decodedFile = base64_decode($note['file']);
            $this->upload_file->set_for_soap($note['filename'], $decodedFile);

            $ext_pos = strrpos($this->upload_file->stored_file_name, ".");
            $this->upload_file->file_ext = substr($this->upload_file->stored_file_name, $ext_pos + 1);
            if (in_array($this->upload_file->file_ext, $sugar_config['upload_badext'])) {
                    $this->upload_file->stored_file_name .= ".txt";
                    $this->upload_file->file_ext = "txt";
            }

            $focus->filename = $this->upload_file->get_stored_file_name();
            $focus->file_mime_type = $this->upload_file->getMimeSoap($focus->filename);
            $focus->save();
        }

        $return_id = $focus->id;

        if(!empty($note['file'])){
        	$this->upload_file->final_move($focus->id);
        }

		if (!empty($note['related_module_id']) && !empty($note['related_module_name'])) {
        	$focus->process_save_dates=false;
        	$module_name = $note['related_module_name'];
        	$module_id = $note['related_module_id'];
			if($module_name != 'Contacts'){
				$focus->parent_type=$module_name;
				$focus->parent_id = $module_id;
			}else{
				$focus->contact_id=$module_id;
			}
			$focus->save();

        } // if
        return $return_id;
    }

    function retrieveFile($id, $filename)
    {
    	if(empty($filename)){
    		return '';
    	}

    	$this->upload_file->stored_file_name = $filename;
    	$filepath = $this->upload_file->get_upload_path($id);
    	if(file_exists($filepath)){
    		$file = file_get_contents($filepath);
    		return base64_encode($file);
    	}
    	return -1;
    }

}
