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
 * <b>isValidDate(String date)</b><br/>
 * Returns true if <i>date</i> is a valid date string.
 *
 */
class IsValidDateExpression extends BooleanExpression {
	/**
	 * Returns true if a passed in date string (in User format) is valid
	 */
	function evaluate() {
        global $current_user;
        $dtStr = $this->getParameters()->evaluate();

        if(empty($dtStr)) {
            return AbstractExpression::$FALSE;
        }
        try {
            $td = TimeDate::getInstance();
            $date = $td->fromUser($dtStr, $current_user);
            if(!empty($date) && $td->asUser($date) == $dtStr) {
                return AbstractExpression::$TRUE;
            }
            //Next try without time
            $date = $td->fromUserDate($dtStr, $current_user);
            if(!empty($date) && $td->asUserDate($date) == $dtStr)  {
                return AbstractExpression::$TRUE;
            }
            return AbstractExpression::$FALSE;
        } catch(Exception $e) {
            return AbstractExpression::$FALSE;
        }
	}

	/**
	 * Returns true is a passed in date string (in user format) is valid.
	 */
	static function getJSEvaluate() {
		return <<<EOQ
		var dtStr = this.getParameters().evaluate();
        if (typeof dtStr != "string" || dtStr == "") return SUGAR.expressions.Expression.FALSE;
        var format = "Y-m-d";
        if (SUGAR.expressions.userPrefs)
            format = SUGAR.expressions.userPrefs.datef;
        var date = SUGAR.util.DateUtils.parse(dtStr, format);
        if(date != false && date != "Invalid Date")
		    return SUGAR.expressions.Expression.TRUE;
		return SUGAR.expressions.Expression.FALSE;
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
		return "isValidDate";
	}

	/**
	 * Returns the String representation of this Expression.
	 */
	function toString() {
	}
}
?>
