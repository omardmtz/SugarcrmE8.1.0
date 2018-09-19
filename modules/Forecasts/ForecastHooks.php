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

class ForecastHooks extends AbstractForecastHooks
{
    /**
     * This method, just set the date_modified to the value from the db, vs the user formatted value that sugarbean sets
     * after it has been retrieved
     *
     * @param Forecast $forecast
     * @param string $event
     * @param array $params
     */
    public static function fixDateModified(Forecast $forecast, $event, $params = array())
    {
        $forecast->date_modified = $forecast->fetched_row['date_modified'];
    }

    /**
     * If the commit_stage field is empty on a bean but the probability is not and Forecasts is setup, then try and
     * match the commit_stage to where the probability falls in the ranges defined by the forecast config.
     *
     * @param RevenueLineItem|Opportunity|SugarBean $bean
     * @param string $event
     * @param array $params
     */
    public function setCommitStageIfEmpty($bean, $event, $params = array())
    {
        // only run on before_save logic hooks
        if ($event != 'before_save') {
            return;
        }
        if (static::isForecastSetup() && empty($bean->commit_stage) && $bean->probability !== '') {
            //Retrieve Forecasts_category_ranges and json decode as an associative array
            $forecast_ranges = isset(static::$settings['forecast_ranges']) ? static::$settings['forecast_ranges'] : '';
            $category_ranges = isset(static::$settings[$forecast_ranges . '_ranges']) ?
                (array)static::$settings[$forecast_ranges . '_ranges'] : array();
            foreach ($category_ranges as $key => $entry) {
                if ($bean->probability >= $entry['min'] && $bean->probability <= $entry['max']) {
                    $bean->commit_stage = $key;
                    break;
                }
            }
        }
    }

    /**
     * @param RevenueLineItem|Opportunity|SugarBean $bean
     * @param string $event
     * @param array $params
     */
    public function setBestWorstEqualToLikelyAmount($bean, $event, $params = array())
    {
        // only run on before_save logic hooks
        if ($event != 'before_save' || empty($bean->sales_stage)) {
            return;
        }
        if (static::isForecastSetup() && in_array($bean->sales_stage, static::getForecastClosedStages())) {
            $field = ($bean->module_dir == 'Opportunities') ? 'amount' : 'likely_case';
            $bean->best_case = $bean->$field;
            $bean->worst_case = $bean->$field;
        }
    }
}
