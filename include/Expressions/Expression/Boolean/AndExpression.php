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
 * <b>and(boolean1, ...)</b><br>
 * Returns true if and only if all parameters are true.<br/>
 * ex: <i>and(true, true)</i> = true, <i>and(true, false)</i> = false
 */
class AndExpression extends BooleanExpression {
	/**
	 * Returns itself when evaluating.
	 */
	function evaluate() {
		$params = $this->getParameters();
        if (!is_array($params)) $params = array($params);
		foreach ( $params as $param ) {
			if ( $param->evaluate() != AbstractExpression::$TRUE )
				return AbstractExpression::$FALSE;
		}
		return AbstractExpression::$TRUE;
	}
	
	/**
	 * Returns the JS Equivalent of the evaluate function.
	 */
	static function getJSEvaluate() {
		return <<<EOQ
			var params = this.getParameters();
            if(!(params instanceof Array)) params = [params];
			for ( var i = 0; i < params.length; i++ ) {
				if ( params[i].evaluate() != SUGAR.expressions.Expression.TRUE )
					return SUGAR.expressions.Expression.FALSE;
			}
			return SUGAR.expressions.Expression.TRUE;
EOQ;
	}
	
	/**
	 * Returns the opreation name that this Expression should be
	 * called by.
	 */
	static function getOperationName() {
		return "and";
	}
	
	/**
	 * Returns the String representation of this Expression.
	 */
	function toString() {
	}
}
?>