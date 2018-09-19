<?php

namespace Sugarcrm\IdentityProvider\Tests\Unit\App\Repository;

use Sugarcrm\IdentityProvider\App\Repository\ConsentRepository;
use Doctrine\DBAL\Connection;

class ConsentRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $db;

    /**
     * @var ConsentRepository
     */
    protected $repository;

    protected function setUp()
    {
        $this->db = $this->createMock(Connection::class);
        $this->repository = new ConsentRepository($this->db);
    }

    public function testFindConsentByClientIdAndTenantIdExist()
    {
        $this->db->expects($this->once())
            ->method('fetchAssoc')
            ->with($this->anything(), $this->equalTo(['cid', 'tid']))
            ->willReturn(['client_id' => 'cid', 'tenant_id' => 'tid', 'scopes' => ['scope1']]);

        $consent = $this->repository->findConsentByClientIdAndTenantId('cid', 'tid');
        $this->assertEquals(['scope1'], $consent->getScopes());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testFindConsentByClientIdAndTenantIdNotExist()
    {
        $this->db->expects($this->once())
            ->method('fetchAssoc')
            ->with($this->anything(), $this->equalTo(['cid', 'tid']))
            ->willReturn(null);

        $consent = $this->repository->findConsentByClientIdAndTenantId('cid', 'tid');
        $this->assertEquals(['scope1'], $consent->getScopes());
    }
}
