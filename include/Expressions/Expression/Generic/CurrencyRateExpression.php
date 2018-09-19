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
 * <b>currencyRate(String <i>currency_id</i>)</b><br>
 * Returns the current conversion_rate from the currency metadata
 */
class CurrencyRateExpression extends GenericExpression
{
    /**
     * Returns the the conversion rate for the passed in currency
     */
    public function evaluate()
    {
        $id = $this->getParameters()->evaluate();
        $currency = SugarCurrency::getCurrencyByID($id);
        return $currency->conversion_rate;
    }

    /**
     * Returns the JS Equivalent of the evaluate function.
     */
    public static function getJSEvaluate()
    {
        return <<<JS
			// this doesn't support BWC modules, so it should return false if it doesn't have Apps.
			if (App === undefined) {
		        return SUGAR.expressions.Expression.FALSE;
			}

			var currencyId = this.getParameters().evaluate();
			return App.metadata.getCurrency(currencyId).conversion_rate;
JS;
    }

    /**
     * Returns the operation name that this Expression should be
     * called by.
     */
    public static function getOperationName()
    {
        return array("currencyRate");
    }

    /**
     * The first parameter is the current currencyId
     */
    public static function getParameterTypes()
    {
        return array(AbstractExpression::$STRING_TYPE);
    }

    /**
     * Returns the maximum number of parameters needed.
     */
    public static function getParamCount()
    {
        return 1;
    }

    /**
     * Returns the String representation of this Expression.
     */
    public function toString()
    {
    }
}
