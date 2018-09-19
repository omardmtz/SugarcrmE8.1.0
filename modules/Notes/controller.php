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


 class NotesController extends SugarController
{
	function action_save(){

		// CCL - Bugs 41103 and 43751.  41103 address the issue where the parent_id is set, but
		// the relate_id field overrides the relationship.  43751 fixes the problem where the relate_id and
		// parent_id are the same value (in which case it should just use relate_id) by adding the != check
		if ((!empty($_REQUEST['relate_id']) && !empty($_REQUEST['parent_id'])) && ($_REQUEST['relate_id'] != $_REQUEST['parent_id']))
		{
			$_REQUEST['relate_id'] = false;
		}

		$GLOBALS['log']->debug('PERFORMING NOTES SAVE');
		$upload_file = new UploadFile('uploadfile');
		$do_final_move = 0;
		if (isset($_FILES['uploadfile']) && $upload_file->confirm_upload())
		{
       		if (!empty($this->bean->id) && !empty($_REQUEST['old_filename']) )
        	{
       	         $upload_file->unlink_file($this->bean->id,$_REQUEST['old_filename']);
       	 	}

	        $this->bean->filename = $upload_file->get_stored_file_name();
	        $this->bean->file_mime_type = $upload_file->mime_type;

       	 $do_final_move = 1;
		}
		else if ( isset( $_REQUEST['old_filename']))
		{
	       	 $this->bean->filename = $_REQUEST['old_filename'];
		}

		$check_notify = false;
		if(!empty($_POST['assigned_user_id']) &&
		    (empty($this->bean->fetched_row) || $this->bean->fetched_row['assigned_user_id'] != $_POST['assigned_user_id']) &&
		    ($_POST['assigned_user_id'] != $GLOBALS['current_user']->id)){
		        $check_notify = true;
		}
	    $this->bean->save($check_notify);

		if ($do_final_move)
		{
       		 $upload_file->final_move($this->bean->id);
		}
		else if ( ! empty($_REQUEST['old_id']))
		{
       	 	$upload_file->duplicate_file($_REQUEST['old_id'], $this->bean->id, $this->bean->filename);
		}
	}

    function action_editview(){
		$this->view = 'edit';
		$GLOBALS['view'] = $this->view;
		if(!empty($_REQUEST['deleteAttachment'])){
			ob_clean();
			echo $this->bean->deleteAttachment($_REQUEST['isDuplicate']) ? 'true' : 'false';
			sugar_cleanup(true);
		}

	}

}
?>
