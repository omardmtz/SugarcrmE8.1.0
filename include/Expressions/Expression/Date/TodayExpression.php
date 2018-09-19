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
 * <b>today()</b><br>
 * Returns a date object representing todays date.
 *
 */
class TodayExpression extends DateExpression
{
	/**
     * The today function is sensitive to the current users timezone since it returns a day without time.
	 */
	function evaluate() {
        $d = TimeDate::getInstance()->getNow(true);
        $d->setTime(0,0,0);

        //set isDate flag to true so text fields receive a date only, otherwise it will display date time
        $d->isDate = true;
		return $d;
	}


	/**
	 * Returns the JS Equivalent of the evaluate function.
	 */
	static function getJSEvaluate() {
		return <<<EOQ
		  var d = new Date();
		  d.setHours(0);
		  d.setMinutes(0);
		  d.setSeconds(0);

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
		return "today";
	}

	/**
	 * Returns the maximum number of parameters needed.
	 */
	static function getParamCount() {
		return 0;
	}

	/**
	 * Returns the String representation of this Expression.
	 */
	function toString() {
	}
}

?>
