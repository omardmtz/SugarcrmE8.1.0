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

require_once 'PMSEEvalRelations.php';

/**
 * Class criteria that evaluates whether logical, arithmetic, relationship
 * this expression must be of type json content into an array
 * the expression is transformed into an array which is evaluated according to the priorities of operator
 * the principal methods are expresions($expre) and expresionsRules($expre)
 *
 */
class PMSEEvalCriteria
{
    use PMSEEvalRelations;

    /**
     * Store the existing groups and subgroups
     * @var array
     */
    public $arrayGroups;

    /**
     * The types of operators that supports the evaluation according to the priorities
     * @var array
     */
    public $allOperators = array(
        'evalAritmetic' => array('x', '/', '+', '-'), //function for evalAritmetic
        'evalRelations' => array('<', '<=', '>', '>=', '==', '!='), //function for evalRelations
        'evalLogic' => array('NOT', 'AND', 'OR') //function for evalLogic
    );

    /**
     * history assessments by token
     * @var string
     */
    public $condition;

    /**
     * Method that evaluates an expression
     * @param array $expre parameter where the expression is to be evaluated
     * @return true o false depending on the form to be evaluated returns one of the values
     */
    public function expresions($expre)
    {
        $arrayExpre = $expre;
        $this->condition = '';
        if (!empty($arrayExpre)) {
            $ar = array();
            $this->condition = '{::';
            foreach ($arrayExpre as $value) {
                $this->condition .= isset($value->expModule) ? '_' . $value->expModule . '_' : '';
                switch ($value->expType) {
                    case 'GROUP':
                        $ar[] = $value->expValue;
                        $this->condition .= $value->expValue . '::';
                        break;
                    case 'LOGIC':
                        $ar[] = $value->expValue;
                        $this->condition .= '::' . $this->logicSimbol($value->expValue) . '::';
                        break;
                    case 'MODULE':
                        $ar[] = $this->evalRelations($value->currentValue, $value->expOperator, $value->expValue,
                            $value->expFieldType);
                        $this->condition .= isset($value->expLabel) ? '[' . $value->expLabel . ']' : '';
                        break;
                    case 'CONTROL':
                        $ar[] = $this->evalRelations($value->currentValue, $value->expOperator, $value->expValue,
                            $value->expFieldType);
                        $this->condition .= isset($value->expLabel) ? '[' . $value->expLabel . ']' : '';
                        break;
                    case 'BUSINESS_RULES':
                        $ar[] = $this->evalRelations($value->currentValue, $value->expOperator, $value->expValue,
                            $value->expFieldType);
                        $this->condition .= isset($value->expLabel) ? '[' . $value->expLabel . ']' : '';
                        break;
                    case 'USER_ADMIN':
                        $ar[] = $this->evalRelations($value->currentValue, $value->expOperator, $value->expValue,
                            $value->expFieldType);
                        $this->condition .= isset($value->expLabel) ? '[' . $value->expLabel . ']' : '';
                        break;
                    case 'USER_ROLE':
                        $ar[] = $this->evalRelations($value->currentValue, $value->expOperator, $value->expValue,
                            $value->expFieldType);
                        $this->condition .= isset($value->expLabel) ? '[' . $value->expLabel . ']' : '';
                        break;
                    case 'USER_IDENTITY':
                        $ar[] = $this->evalRelations($value->currentValue, $value->expOperator, $value->expValue,
                            $value->expFieldType);
                        $this->condition .= isset($value->expLabel) ? '[' . $value->expLabel . ']' : '';
                        break;
//                    default:
//                        break;
                }
            }
            $this->condition .= '::}';
            if ($this->evaluationsRecursive($ar) == 1) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    /**
     * Method expression that evaluates to the Business Rules
     * @param Json $expre parameter where the expression is to be evaluated
     * @return Boolean depending on the form to be evaluated returns one of the values
     * @codeCoverageIgnore
     */
    public function expresionsRules($expre)
    {
        $arrayExpre = $expre;
        if (!empty($arrayExpre)) {
            $arrayExpresion = array();
            foreach ($arrayExpre->ruleset as $conditions) {
                $row = (object)$conditions;
                foreach ($row->conditions as $value) {
                    $arrayValues = $value->value;
                    $val = array_shift($value->value);
                    if (empty($value->value)) {
                        if ($val->type == 'VAR') {
                            $arrayExpresion[] = isset ($value->expFieldType) ? $this->evalRelations($value->variable_value,
                                $value->condition, $val->variable_value, $value->expFieldType) : '';
                        } else {
                            $arrayExpresion[] = isset ($value->expFieldType) ? $this->evalRelations($value->variable_value,
                                $value->condition, $val->value, $value->expFieldType) : '';
                        }
                    } else {
                        $arr = array();
                        foreach ($arrayValues as $val) {
                            $arr[] = $val->type == 'VAR' ? $val->variable_value : $val->value;
                        }
                        $res = $this->evaluationsRecursive($arr);
                        $arrayExpresion[] = isset($value->expFieldType) ? $this->evalRelations($value->variable_value,
                            $value->condition, $res, $value->expFieldType) : '';
                    }
                    $arrayExpresion[] = 'AND';
                }
            }
            array_pop($arrayExpresion);
            if ($this->evaluationsRecursive($arrayExpresion) == 1) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    /**
     * Method conducts an evaluation recursively
     * @param arrar $array where the values ​​and operators are to be evaluated with
     * @return int
     */
    public function evaluationsRecursive($array)
    {
        if (count($array) == 1) {
            return array_shift($array);
        } else {
            //We set the array to check for groups within the array
            $this->verifyGroups($array);
            //check if you have the same amount of (, ) y/o [, ] y/o {, } 
            if (!$this->verifyEqualsGroups()) {
                return 0;
            }
            //get groups in order of priority
            $group = array_shift($this->arrayGroups);
            if (!empty($group)) {
                $subGroup = array_shift($group);
                $signe = key($group); //closing sign
            } else {
                $subGroup = array();
                $signe = '';
            }
            //if we divide groups into subgroups, a new array
            if (count($subGroup) > 0) {
                $ini = $h = array_pop($subGroup);
                $sw = 0;
                while ($h < count($array) && $sw == 0) {
                    if ("$array[$h]" == "$signe") {
                        $fin = $h;
                        $sw = 1;
                    }
                    $h++;
                }
                //new array of the subgroup
                $nuwArray = array();
                for ($index = $ini + 1; $index <= $fin - 1; $index++) {
                    $nuwArray[] = $array[$index];
                    unset($array[$index]);
                }
                //send the array to be evaluated
                if (!empty($nuwArray)) {
                    unset($array[$fin]);
                    $array[$ini] = $this->evaluationsRecursive($nuwArray);
                }
                $array = array_values($array);
                $result = $this->evaluationsRecursive($array);
            } else {
                //if there is NO groups perform the evaluation
                $result = $this->evaluation($array);
            }
            return $result;
        }
    }

    /**
     * Check to see if there groups and get their positions
     * @param array $array Array in which groups will verify if there
     * @param array $this ->arrayGroups the number of groups is stored in this attribute
     */
    public function verifyGroups($array)
    {
        $arrayGroups = array(array("(", ")"), array("[", "]"), array("{", "}"));
        $arrayGroupsExist = array();
        foreach ($arrayGroups as $group) {
            $arrGroup = array();
            foreach ($group as $sig) {
                $arrSubGroup = array();
                $i = 0;
                while ($i < count($array)) {
                    if ("$array[$i]" == $sig) {
                        $arrSubGroup[] = $i;
                    }
                    $i++;
                }
                if (!empty($arrSubGroup)) {
                    $arrGroup[$sig] = $arrSubGroup;
                }
            }
            if (!empty($arrGroup)) {
                $arrayGroupsExist[] = $arrGroup;
            }
        }
        $this->arrayGroups = $arrayGroupsExist;
    }

    /**
     * We check parity groups if there is a (,), [,], {,} that is not closed
     * @return Boolean if there is the same amount associators that open and close one we return false
     */
    public function verifyEqualsGroups()
    {
        $arrayGroups = $this->arrayGroups;
        $value = true;
        foreach ($arrayGroups as $group) {
            $count = 0;
            foreach ($group as $arr) {
                $count = count($arr) - $count;
            }
            if ($count != 0) {
                $value = false;
            }
        }
        return $value;
    }

    /**
     * Evaluation without existecia partnerships
     * @param array $array Array which evaluates only operators having
     * @return int return values ​​of zero or one depending on the evaluation
     */
    public function evaluation($array)
    {
        foreach ($this->allOperators as $funOpe => $operators) {
            foreach ($operators as $sig) {
                $j = 0;
                if (count($array) <= 1) {
                    break;
                }
                while ((count($array) - 1) >= $j) {
                    $ele = isset($array[$j]) ? $array[$j] : '';
                    if ("$ele" == $sig && "$ele" != 'NOT') {
                        $array[$j - 1] = $this->routeFunctionOperator($funOpe, $array[$j - 1], $array[$j],
                            $array[$j + 1]);
                        unset($array[$j + 1]);
                        unset($array[$j]);
                        $j = 0;
                        $array = array_values($array);
                    } elseif ("$ele" == $sig && "$ele" == 'NOT') {
                        $array[$j] = $this->routeFunctionOperator($funOpe, $array[$j + 1], $array[$j]);
                        unset($array[$j + 1]);
                        $j = 0;
                        $array = array_values($array);
                    } else {
                        $j++;
                    }
                }
            }
        }
        return array_shift($array);
    }

    /**
     * Method to address the function to conduct the operation
     * @param string $function Name of the method to redirect
     * @param string $value1 assess value
     * @param string $operations type of operator that evaluates
     * @param string $value2 assess value
     * @return type
     */
    public function routeFunctionOperator($function, $value1, $operations, $value2 = null)
    {
        switch ($function) {
            case 'evalAritmetic':
                $result = $this->evalAritmetic($value1, $operations, $value2);
                break;
            case 'evalRelations':
                $result = $this->evalRelations($value1, $operations, $value2);
                break;
            case 'evalLogic':
                $result = $this->evalLogic($value1, $operations, $value2);
                break;
            default:
                $result = 0;
                break;
        }
        return $result;
    }

    /**
     * Method that evaluates the arithmetic part
     * @param int $value1 value
     * @param string $operator arithmetic operator
     * @param int $value2 value
     * @return int returned the result to be evaluated
     */
    public function evalAritmetic($value1, $operator, $value2)
    {
        switch ($operator) {
            case '+':
                $ret = $value1 + $value2;
                break;
            case '-':
                $ret = $value1 - $value2;
                break;
            case '/':
                if ($value2 > 0) {
                    $ret = $value1 / $value2;
                } else {
                    $ret = 0;
                }
                break;
            case 'x':
                $ret = $value1 * $value2;
                break;
            default:
                $ret = 0;
                break;
        }
        return $ret;
    }

    /**
     * Method that evaluates the logic part
     * @param boolean $value1 takes the values ​​1 or 0
     * @param string $logical if evaluate AND, OR, NOT
     * @param boolean $value2 takes the values ​​1 or 0 in the case of NOT is null
     * @return int
     */
    public function evalLogic($value1, $logical, $value2 = null)
    {
        switch ($logical) {
            case 'AND':
                if ($value1 && $value2) {
                    $ret = 1;
                } else {
                    $ret = 0;
                }
                break;
            case 'OR':
                if ($value1 || $value2) {
                    $ret = 1;
                } else {
                    $ret = 0;
                }
                break;
            case 'NOT':
                if ($value1 == 0) {
                    $ret = 1;
                } else {
                    $ret = 0;
                }
                break;
            default:
                $ret = 0;
                break;
        }
        return $ret;
    }

    /**
     * Method that takes cast depending on the type of value
     * (int), (integer) - integer
     * (bool), (boolean) - boolean
     * (float), (double), (real) - float
     * (string) - string
     * (array) - array
     * (object) - object
     * (unset) - NULL (PHP 5)
     * @param type $value
     * @param type $tipeDate
     * @return type
     */
    public function typeData($value, $typeDate)
    {

        switch (strtolower($typeDate)) {
            case 'address'://varchar
            case 'relate'://varchar
            case 'text':
            case 'url'://varchar
            case 'textfield'://varchar
            case 'name'://varchar
            case 'varchar'://varchar
            case 'parent_type'://varchar
                $newValue = (string)$value;
                break;
            case 'bool'://bool 
            case 'boolean':
            case 'radioenum':
            case 'checkbox':
                $newValue = (boolean)$value;
                break;
            case 'date'://date
            case 'datetime'://datetime
            case 'datetimecombo'://datetime
                $newValue = strtotime($value);
                break;
            case 'enum'://int
            case 'int':
                $newValue = (int)$value;
                break;
            case 'float':
                $newValue = (float)$value;
                break;
            case 'integer':
                $newValue = (integer)$value;
                break;
            case 'decimal'://decimal
                $newValue = $value; ////////////////////////////////
                break;
            case 'currency'://double
                $newValue = $value;
                break;
            case 'encrypt':
            case 'html':
            case 'iframe':
            case 'image':
            case 'multienum':
            case 'phone':
            default:
                $newValue = $value;
                break;
        }
        return $newValue;
    }

    /**
     * logical method that makes the literal symbol
     * @param type $logic logical literal
     * @return string logical symbol
     */
    public function logicSimbol($logic)
    {
        switch ($logic) {
            case 'AND':
                $ret = '&&';
                break;
            case 'OR':
                $ret = '||';
                break;
            case 'NOT':
                $ret = '!';
                break;
            default:
                $ret = 'ES';
                break;
        }
        return $ret;
    }

    /**
     * Returns a string of operations performed
     * @return string chain of operations that the evaluator
     * @codeCoverageIgnore
     */
    public function condition()
    {
        return $this->condition;
    }

}
