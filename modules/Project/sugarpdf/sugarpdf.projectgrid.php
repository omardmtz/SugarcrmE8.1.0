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



use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;

class ProjectSugarpdfProjectgrid extends Sugarpdf{
     /**
     * Options array for the header table.
     * @var Array
     */
    private $headerOptions;
     /**
     * Options array for the body table.
     * @var Array
     */
    private $bodyOptions;

    /**
     * Set the options array for the layout of the pdf
     */
    private function _initOptions(){
        global $mod_strings;
        $this->headerOptions = array(
                      "isheader"=>false,
                      "TD"=>array("bgcolor"=>"#DCDCDC"),
                      "table"=>array("cellspacing"=>"2", "border"=>"0"),
                      "width"=>array("TITLE"=>"50%", "VALUE"=>"150%"),
        );
        $this->bodyOptions = array(
            "evencolor"=>"#DCDCDC",
            "header"=>array("fill"=>"#4B4B4B", "fontStyle"=>"B", "textColor"=>"#FFFFFF"),
            "width"=>array(
                $mod_strings['LBL_TASK_ID']=>"4%",
                $mod_strings['LBL_PERCENT_COMPLETE']=>"8%",
                $mod_strings['LBL_TASK_NAME']=>"36%",
                $mod_strings['LBL_DURATION']=>"8%",
                $mod_strings['LBL_START']=>"8%",
                $mod_strings['LBL_FINISH']=>"8%",
                $mod_strings['LBL_PREDECESSORS']=>"8%",
                $mod_strings['LBL_RESOURCE_NAMES']=>"10%",
                $mod_strings['LBL_ACTUAL_DURATION']=>"10%",
            ),
        );
    }
    /**
     * Custom header for Projectgrid
     */
    public function Header() {
        $ormargins = $this->getOriginalMargins();
        $headerfont = $this->getHeaderFont();
        $headerdata = $this->getHeaderData();

        if (($headerdata['logo']) AND ($headerdata['logo'] != K_BLANK_IMAGE)) {
            $logo = K_PATH_CUSTOM_IMAGES.$headerdata['logo'];
            $imsize = @getimagesize($logo);
            if ($imsize === FALSE) {
                // encode spaces on filename
                $logo = str_replace(' ', '%20', $logo);
                $imsize = @getimagesize($logo);
                if ($imsize === FALSE) {
                    $logo = K_PATH_IMAGES.$headerdata['logo'];
                }
            }
            $this->Image($logo, '', $this->getHeaderMargin(), $headerdata['logo_width'], 0, '', '', '', false, 300, 'R');
            
        }
        
        // This table split the header in 3 equal parts. The first part (on the left) contain the header text.
        $table[0]["data"]=$headerdata['string'];
        $table[0]["logo"]="";
        $table[0]["blank"]="";
        $options = array(
            "isheader"=>false,
        );
        $this->SetTextColor(0, 0, 0);
        // header string
        $this->SetFont($headerfont[0], $headerfont[1], $headerfont[2]);
        // Start overwrite
        $this->writeHTMLTable($table, false, $options);
    }
    
    function preDisplay(){
        global $mod_strings, $timedate;
        parent::preDisplay();
        
        $this->_initOptions();
        //Force landscape orientation
        $this->setPageFormat(PDF_PAGE_FORMAT, "L");
        
        // Header
        $grid[0]['TITLE'] = $mod_strings['LBL_PDF_PROJECT_NAME'];
        $grid[1]['TITLE'] = $mod_strings['LBL_DATE_START'];
        $grid[2]['TITLE'] = $mod_strings['LBL_DATE_END'];
        $grid[3]['TITLE'] = $mod_strings['LBL_LIST_FILTER_VIEW'];
        $grid[4]['TITLE'] = $mod_strings['LBL_DATE'];

        $request = InputValidation::getService();
        $grid[0]['VALUE']['value'] = $request->getValidInputRequest('project_name');
        $grid[1]['VALUE']['value'] = $request->getValidInputRequest('project_start');
        $grid[2]['VALUE']['value'] = $request->getValidInputRequest('project_end');
        if ($_REQUEST['selected_view'] == '0' || $_REQUEST['selected_view'] == '1')
            $grid[3]['VALUE']['value'] = $mod_strings['LBL_FILTER_ALL_TASKS'];
        else if ($_REQUEST['selected_view'] == '2')
            $grid[3]['VALUE']['value'] = $mod_strings['LBL_FILTER_COMPLETED_TASKS'];
        if ($_REQUEST['selected_view'] == '3')
            $grid[3]['VALUE']['value'] = $mod_strings['LBL_FILTER_INCOMPLETE_TASKS'];
        if ($_REQUEST['selected_view'] == '4')
            $grid[3]['VALUE']['value'] = $mod_strings['LBL_FILTER_MILESTONES'];
        if ($_REQUEST['selected_view'] == '5')
            $grid[3]['VALUE']['value'] = $mod_strings['LBL_FILTER_RESOURCE']. " ".$_REQUEST['view_filter_resource'];
        if ($_REQUEST['selected_view'] == '6')
            $grid[3]['VALUE']['value'] = $mod_strings['LBL_FILTER_DATE_RANGE_START']. " ".$_REQUEST['view_filter_date_start']." ".
            $mod_strings['LBL_FILTER_DATE_RANGE_FINISH']." ".$_REQUEST['view_filter_date_finish'];
        if ($_REQUEST['selected_view'] == '7')
            $grid[3]['VALUE']['value'] = $mod_strings['LBL_LIST_OVERDUE_TASKS']. " ".$_REQUEST['view_filter_resource'];
        if ($_REQUEST['selected_view'] == '8')
            $grid[3]['VALUE']['value'] = $mod_strings['LBL_LIST_UPCOMING_TASKS']. " ".$_REQUEST['view_filter_resource'];
        if ($_REQUEST['selected_view'] == '9')
            $grid[3]['VALUE']['value'] = $mod_strings['LBL_FILTER_MY_TASKS']. " ".$_REQUEST['view_filter_resource'];
        if ($_REQUEST['selected_view'] == '10')
            $grid[3]['VALUE']['value'] = $mod_strings['LBL_FILTER_MY_OVERDUE_TASKS']. " ".$_REQUEST['view_filter_resource'];
        if ($_REQUEST['selected_view'] == '11')
            $grid[3]['VALUE']['value'] = $mod_strings['LBL_FILTER_MY_UPCOMING_TASKS']. " ".$_REQUEST['view_filter_resource'];
        
        $grid[4]['VALUE']['value'] = date("m/d/Y", time());
        
        // these options override the params of the $options array.
        // Because we don't want a background for the 2nd column we have to set ['options'] and redeclare the width.
        $grid[0]['VALUE']['options'] = array("width"=>$this->headerOptions['width']['VALUE']);
        $grid[1]['VALUE']['options'] = array("width"=>$this->headerOptions['width']['VALUE']);
        $grid[2]['VALUE']['options'] = array("width"=>$this->headerOptions['width']['VALUE']);
        $grid[3]['VALUE']['options'] = array("width"=>$this->headerOptions['width']['VALUE']);
        $grid[4]['VALUE']['options'] = array("width"=>$this->headerOptions['width']['VALUE']);
        
        $html = $this->writeHTMLTable($grid, true, $this->headerOptions);
        $this->SetHeaderData(PDF_SMALL_HEADER_LOGO, PDF_SMALL_HEADER_LOGO_WIDTH, "", $html);
    }
    
    function display(){
        global $mod_strings, $app_list_strings;
        
        //Create new page           
        $this->AddPage();
        $this->SetFont(PDF_FONT_NAME_MAIN,'',PDF_FONT_SIZE_MAIN);
        $this->Ln();
        $this->Ln1();
        
        
        if ($_REQUEST['numRowsToSave'] > 0) {
            for ($i = 1; $i <= $_REQUEST['numRowsToSave']; $i++) {
                //$val = $val;
                if (isset($_REQUEST["mapped_row_" . $i])) {
                    $actualRow = $_REQUEST["mapped_row_" . $i]-1;
                    $item[$actualRow][$mod_strings['LBL_TASK_ID']] = $_REQUEST["mapped_row_" . $i];
                    if ($_REQUEST['is_milestone_' . $i])  
                        $item[$actualRow][$mod_strings['LBL_TASK_ID']] .= '*';
                    $item[$actualRow][$mod_strings['LBL_PERCENT_COMPLETE']] = $_REQUEST['percent_complete_' . $i]; 
                    $taskName =  str_replace("&amp;nbsp;"," ",$_REQUEST['description_divlink_input_' . $i]);
                    $item[$actualRow][$mod_strings['LBL_TASK_NAME']] = $taskName;
                    $item[$actualRow][$mod_strings['LBL_DURATION']]['value'] = $_REQUEST["duration_" . $i] . " " . $app_list_strings['project_duration_units_dom'][$_REQUEST["duration_unit_hidden_" . $i]]; 
                    $item[$actualRow][$mod_strings['LBL_DURATION']]['options']=array("align"=>"R");
                    $item[$actualRow][$mod_strings['LBL_START']] = $_REQUEST['date_start_' . $i]; 
                    $item[$actualRow][$mod_strings['LBL_FINISH']] = $_REQUEST['date_finish_' . $i]; 
                    $item[$actualRow][$mod_strings['LBL_PREDECESSORS']] = $_REQUEST['predecessors_' . $i]; 
                    $item[$actualRow][$mod_strings['LBL_RESOURCE_NAMES']] = $_REQUEST['resource_full_name_' . $i];
                    if (!empty($_REQUEST['actual_duration_' . $i]))            
                        $item[$actualRow][$mod_strings['LBL_ACTUAL_DURATION']] = $_REQUEST['actual_duration_' . $i]. " " . $app_list_strings['project_duration_units_dom'][$_REQUEST["duration_unit_hidden_" . $i]];
                    else
                        $item[$actualRow][$mod_strings['LBL_ACTUAL_DURATION']] = $_REQUEST['actual_duration_' . $i];
                } 
            } 
            ksort($item);
        }
        $this->writeCellTable($item, $this->bodyOptions);
        
        $filename = preg_replace("#[^A-Z0-9\-_\.]#i", "_", 'Project');
        $this->fileName = "{$filename}.pdf";
    }

    /**
     * {@inheritDoc}
     *
     * @param string $name Ignored
     * @param string $dest Ignored
     */
    public function Output($name = 'doc.pdf', $dest = 'I')
    {
        // the "D" will ensure forced download instead of displayed in browser
        return parent::Output($this->fileName,"D");
    }
}
