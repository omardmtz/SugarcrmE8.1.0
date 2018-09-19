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
 * Class SugarACLKB
 * Additional ACL for KB.
 */
class SugarACLKB extends SugarACLStrategy
{

    /**
     * {@inheritDoc}
     *
     * Need to override default ACL in future.
     */
    public function checkAccess($module, $view, $context)
    {
        return true;
    }
}
