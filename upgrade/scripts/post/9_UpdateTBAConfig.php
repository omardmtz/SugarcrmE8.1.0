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
 * Converts array of disabled modules to array of enabled modules.
 */
class SugarUpgradeUpdateTBAConfig extends UpgradeScript
{
    /**
     * Sorting order.
     * @var int
     */
    public $order = 9999;

    /**
     * Script updates config files.
     * @var int
     */
    public $type = self::UPGRADE_CUSTOM;

    public function run()
    {
        if (version_compare($this->from_version, '7.8.0.0RC1', '>=') &&
            version_compare($this->from_version, '7.8.0.0', '<')
        ) {
            $tbaConfigurator = $this->getTBAConfigurator();
            $actionsList = $tbaConfigurator->getListOfPublicTBAModules();
            $globalState = $tbaConfigurator->isEnabledGlobally();

            $tbaConfigurator->setGlobal(true);

            $config = $tbaConfigurator->getConfig();
            $disabledModules = !empty($config['disabled_modules']) ? $config['disabled_modules'] : [];
            $enabledModules = array_values(array_diff($actionsList, $disabledModules));

            $tbaConfigurator->setForModulesList($enabledModules, true);
            $tbaConfigurator->setForModulesList($disabledModules, false);

            $tbaConfigurator->setGlobal($globalState);

            // Important to get a fresh object after the changes.
            $configurator = new Configurator();
            // Got disabled modules, the key can be overridden in order to user `enabled_modules` only.
            $configurator->config[TeamBasedACLConfigurator::CONFIG_KEY]['disabled_modules'] = false;
            $configurator->handleOverride();
            $configurator->clearCache();
            SugarConfig::getInstance()->clearCache();
        }
        return;
    }

    /**
     * Initialize configurator.
     * @return TeamBasedACLConfigurator
     */
    public function getTBAConfigurator()
    {
        return new TeamBasedACLConfigurator();
    }
}
