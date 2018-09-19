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

class SugarACLDraftEmails extends SugarACLStrategy
{
    /**
     * Only the owner can perform write operations on a draft. The only exception is that a user may delete a draft
     * owned by another user. In addition, don't allow write-access to some fields if the email is a draft.
     *
     * {@inheritdoc}
     */
    public function checkAccess($module, $view, $context)
    {
        // We only care about checking access when ownership matters. If there is no bean, then there is nothing to own.
        if (!isset($context['bean'])) {
            return true;
        }

        $bean = $context['bean'];

        // Allow other strategies to determine access if the email is not a draft.
        if ($bean->state !== Email::STATE_DRAFT) {
            return true;
        }

        // Allow other strategies to determine access if it is not a write operation.
        if (!$this->isWriteOperation($view, $context)) {
            return true;
        }

        // The only write operation a non-owner can perform on a draft is to delete it.
        if (!$bean->isOwner($this->getUserID($context))) {
            return $view === 'delete';
        }

        // The rest of the checks only apply to field access.
        if ($view !== 'field') {
            return true;
        }

        // These fields cannot be changed because they are set by the application.
        $immutableFields = [
            'date_sent',
            'assigned_user_id',
            'assigned_user_name',
        ];

        if (in_array($context['field'], $immutableFields)) {
            return false;
        }

        return true;
    }
}
