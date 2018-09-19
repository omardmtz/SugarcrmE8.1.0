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

class SugarACLEmails extends SugarACLStrategy
{
    /**
     * Don't allow write-access to some fields because they are set by the application.
     *
     * {@inheritdoc}
     */
    public function checkAccess($module, $view, $context)
    {
        if (!$this->isWriteOperation($view, $context)) {
            return true;
        }

        if ($view !== 'field') {
            return true;
        }

        $immutableFields = [
            'reply_to_status',
        ];

        if (in_array($context['field'], $immutableFields)) {
            return false;
        }

        return true;
    }
}
