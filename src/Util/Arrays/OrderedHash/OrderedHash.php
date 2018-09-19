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

namespace Sugarcrm\Sugarcrm\Util\Arrays\OrderedHash;

/**
 * Class OrderedHash
 * @package Sugarcrm\Sugarcrm\Util\Arrays\OrderedHash
 *
 * This class represents an associative array in which the order of pairs is important. The implementation resembles
 * that of {@see SplDoublyLinkedList} with a few exceptions, like in the arguments the `add` method accepts and the role
 * that the constructor plays. And instead of a numerical index, each element is stored under a unique key.
 */
class OrderedHash implements \Iterator, \ArrayAccess, \Countable
{
    /**
     * @var Element
     *
     * Points to the first element.
     */
    protected $head;

    /**
     * @var Element
     *
     * Points to the last element.
     */
    protected $tail;

    /**
     * @var Element
     *
     * Points to the current element while iterating.
     */
    protected $current;

    /**
     * @var array
     *
     * An unordered associative array of all of the elements currently in the hash.
     */
    protected $elements = array();

    /**
     * Produces an ordered hash from an associative array.
     *
     * @param array $hash
     */
    public function __construct($hash = array())
    {
        foreach ($hash as $key => $value) {
            $this->push($key, $value);
        }
    }

    /**
     * Inserts a new element into the hash following the specified element.
     *
     * @param null|Element $before The element that precedes the new element. The element is inserted at the beginning
     * when this parameter is NULL.
     * @param string|int $key The new element's key.
     * @param mixed $value The new element's value.
     * @throws \OutOfRangeException Thrown when the key is not a string or integer.
     * @throws \RuntimeException Thrown when the key is not unique.
     */
    public function add($before, $key, $value)
    {
        if (!(is_string($key) || is_int($key))) {
            throw new \OutOfRangeException('Offset invalid or out of range');
        }

        if ($this->offsetExists($key)) {
            throw new \RuntimeException("$key must be unique");
        }

        $after = null;
        $element = new Element($key, $value);
        $element->setBefore($before);

        if (is_null($before)) {
            // insert at the head
            $after = $this->head;
            $element->setAfter($after);
            $this->head = $element;
        } else {
            // insert between an element and the element that follows it
            $after = $before->getAfter();
            $before->setAfter($element);
            $element->setAfter($after);
        }

        if (is_null($after)) {
            // it was inserted at the tail
            $this->tail = $element;
        } else {
            $after->setBefore($element);
        }

        $this->elements[$key] = $element;
    }

    /**
     * Returns the first element in the hash.
     *
     * @return Element
     * @throws \RuntimeException Thrown when the hash is empty.
     */
    public function bottom()
    {
        if ($this->isEmpty()) {
            throw new \RuntimeException(sprintf('%s is empty', __CLASS__));
        }

        return $this->head;
    }

    /**
     * Returns the number of elements in the hash.
     *
     * @return int
     */
    public function count()
    {
        return count($this->elements);
    }

    /**
     * Returns the element currently pointed to by the iterator.
     *
     * @return Element
     */
    public function current()
    {
        return $this->current;
    }

    /**
     * Sets the iterator to the end of the hash.
     */
    public function fastForward()
    {
        $this->current = $this->top();
    }

    /**
     * Checks whether or not the hash is empty.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return ($this->count() === 0);
    }

    /**
     * Returns the key of the current element.
     *
     * @return null|string|int
     */
    public function key()
    {
        $current = $this->current();

        return is_null($current) ? null : $current->getKey();
    }

    /**
     * Locates the element in the hash by the key provided and moves the element to follow the element specified.
     *
     * @param string|int $key The key of the element to move.
     * @param null|Element $before The element that should precede the element being moved. The element is moved to the
     * beginning when this parameter is NULL.
     */
    public function move($key, $before)
    {
        $element = $this->offsetGet($key);

        if ($element) {
            $this->offsetUnset($key);

            if ($before) {
                $before = $this->offsetGet($before->getKey());
                $this->add($before, $element->getKey(), $element->getValue());
            } else {
                $this->unshift($element->getKey(), $element->getValue());
            }
        }
    }

    /**
     * Moves the iterator to the next element.
     */
    public function next()
    {
        $current = $this->current();

        if ($current) {
            $this->current = $current->getAfter();
        }
    }

    /**
     * Returns whether the requested key exists.
     *
     * @param string|int $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return array_key_exists($key, $this->elements);
    }

    /**
     * Returns the element that is identified by the key provided.
     *
     * @param string|int $key
     * @return null|Element NULL is returned when the element isn't found.
     * @throws \OutOfRangeException Thrown when the key is not a string or integer.
     */
    public function offsetGet($key)
    {
        if (!(is_string($key) || is_int($key))) {
            throw new \OutOfRangeException('Offset invalid or out of range');
        }

        return $this->offsetExists($key) ? $this->elements[$key] : null;
    }

    /**
     * Sets the value for the key provided.
     *
     * A new element is appended if the key does not already exist in the hash.
     *
     * @param string|int $key
     * @param mixed $value
     * @throws \OutOfRangeException Thrown when the key is not a string or integer.
     */
    public function offsetSet($key, $value)
    {
        if (!(is_string($key) || is_int($key))) {
            throw new \OutOfRangeException('Offset invalid or out of range');
        }

        if ($this->offsetExists($key)) {
            $this->elements[$key]->setValue($value);
        } else {
            $this->push($key, $value);
        }
    }

    /**
     * Removes the element with the specified key.
     *
     * The iterator is moved to the next element if the iterator is currently pointing at the removed element.
     *
     * @uses OrderedHash::offsetGet to retrieve the element to remove.
     * @param string|int $key
     */
    public function offsetUnset($key)
    {
        $element = $this->offsetGet($key);

        if (!is_null($element)) {
            $after = $element->getAfter();

            if ($this->bottom() !== $element) {
                $element->getBefore()->setAfter($after);
            }

            if ($this->top() !== $element) {
                $after->setBefore($element->getBefore());
            }

            if ($this->bottom() === $element) {
                $this->head = $after;
            }

            if ($this->top() === $element) {
                $this->tail = $element->getBefore();
            }

            if ($this->current() === $element) {
                $this->current = $after;
            }

            unset($this->elements[$key]);
        }
    }

    /**
     * Removes and returns the last element.
     *
     * @return null|Element NULL is returned when the hash is empty.
     * @throws \RuntimeException Thrown when the hash is empty.
     */
    public function pop()
    {
        if ($this->isEmpty()) {
            throw new \RuntimeException(sprintf('%s is empty', __CLASS__));
        }

        $tail = $this->top();
        $this->offsetUnset($tail->getKey());

        return $tail;
    }

    /**
     * Moves the iterator to the previous element.
     */
    public function prev()
    {
        $current = $this->current();

        if ($current) {
            $this->current = $current->getBefore();
        }
    }

    /**
     * Adds a new element to the end of the hash.
     *
     * @param string|int $key The new element's key.
     * @param mixed $value The new element's value.
     */
    public function push($key, $value)
    {
        $this->add($this->tail, $key, $value);
    }

    /**
     * Sets the iterator to the beginning of the hash.
     */
    public function rewind()
    {
        $this->current = $this->bottom();
    }

    /**
     * Removes and returns the first element.
     *
     * @return null|Element NULL is returned when the hash is empty.
     * @throws \RuntimeException Thrown when the hash is empty.
     */
    public function shift()
    {
        if ($this->isEmpty()) {
            throw new \RuntimeException(sprintf('%s is empty', __CLASS__));
        }

        $head = $this->bottom();
        $this->offsetUnset($head->getKey());

        return $head;
    }

    /**
     * Returns the list as an associative array.
     *
     * @return array
     */
    public function toArray()
    {
        if ($this->isEmpty()) {
            return array();
        }

        $imploded = array();
        $current = $this->bottom();

        while ($current) {
            $imploded[$current->getKey()] = $current->getValue();
            $current = $current->getAfter();
        }

        return $imploded;
    }

    /**
     * Returns the last element in the hash.
     *
     * @return Element
     * @throws \RuntimeException Thrown when the hash is empty.
     */
    public function top()
    {
        if ($this->isEmpty()) {
            throw new \RuntimeException(sprintf('%s is empty', __CLASS__));
        }

        return $this->tail;
    }

    /**
     * Adds a new element to the beginning of the hash.
     *
     * @param string|int $key The new element's key.
     * @param mixed $value The new element's value.
     */
    public function unshift($key, $value)
    {
        $this->add(null, $key, $value);
    }

    /**
     * Checks whether or not there are any more elements while iterating.
     *
     * @return bool
     */
    public function valid()
    {
        return !is_null($this->current());
    }
}
