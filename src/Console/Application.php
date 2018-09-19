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

namespace Sugarcrm\Sugarcrm\Console;

use Sugarcrm\Sugarcrm\Console\Command\Elasticsearch\ExplainCommand;
use Sugarcrm\Sugarcrm\Console\Command\Password\PasswordConfigCommand;
use Sugarcrm\Sugarcrm\Console\Command\Password\PasswordResetCommand;
use Sugarcrm\Sugarcrm\Console\Command\Password\WeakHashesCommand;
use Sugarcrm\Sugarcrm\Console\CommandRegistry\CommandRegistry;
use Sugarcrm\Sugarcrm\Console\Command\Api\ElasticsearchIndicesCommand;
use Sugarcrm\Sugarcrm\Console\Command\Api\ElasticsearchQueueCommand;
use Sugarcrm\Sugarcrm\Console\Command\Api\ElasticsearchRoutingCommand;
use Sugarcrm\Sugarcrm\Console\Command\Api\ElasticsearchRefreshStatusCommand;
use Sugarcrm\Sugarcrm\Console\Command\Api\ElasticsearchRefreshEnableCommand;
use Sugarcrm\Sugarcrm\Console\Command\Api\ElasticsearchRefreshTriggerCommand;
use Sugarcrm\Sugarcrm\Console\Command\Api\ElasticsearchReplicasStatusCommand;
use Sugarcrm\Sugarcrm\Console\Command\Api\ElasticsearchReplicasEnableCommand;
use Sugarcrm\Sugarcrm\Console\Command\Api\SearchFieldsCommand;
use Sugarcrm\Sugarcrm\Console\Command\Api\SearchReindexCommand;
use Sugarcrm\Sugarcrm\Console\Command\Api\SearchStatusCommand;
use Sugarcrm\Sugarcrm\Console\Command\Elasticsearch\CleanupQueueCommand;
use Sugarcrm\Sugarcrm\Console\Command\Elasticsearch\ModuleCommand;
use Sugarcrm\Sugarcrm\Console\Command\Elasticsearch\SilentReindexCommand;
use Sugarcrm\Sugarcrm\Denormalization\TeamSecurity\Console\RebuildCommand;
use Sugarcrm\Sugarcrm\Denormalization\TeamSecurity\Console\StatusCommand;
use Sugarcrm\Sugarcrm\DependencyInjection\Container;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

/**
 *
 * Console application invoked using `bin/sugarcrm`
 *
 * The command line framework is primarily aimed at exposing administrative and
 * developer functionality to the platform on an installed SugarCRM instance.
 * However this framework also supports command execution without having an
 * installed SugarCRM instance. See the CommandRegistry for more details on the
 * different modes and interfaces which can be used.
 *
 * Do not extend existing stock commands if you want to customize a command.
 * The stock commands may be rearranged at any given time. It is also advised
 * to keep the amount of logic to the bare minimum and put your logic into a
 * different place. This will make your command more portable. Commands are
 * meant as CLI wrappers around existing functionality.
 *
 */
class Application extends BaseApplication
{
    /**
     * Execution mode
     * @see CommandRegistry
     * @var string
     */
    protected $mode = '';

    /**
     * Ctor
     */
    public function __construct()
    {
        parent::__construct('SugarCRM Console', $this->getSugarVersion());
    }

    /**
     * Factory to create core console application
     * @param string $mode
     * @return Application
     */
    public static function create($mode)
    {
        $container = Container::getInstance();

        $registry = CommandRegistry::getInstance();

        $registry->addCommands(array(
            // Elasticsearch specific
            new ElasticsearchIndicesCommand(),
            new ElasticsearchQueueCommand(),
            new ElasticsearchRoutingCommand(),
            new ExplainCommand(),
            new ElasticsearchRefreshStatusCommand(),
            new ElasticsearchRefreshEnableCommand(),
            new ElasticsearchRefreshTriggerCommand(),
            new ElasticsearchReplicasStatusCommand(),
            new ElasticsearchReplicasEnableCommand(),
            new CleanupQueueCommand(),
            new ModuleCommand(),
            new SilentReindexCommand(),

            // Genreric Search
            new SearchFieldsCommand(),
            new SearchReindexCommand(),
            new SearchStatusCommand(),

            // Password management
            new WeakHashesCommand(),
            new PasswordConfigCommand(),
            new PasswordResetCommand(),

            //Team Security
            new RebuildCommand(),
            new StatusCommand(),
        ));

        $app = new Application();
        $app->setMode($mode);
        $app->addCommands($registry->getCommands($mode));

        return $app;
    }

    /**
     * Set execution mode
     * @param string $mode
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
    }

    /**
     * Get execution mode
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultInputDefinition()
    {
        $definition = parent::getDefaultInputDefinition();

        // add --profile option
        $definition->addOption(new InputOption(
            '--profile',
            null,
            InputOption::VALUE_NONE,
            'Display timing and memory usage information'
        ));

        return $definition;
    }

    /**
     * {@inheritDoc}
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        if ($input->hasParameterOption('--profile')) {
            $startTime = microtime(true);
        }

        $result = parent::doRun($input, $output);

        if (isset($startTime)) {
            $output->writeln(sprintf(
                PHP_EOL . 'Memory usage: %s MB (peak: %s MB), time: %ss',
                round(memory_get_usage() / 1024 / 1024, 2),
                round(memory_get_peak_usage() / 1024 / 1024, 2),
                round(microtime(true) - $startTime, 3)
            ));
        }

        return $result;
    }

    /**
     * Get sugar version
     * @return string
     */
    protected function getSugarVersion()
    {
        $default = "[standalone mode]";
        $sugarVersionFile = SUGAR_BASE_DIR . '/sugar_version.php';
        if (file_exists($sugarVersionFile)) {
            include $sugarVersionFile;

            // sanity checks returning default
            if (empty($sugar_version) ||
                empty($sugar_flavor)  ||
                empty($sugar_build)   ||
                strpos($sugar_version, '8.0.0') === 0
            ) {
                return $default;
            }

            return "{$sugar_version}-{$sugar_flavor}-{$sugar_build}";
        }
        return $default;
    }
}
