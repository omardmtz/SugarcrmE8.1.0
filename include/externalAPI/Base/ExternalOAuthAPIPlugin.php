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
 * External API based on Oauth
 * @api
 */
interface ExternalOAuthAPIPlugin {
    /**
     * Get OAuth parameters, to create OAuth client
     * @return array
     */
    public function getOauthParams();
    /**
     * Get OAuth request URL
     * @return string
     */
    public function getOauthRequestURL();
    /**
     * Get OAuth authorization URL
     * @return string
     */
    public function getOauthAuthURL();
    /**
     * Get OAuth access URL
     * @return string
     */
    public function getOauthAccessURL();
}