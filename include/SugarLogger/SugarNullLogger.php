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
 * Null logger, used for slim entry points that run from preDispatch.php
 * @api
 */
class SugarNullLogger
{
    /**
     * Overloaded method that ignores the log request
     *
     * @param string $method
     * @param string $message
     */
    public function __call($method, $message)
    {
    }
}

