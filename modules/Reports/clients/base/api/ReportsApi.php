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


class ReportsApi extends ModuleApi
{
    public function registerApiRest()
    {
        return array(
            'recordListCreate' => array(
                'reqType' => 'POST',
                'path' => array('Reports', '?', 'record_list'),
                'pathVars' => array('', 'record', ''),
                'method' => 'createRecordList',
                'shortHelp' => 'An API to create a record list from a saved report',
                'longHelp' => 'modules/Reports/api/help/module_recordlist_post.html',
            ),
            'getReportRecords' => array(
                'reqType' => 'GET',
                'path' => array('Reports', '?', 'records'),
                'pathVars' => array('module', 'record', ''),
                'method' => 'getReportRecords',
                'jsonParams' => array('group_filters'),
                'shortHelp' => 'An API to deliver filtered records from a saved report',
                'longHelp' => 'modules/Reports/clients/base/api/help/report_records_get_help.html',
                'exceptions' => array(
                    // Thrown in getReportRecord
                    'SugarApiExceptionNotFound',
                    // Thrown in getReportRecords
                    'SugarApiExceptionInvalidParameter',
                ),
            ),
            'getRecordCount' => array(
                'reqType' => 'GET',
                'path' => array('Reports', '?', 'record_count'),
                'pathVars' => array('module', 'record', ''),
                'method' => 'getRecordCount',
                'jsonParams' => array('group_filters'),
                'shortHelp' => 'An API to get total number of filtered records from a saved report',
                'longHelp' => 'modules/Reports/clients/base/api/help/report_recordcount_get_help.html',
                'exceptions' => array(
                    // Thrown in getReportRecord
                    'SugarApiExceptionNotFound',
                    // Thrown in getReportRecords
                    'SugarApiExceptionInvalidParameter',
                ),
            ),
            'getSavedReportChartById' => array(
                'reqType' => 'GET',
                'path' => array('Reports', '?', 'chart'),
                'pathVars' => array('module', 'record', ''),
                'method' => 'getSavedReportChartById',
                'shortHelp' => 'An API to get chart data for a saved report',
                'longHelp' => 'modules/Reports/clients/base/api/help/report_chart_get_help.html',
            ),
        );
    }

    /**
     * Creates a record list from a saved report
     * @param $api ServiceBase The API class of the request, used in cases where the API changes how the fields are pulled from the args array.
     * @param $args array The arguments array passed in from the API containing the module and the records
     * @throws SugarApiExceptionNotFound
     * @throws SugarApiException
     * @return array id, module, records
     */
    public function createRecordList(ServiceBase $api, array $args)
    {
        $savedReport = $this->getReportRecord($api, $args);
        $reportDef = json_decode($savedReport->content, true);
        $recordIds = $this->getRecordIdsFromReport($reportDef);
        $id = RecordListFactory::saveRecordList($recordIds, 'Reports');
        $loadedRecordList = RecordListFactory::getRecordList($id);

        return $loadedRecordList;
    }

    /**
     * Gets group field def.
     * @param array $reportDef
     * @param string $field
     * @return array|boolean
     */
    protected function getGroupFilterFieldDef($reportDef, $field)
    {
        $pos = strrpos($field, ':');
        if ($pos !== false) {
            $field_name = substr($field, $pos + 1);
            $table_key = substr($field, 0, $pos);
        } else {
            $table_key = 'self';
            $field_name = $field;
        }
        if (is_array($reportDef['group_defs'])) {
            $report = null;
            foreach ($reportDef['group_defs'] as $groupColumn) {
                if ($groupColumn['table_key'] === $table_key && $groupColumn['name'] === $field_name) {
                    if (empty($groupColumn['type'])) {
                        if (!$report) {
                            $report = new Report($reportDef);
                        }
                        if (!empty($report->full_bean_list[$table_key])) {
                            $bean = $report->full_bean_list[$table_key];
                            $fieldDef = $bean->getFieldDefinition($field_name);
                            if (!empty($fieldDef['type'])) {
                                $groupColumn['type'] = $fieldDef['type'];
                            }
                        }
                    }
                    return $groupColumn;
                }
            }
        }
        return false;
    }

    /**
     * Adds group filters to report def
     * @param Array $reportDef
     * @param Array $groupFilters
     * @throws SugarApiExceptionInvalidParameter
     * @return Array
     */
    protected function addGroupFilters($reportDef, $groupFilters)
    {
        if (!is_array($groupFilters)) {
            throw new SugarApiExceptionInvalidParameter('Invalid group filters: ' . $groupFilters);
        }

        // Construct a Report module filter from group filters
        $adhocFilter = array();
        foreach ($groupFilters as $filter) {
            foreach ($filter as $field => $value) {
                if (is_string($value)) {
                    $value = array($value);
                }
                $fieldDef = $this->getGroupFilterFieldDef($reportDef, $field);
                if ($fieldDef && !empty($fieldDef['type'])) {
                    $filterRow = array(
                        'adhoc' => true,
                        'name' => $fieldDef['name'],
                        'table_key' => $fieldDef['table_key'],
                    );
                    switch ($fieldDef['type']) {
                        case 'enum':
                            $filterRow['qualifier_name'] = 'one_of';
                            $filterRow['input_name0'] = $value;
                            break;
                        case 'date':
                        case 'datetime':
                        case 'datetimecombo':
                            if (count($value) == 1) {
                                $filterRow['qualifier_name'] = 'on';
                                $filterRow['input_name0'] = reset($value);
                            } else {
                                $filterRow['qualifier_name'] = 'between_dates';
                                $filterRow['input_name0'] = $value[0];
                                $filterRow['input_name1'] = $value[1];
                            }
                            break;
                        case 'radioenum':
                        case 'id':
                            $filterRow['qualifier_name'] = 'is';
                            $filterRow['input_name0'] = reset($value);
                            break;
                        default:
                            $filterRow['qualifier_name'] = 'equals';
                            $filterRow['input_name0'] = reset($value);
                            break;
                    }
                    // special case when the input value is empty string
                    // create a filter simiar to the "Is Empty" filter
                    if (strlen(reset($value)) == 0) {
                        $filterRow['qualifier_name'] = 'empty';
                        $filterRow['input_name0'] = 'empty';
                        $filterRow['input_name1'] = 'on';
                    }
                    array_push($adhocFilter, $filterRow);
                } else {
                    throw new SugarApiExceptionInvalidParameter('Invalid group filter field: ' . $field);
                }
            }
        }

        $adhocFilter['operator'] = 'AND';

        // Make sure Filter_1 is defined
        if (empty($reportDef['filters_def']) || !isset($reportDef['filters_def']['Filter_1'])) {
            $reportDef['filters_def']['Filter_1'] = array();
        }
        $savedReportFilter = $reportDef['filters_def']['Filter_1'];

        // For the conditions [] || {"Filter_1":{"operator":"AND"}}
        if (empty($savedReportFilter) ||
            (sizeof($savedReportFilter) == 1 && isset($savedReportFilter['operator']))
        ) {
            // Just set Filter_1 to adhocFilter
            $newFilter = $adhocFilter;
        } else {
            // Concatenate existing and adhocFilter
            $newFilter = array();
            array_push($newFilter, $savedReportFilter);
            array_push($newFilter, $adhocFilter);
            $newFilter['operator'] = 'AND';
        }

        $reportDef['filters_def']['Filter_1'] = $newFilter;
        return $reportDef;
    }

    /**
     * Returns report def with new filters
     * @param $api ServiceBase The API class of the request, used in cases where the API changes how the fields are pulled from the args array.
     * @param $args array The arguments array passed in from the API containing the module and the record
     * @throws SugarApiExceptionNotFound
     * @throws SugarApiExceptionInvalidParameter
     * @throws SugarApiException
     * @return array
     */
    protected function getReportDef($api, $args)
    {
        $savedReport = $this->getReportRecord($api, $args);
        $reportDef = json_decode($savedReport->content, true);

        if (isset($args['use_saved_filters'])) {
            if ($args['use_saved_filters'] === 'true') {
                $reportCache = new ReportCache();
                if ($reportCache->retrieve($savedReport->id)) {
                    $reportDef['filters_def'] = $reportCache->contents_array['filters_def'];
                }
            }
        }

        if (isset($args['group_filters'])) {
            $reportDef = $this->addGroupFilters($reportDef, $args['group_filters']);
        }

        return $reportDef;
    }

    /**
     * Gets offset and limit for pagination.
     * @param $api ServiceBase The API class of the request, used in cases where the API changes how the fields are pulled from the args array.
     * @param $args array The arguments array passed in from the API containing the module and the record
     * @return array
     */
    protected function getPagination($api, $args)
    {
        $offset = 0;
        $limit = -1;
        if (isset($args['offset'])) {
            $offset = (int) $args['offset'];
        }
        if ($offset < 0) {
            $offset = 0;
        }
        if (isset($args['max_num']) && $args['max_num'] !== '') {
            $limit = (int) $args['max_num'];
        }
        $limit = $this->checkMaxListLimit($limit);
        return array(
            $offset,
            $limit,
        );
    }

    /**
     * Returns the records associated with a saved report
     * @param $api ServiceBase The API class of the request, used in cases where the API changes how the fields are pulled from the args array.
     * @param $args array The arguments array passed in from the API containing the module and the record
     * @throws SugarApiExceptionNotFound
     * @throws SugarApiExceptionInvalidParameter
     * @throws SugarApiException
     * @return array records
     */
    public function getReportRecords($api, $args)
    {
        $reportDef = $this->getReportDef($api, $args);
        list($offset, $limit) = $this->getPagination($api, $args);
        if ($limit > 0) {
            // check if there are more
            $limit++;
        }
        $recordIds = $this->getRecordIdsFromReport($reportDef, $offset, $limit);

        if (!empty($recordIds)) {
            $next_offset = -1;
            if (count($recordIds) == $limit) {
                array_pop($recordIds);
                $next_offset = $offset + $limit - 1;
            }
            $args['module'] = $reportDef['module'];
            $args['filter'] = array(array('id' => array('$in' => $recordIds)));
            unset($args['record']);
            $args['offset'] = 0;
            // this tells filterapi not to use default limit
            $args['max_num'] = -1;
            $filterApi = new FilterApi();
            $result = $filterApi->filterList($api, $args);
            return array(
                'next_offset' => $next_offset,
                'records' => $result['records'],
            );
        }

        return array(
            'next_offset' => -1,
            'records' => array(),
        );
    }

    /**
     * Returns the total number of records associated with a saved report
     * @param $api ServiceBase The API class of the request, used in cases where the API changes how the fields are pulled from the args array.
     * @param $args array The arguments array passed in from the API containing the module and the records
     * @throws SugarApiExceptionNotFound
     * @throws SugarApiException
     * @return array data
     */
    public function getRecordCount($api, $args)
    {
        $reportDef = $this->getReportDef($api, $args);
        $report = new Report($reportDef);
        return array('record_count' => $report->getRecordCount());
    }

    /**
     * Retrieves a saved report and chart data, given a report ID in the args
     *
     * @param $api ServiceBase The API class of the request
     * @param $args array The arguments array passed in from the API
     * @throws SugarApiExceptionNotFound
     * @throws SugarApiException
     * @return array
     */
    public function getSavedReportChartById($api, $args)
    {
        $chartReport = $this->getReportDef($api, $args);

        $returnData = array();

        $reporter = new Report($chartReport);
        $reporter->saved_report_id = $args['record'];

        if ($reporter && !$reporter->has_summary_columns()) {
            return '';
        }

        // build report data since it isn't a SugarBean
        $reportData = array();
        $reportData['label'] = $reporter->name; // also report_def.report_name
        $reportData['id'] = $reporter->saved_report_id;
        $reportData['summary_columns'] = $reporter->report_def['summary_columns'];
        $reportData['group_defs'] = $reporter->report_def['group_defs'];
        $reportData['filters_def'] = $reporter->report_def['filters_def'];
        $reportData['base_module'] = $reporter->report_def['module'];
        $reportData['full_table_list'] = $reporter->report_def['full_table_list'];

        // add reportData to returnData
        $returnData['reportData'] = $reportData;

        $chartDisplay = new ChartDisplay();
        $chartDisplay->setReporter($reporter);

        $chart = $chartDisplay->getSugarChart();

        $json = json_decode($chart->buildJson($chart->generateXML(), true), true);

        $returnData['chartData'] = $json;

        return $returnData;
    }

    /**
     * Returns a report record
     * @param $api ServiceBase The API class of the request, used in cases where the API changes how the fields are pulled from the args array.
     * @param $args array The arguments array passed in from the API containing a record id
     * @throws SugarApiExceptionNotFound
     * @throws SugarApiException
     * @return SugarBean record
     */
    protected function getReportRecord($api, $args)
    {
        $this->requireArgs($args, array('record'));

        $savedReport = BeanFactory::getBean('Reports', $args['record']);

        if (empty($savedReport) || !$savedReport->ACLAccess('access')) {
            throw new SugarApiExceptionNotFound('Report not found: ' . $args['record']);
        }

        return $savedReport;
    }

    /**
     * Returns the record ids of a saved report
     * @param array $reportDef
     * @param integer $offset
     * @param integer $limit
     * @return array Array of record ids
     */
    protected function getRecordIdsFromReport($reportDef, $offset = 0, $limit = -1)
    {
        $report = new Report($reportDef);
        return $report->getRecordIds($offset, $limit);
    }
}
