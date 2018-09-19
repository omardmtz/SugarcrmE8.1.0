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

class StudioModuleFactory
{
	protected static $loadedMods = array();

    public static function getStudioModule($module, $seed = null)
	{
		if (!empty(self::$loadedMods[$module]))
            return self::$loadedMods[$module];

        $studioModClass = "{$module}StudioModule";
		if (file_exists("custom/modules/{$module}/{$studioModClass}.php"))
		{
			require_once "custom/modules/{$module}/{$studioModClass}.php";
			$sm = new $studioModClass($module);

		} else if (file_exists("modules/{$module}/{$studioModClass}.php"))
		{
			require_once "modules/{$module}/{$studioModClass}.php";
			$sm = new $studioModClass($module);

		}
		else 
		{
			$sm = new StudioModule($module, $seed);
		}

		if ($GLOBALS['mod_strings']) {
			self::$loadedMods[$module] = $sm;
		}

        return $sm;
	}

    /**
     * Ability to clean out the studio module cache.
     *
     * @param string $module
     * @return bool
     */
    public static function clearModuleCache($module = '') {

        if (empty($module)) {
            self::$loadedMods = array();
            return true;
        } else if(isset(self::$loadedMods[$module]))  {
            unset(self::$loadedMods[$module]);
            return true;
        }

        return false;
    }
}
