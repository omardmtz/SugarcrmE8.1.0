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
 * ResourceManager.php
 * This class is responsible for resource management of SQL queries, file usage, etc.
 */
class ResourceManager {

private static $instance;
private $_observers = array();

    /**
     * The constructor; declared as private
     */
    private function __construct()
    {
    }

/**
 * getInstance
 * Singleton method to return static instance of ResourceManager
 * @return The static singleton ResourceManager instance
 */
static public function getInstance(){	
    if (!isset(self::$instance)) {
        self::$instance = new ResourceManager();
    } // if
    return self::$instance;
}

/**
 * setup
 * Handles determining the appropriate setup based on client type.
 * It will create a SoapResourceObserver instance if the $module parameter is set to 
 * 'Soap'; otherwise, it will try to create a WebResourceObserver instance.
 * @param $module The module value used to create the corresponding observer
 * @return boolean value indicating whether or not an observer was successfully setup
 */
public function setup($module) {
	//Check if config.php exists
	if(!file_exists('config.php') || empty($module)) {
	   return false;	
	}
    
    if($module == 'Soap') {
      $observer = new SoapResourceObserver('Soap');      	
    } else {
      $observer = new WebResourceObserver($module);    	
    }
    
	//Load config
	if(!empty($observer->module)) {
		$limit = 0;
		
		if(isset($GLOBALS['sugar_config']['resource_management'])) {
			   $res = $GLOBALS['sugar_config']['resource_management'];
			if(!empty($res['special_query_modules']) && 
			   in_array($observer->module, $res['special_query_modules']) &&
			   !empty($res['special_query_limit']) &&
			   is_int($res['special_query_limit']) &&
			   $res['special_query_limit'] > 0) {
			   $limit = $res['special_query_limit'];
			} else if(!empty($res['default_limit']) && is_int($res['default_limit']) && $res['default_limit'] > 0) {
			   $limit = $res['default_limit'];
			}
		} //if
		
		if($limit) {
		   
		   $db = DBManagerFactory::getInstance();			
		   $db->setQueryLimit($limit);
		   $observer->setLimit($limit);
		   $this->_observers[] = $observer;
		}
		return true;
	} 
	
	return false;
}

/**
 * notifyObservers
 * This method notifies the registered observers with the provided message.
 * @param $msg Message from language file to notify observers with
 */
public function notifyObservers($msg) {
	
	if(empty($this->_observers)) {
	   return;	
	}

    //Notify observers limit has been reached
    if(empty($GLOBALS['app_strings'])) {
       $GLOBALS['app_strings'] = return_application_language($GLOBALS['current_language']);	
    }
    $limitMsg = $GLOBALS['app_strings'][$msg];
    foreach( $this->_observers as $observer) {
	    $limit = $observer->limit;
	    $module = $observer->module;
	    eval("\$limitMsg = \"$limitMsg\";");
	    $GLOBALS['log']->fatal($limitMsg);
	    $observer->notify($limitMsg);
    }		
}


/*
 * getObservers
 * Returns the observer instances that have been setup for the ResourceManager instance
 * @return Array of ResourceObserver(s)
 */
function getObservers() {
    return $this->_observers;
}
	
}
