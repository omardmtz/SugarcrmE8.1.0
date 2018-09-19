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


class ReportsSugarpdfTotal extends ReportsSugarpdfReports
{
    function display(){
        global $locale;
    
        //Create new page
        $this->AddPage();
        
        $this->bean->clear_results();
        $this->bean->run_total_query();
    
        $total_header_row = $this->bean->get_total_header_row(true);
        $total_row = $this->bean->get_summary_total_row(true);
    
        $item = array();
        $count = 0;
        for($j=0; $j < sizeof($total_header_row); $j++) {
          $label = $total_header_row[$j];
          $value = $total_row['cells'][$j];
          $item[$count][$label] = $value;
        }
        
        $this->writeCellTable($item, $this->options);
    }
}


