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
 * <b>time(String s)</b><br/>
 * Returns <i>s</i> as a time value.<br>
 */
class DefineTimeExpression extends TimeExpression
{
	/**
	 * Returns the entire enumeration bare.
	 */
	function evaluate() {
		$params = $this->getParameters()->evaluate();
		if(!$params) {
		    return false;
		}

        $timedate = TimeDate::getInstance();
		$time = $timedate->fromUserTime($params);

		if ( $time == false ) {
			throw new Exception("Incorrect time format");
		}

		return $time;
	}


	/**
	 * Returns the JS Equivalent of the evaluate function.
	 */
	static function getJSEvaluate() {
		return <<<EOQ
			var params = this.getParameters().evaluate();
			var time   = Date.parse(params);

			if ( isNaN(time) )	throw "Incorrect time format";

			return time;
EOQ;
	}

	/**
	 * Returns the opreation name that this Expression should be
	 * called by.
	 */
	static function getOperationName() {
		return "time";
	}

	/**
	 * All parameters have to be a string.
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
	 * Returns the String representation of this Expression.
	 */
	function toString() {
	}
}

?>