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


class ReportsSugarpdfListview extends ReportsSugarpdfReports
{
    function display(){
        global $report_modules, $app_list_strings;
        global $mod_strings, $locale;
        $this->bean->run_query();
        
        $this->AddPage();
        
        $item = array();
        $header_row = $this->bean->get_header_row('display_columns', false, false, true);
        $count = 0;
    
        while($row = $this->bean->get_next_row('result', 'display_columns', false, true)) {
            for($i= 0 ; $i < sizeof($header_row); $i++) {
                $label = $header_row[$i];
                $value = '';
                if (isset($row['cells'][$i])) {
                    $value = $row['cells'][$i];
                }
                $item[$count][$label] = $value;
            }
            $count++;
        }
        
        $this->writeCellTable($item, $this->options);
    }
}


