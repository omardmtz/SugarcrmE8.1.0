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
 * Config manager
 * @api
 */
class SugarConfig implements SplSubject
{
    public $_cached_values = array();

    /**
     * Observers of the configuration changes
     *
     * @var SplObjectStorage|SplObserver[]
     */
    private $observers;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->observers = new SplObjectStorage();
    }

    static function getInstance() {
        static $instance = null;
        if (is_null($instance)) {
            $instance = new SugarConfig();
        }
        return $instance;
    }

    function get($key, $default = null) {
        if (!isset($this->_cached_values[$key])) {
            $this->_cached_values[$key] = isset($GLOBALS['sugar_config']) ?
                SugarArray::staticGet($GLOBALS['sugar_config'], $key, $default) :
                $default;
        }
        return $this->_cached_values[$key];
    }

    function clearCache($key = null) {
        if (is_null($key)) {
            $this->_cached_values = array();
        } else {
            unset($this->_cached_values[$key]);
        }

        $this->notify();
    }

    /**
     * {@inheritDoc}
     */
    public function attach(SplObserver $observer)
    {
        $this->observers->attach($observer);
    }

    /**
     * {@inheritDoc}
     */
    public function detach(SplObserver $observer)
    {
        $this->observers->detach($observer);
    }

    /**
     * {@inheritDoc}
     */
    public function notify()
    {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }
}
