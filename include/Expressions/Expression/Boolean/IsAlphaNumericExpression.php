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
 * <b>isAlphaNumeric(String string)</b><br>
 * Returns true if "string" contains only letters and numbers.
 */
class IsAlphaNumericExpression extends BooleanExpression {
	/**
	 * Returns itself when evaluating.
	 */
	function evaluate() {
		$params = $this->getParameters()->evaluate();
		if ( preg_match('/^[a-zA-Z0-9]+$/', $params) )	return AbstractExpression::$TRUE;
		return AbstractExpression::$FALSE;
	}

	/**
	 * Returns the JS Equivalent of the evaluate function.
	 */
	static function getJSEvaluate() {
		return <<<EOQ
			var params = this.getParameters().evaluate();
			if ( /^[a-zA-Z0-9]+$/.test(params) )	return SUGAR.expressions.Expression.TRUE;
			return SUGAR.expressions.Expression.FALSE;
EOQ;
	}

	/**
	 * Any generic type will suffice.
	 */
	static function getParameterTypes() {
		return array("string");
	}

	/**
	 * Returns the maximum number of parameters needed.
	 */
	static function getParamCount() {
		return 1;
	}

	/**
	 * Returns the opreation name that this Expression should be
	 * called by.
	 */
	static function getOperationName() {
		return "isAlphaNumeric";
	}

	/**
	 * Returns the String representation of this Expression.
	 */
	function toString() {
	}
}
?>