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
/*********************************************************************************
 * $Id$
 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
 
 require_once('include/formbase.php');
 class FileController extends SugarController{

 	function action_save(){
 		$move=false;
 		$file = new File();
 		$file = populateFromPost('', $file);
 		$upload_file = new UploadFile('uploadfile');
 		$return_id ='';
 		if (isset($_FILES['uploadfile']) && $upload_file->confirm_upload())
		{
    		$file->filename = $upload_file->get_stored_file_name();
    		$file->file_mime_type = $upload_file->mime_type;
			$file->file_ext = $upload_file->file_ext;
			$move=true;
		}
 		$return_id = $file->save();
 		if ($move) {
			$upload_file->final_move($file->id);
		}
 		handleRedirect($return_id, $this->object_name);
 	}

 }
