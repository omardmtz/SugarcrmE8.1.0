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
require_once 'data/SugarACLStrategy.php';

/**
 * Class SugarACLOwnerWrite
 *
 * This ACL restricts the write access to record owners and Administrators only.
 */
class SugarACLOwnerWrite extends SugarACLStrategy
{
    /**
     * {@inheritDoc}
     *
     * Only allow edit access to model owners or module administrators.
     *
     * @param string $module
     * @param string $view
     * @param array $context
     */
    public function checkAccess($module, $view, $context)
    {
        // Allow all read access.
        if (!self::isWriteOperation($view, $context)) {
            return true;
        }

        // Some contexts may not have a bean. For example, the call to /me
        // which retrieves the user's metadata checks access for each module,
        // but there is no specific bean and therefore we do not need to
        // restrict access.
        if (!array_key_exists('bean', $context)) {
            return true;
        }

        $user = $this->getCurrentUser($context);
        $bean = $context['bean'];
        return $user->isAdminForModule($module) || $bean->isOwner($user->id);
    }
}
