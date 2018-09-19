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
 * <b>number(String s)</b><br>
 * Returns the numeric value of <i>s</i>.<br/>
 * ex: <i>number("1,200")</i> = 1200
 */
class ValueOfExpression extends NumericExpression
{
    /**
     * Returns the negative of the expression that it contains.
     */
    public function evaluate()
    {
        $val = $this->getParameters()->evaluate();
        if (is_string($val)) {
            $val = str_replace(",", "", $val);
            $val = empty($val) ? 0 : $val;
            if (!is_numeric($val)) {
                throw new Exception("Error: '$val' is not a number");
            }
            if (strpos($val, ".") !== false) {
                $val = $val;
            } else {
                $val = (int)$val;
            }
        } elseif ($val === null) {
            $val = 0;
        }

        if (is_numeric($val)) {
            return $val;
        } else {
            throw new Exception("Error: '$val' is not a number");
        }
    }

    /**
     * Returns the JS Equivalent of the evaluate function.
     */
    public static function getJSEvaluate()
    {
        return <<<EOQ
			var val = this.getParameters().evaluate() + "";
			val = val.replace(/,/g, "");
			var out = 0;
			if (val.indexOf(".") != -1)
				out = parseFloat(val);
			else
			    out = parseInt(val);
			if (isNaN(out))
			   throw "Error: '" + val + "' is not a number";

			return out;
EOQ;
    }

    /**
     * Returns the operation name that this Expression should be
     * called by.
     */
    public static function getOperationName()
    {
        return "number";
    }

    /**
     * Returns the exact number of parameters needed.
     */
    public static function getParamCount()
    {
        return 1;
    }

    /**
     * All parameters have to be a string.
     */
    public static function getParameterTypes()
    {
        return AbstractExpression::$GENERIC_TYPE;
    }
}
