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
 * <b>forecastSalesStages(Boolean $includeWon, Boolean $includeLost)</b><br/>
 * Returns all the valid sales stages for the Forecast module from the sales_stage_dom, If you pass in `false` for
 * the $includeWon or $includeFalse, those values from the config will not be included in the list<br/>
 * ex: <i>forecastSalesStages(true, false)</i>
 */
class ForecastSalesStageExpression extends EnumExpression
{
    /**
     * Returns the entire enumeration bare.
     */
    public function evaluate()
    {
        $params = $this->getParameters();
        $includeWon = $params[0]->evaluate();
        $includeLost = $params[1]->evaluate();

        $array = array_keys($GLOBALS['app_list_strings']['sales_stage_dom']);

        // get the statuses
        $settings = Forecast::getSettings();

        $keysToRemove = array();
        if ($includeWon == AbstractExpression::$FALSE) {
            $keysToRemove = array_merge($keysToRemove, $settings['sales_stage_won']);
        }

        if ($includeLost == AbstractExpression::$FALSE) {
            $keysToRemove = array_merge($keysToRemove, $settings['sales_stage_lost']);
        }

        return array_diff($array, $keysToRemove);
    }


    /**
     * Returns the JS Equivalent of the evaluate function.
     */
    public static function getJSEvaluate()
    {
        return <<<JS

            // this doesn't support BWC modules, so it should return the full list of dom elememnts
            if (App === undefined) {
		        return SUGAR.language.get('app_list_strings', 'sales_stage_dom');
			}

			var SEE = SUGAR.expressions.Expression,
			    config = App.metadata.getModule('Forecasts', 'config'),
			    params = this.getParameters(),
			    includeWon = params[0].evaluate(),
			    includeClosed = params[1].evaluate(),
			    array = _.keys(App.lang.getAppListStrings('sales_stage_dom')),
			    keysToRemove = [];

            if (!SEE.isTruthy(includeWon)) {
                keysToRemove = _.union(keysToRemove, config.sales_stage_won);
            }

            if (!SEE.isTruthy(includeClosed)) {
                keysToRemove = _.union(keysToRemove, config.sales_stage_lost);
            }

			return _.difference(array, keysToRemove);
JS;
    }

    public static function getParamCount()
    {
        return 2;
    }


    /**
     * The first parameter is a number and the second is the list.
     */
    public static function getParameterTypes()
    {
        return array(AbstractExpression::$BOOLEAN_TYPE, AbstractExpression::$BOOLEAN_TYPE);
    }

    /**
     * Returns the operation name that this Expression could be called by.
     */
    public static function getOperationName()
    {
        return array("forecastSalesStages");
    }

    /**
     * Returns the String representation of this Expression.
     */
    public function toString()
    {
    }
}
