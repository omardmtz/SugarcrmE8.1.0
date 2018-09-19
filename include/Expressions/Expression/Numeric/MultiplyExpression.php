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
 * <b>multiply(Number n, ...)</b><br>
 * Multiplies the supplied numbers and returns the result.<br/>
 * ex: <i>multiply(-4, 2, 3)</i> = -24
 */
class MultiplyExpression extends NumericExpression
{
    /**
     * The Logic for running in PHP, this uses SugarMath as to avoid potential floating-point errors
     *
     * @return String
     */
    public function evaluate()
    {
        $product = '1';
        foreach ($this->getParameters() as $expr) {
            $product = SugarMath::init($product, 6)->mul($expr->evaluate())->result();
        }

        return (string)$product;
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
			product = '1';
			for (var i = 0; i < params.length; i++) {
                product = this.context.multiply(product, params[i].evaluate());
            }
			return product;
JS;
    }

    /**
     * Returns the operation name that this Expression should be
     * called by.
     */
    public static function getOperationName()
    {
        return array('multiply', 'currencyMultiply', 'mul');
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
            $str .= $expr->toString() . " * ";
            if (!$expr instanceof ConstantExpression) {
                $str .= ")";
            }
        }
    }
}
