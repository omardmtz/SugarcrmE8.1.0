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
 * APCu cache backend
 */
class SugarCacheApcu extends SugarCacheAbstract
{
    /**
     * {@inheritDoc}
     */
    protected $_priority = 935;

    /**
     * {@inheritDoc}
     */
    public function useBackend()
    {
        global $sugar_config;

        if (!parent::useBackend()) {
            return false;
        }

        if (!empty($sugar_config['external_cache_disabled_apcu'])) {
            return false;
        }

        if (!extension_loaded('apcu')) {
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
     * {@inheritDoc}
     */
    protected function _setExternal($key, $value)
    {
        apcu_store($key, $value, $this->_expireTimeout);
    }

    /**
     * {@inheritDoc}
     */
    protected function _getExternal($key)
    {
        $value = apcu_fetch($key, $success);

        if (!$success) {
            return null;
        }

        return $value;
    }

    /**
     * {@inheritDoc}
     */
    protected function _clearExternal($key)
    {
        apcu_delete($key);
    }

    /**
     * {@inheritDoc}
     */
    protected function _resetExternal()
    {
        apcu_clear_cache();
    }
}
