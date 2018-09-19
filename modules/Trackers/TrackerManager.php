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

class TrackerManager {

private static $instance;
private static $monitor_id;
private $metadata = array();
private $monitors = array();
private $disabledMonitors = array();
private static $paused = false;

    /**
     * Constructor for TrackerManager.  Declared private for singleton pattern.
     *
     */
    private function __construct()
    {
        require('modules/Trackers/config.php');
        $this->metadata = $tracker_config;
        self::$monitor_id = create_guid();
    }

/**
 * setup
 * This is a private method used to load the configuration settings whereby
 * monitors may be disabled via the Admin settings interface
 *
 */
private function setup($skip_setup = false) {
	if(!empty($this->metadata) && empty($GLOBALS['installing']) && empty($skip_setup)) {

		$admin = Administration::getSettings('tracker');
		foreach($this->metadata as $key=>$entry) {
		   if(isset($entry['bean'])) {
		   	  if(!empty($admin->settings['tracker_'. $entry['name']])) {
		   	  	 $this->disabledMonitors[$entry['name']] = true;
		   	  }
		   }
		}
	}
}

public function setMonitorId($id) {
    self::$monitor_id = $id;
    foreach($this->monitors as $monitor) {
       $monitor->monitor_id = self::$monitor_id;
    }
}

/**
 * getMonitorId
 * Returns the monitor id associated with this TrackerManager instance
 * @returns String id value
 */
public function getMonitorId() {
    return self::$monitor_id;
}

/**
 * getInstance
 * Singleton method to return static instance of TrackerManager
 * @returns static TrackerManager instance
 */
static function getInstance($skip_setup = false){
    if (!isset(self::$instance)) {
        self::$instance = new TrackerManager();
		//Set global variable for tracker monitor instances that are disabled
        self::$instance->setup($skip_setup);
    } // if
    return self::$instance;
}

    /**
     * Singleton method to reset static instance of TrackerManager. May be used in unit tests in order
     * to make sure that current instance configuration is up to date.
     */
    public static function resetInstance()
    {
        self::$instance = null;
    }

/**
 * getMonitor
 * This method returns a Monitor instance based on the monitor name.
 * @param $name The String value of the monitor's name to retrieve
 * @return Monitor instance corresponding to name or a BlankMonitor instance if one could not be found
 */
public function getMonitor($name) {
    if (!empty($this->disabledMonitors[$name])) {
        return false;
    }

	if(isset($this->monitors[$name])) {
	   return $this->monitors[$name];
	}

	if(isset($this->metadata) && isset($this->metadata[$name])) {


       try {
            $instance = $this->_getMonitor(
                $this->metadata[$name]['name'],
                self::$monitor_id,
                $this->metadata[$name]['metadata'],
                $this->metadata[$name]['store']
            );
	       $this->monitors[$name] = $instance;
	       return $this->monitors[$name];
       } catch (Exception $ex) {
       	   $GLOBALS['log']->error($ex->getMessage());
       	   $GLOBALS['log']->error($ex->getTraceAsString());
       	   $this->monitors[$name] = new BlankMonitor();
       	   return $this->monitors[$name];
       }

    } else {
       $GLOBALS['log']->error($GLOBALS['app_strings']['ERR_MONITOR_NOT_CONFIGURED'] . "($name)");
       $this->monitors[$name] = new BlankMonitor();
       return $this->monitors[$name];
    }
}

private function _getMonitor($name = '', $monitorId = '', $metadata = '', $store = '')
{
	$class = strtolower($name . '_monitor');
	$monitor = null;
	if(SugarAutoLoader::requireWithCustom('modules/Trackers/monitor/'.$class.'.php') && class_exists($class)) {
	    $monitor = new $class($name, $monitorId, $metadata, $store);
	} else {
		$monitor = new Monitor($name, $monitorId, $metadata, $store);
	}

	$monitor->setEnabled(empty($this->disabledMonitors[$monitor->name]));
	return $monitor;
}

/**
 * save
 * This method handles saving the monitors and their metrics to the mapped Store implementations
 */
public function save() {
    if(!$this->isPaused()){
		foreach($this->monitors as $monitor) {
			if(array_key_exists('Trackable', class_implements($monitor))) {
			   $monitor->save();
		    }
    	}
    }
}

/**
 * saveMonitor
 * Saves the monitor instance and then clears it
 * If ignoreDisabled is set the ignore the fact of this monitor being disabled
 */
public function saveMonitor($monitor, $flush=true, $ignoreDisabled = false) {

	if(!$this->isPaused() && !empty($monitor)){

		if(($ignoreDisabled || $this->isMonitorEnabled($monitor)) && $monitor instanceof Trackable) {

		   $monitor->save($flush);

		   if($flush) {
			   $monitor->clear();
			   unset($this->monitors[strtolower($monitor->name)]);
		   }
	    }
	}
}

    /**
     * Check if the monitor is enabled
     *
     * @param $monitor
     * @return bool
     */
    public function isMonitorEnabled($monitor)
    {
        return empty($this->disabledMonitors[$monitor->name]);
    }

/**
 * unsetMonitor
 * Method to unset the monitor so that it will not be saved
 */
public function unsetMonitor($monitor) {
   if(!empty($monitor)) {
      unset($this->monitors[strtolower($monitor->name)]);
   }
}

/**
 * pause
 * This function is to be called by a client in order to pause tracking through the lifetime of a Request.
 * Tracking can be started again by calling unPauseTracking
 *
 * Usage: TrackerManager::getInstance()->pauseTracking();
 */
public function pause(){
	self::$paused = true;
}

/**
 * unPause
 * This function is to be called by a client in order to unPause tracking through the lifetime of a Request.
 * Tracking can be paused by calling pauseTracking
 *
 *  * Usage: TrackerManager::getInstance()->unPauseTracking();
 */
public function unPause(){
	self::$paused = false;
}


/**
 * isPaused
 * This function returns the current value of the private paused variable.
 * The result indicates whether or not the TrackerManager is paused.
 *
 * @return boolean value indicating whether or not TrackerManager instance is paused.
 */
public function isPaused() {
   return self::$paused;
}

/**
 * getDisabledMonitors
 * Returns an Array of Monitor's name(s) that hhave been set to disabled in the
 * Administration section.
 *
 * @return Array of disabled Monitor's name(s) that hhave been set to disabled in the
 * Administration section.
 */
public function getDisabledMonitors() {
	return $this->disabledMonitors;
}

/**
 * Set the disabled monitors
 *
 * @param array $disabledMonitors
 */
public function setDisabledMonitors($disabledMonitors) {
	$this->disabledMonitors = $disabledMonitors;
}

/**
 * unsetMonitors
 * Function to unset all Monitors loaded for a TrackerManager instance
 *
 */
public function unsetMonitors() {
	$mons = $this->monitors;
	foreach($mons as $key=>$m) {
		$this->unsetMonitor($m);
	}
}

}

