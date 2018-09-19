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

trait PMSEEvalRelations
{
    /**
     * Method that evaluates the relation between two values with the given operator
     * @param string $value1 value
     * @param string $operator This value should be one in either $arrayRelationsSig or $arrayRelationsLit
     * @param string $value2 value
     * @return int
     */
    public function evalRelations($value1, $operator, $value2, $typeDate = 'typeDefault')
    {
        $arrayRelationsSig = array(
            "==",
            "!=",
            ">=",
            "<=",
            ">",
            "<",
        );
        $arrayRelationsLit = array(
            "equals",
            "not_equals",
            "major_equals_than",
            "minor_equals_than",
            "major_than",
            "minor_than",
            "starts_with",
            "ends_with",
            "contains",
            "does_not_contain",
            "changes",
            "changes_from",
            "changes_to",
        );
        $result = 0;
        if (!in_array($operator, $arrayRelationsLit)) {
            $index = array_search($operator, $arrayRelationsSig);
            if ($index === false) {
                return $result;
            }
            $operator = $arrayRelationsLit[$index];
        }
        if (isset($value1)) {
            $value1 = $this->typeData($value1, $typeDate);
        }
        if (isset($value2)) {
            $value2 = $this->typeData($value2, $typeDate);
        }
        $this->condition .= ':(' . is_array($value1) ? encodeMultienumValue($value1) : $value1 . '):';
        switch ($operator) {
            case 'equals':
                $result = $value1 == $value2 ? 1 : 0;
                break;
            case 'changes':
                $result = isset($value1) ? 1 : 0;
                break;
            case 'changes_from':
            case 'changes_to':
                $result = (isset($value1) && $value1 == $value2) ? 1 : 0;
                break;
            case 'not_equals':
                $result = $value1 != $value2 ? 1 : 0;
                break;
            case 'major_equals_than':
                $result = $value1 >= $value2 ? 1 : 0;
                break;
            case 'minor_equals_than':
                $result = $value1 <= $value2 ? 1 : 0;
                break;
            case 'major_than':
                $result = $value1 > $value2 ? 1 : 0;
                break;
            case 'minor_than':
                $result = $value1 < $value2 ? 1 : 0;
                break;
            case 'starts_with':
                $len2 = strlen($value2);
                if (strlen($value1) >= $len2) {
                    $result = 1;
                    for ($i = 0; $i < $len2; $i++) {
                        if ($value1[$i] != $value2[$i]) {
                            $result = 0;
                            break;
                        }
                    }
                }
                break;
            case 'ends_with':
                $len1 = strlen($value1);
                $len2 = strlen($value2);
                if ($len1 >= $len2) {
                    $result = 1;
                    $len1 -= $len2;
                    for ($i = 0; $i < $len2; $i++) {
                        if ($value1[$len1 + $i] != $value2[$i]) {
                            $result = 0;
                            break;
                        }
                    }
                }
                break;
            case 'contains':
                $result = strpos($value1, $value2) === false ? 0 : 1;
                break;
            case 'does_not_contain':
                $result = strpos($value1, $value2) === false ? 1 : 0;
                break;
            default:
        }
        return $result;
    }
}
