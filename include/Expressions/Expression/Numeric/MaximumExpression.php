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
 * <b>max(Number num, ...)</b><br/>
 * Returns highest value number passed in<br>
 * ex: <i>max(-4, 2, 3)</i> = 3
 */
class MaximumExpression extends NumericExpression
{
    /**
     * Returns the largest value in a set
     */
    public function evaluate()
    {
        $params = $this->getParameters();

        $max = false;
        foreach ($this->getParameters() as $expr) {
            $val = $expr->evaluate();
            if ($max === false || $val > $max) {
                $max = $val;
            }
        }

        return $max;
    }

    /**
     * Returns the JS Equivalent of the evaluate function.
     */
    public static function getJSEvaluate()
    {
        return <<<EOQ
			var params = this.getParameters();
			var max = null;
			for (var i = 0; i < params.length; i++) {
				var val = 	params[i].evaluate();
				if(max == null || val > max)
					max = val;
			}
			return max;
EOQ;
    }

    /**
     * Returns the operation name that this Expression should be
     * called by.
     */
    public static function getOperationName()
    {
        return "max";
    }
}
