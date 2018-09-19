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
interface RegistryInterface
{
    /**
     * Sets a value associated to a key into the registry
     * @param string $key The name of the key to set
     * @param mixed $value The value to associate to the key
     * @param boolean $override Flag that tells the registry whether to override an existing value
     */
    public function set($key, $value, $override = false);

    /**
     * Adds a value to a key that already exists in the registry
     * @param string $key The name of the key to set
     * @param mixed $value The value to associate to the key
     */
    public function append($key, $value);

    /**
     * Gets a value for a key from the registry. Will return the default value
     * if the key is not found.
     * @param string $key The name of the key to get the value for
     * @param mixed $default The default value to return if the key isn't found
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Checks to see if a key exists in the registry. This should handle nulls
     * values that are set into the registry as existing.
     * @param string $key The key to check existence of
     * @return boolean
     */
    public function has($key);

    /**
     * Drops a key value pair from the registry
     * @param string $key The key to drop from the registry
     * @return boolean
     */
    public function drop($key);

    /**
     * Gets changes for a key's values if there are any
     * @param string $key The key to get the changes for
     * @return array
     */
    public function getChanges($key);

    /**
     * Resets the registry. USE THIS WITH CAUTION AS IT WILL RESET THE REGISTRY
     * BACK TO ITS ORIGINAL STATE.
     */
    public function reset();
}
