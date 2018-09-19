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

namespace Sugarcrm\Sugarcrm\Elasticsearch\Query\Parser;

/**
 * to parse search string into an array
 * Class SearchStringProcessor
 *
 * @package Sugarcrm\Sugarcrm\Elasticsearch\Query\Parser
 */
class SearchStringProcessor
{
    /**
     * to parse parentheses into a structured array
     * @param string $string, the string to parse
     * @return array
     */
    public static function parse($string)
    {
        if (!$string) {
            return array();
        }

        $startPosition = null;
        $current = array();
        $stack = array();

        $string .= ' ';
        $len = strlen($string);

        for ($position = 0; $position < $len; $position++) {
            switch ($string[$position]) {
                case '(':
                    $currentString = self::getSubString($string, $position, $startPosition);
                    if (!empty($currentString)) {
                        $current[] = $currentString;
                    }
                    // push current entry to the stack an begin a new entry
                    array_push($stack, $current);
                    $current = array();
                    break;

                case ')':
                    $currentString = self::getSubString($string, $position, $startPosition);
                    if (!empty($currentString)) {
                        $current[] = $currentString;
                    }
                    // save current entry
                    $temp = $current;
                    // pop from stack
                    $current = array_pop($stack);
                    // add just saved entry to current entry
                    $current[] = $temp;
                    break;
                case ' ':
                    // make each word its own token
                    $currentString = self::getSubString($string, $position, $startPosition);
                    if (!empty($currentString)) {
                        $current[] = $currentString;
                    }
                    break;
                default:
                    // remember the offset to do a string capture later
                    if ($startPosition === null) {
                        $startPosition = $position;
                    }
            }
        }

        $ret = array();
        // in case mismatch bracket, there will be some entries left in the stack
        while (!empty($stack)) {
            $ret[] = array_pop($stack);
        }

        foreach ($current as $term) {
            $ret[] = $term;
        }
        return $ret;
    }
    
    /**
     * to get substring based on the startPosition and current position
     * @param string $string, string to parse
     * @param int $position, current position
     * @param int $startPosition, start position
     * @return null|string
     */
    protected static function getSubString($string, $position, &$startPosition)
    {
        if ($startPosition !== null) {
            // extract string from string start to current position
            $buffer = substr($string, $startPosition, $position - $startPosition);
            $startPosition = null;
            return $buffer;
        }
        return null;
    }
}
