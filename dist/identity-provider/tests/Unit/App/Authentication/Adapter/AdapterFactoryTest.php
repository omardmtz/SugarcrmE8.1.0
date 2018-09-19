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

namespace Sugarcrm\IdentityProvider\Tests\Unit\App\Authentication\Adapter;

use Sugarcrm\IdentityProvider\App\Authentication\Adapter\ConfigAdapterFactory;
use Sugarcrm\IdentityProvider\App\Authentication\Adapter\SamlAdapter;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ConfigAdapterFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UrlGeneratorInterface
     */
    protected $urlGenerator;

    /**
     * @var ConfigAdapterFactory
     */
    protected $configAdapterFactory;

    protected function setUp()
    {
        $this->urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $this->configAdapterFactory = new ConfigAdapterFactory($this->urlGenerator);
    }

    public function testGetAdapter()
    {
        $adapter = $this->configAdapterFactory->getAdapter('saml');
        $this->assertInstanceOf(SamlAdapter::class, $adapter);
    }

    public function testGetAdapterNotExists()
    {
        $adapter = $this->configAdapterFactory->getAdapter('NotExists');
        $this->assertNull($adapter);
    }
}
