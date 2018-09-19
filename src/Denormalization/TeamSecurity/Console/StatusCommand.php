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
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Sugarcrm\Sugarcrm\Denormalization\TeamSecurity\State;
use Sugarcrm\Sugarcrm\DependencyInjection\Container;

/**
 * Display the status of the denormalized data.
 */
class StatusCommand extends Command implements InstanceModeInterface
{
    /**
     * {inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('team-security:status')
            ->setDescription('Display the status of the denormalized data');
    }

    /**
     * {inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $state = Container::getInstance()->get(State::class);

        $table = new Table($output);
        $table->setHeaders(['Parameter', 'Value']);
        $table->addRows([
            [
                'Enabled',
                $this->printBoolean($state->isEnabled()),
            ],
            [
                'Active table',
                $this->printNullable($state->getActiveTable()),
            ],
            [
                'Up to date',
                $this->printBoolean($state->isUpToDate()),
            ],
            [
                'Rebuild is running',
                $this->printBoolean($state->isRebuildRunning()),
            ],
        ]);

        $table->render();
    }

    /**
     * Print boolean value
     *
     * @param bool $value
     * @return string
     */
    private function printBoolean($value)
    {
        return $value ? 'Yes' : 'No';
    }

    /**
     * Print nullable value
     *
     * @param mixed $value
     * @return string
     */
    private function printNullable($value)
    {
        if ($value === null) {
            return 'None';
        }

        return $value;
    }
}
