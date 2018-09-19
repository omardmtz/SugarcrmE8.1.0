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
 * Interface for login behavior
 */
interface LoginInterface
{
    /**
     * Decides how to authenticate User when he/she logs-in.
     *
     * @param string $username
     * @param string $password
     * @param bool $fallback
     * @param array $params
     *
     * @return boolean
     */
    public function loginAuthenticate($username, $password, $fallback = false, array $params = []);
}
