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

abstract class NumericExpression extends AbstractExpression
{
	/**
	 * Validates the parameters and throws an Exception if invalid.
	 *
	function validateParameters() {
		$params = $this->getParameters();
		$count  = $this->getParamCount();

		// make sure count is a number
		if ( !is_numeric($count) )	throw new Exception($this->getOperationName() . ": Number of paramters required must be a number.");

		// if we require 0 parameters but we still have parameters
		if ( $count == 0 && isset($params) )	throw new Exception($this->getOperationName() . ": Does not require any parameters.");

		// we require multiple but params only has 1
		if ( $count > 1 && !is_array($params) )	throw new Exception($this->getOperationName() . ": Requires exactly $count parameter(s).");

		// if params is just a number, and we require a single param
		if ( $count == 1 && ( is_numeric($params) || $params instanceof NumericExpression ) )	return;

		// we require only 1 and params has multiple
		if ( $count == 1 && is_array($params) )	throw new Exception($this->getOperationName() . ": Requires exactly $count parameter(s).");

		// check parameter range
		if ( $count != AbstractExpression::$INFINITY && sizeof($params) != $count )
			throw new Exception($this->getOperationName() . ": Requires exactly $count parameter(s).");

		// make sure all parameters are typeof NumericExpression or a numeric constant
		foreach ( $params as $param ) {
			if ( ! $param instanceof NumericExpression )
				throw new Exception($this->getOperationName() . ": All parameters must be of type number.");
		}
	}*/

    /**
     * All parameters have to be a number.
     */
    public static function getParameterTypes()
    {
        return AbstractExpression::$NUMERIC_TYPE;
    }

    /**
     * Utility method to check if we have a Currency Field or not.
     *
     * @param SugarBean $bean
     * @param string $field
     * @return bool
     */
    protected function isCurrencyField(SugarBean $bean, $field)
    {
        $is_currency = false;
        $def = $bean->getFieldDefinition($field);
        if (is_array($def)) {
            // start by just using the type in the def
            $type = $def['type'];
            // but if custom_type is set, use it, when it's not set and dbType is, use dbType
            if (isset($def['custom_type']) && !empty($def['custom_type'])) {
                $type = $def['custom_type'];
            } elseif (isset($def['dbType']) && !empty($def['dbType'])) {
                $type = $def['dbType'];
            }
            // always lower case the type just to make sure.
            $is_currency = (strtolower($type) === 'currency');
        }

        return $is_currency;
    }

    protected function getFieldPrecision(SugarBean $bean, $field)
    {
        $precision = '0';
        $def = $bean->getFieldDefinition($field);
        if (is_array($def)) {
            if (isset($def['len']) && strpos($def['len'], ",") !== false) {
                list($len, $precision) = explode(",", $def['len']);
            }
            if (isset($def['precision'])) {
                $precision = $def['precision'];
            }
        }

        return $precision;
    }
}
