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


class SugarCacheMemory extends SugarCacheAbstract
{
    /**
     * @see SugarCacheAbstract::$_priority
     */
    protected $_priority = 999;
    
    /**
     * @see SugarCacheAbstract::useBackend()
     */
    public function useBackend()
    {
        // we'll always have this backend available
        return true;
    }
    
    /**
     * @see SugarCacheAbstract::_setExternal()
     *
     * Does nothing; cache is gone after request is done.
     */
    protected function _setExternal($key,$value)
    {
    }
    
    /**
     * @see SugarCacheAbstract::_getExternal()
     *
     * Does nothing; cache is gone after request is done.
     */
    protected function _getExternal($key)
    {
    }
    
    /**
     * @see SugarCacheAbstract::_clearExternal()
     *
     * Does nothing; cache is gone after request is done.
     */
    protected function _clearExternal($key)
    {
    }
    
    /**
     * @see SugarCacheAbstract::_resetExternal()
     *
     * Does nothing; cache is gone after request is done.
     */
    protected function _resetExternal()
    {
    }
}
