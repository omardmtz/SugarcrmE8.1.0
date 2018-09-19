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



class ReportsSugarpdfReports extends Sugarpdf
{

    /**
     * Maximal Width for header logo
     * @var integer
     */
    private $logoMaxWidth = 348;

    /**
     * Maximal Height for header logo
     * @var integer
     */
    private $logoMaxHeight = 55;

    /**
     * Options array for the writeCellTable method of reports.
     * @var Array
     */
    protected $options = array(
        "evencolor"=>"#DCDCDC",
        "oddcolor" => "#FFFFFF",
        "header"=>array("fill"=>"#4B4B4B", "fontStyle"=>"B", "textColor"=>"#FFFFFF"),
    );
    
    function preDisplay(){
        global $app_list_strings, $locale, $timedate;
        
        parent::preDisplay();
        
        //Set landscape orientation
        $this->setPageFormat(PDF_PAGE_FORMAT, "L");
        
        $this->SetFont(PDF_FONT_NAME_MAIN,'',PDF_FONT_SIZE_MAIN);
        
        //Set PDF document properties
   		if($this->bean->name == "untitled") {
            $this->SetHeaderData(PDF_SMALL_HEADER_LOGO, PDF_SMALL_HEADER_LOGO_WIDTH, $app_list_strings['moduleList'][$this->bean->module], $timedate->getNow(true));
        } else {
            $this->SetHeaderData(PDF_SMALL_HEADER_LOGO, PDF_SMALL_HEADER_LOGO_WIDTH, $this->bean->name, $timedate->getNow(true));
        }
        $cols = count($this->bean->report_def['display_columns']);
    }

    /**
     * Custom header for Reports
     * @return void
     */
    public function Header()
    {
        $ormargins = $this->getOriginalMargins();
        $headerfont = $this->getHeaderFont();
        $headerdata = $this->getHeaderData();

        if (($headerdata['logo']) AND ($headerdata['logo'] != K_BLANK_IMAGE))
        {
            $logo = K_PATH_CUSTOM_IMAGES.$headerdata['logo'];
            $imsize = @getimagesize($logo);
            if ($imsize === FALSE)
            {
                // encode spaces on filename
                $logo = str_replace(' ', '%20', $logo);
                $imsize = @getimagesize($logo);
                if ($imsize === FALSE) {
                    $logo = K_PATH_IMAGES.$headerdata['logo'];
                    $imsize = @getimagesize($logo);
                }
            }
            //resize image
            if ( $imsize )
            {
                $this->Image($logo, $this->GetX(), $this->getHeaderMargin(), $this->logoMaxWidth, $this->logoMaxHeight, '', '', '', true);
            }

            $imgy = $this->getImageRBY();

        }
        // This table split the header in 3 parts of equal width. The last part (on the right) contain the header text.
        $table[0]["logo"]="";
        $table[0]["blank"]="";
        $table[0]["data"]="<div><font face=\"".$headerfont[0]."\" size=\"".($headerfont[2]+1)."\"><b>".$headerdata['title']."</b></font></div>".$headerdata['string'];
        $options = array(
            "isheader"=>false,
        );

        $this->SetTextColor(0, 0, 0);
        // header string
        $this->SetFont($headerfont[0], $headerfont[1], $headerfont[2]);
        // Start overwrite
        $this->writeHTMLTable($table, false, $options);

        // print an ending header line
        $this->SetLineStyle(array('width' => 0.85 / $this->getScaleFactor(), 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
        $this->SetY((2.835 / $this->getScaleFactor()) + max($imgy, $this->GetY()));
        $this->SetX($ormargins['left']);
        $this->Cell(0, 0, '', 'T', 0, 'C');
    }
}


