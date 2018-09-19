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
 * <b>pow(Number n, Number p)</b><br/>
 * Returns </i>n</i> to the <i>p</i> power.<br/>
 * ex: <i>pow(2, 3)</i> = 8
 */
class PowerExpression extends NumericExpression
{
    /**
     * Returns the negative of the expression that it contains.
     */
    public function evaluate()
    {
        $params = $this->getParameters();

        $base = $params[0]->evaluate();
        $power = $params[1]->evaluate();

        return SugarMath::init($base)->pow($power)->result();
    }

    /**
     * Returns the JS Equivalent of the evaluate function.
     */
    public static function getJSEvaluate()
    {
        return <<<EOQ
			var params = this.getParameters();

			var base = params[0].evaluate();
			var power = params[1].evaluate();

			return Math.pow(base, power);
EOQ;
    }

    /**
     * Returns the operation name that this Expression should be
     * called by.
     */
    public static function getOperationName()
    {
        return "pow";
    }

    /**
     * Returns the exact number of parameters needed.
     */
    public static function getParamCount()
    {
        return 2;
    }
}
