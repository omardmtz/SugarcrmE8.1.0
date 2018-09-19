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

namespace Sugarcrm\IdentityProvider\App\Command;

use Doctrine\DBAL\DBALException;
use Sugarcrm\IdentityProvider\App\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command for executing a fixtures sql query
 */
class FixturesLoadCommand extends Command implements ApplicationAwareInterface
{
    /**
     * @var Application
     */
    private $app;

    /**
     * Add configuration settings to FixturesLoad
     */
    protected function configure()
    {
        $this
            ->setName('fixtures:load')
            ->setDescription('Load all fixtures to database');
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        /* @var FormatterHelper $formatter */
        $formatter = $this->getHelper('formatter');

        $path = $this->app->getRootDir() . '/tests/behat/db/fixtures.sql';

        if (!file_exists($path)) {
            $errorMessages = [
                '',
                sprintf('The file "%s" is not found.', $path),
                '',
            ];
            $formattedBlock = $formatter->formatBlock($errorMessages, 'error');
            $output->writeln($formattedBlock);
            return;
        }

        $query = file_get_contents($path);
        try {
            $this->app->getDoctrineService()->executeQuery($query);
        } catch (DBALException $e) {
            $errorMessages = [
                '',
                '[' . get_class($e) . ']',
                $e->getMessage(),
                '',
            ];
            $formattedBlock = $formatter->formatBlock($errorMessages, 'error');
            $output->writeln($formattedBlock);
            return;
        }

        $output->writeln('done');
    }


    /**
     * {@inheritdoc}
     */
    public function setApplicationInstance(Application $app)
    {
        $this->app = $app;
    }
}
