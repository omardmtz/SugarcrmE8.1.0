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
 * <b>not(Boolean b)</b><br/>
 * Returns false if <i>b</i> is true, and true if <i>b</i> is false.<br/>
 * ex: <i>not(false)</i> = true
 */
class NotExpression extends BooleanExpression {
	/**
	 * Returns itself when evaluating.
	 */
	function evaluate() {
		if ( $this->getParameters()->evaluate() === AbstractExpression::$FALSE)
			return AbstractExpression::$TRUE;
		else
			return AbstractExpression::$FALSE;
	}

	/**
	 * Returns the JS Equivalent of the evaluate function.
	 */
	static function getJSEvaluate() {
		return <<<EOQ
			if ( this.getParameters().evaluate() == SUGAR.expressions.Expression.FALSE )
				return SUGAR.expressions.Expression.TRUE;
			else
				return SUGAR.expressions.Expression.FALSE;
EOQ;
	}

	/**
	 * Returns the opreation name that this Expression should be
	 * called by.
	 */
	static function getOperationName() {
		return "not";
	}

	/**
	 * Returns the maximum number of parameters needed.
	 */
	static function getParamCount() {
		return 1;
	}

	/**
	 * Returns the String representation of this Expression.
	 */
	function toString() {
	}
}
?>