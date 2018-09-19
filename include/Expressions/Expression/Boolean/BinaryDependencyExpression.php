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
 * <b>doBothExist(String s1, String s2)</b><br>
 * Returns true if both <i>s1</i> and <i>s2</i> are not empty.<br/>
 * ex: <i>doBothExist("not", "empty")</i> = true,<br/>
 * <i>doBothExist("empty", "")</i> = false
 */
class BinaryDependencyExpression extends BooleanExpression {
	/**
	 * Returns itself when evaluating.
	 */
	function evaluate() {
		$params = $this->getParameters();
		$a = $params[0]->evaluate();
		$b = $params[1]->evaluate();

		if ( strlen($a) != 0 && strlen($b) != 0 )
			return AbstractExpression::$TRUE;

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
			if ( a != null && b != null && a != '' && b != '' )
				return SUGAR.expressions.Expression.TRUE;
			return SUGAR.expressions.Expression.FALSE;
EOQ;
	}

	/**
	 * Any generic type will suffice.
	 */
	static function getParameterTypes() {
		return array("string", "string");
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
		return "doBothExist";
	}

	/**
	 * Returns the String representation of this Expression.
	 */
	function toString() {
	}
}
?>