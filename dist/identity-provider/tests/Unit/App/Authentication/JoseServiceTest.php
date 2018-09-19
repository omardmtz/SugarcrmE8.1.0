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

namespace Sugarcrm\IdentityProvider\Tests\Unit\App\Authentication;

use Jose\Factory\JWKFactory;
use Jose\Object\JWKInterface;
use Jose\Object\JWSInterface;
use Sugarcrm\IdentityProvider\App\Authentication\JoseService;
use Sugarcrm\IdentityProvider\Tests\IDMFixturesHelper;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * @coversDefaultClass Sugarcrm\IdentityProvider\App\Authentication\JoseService
 */
class JoseServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var JWKInterface
     */
    protected $publicKey;

    /**
     * @var JWKInterface
     */
    protected $privateKey;

    /**
     * @var JoseService
     */
    protected $joseService;

    /**
     * @var
     */
    protected $userToken;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        $this->publicKey = JWKFactory::createFromCertificate(IDMFixturesHelper::getSpPublicKey());
        $this->privateKey = JWKFactory::createFromKey(IDMFixturesHelper::getSpPrivateKey());
        $this->joseService = new JoseService();
        $this->userToken = $this->createMock(TokenInterface::class);
    }

    /**
     * Checks a valid JWT decode operation.
     *
     * @covers ::decodeJWT
     */
    public function testDecodeValidJWT()
    {
        $jwtString = IDMFixturesHelper::getValidJWT();
        $decodedToken = $this->joseService->decodeJWT($jwtString, $this->publicKey->getAll());

        $this->assertInstanceOf(JWSInterface::class, $decodedToken);
        $this->assertEquals('audTest', $decodedToken->getClaim('aud'));
        $this->assertEquals('1529826678', $decodedToken->getClaim('exp'));
        $this->assertEquals('jtiTest', $decodedToken->getClaim('jti'));
        $this->assertEquals('http://sugarcrm.test/auth', $decodedToken->getClaim('redir'));
        $this->assertEquals(['core', 'hydra'], $decodedToken->getClaim('scp'));
    }

    /**
     * Checks expired JWT.
     *
     * @covers ::decodeJWT
     * @expectedException \Assert\AssertionFailedException
     */
    public function testDecodeExpiredJWT()
    {
        $jwtString = IDMFixturesHelper::getExpiredJWT();
        $this->joseService->decodeJWT($jwtString, $this->publicKey->getAll());
    }

    /**
     * Checks a valid JWT that was signed by another key.
     *
     * @covers ::decodeJWT
     * @expectedException \InvalidArgumentException
     */
    public function testDecodeJWTAnotherKeySigned()
    {
        $jwtString = IDMFixturesHelper::getJWTSignedByAnotherKey();
        $this->joseService->decodeJWT($jwtString, $this->publicKey->getAll());
    }

    /**
     * Checks a creation of JWT.
     *
     * @covers ::createJWT
     */
    public function testCreateJWT()
    {
        $srn = 'srn:cluster:idp:us:0000000001:user:d7af25a6-3ac4-4852-807f-47547606c519';
        $jwtString = IDMFixturesHelper::getValidJWT();
        $this->userToken->method('hasAttribute')->with('srn')->willReturn(true);
        $this->userToken->method('getAttribute')->willReturnMap([
            ['authenticatedMethod', 'PROVIDER_KEY_LOCAL'],
            ['srn', $srn],
        ]);
        $decodedToken = $this->joseService->decodeJWT($jwtString, $this->publicKey->getAll());
        $resultToken = $this->joseService->createJWT($decodedToken, $this->privateKey->getAll(), $this->userToken);
        $decodedResultToken = $this->joseService->decodeJWT($resultToken, $this->publicKey->getAll());

        $this->assertEquals(
            $decodedToken->getSignature(0)->getProtectedHeaders(),
            $decodedResultToken->getSignature(0)->getProtectedHeaders()
        );

        $this->assertEquals($srn, $decodedResultToken->getClaim('sub'));
        $this->assertEquals($decodedToken->getClaim('scp'), $decodedResultToken->getClaim('scp'));
        $this->assertEquals($decodedToken->getClaim('jti'), $decodedResultToken->getClaim('jti'));
        $this->assertEquals($decodedToken->getClaim('aud'), $decodedResultToken->getClaim('aud'));
        $this->assertFalse($decodedResultToken->hasClaim('redir'));
        $this->assertTrue($decodedResultToken->hasClaim('iat'));
        $this->assertTrue($decodedResultToken->hasClaim('exp'));
        $this->assertTrue($decodedResultToken->hasClaim('id_ext'));
        $this->assertTrue($decodedResultToken->hasClaim('at_ext'));
        $this->assertEquals([], $decodedResultToken->getClaim('id_ext'));
        $this->assertEquals([], $decodedResultToken->getClaim('at_ext'));
    }

    /**
     * Checks a creation of JWT without subject.
     *
     * @covers ::createJWT
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Srn not found for user
     */
    public function testCreateJWTWithoutSubject()
    {
        $jwtString = IDMFixturesHelper::getValidJWT();
        $decodedToken = $this->joseService->decodeJWT($jwtString, $this->publicKey->getAll());
        $this->joseService->createJWT($decodedToken, $this->privateKey->getAll(), $this->userToken);
    }
}
