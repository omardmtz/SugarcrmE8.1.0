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


class ReportsDashletsApi extends SugarApi
{

    public function registerApiRest()
    {
        return array(
            'getSavedReports' => array(
                'reqType' => 'GET',
                'path' => array('Reports', 'saved_reports'),
                'pathVars' => array('', ''),
                'method' => 'getSavedReports',
                'shortHelp' => 'Returns items from the saved_reports table based on a few criteria',
                'longHelp' => 'modules/Reports/clients/base/api/help/ReportsDashletApiGetSavedReports.html',
            ),
            'getSavedReportChartById' => array(
                'reqType' => 'POST',
                'path' => array('Reports', 'chart', '?'),
                'pathVars' => array('', '', 'reportId'),
                'method' => 'getSavedReportChartById',
                'shortHelp' => 'Updates a ForecastWorksheet model',
                'longHelp' => 'modules/Reports/clients/base/api/help/ReportsDashletApiGetSavedReportById.html',
            )
        );
    }

    /**
     * Retrieves all saved reports that meet args-driven criteria
     *
     * @param ServiceBase $api The API class of the request
     * @param array $args The arguments array passed in from the API
     * @return array
     */
    public function getSavedReports(ServiceBase $api, array $args)
    {
        // Make sure the user isn't seeing reports they don't have access to
        require_once('modules/Reports/SavedReport.php');
        $modules = array_keys(getACLDisAllowedModules());
        $fieldList = array('id', 'name', 'module', 'report_type', 'content', 'chart_type', 'assigned_user_id');

        $sq = new SugarQuery();
        $sq->from(BeanFactory::newBean('Reports'));
        $sq->select($fieldList);
        $sq->orderBy('name', 'asc');

        // if there were restricted modules, add those to the query
        if(count($modules)) {
            $sq->where()->notIn('module', $modules);
        }

        if(isset($args['has_charts']) && $args['has_charts'] == 'true') {
            $sq->where()->notEquals('chart_type', 'none');
        }

        if(isset($args['module']) && $args['module'] !== '') {
            $sq->where()->in('module', array($args['module']));
        }

        $result = $sq->execute();
        // check acls
        foreach ($result as $key => &$row) {
            $savedReport = $this->getSavedReportFromData($row);

            if ($savedReport->ACLAccess('list')) {
                // for front-end to check acls
                $row['_acl'] = ApiHelper::getHelper($api,$savedReport)->getBeanAcl($savedReport, $fieldList);
            }
            else {
                unset($result[$key]);
            }
        }
        return $result;
    }


    /**
     * Retrieves a saved report and chart data, given a report ID in the args
     *
     * @param ServiceBase $api The API class of the request
     * @param array $args The arguments array passed in from the API
     * @return array
     */
    public function getSavedReportChartById(ServiceBase $api, array $args)
    {

        $chartReport = $this->getSavedReportById($args['reportId']);

        if (isset($args['filter_id']) && $args['filter_id'] !== 'all_records') {
            $chartReport->content = $this->updateFilterDef($chartReport->content, $args['filter_id']);
        }

        if (!empty($chartReport)) {
            if (!$chartReport->ACLAccess('view')) {
                throw new SugarApiExceptionNotAuthorized('No access to view this report');
            }

            $returnData = array();

            $this->title = $chartReport->name;


            $reporter = new Report($chartReport->content);
            $reporter->saved_report_id = $chartReport->id;

            if ($reporter && !$reporter->has_summary_columns()) {
                return '';
            }

            // build report data since it isn't a SugarBean
            $reportData = array();
            $reportData['name'] = $reporter->name;
            $reportData['id'] = $reporter->saved_report_id;
            $reportData['summary_columns'] = $reporter->report_def['summary_columns'];
            $reportData['group_defs'] = $reporter->report_def['group_defs'];

            // add reportData to returnData
            $returnData['reportData'] = $reportData;

            $chartDisplay = new ChartDisplay();
            $chartDisplay->setReporter($reporter);

            $chart = $chartDisplay->getSugarChart();

            if (!isset($args['ignore_datacheck'])) {
                $args['ignore_datacheck'] = false;
            }

            $json = json_decode($chart->buildJson($chart->generateXML(), $args['ignore_datacheck']));

            $returnData['chartData'] = $json;

            return $returnData;
        }
    }

    /**
     * Retrieves a saved Report by Report Id
     * @param $reportId
     *
     * @return SugarBean
     */
    protected function getSavedReportById($reportId)
    {
        return BeanFactory::getBean("Reports", $reportId, array("encode" => false));
    }

    /**
     * Creates a SavedReport bean from query result
     * @param $row
     *
     * @return SugarBean
     */
    protected function getSavedReportFromData($row)
    {
        $savedReport = BeanFactory::newBean('Reports');
        $savedReport->populateFromRow($row);
        return $savedReport;
    }

    /**
     * Retrieves a saved report and chart data, given a report ID in the args
     *
     * @param $contentDef string Json encoded report content definition
     * @param $filterId string The id of the requested filter
     * @return string
     */
    private function updateFilterDef($contentDef, $filterId)
    {
        $reportDef = json_decode($contentDef, true);

        switch($filterId) {
            case 'favorites':
                $reportDef['full_table_list']['self']['dependents'] = array();

                $reportDef['full_table_list']['Accounts:favorite_link'] = array(
                        "name" => "Accounts  \\u003E  Favorite",
                        "parent" => "self",
                        "link_def" => array(
                            "name" => "favorite_link",
                            "relationship_name" => "accounts_favorite",
                            "bean_is_lhs" => true,
                            "link_type" => "many",
                            "label" => "Favorite",
                            "module" => "Users",
                            "table_key" => "Accounts:favorite_link",
                        ),
                        "dependents" => array("Filter.1_table_filter_row_1"),
                        "module" => "Users",
                        "label" => "Favorite",
                    );

                $reportDef['filters_def']['Filter_1'] = array(
                        "operator" => "AND",
                        "0" => array(
                            "name" => "id",
                            "table_key" => "Accounts:favorite_link",
                            "qualifier_name" => "is",
                            "input_name0" => "seed_jim_id",
                            "input_name1" => "Jim Brennan",
                        ),
                    );
                return json_encode($reportDef);

                break;

            case 'assigned_to_me':
                $reportDef['full_table_list']['self']['dependents'] = array();

                $reportDef['full_table_list']['Accounts:assigned_user_link'] = array(
                        "name" => "Accounts  \\u003E  Assigned to User",
                        "parent" => "self",
                        "link_def" => array(
                            "name" => "assigned_user_link",
                            "relationship_name" => "accounts_assigned_user",
                            "bean_is_lhs" => false,
                            "link_type" => "one",
                            "label" => "Assigned to User",
                            "module" => "Users",
                            "table_key" => "Accounts:assigned_user_link",
                        ),
                        "dependents" => array("Filter.1_table_filter_row_1"),
                        "module" => "Users",
                        "label" => "Favorite",
                    );

                $reportDef['filters_def']['Filter_1'] = array(
                        "operator" => "AND",
                        "0" => array(
                            "name" => "id",
                            "table_key" => "Accounts:assigned_user_link",
                            "qualifier_name" => "is",
                            "input_name0" => "seed_jim_id",
                            "input_name1" => "Jim Brennan",
                        ),
                    );
                return json_encode($reportDef);

                break;

            default: /* we assume if we don't know the type, it's raw */
                $filter = BeanFactory::getBean('Filters', $filterId);

                $reportDef['filters_def']['Filter_1'] = array(
                        "operator" => "AND",
                );

                $filter_definition = json_decode($filter->filter_definition);

                foreach ($filter_definition as $filter_attr)
                {
                    $filter_field = key($filter_attr);
                    $filter_props = current($filter_attr);

                    if (is_string($filter_props)) {
                        $filter_opp = 'equals';
                        $filter_val = $filter_props;
                    } else {
                        $filter_opp = $this->translateFilterOperator(key($filter_props));
                        $filter_val = current($filter_props);
                    }

                    array_push($reportDef['filters_def']['Filter_1'],
                        array(
                            "name" => $filter_field,
                            "table_key" => "self",
                            "qualifier_name" => $filter_opp,
                            "runtime" => 1,
                            "input_name0" => $filter_val,
                        )
                    );
                }

                return json_encode($reportDef);

                break;
        }
    }

    private function translateFilterOperator($opp) {
        switch($opp) {
            case '$in':
                return 'one_of';
                break;
            case '$not_in':
                return 'not_one_of';
                break;
            case '$not_equals':
                return 'not_equals_str';
                break;
            case '$starts':
                return 'starts_with';
                break;
            case '$dateBetween':
                return 'between_dates';
                break;
            case '$gt':
                return 'after';
                break;
            case '$lt':
                return 'before';
                break;
            default:
                return 'equals';
                break;
        }
    }


}
