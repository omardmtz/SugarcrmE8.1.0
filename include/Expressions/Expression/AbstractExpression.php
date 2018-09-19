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
 * Base expression class
 * @api
 */
abstract class AbstractExpression
{
	// constants
	public static $INFINITY = -1;

	// type constants
	public static $STRING_TYPE   = "string";
	public static $NUMERIC_TYPE  = "number";
	public static $DATE_TYPE 	 = "date";
	public static $TIME_TYPE 	 = "time";
	public static $BOOLEAN_TYPE  = "boolean";
	public static $ENUM_TYPE 	 = "enum";
    public static $RELATE_TYPE   = "relate";
	public static $GENERIC_TYPE  = "generic";

	// booleans
    public static $TRUE;
	public static $FALSE;

	// type to class map
    public static $TYPE_MAP = array(
        "number" => "NumericExpression",
        "string" => "StringExpression",
        "date" => "DateExpression",
        "time" => "TimeExpression",
        "boolean" => "BooleanExpression",
        "enum" => "EnumExpression",
        "relate" => "RelateExpression",
        "generic" => "AbstractExpression",
    );

    /**
     * The type to expression class map for implicit type casting.
     */
    protected static $TYPE_CAST_MAP = array(
        'number' => 'ValueOfExpression',
        'string' => 'DefineStringExpression',
    );

	// instance variables
	var $params;

	/**
	 * Constructs an Expression object given the parameters.
	 */
    public function __construct($params=null)
    {
        self::initBoolConstants();
		// if the array contains only one value, then set params equal to that value
		if ($this->getParamCount() == 1 && is_array($params) && sizeof($params) == 1) {
			$this->params = $params[0];
		}

		// if params is an array with more than or less than 1 value
		else {
			$this->params = $params;
		}

		// validate the parameters
		$this->validateParameters();
	}

	/**
	 * Returns the parameter list for this Expression.
	 */
	function getParameters() {
        if (!$this->params) {
            return $this->params;
        }
        $types = $this->getParameterTypes();
        $oneParam = $this->getParamCount() == 1;

        $params = $this->params;
        if ($oneParam) {
            $params = array($params);
        }

        if (!is_array($types)) {
            $types = array_fill(0, count($params), $types);
        }

        $result = array();
        foreach ($params as $i => $param) {
            if ($param instanceof self) {
                $type = self::getType($param);
                if ($type != $types[$i] && isset(self::$TYPE_CAST_MAP[$types[$i]])) {
                    $class = self::$TYPE_CAST_MAP[$types[$i]];
                    $param = new $class($param);
                }
            }
            $result[] = $param;
        }

        if ($oneParam) {
            $result = array_shift($result);
        }

        return $result;
	}

	/**
	 * Evaluates this expression and returns the
	 * resulting value.
	 */
	abstract function evaluate();

	/**
	 * Returns the JavaScript equivalent for the evaluate
	 * function.
	 */
    public static function getJSEvaluate()
    {
        throw new BadMethodCallException(__METHOD__ . ' is not implemented');
    }

	/**
	 * Returns a string representation of this expression.
	 * TODO: Make this an abstract method.
	 */
	function toString() {}

	/**
	 * Defines the required types of each of the individual parameters.
	 */
    public static function getParameterTypes()
    {
        throw new BadMethodCallException(__METHOD__ . ' is not implemented');
    }

    /**
     * Returns the operation name or names that this expression should be called by
     *
     * @return string|string[]
     */
    public static function getOperationName()
    {
        throw new BadMethodCallException(__METHOD__ . ' is not implemented');
    }

	/**
	 * Validates the parameters and throws an Exception if invalid.
	 */
	function validateParameters() {
		// retrieve the params, the param count, and the param types
        $params = $this->params;
		$count  = $this->getParamCount();
		$types  = $this->getParameterTypes();

		// retrieve the operation name for throwing exceptions
		$op_name = call_user_func(array(get_class($this), "getOperationName"));

        /* parameter and type validation */

		// make sure count is a number
		if ( !is_numeric($count) )	throw new Exception($op_name . ": Number of paramters required must be a number");

		// make sure types is a array or a string
		if ( !is_string($types) && !is_array($types) )
			throw new Exception($op_name . ": Parameter types must be valid and match the parameter count");

		// make sure sizeof types is equal to parameter count
		if ( is_array($types) && $count != AbstractExpression::$INFINITY && $count != sizeof($types) )
			throw new Exception($op_name . ": Parameter types must be valid and match the parameter count");

		// make sure types is valid
		if ( is_string($types) ) {
			if ( ! isset( AbstractExpression::$TYPE_MAP[$types] ) )
				throw new Exception($op_name . ": Invalid type requirement '$types'");
		} else {
			foreach ( $types as $type )
				if ( ! isset(AbstractExpression::$TYPE_MAP[$type]) )
					throw new Exception($op_name . ": Invalid type requirement");
		}


		/* parameter and count validation */

		// if we want 0 params and we got 0 params, forget it
		if ( $count == 0 && !isset($params) )	return;

		// if we want a single param, validate that param
		if ( $count == 1 && $this->isProperType($params, $types) )	return;

		// we require multiple but params only has 1
		if ( $count > 1 && !is_array($params) )
            throw new Exception($op_name . ": Requires exactly $count parameter(s), only one passed in");
		// we require only 1 and params has multiple
		if ( $count == 1 && is_array($params) )
			throw new Exception($op_name . ": Requires exactly $count parameter(s), more than one passed in");

		// check parameter count
		if ( $count != AbstractExpression::$INFINITY && sizeof($params) != $count )
			throw new Exception($op_name . ": Requires exactly $count parameter(s)");

		// if a generic type is specified
		if ( is_string( $types ) ) {
			// only a single parameter
			if ( !is_array($params) ) {
				if ( $this->isProperType( $params, $types ) )	return;
				throw new Exception($op_name . ": All parameters must be of type '$types'");
			}

			// multiple parameters
			foreach ( $params as $param ) {
				if ( ! $this->isProperType( $param, $types ) )
					throw new Exception($op_name . ": All parameters must be of type '$types'");
			}
		}

		// if strict type constraints are specified
		else {
			// only a single parameter
			if ( !is_array($params) ) {
				if ( $this->isProperType( $params, $types[0] ) )	return;
				throw new Exception($op_name . ": Parameter must be of type '" . $types[0] . "'");
			}

			// improper type
			for ( $i = 0 ; $i < sizeof($types) ; $i++ ) {
				if ( ! $this->isProperType( $params[$i], $types[$i] ) )
					throw new Exception($op_name . ": the parameter at index $i must be of type " . $types[$i]);
			}
		}
	}

	/**
	 * Enforces the parameter types.
	 */
	function isProperType($variable, $type) {
		if ( is_array($type) )	return false;

		// retrieve the class
		$class = AbstractExpression::$TYPE_MAP[$type];

		// check if type is empty
		if ( !isset($class) )
            return false;

		// check if it's an instance of type
		if($variable instanceof $class);
            return true;

		// now check for generics
		switch($type) {
			case AbstractExpression::$STRING_TYPE:
				return (is_string($variable) || is_numeric($variable)
				    || $variable instanceof AbstractExpression::$TYPE_MAP[AbstractExpression::$NUMERIC_TYPE]);
				break;
			case AbstractExpression::$NUMERIC_TYPE:
				return (is_numeric($variable) );
				break;
			case AbstractExpression::$BOOLEAN_TYPE:
				if ( $variable instanceof Expression )	$variable = $variable->evaluate();
				return ($variable == AbstractExpression::$TRUE || $variable == AbstractExpression::$FALSE );
				break;
            case AbstractExpression::$DATE_TYPE:
            case AbstractExpression::$TIME_TYPE:
                if ( $variable instanceof Expression )	$variable = $variable->evaluate();
				return ((is_string($variable) && new DateTime($variable) !== false));
				break;
            case AbstractExpression::$RELATE_TYPE:
                return true;
		}

		// just return whether it is an instance or not
		return false;
	}

	/**
	 * Returns the exact number of parameters needed
	 * which is set as infinite by default.
	 */
	static function getParamCount() {
		return AbstractExpression::$INFINITY;
	}

    /**
     * Initialize function for the TRUE/FALSE constants. Should only be called by the abstract class constructor
     */
    protected static function initBoolConstants()
    {
        if (empty(self::$TRUE)) {
            self::$TRUE = new BooleanConstantExpression(true);
        }
        if (empty(self::$FALSE)) {
            self::$FALSE = new BooleanConstantExpression(false);
        }
    }

    /**
     * Returns the value type of the given variable
     *
     * @param mixed $variable
     * @return string|null
     */
    protected static function getType($variable)
    {
        foreach (self::$TYPE_MAP as $type => $class) {
            if ($variable instanceof $class) {
                return $type;
            }
        }

        return null;
    }
}

/**
 * Internal SugarLogic class to define boolean constant values to prevent false positives/negatives when comparing to string/numeric values
 */
class BooleanConstantExpression {
    protected $value;

    public function __construct($value) {
        $this->value = !empty($value);
    }

    public function __toString() {
        if ($this->value) {
            return "true";
        } else {
            return "false";
        }
    }

}
