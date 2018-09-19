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

namespace Sugarcrm\Sugarcrm\Denormalization\TeamSecurity\Console;

use Sugarcrm\Sugarcrm\Console\CommandRegistry\Mode\InstanceModeInterface;
use Sugarcrm\Sugarcrm\Denormalization\TeamSecurity\Command\StateAwareRebuild;
use Sugarcrm\Sugarcrm\DependencyInjection\Container;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Rebuild denormalized team security data.
 */
class RebuildCommand extends Command implements InstanceModeInterface
{
    /**
     * {inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('team-security:rebuild')
            ->setDescription('Rebuild denormalized team security data')
            ->addOption(
                'ignore-up-to-date',
                null,
                InputOption::VALUE_NONE,
                'Run even if the data is up to date'
            )
        ;
    }

    /**
     * {inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $command = Container::getInstance()->get(StateAwareRebuild::class);
        $ignoreUpToDate = $input->getOption('ignore-up-to-date');
        list($status, $message) = $command($ignoreUpToDate);
        $output->writeln($message);

        return $status ? 0 : 1;
    }
}
