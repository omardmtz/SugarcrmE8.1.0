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

namespace Sugarcrm\Sugarcrm\Console\Command\Api;

use Sugarcrm\Sugarcrm\Console\CommandRegistry\Mode\InstanceModeInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;


/**
 *
 * SearchEngine fields
 *
 */
class SearchFieldsCommand extends Command implements InstanceModeInterface
{
    use ApiEndpointTrait;

    /**
     * {inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('search:fields')
            ->setDescription('Show search engine enabled fields')
            ->addOption(
                'modules',
                null,
                InputOption::VALUE_REQUIRED,
                'Comma separated list of modules.'
            )
            ->addOption(
                'searchOnly',
                null,
                InputOption::VALUE_NONE,
                'Show searchable fields only.'
            )
            ->addOption(
                'byBoost',
                null,
                InputOption::VALUE_NONE,
                'Order the output by boost value.'
            )
        ;
    }

    /**
     * {inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $args = array();

        if ($modules = $input->getOption('modules')) {
            $args['module_list'] = $modules;
        }

        if ($searchOnly = $input->getOption('searchOnly')) {
            $args['search_only'] = true;
        }

        if ($byBoost = $input->getOption('byBoost')) {
            $args['order_by_boost'] = true;
        }


        $result = $this
            ->initApi($this->getApi())
            ->callApi('searchFields', $args)
        ;

        // handle output which is different when ordered by boost
        $table = new Table($output);

        if ($byBoost) {
            $table->setHeaders(array('Module', 'Field', 'Boost'));
            foreach ($result as $raw => $boost) {
                $raw = explode('.', $raw);
                $table->addRow(array($raw[0], $raw[1], $boost));
            }
        } else {
            $table->setHeaders(array('Module', 'Field', 'Type', 'Searchable', 'Boost'));
            foreach ($result as $module => $fields) {
                foreach ($fields as $field => $props) {
                    $searchAble = !empty($props['searchable']) ? 'yes' : 'no';
                    $boost = isset($props['boost']) ? $props['boost'] : 'n/a';
                    $table->addRow(array($module, $field, $props['type'], $searchAble, $boost));
                }
            }
        }

        $table->render();
    }

    /**
     * @return \AdministrationApi
     */
    protected function getApi()
    {
        return new \AdministrationApi();
    }
}
