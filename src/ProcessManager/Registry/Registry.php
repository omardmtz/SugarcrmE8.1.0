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

namespace Sugarcrm\Sugarcrm\ProcessManager\Registry;

/**
 * Interface that defines the methods that any registry should implement
 */
class Registry implements RegistryInterface
{
    /**
     * Singleton instance holder
     * @var Registry
     */
    private static $instance = null;

    /**
     * Registry data store
     * @var array
     */
    private $data = array();

    /**
     * List of changes for a key. This will be an array of change values containing
     * from and to values.
     * @var array
     */
    private $changes = array();

    /**
     * Private constructor to enforce singleton instantiation
     */
    final private function __construct()
    {

    }

    /**
     * Registry instance getter
     * @return Registry
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Adds a row to the change set for a key
     * @param string $key The key to set a change for
     * @param mixed $value The value to set the key to
     */
    protected function addChange($key, $value)
    {
        if ($this->has($key)) {
            $this->changes[$key][] = [
                'from' => $this->data[$key],
                'to' => $value,
            ];
        }
    }

    /**
     * @inheritDoc
     */
    public function set($key, $value, $override = false)
    {
        if (!$this->has($key)) {
            $this->data[$key] = $value;
        } else {
            if ($override) {
                $this->addChange($key, $value);
                $this->data[$key] = $value;
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function append($key, $value)
    {
        if (!$this->has($key)) {
            $this->data[$key] = $value;
        } else {
            // Get our current data value for this key
            $data = $this->data[$key];

            // If it is not an array, make it one
            if (!is_array($data)) {
                $data = [$data];
            }

            // Add the new value to it
            $data[] = $value;
            $this->addChange($key, $data);
            $this->data[$key] = $data;
        }
    }

    /**
     * @inheritDoc
     */
    public function get($key, $default = null)
    {
        if ($this->has($key)) {
            return $this->data[$key];
        }

        return $default;
    }

    /**
     * @inheritDoc
     */
    public function has($key)
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * @inheritDoc
     */
    public function drop($key)
    {
        // Add to the change log
        $this->addChange($key, null);

        // Remove it from the stack
        unset($this->data[$key]);
    }

    /**
     * @inheritDoc
     */
    public function getChanges($key)
    {
        return isset($this->changes[$key]) ? $this->changes[$key] : array();
    }

    /**
     * @inheritDoc
     */
    public function reset()
    {
        // This is a very destructive method so please use with caution
        $this->data = $this->changes = array();
    }
}
