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

class SugarForecasting_Chart_Individual extends SugarForecasting_Chart_AbstractChart
{
    /**
     * Constructor
     *
     * @param array $args
     */
    public function __construct($args)
    {
        if (isset($args['data_array']) && $args['data_array']) {
            $this->dataArray = $args['data_array'];
        }
        parent::__construct($args);
    }

    /**
     * Process the data into the current JIT Chart Format
     *
     * @return array
     */
    public function process()
    {
        return $this->generateChartJson();
    }

    protected function generateChartJson()
    {
        global $app_list_strings, $app_strings;

        $arrData = array();
        $arrProbabilities = array();
        $forecast_strings = $this->getModuleLanguage('Forecasts');
        $config = $this->getForecastConfig();

        $acl = new SugarACLForecastWorksheets();

        $bestAccess = $acl->checkAccess(
            'ForecastWorksheets',
            'field',
            array('field' => 'best_case', 'action' => 'view')
        );
        $worstAccess = $acl->checkAccess(
            'ForecastWorksheets',
            'field',
            array('field' => 'worst_case', 'action' => 'view')
        );

        if (!empty($this->dataArray)) {
            foreach ($this->dataArray as $data) {

                // If users have made likely/best/worst not required,
                // set the value to 0 for upcoming currency math
                if (empty($data['likely_case'])) {
                    $data['likely_case'] = 0;
                }

                $v = array(
                    'id' => $data['id'],
                    'record_id' => $data['parent_id'],
                    'forecast' => $data['commit_stage'],
                    'probability' => $data['probability'],
                    'sales_stage' => $data['sales_stage'],
                    'likely' => SugarCurrency::convertWithRate($data['likely_case'], $data['base_rate']),
                    'date_closed_timestamp' => intval($data['date_closed_timestamp'])
                );

                if ($config['show_worksheet_best'] && $bestAccess) {
                    if (empty($data['best_case'])) {
                        $data['best_case'] = 0;
                    }

                    $v['best'] = SugarCurrency::convertWithRate($data['best_case'], $data['base_rate']);
                }

                if ($config['show_worksheet_worst'] && $worstAccess) {
                    if (empty($data['worst_case'])) {
                        $data['worst_case'] = 0;
                    }

                    $v['worst'] = SugarCurrency::convertWithRate($data['worst_case'], $data['base_rate']);
                }

                $arrData[] = $v;

                $arrProbabilities[$data['probability']] = $data['probability'];
            }
            asort($arrProbabilities);
        }

        $tp = $this->getTimeperiod();

        if (!$tp->id) {
            // Forecast Time Period was out of range
            $title = '';
            $quota = null;
            $xaxis = array();
            $error = 'ERR_NO_ACTIVE_TIMEPERIOD';
        } else {
            $title = string_format(
                $forecast_strings['LBL_CHART_FORECAST_FOR'],
                array($tp->name)
            );
            $quota = $this->getUserQuota();
            $xaxis = $tp->getChartLabels(array());
            $error = false;
        }

        return array(
            'title' => $title,
            'quota' => $quota,
            'x-axis' => $xaxis,
            'labels' => array(
                'forecast' => $app_list_strings[$config['buckets_dom']],
                'sales_stage' => $app_list_strings['sales_stage_dom'],
                'probability' => $arrProbabilities,
                'dataset' => array(
                    'likely' => $app_strings['LBL_LIKELY'],
                    'best' => $app_strings['LBL_BEST'],
                    'worst' => $app_strings['LBL_WORST']
                )
            ),
            'data' => $arrData,
            'error' => $error,
        );
    }

    /**
     * Return the quota for the current user and time period
     *
     * @return mixed
     */
    protected function getUserQuota()
    {
        /* @var $quota_bean Quota */
        $quota_bean = BeanFactory::newBean('Quotas');
        $quota = $quota_bean->getRollupQuota($this->getArg('timeperiod_id'), $this->getArg('user_id'));

        return SugarCurrency::convertAmountToBase($quota['amount'], $quota['currency_id']);
    }
}
