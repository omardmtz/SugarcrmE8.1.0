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

use Sugarcrm\Sugarcrm\Util\Uuid;

class EmailTemplateFormBase
{

	function getFormBody($prefix, $mod='',$formname='', $size='30') {


		global $mod_strings;

		$temp_strings = $mod_strings;

		if(!empty($mod)) {
			global $current_language;
			$mod_strings = return_module_language($current_language, $mod);
		}
					global $app_strings;
					global $app_list_strings;

				$lbl_required_symbol = $app_strings['LBL_REQUIRED_SYMBOL'];
				$lbl_subject = $mod_strings['LBL_NOTE_SUBJECT'];
				$lbl_description = $mod_strings['LBL_NOTE'];
				$default_parent_type= $app_list_strings['record_type_default_key'];

$form = <<<EOF
				<input type="hidden" name="${prefix}record" value="">
				<input type="hidden" name="${prefix}parent_type" value="${default_parent_type}">
				<p>
				<table cellspacing="0" cellpadding="0" border="0">
				<tr>
				    <td scope="row">$lbl_subject <span class="required">$lbl_required_symbol</span></td>
				</tr>
				<tr>
				    <td ><input name='${prefix}name' size='${size}' maxlength='255' type="text" value=""></td>
				</tr>
				<tr>
				    <td scope="row">$lbl_description</td>
				</tr>
				<tr>
				    <td ><textarea name='${prefix}description' cols='${size}' rows='4' ></textarea></td>
				</tr>
				</table></p>
EOF;

	$javascript = new javascript();
	$javascript->setFormName($formname);
	$javascript->setSugarBean(BeanFactory::newBean('EmailTemplates'));
	$javascript->addRequiredFields($prefix);
	$form .=$javascript->getScript();
	$mod_strings = $temp_strings;
	return $form;
	}

	function getForm($prefix, $mod='') {
		if(!empty($mod)) {
		global $current_language;
		$mod_strings = return_module_language($current_language, $mod);
	}else global $mod_strings;
		global $app_strings;
		global $app_list_strings;

		$lbl_save_button_title = $app_strings['LBL_SAVE_BUTTON_TITLE'];
		$lbl_save_button_key = $app_strings['LBL_SAVE_BUTTON_KEY'];
		$lbl_save_button_label = $app_strings['LBL_SAVE_BUTTON_LABEL'];


		$the_form = get_left_form_header($mod_strings['LBL_NEW_FORM_TITLE']);
$the_form .= <<<EOQ

				<form name="${prefix}EmailTemplateSave" onSubmit="return check_form('${prefix}EmailTemplateSave')" method="POST" action="index.php">
					<input type="hidden" name="${prefix}module" value="EmailTemplates">
					<input type="hidden" name="${prefix}action" value="Save">
EOQ;
		$the_form .= $this->getFormBody($prefix, $mod, "${prefix}EmailTemplateSave", "20");
$the_form .= <<<EOQ
				<p><input title="$lbl_save_button_title" accessKey="$lbl_save_button_key" class="button" type="submit" name="button" value="  $lbl_save_button_label  " ></p>
				</form>

EOQ;

		$the_form .= get_left_form_footer();
		$the_form .= get_validate_record_js();


		return $the_form;
	}


	function handleSave($prefix,$redirect=true, $useRequired=false)
	{
		require_once('include/formbase.php');
		global $upload_maxsize;
		global $mod_strings;
		global $sugar_config;

		$focus = BeanFactory::newBean('EmailTemplates');
		if($useRequired && !checkRequired($prefix, array_keys($focus->required_fields))) {
			return null;
		}
		$focus = populateFromPost($prefix, $focus);
        //process the text only flag
        if(isset($_POST['text_only']) && ($_POST['text_only'] == '1')){
            $focus->text_only = 1;
        }else{
            $focus->text_only = 0;
        }
		if(!$focus->ACLAccess('Save')) {
			ACLController::displayNoAccess(true);
			sugar_cleanup(true);
		}
		if(!isset($_REQUEST['published'])) $focus->published = 'off';

		$preProcessedImages = array();
		$emailTemplateBodyHtml = from_html($focus->body_html);
		if(strpos($emailTemplateBodyHtml, '"cache/images/')) {
			$matches = array();
			preg_match_all('#<img[^>]*[\s]+src[^=]*=[\s]*["\']cache/images/(.+?)["\']#si', $emailTemplateBodyHtml, $matches);
			foreach($matches[1] as $match) {
				$filename = urldecode($match);
                if($filename != pathinfo($filename, PATHINFO_BASENAME)) {
                    // don't allow paths there
                    $emailTemplateBodyHtml = str_replace("cache/images/$match", "", $emailTemplateBodyHtml);
                    continue;
                }
				$file_location = sugar_cached("images/{$filename}");
				$mime_type = pathinfo($filename, PATHINFO_EXTENSION);

				if(file_exists($file_location)) {
					$id = create_guid();
					$newFileLocation = "upload://$id";
					if(!copy($file_location, $newFileLocation)) {
						$GLOBALS['log']->debug("EMAIL Template could not copy attachment to $newFileLocation");
					} else {
						$secureLink = "index.php?entryPoint=download&type=Notes&id={$id}";
					    $emailTemplateBodyHtml = str_replace("cache/images/$match", $secureLink, $emailTemplateBodyHtml);
						unlink($file_location);
						$preProcessedImages[$filename] = $id;
					}
				} // if
			} // foreach
		} // if
		if (isset($GLOBALS['check_notify'])) {
            $check_notify = $GLOBALS['check_notify'];
        }
        else {
            $check_notify = FALSE;
        }
        $focus->body_html = $emailTemplateBodyHtml;
        $return_id = $focus->save($check_notify);
		///////////////////////////////////////////////////////////////////////////////
		////	ATTACHMENT HANDLING

		///////////////////////////////////////////////////////////////////////////
		////	ADDING NEW ATTACHMENTS
		if(!empty($focus->id)) {
			$note = BeanFactory::newBean('Notes');
            //FIXME: notes.email_type should be EmailTemplates
            $where = sprintf(' notes.email_id = %s', $focus->db->quoted($focus->id));
			if(!empty($_REQUEST['old_id'])) { // to support duplication of email templates
                $where .= sprintf(' OR notes.email_id = %s', $focus->db->quoted($_REQUEST['old_id']));
			}
            $attachments = $note->get_full_list("", $where, true);
		}

		if(!isset($attachments)) {
			$attachments = array();
		}

		foreach ($_FILES as $key => $file)
		{
			$note = BeanFactory::newBean('Notes');

			//Images are presaved above so we need to prevent duplicate files from being created.
			if( isset($preProcessedImages[$file['name']]) )
			{
			    $oldId = $preProcessedImages[$file['name']];
			    $note->id = $oldId;
			    $note->new_with_id = TRUE;
			    $GLOBALS['log']->debug("Image {$file['name']} has already been processed.");
            }

			$i=preg_replace("/email_attachment(.+)/",'$1',$key);
			$upload_file = new UploadFile($key);

			if(isset($_FILES[$key]) && $upload_file->confirm_upload() && preg_match("/^email_attachment/",$key)) {
				$note->filename = $upload_file->get_stored_file_name();
				$note->file = $upload_file;
				$note->name = $mod_strings['LBL_EMAIL_ATTACHMENT'].': '.$note->file->original_file_name;
				$note->team_id = $focus->team_id;
				if(isset($_REQUEST['embedded'.$i]) && !empty($_REQUEST['embedded'.$i])){
                  if($_REQUEST['embedded'.$i]=='true'){
				  	$note->embed_flag =true;
                  }
                  else{
                  	$note->embed_flag =false;
                  }
				}
				array_push($attachments, $note);
			}

		}

		$focus->saved_attachments = array();
		foreach($attachments as $note)
		{
			if( !empty($note->id) && $note->new_with_id === FALSE)
			{
				if(empty($_REQUEST['old_id']))
					array_push($focus->saved_attachments, $note); // to support duplication of email templates
				else
				{
					// we're duplicating a template with attachments
					// dupe the file, create a new note, assign the note to the new template
					$newNote = BeanFactory::getBean('Notes', $note->id);
					$newNote->id = create_guid();
					$newNote->email_id = $focus->id;
                    //FIXME: Should email_type be set to Emails or EmailTemplates ($focus->module_name)?
					$newNote->new_with_id = true;
					$newNote->date_modified = '';
					$newNote->date_entered = '';

                    // Duplicate the file before saving so that the file size is captured during save.
                    UploadFile::duplicate_file($note->id, $newNote->id, $note->filename);
                    $newNote->save();
				}
				continue;
			}
			$note->email_id = $focus->id;
            //FIXME: Is this code used for anything other than saving a template? If no, then email_type should be
            // EmailTemplates. If yes, then email_type may be Emails or EmailTemplates depending on the use.
			$note->email_type = 'Emails';
			$note->file_mime_type = $note->file->mime_type;
			$note_id = $note->save();
			array_push($focus->saved_attachments, $note);
			$note->id = $note_id;

			if($note->new_with_id === FALSE)
    			$note->file->final_move($note->id);
    	    else
    	       $GLOBALS['log']->debug("Not performing final move for note id {$note->id} as it has already been processed");
		}

		////	END NEW ATTACHMENTS
		///////////////////////////////////////////////////////////////////////////

	///////////////////////////////////////////////////////////////////////////
	////	ATTACHMENTS FROM DOCUMENTS
	if(!empty($_REQUEST['document'])){
      $count = count($_REQUEST['document']);
    } else {
    	$count=10;
    }

	for($i=0; $i<$count; $i++) {
		if(isset($_REQUEST['documentId'.$i]) && !empty($_REQUEST['documentId'.$i])) {
			$doc = BeanFactory::retrieveBean('Documents', $_REQUEST['documentId'.$i]);
			if(empty($doc)) continue;
			$docRev = BeanFactory::retrieveBean('DocumentRevisions', $doc->document_revision_id);
			if(empty($docRev)) continue;
			$docNote = BeanFactory::newBean('Notes');
            $docNote->id = Uuid::uuid1();
            $docNote->new_with_id = true;

			array_push($focus->saved_attachments, $docRev);

			$docNote->name = $doc->document_name;
			$docNote->filename = $docRev->filename;
			$docNote->description = $doc->description;
			$docNote->email_id = $focus->id;
            //FIXME: Is this code used for anything other than saving a template? If no, then email_type should be
            // EmailTemplates. If yes, then email_type may be Emails or EmailTemplates depending on the use.
			$docNote->email_type = 'Emails';
			$docNote->file_mime_type = $docRev->file_mime_type;

            // Duplicate the file before saving so that the file size is captured during save.
            UploadFile::duplicate_file($docRev->id, $docNote->id, $docRev->filename);
            $docNote->save();
		}

	}

	////	END ATTACHMENTS FROM DOCUMENTS
	///////////////////////////////////////////////////////////////////////////

		///////////////////////////////////////////////////////////////////////////
		////	REMOVE ATTACHMENTS

		if(isset($_REQUEST['remove_attachment']) && !empty($_REQUEST['remove_attachment'])) {
			foreach($_REQUEST['remove_attachment'] as $noteId) {
                $query = 'UPDATE notes SET deleted = 1 WHERE id = ?';
                $conn = $focus->db->getConnection();
                $conn->executeQuery($query, array($noteId));
			}

		}

		////	END REMOVE ATTACHMENTS
		///////////////////////////////////////////////////////////////////////////
	////	END ATTACHMENT HANDLING
	///////////////////////////////////////////////////////////////////////////////

        clear_register_value('select_array', $focus->object_name);

		if($redirect) {
		$GLOBALS['log']->debug("Saved record with id of ".$return_id);
			handleRedirect($return_id, "EmailTemplates");
		}else{
			return $focus;
		}
	}

}
