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
 * <b>equal(Generic item1, Generic item2)</b><br>
 * Returns true if "item1" is equal to "item2".<br/>
 * ex: <i>equal("one", "one")</i> = true, <i>equal(1, "one")</i> = false<br/>
 * Formula for checkbox fields: equal($checkbox_field_name, true)
 */
class EqualExpression extends BooleanExpression
{
    /**
     * Returns itself when evaluating.
     */
    function evaluate()
    {
        $params = $this->getParameters();

        $a = $params[0]->evaluate();
        $b = $params[1]->evaluate();
        $hasBool = $a instanceof BooleanConstantExpression || $b instanceof BooleanConstantExpression;

        if ($hasBool) {
            if (($this->isTruthy($a) && $this->isTruthy($b)) || (!$this->isTruthy($a) && !$this->isTruthy($b))) {
                return AbstractExpression::$TRUE;
            } else {
                return AbstractExpression::$FALSE;
            }
        }
        if ($a == $b) {
            return AbstractExpression::$TRUE;
        }
        return AbstractExpression::$FALSE;
    }

    /**
     * Returns the JS Equivalent of the evaluate function.
     */
    static function getJSEvaluate()
    {
        return <<<EOQ
            var SEE = SUGAR.expressions.Expression,
                params = this.getParameters(),
                a = params[0].evaluate(),
                b = params[1].evaluate(),
                hasBool = params[0] instanceof SUGAR.expressions.BooleanExpression ||
                    params[1] instanceof SUGAR.expressions.BooleanExpression;

            if ( a == b  || (hasBool && ((SEE.isTruthy(a) && SEE.isTruthy(b)) || (!SEE.isTruthy(a) && !SEE.isTruthy(b))))) {
               return SEE.TRUE;
            }
            return SEE.FALSE;
EOQ;
    }

    /**
     * Any generic type will suffice.
     */
    static function getParameterTypes()
    {
        return array(AbstractExpression::$GENERIC_TYPE, AbstractExpression::$GENERIC_TYPE);
    }

    /**
     * Returns the maximum number of parameters needed.
     */
    static function getParamCount()
    {
        return 2;
    }

    /**
     * Returns the opreation name that this Expression should be
     * called by.
     */
    static function getOperationName()
    {
        return 'equal';
    }

    /**
     * Returns the String representation of this Expression.
     */
    function toString()
    {
    }

    /**
     * Internal function to determine if a value is either a True boolean constant or a value that SugarLogic considers "truthy"
     * @param $val
     *
     * @return bool
     */
    protected function isTruthy($val) {
        if ($val instanceof BooleanConstantExpression) {
            return $val == AbstractExpression::$TRUE;
        }
        if (is_string($val)) {
            return strtolower($val) !== 'false' && strtolower($val) !== '';
        }
        return !empty($val);
    }
}
