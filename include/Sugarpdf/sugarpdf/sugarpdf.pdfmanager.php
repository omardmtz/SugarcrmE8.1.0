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

class SugarpdfPdfmanager extends SugarpdfSmarty
{
    protected $pdfFilename;
    protected $footerText = null;

    public function preDisplay()
    {
        parent::preDisplay();

        // settings for disable smarty php tags
        $this->ss->security_settings['PHP_TAGS'] = false;
        $this->ss->security = true;
        if (defined('SUGAR_SHADOW_PATH')) {
            $this->ss->secure_dir[] = SUGAR_SHADOW_PATH;
        }

        // header/footer settings
        $this->setPrintHeader(false);
        $this->setPrintFooter(true); // always print page number at least

        if (!empty($_REQUEST['pdf_template_id'])) {

            $pdfTemplate = BeanFactory::newBean('PdfManager');
            if ($pdfTemplate->retrieve($_REQUEST['pdf_template_id'], false) !== null) {

                $previewMode = FALSE;
                if (!empty($_REQUEST['pdf_preview']) && $_REQUEST['pdf_preview'] == 1) {
                    $previewMode = true;
                    $this->bean = BeanFactory::newBean($pdfTemplate->base_module);
                }

                $this->SetCreator(PDF_CREATOR);
                $this->SetAuthor($pdfTemplate->author);
                $this->SetTitle($pdfTemplate->title);
                $this->SetSubject($pdfTemplate->subject);
                $this->SetKeywords($pdfTemplate->keywords);
                $this->templateLocation = $this->buildTemplateFile($pdfTemplate, $previewMode);

                $headerLogo = '';
                if (!empty($pdfTemplate->header_logo)) {
                    // Create a temporary copy of the header logo
                    // and append the original filename, so TCPDF can figure the extension
                    $headerLogo = 'upload/' . $pdfTemplate->id . $pdfTemplate->header_logo;
                    copy('upload/' . $pdfTemplate->id, $headerLogo);
                }

                if (!empty($pdfTemplate->header_logo) ||
                    !empty($pdfTemplate->header_title) || !empty($pdfTemplate->header_text)) {
                    $this->setHeaderData(
                        $headerLogo,
                        PDF_HEADER_LOGO_WIDTH,
                        $pdfTemplate->header_title,
                        $pdfTemplate->header_text
                    );
                    $this->setPrintHeader(true);
                }

                if (!empty($pdfTemplate->footer_text)) {
                    $this->footerText = $pdfTemplate->footer_text;
                }


                $filenameParts = array();
                if (!empty($this->bean) && !empty($this->bean->name)) {
                    $filenameParts[] = $this->bean->name;
                }
                if (!empty($pdfTemplate->name)) {
                    $filenameParts[] = $pdfTemplate->name;
                }

                $cr = array(' ',"\r", "\n","/");
                $this->pdfFilename = str_replace($cr, '_', implode("_", $filenameParts ).".pdf");
            }
        }


        if ($previewMode === FALSE) {
            require_once 'modules/PdfManager/PdfManagerHelper.php';
            $fields = PdfManagerHelper::parseBeanFields($this->bean, true);
        } else {
            $fields = array();
        }

        if ($this->module == 'Quotes' && $previewMode === FALSE) {
            global $locale;
            require_once 'modules/Quotes/config.php';
            require_once 'modules/Currencies/Currency.php';
            $currency = BeanFactory::newBean('Currencies');
            $format_number_array = array(
                'currency_symbol' => true,
                'type' => 'sugarpdf',
                'currency_id' => $this->bean->currency_id,
                'charset_convert' => true, /* UTF-8 uses different bytes for Euro and Pounds */
            );
            $currency->retrieve($this->bean->currency_id);
            $fields['currency_iso'] = $currency->iso4217;
            
            // Adding Tax Rate Field
            $fields['taxrate_value'] = format_number_sugarpdf($this->bean->taxrate_value, $locale->getPrecision(), $locale->getPrecision(), array('percentage' => true));;

            $this->bean->load_relationship('product_bundles');
            $product_bundle_list = $this->bean->product_bundles->getBeans();
            usort($product_bundle_list, array('ProductBundle', 'compareProductBundlesByIndex'));

            $bundles = array();
            $count = 0;
            foreach ($product_bundle_list as $ordered_bundle) {

                $bundleFields = PdfManagerHelper::parseBeanFields($ordered_bundle, true);
                $bundleFields['products'] = array();
                $product_bundle_line_items = $ordered_bundle->get_product_bundle_line_items();
                foreach ($product_bundle_line_items as $product_bundle_line_item) {

                    $bundleFields['products'][$count] = PdfManagerHelper::parseBeanFields($product_bundle_line_item, true);

                    if ($product_bundle_line_item->object_name == "ProductBundleNote") {
                        $bundleFields['products'][$count]['name'] = $bundleFields['products'][$count]['description'];
                    } else {
                        // Special case about discount amount
                        if ($product_bundle_line_item->discount_select) {
                            $bundleFields['products'][$count]['discount_amount'] = format_number($product_bundle_line_item->discount_amount, $locale->getPrecision(), $locale->getPrecision()) . '%';
                    }

                        // Special case about ext price
                        $bundleFields['products'][$count]['ext_price'] = format_number_sugarpdf($product_bundle_line_item->discount_price * $product_bundle_line_item->quantity, $locale->getPrecision(), $locale->getPrecision(), $format_number_array);                                        
                    }
                    
                    
                    $count++;
                }
                $bundles[] = $bundleFields;
            }

            $this->ss->assign('product_bundles', $bundles);
        }

         $this->ss->assign('fields', $fields);
    }

    public function display()
    {
        parent::display();

        $headerdata = $this->getHeaderData();
        // Remove the temporary logo copy (starts with "upload/") if exists
        if (!empty($headerdata['logo']) && file_exists($headerdata['logo']) && strpos($headerdata['logo'], "upload/") === 0) {
            unlink($headerdata['logo']);
        }
    }

    /**
     * Build the Email with the attachement
     *
     * @param $file_name
     * @param $focus
     * @return $email_id
     */
    private function buildEmail ($file_name, $focus) {
        
        global $mod_strings;
        global $current_user;

        //First Create e-mail draft
        $email_object = BeanFactory::newBean("Emails");
        // set the id for relationships
        $email_object->id = create_guid();
        $email_object->new_with_id = true;

        //subject
        $email_object->name = $focus->name;
        //body
        $email_object->description_html = sprintf(translate('LBL_EMAIL_PDF_DEFAULT_DESCRIPTION', "PdfManager"), $file_name);
        $email_object->description = html_entity_decode($email_object->description_html,ENT_COMPAT,'UTF-8');

        //parent type, id
        $email_object->parent_type = $focus->module_name;
        $email_object->parent_id = $focus->id;
        //type is draft
        $email_object->type = "draft";
        $email_object->status = "draft";
        $email_object->state = Email::STATE_DRAFT;

        $email_object->to_addrs_ids = $focus->id;
        $email_object->to_addrs_names = $focus->name.";";

        if (isset($focus->emailAddress)) {
            $to_addrs = $focus->emailAddress->getPrimaryAddress($focus);
            $email_object->to_addrs_emails = $to_addrs.";";
            $email_object->to_addrs = $focus->name." <".$to_addrs.">";
        }
        elseif( $focus->module_name == "Quotes" ) {
            // link the sent pdf to the relevant account
            if(isset($focus->billing_account_id) && !empty($focus->billing_account_id)) {
                $email_object->load_relationship('accounts');
                $email_object->accounts->add($focus->billing_account_id);
            }

            //check to see if there is a billing contact associated with this quote
            if(!empty($focus->billing_contact_id) && $focus->billing_contact_id!="") {
                $contact = BeanFactory::newBean("Contacts");
                $contact->retrieve($focus->billing_contact_id);

                if(!empty($contact->email1) || !empty($contact->email2)) {
                    if ($email_object->load_relationship('to')) {
                        $ep = BeanFactory::newBean('EmailParticipants');
                        $ep->new_with_id = true;
                        $ep->id = Uuid::uuid1();
                        BeanFactory::registerBean($ep);
                        $ep->parent_type = $contact->getModuleName();
                        $ep->parent_id = $contact->id;
                        $ep->email_address = $contact->emailAddress->getPrimaryAddress($contact);

                        if (!empty($ep->email_address)) {
                            $ep->email_address_id = $contact->emailAddress->getEmailGUID($ep->email_address);
                        }

                        $email_object->to->add($ep);
                    };

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
                $acct = BeanFactory::newBean("Accounts");
                $acct->retrieve($focus->billing_account_id);

                if(!empty($acct->email1) || !empty($acct->email2)) {
                    if ($email_object->load_relationship('to')) {
                        $ep = BeanFactory::newBean('EmailParticipants');
                        $ep->new_with_id = true;
                        $ep->id = Uuid::uuid1();
                        BeanFactory::registerBean($ep);
                        $ep->parent_type = $acct->getModuleName();
                        $ep->parent_id = $acct->id;
                        $ep->email_address = $acct->emailAddress->getPrimaryAddress($acct);

                        if (!empty($ep->email_address)) {
                            $ep->email_address_id = $acct->emailAddress->getEmailGUID($ep->email_address);
                        }

                        $email_object->to->add($ep);
                    };

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
        }

        if (isset($email_object->team_id)) {
            $email_object->team_id  = $current_user->getPrivateTeamID();
        }
        if (isset($email_object->team_set_id)) {
            $teamSet = BeanFactory::newBean('TeamSets');
            $teamIdsArray = array($current_user->getPrivateTeamID());
            $email_object->team_set_id = $teamSet->addTeams($teamIdsArray);
        }

        $email_object->assigned_user_id = $current_user->id;

        //Save the email object
        global $timedate;
        $email_object->date_start = $timedate->now();
        
        $email_object->save(FALSE);
        $email_id = $email_object->id;

        //Handle PDF Attachment
        $note = BeanFactory::newBean("Notes");
        $note->id = Uuid::uuid1();
        $note->new_with_id = true;
        $note->filename = $file_name;
        $note->file_mime_type = get_file_mime_type("upload://{$file_name}", 'application/octet-stream');
        $note->name = translate('LBL_EMAIL_ATTACHMENT', "Quotes").$file_name;

        $note->email_id = $email_object->id;
        $note->email_type = $email_object->module_name;

        //teams
        $note->team_id = $current_user->getPrivateTeamID();
        $noteTeamSet = BeanFactory::newBean('TeamSets');
        $noteteamIdsArray = array($current_user->getPrivateTeamID());
        $note->team_set_id = $noteTeamSet->addTeams($noteteamIdsArray);

        // Copy the file before saving so that the file size is captured during save.
	    $source = 'upload://'.$file_name;
        $destination = "upload://{$note->id}";
        
        if (!copy($source, $destination)){
            $msg = str_replace('$destination', $destination, translate('LBL_RENAME_ERROR', "Quotes"));
            die($msg);
        }

        @unlink($source);

        $note->save();
        $email_object->attachments->add($note);

        //return the email id
        return $email_id;
    }
    
    /**
     * Build the template file for smarty to parse
     *
     * @param $pdfTemplate
     * @param $previewMode
     * @return $tpl_filename
     */
    private function buildTemplateFile($pdfTemplate, $previewMode = FALSE)
    {
        if (!empty($pdfTemplate)) {

            if ( ! file_exists(sugar_cached('modules/PdfManager/tpls')) ) {
                mkdir_recursive(sugar_cached('modules/PdfManager/tpls'));
            }
            $tpl_filename = sugar_cached('modules/PdfManager/tpls/' . $pdfTemplate->id . '.tpl');

            if ($previewMode !== FALSE) {
                $tpl_filename = sugar_cached('modules/PdfManager/tpls/' . $pdfTemplate->id . '_preview.tpl');
                $pdfTemplate->body_html = str_replace(array('{', '}'), array('&#123;', '&#125;'), $pdfTemplate->body_html);
            }

            if ($pdfTemplate->base_module == 'Quotes') {

                $pdfTemplate->body_html = str_replace(
                    '$fields.product_bundles',
                    '$bundle',
                    $pdfTemplate->body_html
                );

                $pdfTemplate->body_html = str_replace(
                    '$fields.products',
                    '$product',
                    $pdfTemplate->body_html
                );
                
                $pdfTemplate->body_html = str_replace(
                    '<!--START_BUNDLE_LOOP-->',
                    '{foreach from=$product_bundles item="bundle"}',
                    $pdfTemplate->body_html
                );
                
                $pdfTemplate->body_html = str_replace(
                    '<!--START_PRODUCT_LOOP-->',
                    '{foreach from=$bundle.products item="product"}',
                    $pdfTemplate->body_html
                );
                
                $pdfTemplate->body_html = str_replace(
                    array("<!--END_PRODUCT_LOOP-->", "<!--END_BUNDLE_LOOP-->"),
                    '{/foreach}',
                    $pdfTemplate->body_html
                );
                
            }

            sugar_file_put_contents($tpl_filename, $pdfTemplate->body_html);

            return $tpl_filename;
        }

        return '';
    }

    /**
     * Set the file name and manage the email attachement output
     *
     * @see TCPDF::Output()
     */
    public function Output($name="doc.pdf", $dest='I')
    {
        if (!empty($this->pdfFilename)) {
            $name = $this->pdfFilename;
        }

        // This case is for "email as PDF"
        if (isset($_REQUEST['to_email']) && $_REQUEST['to_email']=="1") {
            // After the output the object is destroy
            
            $bean = $this->bean;

            $tmp = parent::Output('','S');
            $badoutput = ob_get_contents();
            if(strlen($badoutput) > 0) {
                ob_end_clean();
            }
            file_put_contents('upload://'.$name, ltrim($tmp));

            $email_id = $this->buildEmail($name, $bean);

            //redirect
            if($email_id=="") {
                //Redirect to quote, since something went wrong
                echo "There was an error with your request";
                exit; //end if email id is blank
            } else {
                SugarApplication::redirect("index.php?module=Emails&action=Compose&record=".$email_id."&replyForward=true&reply=");
            }
        }

        parent::Output($name, 'D');
    }

    /**
     * PDF manager specific Header function
     */
    public function Header() {
  			$ormargins = $this->getOriginalMargins();
  			$headerfont = $this->getHeaderFont();
  			$headerdata = $this->getHeaderData();
  			if ($headerdata['logo'] && $headerdata['logo'] != K_BLANK_IMAGE) {
                $this->Image($headerdata['logo'], $this->GetX(), $this->getHeaderMargin(), $headerdata['logo_width'], 12);
  				$imgy = $this->getImageRBY();
  			} else {
  				$imgy = $this->GetY();
  			}
  			$cell_height = round(($this->getCellHeightRatio() * $headerfont[2]) / $this->getScaleFactor(), 2);
  			// set starting margin for text data cell
  			if ($this->getRTL()) {
  				$header_x = $ormargins['right'] + ($headerdata['logo_width'] * 1.1);
  			} else {
  				$header_x = $ormargins['left'] + ($headerdata['logo_width'] * 1.1);
  			}
  			$this->SetTextColor(0, 0, 0);
  			// header title
  			$this->SetFont($headerfont[0], 'B', $headerfont[2] + 1);
  			$this->SetX($header_x);
  			$this->Cell(0, $cell_height, $headerdata['title'], 0, 1, '', 0, '', 0);
  			// header string
  			$this->SetFont($headerfont[0], $headerfont[1], $headerfont[2]);
  			$this->SetX($header_x);
  			$this->MultiCell(0, $cell_height, $headerdata['string'], 0, '', 0, 1, '', '', true, 0, false);
  			// print an ending header line
  			$this->SetLineStyle(array('width' => 0.85 / $this->getScaleFactor(), 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
  			$this->SetY((2.835 / $this->getScaleFactor()) + max($imgy, $this->GetY()));
  			if ($this->getRTL()) {
  				$this->SetX($ormargins['right']);
  			} else {
  				$this->SetX($ormargins['left']);
  			}
  			$this->Cell(0, 0, '', 'T', 0, 'C');
  		}

    /**
     * PDF manager specific Footer function
     */
    public function Footer() {
        $cur_y = $this->GetY();
        $ormargins = $this->getOriginalMargins();
        $this->SetTextColor(0, 0, 0);
        //set style for cell border
        $line_width = 0.85 / $this->getScaleFactor();
        $this->SetLineStyle(array('width' => $line_width, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
        //print document barcode
        $barcode = $this->getBarcode();
        if (!empty($barcode)) {
            $this->Ln($line_width);
            $barcode_width = round(($this->getPageWidth() - $ormargins['left'] - $ormargins['right'])/3);
            $this->write1DBarcode($barcode, 'C128B', $this->GetX(), $cur_y + $line_width, $barcode_width, (($this->getFooterMargin() / 3) - $line_width), 0.3, '', '');
        }
        if (empty($this->pagegroups)) {
            $pagenumtxt = $this->l['w_page'].' '.$this->getAliasNumPage().' / '.$this->getAliasNbPages();
        } else {
            $pagenumtxt = $this->l['w_page'].' '.$this->getPageNumGroupAlias().' / '.$this->getPageGroupAlias();
        }
        $this->SetY($cur_y);

        if ($this->getRTL()) {
            $this->SetX($ormargins['right']);
            if ($this->footerText) {
                // footer text and page number
                $this->Cell(0, 0, $this->footerText, 'T', 0, 'R');
                $this->Cell(0, 0, $pagenumtxt, 0, 0, 'L');
            } else {
                // page number only
                $this->Cell(0, 0, $pagenumtxt, 'T', 0, 'L');
            }
        } else {
            $this->SetX($ormargins['left']);
            if ($this->footerText) {
                // footer text and page number
                $this->Cell(0, 0, $this->footerText, 'T', 0, 'L');
                $this->Cell(0, 0, $pagenumtxt, 0, 0, 'R');
            } else {
                // page number only
                $this->Cell(0, 0, $pagenumtxt, 'T', 0, 'R');
            }
        }
    }
}
