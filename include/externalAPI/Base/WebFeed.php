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
 * External API to news feed system
 * @api
 */
interface WebFeed
{
    /**
     * @deprecated This is a depreciated method and will be removed in version 7.3.
     */
    public function getLatestUpdates($maxTime, $maxEntries);
}
