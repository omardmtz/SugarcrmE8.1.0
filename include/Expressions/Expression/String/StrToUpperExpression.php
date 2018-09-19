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
 * <b>strToUpper(String s)</b><br/> 
 * Returns <i>s</i> converted to upper case.<br/>
 * ex: <em>strToLower("Hello World")</em> = "HELLO WORLD"
 */
class StrToUpperExpression extends StringExpression {
	/**
	 * Returns itself when evaluating.
	 */
	function evaluate() {
		$param =$this->getParameters();
		if (is_array($param))
			$param = $param[0];
    $strtoupper = function_exists('mb_strtoupper') ? mb_strtoupper($param->evaluate(), 'UTF-8') : strtoupper($param->evaluate());
		return $strtoupper;
	}

	/**
	 * Returns the JS Equivalent of the evaluate function.
	 */
	static function getJSEvaluate() {
		return <<<EOQ
			var string = this.getParameters().evaluate() + "" ;
			return string.toUpperCase();
EOQ;
	}

	/**
	 * Returns the opreation name that this Expression should be
	 * called by.
	 */
	static function getOperationName() {
		return "strToUpper";
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
