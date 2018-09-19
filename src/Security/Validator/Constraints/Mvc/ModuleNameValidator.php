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

namespace Sugarcrm\Sugarcrm\Security\Validator\Constraints\Mvc;

use Sugarcrm\Sugarcrm\Security\Validator\Constraints\Bean\ModuleNameValidator as BeanModuleNameValidator;

/**
 *
 * Legacy module name validator. Note that currently any available module value
 * will return a positive result. This validator currently does not take into
 * account the module which are somehow disabled.
 *
 */
class ModuleNameValidator extends BeanModuleNameValidator
{
    /**
     * List of all available modules.
     * @var array
     */
    protected $moduleList = array();

    /**
     * List of explicit module name we allow which cannot
     * be resolved otherwise.
     *
     * TODO: Because of the lack of validation in the past we relied heavily
     * on directory and file exist checks. As we do have some registry of
     * modules (although the definition is currently a bit loose) we should
     * fix the root cause of not having all our modules in there instead of
     * relying on this explicit list.
     *
     * @var array
     */
    protected $explicitModules = array(
        'app_strings',
        'Charts', // @see BR-3616
        'ExpressionEngine', // @see BR-3617
    );

    /**
     * Ctor
     */
    public function __construct(array $moduleList = null)
    {
        $this->moduleList = $moduleList ?: $this->getModulesFromGlobals();
    }

    /**
     * Get list of modules as available in the globals
     * @return array
     */
    protected function getModulesFromGlobals()
    {
        $moduleList = isset($GLOBALS['moduleList']) ? $GLOBALS['moduleList'] : array();
        $modInvisList = isset($GLOBALS['modInvisList']) ? $GLOBALS['modInvisList'] : array();
        return array_merge($moduleList, $modInvisList);
    }

    /**
     * Check if module exists
     * @param string $value
     * @return boolean
     */
    protected function isValidModule($module)
    {
        // try beans first
        if ($this->isValidBeanModule($module)) {
            return true;
        }

        // fallback to explicit modules
        if (in_array($module, $this->explicitModules)) {
            return true;
        }

        // last resort list
        if (in_array($module, $this->moduleList)) {
            return true;
        }

        return false;
    }

    /**
     * Check if bean module exists
     * @param string $value
     * @return boolean
     */
    protected function isValidBeanModule($module)
    {
        return \BeanFactory::getBeanClass($module) ? true : false;
    }
}
