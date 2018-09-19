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
 * <b>min(Number num, ...)</b><br/>
 * Returns lowest value number passed in<br>
 * ex: <i>min(-4, 2, 3)</i> = -4
 */
class MinimumExpression extends NumericExpression
{
    /**
     * Returns the smallest value in a set
     */
    public function evaluate()
    {
        $params = $this->getParameters();

        $min = false;
        foreach ($this->getParameters() as $expr) {
            $val = $expr->evaluate();
            if ($min === false || $val < $min) {
                $min = $val;
            }
        }

        return $min;
    }

    /**
     * Returns the JS Equivalent of the evaluate function.
     */
    public static function getJSEvaluate()
    {
        return <<<EOQ
			var params = this.getParameters();
			var min = null;
			for (var i = 0; i < params.length; i++) {
				var val = 	params[i].evaluate();
				if(min == null || val < min)
					min = val;
			}
			return min;
EOQ;
    }

    /**
     * Returns the operation name that this Expression should be
     * called by.
     */
    public static function getOperationName()
    {
        return "min";
    }
}
