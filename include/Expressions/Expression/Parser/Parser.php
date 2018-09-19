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
 * Expression parser
 * @api
 */
class Parser {
    static $NUMERIC_CONSTANTS =  array(
    	'pi' 	=> 3.141592653589793,
    	'e'		=> 2.718281828459045,
    );

    // the function map
    public static $function_cache = array();

    /**
     * Evaluates an expression.
     *
     * @throws Exception
     * @param string $expr the expression to evaluate
     * @param object|boolean $context optional
     * @return AbstractExpression
     */
	static function evaluate($expr, $context = false)
	{


		// trim spaces, left and right, and remove newlines
		$expr = str_replace("\n", "", trim($expr));

		// check if its a constant and return a constant expression
		$const = Parser::toConstant($expr);
		if ( isset($const) )	return $const;

        if (preg_match('/^\$[a-zA-Z0-9_\-]+$/', $expr))
        {
            $var = substr($expr, 1);
            $ret = new SugarFieldExpression($var);
            if ($context) $ret->context = $context;
            return $ret;
        }
        //Related field shorthand
        if (preg_match('/^\$[a-zA-Z0-9_\-]+\.[a-zA-Z0-9_\-]+$/', $expr))
        {
            $link = substr($expr, 1, strpos($expr, ".") - 1);
            $related = substr($expr, strpos($expr, ".") + 1);
            $linkField = new SugarFieldExpression($link);
            if ($context) $linkField->context = $context;
            return new RelatedFieldExpression(array(
                $linkField, Parser::toConstant('"' . $related . '"'))
            );

        }


		// VALIDATE: expression format
		if ( ! preg_match('/^[a-zA-Z0-9_\-$]+\(.*\)$/', $expr) ) {
			throw new Exception("Attempted to evaluate expression with an invalid format: $expr");
			return;
		}

		// EXTRACT: Function
		$open_paren_loc = strpos($expr, '(');

		// if no open-paren '(' found
		if ( $open_paren_loc < 0 )	{
            throw new Exception("Attempted to evaluate expression with a Syntax Error (No opening paranthesis found): $expr");
            return;
        }

		// get the function
		$func   = substr( $expr , 0 ,  $open_paren_loc);

        // handle if function is not valid
        if (empty(static::$function_cache)) {
            $cacheFile = sugar_cached('Expressions/functionmap.php');
            if (!file_exists($cacheFile)) {
                $GLOBALS['updateSilent'] = true;
                include("include/Expressions/updatecache.php");
            }

            // $FUNCTION_MAP is pulled in from the file.
            if (empty($FUNCTION_MAP)) {
                require $cacheFile;
            }
            static::$function_cache = $FUNCTION_MAP;
        }


		if ( !isset(static::$function_cache[$func]) )	{
            throw new Exception("Attempted to evaluate expression with an invalid function '$func': $expr");
            return;
        }

		// EXTRACT: Parameters
		$params = substr( $expr , $open_paren_loc + 1, -1);

		// now parse the individual parameters recursively
		$level  = 0;
		$length = strlen($params);
		$argument = "";
		$args = array();

		// flags
		$char 			= null;
		$lastCharRead	= null;
		$justReadString	= false;		// did i just read in a string
		$isInQuotes 	= false;		// am i currently reading in a string
		$isPrevCharBK 	= false;		// is my previous character a backslash
        $isInVariable 	= false;		// is my previous character a backslash

		for ( $i = 0 ; $i <= $length ; $i++ ) {
			// store the last character read
			$lastCharRead = $char;

			// the last parameter
			if ( $i == $length ) {
                if ($argument != "") {
				    $subExp = Parser::evaluate($argument, $context);
                    $subExp->context = $context;
                    $args[] = $subExp;
                }
				break;
			}

			// set isprevcharbk
			$isPrevCharBK = $lastCharRead == '\\';

			// get the charAt index $i
			$char = $params{$i};

			// if i am in quotes, then keep reading
			if ( $isInQuotes && $char != '"' && !$isPrevCharBK ) {
				$argument .= $char;
				continue;
			}

			// check for quotes
			if ( $char == '"' && !$isPrevCharBK && $level == 0 )
			{
				// if i am ending a quote, then make sure nothing follows
				if ( $isInQuotes ) {
					// only spaces may follow the end of a string
					$temp = substr($params, $i+1, strpos($params, ",", $i) - $i - 1 );
					if ( !preg_match( '/^(\s*|\s*\))$/', $temp ) ) {
			            throw new Exception("Syntax Error:Improperly Terminated String '$temp' in formula: $expr");
			            return;
			        }
				}

				// negate if i am in quotes
				$isInQuotes = !$isInQuotes;
			}

            if( $char == '$' && !$isInQuotes && !$isPrevCharBK)
            {
                if($isInVariable) {
                    throw new Exception ("Syntax Error: Invalid variable name in formula: $expr");
                }
            }

			// check parantheses open/close
			if ( $char == '(' ) {
				$level++;
			} else if ( $char == ')' ) {
				$level--;
			}
			// argument splitting
			else if ( $char == ',' && $level == 0 ) {
				$subExp = Parser::evaluate($argument, $context);
                $subExp->context = $context;
                $args[] = $subExp;
				$argument = "";
				continue;
			}

			// construct the next argument
			$argument .= $char;
		}


		// now check to make sure all the parantheses opened were closed
		if ( $level != 0 )	{
            throw new Exception("Syntax Error (Incorrectly Matched Parantheses) in formula: $expr");
            return;
        }

		// now check to make sure all the quotes opened were closed
		if ( $isInQuotes )	if ( $level != 0 ) {
            throw new Exception("Syntax Error (Unterminated String Literal) in formula: $expr");
            return;
        }

		// require and return the appropriate expression object
		$expObject = new static::$function_cache[$func]['class']($args);
        if ($context) {
            $expObject->context = $context;
        }
		return $expObject;
	}

	/**
	 * Takes in a string and returns a ConstantExpression if the
	 * string can be converted to a constant.
	 */
	static function toConstant($expr) {

		// a raw numeric constant
		if ( preg_match('/^(\-)?[0-9]*(\.[0-9]+)?$/', $expr) ) {
			return new ConstantExpression($expr);
		}

		// a pre defined numeric constant
        if (isset(self::$NUMERIC_CONSTANTS[$expr]))
		{
			return new ConstantExpression(self::$NUMERIC_CONSTANTS[$expr]);
		}

		// a constant string literal
		if ( preg_match('/^".*"$/', $expr) ) {
			$expr = substr($expr, 1, -1);		// remove start/end quotes

			return new StringLiteralExpression( $expr );
		}

		// a boolean
		if ( $expr == "true" ) {
			return new TrueExpression();
		} else if ( $expr == "false" ) {
			return new FalseExpression();
		}

		// a date
		if ( preg_match('/^(0[0-9]|1[0-2])\/([0-2][0-9]|3[0-1])\/[0-3][0-9]{3,3}$/', $expr) ) {
			return new DefineDateExpression(new StringLiteralExpression( $expr ));
		}

		// a time
		if ( preg_match('/^([0-1][0-9]|2[0-4]):[0-5][0-9]:[0-5][0-9]$/', $expr) ) {
			return new DefineDateExpression($expr);
		}

		// neither
		return null;
	}

	/**
	 * Throws a custom exception with a predefined prefix and message.
	 */
	static function throwException($function, $type, $message) {
		throw new Exception("$function : $type ($message)");
	}

	/**
     * @deprecated
	 * returns the expression with the variables replaced with the values in target.
	 *
	 * @param string $expr
	 * @param Array/SugarBean $target
	 */
	static function replaceVariables($expr, $target) {
		$target->load_relationships();
        $variables = Parser::getFieldsFromExpression($expr);
		$ret = $expr;
		foreach($variables as $field) {
			if (is_array($target))
			{
				if (isset($target[$field])) {
					$val = Parser::getFormatedValue($target[$field], $field);
					$ret = str_replace("$$field", $val, $ret);
				} else {
				    continue;
                    //throw new Exception("Unknown variable $$field in formula: $expr");
                    //return;
				}
			} else
			{
				//Special case for link fields
                if (isset($target->field_defs[$field]) && $target->field_defs[$field]['type'] == "link")
                {
                    $val = "link(\"$field\")";
                    $ret = str_replace("$$field", $val, $ret);
                }
                else if (isset ($target->$field)) {

                        $val = Parser::getFormatedValue($target->$field, $field);
                        $ret = str_replace("$$field", $val, $ret);
                    } else  {
                        continue;
                       // throw new Exception("Unknown variable $$field in formula: $expr");
                       // return;
                    }
                }
			}
		return $ret;
	}

	private static function getFormatedValue($val, $fieldName) {
		//Boolean values
		if ($val === true) {
			return AbstractExpression::$TRUE;
		} else if ($val === false) {
			return AbstractExpression::$FALSE;
		}

		//Number values will be stripped of commas
		if (preg_match('/^(\-)?[0-9,]+(\.[0-9]+)?$/', $val)) {
			$val = str_replace(',', '', $val);
		}
		//Strings should be quoted
		else {
			$val = '"' . $val . '"';
		}

		return $val;
	}

    /**
     * @static
     * @param $expr
     * @param SugarBean $context
     * @return array
     */
    static function getFieldsFromExpression($expr, $fieldDefs = false) {
    	$matches = array();
    	preg_match_all('/\$(\w+)/', $expr, $matches);
    	$fields = array_values($matches[1]);
        if ($fieldDefs){
            //Now attempt to map the relate field to the link
            foreach($fieldDefs as $name => $def)
            {
                if (isset($def['type']) && $def['type'] == 'relate' && !empty($def['link']) && in_array($def['link'], $fields) && !empty($def['id_name']))
                {
                    $fields[] = $def['id_name'];
                }
            }
        }
        return $fields;
    }

    /**
     * Test if a expression is a Related Expression
     *
     * This should be updated anytime a new RelatedExpression is added
     *
     * @param AbstractExpression $expr The current Expression
     * @return bool
     */
    public static function isRelatedExpression($expr)
    {
        return ($expr instanceof RelatedFieldExpression
            || $expr instanceof MinRelatedExpression
            || $expr instanceof MaxRelatedExpression
            || $expr instanceof AverageRelatedExpression
            || $expr instanceof SumRelatedExpression
            || $expr instanceof SumConditionalRelatedExpression
            || $expr instanceof MaxRelatedDateExpression
            || $expr instanceof CountRelatedExpression
            || $expr instanceof CountConditionalRelatedExpression
        );
    }

    /**
     * Look for a relationship in an expression,
     *
     * @param AbstractExpression $expr
     * @param string {$linkName}
     * @return array
     */
    public static function getFormulaRelateFields($expr, $linkName = '')
    {
        $result = array();

        if (static::isRelatedExpression($expr)) {
            /** @var AbstractExpression[] $params */
            $params = $expr->getParameters();

            // if this is not an array, then it's one of the few that have additional fields
            if (is_array($params)) {
                // here we don't evaluate the first param since we need the field name
                // but not it's value
                if ($params[0] instanceof SugarFieldExpression) {
                    if ($linkName === '' || $params[0]->varName === $linkName) {
                        $result[] = $params[1]->evaluate();
                    }
                }

                if ($expr instanceof SumConditionalRelatedExpression) {
                    // with this one, the third param is also needed, since if it changes, it should trigger the
                    // logic hook to run
                    if ($linkName === '' || $params[0]->varName === $linkName) {
                        $result[] = $params[2]->evaluate();
                    }

                }
            }
            return $result;
        }

        $params = $expr->getParameters();
        if (is_array($params)) {
            /** @var AbstractExpression $param */
            foreach ($params as $param) {
                $result = array_merge(
                    $result,
                    self::getFormulaRelateFields($param, $linkName)
                );
            }
        } else if ($params instanceof AbstractExpression) {
             $result = array_merge(
                 $result,
                 self::getFormulaRelateFields($params, $linkName)
             );
        }

        return array_unique($result);
    }
}
