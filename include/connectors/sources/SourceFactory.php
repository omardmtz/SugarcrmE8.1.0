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
 * Provides a factory to loading a connector along with any key->value options to initialize on the
 * source.  The name of the class to be loaded, corresponds to the path on the file system. For example a source
 * with the name ext_soap_hoovers would be ext/soap/hoovers.php
 * @api
 */
class SourceFactory{

	/**
	 * Given a source param, load the correct source and return the object
	 * @param string $source string representing the source to load
	 * @return source
	 */
	public static function getSource($class, $call_init = true)
	{
		$dir = str_replace('_','/',$class);
		$parts = explode("/", $dir);
		$file = $parts[count($parts)-1];
		$pos = strrpos($file, '/');
		if(ConnectorFactory::load($class, 'sources')) {
			if (!class_exists($class)) {
            	return null;
            }
            try {
		        $instance = new $class();
			    if($call_init) {
			        $instance->init();
			    }
				return $instance;
			} catch(Exception $ex) {
				return null;
			}
		}

		return null;
	}

}
?>
