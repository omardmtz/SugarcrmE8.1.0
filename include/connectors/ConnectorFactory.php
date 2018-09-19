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
 * Connector factory
 * @api
 */
class ConnectorFactory{

	static $source_map = array();

	/**
	 * Gets an instance of a connector as an object on a component
	 * @param string $source_name The connector id
	 * @param boolean $fresh Indicates whether the cache should be bypassed
	 * @return component
	 */
	public static function getInstance($source_name, $fresh = false) {
		if (empty(self::$source_map[$source_name]) || $fresh) {
			$source = SourceFactory::getSource($source_name);
			if(empty($source)) {
			    $GLOBALS['log']->fatal("Failed to load source $source_name");
			    return false;
			}
			$component = new component();
			$component->setSource($source);
			$component->init();
			self::$source_map[$source_name] = $component;
		}
		return self::$source_map[$source_name];
	}

	/**
	 * Split the class name by _ and go through the class name
	 * which represents the inheritance structure to load up all required parents.
	 * @param string $class the root class we want to load.
	 */
	public static function load($class, $type)
	{
		return self::loadClass($class, $type);
	}

	/**
	 * include a source class file.
	 * @param string $class a class file to include.
	 */
	public static function loadClass($class, $type)
	{
		$dir = str_replace('_','/',$class);
		$parts = explode("/", $dir);
		$file = "{$type}/{$dir}/".$parts[count($parts)-1] . '.php';
		if(!SugarAutoLoader::requireWithCustom("modules/Connectors/connectors/$file")) {
		    return SugarAutoLoader::requireWithCustom("connectors/$file");
		}
		return true;
	}
}
