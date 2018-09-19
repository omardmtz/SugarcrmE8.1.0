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

namespace Sugarcrm\IdentityProvider\App\Provider;

use Doctrine\DBAL\Migrations\Configuration\Configuration;
use Doctrine\DBAL\Migrations\OutputWriter;
use Doctrine\DBAL\Migrations\Tools\Console\Command\AbstractCommand;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Symfony\Component\Console\Helper\QuestionHelper;
use Silex\Application;
use Silex\Api\BootableProviderInterface;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

use Symfony\Component\Console\Application as Console;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * Class DoctrineMigrationsProvider
 */
class DoctrineMigrationsProvider implements ServiceProviderInterface, BootableProviderInterface
{
    /**
     * @var Console
     */
    protected $console;

    /**
     * @var string
     */
    protected $migrationsTableName = 'migration_versions';

    /**
     * @param Console $console
     */
    public function __construct(Console $console)
    {
        $this->console = $console;
    }

    /**
     * Register DoctrineMigrationsProvider service.
     * Add necessary parameters to Application instance.
     * @param Container $container An Application instance
     */
    public function register(Container $container)
    {
        // There is no special requirements to register DoctrineMigrationsProvider service.
    }

    /**
     * This method is called for all registered services which implement interface BootableProviderInterface
     * @param Application $app
     */
    public function boot(Application $app)
    {
        $helperSet = new HelperSet([
            'connection' => new ConnectionHelper($app->getDoctrineService()),
            'dialog'     => new QuestionHelper(),
        ]);

        $this->console->setHelperSet($helperSet);
        $commands = [
            'Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand',
            'Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand',
            'Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand',
            'Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand',
            'Doctrine\DBAL\Migrations\Tools\Console\Command\VersionCommand',
        ];

        $outputWriter = new OutputWriter(
            function ($message) {
                $output = new ConsoleOutput();
                $output->writeln($message);
            }
        );

        $migrationsDirectory = $app->getRootDir() . '/src/App/Migrations';

        $configuration = new Configuration($app->getDoctrineService(), $outputWriter);
        $configuration->setName('Migrations');
        $configuration->setMigrationsDirectory($migrationsDirectory);
        $configuration->setMigrationsNamespace('Sugarcrm\IdentityProvider\App\Migrations');
        $configuration->setMigrationsTableName($this->migrationsTableName);

        foreach ($commands as $name) {
            /** @var AbstractCommand $command */
            $command = new $name();
            $command->setMigrationConfiguration($configuration);
            $this->console->add($command);
        }
    }
}
