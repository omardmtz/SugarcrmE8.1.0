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

namespace Sugarcrm\IdentityProvider\Tests\Functional\SAML;

use Sugarcrm\IdentityProvider\App\Application;
use Sugarcrm\IdentityProvider\App\TenantConfiguration;

use Silex\WebTestCase;
use Symfony\Component\HttpKernel\Client;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Driver\ResultStatement;

/**
 * Initialize app for SAML web test cases
 */
abstract class AppFlowTest extends WebTestCase
{
    /**
     * Client object for WebTestCase
     * @var Client
     */
    protected $webClient;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();

        $this->webClient = $this->createClient();
        $this->webClient->followRedirects(false);
    }

    /**
     * @inheritdoc
     * @return mixed
     */
    public function createApplication()
    {
        require_once __DIR__ . '/../../../vendor/autoload.php';
        $app = new Application(
            [
                'env' => Application::ENV_TESTS,
            ]
        );
        $app->getSession()->set('tenant', 'srn:cloud:idp:eu:0000000001:tenant:0000000001');
        $tenantConfiguration = $this->getMockBuilder(TenantConfiguration::class)
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();
        $tenantConfiguration->method('get')->willReturn(['saml' => $this->getSamlParameters()]);
        $app['tenantConfiguration'] = $tenantConfiguration;

        // Set up mock of Database connection and executor.
        // You can insert any data to $dbResult if you want it to be returned by any DB fetch call.
        // Is applied throughout the whole Application, so be careful.
        $dbResult = [
            'identity_value' => 'some-test-username-from-db',
            'password_hash' => '',
            'attributes' => '',
            'custom_attributes' => '',
        ];
        $db = $this->createMock(Connection::class);
        $qb = $this->getMockBuilder(QueryBuilder::class)
            ->disableOriginalConstructor()
            ->setMethods(['execute'])
            ->getMock();
        $statement = $this->createMock(ResultStatement::class);
        $statement->method('fetch')->willReturn($dbResult);
        $qb->method('execute')->willReturn($statement);
        $db->method('createQueryBuilder')->willReturn($qb);
        $app['db'] = $db;

        return $app;
    }

    /**
     * Returns parameters for SAML service.
     *
     * @return array
     */
    abstract public function getSamlParameters();
}
