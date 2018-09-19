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
 * <b>subtract(Number a, Number b)</b><br>
 * Returns <i>a</i> minus <i>b</i>.<br/>
 * ex: <i>subtract(9, 2, 3)</i> = 4
 */
class SubtractExpression extends NumericExpression
{
    /**
     * The Logic for running in PHP, this uses SugarMath as to avoid potential floating-point errors
     *
     * @returns string
     */
    public function evaluate()
    {
        $params = $this->getParameters();
        $diff = $params[0]->evaluate();
        for ($i = 1; $i < sizeof($params); $i++) {
            $diff = SugarMath::init($diff, 6)->sub($params[$i]->evaluate())->result();
        }

        return (string)$diff;
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
			diff   = params[0].evaluate();
			for (var i = 1; i < params.length; i++) {
                diff = this.context.subtract(diff, params[i].evaluate());
            }
			return diff;
JS;
    }

    /**
     * Returns the operation name that this Expression should be
     * called by.
     */
    public static function getOperationName()
    {
        return array('subtract', 'currencySubtract', 'sub');
    }

    /**
     * Returns the String representation of this Expression.
     */
    public function toString()
    {
        $str = "";

        foreach ($this->getParameters() as $expr) {
            if (!$expr instanceof ConstantExpression) {
                $str .= "(";
            }
            $str .= $expr->toString() . " - ";
            if (!$expr instanceof ConstantExpression) {
                $str .= ")";
            }
        }
    }
}
