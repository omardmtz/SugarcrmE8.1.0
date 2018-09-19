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
 * <b>indexOf(val, List l)</b><br>
 * Returns the position of <i>val</i> in <i>l</i><br/>
 * or -1 if <i>l</i> does not contain <i>val</i>.<br/>
 * ex: <i>indexOf("a", createList("a", "b", "c"))</i> = 0,<br/>
 * <i>indexOf("foo", createList("a", "b", "c"))</i> = -1
 */
class IndexOfExpression extends NumericExpression
{
    /**
     * Returns the entire enumeration bare.
     */
    public function evaluate()
    {
        $params = $this->getParameters();
        $array = $params[1]->evaluate();
        $value = $params[0]->evaluate();

        for ($i = 0; $i < sizeOf($array); $i++) {
            if ($array[$i] == $value) {
                return $i;
            }
        }

        return -1;
    }

    /**
     * Returns the JS Equivalent of the evaluate function.
     */
    public static function getJSEvaluate()
    {
        return <<<EOQ
			var params = this.getParameters();
			var arr  = params[1].evaluate();
			var val  = params[0].evaluate();

			for (var i=0; i < arr.length; i++) {
			if (arr[i] == val) {
				return i;
			}
		}
		return -1;
EOQ;
    }

    /**
     * Returns the operation name that this Expression should be
     * called by.
     */
    public static function getOperationName()
    {
        return "indexOf";
    }

    /**
     * The first parameter is a number and the second is the list.
     */
    public static function getParameterTypes()
    {
        return array("generic", "enum");
    }

    /**
     * Returns the maximum number of parameters needed.
     */
    public static function getParamCount()
    {
        return 2;
    }

    /**
     * Returns the String representation of this Expression.
     */
    public function toString()
    {
    }
}
