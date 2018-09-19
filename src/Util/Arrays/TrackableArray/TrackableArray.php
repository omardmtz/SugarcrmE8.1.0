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

namespace Sugarcrm\Sugarcrm\Util\Arrays\TrackableArray;


/**
 * Class TrackableArray
 *
 * Implements an array in which all changes can be tracked and replayed on another array.
 *
 * @package Sugarcrm\Sugarcrm\Util\Arrays\TrackableArray
 */
class TrackableArray extends \ArrayObject
{
    protected $modifiedKeys = array();

    protected $unsetKeys = array();

    protected $track = false;

    public function __construct($input = array(), $flags = 0, $ittr = "ArrayIterator")
    {
        parent::__construct($input, $flags, $ittr);
        //All multidimensional arrays need to be converted as well.
        foreach ($this as $key => $value) {
            if (is_array($value)) {
                $this->offsetSet($key, $value);
            }
        }

    }

    /**
     * {@inheritdoc} Also tracks sets to keys if tracking is currently enabled.
     * Array values are automatically converted to instances of TrackableArray
     */
    public function offsetSet($offset, $value)
    {
        if ($this->track) {
            //This is required to allow $array[] = 'val'; syntax to be tracked.
            if ($offset === null) {
                $keys = array_keys($this->getArrayCopy());
                if (empty($keys)) {
                    $offset = 0;
                } else {
                    $offset = max($keys) + 1;
                }
            }

            $this->modifiedKeys[$offset] = true;
            if (isset($this->unsetKeys[$offset])) {
                unset($this->unsetKeys[$offset]);
            }
        }
        //Multidimensional support
        if (is_array($value)) {
            if (!$this->track) {
                $value = new TrackableArray($value);
            } else {
                $tValue = new TrackableArray();
                $tValue->enableTracking($this->track);
                array_walk($value, function($val, $key) use ($tValue) {
                    $tValue->offsetSet($key, $val);
                });
                $value = $tValue;
            }

        }
        parent::offsetSet($offset, $value);
    }

    /**
     * {@inheritdoc} Also tracks unsets to keys if tracking is currently enabled.
     */
    public function offsetUnset($offset)
    {
        if ($this->track) {
            $this->unsetKeys[$offset] = true;
            if (isset($this->modifiedKeys[$offset])) {
                unset($this->modifiedKeys[$offset]);
            }
        }
        if ($this->offsetExists($offset)) {
            parent::offsetUnset($offset);
        }
    }

    /**
     * {@inheritdoc} Override of parent signature to return by reference.
     * This is done to allow multidimensional array syntax. Ex. $arr['foo']['bar'] = 'baz';
     * Multidimensional arrays created this way are automatically converted to TrackableArray instances.
     */
    public function &offsetGet($offset)
    {
        //Required for direct deep syntax. Ex. $arr = array(); $arr['a']['b']['c'] = true;
        if (!$this->offsetExists($offset)) {
            $val = new TrackableArray();
            $val->enableTracking($this->track);
            $this->offsetSet($offset, $val);
        } else {
            $val = parent::offsetGet($offset);
        }

        return $val;
    }

    /**
     * Merges the given array into the values stored in this array.
     * These changes are not tracked.
     * The merge is not recursive.
     *
     * @param array $array
     *
     * @return null;
     */

    public function populateFromArray(array $array)
    {
        $shouldTrack = $this->track;
        $this->enableTracking(false);
        foreach ($array as $key => $val) {
            $this->offsetSet($key, $val);
        }
        $this->enableTracking($shouldTrack);
    }

    /**
     * Enables or disables tracking of changes for this array. Recursively applies to multidimensional arrays.
     *
     * @param bool $track
     *
     * @return null
     */
    public function enableTracking($track = true)
    {
        $this->track = $track;
        foreach ($this as $val) {
            if ($val instanceof TrackableArray) {
                $val->enableTracking($track);
            }
        }
    }

    /**
     * Modifies an array with the changes that have been tracked by this array.
     * Applies recursively to multidimensional arrays.
     *
     * @param array $array
     */
    public function applyTrackedChangesToArray(array &$array)
    {
        //Get a list of all modified keys, including sub-arrays
        $keys = $this->getChangedKeys(false);
        foreach ($keys as $key) {
            $val = $this->offsetGet($key);
            if ($val instanceof self) {
                if (!isset($array[$key]) || !is_array($array[$key])) {
                    $array[$key] = array();
                }
                $val->applyTrackedChangesToArray($array[$key]);
            } else {
                $array[$key] = $val;
            }
        }

        foreach ($this->unsetKeys as $key => $v) {
            unset($array[$key]);
        }
    }

    /**
     * Returns the list of keys that have been modified while tracking is enabled
     *
     * @param bool $includeUnset include keys there were unset
     *
     * @return array
     */
    public function getChangedKeys($includeUnset = true)
    {
        $modifiedSubArrays = array();
        foreach ($this as $key => $val) {
            if (($val instanceof self)) {
                $subChanged = $val->getChangedKeys();
                if (!empty($subChanged)) {
                    $modifiedSubArrays[] = $key;
                }
            }
        }

        $ret = array_merge($modifiedSubArrays, array_keys($this->modifiedKeys));
        if ($includeUnset) {
            $ret = array_merge($ret, array_keys($this->unsetKeys));
        }

        return array_unique($ret);;
    }

    public function __toString()
    {
        return (string) print_r($this, true);
    }


    /**
     * Returns a copy of this object as an array.
     * Instances of TrackableArray stored will also be converted to arrays recursively.
     * @return array
     */
    public function getArrayCopy()
    {
        $ret = parent::getArrayCopy();
        foreach ($ret as $key => $value) {
            if ($value instanceof self) {
                $ret[$key] = (array)$value;
            }
        }

        return $ret;
    }
}
