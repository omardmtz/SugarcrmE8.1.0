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
 * <b>valueAt(Number index, Enum values)</b><br>
 * Returns the value at position <i>index</i> in the collection <i>values</i>.<br/>
 * ex: <i>valueAt(1, enum("a", "b", "c") = "b"</i>
 */
class IndexValueExpression extends GenericExpression
{
	/**
	 * Returns the entire enumeration bare.
	 */
	function evaluate() {
		$params = $this->getParameters();
		$array  = $params[1]->evaluate();
		$index  = $params[0]->evaluate();

		if (is_numeric($index))
			$index = intval($index);

		if ( $index >= sizeof($array) || $index < 0 )
			throw new Exception( $this->getOperationName() . ": Attempt to access an index out of bounds" );

		return $array[$index];
	}


	/**
	 * Returns the JS Equivalent of the evaluate function.
	 */
	static function getJSEvaluate() {
		return <<<EOQ
			var params = this.getParameters();
			var array  = params[1].evaluate();
			var index  = params[0].evaluate();

			if (typeof index == 'string' && !isNaN(index))
				index = Number(index);

			if ( index >= array.length || index < 0 )
				throw ("value_at: Attempt to access an index out of bounds");

			return array[index];
EOQ;
	}

	/**
	 * Returns the opreation name that this Expression should be
	 * called by.
	 */
	static function getOperationName() {
		return "valueAt";
	}

	/**
	 * The first parameter is a number and the second is the list.
	 */
    static function getParameterTypes() {
		return array("number", "enum");
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

?>