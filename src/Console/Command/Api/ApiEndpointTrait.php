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

namespace Sugarcrm\Sugarcrm\Console\Command\Api;

/**
 *
 * Trait for easy \SugarApi endpoint calls from Command
 *
 * This trait can be used to easily expose existing REST API calls as a
 * command. Note that this trait makes internal calls mimicking an actual
 * REST API call. This implementation does not make actual REST API calls.
 *
 * By default the CLI framework in instance mode will initialize a system
 * user as current user. If this is not desirable, the command can override
 * current user as it sees fit.
 *
 */
trait ApiEndpointTrait
{
    /**
     * @var \SugarApi
     */
    protected $api;

    /**
     * @var \RestService
     */
    protected $service;

    /**
     * Initialize API
     * @param \SugarApi $api
     * @return ApiEndpointTrait
     */
    protected function initApi(\SugarApi $api)
    {
        $this->api = $api;
        $this->service = $this->getService();
        return $this;
    }

    /**
     * Wrapper to call a method with arguments on given SugarApi object
     * @param string $method Method to be invoked on the public API
     * @param array $args Arguments to be passed to the public API
     */
    protected function callApi($method, array $args = array())
    {
        $args = array($this->service, $args);
        return call_user_func_array(array($this->api, $method), $args);
    }

    /**
     * Get REST service backend
     * @return \RestService
     */
    protected function getService()
    {
        return new \RestService();
    }
}
