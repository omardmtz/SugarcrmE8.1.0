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

use Sugarcrm\Sugarcrm\ProcessManager;


class PMSELogicHook
{
    function after_save($bean, $event, $arguments)
    {
        if (!$this->isSugarInstalled()) {
            return true;
        }

        if (!PMSEEngineUtils::hasActiveProcesses($bean)) {
            return true;
        }
        //Define PA Hook Handler
        $handler = ProcessManager\Factory::getPMSEObject('PMSEHookHandler');
        return $handler->runStartEventAfterSave($bean, $event, $arguments);
    }

    function after_delete($bean, $event, $arguments)
    {
        if (!$this->isSugarInstalled()) {
            return true;
        }

        if (!PMSEEngineUtils::hasActiveProcesses($bean)) {
            return true;
        }
        //Define PA Hook Handler
        $handler = ProcessManager\Factory::getPMSEObject('PMSEHookHandler');
        return $handler->terminateCaseAfterDelete($bean, $event, $arguments);
    }

    /**
     * Checks to see if Sugar is installed. Returns false when Sugar is in the process
     * of installation
     * @return boolean
     */
    protected function isSugarInstalled()
    {
        global $sugar_config;

        // During installation, the `installing` variable is set, so if this is
        // not empty, then we are in the middle of installation, or not installed
        if (!empty($GLOBALS['installing'])) {
            return false;
        }

        // When installed, sugar sets `installer_locked` in the config to true,
        // so if `installer_locked` is not empty then we are installed
        return !empty($sugar_config['installer_locked']);
    }
}
