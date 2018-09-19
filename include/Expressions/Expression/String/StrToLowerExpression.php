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
 * <b>strToLower(String s)</b><br/>
 * Returns <i>s</i> converted to lower case.<br/>
 * ex: <em>strToLower("Hello World")</em> = "hello world"
 */
class StrToLowerExpression extends StringExpression {
	/**
	 * Returns itself when evaluating.
	 */
	function evaluate() {
		$param =$this->getParameters();
		if (is_array($param))
			$param = $param[0];
    $strtolower = function_exists('mb_strtolower') ? mb_strtolower($param->evaluate(), 'UTF-8') : strtolower($param->evaluate());
		return $strtolower;
	}

	/**
	 * Returns the JS Equivalent of the evaluate function.
	 */
	static function getJSEvaluate() {
		return <<<EOQ
			var string = this.getParameters().evaluate() + "";
			return string.toLowerCase();
EOQ;
	}

	/**
	 * Returns the opreation name that this Expression should be
	 * called by.
	 */
	static function getOperationName() {
		return "strToLower";
	}

	/**
	 * Returns the String representation of this Expression.
	 */
	function toString() {
	}

    /**
     * Return param count to prevent errors.
     */
    public static function getParamCount()
    {
        return 1;
    }
}
?>
