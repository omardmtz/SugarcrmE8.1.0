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


class SugarCacheZend extends SugarCacheAbstract
{
    /**
     * @see SugarCacheAbstract::$_priority
     */
    protected $_priority = 910;

    /**
     * @see SugarCacheAbstract::useBackend()
     */
    public function useBackend()
    {
        if ( !parent::useBackend() )
            return false;

        if ( function_exists("zend_shm_cache_fetch")
                && empty($GLOBALS['sugar_config']['external_cache_disabled_zend']))
            return true;

        return false;
    }

    /**
     * @see SugarCacheAbstract::_setExternal()
     */
    protected function _setExternal(
        $key,
        $value
        )
    {
        zend_shm_cache_store($key,serialize($value),$this->_expireTimeout);
    }

    /**
     * @see SugarCacheAbstract::_getExternal()
     */
    protected function _getExternal(
        $key
        )
    {
        $raw_cache_value = zend_shm_cache_fetch($key);
        if($raw_cache_value === false) {
            return null;
        }
        return is_string($raw_cache_value) ?
            unserialize($raw_cache_value) :
            $raw_cache_value;
    }

    /**
     * @see SugarCacheAbstract::_clearExternal()
     */
    protected function _clearExternal(
        $key
        )
    {
        zend_shm_cache_delete($key);
    }

    /**
     * @see SugarCacheAbstract::_resetExternal()
     */
    protected function _resetExternal()
    {
        zend_shm_cache_clear();
    }
}
