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
 * <b>forecastCommitStage(Number $number)</b><br>
 * Return the correct commit_stage for the number passed in based on how the Forecast is configured
 */
class ForecastCommitStageExpression extends EnumExpression
{
    /**
     * based on the probability passed in, it will return the associated commit stage
     * from the Forecast Config params.
     *
     * @return String The value of the commit stage, If no stage is found, return an empty string
     */
    public function evaluate()
    {
        $probability = $this->getParameters()->evaluate();
        $forecast_config = Forecast::getSettings();

        // if forecast is not setup, return '';
        if ($forecast_config['is_setup'] === 0) {
            return '';
        }

        $ranges = $forecast_config[$forecast_config['forecast_ranges'] . '_ranges'];

        foreach ($ranges as $stage => $range) {
            if ($probability >= $range['min'] && $probability <= $range['max']) {
                return $stage;
            }
        }

        return '';
    }

    /**
     * This evaluation of the expression in JavaScript
     */
    public static function getJSEvaluate()
    {
        return <<<JS
			var value = this.getParameters().evaluate();

			// this doesn't support BWC modules, so it should return false if it doesn't have app.
			// we can't use underscore as it's not in BWC mode here
			if (App === undefined) {
		        return '';
			}

			var config = App.metadata.getModule('Forecasts', 'config');

            // if forecast is not set, return an empty string
			if (config.forecast_setup === 0) {
                return '';
			}

			var ranges = config[config.forecast_ranges + '_ranges'],
			    stage = '';

            _.find(ranges, function(_range, _index) {
                if (value >= _range.min && value <= _range.max) {
                    stage = _index;
                    return true;
                }
                return false;
            });

            return stage;
JS;
    }

    /**
     * Number of params that the expression expects
     *
     * @return int
     */
    public static function getParamCount()
    {
        return 1;
    }

    /**
     * The first parameter is a number
     */
    public static function getParameterTypes()
    {
        return array(
            AbstractExpression::$NUMERIC_TYPE
        );
    }

    /**
     * Returns the operation name that this Expression could be called by.
     */
    public static function getOperationName()
    {
        return array('forecastCommitStage');
    }

    /**
     * Returns the String representation of this Expression.
     */
    public function toString()
    {
    }
}
