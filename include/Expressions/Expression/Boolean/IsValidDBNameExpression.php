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
 * <b>isValidDBName(String name)</b><br/>
 * Returns true if <i>name</i> is legal as a column name in the database.
 */
class IsValidDBNameExpression extends BooleanExpression {
	/**
	 * Returns itself when evaluating.
	 */
	function evaluate() {
		$nameStr = $this->getParameters()->evaluate();

		if( strlen($nameStr) == 0) return AbstractExpression::$TRUE;
		if(! preg_match('/^[a-zA-Z][a-zA-Z\_0-9]+$/', $nameStr) )
			return AbstractExpression::$FALSE;
		return AbstractExpression::$TRUE;
	}

	/**
	 * Returns the JS Equivalent of the evaluate function.
	 */
	static function getJSEvaluate() {
		return <<<EOQ
		var str = this.getParameters().evaluate();
		if(str.length== 0) {
			return true;
		}
		// must start with a letter
		if(!/^[a-zA-Z][a-zA-Z\_0-9]+$/.test(str))
			return SUGAR.expressions.Expression.FALSE;
		return SUGAR.expressions.Expression.TRUE;
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
		return "isValidDBName";
	}

	/**
	 * Returns the String representation of this Expression.
	 */
	function toString() {
	}
}
?>
