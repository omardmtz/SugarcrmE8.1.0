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


if(!defined('SUGAR_SMARTY_DIR'))
{
	define('SUGAR_SMARTY_DIR', sugar_cached('smarty/'));
}

/**
 * Smarty wrapper for Sugar
 * @api
 */
class Sugar_Smarty extends Smarty
{
    protected static $_plugins_dir;
    public function __construct()
	{
		if(!file_exists(SUGAR_SMARTY_DIR))mkdir_recursive(SUGAR_SMARTY_DIR, true);
		if(!file_exists(SUGAR_SMARTY_DIR . 'templates_c'))mkdir_recursive(SUGAR_SMARTY_DIR . 'templates_c', true);
		if(!file_exists(SUGAR_SMARTY_DIR . 'configs'))mkdir_recursive(SUGAR_SMARTY_DIR . 'configs', true);
		if(!file_exists(SUGAR_SMARTY_DIR . 'cache'))mkdir_recursive(SUGAR_SMARTY_DIR . 'cache', true);

		$this->template_dir = '.';
		$this->compile_dir = SUGAR_SMARTY_DIR . 'templates_c';
		$this->config_dir = SUGAR_SMARTY_DIR . 'configs';
		$this->cache_dir = SUGAR_SMARTY_DIR . 'cache';
		$this->request_use_auto_globals = true; // to disable Smarty from using long arrays
        // Smarty will create subdirectories under the compiled templates and cache directories
        $this->use_sub_dirs = true;

        if(empty(self::$_plugins_dir)) {
            self::$_plugins_dir = array();
            if (file_exists('custom/include/SugarSmarty/plugins')) {
                self::$_plugins_dir[] = 'custom/include/SugarSmarty/plugins';
            }
            if (file_exists('custom/vendor/Smarty/plugins')) {
                self::$_plugins_dir[] = 'custom/vendor/Smarty/plugins';
            }
            self::$_plugins_dir[] = 'include/SugarSmarty/plugins';
            self::$_plugins_dir[] = 'vendor/Smarty/plugins';
        }
        $this->plugins_dir = self::$_plugins_dir;

		$this->assign("VERSION_MARK", getVersionedPath(''));
	}

	/**
	 * Fetch template or custom double
	 * @see Smarty::fetch()
     * @param string $resource_name
     * @param string $cache_id
     * @param string $compile_id
     * @param boolean $display
	 */
	public function fetchCustom($resource_name, $cache_id = null, $compile_id = null, $display = false)
	{
	    return $this->fetch(SugarAutoLoader::existingCustomOne($resource_name), $cache_id, $compile_id, $display);
	}

	/**
	 * Display template or custom double
	 * @see Smarty::display()
     * @param string $resource_name
     * @param string $cache_id
     * @param string $compile_id
	 */
	function displayCustom($resource_name, $cache_id = null, $compile_id = null)
	{
	    return $this->display(SugarAutoLoader::existingCustomOne($resource_name), $cache_id, $compile_id);
	}

	/**
	 * Override default _unlink method call to fix Bug 53010
	 *
	 * @param string $resource
     * @param integer $exp_time
     */
    function _unlink($resource, $exp_time = null)
    {
        if(file_exists($resource)) {
            return parent::_unlink($resource, $exp_time);
        }

        // file wasn't found, so it must be gone.
        return true;
    }
}
