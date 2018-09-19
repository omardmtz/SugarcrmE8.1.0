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
 * This class is used to enforce ACLs on modules that are restricted to developers of a specific module
 */
class SugarACLDeveloperForTarget extends SugarACLStrategy
{

    protected $allowUserRead = false;
    protected $targetModuleField = false;

    public function __construct($aclOptions)
    {
        if (is_array($aclOptions)) {
            if (!empty($aclOptions['allowUserRead'])) {
                $this->allowUserRead = true;
            }
            if (!empty($aclOptions['targetModuleField'])) {
                $this->targetModuleField = $aclOptions['targetModuleField'];
            }
        }
    }

    /**
     * Only allow access to users with the user admin setting
     *
     * @param string $module
     * @param string $view
     * @param array  $context
     *
     * @return bool|void
     */
    public function checkAccess($module, $view, $context)
    {
        $current_user = $this->getCurrentUser($context);
        if (!$current_user) {
            return false;
        }

        if ($view == 'team_security' || $view == 'field') {
            return true;
        }

        if (empty($context['bean'])) {

            if ($current_user->isAdmin()) {
                return true;
            }

            $dev_mods = $current_user->getDeveloperModules();
            if (count($dev_mods)) {
                $sup_mods = PMSEEngineUtils::getSupportedModules();
                $valid_mods = array_intersect($dev_mods, $sup_mods);
                return !empty($valid_mods);
            }
            return false;
        }

        if (!empty($this->targetModuleField) && !empty($context['bean'])) {
            $field = $this->targetModuleField;
            $bean = $context['bean'];
            if (empty($bean->$field) || $current_user->isDeveloperForModule($bean->$field)) {
                return true;
            }
        }

        if ($this->allowUserRead && !$this->isWriteOperation($view, $context)) {
            return true;
        } else {
            return false;
        }

        return false;
    }

}
