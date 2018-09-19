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

namespace Sugarcrm\IdentityProvider\Tests\Unit\App\Controller;

use Sugarcrm\IdentityProvider\App\Application;
use Sugarcrm\IdentityProvider\App\Controller\SAMLController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class FailedMetadataSAMLControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SAMLController|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $controller;

    /**
     * @var Request|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $request;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $settings;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $generator;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $application;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();

        $this->controller = $this->getMockBuilder(SAMLController::class)
            ->setMethods(['getSamlSettings'])
            ->getMock();
        $this->settings = $this->getMockBuilder(\OneLogin_Saml2_Settings::class)
            ->disableOriginalConstructor()
            ->setMethods(['getSPMetadata', 'validateMetadata'])
            ->getMock();
        $this->controller->expects($this->any())
            ->method('getSamlSettings')
            ->willReturn($this->settings);

        $this->generator = $this->createMock(UrlGeneratorInterface::class);
        $this->generator->expects($this->any())
            ->method('generate')
            ->willReturn('test');

        $this->application = $this->getMockBuilder(Application::class)
            ->disableOriginalConstructor()
            ->setMethods(['getUrlGeneratorService', 'offsetGet', 'offsetExists', 'redirect'])
            ->getMock();

        $this->application->expects($this->any())
            ->method('getUrlGeneratorService')
            ->willReturn($this->generator);

        $this->application->expects($this->any())
            ->method('offsetExists')
            ->willReturn(true);

        $this->request = $this->createMock(Request::class);
    }

    public function testMetadataActionNoConfig()
    {
        $this->application->expects($this->once())
            ->method('offsetGet')
            ->with($this->equalTo('config'))
            ->willReturn([]);

        $this->application->expects($this->once())
            ->method('redirect')
            ->with($this->equalTo('test'))
            ->willReturn(true);

        $this->settings->expects($this->never())
            ->method('getSPMetadata')
            ->willReturn([]);

        $this->settings->expects($this->never())
            ->method('validateMetadata')
            ->willReturn([]);

        $this->assertTrue($this->controller->metadataAction($this->application, $this->request));
    }

    public function testMetadataActionWrongConfig()
    {
        $config = ['saml' => ['test']];
        $this->application->expects($this->exactly(2))
            ->method('offsetGet')
            ->withConsecutive(
                [$this->equalTo('config')],
                [$this->equalTo('config')]
            )
            ->willReturnOnConsecutiveCalls($config, $config);

        $this->application->expects($this->once())
            ->method('redirect')
            ->with($this->equalTo('test'))
            ->willReturn(true);

        $this->settings->expects($this->once())
            ->method('getSPMetadata')
            ->willThrowException(new \OneLogin_Saml2_Error('test'));

        $this->settings->expects($this->never())
            ->method('validateMetadata')
            ->willReturn(['test']);

        $this->assertTrue($this->controller->metadataAction($this->application, $this->request));
    }

    public function testMetadataActionValidateErrors()
    {
        $config = ['saml' => ['test']];
        $this->application->expects($this->exactly(2))
            ->method('offsetGet')
            ->withConsecutive(
                [$this->equalTo('config')],
                [$this->equalTo('config')]
            )
            ->willReturnOnConsecutiveCalls($config, $config);

        $this->application->expects($this->once())
            ->method('redirect')
            ->with($this->equalTo('test'))
            ->willReturn(true);

        $this->settings->expects($this->once())
            ->method('getSPMetadata')
            ->willReturn('test');

        $this->settings->expects($this->once())
            ->method('validateMetadata')
            ->willReturn(['test']);

        $this->assertTrue($this->controller->metadataAction($this->application, $this->request));
    }
}
