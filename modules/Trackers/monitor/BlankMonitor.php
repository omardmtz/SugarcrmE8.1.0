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

class BlankMonitor extends Monitor implements Trackable {
    
    /**
     * BlankMonitor constructor
     */
    public function __construct()
    {
    }
    
    /**
     * setValue
     * Sets the value defined in the monitor's metrics for the given name
     * @param $name String value of metric name
     * @param $value Mixed value 
     * @throws Exception Thrown if metric name is not configured for monitor instance
     */
    function setValue($name, $value) {

    }
    
    /**
     * getStores
     * Returns Array of store names defined for monitor instance
     * @return Array of store names defined for monitor instance
     */
    function getStores() {
        return null;	
    }
    
    /**
     * getMetrics
     * Returns Array of metric instances defined for monitor instance
     * @return Array of metric instances defined for monitor instance
     */
    function getMetrics() {
    	return null;
    }
    
    /**
     * save
     * This method retrieves the Store instances associated with monitor and calls
     * the flush method passing with the montior ($this) instance.
     * 
     */
    public function save($flush=true)
    {
    }


	/**
	 * getStore
	 * This method checks if the Store implementation has already been instantiated and
	 * will return the one stored; otherwise it will create the Store implementation and
	 * save it to the Array of Stores.
	 * @param $store The name of the store as defined in the 'modules/Trackers/config.php' settings
	 * @return An instance of a Store implementation
	 * @throws Exception Thrown if $store class cannot be loaded
	 */
	protected function getStore($store) {
		return null;
	}

    
	/**
	 * clear
	 * This function clears the metrics data in the monitor instance
	 */
	public function clear() {

	}
}
