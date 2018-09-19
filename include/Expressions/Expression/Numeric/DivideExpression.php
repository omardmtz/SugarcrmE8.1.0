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
 * <b>divide(Number numerator, Number denominator)</b><br>
 * Returns the <i>numerator</i> divided by the <i>denominator</i>.<br/>
 * ex: <i>divide(8, 2)</i> = 4
 */
class DivideExpression extends NumericExpression
{
    /**
     * The Logic for running in PHP, this uses SugarMath as to avoid potential floating-point errors
     *
     * @throws Exception
     * @return String
     */
    public function evaluate()
    {
        $params = $this->getParameters();
        $numerator = $params[0]->evaluate();
        $denominator = $params[1]->evaluate();
        if ($denominator == 0) {
            throw new Exception("Division by zero");
        }

        return (string)SugarMath::init($numerator, 6)->div($denominator)->result();
    }

    /**
     * Returns the JS Equivalent of the evaluate function, When in sidecar it uses SugarMath, but when outside of
     * sidecar it uses a custom method to convert the values to a float and then back into a fixed `string` with a
     * precision of 6
     */
    public static function getJSEvaluate()
    {
        return <<<JS
			var params = this.getParameters(),
			    numerator   = params[0].evaluate();
			    denominator = params[1].evaluate();
            if (denominator == 0) {
			    throw "Division by 0 error";
            }
			return this.context.divide(numerator, denominator);
JS;
    }

    /**
     * Returns the operation name that this Expression should be
     * called by.
     */
    public static function getOperationName()
    {
        return array('divide', 'currencyDivide', 'div');
    }

    /**
     * Returns the exact number of parameters needed.
     */
    public static function getParamCount()
    {
        return 2;
    }
}
