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
 * <b>timestamp(<datetime string>)</b><br>
 * Returns the passed in datetime string as a unix timestamp
 */
class TimestampExpression extends DateExpression
{
    /**
     * Returns the entire enumeration bare.
     */
    public function evaluate()
    {
        $date = $this->getParameters()->evaluate();
        $params = DateExpression::parse($date);
        if (!$params) {
            return false;
        }

        // if the date expression doesn't have a time on it or the def is type of 'date' then force
        // the time to be midnight on that day
        if (!DateExpression::hasTime($date) || (isset($date->def) && $date->def['type'] === 'date')) {
            $params->setTime(0, 0, 0);
        }
        return $params->getTimestamp();
    }

    /**
     * Returns the JS Equivalent of the evaluate function.
     */
    public static function getJSEvaluate()
    {
        return <<<EOQ
	    var datetime = this.getParameters().evaluate(),
            arr,
            ret = [],
            date = this.context.parseDate(datetime);

        return Math.round(+date.getTime()/1000);
EOQ;
    }

    /**
     * Returns the opreation name that this Expression should be
     * called by.
     */
    public static function getOperationName()
    {
        return "timestamp";
    }

    /**
     * Returns the maximum number of parameters needed.
     */
    public static function getParamCount()
    {
        return 1;
    }

    /**
     * All parameters have to be a string.
     */
    public static function getParameterTypes()
    {
        return AbstractExpression::$DATE_TYPE;
    }
}
