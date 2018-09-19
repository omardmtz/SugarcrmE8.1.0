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


class SugarCachesMash extends SugarCacheAbstract
{
    /**
     * @see SugarCacheAbstract::$_priority
     */
    protected $_priority = 950;
    
    /**
     * @see SugarCacheAbstract::useBackend()
     */
    public function useBackend()
    {
        if ( !parent::useBackend() )
            return false;
        
        if ( function_exists("zget")
                && empty($GLOBALS['sugar_config']['external_cache_disabled_smash']))
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
        zput('/tmp/'.$this->_keyPrefix.'/'.$key, $value, $this->_expireTimeout);
    }
    
    /**
     * @see SugarCacheAbstract::_getExternal()
     */
    protected function _getExternal(
        $key
        )
    {
        return zget('/tmp/'.$this->_keyPrefix.'/'.$key,null);
    }
    
    /**
     * @see SugarCacheAbstract::_clearExternal()
     */
    protected function _clearExternal(
        $key
        )
    {
        zdelete('/tmp/'.$this->_keyPrefix.'/'.$key);
    }
    
    /**
     * @see SugarCacheAbstract::_resetExternal()
     */
    protected function _resetExternal()
    {
        zdelete('/tmp/'.$this->_keyPrefix.'/');
    }
}
