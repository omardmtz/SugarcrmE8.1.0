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
 * <b>contains(String haystack, String needle)</b><br/>
 * Returns true if needle is within haystack.<br/>
 * ex: <i>contains("Hello World", "llo")</i> = true
 */
class ContainsExpression extends BooleanExpression {
	/**
	 * Returns itself when evaluating.
	 */
	function evaluate() {
		// TODO: add caching of return values
		$params	  = $this->getParameters();
		$haystack = $params[0]->evaluate();
		$needle	  = $params[1]->evaluate();

		if ( strpos($haystack, $needle) === false )
			return AbstractExpression::$FALSE;
		else
			return AbstractExpression::$TRUE;
	}

	/**
	 * Returns the JS Equivalent of the evaluate function.
	 */
	static function getJSEvaluate() {
		return <<<EOQ
			var params	  = this.getParameters();
			var haystack  = params[0].evaluate() + "";
			var needle	  = params[1].evaluate();

			return ( haystack.indexOf(needle) > -1 ? SUGAR.expressions.Expression.TRUE : SUGAR.expressions.Expression.FALSE );
EOQ;
	}

	/**
	 * Returns the opreation name that this Expression should be
	 * called by.
	 */
	static function getOperationName() {
		return "contains";
	}

	/**
	 * Returns the exact number of parameters needed.
	 */
	static function getParamCount() {
		return 2;
	}

	/**
	 * Returns the String representation of this Expression.
	 */
	function toString() {
	}
    static function getParameterTypes() {
		return AbstractExpression::$STRING_TYPE;
	}
}
?>