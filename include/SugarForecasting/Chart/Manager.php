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

class SugarForecasting_Chart_Manager extends SugarForecasting_Chart_AbstractChart
{
    /**
     * Constructor
     *
     * @param array $args
     */
    public function __construct($args)
    {
        $this->isManager = true;

        if (isset($args['data_array']) && $args['data_array']) {
            $this->dataArray = $args['data_array'];
        }

        parent::__construct($args);

        if (!is_array($this->dataset)) {
            $this->dataset = array($this->dataset);
        }
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

    public function generateChartJson()
    {
        $config = $this->getForecastConfig();
        // sort the data so it's in the correct order
        usort($this->dataArray, array($this, 'sortChartColumns'));

        // loop variables
        $values = array();

        foreach ($this->dataArray as $data) {
            $value = array(
                'id' => $data['id'],
                'user_id' => $data['user_id'],
                'name' => html_entity_decode($data['name'], ENT_QUOTES),
                'likely' => SugarCurrency::convertWithRate($data['likely_case'], $data['base_rate']),
                'likely_adjusted' => SugarCurrency::convertWithRate(
                    $data['likely_case_adjusted'],
                    $data['base_rate']
                )
            );

            if ($config['show_worksheet_best']) {
                $value['best'] = SugarCurrency::convertWithRate($data['best_case'], $data['base_rate']);
                $value['best_adjusted'] = SugarCurrency::convertWithRate(
                    $data['best_case_adjusted'],
                    $data['base_rate']
                );
            }
            if ($config['show_worksheet_worst']) {
                $value['worst'] = SugarCurrency::convertWithRate($data['worst_case'], $data['base_rate']);
                $value['worst_adjusted'] = SugarCurrency::convertWithRate(
                    $data['worst_case_adjusted'],
                    $data['base_rate']
                );
            }
            $values[] = $value;
        }

        $forecast_strings = $this->getModuleLanguage('Forecasts');
        global $app_strings;

        $tp = $this->getTimeperiod();

        return array(
                'title' => string_format(
                    $forecast_strings['LBL_CHART_FORECAST_FOR'],
                    array($tp->name)
                ),
                'quota' => $this->getRollupQuota(),
                'labels' => array(
                    'dataset' => array(
                        'likely' => $app_strings['LBL_LIKELY'],
                        'best' => $app_strings['LBL_BEST'],
                        'worst' => $app_strings['LBL_WORST'],
                        'likely_adjusted' => $app_strings['LBL_LIKELY_ADJUSTED'],
                        'best_adjusted' => $app_strings['LBL_BEST_ADJUSTED'],
                        'worst_adjusted' => $app_strings['LBL_WORST_ADJUSTED']
                    )
                ),
            'data' => $values
        );
    }

    /**
     * Get the quota from the sum of all the rows in the dataset
     *
     * @return float
     */
    protected function getQuotaTotalFromData()
    {
        $quota = 0;

        foreach ($this->dataArray as $data) {
            $quota += SugarCurrency::convertAmountToBase($data['quota'], $data['currency_id']);
        }

        return $quota;
    }

    /**
     * Get the roll up quota for a manager from the quota table.  If one doesn't exist it
     * will call @see getQuotaTotalFromData to return the quota total from the worksheet dataset
     *
     * @return float
     */
    protected function getRollupQuota()
    {
        //grab user that is the target of this call to check if it is the top level manager
        $targetedUser = BeanFactory::getBean("Users", $this->getArg('user_id'));

        if (!empty($targetedUser->reports_to_id)) {
            // pull the worksheet data since we need the draft records if they exist to show what could be in draft
            // for the user, if they are the current user.
            /* @var $mgr_worksheet ForecastManagerWorksheet */
            $mgr_worksheet = BeanFactory::newBean('ForecastManagerWorksheets');
            $totals = $mgr_worksheet->worksheetTotals($targetedUser->id, $this->getArg('timeperiod_id'));

            return $totals['quota'];
        }
        // get the quota from the loaded data for a manager that has no manager
        return $this->getQuotaTotalFromData();
    }

    /**
     * Method for sorting the dataArray before we return it so that the tallest bar is always first and the
     * lowest bar is always last.
     *
     * @param array $a          The left side of the compare
     * @param array $b          The right side of the compare
     * @return int
     */
    protected function sortChartColumns($a, $b)
    {
        $sumA = 0;
        $sumB = 0;

        foreach ($this->dataset as $dataset) {
            $sumA += SugarCurrency::convertAmountToBase($a[$dataset . '_case_adjusted'], $a['currency_id']);
            $sumB += SugarCurrency::convertAmountToBase($b[$dataset . '_case_adjusted'], $b['currency_id']);
        }

        if (intval($sumA) > intval($sumB)) {
            return -1;
        } else {
            if (intval($sumA) < intval($sumB)) {
                return 1;
            } else {
                return 0;
            }
        }
    }
}
