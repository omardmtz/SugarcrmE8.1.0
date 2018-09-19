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
 * External API interface
 * @api
 */
interface ExternalAPIPlugin {
    /**
     * Check if this API supports certain authentication method
     * If $method is empty, return the list of supported methods
     * @param string $method
	 * @return array|bool
     */
    public function supports($method = '');
    /**
     * Load data from EAPM bean
     * @param EAPM $eapmBean
     */
    public function loadEAPM($eapmBean);
    /**
     * Check if the data from the bean are good for login
     * @param EAPM $eapmBean
     * @return bool
     */
    public function checkLogin($eapmBean = null);
    /**
     * Log out from the service
     */
    public function logOff();
}