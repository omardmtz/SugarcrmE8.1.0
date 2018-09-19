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

class SugarACLOutboundEmail extends SugarACLStrategy
{
    /**
     * {@inheritdoc}
     */
    public function checkAccess($module, $view, $context)
    {
        if (in_array($view, ['access', 'team_security'])) {
            return true;
        }

        $currentUser = $this->getCurrentUser($context);

        if (!$currentUser) {
            return false;
        }

        // Normal users cannot create new user records unless the admin allows them.
        if ($view === 'create') {
            return $this->isUserAllowedToConfigureEmailAccounts($currentUser);
        }

        if (!isset($context['bean'])) {
            return true;
        }

        $bean = $context['bean'];
        $systemIsAllowed = $bean->isAllowUserAccessToSystemDefaultOutbound();

        // The system-override record is not accessible when the admin has allowed the system record to be used.
        if ($systemIsAllowed && $bean->type === OutboundEmail::TYPE_SYSTEM_OVERRIDE) {
            return false;
        }

        if ($view === 'field') {
            if ($this->isWriteOperation($view, $context)) {
                // Only the owner has write permission.
                if (!$bean->isOwner($currentUser->id)) {
                    return false;
                }

                switch ($bean->type) {
                    case OutboundEmail::TYPE_SYSTEM:
                        // The name cannot be changed.
                        return $context['field'] !== 'name';
                    case OutboundEmail::TYPE_SYSTEM_OVERRIDE:
                        // Only the username and password can be changed.
                        return in_array($context['field'], ['id', 'mail_smtpuser', 'mail_smtppass']);
                    default:
                        // Anything can change for user records.
                        return true;
                }
            }

            return true;
        }

        // The system and system-override records cannot be deleted.
        $systemTypes = [
            OutboundEmail::TYPE_SYSTEM,
            OutboundEmail::TYPE_SYSTEM_OVERRIDE,
        ];

        if ($view === 'delete' && in_array($bean->type, $systemTypes)) {
            return false;
        }

        // The owner has full permissions.
        if ($bean->isOwner($currentUser->id)) {
            return true;
        }

        // Only the owner has write permission.
        if ($this->isWriteOperation($view, $context)) {
            return false;
        }

        // Anyone can see the system record as long as the admin has allowed it to be used.
        if ($bean->type === OutboundEmail::TYPE_SYSTEM && $systemIsAllowed) {
            return true;
        }

        // No one can see a non-system record they don't own.
        return false;
    }

    /**
     * Determines if the user is allowed to create user email accounts.
     *
     * @param User $user
     * @return bool
     */
    protected function isUserAllowedToConfigureEmailAccounts(User $user)
    {
        $oe = BeanFactory::newBean('OutboundEmail');

        return $oe->isUserAllowedToConfigureEmailAccounts($user);
    }
}
