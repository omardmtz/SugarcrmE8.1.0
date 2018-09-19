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

namespace Sugarcrm\Sugarcrm\Console\Command\Elasticsearch;

use Sugarcrm\Sugarcrm\Console\CommandRegistry\Mode\InstanceModeInterface;
use Sugarcrm\Sugarcrm\SearchEngine\SearchEngine;
use Sugarcrm\Sugarcrm\SearchEngine\Engine\Elastic;
use Sugarcrm\Sugarcrm\SearchEngine\AdminSettings;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use RuntimeException;
use MetaDataManager;

/**
 *
 * Enable/disable module for global search
 *
 */
class ModuleCommand extends Command implements InstanceModeInterface
{
    /**
     * {inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('search:module')
            ->setDescription('Enable/disable given module for search')
            ->addArgument(
                'module',
                InputArgument::REQUIRED,
                'Module name (i.e. Accounts)',
                null
            )
            ->addArgument(
                'action',
                InputArgument::REQUIRED,
                'enable or disable',
                null
            )
        ;
    }

    /**
     * {inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $engine = SearchEngine::getInstance()->getEngine();

        if (!$engine instanceof Elastic) {
            throw new RuntimeException("Backend search engine is not Elastic");
        }

        $module = $input->getArgument('module');
        $action = $input->getArgument('action');

        if (!in_array($action, array('enable', 'disable'), true)) {
            throw new RuntimeException("Please specify a proper action: 'enable' or 'disable'");
        }

        $settings = new AdminSettings();
        list($enabled, $disabled) = $settings->getModules();

        if ($action === 'enable') {
            if (!in_array($module, $disabled)) {
                throw new RuntimeException("Module $module cannot be enabled");
            }
            $key =  array_search($module, $disabled);
            unset($disabled[$key]);
            $enabled[] = $module;
        } else {
            if (!in_array($module, $enabled)) {
                throw new RuntimeException("Module $module cannot be disabled");
            }
            $key =  array_search($module, $enabled);
            unset($enabled[$key]);
            $disabled[] = $module;
        }

        $settings->saveFTSModuleListSettings($enabled, $disabled);
        MetaDataManager::refreshSectionCache(array(MetaDataManager::MM_SERVERINFO, MetaDataManager::MM_MODULES));
    }
}
