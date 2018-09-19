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

namespace Sugarcrm\IdentityProvider\Tests\Unit\App\Command;

use Sugarcrm\IdentityProvider\App\Command\FixturesLoadCommand;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\NullOutput;
use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Helper\HelperSet;

/**
 * Class FixturesLoadTest
 */
class FixturesLoadTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that execute fixtures command uses necessary sql file
     */
    public function testExecuteMigrationsScript()
    {
        $app = $this->getMockBuilder('Sugarcrm\IdentityProvider\App\Application')
            ->disableOriginalConstructor()
            ->setMethods(['getRootDir'])
            ->getMock();

        $app->expects($this->atLeastOnce())
            ->method('getRootDir')
            ->willReturn(__DIR__ . '/../../../../');

        // Get fixtures sql query from fixtures.sql
        $path = $app->getRootDir() . '/tests/behat/db/fixtures.sql';
        $query = file_get_contents($path);

        /** @var Connection|PHPUnit_Framework_MockObject_MockObject $dbMock*/
        $dbMock = $this->getMockBuilder('Doctrine\DBAL\Connection')
            ->setMethods(['executeQuery'])
            ->disableOriginalConstructor()
            ->getMock();

        // Expects one call of executeQuery method with sql query as param
        $dbMock->expects($this->once())
            ->method('executeQuery')
            ->with($query);

        $app['db'] = $dbMock;

        /** @var FixturesLoad $fixturesLoadMock */
        $fixturesLoad = new FixturesLoadCommand();

        $output = new NullOutput();
        $input = new ArgvInput();
        $helperSet = new HelperSet(array(
            'formatter' => new FormatterHelper(),
        ));

        $fixturesLoad->setHelperSet($helperSet);
        $fixturesLoad->setApplicationInstance($app);

        $fixturesLoad->execute($input, $output);
    }
}
