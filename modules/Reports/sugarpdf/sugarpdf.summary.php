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


class ReportsSugarpdfSummary extends ReportsSugarpdfReports
{
    function display()
    {
        global $locale;

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

                    $leftOffset = $this->GetX() + ($pageWidth - $marginLeft - $marginRight - $imageWidth) / 2;
                    $topOffset = $this->GetY();

                    $this->Image($imageFile, $leftOffset, $topOffset, $imageWidth, $imageHeight, "", "", "N", false, 300, "", false, false, 0, true);

                    if ($sugarChart->print_html_legend_pdf) {
                        $legend = $sugarChart->buildHTMLLegend($xmlFile);
                        $this->writeHTML($legend, true, false, false, true, "");
                    }
                }
            }
        }

        //Create new page
        $this->AddPage();

        $this->bean->run_summary_query();
        $item = array();
        $header_row = $this->bean->get_summary_header_row();
        $count = 0;

        if (count($this->bean->report_def['summary_columns']) == 0) {
            $item[$count]['']='';
            $count++;
        }
        if (count($this->bean->report_def['summary_columns']) > 0) {
            while ($row = $this->bean->get_summary_next_row()) {
                for ($i= 0; $i < sizeof($header_row); $i++) {
                    $label = $header_row[$i];
                    $value = '';
                    if (isset($row['cells'][$i])) {
                        $value = $row['cells'][$i];
                    }
                    $item[$count][$label] = $value;

                }
                $count++;
            }
        }

        $this->writeCellTable($item, $this->options);
        $this->Ln1();

        $this->bean->clear_results();

        if ($this->bean->has_summary_columns()) {
            $this->bean->run_total_query();
        }

        $total_header_row = $this->bean->get_total_header_row();
        $total_row = $this->bean->get_summary_total_row();
        $item = array();
        $count = 0;

        for ($j=0; $j < sizeof($total_header_row); $j++) {
            $label = $total_header_row[$j];
            $item[$count][$label] = $total_row['cells'][$j];
        }

        $this->writeCellTable($item, $this->options);

    }
}
