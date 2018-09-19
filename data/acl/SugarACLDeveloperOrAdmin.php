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
 * This class is used to enforce ACLs on modules that are restricted to developer or admins only.
 */
class SugarACLDeveloperOrAdmin extends SugarACLStrategy
{
    protected $allowUserRead = false;
    protected $aclModule = '';

    public function __construct($aclOptions)
    {
        if (is_array($aclOptions)) {
            if (!empty($aclOptions['allowUserRead'])) {
                $this->allowUserRead = true;
            }
            if (!empty($aclOptions['aclModule'])) {
                $this->aclModule = $aclOptions['aclModule'];
            }
        }
    }

    /**
     * Only allow access to users with the user developer or admin setting
     * @param string $module
     * @param string $view
     * @param array $context
     * @return bool|void
     */
    public function checkAccess($module, $view, $context)
    {
        if ($view == 'team_security') {
            // Let the other modules decide
            return true;
        }

        if (!empty($this->aclModule)) {
            $module = $this->aclModule;
        }

        $currentUser = $this->getCurrentUser($context);

        if (!$currentUser) {
            return false;
        }

        if($currentUser->isAdminForModule($module) || $currentUser->isDeveloperForModule($module)) {
            return true;
        } 
        else {
            if ($this->allowUserRead && !$this->isWriteOperation($view, $context)) {
                return true;
            } 
            else {
                return false;
            }
        }
    }
}
