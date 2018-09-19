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

abstract class SugarForecasting_Export_AbstractExport extends SugarForecasting_AbstractForecastArgs implements SugarForecasting_ForecastProcessInterface
{

    /**
     * Where we store the data we want to use
     *
     * @var array
     */
    protected $dataArray = array();

    /**
     * Class Constructor
     * @param array $args       Service Arguments
     */
    public function __construct($args)
    {
        if (!empty($args['dataset'])) {
            $this->dataset = $args['dataset'];
        }

        parent::__construct($args);
    }

    /**
     * Return the data array
     *
     * @return array
     */
    public function getDataArray()
    {
        return $this->dataArray;
    }


    /**
     * getContent
     *
     * Returns all the content for the export
     *
     * @param $data
     * @param $focus
     * @param $fields_array
     * @param string $filter_by     What field that should be filtered on
     * @param array $filters        The values to check $filter_by
     *
     * @return string content for the export file
     */
    protected function getContent($data, $focus, $fields_array, $filter_by = null, array $filters = array())
    {
        require_once('include/export_utils.php');
        $fields_array = get_field_order_mapping($focus->object_name, $fields_array);

        foreach ($fields_array as $key => $label) {
             $fields_array[$key] = translateForExport($label, $focus);
        }

        // setup the "header" line with proper delimiters
        $content = "\"".implode("\"".getDelimiter()."\"", array_values($fields_array))."\"\r\n";

        if (!empty($data)) {
            $content .= $this->getExportDataContent($data, $focus, $fields_array, $filter_by, $filters);
        }

        return $content;
    }

    /**
     * getExportDataContent
     *
     * Returns the export content for the data portion
     *
     * @param $data
     * @param $focus
     * @param $fields_array
     * @param string $filter_by     What field that should be filtered on
     * @param array $filters        The values to check $filter_by
     *
     * @return string content for the data portion of export
     */
    protected function getExportDataContent($data, $focus, $fields_array, $filter_by = null, array $filters = array())
    {
        require_once('include/export_utils.php');

        global $current_user;
        $content = '';
        $delimiter = getDelimiter();

        //process retrieved record
        $isAdminUser = is_admin($current_user);

        foreach ($data as $val) {
            if (!empty($filter_by)  // make sure we have something to filter by
                && !empty($filters) // make sure we have filters
                && isset($val[$filter_by]) // make sure the row has what we are filtering by
                && !in_array($val[$filter_by], $filters)) {     // make sure the value from the row is in the filters
                // skip this row as it's not in the filters
                continue;
            }

            $new_arr = array();

            if (!$isAdminUser) {
                $focus->id = (!empty($val['id']))?$val['id']:'';
                $focus->assigned_user_id = (!empty($val['assigned_user_id']))?$val['assigned_user_id']:'' ;
                $focus->created_by = (!empty($val['created_by']))?$val['created_by']:'';
                $focus->ACLFilterFieldList($val, array(), array("blank_value" => true));
            }

            foreach ($fields_array as $key => $label) {
                $value = $val[$key];

                //getting content values depending on their types
                if (isset($focus->field_defs[$key])) {
                    $sfh = SugarFieldHandler::getSugarField($focus->field_defs[$key]['type']);
                    $value = $sfh->exportSanitize($value, $focus->field_defs[$key], $focus, $val);
                }

                $new_arr[$key] = preg_replace("/\"/", "\"\"", $value);
            }

            $line = implode("\"". $delimiter ."\"", $new_arr);
            $content .= "\"" . $line . "\"\r\n";
        }

        return $content;
    }


    /**
     * getFilename
     *
     * @return string name of the filename to export contents into
     */
    public function getFilename()
    {
        $timePeriod = BeanFactory::newBean('TimePeriods');
        $timePeriod->retrieve($this->args['timeperiod_id']);
        return str_replace(' ', '_', $timePeriod->name);
    }


    /**
     * export
     *
     * @param $contents String value of the file contents
     */
    public function export($contents)
    {
        global $locale;
        $filename = $this->getFilename();
        header("Content-Disposition: attachment; filename=\"{$filename}\"");
        header("Content-Type: text/x-csv");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . TimeDate::httpTime());
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Content-Length: ".mb_strlen($locale->translateCharset($contents, 'UTF-8', $locale->getExportCharset())));
        echo $locale->translateCharset($contents, 'UTF-8', $locale->getExportCharset());
    }
}
