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
abstract class StringExpression extends AbstractExpression
{
	/**
	 * Validates the parameters and throws an Exception if invalid.
	 *
	function validateParameters() {
		$params = $this->getParameters();
		$count  = $this->getParamCount();
		$types  = $this->getParameterTypes();

		// make sure count is a number
		if ( !is_numeric($count) )	throw new Exception($this->getOperationName() . ": Number of paramters required must be a number.");

		// make sure types is a array or string
		if ( ( !is_string($types) && !is_array($types) ) || ( $types != AbstractExpression::$STRING_TYPE && $count != AbstractExpression::$INFINITY && $count != sizeof($types) ) )
			throw new Exception($this->getOperationName() . ": Parameter types must be valid and match the parameter count.");

		// if we require 0 parameters but we still have parameters
		if ( $count == 0 && isset($params) )	throw new Exception($this->getOperationName() . ": Does not require any parameters.");

		// we require multiple but params only has 1
		if ( $count > 1 && !is_array($params) )	throw new Exception($this->getOperationName() . ": Requires exactly $count parameter(s).");

		// if params is just a string, and we require a single string
		if ( $count == 1 && $types == AbstractExpression::$STRING_TYPE && ( is_string($params) || $params instanceof StringExpression ) )
			return;

		// we require only 1 and params has multiple
		if ( $count == 1 && is_array($params) )	throw new Exception($this->getOperationName() . ": Requires exactly $count parameter(s).");

		// check parameter range
		if ( $count != AbstractExpression::$INFINITY && sizeof($params) != $count )
			throw new Exception($this->getOperationName() . ": Requires exactly $count parameter(s).");

		if ( $types == AbstractExpression::$STRING_TYPE ) {
			// make sure all parameters are typeof StringExpression or a string literal
			foreach ( $params as $param ) {
				if ( ! $param instanceof StringExpression )
					throw new Exception($this->getOperationName() . ": All parameters must be strings.");
			}
		} else {
			if ( !is_array($params) && ! $params instanceof AbstractExpression::$TYPE_MAP[$types[0]] ) {
				throw new Exception($this->getOperationName() . ": invalid type specified.");
			} else if ( !is_array($params) && $params instanceof AbstractExpression::$TYPE_MAP[$types[0]] ) {
				return;
			}

			for ( $i = 0 ; $i < sizeof($types) ; $i++ ) {
				$type  = $types[$i];
				$param = $params[$i];

				// invalid type
				if ( empty(AbstractExpression::$TYPE_MAP[$type]) )
					throw new Exception($this->getOperationName() . ": invalid type specified.");

				// improper type
				if ( ! $param instanceof AbstractExpression::$TYPE_MAP[$type] )
					throw new Exception($this->getOperationName() . ": the parameter at index $i must be of type $type.");
			}
		}
	}*/

	/**
	 * All parameters have to be a string.
	 */
    static function getParameterTypes() {
		return AbstractExpression::$STRING_TYPE;
	}
}

?>