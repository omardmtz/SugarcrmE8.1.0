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
 * Interface for external login behavior
 */
interface ExternalLoginInterface extends LoginInterface
{
    /**
     * Get URL to follow to get logged in
     *
     * @param array $returnQueryVars Query variables that should be added to the callback URL
     * @return string
     */
    public function getLoginUrl($returnQueryVars = array());
    /**
     * Get URL to follow to get logged out
     * @return string
     */
    public function getLogoutUrl();
}
