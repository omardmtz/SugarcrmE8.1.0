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
 * <b>abs(Number num)</b><br>
 * Returns the absolute value of <i>num</i>.
 * ex: <i>abs(-5)</i> = 5
 */
class AbsoluteValueExpression extends NumericExpression
{
    /**
	 * Returns the negative of the expression that it contains.
	 */
    public function evaluate()
    {
        return abs($this->getParameters()->evaluate());
    }

    /**
	 * Returns the JS Equivalent of the evaluate function.
	 */
    public static function getJSEvaluate()
    {
        return <<<EOQ
			return Math.abs(this.getParameters().evaluate());
EOQ;
    }

    /**
	 * Returns the operation name that this Expression should be
	 * called by.
	 */
    public static function getOperationName()
    {
        return "abs";
    }

    /**
	 * Returns the exact number of parameters needed.
	 */
    public static function getParamCount()
    {
        return 1;
    }
}
