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
 * <b>toString(val)</b><br/>
 * Converts the given value to a string.<br>
 * ex: <i>toString(5.5)</i> = "5.5"
 */
class DefineStringExpression extends StringExpression {
	/**
	 * Returns itself when evaluating.
	 */
	function evaluate() {
        try {
            $val = $this->getParameters()->evaluate();
            //Dates should be formated before being cast to a string
            if(($val instanceof SugarDateTime) && !empty($val->def))
            {
                global $timedate;
                $date = DateExpression::roundTime($val);
                if ($val->def['type'] == "date")
                {
                    //Date
                    $date->setTimezone(new DateTimeZone("UTC"));
                    $retstr = $timedate->asUserDate($date);
                } else
                {
                    //Datetime
                    $retstr = $timedate->asUser($date);
                }
            }
            else {
                $retstr = $val . "";
            }
        } catch (Exception $e) {
            $GLOBALS['log']->warn('DefineStringExpression::evaluate() returned empty string due to received exception: '.$e->getMessage());
            $retstr = "";
        }
		return $retstr;
	}

	/**
	 * Returns the JS Equivalent of the evaluate function.
	 */
	static function getJSEvaluate() {
		return <<<EOQ
        var value = this.getParameters().evaluate(),
            string;
        if (value instanceof Date) {
            var dateOnly = value.type == "date";
            string = App.date(value).formatUser(dateOnly);
        } else {
            string = value + "";
        }
        return string;
EOQ;
	}

	/**
	 * Returns the opreation name that this Expression should be
	 * called by.
	 */
	static function getOperationName() {
		return array("toString", "string");
	}

	/**
	 * Returns the exact number of parameters needed.
	 */
	static function getParamCount() {
		return 1;
	}

	/**
	 * Any generic type will suffice.
	 */
    static function getParameterTypes() {
		return array("generic");
	}

	/**
	 * Returns the String representation of this Expression.
	 */
	function toString() {
	}
}
