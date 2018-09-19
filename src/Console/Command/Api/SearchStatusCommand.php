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
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;


/**
 *
 * SearchEngine status
 *
 */
class SearchStatusCommand extends Command implements InstanceModeInterface
{
    use ApiEndpointTrait;

    /**
     * {inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('search:status')
            ->setDescription('Show search engine availability and enabled modules')
        ;
    }

    /**
     * {inheritdoc}
     *
     * Return codes:
     * 0 = search available
     * 1 = search unavailable
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $result = $this
            ->initApi($this->getApi())
            ->callApi('searchStatus')
        ;

        $available = $result['available'];

        $table = new Table($output);
        $table->setHeaders(array('Enabled modules'));

        if (isset($result['enabled_modules'])) {
            foreach ($result['enabled_modules'] as $module) {
                $table->addRow(array($module));
            }
        }

        $table->render();

        if ($available) {
            $output->writeln("SearchEngine AVAILABLE");
        } else {
            $output->writeln("SearchEngine *NOT* available");
        }

        return $available ? 0 : 1;
    }

    /**
     * @return \AdministrationApi
     */
    protected function getApi()
    {
        return new \AdministrationApi();
    }
}
