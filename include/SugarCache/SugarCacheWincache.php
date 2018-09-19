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


class SugarCacheWincache extends SugarCacheAbstract
{
    /**
     * @see SugarCacheAbstract::$_priority
     */
    protected $_priority = 930;
    
    /**
     * @see SugarCacheAbstract::useBackend()
     */
    public function useBackend()
    {
        if (!parent::useBackend()) {
            return false;
        }

        if (!empty($GLOBALS['sugar_config']['external_cache_disabled_wincache'])) {
            return false;
        }

        if (!extension_loaded('wincache')) {
            return false;
        }

        if (!ini_get('wincache.ucenabled')) {
            return false;
        }

        if (php_sapi_name() === 'cli' && !ini_get('wincache.enablecli')) {
            return false;
        }

        return true;
    }
    
    /**
     * @see SugarCacheAbstract::_setExternal()
     */
    protected function _setExternal(
        $key,
        $value
        )
    {
        wincache_ucache_set($key,$value,$this->_expireTimeout);
    }
    
    /**
     * @see SugarCacheAbstract::_getExternal()
     */
    protected function _getExternal(
        $key
        )
    {
        if ( !wincache_ucache_exists($key) ) {
            return null;
        }
        
        return wincache_ucache_get($key);
    }
    
    /**
     * @see SugarCacheAbstract::_clearExternal()
     */
    protected function _clearExternal(
        $key
        )
    {
        wincache_ucache_delete($key);
    }
    
    /**
     * @see SugarCacheAbstract::_resetExternal()
     */
    protected function _resetExternal()
    {
        wincache_ucache_clear();
    }
}
