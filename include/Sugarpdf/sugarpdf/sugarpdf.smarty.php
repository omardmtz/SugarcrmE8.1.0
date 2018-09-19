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


/**
 * This is an helper class to generate PDF using smarty template.
 * You have to extend this class, set the templateLocation to your smarty template
 * location and assign the Smarty variables ($this->ss->assign()) in the overriden
 * preDisplay method (don't forget to call the parent).
 * 
 * @author bsoufflet
 *
 */
class SugarpdfSmarty extends Sugarpdf{
    
    /**
     * 
     * @var String
     */
    protected $templateLocation = "";
    /**
     * The Sugar_Smarty object
     * @var Sugar_Smarty
     */
    protected $ss;
    /**
     * These 5 variables are use for the writeHTML method.
     * @see vendor/tcpdf/tcpdf.php writeHTML()
     */
    protected $smartyLn = true;
    protected $smartyFill = false;
    protected $smartyReseth = false;
    protected $smartyCell = false;
    protected $smartyAlign = "";
    
    function preDisplay(){
        parent::preDisplay();
        $this->print_header = false;
        $this->print_footer = false;
        $this->_initSmartyInstance();
    }
    
    function display(){
        //turn off all error reporting so that PHP warnings don't munge the PDF code
        error_reporting(E_ALL);
        set_time_limit(1800);
        
        //Create new page           
        $this->AddPage();
        $this->SetFont(PDF_FONT_NAME_MAIN,'',8);
        
        if(!empty($this->templateLocation)){
            $str = $this->ss->fetch($this->templateLocation);
            $this->writeHTML($str, $this->smartyLn, $this->smartyFill, $this->smartyReseth, $this->smartyCell, $this->smartyAlign);
        }else{
            $this->Error('The class SugarpdfSmarty has to be extended and you have to set a location for the Smarty template.');
        }
    }
    
    /**
     * Init the Sugar_Smarty object.
     */
    private function _initSmartyInstance(){
        if ( !($this->ss instanceof Sugar_Smarty) ) {
            $this->ss = new Sugar_Smarty();
            // TODO: Remove after MAR-1064 is merged.
            // Enable enhanced security for user-provided templates. This
            // includes disabling the {php} Smarty tag.
            $this->ss->security = true;
            if (defined('SUGAR_SHADOW_PATH')) {
                $this->ss->secure_dir[] = SUGAR_SHADOW_PATH;
            }

            $this->ss->assign('MOD', $GLOBALS['mod_strings']);
            $this->ss->assign('APP', $GLOBALS['app_strings']);
        }
    }

    /*
     * @see TCPDF::Image()
     */
    public function Image($file, $x = '', $y = '', $w = 0, $h = 0, $type = '', $link = '', $align = '', $resize = false, $dpi = 300, $palign = '', $ismask = false, $imgmask = false, $border = 0, $fitbox = false)
    {
        // strip query string from the image if it exists in the file name, tcpdf doesn't like 'query strings as part of the image file name
        //a sample image string is:  http://d2owqhhe2x3j50.cloudfront.net/images/sugar7/home/concept1_1.png?version=3.0.3

        //grab the base name and search for the '?' character
        $fileinfo = pathinfo($file);
        $q_pos = strpos($fileinfo['basename'], '?');

        if (!empty($fileinfo['basename']) && $q_pos !== false ) {
            //split the file name and reassemble
            $splitName = substr($fileinfo['basename'], 0, $q_pos);
            $file = $fileinfo['dirname'] . '/' . $splitName;
        }

        return parent::Image($file, $x, $y, $w, $h, $type, $link, $align, $resize, $dpi, $palign, $ismask, $imgmask, $border, $fitbox);
    }
    
}
