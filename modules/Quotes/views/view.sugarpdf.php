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

class QuotesViewSugarpdf extends ViewSugarpdf{

    /**
     * OVERRIDE
     */
    function display(){
        $this->sugarpdfBean->process();

        // This case is for "email as PDF"
        if (isset($_REQUEST['email_action']) && $_REQUEST['email_action']=="EmailLayout") {
            // After the output the object is destroy
            $fileName = $this->sugarpdfBean->fileName;
            $bean = $this->sugarpdfBean->bean;

            $tmp = $this->sugarpdfBean->Output('','S');
            $badoutput = ob_get_contents();
            if(strlen($badoutput) > 0) {
                ob_end_clean();
            }
            file_put_contents("upload://$fileName", ltrim($tmp));

            $email_id = $this->email_layout($fileName, $bean);

            //redirect
            if($email_id=="") {
                //Redirect to quote, since something went wrong
                echo "There was an error with your request";
                exit; //end if email id is blank
            } else {
                //Redirect to new email draft just created
                header("Location: index.php?action=Compose&module=Emails&parent_type=Quotes&return_module=Quotes&return_action=DetailView&return_id=".$_REQUEST['record']."&recordId=$email_id"."&parent_id=".$_REQUEST['record']);
            }
        }

        $this->sugarpdfBean->Output($this->sugarpdfBean->fileName,'D');
        sugar_cleanup(true);
    }

    /**
     *
     * @param $file_name
     * @param $focus
     * @return unknown_type
     */
    function email_layout ($file_name, $focus) {
        //Focus is quote bean
        global $log;
        global $mod_strings;
        global $layouts;
        global $current_user;

        //First Create e-mail draft
        $email_object = BeanFactory::newBean('Emails');
        // set the id for relationships
        $email_object->id = create_guid();
        $email_object->new_with_id = true;

        //subject
        $email_object->name = $mod_strings['LBL_EMAIL_QUOTE_FOR'].$focus->name."";
        //body
        $email_object->description_html = $mod_strings['LBL_EMAIL_DEFAULT_DESCRIPTION'];
        //parent type, id
        $email_object->parent_type = "Quotes";
        $email_object->parent_id = $focus->id;
        //type is draft
        $email_object->type = "draft";
        $email_object->status = "draft";

        // link the sent pdf to the relevant account
        if(isset($focus->billing_account_id) && !empty($focus->billing_account_id)) {
            $email_object->load_relationship('accounts');
            $email_object->accounts->add($focus->billing_account_id);
        }

        //check to see if there is a billing contact associated with this quote
        if(!empty($focus->billing_contact_id) && $focus->billing_contact_id!="") {
            global $beanFiles;
            require_once($beanFiles['Contact']);
            $contact = BeanFactory::getBean('Contacts', $focus->billing_contact_id);

            if(!empty($contact->email1) || !empty($contact->email2)) {
                //contact email is set
                $email_object->to_addrs_ids = $focus->billing_contact_id;
                $email_object->to_addrs_names = $focus->billing_contact_name.";";

                if(!empty($contact->email1)){
                    $email_object->to_addrs_emails = $contact->email1.";";
                    $email_object->to_addrs = $focus->billing_contact_name." <".$contact->email1.">";
                } elseif(!empty($contact->email2)){
                    $email_object->to_addrs_emails = $contact->email2.";";
                    $email_object->to_addrs = $focus->billing_contact_name." <".$contact->email2.">";
                }

                // create relationship b/t the email(w/pdf) and the contact
                $contact->load_relationship('emails');
                $contact->emails->add($email_object->id);
            }//end if contact name is set
        } elseif(isset($focus->billing_account_id) && !empty($focus->billing_account_id)) {
            $acct = BeanFactory::getBean('Accounts', $focus->billing_account_id);

            if(!empty($acct->email1) || !empty($acct->email2)) {
                //acct email is set
                $email_object->to_addrs_ids = $focus->billing_account_id;
                $email_object->to_addrs_names = $focus->billing_account_name.";";

                if(!empty($acct->email1)){
                    $email_object->to_addrs_emails = $acct->email1.";";
                    $email_object->to_addrs = $focus->billing_account_name." <".$acct->email1.">";
                } elseif(!empty($acct->email2)){
                    $email_object->to_addrs_emails = $acct->email2.";";
                    $email_object->to_addrs = $focus->billing_account_name." <".$acct->email2.">";
                }

                // create relationship b/t the email(w/pdf) and the acct
                $acct->load_relationship('emails');
                $acct->emails->add($email_object->id);
            }//end if acct name is set
        }



        //team id
        $email_object->team_id  = $current_user->default_team;
        //assigned_user_id
        $email_object->assigned_user_id = $current_user->id;
        //Save the email object
        global $timedate;
        $email_object->date_start = $timedate->now();
        $email_object->save(FALSE);
        $email_id = $email_object->id;

        //Handle PDF Attachment
        $note = BeanFactory::newBean('Notes');
        $note->id = Uuid::uuid1();
        $note->new_with_id = true;
        $note->filename = $file_name;
        $note->team_id = "";
        $note->file_mime_type = "application/pdf";
        $note->name = $mod_strings['LBL_EMAIL_ATTACHMENT'].$file_name;

        //save the pdf attachment note
        $note->email_id = $email_object->id;
        $note->email_type = "Emails";

        // Move the file before saving so that the file size is captured during save.
	    $source = "upload://$file_name";
        $destination = "upload://{$note->id}";

        if (!rename($source, $destination)){
            $msg = str_replace('$destination', $destination, $mod_strings['LBL_RENAME_ERROR']);
            die($msg);
        }

        $note->save();

        //return the email id
        return $email_id;
    }
}
?>
