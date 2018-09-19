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
 * <b>daysUntil(Date d)</b><br>
 * Returns number of days from now until the specified date.
 */
class DaysUntilExpression extends NumericExpression
{
	/**
	 * Returns the entire enumeration bare.
	 */
	function evaluate() {
        $params = DateExpression::parse($this->getParameters()->evaluate());
        if(!$params) {
            return false;
        }
        $now = TimeDate::getInstance()->getNow(true);
        //set the time to 0, as we are returning an integer based on the date.
        $params->setTime(0, 0, 0); // this will be the timestamp delimiter of the day.
        $tsdiff = $params->ts - $now->ts;
        $diff = (int)ceil($tsdiff/86400);
        return $diff;
	}


	/**
	 * Returns the JS Equivalent of the evaluate function.
	 */
	static function getJSEvaluate() {
		return <<<EOQ
            var then = SUGAR.util.DateUtils.parse(this.getParameters().evaluate(), 'user');
			var now = new Date();
			then.setHours(0);
			then.setMinutes(0);
			then.setSeconds(0);
			var diff = then - now;
			var days = Math.ceil(diff / 86400000);

			return days;
EOQ;
	}


	/**
	 * Returns the opreation name that this Expression should be
	 * called by.
	 */
	static function getOperationName() {
		return "daysUntil";
	}

	/**
	 * All parameters have to be a date.
	 */
    static function getParameterTypes() {
		return array(AbstractExpression::$DATE_TYPE);
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
