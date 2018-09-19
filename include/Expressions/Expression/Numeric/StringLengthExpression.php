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
 * <b>strlen(String s)</b><br>
 * Returns the number of characters in the String <i>s</i>.<br/>
 * ex: <i>strlen("Hello")</i> = 5
 */
class StringLengthExpression extends NumericExpression
{
    /**
     * Returns the negative of the expression that it contains.
     */
    public function evaluate()
    {
        return strlen($this->getParameters()->evaluate());
    }

    /**
     * Returns the JS Equivalent of the evaluate function.
     */
    public static function getJSEvaluate()
    {
        return <<<EOQ
			var p = this.getParameters().evaluate() + "";
			return p.length;
EOQ;
    }

    /**
     * Returns the operation name that this Expression should be
     * called by.
     */
    public static function getOperationName()
    {
        return "strlen";
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
        return AbstractExpression::$STRING_TYPE;
    }
}
