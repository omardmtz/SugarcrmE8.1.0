<?php

namespace Sugarcrm\IdentityProvider\Tests\Unit\Authentication;

use Sugarcrm\IdentityProvider\Authentication\Tenant;
use Sugarcrm\IdentityProvider\Srn\Srn;

class TenantTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Tenant
     */
    protected $tenant;

    protected function setUp()
    {
        $this->tenant = new Tenant();
    }

    public function testSetAndGet()
    {
        $this->tenant->setId($id = '1234567890');
        $this->tenant->setDisplayName($name = 'solarwind');
        $this->tenant->setDomainName($domain = 'solarwind.net');
        $this->tenant->setRegion($region = 'us');
        $this->tenant->setDeleted(1);

        $this->assertEquals($id, $this->tenant->getId());
        $this->assertEquals($name, $this->tenant->getDisplayName());
        $this->assertEquals($domain, $this->tenant->getDomainName());
        $this->assertEquals($region, $this->tenant->getRegion());
        $this->assertTrue($this->tenant->isDeleted());
    }

    public function testFillBySRN()
    {
        $srn = new Srn();
        $srn->setRegion('eu');
        $srn->setTenantId('1234567890');

        $this->tenant->fillFromSRN($srn);
        $this->assertEquals('eu', $this->tenant->getRegion());
        $this->assertEquals('1234567890', $this->tenant->getId());
    }
}
