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
 * Global registry
 * @api
 */
class SugarRegistry
{
    private static $_instances = array();
    private $_data = array();

    public function __construct() {

    }

    public static function getInstance($name = 'default') {
        if (!isset(self::$_instances[$name])) {
            self::$_instances[$name] = new self();
        }
        return self::$_instances[$name];
    }

    public function __get($key) {
        return isset($this->_data[$key]) ? $this->_data[$key] : null;
    }

    public function __set($key, $value) {
        $this->_data[$key] = $value;
    }

    public function __isset($key) {
        return isset($this->_data[$key]);
    }

    public function __unset($key) {
        unset($this->_data[$key]);
    }

    public function addToGlobals() {
        foreach ($this->_data as $k => $v) {
            $GLOBALS[$k] = $v;
        }
    }
}

