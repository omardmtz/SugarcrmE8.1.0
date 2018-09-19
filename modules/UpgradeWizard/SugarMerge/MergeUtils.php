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
/*********************************************************************************
 * $Id$
 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

/**
 * Set of utilities for merging and comparing metadata
 *
 */
class MergeUtils
{

    /**
     * Performs a 3-way merge with Old, New, and Custom metadata defs. New that has changed wins over Custom wins over Old
     * @param $oldDefs
     * @param $newDefs
     * @param $customDefs
     *
     * @return array
     */
    public static function deepMergeDef($oldDefs, $newDefs, $customDefs)
    {
        //Nothing in custom is a special case where we return only the new value
        if (empty($customDefs)) {
            return $newDefs;
        }

        // Get a unique listing of the various keys from the defs, done in two
        // steps to keep horizontal scrolling to a minimum.
        $keys = array_merge(array_keys($oldDefs), array_keys($newDefs), array_keys($customDefs));
        $keys = array_unique($keys);
        $ret = array();


        // Loop and merge each key in the set
        foreach ($keys as $key) {
            // We are going to merge custom into new into old, so set up what is
            // needed up front
            $old = isset($oldDefs[$key]) ? (array)$oldDefs[$key] : array();
            $new = isset($newDefs[$key]) ? (array)$newDefs[$key] : array();
            $cst = isset($customDefs[$key]) ? (array)$customDefs[$key] : array();

            // If there is something in custom that isn't in the old or new,
            // keep it
            if ($cst && !$new && !$old) {
                $ret[$key] = $customDefs[$key];
                continue;
            }

            // If there is something new that isn't in old or custom, make it custom
            if ($new && !$old && !$cst) {
                $ret[$key] = $newDefs[$key];
                continue;
            }

            // If an entire key was removed, remove it from custom as well
            if ($old && !$new && $cst) {
                continue;
            }

            // If we have both, merge them gracefully
            if ($old != $new) {
                //Check if the propery exists in custom, but was not modified
                if ($cst == $old) {
                    //deleted items should just be skipped if they were not modified
                    if (isset($newDefs[$key])) {
                        $ret[$key] = $newDefs[$key];
                    }
                    continue;
                }
                $isAssociative = array_values($new) !== $new;
                if (is_array($old) && is_array($new)) {
                    $del = array_udiff($old, $new, 'static::uDiffComp');
                    $add = array_udiff($new, $old, 'static::uDiffComp');
                    $cst = static::removeDeletedElements($cst, $del);
                    $cst = static::addAdditionalElements($cst, $add, !$isAssociative);
                    if (!isset($oldDefs[$key]) || !is_array($oldDefs[$key])) {
                        if (!empty($cst) && is_array($cst)) {
                            $cst = $cst[0];
                        } else {
                            //Skip empty non-array values (essentially unset)
                            continue;
                        }
                    }
                }
                $ret[$key] = $cst;
                continue;
            }

            //If we got all the way here, that means the value hasn't changed, use what is in custom
            //even if that means use nothign because custom deleted the entry
            if (isset($customDefs[$key])) {
                $ret[$key] = $customDefs[$key];
            }
        }

        return $ret;
    }

    /**
     * Comparison function used to check deep array diffs
     *
     * @param array $a Input array to check presence in second array
     * @param array $b Second array to use for checking
     *
     * @return int
     */
    public static function uDiffComp($a, $b)
    {
        $a = static::getUniqueIdForValue($a);
        $b = static::getUniqueIdForValue($b);
        if ($a < $b) {
            return -1;
        } else {
            if ($a > $b) {
                return 1;
            }
        }

        return 0;
    }

    /**
     * @param $val
     * returns a repeatable identifier for most sugar metadata values
     *
     * @return null|string
     */
    protected static function getUniqueIdForValue($val)
    {
        $id = $val;
        if (is_array($val)) {
            if (isset($val['name'])) {
                $id = $val['name'];
            } elseif (isset($val['label'])) {
                $id = $val['label'];
            } else {
                //Use a JSON version to track for identity
                $id = json_encode($val);
            }
        }

        return $id;
    }

    /**
     * Removes elements from an array of defs
     *
     * @param array $data The custom metadata to delete from
     * @param array $del The array of elements to delete
     *
     * @return array
     */
    public static function removeDeletedElements($data, $del)
    {
        $track = array();

        // Get the elements that are deleted, by name
        foreach ($del as $val) {
            $t = static::getUniqueIdForValue($val);
            if ($t) {
                $track[$t] = $t;
            }
        }

        // Now loop over the custom data and remove what's necessary
        foreach ($data as $k => $v) {
            $t = static::getUniqueIdForValue($v);
            if (isset($track[$t])) {
                unset($data[$k]);
            }
        }

        return $data;
    }

    /**
     * Adds metadata to an existing data collection
     *
     * @param array $data The custom metadata to add to
     * @param array $add The array of elements to add to the metadata
     * @param bool  $preserveOrder if true, will attempt to insert add values at the indexes found in the $add array
     *
     * @return array
     */
    public static function addAdditionalElements($data, $add, $preserveOrder = false)
    {
        // Track is used to keep track of added elements
        // Names is used to keep track of existing names in the metadata
        $values = $order = $track = $names = array();

        foreach ($data as $k => $v) {
            $t = static::getUniqueIdForValue($v);
            if ($t) {
                $names[$t] = $k;
                if ($preserveOrder) {
                    //Need a real unique ID for elements that existed multiple times in the original defs
                    //But had no name
                    while (isset($order[$t])) {
                        $t .= "_";
                    }
                    //Preserve the superset of values to build the final result
                    $order[$t] = $k;
                    $values[$t] = $v;
                }
            }
        }

        // Get the elements that are added, by name
        foreach ($add as $k => $val) {
            $t = static::getUniqueIdForValue($val);
            if ($t && !isset($names[$t])) {
                $track[$t] = $val;
                if ($preserveOrder) {
                    $order[$t] = $k;
                    $values[$t] = $val;
                }
            }
        }

        if ($preserveOrder) {
            sasort($order);
            $ret = array();
            foreach ($order as $t => $o) {
                $ret[] = $values[$t];
            }

            return $ret;
        }

        return array_merge($data, $track);
    }
}
