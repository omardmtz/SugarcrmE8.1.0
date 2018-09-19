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
 * <b>isBefore(Date day1, Date day2)</b><br>
 * Returns true day1 is before day2.<br/>
 * ex: <i>isBefore(date("1/1/2001"), date("2/2/2002"))</i> = true
 */
class isBeforeExpression extends BooleanExpression {
	/**
	 * Returns itself when evaluating.
	 */
	function evaluate() {
		$params = $this->getParameters();

		$a = DateExpression::parse($params[0]->evaluate());
		$b = DateExpression::parse($params[1]->evaluate());

		if(empty($a) || empty($b)) {
		    return false;
		}

		if ( $a < $b )	return AbstractExpression::$TRUE;
		return AbstractExpression::$FALSE;
	}

	/**
	 * Returns the JS Equivalent of the evaluate function.
	 */
	static function getJSEvaluate() {
		return <<<EOQ
			var params = this.getParameters();
			var a = params[0].evaluate();
			var b = params[1].evaluate();

            if (!a || !b || isNaN(a) || isNaN(b)) {
                return SUGAR.expressions.Expression.FALSE;
            }

			if ( a < b )	return SUGAR.expressions.Expression.TRUE;
			return SUGAR.expressions.Expression.FALSE;
EOQ;
	}

	/**
	 * Any generic type will suffice.
	 */
	static function getParameterTypes() {
		return array("date", "date");
	}

	/**
	 * Returns the maximum number of parameters needed.
	 */
	static function getParamCount() {
		return 2;
	}

	/**
	 * Returns the opreation name that this Expression should be
	 * called by.
	 */
	static function getOperationName() {
		return "isBefore";
	}

	/**
	 * Returns the String representation of this Expression.
	 */
	function toString() {
	}
}
?>
