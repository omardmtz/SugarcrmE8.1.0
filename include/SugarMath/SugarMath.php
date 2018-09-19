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
 * SugarMath
 *
 * A class for handling math functions with arbitrary precision in an object-oriented manner.
 * This is basically a wrapper around the PHP BCMATH library. This is perfectly suited for
 * currency calculations, or anywhere fixed point math calculations are important.
 *
 * Example:
 *
 * // PHP will result in "7" because internal representation is 7.9999999999999991118...
 * echo floor((0.1+0.7)*10);
 *
 * // this will result in "8" as expected, the internal representation is 8.0
 * echo floor(SugarMath::init(0.1)->add(0.7)->mul(10)->result());
 *
 * SugarMath can also process math expressions. Expressions have an advantage over
 * individual computations in that they calculate the entire expression at a higher
 * precision (+10 decimals) and then round the final value to the intended precision.
 *
 * Examples:
 *
 * SugarMath::init()->exp("1+2/3*(4+5)^2")->result();
 * // reusable expression with params
 * SugarMath::init()->exp("1+?-4*(?+?)",array($p1,$p2,$p3))->result();
 *
 */
class SugarMath
{

    /**
     * the current value being applied
     *
     * @var string $value
     */
    protected $value = '0';

    /**
     * the math decimal precision, default is 6
     *
     * @var int $scale
     */
    protected $scale = 6;

    /**
     * class constructor
     *
     * @param mixed $value the starting value to apply math to
     * @param int $scale Optional math scale
     */
    public function __construct($value = '0', $scale = null)
    {
        $this->setValue($value);
        if (isset($scale)) {
            $this->setScale($scale);
        }
    }

    /**
     * returns current value when retrieving the object itself
     *
     * @return string result value
     */
    public function __toString()
    {
        return (string)$this->result();
    }

    /**
     * create a new object statically, can directly chain
     * example: $foo = SugarMath::init(500,2)->add(45)->mul(3);
     *
     * @param  mixed $value
     * @param  int   $scale
     * @return SugarMath
     */
    static public function init($value = '0', $scale = null)
    {
        return new self($value, $scale);
    }

    /**
     * set the scale value
     *
     * @param  int $scale
     * @return SugarMath object
     */
    public function setScale($scale)
    {
        $this->testValue($scale, 'intpos', 'scale must be a positive integer');
        $this->scale = $scale;
        return $this;
    }

    /**
     * get the scale value
     *
     * @return int
     */
    public function getScale()
    {
        return $this->scale;
    }

    /**
     * set the current value
     *
     * @param  mixed $value
     * @return SugarMath object
     */
    public function setValue($value)
    {
        $this->testValue($value);
        $this->value = $value;
        return $this;
    }

    /**
     * get the current value
     *
     * @param bool $round round result value to given scale?
     * @return number
     */
    public function result($round = true)
    {
        return $round ? $this->round($this->value) : $this->value;
    }

    /**
     * apply a math operation
     *
     * @param  string     $operator string type of math operation
     * @param  array|null $params array|null parameter values for operation
     * @throws SugarMath_Exception
     * @return string     operation result
     */
    protected function _applyOperation($operator, $params = null)
    {
        switch ($operator) {
            case 'add':
                return bcadd($params[0], $params[1], $this->scale);
                break;
            case 'sub':
                return bcsub($params[0], $params[1], $this->scale);
                break;
            case 'mul':
                return bcmul($params[0], $params[1], $this->scale);
                break;
            case 'div':
                return bcdiv($params[0], $params[1], $this->scale);
                break;
            case 'pow':
                return bcpow($params[0], $params[1], $this->scale);
                break;
            case 'mod':
                return bcmod($params[0], $params[1]);
                break;
            case 'powmod':
                return bcpowmod($params[0], $params[1], $params[2], $this->scale);
                break;
            case 'sqrt':
                return bcsqrt($params[0], $this->scale);
                break;
            case 'comp':
                return bccomp($params[0], $params[1], $this->scale);
                break;
            default:
                throw new SugarMath_Exception("unknown operator '{$operator}'");
                break;
        }
    }

    /**
     * add to current value
     *
     * @param mixed    $value
     * @return SugarMath object
     */
    public function add($value)
    {
        $this->value = $this->_applyOperation('add', array($this->value, $value));
        return $this;
    }

    /**
     * subtract from current value
     *
     * @param mixed    $value
     * @return SugarMath object
     */
    public function sub($value)
    {
        $this->value = $this->_applyOperation('sub', array($this->value, $value));
        return $this;
    }

    /**
     * multiply current value
     *
     * @param mixed    $value
     * @return SugarMath object
     */
    public function mul($value)
    {
        $this->value = $this->_applyOperation('mul', array($this->value, $value));
        return $this;
    }

    /**
     * divide current value
     *
     * @param mixed    $value
     * @return SugarMath object
     */
    public function div($value)
    {
        $this->value = $this->_applyOperation('div', array($this->value, $value));
        return $this;
    }

    /**
     * find modulus of current value
     *
     * @param int      $mod
     * @return SugarMath object
     */
    public function mod($mod)
    {
        $this->value = $this->_applyOperation('mod', array($this->value, $mod));
        return $this;
    }

    /**
     * find power of current value
     *
     * @param int      $pow
     * @return SugarMath object
     */
    public function pow($pow)
    {
        $this->value = $this->_applyOperation('pow', array($this->value, $pow));
        return $this;
    }

    /**
     * find power of current value and return its modulus
     *
     * @param mixed    $pow
     * @param int      $mod
     * @return SugarMath object
     */
    public function powmod($pow, $mod)
    {
        $this->value = $this->_applyOperation('powmod', array($this->value, $pow, $mod));
        return $this;
    }

    /**
     * find square root of current value
     *
     * @return SugarMath object
     */
    public function sqrt()
    {
        $this->value = $this->_applyOperation('sqrt', array($this->value));
        return $this;
    }

    /**
     * compare current value to this one
     *
     * @param mixed    $value
     * @return int  0 if equal, 1 if current value is greater, -1 otherwise
     */
    public function comp($value)
    {
        return $this->_applyOperation('comp', array($this->value, $value));
    }

    /**
     * round value to given precision
     *
     * @param string $value
     * @param int $scale
     * @return string rounded value
     */
    public function round($value, $scale = null)
    {
        if (!isset($scale)) {
            $scale = $this->scale;
        }
        if (false !== ($pos = strpos($value, '.')) && (strlen($value) - $pos - 1) > $scale) {
            $zeros = str_repeat("0", $scale);
            return bcadd($value, (($value < 0) ? '-' : '') . "0.{$zeros}5", $scale);
        } else {
            return bcadd($value, 0, $scale);
        }
    }

    /**
     * test that value is numeric
     *
     * @param number|string $value
     * @param string        $type Optional type of test
     * @param string        $errorMsg Optional error message to show
     * @throws SugarMath_Exception
     * @return boolean false on failure
     */
    protected function testValue($value, $type = 'numeric', $errorMsg = null)
    {
        switch ($type) {
            case 'numeric':
            default:
                if (!is_numeric($value)) {
                    $message = isset($errorMsg) ? $errorMsg : "value '{$value}' must be numeric";
                    throw new SugarMath_Exception("{$message}");
                }
                break;
            case 'int':
            case 'intpos':
                if (!is_numeric($value) || strpos((string)$value, '.') !== false) {
                    $message = isset($errorMsg) ? $errorMsg : "value '{$value}' must be an integer";
                    throw new SugarMath_Exception("{$message}");
                }
                if ($type == 'intpos') {
                    if ($value < 0) {
                        $message = isset($errorMsg) ? $errorMsg : "value '{$value}' must be a positive integer";
                        throw new SugarMath_Exception("{$message}");
                    }
                }
                break;
        }
        return true;
    }

    /**
     * calculate a math expression and return the result
     *
     * Example:
     *
     * exp("23.33 + ? * (4 - ?) / ?", array($v1, $v2, $v3))->result();
     *
     * @param string   $exp math expression
     * @param array    $args values for the ? parts of the expression
     * @throws SugarMath_Exception
     * @return SugarMath object
     */
    public function exp($exp, $args = array())
    {
        if (strlen($exp) == 0) {
            // expression empty, set to 0
            $this->value = '0';
            return $this;
        }
        if (!isset($args)) {
            $args = array();
        }
        if (!is_string($exp)) {
            throw new SugarMath_Exception('expression must be a string');
        }
        if (!is_array($args)) {
            throw new SugarMath_Exception('expression args must be an array');
        }
        if (count($args) > 0) {
            foreach ($args as $arg) {
                $this->testValue($arg, 'numeric', 'arguments must be numeric');
            }
        }
        // number of ? must match number of args
        if (substr_count($exp, '?') !== count($args)) {
            throw new SugarMath_Exception('number of args mismatch number of ? in exp');
        }
        // expression parenthesis must be balanced
        if (substr_count($exp, '(') !== substr_count($exp, ')')) {
            throw new SugarMath_Exception('parenthesis mismatch');
        }
        // give us ample of precision for the internal calculations
        $this->scale += 10;
        // convert infix expression into postfix (reverse polish notation)
        $output = array(); // our output queue for RPN
        $stack = array(); // our operand stack
        $isAfterOperand = false; // track if we are right after an operand
        // define operator precedence/associativity
        $ops = array(
            '+' => array(2, 'L'),
            '-' => array(2, 'L'),
            '*' => array(3, 'L'),
            '/' => array(3, 'L'),
            '%' => array(3, 'L'),
            '^' => array(4, 'R'),
        );
        // define possible values for an operator (or sign)
        $opsVals = array_keys($ops);
        // define possible values for an operand
        $nums = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.');
        $thisNum = '';
        // loop through the expression left-to-right, placing operators
        // and operands onto the stack, and ultimately onto the output queue
        // in reverse polish notation for later processing. Only operators
        // operands and parenthesis are allowed, no function calls.
        for ($x = 0, $y = strlen($exp); $x < $y; $x++) {
            // grab current char
            $char = $exp[$x];
            if ($char == '?') {
                // if ?, replace with argument from list, place on output queue
                $arg = array_shift($args);
                $output[] = $arg;
                $isAfterOperand = true;
            } elseif (in_array($char, $nums)) {
                // if operand, accumulate chars of current number,
                // place on the output queue
                $thisNum .= $char;
                while ($x < $y - 1 && in_array($exp[$x + 1], $nums)) {
                    $thisNum .= $exp[$x + 1];
                    $x++;
                }
                $output[] = $thisNum;
                $thisNum = '';
                $isAfterOperand = true;
            } elseif (in_array($char, $opsVals)) {
                // do not allow operators immediately after another operator (except + or -)
                if (!$isAfterOperand && !in_array($char, array('+', '-'))) {
                    throw new SugarMath_Exception("grouped operators error");
                }
                // if operator, see if operator at the top of stack has
                // higher precedence, and if so continue to pop from stack
                // and push onto the output queue until false
                while (in_array($last_op = end($stack), $opsVals)) {
                    if (
                        ($ops[$char][1] == 'L' && $ops[$char][0] <= $ops[$last_op][0])
                        || ($ops[$char][0] < $ops[$last_op][0])
                    ) {
                        $output[] = array_pop($stack);
                    } else {
                        break;
                    }
                }
                // push operator onto ops stack
                $stack[] = $char;
                $isAfterOperand = false;
            } elseif ($char == '(') {
                // push opening parenthesis onto stack
                $stack[] = $char;
            } elseif ($char == ')') {
                // push operators onto output queue until matching left parenthesis is found
                while (($last_op = array_pop($stack))) {
                    if ($last_op === null) {
                        // ran out of stack with no left parenthesis found, error
                        throw new SugarMath_Exception('exp() unbalanced parenthesis');
                    } elseif ($last_op == '(') {
                        // left parenthesis found, we are done looping
                        break;
                    } else {
                        // push operators onto the output, continue looping
                        $output[] = $last_op;
                    }
                }
            } else {
                // throw error for invalid chars in expression
                if (!in_array($char, array(' '))) {
                    throw new SugarMath_Exception('invalid expression syntax');
                }
            }
        }
        while (($last_op = array_pop($stack)) !== null) {
            // push remaining operators onto the stack. if parenthesis found, error
            if ($last_op == '(' || $last_op == ')') {
                throw new SugarMath_Exception('exp() unbalanced parenthesis');
            }
            $output[] = $last_op;
        }
        // calculate using reverse polish notation from output queue
        $result = array();
        foreach ($output as $val) {
            if (!in_array($val, $opsVals)) {
                // not an operator, push onto result stack
                $result[] = $val;
            } else {
                // operator, pop last to operands and apply math, push back onto result stack
                $p1 = array_pop($result);
                $p2 = array_pop($result);
                switch ($val) {
                    case '+':
                        $result[] = $this->_applyOperation('add', array($p2, $p1));
                        break;
                    case '-':
                        $result[] = $this->_applyOperation('sub', array($p2, $p1));
                        break;
                    case '*':
                        $result[] = $this->_applyOperation('mul', array($p2, $p1));
                        break;
                    case '/':
                        $result[] = $this->_applyOperation('div', array($p2, $p1));
                        break;
                    case '%':
                        $result[] = $this->_applyOperation('mod', array($p2, $p1));
                        break;
                    case '^':
                        $result[] = $this->_applyOperation('pow', array($p2, $p1));
                        break;
                }
            }
        }
        // set scale back to original value
        $this->scale -= 10;
        // if original expression was empty parenthesis, result will be 0
        $this->value = !empty($result) ? $result[0] : '0';
        return $this;
    }
}
