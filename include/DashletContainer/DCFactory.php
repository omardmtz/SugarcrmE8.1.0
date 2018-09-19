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
 * The Dashlet Container Factory (DCF) provides a facility for loading the appropriate Dashlet Container.
 * It will make the decision based on what container is requested as well as system and user settings.
 * @author mitani
 * @api
 */
class DCFactory
{
	static $defaultContainer = 'DCList';

	/**
	 * Prevent Instantiation of DCFactory it should only be used statically
	 *
	 */
	private function __construct()
	{
	}

	/**
	 * This function will make the decision for which container to load.
	 *
	 * If container is not specified
	 * 1. check if user has a default container they prefer load
	 *
	 * @param string $dashletMetaDataFile - file path to the meta-data specificying the Dashlets used in this container
	 * @param string $container  - name of the Dashlet Container to use if not specified it will use the system default
	 * @static
	 * @return DashletContainer
	 */
	public static function getContainer($dashletMetaDataFile, $container = null)
	{
		if($container == null)
			$container = self::$defaultContainer;

		if(!SugarAutoLoader::requireWithCustom('include/DashletContainer/Containers/' . $container .'.php'))
		    return false;

		$class = SugarAutoLoader::customClass($container);

		if ( !class_exists($class) )
		    return false;

        return new $class($dashletMetaDataFile);
	}
}