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
 * @deprecated Use SugarCacheApcu instead
 */
class SugarCacheAPC extends SugarCacheAbstract
{
    /**
     * @see SugarCacheAbstract::$_priority
     */
    protected $_priority = 940;

    /**
     * @see SugarCacheAbstract::useBackend()
     */
    public function useBackend()
    {
        if (!parent::useBackend()) {
            return false;
        }

        if (!empty($GLOBALS['sugar_config']['external_cache_disabled_apc'])) {
            return false;
        }

        if (!extension_loaded('apc')) {
            return false;
        }

        if (!ini_get('apc.enabled')) {
            return false;
        }

        if (php_sapi_name() === 'cli' && !ini_get('apc.enable_cli')) {
            return false;
        }

        return true;
    }

    /**
     * @see SugarCacheAbstract::_setExternal()
     */
    protected function _setExternal($key,$value)
    {
        apc_store($key,$value,$this->_expireTimeout);
    }

    /**
     * @see SugarCacheAbstract::_getExternal()
     */
    protected function _getExternal($key)
    {
        $res = apc_fetch($key);
        if($res === false) {
            return null;
        }

        return $res;
    }

    /**
     * @see SugarCacheAbstract::_clearExternal()
     */
    protected function _clearExternal($key)
    {
        apc_delete($key);
    }

    /**
     * @see SugarCacheAbstract::_resetExternal()
     */
    protected function _resetExternal()
    {
        apc_clear_cache('user');
    }
}
