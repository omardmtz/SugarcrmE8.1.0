<?php

namespace Sugarcrm\IdentityProvider\Tests\Unit\Authentication;

use Sugarcrm\IdentityProvider\Authentication\Consent;

class ConsentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Consent
     */
    protected $consent;

    protected function setUp()
    {
        $this->consent = new Consent();
    }

    public function testSetAndGetClientIdAndTenantId ()
    {
        $this->consent->setClientId('client');
        $this->assertEquals('client', $this->consent->getClientId());

        $this->consent->setTenantId('tenant');
        $this->assertEquals('tenant', $this->consent->getTenantId());
    }

    public function providerTestSetAndGetScope()
    {
        return [
            [json_encode(['json1', 'json2'], JSON_UNESCAPED_SLASHES), ['json1', 'json2']],
            [['array1', 'array2'], ['array1', 'array2']],
            ['string1', ['string1']],
        ];
    }

    /**
     * @dataProvider providerTestSetAndGetScope
     */
    public function testSetAndGetScope($params, $result)
    {
        $this->consent->setScopes($params);
        $this->assertEquals($result, $this->consent->getScopes());
    }
}
