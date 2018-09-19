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
 * <b>addDays($date, $days)</b><br>
 * Returns a date object moved forward or backwards by <i>$days</i> days.<br/>
 * ex: <i>addDays(date("1/1/2010"), 5)</i> = "1/6/2010"
 **/
class AddDaysExpression extends DateExpression
{
	/**
	 * Returns the entire enumeration bare.
	 */
	function evaluate() {
        $params = $this->getParameters();

        $date = DateExpression::parse($params[0]->evaluate());
        if(!$date) {
            return false;
        }
        $days = (int) $params[1]->evaluate();
        
        if ($days < 0)
           return $date->modify("$days day");

        return $date->modify("+$days day");
	}


	/**
	 * Returns the JS Equivalent of the evaluate function.
	 */
	static function getJSEvaluate() {
		return <<<EOQ
		    var params = this.getParameters();
            var fromDate = params[0].evaluate();
            if (!fromDate) {
                return '';
            }
			var days = parseInt(params[1].evaluate(), 10);
			if (_.isNaN(days)) {
				return '';
			}
			var date = SUGAR.util.DateUtils.parse(fromDate, 'user');

            //Clone the object to prevent possible issues with other operations on this variable.
            var d = new Date(date);
            d.setDate(d.getDate() + days);

            // if we're calling this from Sidecar, we need to pass back the date
            // as a string, not a Date object otherwise it won't validate properly
            if (this.context.view) {
                d = App.date.format(d, 'Y-m-d');
            }

            return d;
EOQ;
	}

	/**
	 * Returns the opreation name that this Expression should be
	 * called by.
	 */
	static function getOperationName() {
		return "addDays";
	}
    static function getParameterTypes() {
		return array("date", "number");
	}

	/**
	 * Returns the maximum number of parameters needed.
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
