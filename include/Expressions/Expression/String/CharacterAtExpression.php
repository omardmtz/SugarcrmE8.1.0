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
 * <b>charAt(String s, Number index)</b><br>
 * Returns character at index <i>i</i> in <i>s</i>.<br/>
 * ex: <em>charAt("Hello", 1)</em> = "e"
 */
class CharacterAtExpression extends StringExpression {
	/**
	 * Returns itself when evaluating.
	 */
	function evaluate() {
		$params = $this->getParameters();
		$str = $params[0]->evaluate();
		$idx = $params[1]->evaluate();
		return $str{$idx};
	}

	/**
	 * Returns the JS Equivalent of the evaluate function.
	 */
	static function getJSEvaluate() {
		return <<<EOQ
			var params = this.getParameters();
			var str = params[0].evaluate() + "";
			var idx = params[1].evaluate();
			return str.charAt(idx);
EOQ;
	}

	/**
	 * Returns the opreation name that this Expression should be
	 * called by.
	 */
	static function getOperationName() {
		return "charAt";
	}

	/**
	 * Any generic type will suffice.
	 */
    static function getParameterTypes() {
		return array("string", "number");
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
}
?>