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
 * This engine is now deprecated. Use the sucrose chart engine instead.
 * @deprecated This file will removed in a future release.
 */
class nvd3Reports extends nvd3
{

    private $processed_report_keys = array();

    function __construct()
    {
        parent::__construct();
    }

    function calculateReportGroupTotal($dataset)
    {
        $total = 0;
        foreach ($dataset as $value) {
            $total += $value['numerical_value'];
        }

        return $total;
    }

    /**
     * Method checks is our dataset from currency field or not
     *
     * @param array $dataset of chart
     * @return bool is currency
     */
    public function isCurrencyReportGroupTotal(array $dataset)
    {
        $isCurrency = true;
        foreach ($dataset as $value) {
            if (empty($value['numerical_is_currency'])) {
                $isCurrency = false;
                break;
            }
        }
        return $isCurrency;
    }

    function processReportData($dataset, $level = 1, $first = false)
    {
        $data = '';
        $this->handleSort($this->super_set);

        // rearrange $dataset to get the correct order for the first row
        if ($first) {
            $temp_dataset = array();
            foreach ($this->super_set as $key) {
                $temp_dataset[$key] = (isset($dataset[$key])) ? $dataset[$key] : array();
            }
            $dataset = $temp_dataset;
        }

        foreach ($dataset as $key => $value) {
            if ($first && empty($value)) {
                $data .= $this->processDataGroup(4, $key, 'NULL', '', '');
            } elseif (array_key_exists('numerical_value', $dataset)) {
                $link = (isset($dataset['link'])) ? '#'.$dataset['link'] : '';
                $data .= $this->processDataGroup($level, $dataset['group_base_text'], $dataset['numerical_value'], $dataset['numerical_value'], $link);
                array_push($this->processed_report_keys, $dataset['group_base_text']);
                return $data;
            } else {
                $data .= $this->processReportData($value, $level+1);
            }
        }

        return $data;
    }

    function processReportGroup($dataset)
    {
        $super_set = array();
        $super_set_data = array();

        foreach ($dataset as $groupBy => $groups) {
            $prev_super_set = $super_set;
            foreach ($groups as $group => $groupData) {
                $super_set_data[$group] = $groupData;
            }
            if (count($groups) > count($super_set)) {
                $super_set = array_keys($groups);
                foreach ($prev_super_set as $prev_group) {
                    if (!in_array($prev_group, $groups)) {
                        array_push($super_set, $prev_group);
                    }
                }
            } else {
                foreach ($groups as $group => $groupData) {
                    if (!in_array($group, $super_set)) {
                        array_push($super_set, $group);
                    }
                }
            }
        }
        $super_set = array_unique($super_set);
        $this->super_set_data = $super_set_data;

        $this->handleSort($super_set);

        return $super_set;
    }

    /**
     * Handle sorting for special field types on grouped data.
     *
     * @param array &$super_set Grouped data
     */
    protected function handleSort(&$super_set)
    {
        if (!isset($this->reporter)) {
            return;
        }

        // store last grouped field
        $lastgroupfield = end($this->group_by);

        if ($this->isDateSort($lastgroupfield)) {
            usort($super_set, array($this, "runDateSort"));
        } else {
            asort($super_set);
        }
    }

    /**
     * Checks if the field is a date and needs to be sorted as a date
     * @param $field
     * @return bool
     */
    protected function isDateSort($field)
    {
        if (isset($this->reporter->focus->field_defs[$field])) {
            $dateTypes = array('date', 'datetime', 'datetimecombo');
            $type = $this->reporter->focus->field_defs[$field]['type'];
            return in_array($type, $dateTypes);
        }
        return false;
    }

    /**
     * Helper function for sorting dates.
     *
     * @param DateTime $a Date 1
     * @param DateTime $b Date 2
     * @return int an integer LT, EQ, or GT zero if Date 1 is respectively LT, EQ, or GT Date 2
     */
    protected function runDateSort($a, $b)
    {
        $a = new DateTime($this->super_set_data[$a]['raw_value']);
        $b = new DateTime($this->super_set_data[$b]['raw_value']);

        if ($a == $b) {
            return 0;
        }

        return ($a < $b) ? -1 : 1;
    }

    function xmlDataReportSingleValue()
    {
        $data = '';
        foreach ($this->data_set as $key => $dataset) {
            $total = $this->calculateReportGroupTotal($dataset);
            $this->checkYAxis($total);

            $data .= $this->tab('<group>', 2);
            $data .= $this->tabValue('title', $key, 3);
            $data .= $this->tab('<subgroups>', 3);
            $data .= $this->tab('<group>', 4);
            $data .= $this->tabValue('title', $total, 5);
            $data .= $this->tabValue('value', $total, 5);
            $data .= $this->tabValue('label', $key, 5);
            $data .= $this->tab('<link></link>', 5);
            $data .= $this->tab('</group>', 4);
            $data .= $this->tab('</subgroups>', 3);
            $data .= $this->tab('</group>', 2);
        }
        return $data;
    }

    function xmlDataReportChart()
    {
        global $app_strings;
        $data = '';
        // correctly process the first row
        $first = true;
        foreach ($this->data_set as $key => $dataset) {

            $total = $this->calculateReportGroupTotal($dataset);
            $this->checkYAxis($total);

            $data .= $this->tab('<group>', 2);
            $data .= $this->tabValue('title', $key, 3);
            $data .= $this->tabValue('value', $total, 3);

            $label = $total;
            if ($this->isCurrencyReportGroupTotal($dataset)) {
                $label = currency_format_number($total, array(
                    'currency_symbol' => $this->currency_symbol,
                    'decimals' => ($this->chart_properties['thousands'] ? 0 : null)
                ));
            }
            if ($this->chart_properties['thousands']) {
                $label .= $app_strings['LBL_THOUSANDS_SYMBOL'];
            }
            $data .= $this->tabValue('label', $label, 3);

            $data .= $this->tab('<subgroups>', 3);

            if ((isset($dataset[$total]) && $total != $dataset[$total]['numerical_value'])
                || !array_key_exists($key, $dataset) || $key == "") {
                    $data .= $this->processReportData($dataset, 4, $first);
            } elseif (count($this->data_set) == 1 && $first) {
                foreach ($dataset as $k => $v) {
                    if (isset($v['numerical_value'])) {
                        $data .= $this->processDataGroup(4, $k, $v['numerical_value'], $v['numerical_value'], '');
                    }
                }
            }

            if (!$first) {
                $not_processed = array_diff($this->super_set, $this->processed_report_keys);
                $processed_diff_count = count($this->super_set) - count($not_processed);

                if ($processed_diff_count != 0) {
                    foreach ($not_processed as $title) {
                        $data .= $this->processDataGroup(4, $title, 'NULL', '', '');
                    }
                }
            }

            $data .= $this->tab('</subgroups>', 3);
            $data .= $this->tab('</group>', 2);
            $this->processed_report_keys = array();
            // we're done with the first row!
            //$first = false;
        }
        return $data;
    }

    public function processXmlData()
    {
        $data = '';

        $this->super_set = $this->processReportGroup($this->data_set);
        $single_value = false;

        foreach ($this->data_set as $key => $dataset) {
            if ((isset($dataset[$key]) && count($this->data_set[$key]) == 1)) {
                $single_value = true;
            } else {
                $single_value = false;
            }
        }
        if ($this->chart_properties['type'] == 'line chart' && $single_value) {
            $data .= $this->xmlDataReportSingleValue();
        } else {
            $data .= $this->xmlDataReportChart();
        }

        return $data;
    }

    /**
     * wrapper function to return the html code containing the chart in a div
     *
     * @param     string $name     name of the div
     *            string $xmlFile    location of the XML file
     *            string $style    optional additional styles for the div
     * @return    string returns the html code through smarty
     */
    function display($name, $xmlFile, $width = '320', $height = '480', $resize = false)
    {
        $GLOBALS['log']->deprecated('The nvd3 chart engine is deprecated.');

        if (empty($name)) {
            $name = "unsavedReport";
        }

        return parent::display($name, $xmlFile, $width, $height, $resize = false);

    }
}
