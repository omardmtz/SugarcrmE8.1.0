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
 * This class is used to forbid write access to certain module fields for
 * non-admin users.
 */
class SugarACLAdminOnlyFields extends SugarACLStrategy
{
    /**
     * @var array List of non writable fields for non-admin users.
     */
    private $nonWritableFields = array();

    /**
     * Class constructor.
     *
     * @param array $aclOptions ACL options.
     */
    public function __construct($aclOptions)
    {
        if (!empty($aclOptions['non_writable_fields'])) {
            $this->nonWritableFields = $aclOptions['non_writable_fields'];
        }
    }

    /**
     * Only allow write access to certain fields if the current user is an
     * administrator or if the fields aren't configured as non writable fields
     * in module vardefs.
     *
     * @param string $module Module name.
     * @param string $view View name.
     * @param array $context Context parameters.
     * @return bool Returns true if the current user has access to perform a
     *   certain action within the supplied context, false otherwise.
     */
    public function checkAccess($module, $view, $context)
    {
        if (!$this->isWriteOperation($view, $context)) {
            return true;
        }

        $view = $this->fixUpActionName($view);
        $user = $this->getCurrentUser($context);

        if ($view === 'field'
            && $this->isNonWriteableField($context['field'])
            && !$user->isAdminForModule($module)
        ) {
            return false;
        }

        return true;
    }

    /**
     * Check if supplied field is a non writable field.
     *
     * @param string $field Field name.
     * @return bool Returns true if supplied field is a non writable field,
     *   false otherwise.
     */
    private function isNonWriteableField($field)
    {
        return in_array($field, $this->nonWritableFields);
    }
}
