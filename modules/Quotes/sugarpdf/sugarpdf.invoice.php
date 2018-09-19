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


class QuotesSugarpdfInvoice extends QuotesSugarpdfStandard{
    
    function preDisplay(){
        global $mod_strings, $timedate;
        parent::preDisplay();
        
        $quote[0]['TITLE'] = $mod_strings['LBL_PDF_INVOICE_NUMBER'];
        $quote[1]['TITLE'] = $mod_strings['LBL_PDF_QUOTE_DATE'];
        $quote[2]['TITLE'] = $mod_strings['LBL_PURCHASE_ORDER_NUM'];
        $quote[3]['TITLE'] = $mod_strings['LBL_PAYMENT_TERMS'];
        $quote[0]['VALUE']['value'] = format_number_display($this->bean->quote_num);
        $quote[1]['VALUE']['value'] = $timedate->nowDate();
 	$quote[2]['VALUE']['value'] = $this->bean->purchase_order_num;
        $quote[3]['VALUE']['value'] = $this->bean->payment_terms;
        // these options override the params of the $options array.
        $quote[0]['VALUE']['options'] = array();
        $quote[1]['VALUE']['options'] = array();
        $quote[2]['VALUE']['options'] = array();
        $quote[3]['VALUE']['options'] = array();

        $html = $this->writeHTMLTable($quote, true, $this->headerOptions);
        $this->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $mod_strings['LBL_PDF_INVOICE_TITLE'], $html);
    }
    
    /**
     * This method build the name of the PDF file to output.
     */
    function buildFileName(){
        global $mod_strings;
        
        $fileName = preg_replace("#[^A-Z0-9\-_\.]#i", "_", $this->bean->shipping_account_name);
        
        if (!empty($this->bean->quote_num)) {
            $fileName .= "_{$this->bean->quote_num}";
        }
        
        $fileName = $mod_strings['LBL_INVOICE']."_{$fileName}.pdf";
        
        if(isset($_SERVER['HTTP_USER_AGENT']) && preg_match("/MSIE/", $_SERVER['HTTP_USER_AGENT'])) {
            //$fileName = $locale->translateCharset($fileName, $locale->getExportCharset());
            $fileName = urlencode($fileName);
        }
        
        $this->fileName = $fileName;
    }
}
