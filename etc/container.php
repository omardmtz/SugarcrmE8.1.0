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

use Doctrine\DBAL\Connection;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

use Sugarcrm\Sugarcrm\Audit\EventRepository;
use Sugarcrm\Sugarcrm\Audit\Formatter as AuditFormatter;
use Sugarcrm\Sugarcrm\Audit\Formatter\CompositeFormatter;
use Sugarcrm\Sugarcrm\DataPrivacy\Erasure\Repository;
use Sugarcrm\Sugarcrm\Denormalization\TeamSecurity\Command\Rebuild;
use Sugarcrm\Sugarcrm\Denormalization\TeamSecurity\Command\StateAwareRebuild;
use Sugarcrm\Sugarcrm\Denormalization\TeamSecurity\Console\StatusCommand;
use Sugarcrm\Sugarcrm\Denormalization\TeamSecurity\Job\RebuildJob;
use Sugarcrm\Sugarcrm\Denormalization\TeamSecurity\Listener;
use Sugarcrm\Sugarcrm\Denormalization\TeamSecurity\Listener\Builder\StateAwareBuilder;
use Sugarcrm\Sugarcrm\Denormalization\TeamSecurity\Listener\Composite;
use Sugarcrm\Sugarcrm\Denormalization\TeamSecurity\Listener\Logger;
use Sugarcrm\Sugarcrm\Denormalization\TeamSecurity\Listener\StateAwareListener;
use Sugarcrm\Sugarcrm\Denormalization\TeamSecurity\State;
use Sugarcrm\Sugarcrm\Denormalization\TeamSecurity\State\Storage\AdminSettingsStorage;
use Sugarcrm\Sugarcrm\Logger\Factory as LoggerFactory;
use Sugarcrm\Sugarcrm\Security\Context;
use Sugarcrm\Sugarcrm\Security\Subject\Formatter as SubjectFormatter;
use Sugarcrm\Sugarcrm\Security\Subject\Formatter\BeanFormatter;
use UltraLite\Container\Container;

return new Container([
    SugarConfig::class => function () {
        return SugarConfig::getInstance();
    },
    Connection::class => function () {
        return DBManagerFactory::getConnection();
    },
    LoggerInterface::class . '-denorm' => function () {
        return LoggerFactory::getLogger('denorm');
    },
    LoggerInterface::class . '-security' => function () {
        return LoggerFactory::getLogger('security');
    },
    State::class => function (ContainerInterface $container) {
        $config = $container->get(SugarConfig::class);

        $state = new State(
            $config,
            new AdminSettingsStorage(),
            $container->get(LoggerInterface::class . '-denorm')
        );
        $config->attach($state);

        return $state;
    },
    Listener::class => function (ContainerInterface $container) {
        $state = $container->get(State::class);
        $builder = new StateAwareBuilder(
            $container->get(Connection::class),
            $state
        );

        $logger = $container->get(LoggerInterface::class . '-denorm');

        $listener = new StateAwareListener($builder, $logger);
        $state->attach($listener);

        return new Composite(
            new Logger($logger),
            $listener
        );
    },
    StateAwareRebuild::class => function (ContainerInterface $container) {
        $logger = $container->get(LoggerInterface::class . '-denorm');

        return new StateAwareRebuild(
            $container->get(State::class),
            new Rebuild(
                $container->get(Connection::class),
                $logger
            ),
            $logger
        );
    },
    StatusCommand::class => function (ContainerInterface $container) {
        return new StatusCommand(
            $container->get(State::class)
        );
    },
    RebuildJob::class => function (ContainerInterface $container) {
        return new RebuildJob(
            $container->get(StateAwareRebuild::class)
        );
    },
    Context::class => function (ContainerInterface $container) {
        return new Context(
            $container->get(LoggerInterface::class . '-security')
        );
    },
    Localization::class => function () {
        return Localization::getObject();
    },
    SubjectFormatter::class => function (ContainerInterface $container) {
        return new BeanFormatter(
            $container->get(Localization::class)
        );
    },
    EventRepository::class => function (ContainerInterface $container) {
        return new EventRepository(
            $container->get(Connection::class),
            $container->get(Context::class)
        );
    },
    Repository::class => function (ContainerInterface $container) {
        return new Repository(
            $container->get(Connection::class)
        );
    },
    AuditFormatter::class => function (ContainerInterface $container) {
        $class = \SugarAutoLoader::customClass(CompositeFormatter::class);
        return new $class(
            new \Sugarcrm\Sugarcrm\Audit\Formatter\Date(),
            new \Sugarcrm\Sugarcrm\Audit\Formatter\Enum(),
            new \Sugarcrm\Sugarcrm\Audit\Formatter\Email(),
            new \Sugarcrm\Sugarcrm\Audit\Formatter\Subject($container->get(SubjectFormatter::class))
        );
    },
]);
