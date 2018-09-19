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
use Sugarcrm\Sugarcrm\Elasticsearch\Provider\GlobalSearch\GlobalSearch;
use Sugarcrm\Sugarcrm\SearchEngine\SearchEngine;
use Sugarcrm\Sugarcrm\SearchEngine\Engine\Elastic;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *
 * CLI Tool to run Elasticsearch explain queries on GlobalSearch provider.
 * The target audience for this command are primarily developers and
 * advanced administrator/support people to troubleshoot the relevance
 * scoring performed by the GlobalSearch provider.
 *
 * The command allows you to execute a search against one or more modules
 * (or all search enabled modules if not explicitly specified) and return
 * the results including the "explain" details from the Elasticsearch
 * backend. This information can be used to understand in depth how hits
 * are sorted based on their relevance score.
 *
 * Additionally this command allows you to execute a search as a specific
 * user by passing an optional user id. This allows to mimic any global
 * search actions users perform using the UI and to debug such results.
 *
 * The command returns raw JSON in two parts:
 * 1. The actual query which has been sent to the Elasticsearch backend
 * 2. The full response including the explain details
 *
 */
class ExplainCommand extends Command implements InstanceModeInterface
{
    /**
     * {inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('elastic:explain')
            ->setDescription(
                'Execute global search explain queries for debugging purposes. ' .
                'As this will generate a lot of output in JSON format, it is ' .
                'recommended to redirect the output to a file for later processing.'
            )
            ->addArgument(
                'query',
                InputArgument::REQUIRED,
                'Search term(s), i.e. "John Doe Google"'
            )
            ->addOption(
                'modules',
                null,
                InputOption::VALUE_REQUIRED,
                'Comma separated list of modules to search.'
            )
            ->addOption(
                'user',
                null,
                InputOption::VALUE_REQUIRED,
                'User id, defaults to system user.'
            )
            ->addOption(
                'limit',
                null,
                InputOption::VALUE_REQUIRED,
                'Maximum amount of records to return.',
                20
            )
        ;
    }

    /**
     * {inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $globalSearch = $this->getGlobalSearchProvider();

        $modules = $input->getOption('modules');
        if ($modules) {
            $modules = explode(',', $modules);
        } else {
            $modules = array();
        }

        $userId = $input->getOption('user');
        if ($userId) {
            $user = $this->getUser($userId);
        } else {
            $user = $this->getCurrentUser();
        }

        $limit = (int) $input->getOption('limit');

        $globalSearch
            ->from($modules)
            ->term($input->getArgument('query'))
            ->limit($limit)
            ->offset(0)
            ->fieldBoost(true)
            ->useHighlighter(true)
            ->setExplain(true)
            ->setUser($user)
        ;

        $resultSet = $globalSearch->search()->getResultSet();

        $status = $resultSet->getResponse()->getStatus();
        if ($status != 200) {
            throw new \Exception(sprintf(
                'Something went wrong, got status %s',
                $status
            ));
        }

        $query = $this->jsonEncode($resultSet->getQuery()->getParams());
        $result = $this->jsonEncode($resultSet->getResponse()->getData());

        $output->writeln($query);
        $output->writeln($result);
    }

    /**
     * Encode data array to json
     * @param array $data
     * @return string
     */
    protected function jsonEncode(array $data)
    {
        return json_encode($data, JSON_PRETTY_PRINT | JSON_FORCE_OBJECT);
    }

    /**
     * Get GlobalSearch provider
     * @throws \Exception
     * @return GlobalSearch
     */
    protected function getGlobalSearchProvider()
    {
        $engine = SearchEngine::getInstance()->getEngine();

        if (!$engine instanceof Elastic) {
            throw new \Exception("This command is only valid when using Elasticsearch as backend");
        }

        return $engine->getContainer()->getProvider('GlobalSearch');
    }

    /**
     * Get user bean based on id
     * @param string $id
     * @throws \Exception
     * @return \User
     */
    protected function getUser($id)
    {
        $user = \BeanFactory::getBean('Users', $id);
        if (empty($user->id)) {
            throw new \Exception("Cannot find user with id '{$id}'");
        }
        return $user;
    }

    /**
     * Get current user
     * @return \User
     */
    protected function getCurrentUser()
    {
        return $GLOBALS['current_user'];
    }
}
