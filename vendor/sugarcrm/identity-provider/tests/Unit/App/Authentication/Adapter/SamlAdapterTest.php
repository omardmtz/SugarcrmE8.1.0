<?php

namespace Sugarcrm\IdentityProvider\Tests\Unit\App\Authentication\Adapter;

use Sugarcrm\IdentityProvider\App\Authentication\Adapter\SamlAdapter;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SamlAdapterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UrlGeneratorInterface
     */
    protected $urlGenerator;

    /**
     * @var SamlAdapter
     */
    protected $adapter;

    protected function setUp()
    {
        $this->urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $this->adapter = new SamlAdapter($this->urlGenerator);
    }

    public function testAdaptNoConfig()
    {
        $this->assertEmpty($this->adapter->getConfig(''));
    }

    public function testAdapt()
    {
        $config = \GuzzleHttp\json_encode([
            'idp_sso_url' => 'https://sugarcrm-idmeloper-dev.onelogin.com/trust/saml2/http-post/sso/735046',
            'idp_slo_url' => 'https://sugarcrm-idmeloper-dev.onelogin.com/trust/saml2/http-redirect/slo/735046',
            'idp_entity_id' => 'https://app.onelogin.com/saml/metadata/735046',
            'sp_entity_id' => 'php-saml',
            'x509_cert' => '-----BEGIN CERTIFICATE-----
MIIEFTCCAv2gAwIBAgITVt8w4PTGB4LRNuL1ipqpftGjwjANBgkqhkiG9w0BAQUF
ADBYMQswCQYDVQQGEwJVUzERMA8GA1UECgwIU3VnYXJDUk0xFTATBgNVBAsMDE9u
ZUxvZ2luIElkUDEfMB0GA1UEAwwWT25lTG9naW4gQWNjb3VudCA5OTkzODAeFw0x
NzAxMjkxMTI5MzlaFw0yMjAxMzAxMTI5MzlaMFgxCzAJBgNVBAYTAlVTMREwDwYD
VQQKDAhTdWdhckNSTTEVMBMGA1UECwwMT25lTG9naW4gSWRQMR8wHQYDVQQDDBZP
bmVMb2dpbiBBY2NvdW50IDk5OTM4MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIB
CgKCAQEA1MYPNsLtHGkxsVBK9/6VXnN2rlcGKI9nhkOvI6Ac/rFJoj9BqJJ8TF41
sx1b2QTBGV9Ekxf8H2b/sIM5dY1aGYZd9HziMT4UzbgKWzdxW2Q/7ggWZUf2hTkm
oCTm3ULed6vn73jSkrAlP8TDAuJWY42GtA5YQfjvXedEBL57YnI5azKIjLeAvxva
xJGj6WsxI6jZ8HCON0X5fSvPUlfV0Th9hw3nh7EH3nbw75H/+O0zP1TE06MIw1sR
TNxqJKk5WzvnS08YXBbqj3Hbw9zKE9R+vsN9Oj9hHbE+x7T7oZjxLqSzhGwKw5bq
v6cI2x64rOCRm43p+/233gv4GLHVBQIDAQABo4HXMIHUMAwGA1UdEwEB/wQCMAAw
HQYDVR0OBBYEFKOKXBQVnyGFhgfyl6CvypWqnKJhMIGUBgNVHSMEgYwwgYmAFKOK
XBQVnyGFhgfyl6CvypWqnKJhoVykWjBYMQswCQYDVQQGEwJVUzERMA8GA1UECgwI
U3VnYXJDUk0xFTATBgNVBAsMDE9uZUxvZ2luIElkUDEfMB0GA1UEAwwWT25lTG9n
aW4gQWNjb3VudCA5OTkzOIITVt8w4PTGB4LRNuL1ipqpftGjwjAOBgNVHQ8BAf8E
BAMCB4AwDQYJKoZIhvcNAQEFBQADggEBALb1bXW/39gAsdhz6oxCiWtbYXyS1rDP
dnHECepY/1+zRFIoQPgjcy++3f1nbqHvVCJhh2qULkncBSdvchRkfLAppIf2svpl
WXQyYzQnTfBJWJzDdDpqJuImYsVL5y8jhIE2mJDsaYR1vmmNHHrZ7GO7o1Z86tSw
/2NCl9UGV8hJYsTN800nOwo5SB9TAFjcvoVb2G6GaJdpqI5PPtbc0eBDUEOCyWcr
6s6PmwR+qdbrG7z5jZpKBNjLhGdYrG/c8yXuZsMWsg6OTgs9zIPIL99BxOq5NGgM
YAcpEEeMZZps8zfYfwhodIsttwQO2agEBtEtob5ft9LMMK5kRdIXxzg=
-----END CERTIFICATE-----
',
            'provision_user' => true,
            'same_window' => true,
            'request_signing_pkey' => 'test_pkey',
            'request_signing_cert' => 'test_cert',
            'request_signing_method' => 'RSA_SHA256',
            'sign_authn_request' => false,
            'sign_logout_request' => false,
            'sign_logout_response' => false,
            'validate_request_id' => false,
            'attribute_mapping' => \GuzzleHttp\json_encode(['name_id' => 'email']),
        ]);

        $this->urlGenerator->expects($this->exactly(2))
            ->method('generate')
            ->withConsecutive(
                [$this->equalTo('samlAcs'), $this->isEmpty(), $this->equalTo(UrlGeneratorInterface::ABSOLUTE_URL)],
                [$this->equalTo('samlLogout'), $this->isEmpty(), $this->equalTo(UrlGeneratorInterface::ABSOLUTE_URL)]
            )
            ->willReturnOnConsecutiveCalls('http://idp-php/acs', 'http://idp-php/logout');
        $config = $this->adapter->getConfig($config);
        $this->assertEquals('http://idp-php/acs', $config['sp']['assertionConsumerService']['url']);
        $this->assertEquals('http://idp-php/logout', $config['sp']['singleLogoutService']['url']);
    }
}
