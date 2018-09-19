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



class ReportsSugarpdfSummary_combo extends ReportsSugarpdfReports
{
    function display()
    {
        global $app_list_strings, $locale;

        //add chart
        if (isset($_REQUEST['id']) && $_REQUEST['id'] != false) {
            $this->bean->is_saved_report = true;
        }
        // fixing bug #47260: Print PDF Reports
        // if chart_type is 'none' we don't need do add any image to pdf
        if ($this->bean->chart_type != 'none') {
            $chartDisplay = new ChartDisplay();
            $xmlFile = $chartDisplay->get_cache_file_name($this->bean);

            $sugarChart = SugarChartFactory::getInstance();

            if ($sugarChart->supports_image_export) {
                $imageFile = $sugarChart->get_image_cache_file_name($xmlFile, ".".$sugarChart->image_export_type);
                // check image size is not '0'
                if (file_exists($imageFile) && getimagesize($imageFile) > 0) {
                    $this->AddPage();
                    list($width, $height) = getimagesize($imageFile);
                    $imageWidthAsUnits = $this->pixelsToUnits($width);
                    $imageHeightAsUnits = $this->pixelsToUnits($height);

                    $dimensions = $this->getPageDimensions();

                    $pageWidth = $dimensions['wk'];
                    $pageHeight = $dimensions['hk'];

                    $marginTop = $dimensions['tm'];
                    $marginBottom = $dimensions['bm'];
                    $marginLeft = $dimensions['lm'];
                    $marginRight = $dimensions['rm'];

                    $freeWidth = 0.9 * ($pageWidth - $marginLeft - $marginRight);
                    $freeHeight = 0.9 * ($pageHeight - $marginTop - $marginBottom);

                    $rate = min($freeHeight / $imageHeightAsUnits, $freeWidth / $imageWidthAsUnits, 2);
                    $imageWidth = floor($rate * $imageWidthAsUnits);
                    $imageHeight = floor($rate * $imageHeightAsUnits);

                    $leftOffset = $this->GetX() + ($freeWidth - $imageWidth) / 2;
                    $topOffset = $this->GetY() + ($freeHeight - $imageHeight) / 2;

                    $this->Image($imageFile, $leftOffset, $topOffset, $imageWidth, $imageHeight, "", "", "N", false, 300, "", false, false, 0, true);

                    if ($sugarChart->print_html_legend_pdf) {
                        $legend = $sugarChart->buildHTMLLegend($xmlFile);
                        $this->writeHTML($legend, true, false, false, true, "");
                    }
                }
            }
        }

        $this->AddPage();

        //disable paging so we get all results in one pass
        $this->bean->enable_paging = false;
        $cols = count($this->bean->report_def['display_columns']);
        $this->bean->run_summary_combo_query();

        $header_row = $this->bean->get_summary_header_row();
        $columns_row = $this->bean->get_header_row();

        // build options for the writeHTMLTable from options for the writeCellTable
        $options = array('header' =>
            array(
                "tr" => array(
                    "bgcolor" => $this->options['header']['fill'],
                    "color"   => $this->options['header']['textColor']),
                "td"      => array()
            ),
            'evencolor' => $this->options['evencolor'],
            'oddcolor' => $this->options['oddcolor'],
        );

        while (($row = $this->bean->get_summary_next_row()) != 0) {
            // summary columns
            $item = array();
            $count = 0;

            for ($j=0; $j < sizeof($row['cells']); $j++) {
                if ($j > count($header_row) - 1) {
                    $label = $header_row[count($header_row) - 1];
                } else {
                    $label = $header_row[$j];
                }
                if (preg_match('/type.*=.*checkbox/Uis', $row['cells'][$j])) { // parse out checkboxes
                    if (preg_match('/checked/i', $row['cells'][$j])) {
                        $row['cells'][$j] = $app_list_strings['dom_switch_bool']['on'];
                    } else {
                        $row['cells'][$j] = $app_list_strings['dom_switch_bool']['off'];
                    }
                }
                $value = $row['cells'][$j];
                $item[$count][$label] = $value;
            }

            foreach ($item as $i) {
                $text = '';
                foreach ($i as $l => $j) {
                    if ($text) {
                        $text .= ', ';
                    }
                    $text .= $l . ' = ' . $j;
                }
                $this->Write(1, $text);
                $this->Ln1();
            }

            // display columns
            $item = array();
            $count = 0;

            for ($i=0; $i < $this->bean->current_summary_row_count; $i++) {
                if (($column_row = $this->bean->get_next_row('result', 'display_columns', false, true)) != 0) {
                    for ($j=0; $j < sizeof($columns_row); $j++) {
                        $label = $columns_row[$j];
                        $item[$count][$label] = $column_row['cells'][$j];
                    }
                    $count++;
                } else {
                    break;
                }
            }

            $this->writeHTMLTable($item, false, $options);
            $this->Ln1();
        }

        $this->bean->clear_results();

        if ($this->bean->has_summary_columns()) {
            $this->bean->run_total_query();
            $total_row = $this->bean->get_summary_total_row();
            $item = array();
            $count = 0;

            for ($j=0; $j < sizeof($header_row); $j++) {
                $label = $header_row[$j];
                $value = $total_row['cells'][$j];
                $item[$count][$label] = $value;
            }

            $this->writeHTMLTable($item, false, $options);
        }

    }
}
