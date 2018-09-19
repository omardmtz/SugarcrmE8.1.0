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
 * <b>concat(String s, ...)</b><br/>
 * Appends two or more pieces of text together.<br/>
 * ex: <i>concat("Hello", " ", "World")</i> = "Hello World"
 */
class ConcatenateExpression extends StringExpression {
	/**
	 * Returns itself when evaluating.
	 */
	function evaluate() {
		// TODO: add caching of return values
		$concat = "";
		foreach ( $this->getParameters() as $expr ) {
			$concat .= $expr->evaluate();
		}
		return $concat;
	}

	/**
	 * Returns the JS Equivalent of the evaluate function.
	 */
	static function getJSEvaluate() {
		return <<<EOQ
			var concat = "";
			var params = this.getParameters() ;
			for ( var i = 0; i < params.length; i++ ) {
				concat += params[i].evaluate();
			}
			return concat;
EOQ;
	}

	/**
	 * Returns the opreation name that this Expression should be
	 * called by.
	 */
	static function getOperationName() {
		return "concat";
	}

	/**
	 * Returns the String representation of this Expression.
	 */
	function toString() {
	}
}
?>