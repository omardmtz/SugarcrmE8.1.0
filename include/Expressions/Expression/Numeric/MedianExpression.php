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
 * <b>median(Number n, ...)</b><br/>
 * Returns the median of the supplied numbers.
 * ex: <i>median(4, 5, 5, 6, 7)</i> = 5
 */
class MedianExpression extends NumericExpression
{
    /**
     * Returns itself when evaluating.
     */
    public function evaluate()
    {
        $values = array();

        foreach ($this->getParameters() as $expr) {
            $values[] = $expr->evaluate();
        }

        sort($values);
        if (sizeof($values) % 2 == 0) {
            return ($values[sizeof($values) / 2] + $values[sizeof($values) / 2 - 1]) / 2;
        }

        return $values[floor(sizeof($values) / 2)];
    }

    /**
     * Returns the JS Equivalent of the evaluate function.
     */
    public static function getJSEvaluate()
    {
        return <<<EOQ
			var params = this.getParameters();
			var values = new Array();

			for ( var i = 0; i < params.length; i++ )
				values[values.length] = parseFloat(params[i].evaluate());

			// sort numerically
			values.sort(function (a, b) {return a - b;});

			if (values.length % 2 == 0) {
				return (values[values.length/2] + values[values.length/2 - 1]) / 2;
			}

			return values[ Math.round(values.length/2) - 1 ];
EOQ;
    }

    /**
     * Returns the operation name that this Expression should be
     * called by.
     */
    public static function getOperationName()
    {
        return "median";
    }

    /**
     * Returns the String representation of this Expression.
     */
    public function toString()
    {
        //pass
    }
}
